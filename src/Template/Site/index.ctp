<!-- SLIDERS CENTRAL -->
<section class="section dark-background">
  <div class="container">
    <?php foreach ($components['home'] AS $home) : ?>
      <div class="row">
        <div class="col-sm-4">
          <a href="<?= $this->Url->build($home->cta1) ?>" <?= ($home->isTargetBlank1() ? " target=\"_blank\"" : "") ?> ><img src="<?= $this->Url->build($home->slide1); ?>" width="100%"  onerror="this.onerror=null;this.src=`<?= $this->Url->build('images/products/no-image.png') ?>`"></a>
        </div>
        <div class="col-sm-4">
          <a href="<?= $this->Url->build($home->cta2) ?>" <?= ($home->isTargetBlank2() ? " target=\"_blank\"" : "") ?> ><img src="<?= $this->Url->build($home->slide2); ?>" width="100%"  onerror="this.onerror=null;this.src=`<?= $this->Url->build('images/products/no-image.png') ?>`"></a>
        </div>
        <div class="col-sm-4">
          <a href="<?= $this->Url->build($home->cta3) ?>" <?= ($home->isTargetBlank3() ? " target=\"_blank\"" : "") ?> ><img src="<?= $this->Url->build($home->slide3); ?>" width="100%"  onerror="this.onerror=null;this.src=`<?= $this->Url->build('images/products/no-image.png') ?>`"></a>
        </div>
      </div>
    <?php endforeach; ?>
  </div><!-- end container -->
</section>
<!-- END SLIDERS CENTRAL  -->

<!-- start section -->
<?php if ($auth['role_id'] <> 3 ) : ?>
  <section class="section white-background">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <ul class="nav nav-tabs style1" role="tablist">
            <li role="presentation" class="active">
              <a href="#featured" aria-controls="featured" role="tab" data-toggle="tab"><h6 class="text-uppercase"><?= __('Precios en Oferta') ?> </h6></a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="featured">
              <div class="row column-4">
                <?php foreach ($offers AS $product): ?>
                  <?php if ($product['price_product'] > 0 ): ?>
                  <div class="col-sm-6 col-md-3">
                    <div class="thumbnail store style1">
                      <div class="header">
                        <div class="badges">
                          <span class="product-badge top left warning-background text-white semi-circle"><?= __('Oferta') ?></span>
                        </div>
                        <a href="<?= $this->Url->build(['action' => 'detail_product', $product['code']]) ?>">
                          <figure class="layer">
                            <img src="<?= $this->Url->build($product['img']) ?>" alt="<?= $product['code'] ?>">
                          </figure>
                        </a>
                      </div>
                      <div class="caption">
                        <h6 class="regular">
                          <a href="<?= $this->Url->build(['action' => 'detail_product', $product['code']]) ?>">
                            <b><?= $product['code'] ?></b>
                            <br><?= $product['type_product'] ?>
                            <br><b><?= strtoupper(substr($product['product_series'],0,23)) ?></b>
                            <br><?= strtoupper(substr($product['models'],0,23)) .'...' ?>
                          </a>
                        </h6>
                        <?php if ($auth['role_id'] <> 3 ) : ?>
                        <div class="price">
                          <div class="col-md-6 col-xs-6">
                            <span class="amount text-primary">$ <?= number_format($product['price_product'], 2) ?></span>
                          </div>
                          <?php if($product['iscart'] == 0):  ?>
                            <div class="col-md-6 col-xs-6 cant_product">
                              <span class="col-md-6 col-xs-6">Cant.</span>
                              <div class="col-md-6 col-xs-6">
                                <input type="number" min="1" title="Requerido" class="form-control" name="<?= $product['code'] ?>" id="<?= $product['code'] ?>" onKeyPress="javascript:return solo_numeros ( event )" />
                              </div>
                            </div>
                          <?php endif; ?>
                        </div>
                        <div id="btn<?= $product['code'] ?>" class="col-md-12 col-xs-12 add_cart_botton">
                          <?php if($product['iscart'] == 0):  ?>
                            <a href="" onclick="add_cart('<?= $product['code'] ?>',<?= $auth['id'] ?>, event)"><i class="fa fa-cart-plus mr-5"></i><?= __('Agregar a pedido') ?></a>
                          <?php else : ?>
                            <a class="text-success"><i class="fa fa-check-circle mr-5"></i> <?= __('add_to_cart') ?> </a>
                          <?php endif; ?>
                        </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
