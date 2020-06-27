<?php $totalOrder = 0; ?>
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-12 text-left">
      <h2 class="title"><?= __('Detalle Pedido') ?> # <?= $numberOrder ?> </h2>
    </div>
  </div>

  <hr class="spacer-5"><hr class="spacer-20 no-border">

  <div class="row">
    <div class="col-sm-12">
      <form onsubmit="event.preventDefault();confirmDuplicate()" id="form_detail_order" method="post">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th colspan="2" width="45%"><?= __('Productos') ?></th>
                <th width="15%"><?= __('Precio') ?></th>
                <th width="5%"><?= __('Cantidad') ?></th>
                <th colspan="15%"><?= __('Total') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($detail AS $d): ?>
                <?php $totalOrder = $totalOrder + ($d['amount'] * $d['price_product']) ?>
                <tr>
                  <td>
                    <a href="<?= $this->Url->build(['action' => 'detail_product', $d['code'] ]) ?>">
                      <img width="120px" src="<?= $this->Url->build('/images/products/') ?><?= $d['code'] ?>.jpg" alt="<?= $d['code'] ?>" onerror="searchImage('<?= $d['id_family'] ?>', this);">
                    </a>
                    <input type="hidden" name="id_product[]" value="<?= $d['code'] ?>">
                    <input type="hidden" name="amount[]" value="<?= $d['amount'] ?>">
                  </td>
                  <td>
                    <h6 class="regular">
                      <a href="<?= $this->Url->build(['action' => 'detail_product', $d['code'] ]) ?>"><?= $d['code'] ?></a>
                    </h6>
                    <p><?= $d['type_product'] ?></p>
                    <h6 class="regular"> <?= $d['product_series'] ?> </h6>
                    <p> <?= $d['models'] ?> </p>
                  </td>
                  <td> $<?= number_format($d['price_product'], 2); ?> </td>
                  <td><?= $d['amount'] ?></td>
                  <td> $<?= number_format($d['amount'] * $d['price_product'], 2); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-md-9 col-xs-5 text-right">
            <hr class="no-border">
            <h3>TOTAL</h3>
            <hr class="no-border">
          </div>
          <div class="col-md-3 col-xs-7 text-right">
            <hr>
            <h3>$<?= number_format($totalOrder,2); ?></h3>
            <hr>
          </div>
        </div>
        
        <DIV>ESTO ES UNA PRUEBA PARA JENKINS</DIV>

        <div id="btn-detail">
          <a href="<?= $this->Url->build(['action' => 'products']) ?>" class="btn btn-light round btn-md pull-left">
            <i class="fa fa-arrow-left mr-5"></i> <span class="hidden-xs"><?=  __('Continuar comprando') ?></span>
          </a>

          <button type="submit" class="btn btn-default round btn-md pull-right">
            <?= __('Duplicar') ?> <i class="fa fa fa-files-o ml-5"></i>
          </button>

          <a href="<?= $this->Url->build(['action' => 'generate_pdf_order', $idOrder]) ?>" target="_blank" style="margin-right:5px;"  class="btn btn-danger round btn-md pull-right">
             <?=  __('Imprimir') ?>  <i class="fa fa-print"></i>
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
