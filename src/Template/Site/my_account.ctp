<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-12 text-left">
      <h2 class="title"><?= __('Mi cuenta') ?></h2>
    </div><!-- end col -->
  </div><!-- end row -->

  <hr class="spacer-5"><hr class="spacer-20 no-border">

  <div class="row">
    <div id="message"></div>
    <div class="col-sm-12">
      <p> <?= __('Hola') ?> <strong> <?= h($auth['first_name']) .' '.h($auth['last_name'])  ?></strong>! <?= __('Puede cambiar su informacion personal')  ?> <a id="show-information" href="#"> <?= __('AQUI') ?> </a></p>

      <hr class="spacer-5 no-border">
      <form class="form-horizontal"  id="information-user">
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="first" class="control-label"><?= __('Nombres') ?> :  </label> <?= h($auth['first_name'])  ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="last" class="control-label"><?= __('Apellidos') ?> :  </label> <?= h($auth['last_name'])  ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="cargo" class="control-label"><?= __('País') ?> : </label> <?= h($user->tbl_country->name) ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="cargo" class="control-label"><?= __('Cargo') ?> : </label> <?= h($auth['appoinment']) ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="telefono1" class="control-label"><?= __('Telefono') ?> : </label> <?= h($auth['phone1']) ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="telefono2" class="control-label"><?= __('Telefono') ?> 2 : </label> <?= h($auth['phone2']) ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="telefono2" class="control-label"><?= __('Email') ?> : </label> <?= h($auth['email']) ?>
          </div>
        </div>
      </form>
      <form class="form-horizontal" method="post" id="update-information">
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="name" class="control-label"> <?= __('Nombres') ?> <span class="text-danger">*</span></label>
            <input type="name" class="form-control input-md" required name="first_name" id="first_name" value="<?= h($auth['first_name']) ?>" placeholder="<?= __('Nombres') ?>">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="name" class="control-label"> <?= __('Apellidos') ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control input-md" required name="last_name" id="last_name" value="<?= h($auth['last_name'])  ?>" placeholder="<?= __('Apellidos') ?>">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="cargo" class="control-label"><?= __('Cargo') ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control input-md"  id="appoinment" name="appoinment" value="<?= h($auth['appoinment']) ?>" placeholder="<?= __('Cargo') ?>" required>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="cargo" class="control-label"><?= __('País') ?> <span class="text-danger">*</span></label>
            <select name="country_id" id="country_id" class="form-control" placeholder="<?= __('Seleccione país') ?>" >
              <option value=""></option>
              <?php foreach($countries as $country): ?>
                <option <?= $country->id == $user->tbl_country->id ? 'SELECTED' : '' ?> value="<?= $country->id ?>"><?= $country->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="telefono1" class="control-label"><?= __('Telefono') ?> <span class="text-danger">*</span></label>
            <input type="numeric" class="form-control input-md" id="phone1" name="phone1" value="<?= h($auth['phone1']) ?>" placeholder="<?= __('Telefono') ?>" required>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="telefono2" class="control-label"><?= __('Telefono') ?> 2</label>
            <input type="numeric" class="form-control input-md" id="phone2" name="phone2" value="<?= h($auth['phone2']) ?>" placeholder="<?= __('Telefono') ?> 2">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="telefono2" class="control-label"><?= __('Email') ?> <span class="text-danger">*</span></label>
            <input type="email" readonly class="form-control input-md" id="email" name="email" value="<?= h($auth['email']) ?>" placeholder="Email" required>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="password" class="control-label"><?= __('Password') ?> </label>
            <input type="password" name="password" class="form-control input-md"  id="password" placeholder="Password">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 ">
            <label for="password2" class="control-label"><?= __('Confirmar Password') ?> </label>
            <input type="password" name="password2" class="form-control input-md" id="password2" placeholder="<?= __('Confirmar password') ?>">
          </div>
        </div>
        <div class="form-group">
          <div class=" col-sm-10">
            <div class="col-sm-offset-6 col-sm-6 text-right" style="padding:0px;">
              <button type="submit" class="btn btn-default round btn-md"><i class="fa fa-user mr-5"></i> <?= __('Guardar') ?> </button>
            </div>
          </div>
        </div><!-- end form-group -->
        <div class="form-group">
          <div class="col-sm-offset-10 col-sm-2">
          </div>
        </div><!-- end form-group -->
      </form>
    </div><!-- end col -->
  </div><!-- end row -->
</div><!-- end col -->
