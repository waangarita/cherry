<div class="row">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-12 text-left">
        <h2 class="title">
          <?= __('Ingresar') ?>
        </h2>
      </div><!-- end col -->
    </div><!-- end row -->

    <hr class="spacer-5"><hr class="spacer-20 no-border">
    <div class="row">
      <div class="col-sm-12 col-md-10 col-lg-8">
        <form class="form-horizontal" method="post">
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="email" class="control-label"><?= __('Email') ?></label>
              <input type="email" class="form-control input-md" name="email" value="<?= isset($_COOKIE['email']) ? $_COOKIE['email'] : '' ?>" id="email" placeholder="Email">
            </div>
          </div><!-- end form-group -->
          <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
              <label for="password" class="control-label"><?= __('Contraseña') ?></label>
              <input type="password" class="form-control input-md"  value="<?= isset($_COOKIE['password']) ? $_COOKIE['password'] : '' ?>" name="password" id="password" placeholder="<?= __('Contraseña') ?>">
            </div>
          </div><!-- end form-group -->
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="col-sm-6" style="padding:0px;">
                <div class="checkbox-input mb-10">
                  <input id="remember" name="remember" class="styled" type="checkbox">
                  <label for="remember">
                    <?= __('Recordarme') ?>
                  </label>
                </div><!-- end checkbox-input -->
                <label><a href="<?= $this->Url->build(['action' => 'signup']) ?>"><?= __('Registrarme') ?></a> | <a href="<?= $this->Url->build(['action' => 'forgot_password']) ?>"><?= __('Olvido su contraseña') ?>?</a></label>
              </div>
              <div class="col-sm-6 text-right" style="padding:0px;">
                <button type="submit" class="btn btn-default round btn-md"><i class="fa fa-lock mr-5"></i> <?= __('Ingresar') ?></button>
              </div>
            </div>
          </div><!-- end form-group -->
        </form>
      </div><!-- end col -->
    </div><!-- end row -->
  </div><!-- end col -->
</div><!-- end container -->
