<?php
namespace App\Controller;
use mPDF;
use Cake\I18n\Time;
use Cake\Routing\Router;

class SiteController extends ApiController
{
	public function initialize () {
		parent::initialize();

		// load models
		$this->loadModel('AdmUser');
		$this->loadModel('TblLogLogin');
		$this->loadModel('TblOrder');
		$this->loadModel('TblCountry');

		// load components
		$this->loadComponent('Auth', [
			'loginAction' => ['controller' => 'Site', 'action' => 'login'],
			'loginRedirect' => ['controller' => 'Site', 'action' => 'index'],
			'logoutRedirect' => ['controller' => 'Site', 'action' => 'login'],
			'authenticate' => [
				'Form' => [
					'userModel' => 'AdmUser',
					'fields' => ['username' => 'email', 'password' => 'password']
				]
			],
			'storage' => 'Session'
		]);

		// allow just de signup page
		$this->Auth->allow(['signup']);

		// allow just de forgotPassword page
		$this->Auth->allow(['forgotPassword']);

		// allow just de forgotPassword page
		$this->Auth->allow(['changePassword']);

		//set the site layout as default for all the views
		$this->viewBuilder()->layout('site');

		// get Footer banner
		$components = $this->getSlidersBySection('index');
		$this->set(compact('components'));

		//global vars
		$auth = $this->Auth->user();
		$this->set('auth', $auth);
	}

	/**
	* logLogin => save register of login user
	*
	* @param var $id_user code user login
	* @return True or False
	*/
	public function logLogin ($id_user) {
		$log = $this->TblLogLogin->newEntity();
		$log->id_user = $id_user;
		if($this->TblLogLogin->save($log)) {
			return true;
		}else{
			return false;
		}
	}

	public function index () {
		$user = $this->Auth->user();
		$this->viewBuilder()->layout('site_dark');

		$brands = $this->getBrands();
		$new_products = $this->getProductsByStatus('N',$user['id_client'],$user['id']);
		$prices_best = $this->getProductsByStatus('PM',$user['id_client'],$user['id'],4);
		$offers = $this->getProductsByStatus('D',$user['id_client'],$user['id'],8);
		
		debug($offers);
		debug($prices_best);
		debug($new_products);

		$this->set(compact('brands'));
		$this->set(compact('new_products'));
		$this->set(compact('prices_best'));
		$this->set(compact('offers'));
	}

	public function login () {
		$this->viewBuilder()->layout('site_auth');

		if( $this->request->is('post') ) {
			$user = $this->Auth->identify();
			if( $user ) {
				if($user['status'] == 'ACTIVE') {
					// Verify if is first login
					$querylog = $this->TblLogLogin->find();
					$querylog->select(['cuantos' => $querylog->func()->count('id_user')])->where(['id_user' => $user['id']]);

					foreach ($querylog AS $item) {
						$countLogin = $item->cuantos;
					}

					// if countLogin is zero , redirect to change password
					if ($countLogin == 0) {
						return $this->redirect(['action' => 'change_password', $user['id'] ]);
					} else {
						// verify if is recovery password
						if ($this->verifyIsRecovery($user['id'])) {
							return $this->redirect(['action' => 'change_password', $user['id'] ]);
						} else {
							if (isset($this->request->data['remember'])) {
								$hour = time() + 3600 * 24 * 30;
								setcookie('email', $this->request->data['email'], $hour);
								setcookie('password', $this->request->data['password'], $hour);
							}

							$loguser = $this->logLogin($user['id']);
							$this->Auth->setUser($user);
							return $this->redirect($this->Auth->redirectUrl());
						}
					}

				} else {
					$this->Flash->error(__('Usuario inactivo'));
				}
			}
			$this->Flash->error(__('Usuario o contraseña incorrectos, intente nuevamente!'));
		}
	}

	public function changePassword ($idUser) {
		$this->viewBuilder()->layout('site_auth');

		$user = $this->AdmUser->get($idUser);

		if ($this->request->is('post')) {
			$user->password = $this->request->data['password'];
			if($this->AdmUser->save($user)) {
				// Log , start session
				$loguser = $this->logLogin($idUser);
				// Update Recovery Password
				$this->TblRecoveryPassword->updateAll(array('active' => 0), array('user_id' => $idUser));

				// flash wicth success operation
				$this->Flash->success_site(__('Contraseña cambiada correctamente, por favor ingrese nuevamente'));
			} else {
				$this->Flash->error(__('Hubo un problema con el cambio de contraseña, por favor intente de nuevo'));
			}
			return $this->redirect(['action' => 'login']);
		}
	}

	public function signup () {
		$this->viewBuilder()->layout('site_auth');

		$countries = $this->TblCountry->find();
		
		$this->set('countries', $countries);

		if( $this->request->is('post') ) {
			try {

				if ( $this->AdmUser->exists(['email' => $this->request->data('email'), 'status' => 'ACTIVE']) ) {
					$this->Flash->warning(__('Ya existe un usuario registrado con este email, no es posible crearlo'));
				} else {

					if ($this->AdmUser->exists(['email' => $this->request->data('email'), 'status' => 'DELETED'])) {
						$signup = $this->AdmUser->find()->where(['email' => $this->request->data('email')])->first(); // util ->first() for that return the object correctly
						$this->AdmUser->patchEntity($signup, $this->request->data());
					} else {
						$signup = $this->AdmUser->newEntity($this->request->data());
					}

					$signup->last_login = date('Y-m-d h:d:s');
					$signup->role_id = 2;
					$signup->status = 'STANDBY';
					$signup->id_client = $this->getCompany($this->request->data('company'));
					
					if ($this->AdmUser->save($signup)) {
						$this->Flash->success_site(__('Gracias por su interés. Su solicitud será atendida y nos pondremos en contacto con usted próximamente'));

						// Send notification to administrator
						$administrators = $this->AdmUser->find()->where(['role_id' => 4]); // Search users administrators

						foreach ($administrators as $admin) {
							$from = 'no-reply@cherry.com';
							$to = $admin->email;
							$subject = __('Nueva solicitud de usuario - cherry');
							$template = 'new_user';

							$info['info'] = array(
								'id' => $signup->id,
								'compania' => $this->request->data['company'],
								'nombres' => $this->request->data['first_name'],
								'apellidos' => $this->request->data['last_name'],
							);

							$this->sendEmail($from, $to, $subject, $template, $info);
						}
						// End Notification

					} else {
						$this->log($signup, 'debug');
						$this->Flash->error(__('Error al enviar solicitud por favor intente de nuevo'));
					}
				}
			} catch (\Exception $e){
				$this->log($e->getMessage(), "error");
			}
		}
	}

	public function deleteCart ($id) {
		try {
			$this->deleteProductCart($id);
			$this->Flash->success_site(__('Producto eliminado correctamente de la lista de pedido'));

		} catch (\Exception $e) {
			$this->log($e->getMessage(), "error");
		}
		$this->redirect(['action' => 'cart']);
	}

	public function logout () {
		return $this->redirect($this->Auth->logout());
	}

	public function promotion () {
		$breadcrumbs = '<li class="active">'.__('Promociones').'</li>';
		$user = $this->Auth->user();
		$this->viewBuilder()->layout('site');

		$brands = $this->getBrands();
		$prices_best = $this->getProductsByStatus('PM', $user['id_client'], $user['id'], 4);
		$promotions = $this->getPromotion();
		$cart = $this->getCartByUser($user['id']);

		$this->set(compact('brands'));
		$this->set(compact('prices_best'));
		$this->set(compact('promotions'));
		$this->set(compact('cart'));
		$this->set(compact('breadcrumbs'));
	}

	public function faq () {
		$breadcrumbs = '<li class="active">'.__('Faq').'</li>';
		$user = $this->Auth->user();
		$this->viewBuilder()->layout('site');

		$brands = $this->getBrands();
		$prices_best = $this->getProductsByStatus('PM', $user['id_client'], $user['id'], 4);
		$faqs = $this->getFaq();
		$cart = $this->getCartByUser($user['id']);

		$this->set(compact('brands'));
		$this->set(compact('prices_best'));
		$this->set(compact('faqs'));
		$this->set(compact('cart'));
		$this->set(compact('breadcrumbs'));
	}

	public function cart () {
		$breadcrumbs = '<li><a href="/site/my_account">'.__('Mi cuenta').'</a></li>
						<li class="active">'.__('Pedido').'</li>';
		$this->viewBuilder()->layout('site');
		$user = $this->Auth->user();

		if ($this->request->is('post')) {
			$order = $this->generateOrder();
			if($order) {
				$this->redirect(['action' => 'historyOrders']);
				$this->Flash->success_site(__('Gracias por su pedido, estamos trabajando en él'));
			} else {
				$this->Flash->error(__('Error al crear orden, por favor valide informacion e intente de nuevo recuerde que las cantidades son necesarias'));
			}
		}

		$brands = $this->getBrands();
		$prices_best = $this->getProductsByStatus('PM', $user['id_client'], $user['id'], 4);
		$cart = $this->getCartByUser($user['id']);

		$this->set('isCart', True);
		$this->set(compact('brands', 'prices_best', 'cart', 'breadcrumbs'));
	}

	public function products () {
		$breadcrumbs = '<li class="active">'.__('Productos').'</li>';
		$user = $this->Auth->user();
		$this->viewBuilder()->layout('site');

		if ($this->request->query('family')) {
			$family = trim($this->request->query('family'));
			$brandAndFamily = $this->getFamilyAndBrand($family);
			$products = $this->getProductsByfamily($family, $user['id_client'], $user['id']);

			foreach ($brandAndFamily as $item) {
				$search = $item->tbl_brand->name  .' - '.$item->name;
				$breadcrumbs = '<li> <a href="/site/products">'.__('Productos').'</a> </li>
								<li class="active">'.$item->tbl_brand->name.'</li>
								<li class="active">'.$item->name.'</li>';
			}

			$this->set(compact('search'));
		} elseif ($this->request->query('search')) {
			$search = trim($this->request->query('search'));
			$products = $this->search($search, $user['id_client'], $user['id']);
			$this->set(compact('search'));
		} else {
			$products = $this->getAllProducts($user['id_client'], $user['id']);
		}

		$brands = $this->getBrands();
		$prices_best = $this->getProductsByStatus('PM', $user['id_client'], $user['id'], 4);
		$cart = $this->getCartByUser($user['id']);

		$this->set(compact('brands', 'prices_best', 'cart', 'breadcrumbs'));
		$this->set('products', $this->Paginator->paginate($products, ['limit' => 12]));
	}

	public function detailProduct ($id_product) {
		$breadcrumbs = '<li class="active">'.__('Detalle Producto').'</li>';
		$user = $this->Auth->user();
		$this->viewBuilder()->layout('site');

		if($this->request->is('post')) {
			$this->saveProductCart();

			$successMsg = sprintf(__("Se han agregado a su pedido %s  productos de esta referencia"), $this->request->data['amount']);
			$this->log(sprintf("%s by '%s'", $successMsg, $user['email']), 'info');
			$this->Flash->success_site($successMsg);
		}

		$brands = $this->getBrands();
		$detailProduct = $this->getDetailProduct($id_product, $user['id_client'], $user['id']);
		$prices_best = $this->getProductsByStatus('PM', $user['id_client'], $user['id'], 4);
		$references = $this->getCrossReferences($id_product, $user['id_client'], $user['id']);
		$cart = $this->getCartByUser($user['id']);

		$this->set(compact('brands', 'prices_best', 'detailProduct', 'references', 'cart', 'breadcrumbs'));
	}

	public function myAccount () {
		$breadcrumbs = '<li class="active">'.__('Mi cuenta').'</li>';
		$user_log = $this->Auth->user();
		$user = $this->AdmUser->get($user_log['id'],['contain' => 'TblCountry']);
		$countries = $this->TblCountry->find();
		$this->viewBuilder()->layout('site_account');

		if ($this->request->is('post')) {
			$user->first_name = $this->request->data['first_name'];
			$user->last_name = $this->request->data['last_name'];
			$user->appoinment = $this->request->data['appoinment'];
			$user->phone1 = $this->request->data['phone1'];
			$user->phone2 = $this->request->data['phone2'];
			if ( !empty($this->request->data['password']) ) {
				$user->password = $this->request->data['password'];
			}

			if ( $this->AdmUser->save($user) ) {
				$successMsg = sprintf( __("Se actualizo con exito los datos de  '%s %s', por favor ingrese de nuevo para visualizar los cambios"), $user->first_name, $user->last_name);
				$this->log(sprintf("%s by '%s'", $successMsg, $user['email']), 'info');
				$this->Flash->success_site($successMsg);
				return $this->redirect($this->Auth->logout());
			} else {
				$this->Flash->error(__('Error al actualizar datos, por favor valide informacion'));
			}
		}

		$this->set(compact('breadcrumbs', 'user', 'countries'));
	}

	public function historyOrders () {
		$breadcrumbs = '<li class="active">'.__('Historial Ordenes').'</li>';
		$user = $this->Auth->user();
		$this->viewBuilder()->layout('site_account');
		$order = $this->TblOrder;

		$history = $this->getHistoryOrders($user['id']);

		$this->set(compact('history'));
		$this->set(compact('order'));
		$this->set(compact('breadcrumbs'));
	}

	public function detailOrder ($idOrder) {
		$breadcrumbs = '<li><a href="/site/history-orders">'.__('Historial Ordenes').'</a></li>
						<li class="active">'.__('Detalle Orden').'</li>';
		//layout site_account for my profile or my account
		$user = $this->Auth->user();
		$this->viewBuilder()->layout('site_account');

		// Duplicate Order , save to cart
		if ($this->request->is('post')) {
			$cuantos = count($this->request->data['id_product']);
			$request =  $this->request->data;
			for ($i=0; $i<$cuantos; $i++) {
				$this->request->data['id_product'] = $request['id_product'][$i];
				$this->request->data['amount'] = $request['amount'][$i];
				$this->request->data['id_user'] = $user['id'];
				$this->saveProductCart();
			}
			// redirect to cart , detail order
			$this->redirect(['action' => 'cart']);
		}

		$detail = $this->getDetailOrder($user['id'], $idOrder, $top=0);
		$codeOrder = $this->TblOrder->createCodeOrder($idOrder, $user['id'], $user['id_client']);
		$this->set(compact('detail'));
		$this->set('numberOrder', $codeOrder);
		$this->set('idOrder', $idOrder);
		$this->set(compact('breadcrumbs'));
	}

	public function forgotPassword () {
		$this->viewBuilder()->layout('site_auth');

		if ($this->request->is('post')) {
			$email = $this->request->data['email'];
			if ($this->recoveryPassword($email)) {
				$this->Flash->success_site(__('Enviamos un nuevo password a su correo.'));
			} else {
				$this->Flash->error(__('Hubo un error al intentar enviar correo por favor, intente de nuevo'));
			}
		}
	}

	public function getListProducts () {
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="list_products.csv";');
		$user = $this->Auth->user();
		$products = $this->getListProductByUser($user['id_client']);

		$lista = [];
		$lista[] = array('Code', 'type_product', 'family/line', 'product_series', 'models', 'presentation', 'per_master', 'weight', 'Price');

		foreach ($products AS $value) {
			$lista[] = array(
				$value['code'],
				$value['type_product'],
				$value['family'],
				$value['product_series'],
				$value['models'],
				$value['presentation'],
				$value['per_master'],
				$value['weight'],
				number_format($value['price_product'],2)
			);
		}

		$fp = fopen('php://output', 'w');

		foreach ($lista as $campos) {
			fputcsv($fp, $campos);
		}
		// fclose($fp);
		fpassthru($fp);
		die;
	}

	public function termsConditions () {
		$breadcrumbs = '<li class="active">'.__('Terminos Condiciones').'</li>';
		$user = $this->Auth->user();
		$this->viewBuilder()->layout('site');

		$brands = $this->getBrands();
		$prices_best = $this->getProductsByStatus('PM', $user['id_client'], $user['id'], 4);
		$terms = $this->getSlidersBySection('terminos-condiciones');
		$cart = $this->getCartByUser($user['id']);
		$this->log($terms, 'debug');

		$this->set(compact('brands'));
		$this->set(compact('prices_best'));
		$this->set(compact('terms'));
		$this->set(compact('cart'));
		$this->set(compact('breadcrumbs'));
	}

	public function generatePdfOrder($idOrder) {
		$mpdf = new Mpdf();
		$user = $this->Auth->user();
		$detail = $this->getDetailOrder($user['id'], $idOrder, $top=0);
		
		$total_pedido =0;
		$code = $this->TblOrder->createCodeOrder($idOrder, $user['id'], $user['id_client']);

		$first_name = $user['first_name'];
		$last_name = $user['last_name'];
		$email = $user['email'];;
		$telefono = $user['phone1'];
		$telefono2 = $user['phone2'];
		$code_erp = $user['id_client'];
		$id_user = $user['id'];
		

		foreach ($detail as $det):
			$total_pedido = $total_pedido + (number_format($det['price_product'] * $det['amount'], 2));
		endforeach;

		$date = new \DateTime($detail[0]['created']);

		$logo = 'http://cherryext.miro.beecloud.me/images/cherry-logo.png';
		$html='<body style="font-family: FreeSans;">
		<div>
			<img src="'.$logo.'" width="220px">
		</div>
		<hr>
		<br>
		<div>
			<h2> '.__("Detalle pedido").' </h2>
			<h4> '.__("Fecha pedido").' : '.$date->format('m-d-Y H:i') .' &nbsp;&nbsp; | &nbsp;&nbsp; '.__("Pedido").'#: '. $code.' </h4>
		</div>
		<div>
			<table width="100%" style="border: 1px solid #999;border-collapse: collapse;">
			<thead>
				<tr>
					<th style="border: 1px solid #999;text-align: center;padding: 0.5rem;" width="50%">'.__("Comprador").'</th>
					<th style="border: 1px solid #999;text-align: center;padding: 0.5rem;" width="50%">'.__("Detalle").'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Codigo ERP").':</b> '. $code_erp .' </td>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Pedido").'#: </b> '. $code .' </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Comprador").':</b> '. $first_name .' '. $last_name .' </td>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>Items: </b> '. count($detail) .'  </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>Email:</b> '. $email .' </td>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Total pedido").':</b> $'. number_format($total_pedido,2) .'  </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Telefono").':</b> '. $telefono .' </td>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Forma envio").':</b> '.$detail[0]['shipping_way'].'  </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Telefono").' 2:</b> '. $telefono2 .' </td>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Tranportadora").':</b> '.$detail[0]['shipping_name'].' </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"></td>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Notas adicionales").':</b> '.$detail[0]['notes'].' </td>
				</tr>
			</tbody>
			</table>
		</div>
		<br>
		<div>
			<table  width="100%" style="border: 1px solid #999;border-collapse: collapse;" >
			<thead>
				<tr>
				<th style="border: 1px solid #999;text-align: center;padding: 0.2rem;" colspan="2" width="50%">'.__("Producto").'</th>
				<th style="border: 1px solid #999;text-align: center;padding: 0.2rem;" width="20%">'.__("Precio").'</th>
				<th style="border: 1px solid #999;text-align: center;padding: 0.2rem;" width="80px">'.__("Cantidad").'</th>
				<th style="border: 1px solid #999;text-align: center;padding: 0.2rem;" colspan="20%">'.__("Total").'</th>
				</tr>
			</thead>
			<tbody> ';
				foreach ($detail as $d):
					$html .='<tr>
						<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;">
						 <img src="/images/products/'.$d['code'].'.jpg" width="70px" >
						</td>
						<td style="border: 1px solid #999;text-align: left;padding: 0.2rem;">
							<b style="font-size:10pt;">'.__("Codigo").': '. $d['code'] .'</b>
							<p><b>'.__("Tipo producto").':</b> '. $d['type_product'] .'</p>
							<p><b>'.__("Series").':</b> '. $d['product_series'] .' </p>
							<p><b>'.__("Modelos").':</b> '. $d['models'] .' </p>
						</td>
						<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;font-size:10pt;font-weight:bold;">
							$'. number_format($d['price_product'],2) .'</td>
						<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;font-size:10pt;font-weight:bold;">
							'. $d['amount'] .'</td>
						<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;font-size:10pt;font-weight:bold;">
							$'. number_format($d['price_product'] * $d['amount'], 2) .'
						</td>
					</tr>';
				endforeach; 

			$html .='</tbody>
			<tfoot>
				<tr>
				<td colspan="4"></td>
				<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;font-size:12pt;font-weight:bold;">TOTAL &nbsp;&nbsp; $'. number_format($total_pedido,2) .'</td>
				</tr>
			</tfoot>
			</table>
		</div>
		</body>';

		$mpdf->WriteHTML($html);
		$mpdf->Output();
		exit;
		
	}

	public function generatePdfCart() {
		$mpdf = new Mpdf();
		$user = $this->Auth->user();
		$cart = $this->getCartByUser($user['id']);
		
		$total_pedido =0;

		$first_name = $user['first_name'];
		$last_name = $user['last_name'];
		$email = $user['email'];;
		$telefono = $user['phone1'];
		$telefono2 = $user['phone2'];
		$code_erp = $user['id_client'];
		

		foreach ($cart as $car):
			$total_pedido = $total_pedido + (number_format($car['price'] * $car['amount'], 2));
		endforeach;


		$logo = 'http://cherryext.miro.beecloud.me/images/cherry-logo.png';
		$html='<body style="font-family: FreeSans;">
		<div>
			<img src="'.$logo.'" width="220px">
		</div>
		<hr>
		<br>
		<div>
			<h2> '.__("Detalle pedido").' </h2>
			<h4> '.__("Fecha").' : '. date('m-d-Y H:i') .' </h4>
		</div>
		<div>
			<table width="100%" style="border: 1px solid #999;border-collapse: collapse;">
			<thead>
				<tr>
					<th style="border: 1px solid #999;text-align: center;padding: 0.5rem;" width="50%">'.__("Comprador").'</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Codigo ERP").':</b> '. $code_erp .' </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Comprador").':</b> '. $first_name .' '. $last_name .' </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>Email:</b> '. $email .' </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Telefono").':</b> '. $telefono .' </td>
				</tr>
				<tr>
					<td style="padding: 0.2rem;border-right:1px solid #999;"> <b>'.__("Telefono").' 2:</b> '. $telefono2 .' </td>
				</tr>
			</tbody>
			</table>
		</div>
		<br>
		<div>
			<table  width="100%" style="border: 1px solid #999;border-collapse: collapse;" >
			<thead>
				<tr>
				<th style="border: 1px solid #999;text-align: center;padding: 0.2rem;" colspan="2" width="50%">'.__("Producto").'</th>
				<th style="border: 1px solid #999;text-align: center;padding: 0.2rem;" width="20%">'.__("Precio").'</th>
				<th style="border: 1px solid #999;text-align: center;padding: 0.2rem;" width="80px">'.__("Cantidad").'</th>
				<th style="border: 1px solid #999;text-align: center;padding: 0.2rem;" colspan="20%">'.__("Total").'</th>
				</tr>
			</thead>
			<tbody> ';
				foreach ($cart as $d):
					$html .='<tr>
						<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;">
						 <img src="/images/products/'.$d['tbl_product']['code'].'.jpg" width="70px" >
						</td>
						<td style="border: 1px solid #999;text-align: left;padding: 0.2rem;">
							<b style="font-size:10pt;">'.__("Codigo").': '. $d['tbl_product']['code'] .'</b>
							<p><b>'.__("Tipo producto").':</b> '. $d['tbl_product']['type_product'] .'</p>
							<p><b>'.__("Series").':</b> '. $d['tbl_product']['product_series'] .' </p>
							<p><b>'.__("Modelos").':</b> '. $d['tbl_product']['models'] .' </p>
						</td>
						<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;font-size:10pt;font-weight:bold;">
							$'. number_format($d['price'],2) .'</td>
						<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;font-size:10pt;font-weight:bold;">
							'. $d['amount'] .'</td>
						<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;font-size:10pt;font-weight:bold;">
							$'. number_format($d['price'] * $d['amount'], 2) .'
						</td>
					</tr>';
				endforeach; 

			$html .='</tbody>
			<tfoot>
				<tr>
				<td colspan="4"></td>
				<td style="border: 1px solid #999;text-align: center;padding: 0.2rem;font-size:12pt;font-weight:bold;">TOTAL &nbsp;&nbsp; $'. number_format($total_pedido,2) .'</td>
				</tr>
			</tfoot>
			</table>
		</div>
		</body>';

		$mpdf->WriteHTML($html);
		$mpdf->Output();
		exit;
	}
}
?>