<?php

namespace App\Controller;
use Cake\Mailer\Email;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\I18n\I18n;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use App\Model\Entity\TblProduct;


class ApiController extends AppController
{

	public function initialize () {
		parent::initialize();

		// load models
		$this->loadModel('TblProduct');
		$this->loadModel('TblBrand');
		$this->loadModel('TblFamily');
		$this->loadModel('TblSiteConfig');
		$this->loadModel('TblSiteConfigSlide');
		$this->loadModel('TblListPrice');
		$this->loadModel('AdmUser');
		$this->loadModel('TblClient');
		$this->loadModel('TblOrder');
		$this->loadModel('TblDetailOrder');
		$this->loadModel('TblCart');
		$this->loadModel('TblPromotion');
		$this->loadModel('TblPromotionSlide');
		$this->loadModel('TblFaq');
		$this->loadModel('TblCrossReferences');
		$this->loadModel('TblRecoveryPassword');

		// Checking language
		if ($this->request->session()->check('Config.locale')) {
			I18n::locale($this->request->session()->read('Config.locale'));
		}

		Time::setJsonEncodeFormat('yyyy-MM-dd HH:mm');

		// set language from front
		$this->set('language', I18n::locale() == 'es_ES' ? 'ES' : 'EN');
	}


	public function goToLanguage($lan = 'ES') {
		if($this->changeLanguage($lan)) {
			$this->redirect(['controller' =>  'Site', 'action' => 'login']);
		}
	}

	/**
	* getFamilyAndBrand => trae la familia y su marca dependiendo el codigo
	*
	* @param var $familyId code family
	* @return json object family
	*/
	public function getFamilyAndBrand ($familyId) {
		return $this->TblFamily->find()->contain(['TblBrand'])->where(['TblFamily.code' => $familyId ]);
	}

	/**
	* getBrands => Get all brands with partner families
	*
	* @return json object brands with family
	*/
	public function getBrands () {
		$compact = ['success','data'];
		$success = true;

		$data['brands'] = $this->TblBrand->find()
										->select(['code', 'name'])
										->contain(['TblFamily' => function($q) { return $q->select(['code', 'name', 'id_brand']); }]);
		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['brands'];
	}

	/**
	* Retrieve the section  page by slug
	* @param slug or section Name.  slug
	* @return json
	*/
	public function getSlidersBySection ($sectionName) {
		$compact = ['success','data'];
		$success = true;


		if ($sectionName == 'terminos-condiciones') {
			$data = $this->TblSiteConfig->getPageBySection($sectionName);
			debug($data);
		} else {
			$data['home'] = $this->TblSiteConfig->getPageBySection($sectionName);

			$data['sliders'] = $this->TblSiteConfigSlide->getSlidersBySection($sectionName);
		}

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data;
	}

	/**
	* Retrieve the products by status
	* @param status [N,D,L,NA,PM]  N= NUEVO , D = DISPONIBLE , L = LIQUIDACION , NA = NO APLICA , PM = PRECIO MEJORADO
	* @param ic_client  from list price
	* @param Top  if is void  == 100 else top = limit
	* @return json
	*/
	public function getProductsByStatus ($status, $id_client, $id_user, $top = 100) {
		$compact = ['success','data'];
		$success = true;

		$data['products'] = $this->TblProduct->getProductByStatus($status, $id_client, $top, $id_user);
		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['products'];
	}

	/**
	* Retrieve all products
	* @param user or id_client  by list price
	* @return json
	*/
	public function getAllProducts ($id_client, $id_user) {
		$compact= ['success', 'data'];
		$success = true;

		$data['products'] = $this->TblProduct->getAllProducts($id_client, $id_user);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['products'];
	}

	/**
	* getProductsByfamily get products where code is params
	* @param $family => code family to searcg
	* @param $id_client code erp login
	* @param $id_user user login
	* @return json
	*/
	public function getProductsByfamily ($family, $id_client, $id_user) {
		$compact= ['success', 'data'];
		$success = true;

		$data['products'] = $this->TblProduct->getProductsByfamily($family, $id_client, $id_user);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['products'];
	}

	/**
	* @param filter = words to search
	* @param Id Client from list price
	* @return json
	*/
	public function search ($filter, $id_client, $id_user) {
		$compact= ['success', 'data'];
		$success = true;

		$data['products'] = $this->TblProduct->searchProducts($filter, $id_client, $id_user);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['products'];
	}

	/**
	* saveProductCart save product in cart of user
	* @return json
	*/
	public function saveProductCart () {
		$compact = ['success'];
		$success = false;
		try {
			if( $this->request->is('post')) {
				$cart = $this->TblCart->newEntity($this->request->data());
				if($this->TblCart->save($cart)) {
					$success = true;
				}
			}
		} catch (\Exception $e) {
			$this->log($e->getMessage(), "error");
		}

		$this->set(compact($compact));
		$this->set('_serialize', true);
	}

	/**
	* deleteProductCart delete product of cart
	* @param id => code detail cart
	* @return json
	*/
	public function deleteProductCart ($id) {
		$compact = ['success','data'];
		$success = false;

		$cart = $this->TblCart->get($id);
		$code = $cart->id_product;
		$data = $cart->id_user;
		try{
			if( $this->TblCart->delete($cart) ) {
				$success = true;
			}
		} catch (\Exception $e) {
			$this->log($e->getMessage(), "error");
		}

		$this->set(compact($compact));
		$this->set('_serialize', true);
	}

	/**
	* Retrieve the cart products by user
	* @param id_user cart by user
	* @return json
	*/
	public function getCartByUser ($id_user) {
		$compact= ['success', 'data'];
		$success = true;

		$data = $this->TblCart->getCartByUser($id_user);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data;
	}

	/**
  * searchProducts get detail by products
  *
  * @param $id_product => code products
  * @param $id_client => Code company of user login
  * @param $user_id => code user login from get precio Product by list products
  * @return array detail products
  */
	public function getDetailProduct ($id_product, $id_client, $id_user) {
		$compact= ['success', 'data'];
		$success = true;

		$data['product'] = $this->TblProduct->getDetailProduct($id_product, $id_client, $id_user);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['product'];
	}

	/**
  * getCrossReferences => busca los productos asociados
  *
  * @param $id_product => code products
  * @param $id_client => Code company of user login
  * @param $id_user => code user login from get precio Product by list products
  * @return json products
  */
	public function getCrossReferences ($id_product, $id_client, $id_user) {
		$compact= ['success', 'data'];
		$success = true;

		$data['products'] = $this->TblCrossReferences->getCrossReferences($id_product, $id_client, $id_user);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['products'];
	}

	/**
  * getPromotion => get promotions site
  *
  * @return array promotions
  */
	public function getPromotion () {
		$compact= ['success', 'data'];
		$success = true;

		$data['promotion'] = $this->TblPromotion->find()
												->select(['id', 'name'])
												->contain(['TblPromotionSlide']);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['promotion'];
	}

	/**
	* return sliders of the promotion especified
	* @param id promotion for the slide
	* @return json
	*/
	public function getPromotionSlide ($id_promotion) {
		$compact= ['success', 'data'];
		$success = true;

		$data['slide'] = $this->TblPromotionSlide->find()
												->select(['id', 'img_desktop', 'img_mobile', 'cta'])
												->where(['id_promotion' => $id_promotion]);
		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['slide'];
	}

	/**
  * getFaq => get faq site
  *
  * @return json get Faq
  */
	public function getFaq () {
		$compact= ['success', 'data'];
		$success = true;

		$data['faq'] = $this->TblFaq->find()->select(['id', 'question', 'answer']);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['faq'];
	}

	/**
	* Retrieve the info of user especifieds
	* @param id user especified
	* @return json
	*/
	public function getInfoUser ($id_user) {
		$compact= ['success', 'data'];
		$success = true;

		$data['user'] = $this->AdmUser->find()
		->contain(['TblClient' => function ($q) {
			return $q->select(['name']);
		}])
		->select(['id', 'id_client', 'email', 'password', 'first_name', 'last_name', 'appoinment', 'phone1', 'phone2'])
		->where(['id' => $id_user, 'status' => 'ACTIVE' ]);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['user'];
	}

	/**
	* Retrieve update info of user
	* @param id user
	* @return json
	*/
	public function updateInfoUser ($id_user) {
		$compact = ['success'];
		$success = false;
		if( $this->request->is('post') || $this->request->is('put')) {
			$user = $this->AdmUser->newEntity($this->request->data());
			if($this->AdmUser->save($user)) {
				$success = true;
			} else {
				$success = false;
			}
		}
		$this->set(compact($compact));
		$this->set('_serialize', true);
	}

	/**
	* generateOrder generate new order and send email to administrators
	* @param id user especified
	* @return json true or false
	*/
	public function generateOrder () {
		$compact = ['success'];
		$success = false;

		try {
			$order = $this->TblOrder->newEntity();
			$order->id_user = $this->request->data['id_user'];
			$order->status = 'PENDING';
			$order->shipping_way = $this->request->data['shipping_way'];
			$order->purchase_order = $this->request->data['purchase_order'];
			$order->shipping_name = $this->request->data['shipping_name'];
			$order->notes = $this->request->data['notes'];

			if ($this->TblOrder->save($order)) {
				$total = count($this->request->data['id_product']);
				for ($i=0; $i<$total; $i++) {
					$detail = $this->TblDetailOrder->newEntity();
					$detail->id_order = $order->id;
					$detail->id_product = $this->request->data['id_product'][$i];
					$detail->amount = $this->request->data['amount'][$i];
					if ($this->TblDetailOrder->save($detail)) {
						$this->log(sprintf('Order de perdido [%s] creada',  $order->id), 'info');
					}else {
						$this->log('error de creacion de detalle de orden de pedido', 'error');
						$this->log($detail->errors(), 'debug', get_class($this));
					}
				}

				$this->TblCart->deleteAll(['id_user' => $order->id_user]);
				$success = true;

				// Send Notification
					$administrators = $this->AdmUser->find()->where(['role_id' => 4]); // Search users administrators

					foreach ($administrators as $admin) {
						$info['shipping_way'] = $order->shipping_way;
						$info['purchase_order'] = $order->purchase_order;
						$info['shipping_name'] = $order->shipping_name;
						$info['notes'] = $order->notes;
						$info['user'] = $this->AdmUser->find()->contain(['TblClient'])->where(['id' => $this->request->data['id_user'] ]);
						$info['detail'] = $this->TblOrder->detailOrder($this->request->data['id_user'], $order->id);

						foreach ($info['user'] AS $user) {
							$client_id = $user->id_client;
							$nameClient = $user->tbl_client->name;
							$buyer = $user->first_name .' '.$user->last_name;
							$emailBuyer = $user->email;
							$phoneBuyer = $user->phone1;
							$phone2Buyer = $user->phone2;
							$listPrice = $user->tbl_client->id_type_list_price;
						}

						$codeOrder = $this->TblOrder->createCodeOrder($order->id, $this->request->data['id_user'],  $client_id);

						// CREATE EXCEL
						$namefile = $codeOrder .date('Y-m-d h:i:s').'.xlsx';
						$spreadsheet = new Spreadsheet();
						$sheet = $spreadsheet->getActiveSheet();
						$sheet->setCellValue('A1', 'Detalle Pedido');
						$sheet->setCellValue('A2', 'Fecha pedido: '.date('m-d-Y h:i'));
						$sheet->setCellValue('A3', 'Orden de compra: '.$order->purchase_order);

						$sheet->setCellValue('A5', 'Comprador');
						$sheet->setCellValue('B5', 'Detalle');

						$sheet->setCellValue('A6', 'Codigo ERP: '.$client_id);
						$sheet->setCellValue('A7', 'Empresa: '.$nameClient);
						$sheet->setCellValue('A8', 'Comprador: '.$buyer);
						$sheet->setCellValue('A9', 'Email: '.$emailBuyer);
						$sheet->setCellValue('A10', 'Telefono: '.$phoneBuyer);
						$sheet->setCellValue('A11', 'Telefono2: '.$phone2Buyer);

						$sheet->setCellValue('B6', 'Pedido #: '.$codeOrder);
						$sheet->setCellValue('B7', 'Items: '.count($info['detail']));
						
						$sheet->setCellValue('B9', 'Lista Precios Aplicada: '.$listPrice);
						$sheet->setCellValue('B10', 'Forma envio: '.$order->shipping_way);
						$sheet->setCellValue('B11', 'Transportadora: '.$order->shipping_name);
						$sheet->setCellValue('B12', 'Notas adicionales: '.$order->notes);

						$sheet->setCellValue('A14', 'Codigo');
						$sheet->setCellValue('B14', 'Producto');
						$sheet->setCellValue('C14', 'Series');
						$sheet->setCellValue('D14', 'Modelos');
						$sheet->setCellValue('E14', 'Precio');
						$sheet->setCellValue('F14', 'Cantidad');
						$sheet->setCellValue('G14', 'Total');

						$row = 15;
						$sum = 0;

						foreach ($info['detail'] as $detail) {
							$sheet->setCellValue('A'.$row, $detail['code']);
							$sheet->setCellValue('B'.$row, $detail['type_product']);
							$sheet->setCellValue('C'.$row, $detail['product_series']);
							$sheet->setCellValue('D'.$row, $detail['models']);
							$sheet->setCellValue('E'.$row, number_format($detail['price_product'],2));
							$sheet->setCellValue('F'.$row, $detail['amount']);
							$sheet->setCellValue('G'.$row, number_format($detail['price_product'] * $detail['amount'], 2));
							$sum = $sum + number_format($detail['price_product'] * $detail['amount'], 2);
							$row++;
						}

						$sheet->setCellValue('G'.$row, $sum);
						$sheet->setCellValue('B8', 'Total Pedido: $'.$sum);


						$writer = new Xlsx($spreadsheet);
						$writer->save('files'.DS.'order_excel'.DS.$namefile);
						// END CREATE EXCEL

						$info['code'] = $codeOrder;

						$from = 'pedidos-cherry@'; //'no-reply@cherry.com';
						$to = 'camilo.parada@'; //$admin->email;
						$subject = 'Pedido # '.$codeOrder.' cherry';
						$template = 'orders';
						$attachments = WWW_ROOT.'files'.DS.'order_excel'.DS.$namefile;
						$this->log($attachments, 'info');

						$this->sendEmail($from, $to, $subject, $template, $info, $attachments);
					}
				// End Send Notifications
			} else {
				$this->log('error al crear orden de pedido', 'error');
				$this->log($order->errors(), 'error', get_class($this));
			}
		} catch (\Exception $e) {
			$this->log($e->getMessage(), "error");
		}

		return $success;
		$this->set(compact($compact));
		$this->set('_serialize', true);
	}

	/**
	* Retrieve all orders by user
	* @param id user
	* @return json
	*/
	public function getHistoryOrders ($id_user) {
		$compact = ['success','data'];
		$success = true;

		$data['orders'] = $this->TblOrder->find()->where(['id_user' => $id_user])->order(['created DESC']);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['orders'];
	}

	/**
	* Retrieve detail of the order especified
	* @param id order
	* @return json
	*/
	public function getDetailOrder ($idUser, $idOrder, $top) {
		$compact = ['success','data'];
		$success = true;

		$data['detail'] = $this->TblOrder->detailOrder($idUser, $idOrder, $top);

		$this->set(compact($compact));
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data['detail'];
	}


	/**
	* get code  of the company
	* @param $company name company
	* @return code company
	*/
	public function getCompany ($company) {
		$data = '';
		try{
			$result = $this->TblClient->getCompany($company);
			$data = $result;
		}
		catch(\Exception $e){
			$this->log($e->getMessage(), "error");
		}

		if(!$this->RequestHandler->prefers('json'))
		return $data;
	}


	/**
	* @param amount ,  new amount to item cart
	* @return id,  identify to item cart
	*/
	public function updateAmount ($amount, $id) {
		$compact = ['success'];
		$success = false;

		if( $this->request->is('post') || $this->request->is('put')) {
			$cart = $this->TblCart->get($id);
			$cart->amount = $amount;
			if ($this->TblCart->save($cart)) {
				$success = true;
			} else {
				$success = false;
			}
		}
		$this->set(compact($compact));
		$this->set('_serialize', true);
	}

	/**
	* Recovery password to user
	* @param email , the email user
	*/
	public function recoveryPassword ($email) {
		$user = $this->AdmUser->find()->where(['email' => $email]);

		foreach ($user as $u) {
			$idUser = $u->id;
			$first_name = $u->first_name;
			$last_name = $u->last_name;
		}

		$from = 'no-reply@cherry.com';
		$to = $email;
		$subject = 'Recuperacion password cherry';
		$template = 'recovery';

		// Generate Password temparoraty
		$password = $this->AdmUser->generateNewPassword($idUser);

		// save register in TBL_RECOVERY_PASSWORD
		$register = $this->TblRecoveryPassword->newEntity();
		$register->user_id = $idUser;
		$register->password = $password;
		$register->active = 1;

		if ($this->TblRecoveryPassword->save($register)) {
				$info['info'] = array(
											'password' => $password,
											'nombres' => $first_name,
											'apellidos' => $last_name
										);

				$this->sendEmail($from, $to, $subject, $template, $info);
				return true;
		} else {
			return false;
		}
	}

	/**
	* verifyIsRecovery , saber si el usuario esta reseteando la password
	* @param $idUser , code user recovery password
	*/
	public function verifyIsRecovery ($idUser) {
		return $this->TblRecoveryPassword->exists(['user_id' => $idUser, 'active' => 1 ]);
	}

	/**
  * getListProductByUser Catalogo de productos por usuario para el front
  *
  * @param $id_client => Code company of user login
  * @return array products
  */
	public function getListProductByUser ($client_id) {
		$data = $this->TblProduct->getListProductByUser($client_id);

		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return $data;
	}

	/**
  * changeLanguage Cambiar idioma del front
  *
  * @param $lan => EN:Ingles or ES:Espanol
  * @return array products
  */
	public function changeLanguage ($lan) {
		if ($lan == 'EN') {
			$locale = 'en_US';
		} else {
			$locale = 'es_ES';
		}

		$this->request->session()->write('Config.locale', $locale);

		$this->set('success',true);
		$this->set('_serialize', true);

		if(!$this->RequestHandler->prefers('json'))
		return true;
	}
}
