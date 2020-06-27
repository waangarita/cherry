<!-- start section -->
<section class="footer_banner" >
  <?php foreach ($components['home'] AS $home) : ?>
  <img src="<?= $this->Url->build($home->banner) ?>" width="100%" height="230px;" alt="">
<?php endforeach; ?>
</section>
<!-- end section -->

<!-- start footer -->
<footer class="footer">
  <div  class="container">
    <div class="row">
      <div class="col-sm-3"></div>
      <div class=" col-sm-3">
        <h5 class="title"><?= __('Mi cuenta') ?></h5>
        <ul class="list alt-list">
          <li><a href="<?= $this->Url->build(['action' => 'my_account']) ?>"><i class="fa fa-angle-right"></i><?= __('Mi cuenta') ?></a></li>
          <?php if ($auth['role_id'] <> 3): ?>
          <li><a href="<?= $this->Url->build(['action' => 'cart']) ?>"><i class="fa fa-angle-right"></i><?= __('Pedido') ?></a></li>
          <li><a href="<?= $this->Url->build(['action' => 'history_orders']) ?>"><i class="fa fa-angle-right"></i><?= __('Historial Ordenes') ?></a></li>
          <?php endif; ?>
        </ul>
      </div><!-- end col -->
    </div><!-- end row -->

    <hr class="spacer-30">

    <div class="row text-center">
      <div class="col-sm-12">
        <p class="text-sm">&COPY; <?= date('Y') ?> <a href=""></a> </p>
      </div><!-- end col -->
    </div><!-- end row -->
  </div><!-- end container -->
</footer>
<!-- end footer -->
