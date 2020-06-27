<div class="navbar-vertical">
  <ul class="nav nav-stacked">
    <li class="header hidden-xs">
      <h6 class="text-uppercase"><?= __('marcas') ?></h6>
    </li>
    <?php foreach ($brands AS $brand) : ?>
      <li class="hidden-xs">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <?= $brand->name ?>
          <?php if ( count($brand->Families) > 0) : ?>
            <i class="fa fa-angle-right pull-right"></i>
          <?php endif; ?>
        </a>
        <ul class="dropdown-menu">
          <?php foreach ($brand->Families AS $family) : ?>
            <li><a href="<?= $this->Url->build(['action' => 'products', 'family' => $family->code ]) ?>"><?= $family->name ?></a></li>
          <?php endforeach; ?>
        </ul>
      </li>
    <?php endforeach; ?>

    <!-- brands list mobile -->
    <li class="hidden-sm hidden-md hidden-lg">
      <button type="button" data-toggle="collapse" data-target="#navbar-collapse-2" class="navbar-toggle brands-list"><?= __('marcas') ?></button>
      <div id="navbar-collapse-2" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
           <?php foreach ($brands AS $brand) : ?>
            <li>
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <?= $brand->name ?>
                <?php if ( count($brand->Families) > 0) : ?>
                  <i class="fa fa-angle-right pull-right"></i>
                <?php endif; ?>
              </a>
              <ul class="dropdown-menu">
                <?php foreach ($brand->Families AS $family) : ?>
                  <li><a href="<?= $this->Url->build(['action' => 'products', 'family' => $family->code ]) ?>"><?= $family->name ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </li>
    <!-- end brands list mobile -->

  </ul>

</div>
<!-- end navbar-vertical -->
