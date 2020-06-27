<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-6 text-left">
      <h2 class="title"><?= __('Pedido') ?></h2>
    </div><!-- end col -->
    <?php if(count($cart) != 0): ?>
    <div class="col-sm-6 text-right" >
      <a  target="_blank" href="<?= $this->Url->build(['action' => 'generatePdfCart']) ?>" style="margin-top: 20px;margin-bottom: 10px;" class="btn btn-danger round btn-md"> <i class="fa fa-print"></i>  <?=  __('Imprimir') ?> </a>
    </div><!-- end col -->
    <?php endif; ?>
  </div><!-- end row -->

  <hr class="spacer-5"><hr class="spacer-20 no-border">

  <div class="row">
    <div class="col-sm-12">
      <form onsubmit="event.preventDefault();confirmSendOrder()" id="form_cart" method="post">
        <div class="table-responsive">
          <input type="hidden" name="id_user" value="<?= $auth['id'] ?>" class="form-control">
          <table class="table table-striped">
            <thead>
              <tr>
                <th colspan="2" width="45%"><?= __('Producto') ?></th>
                <th width="15%"><?= __('Precio') ?></th>
                <th width="5%"><?= __('Cantidad') ?></th>
                <th width="15%"><?= __('Total') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total = 0;
              if (count($cart) == 0): ?>
              <tr>
                <td class="text-center" colspan="5"> <?= __('No hay productos para visualizar') ?>  </td>
              </tr>
            <?php else : ?>
              <?php
              foreach ($cart as $product):
                $total = $total + ($product['price'] * $product['amount']);
                ?>
                <tr>
                  <td>
                    <a href="<?= $this->Url->build(['action' => 'detail_product', $product['code'] ]) ?>">
                      <img width="60px" src="<?= $this->Url->build($product->tbl_product['img']) ?>">
                    </a>
                  </td>
                  <td>
                    <h6 class="regular">
                      <a href="<?= $this->Url->build(['action' => 'detail_product', $product->tbl_product['code'] ]) ?>"><?= $product->tbl_product['code'] ?></a>
                    </h6>
                    <p><?= $product->tbl_product['type_product'] ?></p>
                    <h6 class="regular"> <?= $product->tbl_product['product_series'] ?> </h6>
                    <p> <?= $product->tbl_product['models'] ?> </p>
                  </td>
                  <td>
                    <span>$ <?= number_format($product['price'],2) ?></span>
                    <input type="hidden" id="priceProduct<?= $product['id'] ?>" value="<?= number_format($product['price'],2) ?>">
                  </td>
                  <td>
                    <input type="number" min="1" onKeyPress="javascript:return solo_numeros ( event )" name="amount[]" required onchange="updatePrices(this.value, <?= $product['id'] ?>)" value="<?= $product['amount'] ?>" class="form-control">
                    <input type="hidden" name="id_product[]" value="<?= $product->tbl_product['code'] ?>" class="form-control">
                  </td>
                  <td>
                    <span id="totalByProduct<?= $product['id'] ?>" class="text-primary">$<?= number_format($product['price'] * $product['amount'] ,2) ?></span>
                    <input type="hidden" id="total<?= $product['id'] ?>" class="totalProduct" value="<?= number_format($product['price'] * $product['amount'] ,2) ?>">
                  </td>
                  <td>
                    <a href="<?= $this->Url->build(['action' => 'delete_cart', $product['id']]) ?>" style="font-size:15pt" class="fa fa-trash text-danger"
                      onclick="delete_cart('<?= $product['id'] ?>','¿<?= __("Esta seguro que desea eliminar  de la lista") . $product->tbl_product["type_product"] ?>?',event)">
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table><!-- end table -->
      </div><!-- end table-responsive -->
      <div class="row">
        <div class="col-md-offset-6 col-sm-offset-6 col-md-2 col-sm-2 col-xs-5 text-right">
          <hr class="no-border">
          <h3><?= __('Total') ?></h3>
          <hr class="no-border">
        </div>
        <div class="col-md-4 col-sm-4 col-xs-7 text-right">
          <hr>
          <h3 id="labelTotalOrder">$<?= number_format($total,2) ?></h3>
          <hr>
        </div>
      </div>
      <div class="row">
        <?php if(count($cart) != 0): ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="form-group">
            <label class="control-label"><?= __('Orden de compra') ?>: <span class="text-danger">*</span></label>
            <input type="text" name="purchase_order" id="purchase_order" class="form-control" required placeholder="<?= __('Orden de compra') ?>">

            <label class="control-label"><?= __('Forma envio') ?>: <span class="text-danger">*</span></label>
            <select name="shipping_way" id="shipping_way" required class="form-control">
                <option value="Aéreo"><?= __('Aéreo') ?></option>
                <option value="Boxes"><?= __('Boxes') ?></option>
                <option value="Contenedor"><?= __('Contenedor') ?></option>
                <option value="Local"><?= __('Local') ?></option>
                <option value="Maritimo"><?= __('Maritimo') ?></option>
            </select>

            <label class="control-label"><?= __('Transportadora') ?>: <span class="text-danger">*</span></label>
            <input type="text" name="shipping_name" id="shipping_name" class="form-control" required placeholder="<?= __('Transportadora') ?>">

            <label class="control-label"><?= __('Notas adicionales') ?>:</label>
            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="<?= __('Notas adicionales') ?>"></textarea>
          </div>
        </div>
        <?php endif; ?>
      </div>
      <div class="row">
        <div class="col-md-6 col-xs-6 text-left">
          <a href="<?= $this->Url->build(['action' => 'products' ]) ?>" class="btn btn-light round btn-md ">
            <i class="fa fa-arrow-left mr-5"></i><span class="hidden-xs"><?= __('Seguir Comprando') ?></span>
          </a>
        </div>
        <?php if(count($cart) != 0): ?>
        <div class="col-md-6 col-xs-6 text-right">
            <button type="submit" class="btn btn-default round btn-md ">
              <?= __('Enviar Pedido') ?> <i class="fa fa-paper-plane ml-5"></i>
            </button>
        </div>
        <?php endif; ?>
      </div>
    </form>
  </div><!-- end col -->
</div><!-- end row -->
</div><!-- end col -->
