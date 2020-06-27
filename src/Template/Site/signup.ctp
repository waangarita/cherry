<div class="row">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-12 text-left">
        <h2 class="title">
          <?= __('Registrarme') ?>
        </h2>
      </div><!-- end col -->
    </div><!-- end row -->

    <hr class="spacer-5"><hr class="spacer-20 no-border">
    <div id="message"></div>
    <div class="row">
      <div class="col-sm-12 col-md-10 col-lg-8">
        <hr class="spacer-5 no-border">
        <form class="form-horizontal" id="register_form" method="post">
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="first_name" class="control-label"><?= __('Nombres') ?> <span class="text-danger">*</span></label>
              <input type="text" name="first_name" class="form-control input-md" required id="first_name" placeholder="<?= __('Nombres') ?>">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="last_name" class="control-label"><?= __('Apellidos') ?> <span class="text-danger">*</span></label>
              <input type="text" class="form-control input-md" required id="last_name" name="last_name" placeholder="<?= __('Apellidos') ?>">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="company" class="control-label"><?= __('Empresa') ?> <span class="text-danger">*</span></label>
              <input type="text" class="form-control input-md" id="company" name="company" placeholder="<?= __('Nombre Empresa') ?>" required>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="company" class="control-label"><?= __('País') ?> <span class="text-danger">*</span></label>
              <select name="country_id" required id="country_id" class="form-control" placeholder="<?= __('Seleccione') ?>">
                <option value=""><?= __('Seleccione su país') ?></option>
                <?php foreach ($countries as $country):?>
                  <option value="<?= $country->id ?>"><?= $country->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="phone1" class="control-label"><?= __('Telefono') ?> <span class="text-danger">*</span></label>
              <input type="numeric" class="form-control input-md" id="phone1" name="phone1" placeholder="<?= __('Telefono') ?>" required>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="phone2" class="control-label"><?= __('Telefono') ?> 2</label>
              <input type="numeric" class="form-control input-md" id="phone2" name="phone2" placeholder="<?= __('Telefono') ?> 2">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="email" class="control-label"><?= __('Email') ?>  <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control input-md" id="email" placeholder="<?= __('Email') ?>" required>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="password" class="control-label"><?= __('Contraseña') ?> <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control input-md" id="password" placeholder="<?= __('Contraseña') ?>" required>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="password2" class="control-label"><?= __('Confirmar Contraseña') ?> <span class="text-danger">*</span></label>
              <input type="password"  name="password2" class="form-control input-md" id="password2" placeholder="<?= __('Confirmar Contraseña') ?>" required>
            </div>
          </div>
          <div class="form-group">
            <div class=" col-sm-10 col-sm-offset-2">
              <div class="col-sm-6 text-left" style="padding:0px;">
                <label><a href="<?= $this->Url->build(['action' => 'login']) ?>"><?= __('Iniciar Sesión') ?></a></label>
              </div>
              <div class="col-sm-6 text-right" style="padding:0px;">
                <button type="submit" class="btn btn-default round btn-md"><i class="fa fa-user mr-5"></i><?= __('Registrarme') ?> </button>
              </div>
            </div>
          </div><!-- end form-group -->
        </form>
      </div><!-- end col -->
    </div><!-- end row -->
  </div><!-- end col -->
</div>

<?php $this->append('scripts'); ?>
<script type="text/javascript">
$(document).ready(function () {
  $('#register_form').submit(()=> {
    if ($('#password').val() !== $('#password2').val() ) {
      $('#message').html('<div  class="alert alert-danger"> <i class="fa fa-times-circle" style="font-size:15pt;"></i> El password no coincide por favor validar</div>')
      $('#password').focus()
      return false;
    }else{
      $('#message').html('')
    }

  })
})
</script>
<?php $this->end(); ?>
