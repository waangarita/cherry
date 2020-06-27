<div class="col-sm-9">

  <?= $this->Flash->render() ?>
  <?php foreach($detailProduct as $product): ?>
    <form method="post">
      <div class="row">
        <div class="col-sm-5">
          <img src="<?= $this->Url->build($product->img) ?>" alt="<?= $product->code ?>" width="100%"/>
        </div>
        <div class="col-sm-7">
          <div class="row">
            <div class="col-sm-12">
              <h5 class="title"><?= $product->type_product ?></h5>
              <h4 class="title"><b><?= $product->product_series?></b></h4>
              <p class="text-gray alt-font">Code : <?= $product->code ?></p>
              <p class="text-gray alt-font">
                <?= $product->models ?>
              </p>
              <p class="text-gray alt-font">
                <?php
                $code_brand= substr($product->code,0,1);
                $code_family= substr($product->code,2,2);
                if ($code_brand == 0 && ($code_family == 00 || $code_family == 01 || $code_family == 04 )) {
                  $shipweigth = 'gr';
                } else {
                  $shipweigth = 'pp';
                }
                ?>
                <?php if ($product->weight !== ''): ?>
                Weight: <?= $product->weight .' '.$shipweigth ?><br>
                <?php endif; ?>

                <?php if($product->presentation !== '' && ($product->per_master > 0 ) ):  ?>
                Master: <?= $product->per_master ?> x <?= $product->presentation ?>
                <?php endif; ?>
              </p>
              <?php if ($auth['role_id'] <> 3 ) : ?>
                <div class="price">
                  <div class="col-md-9 col-xs-6">
                    <h3 class="amount text-primary">$ <?= number_format($product->price_product, 2) ?></h3>
                  </div>
                  <?php if ($product->iscart <> 1 ): ?>
                  <div class="col-md-3 col-xs-6 cant_product">
                    <span class="col-md-6 col-xs-6" style="margin-top:25px;padding-right:7px;text-align:right"> Cant. </span>
                    <div class="col-md-6 col-xs-6" style="margin-top:20px;">
                      <input type="hidden" name="id_product" value="<?= $product->code ?>">
                      <input type="hidden" name="id_user" value="<?= $auth['id'] ?>">
                      <input type="number" required class="form-control" min="1" name="amount" id="amount" onKeyPress="javascript:return solo_numeros ( event )" />
                    </div>
                  </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <?php if ($auth['role_id'] <> 3 ) : ?>

        <div class="row">
          <div class="col-sm-6 col-xs-5"></div>
          <div class="col-sm-6 col-xs-7 text-right">
            <ul class="list list-inline">
              <li><a href="<?= $this->Url->build(['action' => 'products']) ?>" type="button" class="btn btn-gray btn-md round"> <?= __('Continuar comprando') ?></a></li>
              <?php if ($product->iscart <> 1 ): ?>
              <li><button type="submit" class="btn btn-warning btn-md round"><i class="fa fa-shopping-basket mr-5"></i> <?= __('Agregar al Pedido') ?> </button></li>
              <?php else: ?>
              <li> <a href="#" class="btn btn-default btn-md round"><i style="font-size:13pt;" class="fa fa-check-circle"></i> <?= __('Pedido') ?> </a> </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>

        <hr>
      <?php endif; ?>
    </form>
  <?php endforeach; ?>

  <?php if (!empty($references)) : ?>
    <h6 class="title"> <?= __('TAMBIEN LE PUEDE INTERESAR') ?>  </h6>
    <br>
    <div class="row column-3">
      <?php foreach ($references AS $product) : ?>
        <?php if ($product->price_product > 0): ?>
        <div class="col-sm-6 col-md-4">
          <div class="thumbnail store style1">
            <div class="header">
              <?php
              switch ($product->tbl_product['status']) {
                case 'N':
                  $status = __('Nuevo');
                  $label = 'primary';
                break;
                case 'L':
                  $status = __('Oferta');
                  $label = 'warning';
                break;
                case 'D':
                  $status = __('Descuento');
                  $label = 'danger';
                break;
                default:
                  $status = '';
                  $label = '';
                break;
              }
              ?>
              <div class="badges">
                <span class="product-badge top left <?= $label ?>-background text-white semi-circle"><?= $status ?></span>
              </div>
              <a href="<?= $this->Url->build(['action' => 'detail_product', $product->tbl_product['code']]) ?>">
                <figure class="layer">
                  <img src="<?= $this->Url->build($product->tbl_product['img']) ?>" alt="<?= $product->tbl_product['code'] ?>" >
                </figure>
              </a>
            </div>
            <div class="caption">
              <h6 class="regular">
                <a href="<?= $this->Url->build(['action' => 'detail_product', $product->tbl_product['code']]) ?>">
                  <b><?= $product->tbl_product['code'] ?></b>
                  <br><?= $product->tbl_product['type_product'] ?>
                  <br><b><?= strtoupper(substr($product->tbl_product['product_series'],0,22)) ?></b>
                  <br><?= strtoupper(substr($product->tbl_product['models'],0,22)) .'...' ?>
                </a>
              </h6>
              <?php if ($auth['role_id'] <> 3 ) : ?>
                <div class="price">
                  <div class="col-md-6 col-xs-6">
                    <span class="amount text-primary">$ <?= number_format($product->price_product, 2) ?></span>
                  </div>
                  <?php if($product->iscart == 0):  ?>
                  <div class="col-md-6 col-xs-6 cant_product">
                    <span class="col-md-6 col-xs-6">Cant.</span>
                    <div class="col-md-6 col-xs-6">
                      <input type="number" title="Requerido" class="form-control" min="1" name="<?= $product->tbl_product['code'] ?>" id="<?= $product->tbl_product['code'] ?>" onKeyPress="javascript:return solo_numeros ( event )" />
                    </div>
                  </div>
                  <?php endif; ?>
                </div>
                <div id="btn<?= $product->tbl_product['code'] ?>" class="col-md-12 col-xs-12 add_cart_botton">
                  <?php if($product->iscart == 0) {  ?>
                    <a href="#" onclick="add_cart('<?= $product->tbl_product['code'] ?>', <?= $auth['id'] ?>, event)"><i class="fa fa-cart-plus mr-5"></i><?= __('Agregar a Pedido') ?></a>
                    <?php } else {  ?>
                      <a class="text-success"><i class="fa fa-check-circle mr-5"></i> <?= __('add_to_cart') ?> </a>
                      <?php } ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
        <?php endif; ?>
      <?php endforeach; ?>
  <?php endif; ?>
</div>
