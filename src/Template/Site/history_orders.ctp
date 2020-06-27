<div class="col-sm-9">
  <?= $this->Flash->render() ?>
  <div class="row">
    <div class="col-sm-12 text-left">
      <h2 class="title"><?= __('Historial Ordenes') ?></h2>
    </div><!-- end col -->
  </div><!-- end row -->

  <hr class="spacer-5"><hr class="spacer-20 no-border">

  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
        <table class="table table-striped table-hover ">
          <thead>
            <tr>
              <th><?= __('Pedido ID') ?></th>
              <th><?= __('Productos') ?></th>
              <th><?= __('Total') ?></th>
              <th><?= __('Fecha') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($history AS $h): ?>
              <?php
                $total = 0;
                $detailOrder = $order->detailOrder($h->id_user, $h->id, 3);
                $codeOrder = $order->createCodeOrder($h->id, $h->id_user, $auth['id_client']);
              ?>
              <tr>
                <td># <?= $codeOrder ?></td>
                <td>
                  <a href="<?= $this->Url->build(['action' => 'detailOrder', $h->id ]) ?>">
                    <ul style="list-style:none">
                      <?php foreach ($detailOrder AS $detail): ?>
                        <?php $total = $total + ($detail['price_product'] * $detail['amount']) ?>
                        <li><?= $detail['code'] ?> - <?= $detail['type_product'] ?></li>
                      <?php endforeach; ?>...
                    </ul>
                  </a>
                </td>
                <td>$<?= number_format($total,2) ?></td>
                <td><?= $h->created->format('m-d-Y h:i') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table><!-- end table -->
      </div><!-- end table-responsive -->

      <hr class="spacer-10 no-border">

      <div class="col-md-12">
        <a href="<?= $this->Url->build(['action' => 'products' ]) ?>" class="btn btn-light round btn-md">
          <i class="fa fa-arrow-left mr-5"></i> <?= __('Continuar comprando') ?>
        </a>
      </div>

    </div><!-- end col -->
  </div><!-- end row -->
</div><!-- end col -->
