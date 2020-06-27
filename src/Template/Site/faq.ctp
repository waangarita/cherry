<div class="col-xs-12 col-sm-9 content-faq">
    <div class="row">
      <h5> <?= __('PREGUNTAS FRECUENTES') ?></h5>
      <br>
      <p>
        <?= __('Si en cualquier momento de su visita tiene alguna pregunta') ?>,
        <?= __('no dude en llamarnos al (305) 477-2988 o envienos un correo electronico a') ?> <b>ventas@cherry.com</b>.
      </p>
    </div>

    <div class="row accordion style1" id="question" role="tablist" aria-multiselectable="true">
      <?php foreach ($faqs as $faq) : ?>
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="question<?= $faq->id ?>">
            <h4 class="panel-title">
              <a class="collapsed" data-toggle="collapse" data-parent="#question" href="#collapseQuestion<?= $faq->id ?>" aria-expanded="false" aria-controls="collapse<?= $faq->id ?>">
                <?= $faq->question ?>
              </a>
            </h4>
          </div><!-- end panel-heading -->
          <div id="collapseQuestion<?= $faq->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="question<?= $faq->id ?>">
            <div class="panel-body">
              <p><?= $faq->answer ?></p>
            </div><!-- end panel-body -->
          </div><!-- end collapse -->
        </div><!-- end panel -->
      <?php endforeach; ?>
    </div><!-- end panel-group -->

    <hr class="no-border">

    <div class="row">
      <h6> <?= __('CONTACTENOS') ?> </h6>
      <br>
      <p>
        <b><?= __('Telefono') ?>: </b>+1 (305) 477-2988 ventas@cherry.com
      </p>
      <p>
        <b><?= __('Correo') ?>: </b>ventas@cherry.com
      </p>
      <p>
        <b>Skype: </b>ventas@cherry.com
      </p>
    </div>
  <!-- END FAQ -->

</div><!-- end row -->
