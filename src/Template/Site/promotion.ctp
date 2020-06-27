<!-- PROMOS SLIDERS -->
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-12">
      <div class="owl-carousel slider owl-theme">
        <?php
        foreach ($promotions as $promotion) :
          if ($promotion->id == 1) :
            foreach ($promotion->sliders as $slide) :
              ?>
              <div class="item">
                <figure>
                  <a href="<?= $this->Url->build($slide['cta']) ?>" <?= ($slide->isTargetBlank() ? " target=\"_blank\"" : "") ?> >
                    <img src="<?= $this->Url->build($slide['img_desktop']) ?>" onerror="this.onerror=null;this.src=`<?= $this->Url->build('/images/products/no-image.png') ?>`" alt=""/>
                  </a>
                </figure>
              </div>
              <?php
            endforeach;
          endif;
        endforeach;
        ?>
      </div><!-- end owl carousel -->
    </div><!-- end col -->
  </div><!-- end row -->
  <hr class="no-border">

  <?php foreach ($promotions as $promotion) : ?>
    <?php if ($promotion->id <> 1) : ?>
      <div class="row">
        <div class="col-md-12">
          <h5><?= ucwords($promotion->name) ?></h5>
        </div>
        <div class="col-sm-12">
          <div class="owl-carousel column-3 owl-theme">
            <?php foreach ($promotion->sliders as $slide) : ?>
              <div class="item">
                <figure>
                  <a href="<?= $this->Url->build($slide['cta']) ?>" <?= ($slide->isTargetBlank() ? " target=\"_blank\"" : "") ?> >
                    <img src="<?= $this->Url->build($slide['img_desktop']) ?>" onerror="this.onerror=null;this.src=`<?= $this->Url->build('/images/products/no-image.png') ?>`" alt=""/>
                  </a>
                </figure>
              </div>
            <?php endforeach; ?>
          </div><!-- end owl carousel -->
        </div><!-- end col -->
      </div><!-- end row -->
      <hr class="no-border">
    <?php endif;?>
  <?php endforeach; ?>
</div>
<!-- END PROMOS SLIDERS -->
