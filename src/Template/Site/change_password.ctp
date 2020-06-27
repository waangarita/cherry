<form id="form_change_pass" method="post">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-12 text-left">
        <h2 class="title"><?= __('Cambiar Password') ?></h2>
      </div>
    </div>

    <hr class="spacer-5"><hr class="spacer-20 no-border">

    <div class="row">
      <div class="col-sm-10 col-sm-offset-2 col-md-10 col-lg-8">
        <div id="message"></div>
        <p><?= __('Tiene un password temporal, por favor ingrese un nuevo password') ?> </p>
        <form>
          <div class="form-group">
            <label for="password"><?= __('Nuevo Password') ?> </label>
            <input type="password" required name="password" class="form-control input-md" id="password" placeholder="<?= __('Nuevo Password') ?>">
          </div>
          <div class="form-group">
            <label for="password2"><?= __('Confirmar Nuevo Password') ?> </label>
            <input type="password" required name="password2" class="form-control input-md" id="password2" placeholder="<?= __('Confirmar Nuevo Password') ?>">
          </div>
          <div  class="form-group">
            <button style="float:right;" type="submit" class="btn btn-default round btn-md"><?= __('Cambiar Password') ?> </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</form>
<?php $this->append('scripts'); ?>
<script type="text/javascript">
$(document).ready(function () {
  $('#form_change_pass').submit(()=> {
    if ($('#password').val() !== $('#password2').val() ) {
      $('#message').html('<div  class="alert alert-danger"> <i class="fa fa-times-circle" style="font-size:15pt;"></i> <?= __('El password no coincide por favor validar') ?> </div>')
      $('#password').focus()
      return false;
    }else{
      $('#message').html('')
    }
  })
})
</script>
<?php $this->end(); ?>
