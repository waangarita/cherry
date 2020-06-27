<form class="" method="post">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-12 text-left">
        <h2 class="title"><?= __('Restaurar Contraseña') ?></h2>
      </div>
    </div>

    <hr class="spacer-5"><hr class="spacer-20 no-border">

    <div class="row">
      <div class="col-sm-10 col-sm-offset-2 col-md-10 col-lg-8">
        <p><?= __('Por favor ingrese su direccion de correo, Usted recibiera un mensaje con una nueva Contraseña') ?> </p>
        <form>
          <div class="form-group">
            <label for="email"><?= __('Correo') ?> </label>
            <input type="email" name="email" class="form-control input-md" id="email" placeholder="<?= __('email@email.com') ?>">
          </div>
          <div  class="form-group">
            <label><a style="float:left;" href="<?= $this->Url->build(['action' => 'login']) ?>"><?= __('Iniciar Sesión') ?> </a></label>
            <button style="float:right;" type="submit" class="btn btn-default round btn-md"><?= __('Restaurar Contraseña') ?> </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</form>
