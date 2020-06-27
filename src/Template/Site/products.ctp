<div class="col-sm-9">
  <?php if( count($products) == 0): ?>
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3 text-center ">
        <h1 id="no-found" class="text-warning alt-font" >Oops!</h1>
        <h2> '<?= $search ?>' <br> <?= __('No Encontrado') ?> </h2>
        <p class="lead"><?= __('No hay productos relacionados') ?></p>
        <a href="<?= $this->Url->build(['action' => 'products']) ?>" class="btn btn-default semi-circle btn-block btn-md">
          <i class="fa fa-reply"></i> <?= __('Regresar') ?>
        </a>
      </div>
    </div>
  <?php else: ?>
    <?php if (isset($search)): ?>
      <div class="row title-search">
        <p style="font-size:16pt;"> <?= __('Resultados de') .' <b>'.$search.'</b> ' ?> </p>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-sm-6">
        <div class="holder"></div>
      </div>
      <div class="col-sm-6 legends text-right">
        <b class="legend1"></b> &nbsp;&nbsp;&nbsp; <b class="legend2"></b>
      </div>
    </div>
    
    <div>Esto es una prueba para jenkins</div>

    <br>
    <!-- CONTENT PRODUCT  -->
    <div id="itemContainer" class="row column-3">
      <?php foreach ($products AS $product): ?>
        <?php if ($product['price_product'] > 0 ): ?>
          <div class="col-sm-6 col-md-4">
            <div class="thumbnail store style1">
              <div class="header">
                <?php
                switch ($product['status']) {
                  case 'N':
                  $status = 'Nuevo';
                  $label = 'primary';
                  break;
                  case 'L':
                  $status = 'Oferta';
                  $label = 'warning';
                  break;
                  case 'D':
                  $status = 'Fuera de estok';
                  $label = 'danger';
                  break;
                  case 'PM':
                  $status = 'Mejor precio';
                  $label = 'warning';
                  break;
                  default:
                  $status = '';
                  $label = '';
                  break;
                }
                ?>
                <div class="badges">
                  <span class="product-badge top left <?= $label ?>-background text-white semi-circle"><?= __($status) ?></span>
                </div>
                <a href="<?= $this->Url->build(['action' => 'detail_product', $product['code']]) ?>">
                  <figure class="layer">
                    <img src="<?= $this->Url->build($product->img) ?>" alt="<?= $product->code ?>">
                  </figure>
                </a>
              </div>
              <div class="caption">
                <h6 class="regular">
                  <a href="<?= $this->Url->build(['action' => 'detail_product', $product['code']]) ?>">
                    <b><?= $product['code'] ?></b>
                    <br><?= $product['type_product'] ?>
                    <br><b><?= strtoupper(substr($product['product_series'],0,22)) ?></b>
                    <br><?= strtoupper(substr($product['models'],0,22)) .'...' ?>
                  </a>
                </h6>
                <?php if ($auth['role_id'] <> 3 ) : ?>
                  <div class="price">
                    <div class="col-md-6 col-xs-6">
                      <span class="amount text-primary">$ <?= number_format($product['price_product'], 2) ?></span>
                    </div>
                      <div class="col-md-6 col-xs-6 cant_product" data-code="<?= $product['code'] ?>">
                        <?php if($product['iscart'] == 0):  ?>
                        <span class="col-md-6 col-xs-6">Cant.</span>
                        <div class="col-md-6 col-xs-6">
                          <input type="number"  min="1" title="Requerido" class="form-control" name="<?= $product['code'] ?>" id="<?= $product['code'] ?>" onKeyPress="javascript:return solo_numeros ( event )" />
                        </div>
                        <?php endif; ?>
                      </div>
                  </div>
                  <div id="btn<?= $product['code'] ?>" class="col-md-12 col-xs-12 add_cart_botton">
                    <?php if($product['iscart'] == 0):  ?>
                      <a href="" onclick="add_cart('<?= $product['code'] ?>',<?= $auth['id'] ?>, event)"><i class="fa fa-cart-plus mr-5"></i><?= __('Agregar a Pedido') ?></a>
                    <?php else : ?>
                      <a class="success-btn-prod"><i class="fa fa-check-circle mr-5"></i> <?= __('add_to_cart') ?> </a>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
      <div class="col-sm-12 text-center">
          <ul class="pagination">
            <?= $this->Paginator->first('«') ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->last('»') ?>
          </ul>
      </div>
    </div>
    <!-- END CONTENT PRODUCT  -->

    <hr class="spacer-10 no-border">

    <br>

    <div class="row">
      <div class="col-sm-6">
        <div class="holder"></div>
      </div><!-- end col -->
      <div class="col-sm-6 legends text-right">
        <b class="legend1"></b> &nbsp;&nbsp;&nbsp; <b class="legend2"></b>
      </div>
    </div>
  <?php endif; ?>
</div>
