<!DOCTYPE html>
<html lang="en">
<head>
  <title> cherry </title>
  <meta charset="utf-8">
  <meta name="description" content="cherry Products">
  <meta name="author" content="" />
  <meta name="keywords" content="global, cherry, products" />
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">

  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <!--Favicon-->
  <link rel="shortcut icon" href="<?= $this->Url->build('/img/favicon.ico') ?>" type="image/x-icon">
  <link rel="icon" href="<?= $this->Url->build('/img/favicon.ico') ?>" type="image/x-icon">

  <!-- css files -->
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/maqueta/css/bootstrap.min.css') ?>" />
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/maqueta/css/font-awesome.min.css') ?>" />
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/maqueta/css/owl.carousel.min.css') ?>" />
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/maqueta/css/owl.theme.default.min.css') ?>" />
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/maqueta/css/animate.css') ?>" />
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/maqueta/css/swiper.css') ?>" />

  <!-- this is default skin you can replace that with: dark.css, yellow.css, red.css ect -->
  <link id="pagestyle" rel="stylesheet" type="text/css" href="<?= $this->Url->build('/maqueta/css/skin-blue.css') ?>" />

  <!-- Nuevos Estilos cherry -->
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/maqueta/css/new_style.css') ?>" />

  <!-- Google fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800&amp;subset=latin-ext" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= $this->Url->build('/css/sweetalert.css') ?>">
</head>
<body>
  <!-- start topBar -->
  <div class="topBar">
    <div class="container">
      <ul class="list-inline pull-left hidden-sm hidden-xs">
        <li><span class="text-primary"> <?= __('Tienes alguna pregunta') ?>? </span> <?= __('Llamanos') ?> +1 305 477 2988 </li>
      </ul>
      <ul class="topBarNav pull-right">
        <?php if ($language == 'ES'): ?>
        <li class="linkdown">
          <a href="" onclick="language('ES',event)">
            <img src="<?= $this->Url->build('/maqueta/img/flags/flag-spain.jpg') ?>" class="mr-5" alt="">
            <span class="hidden-xs">
              Español
              <i class="fa fa-angle-down ml-5"></i>
            </span>
          </a>
          <ul class="w-100">
            <li><a href="" onclick="language('EN',event)"><img src="<?= $this->Url->build('/maqueta/img/flags/flag-english.jpg') ?>" class="mr-5" alt=""> English </a></li>
          </ul>
        </li>
        <?php else: ?>
          <li class="linkdown">
            <a href="" onclick="language('EN',event)">
              <img src="<?= $this->Url->build('/maqueta/img/flags/flag-english.jpg') ?>" class="mr-5" alt="">
              <span class="hidden-xs">
                English
                <i class="fa fa-angle-down ml-5"></i>
              </span>
            </a>
            <ul class="w-100">
              <li>
                <a href="" onclick="language('ES',event)">
                  <img src="<?= $this->Url->build('/maqueta/img/flags/flag-spain.jpg') ?>" class="mr-5" alt=""> Español
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div><!-- end container -->
  </div>

  <!-- start section -->
  <section class="section white-backgorund">
    <div class="container">
      <?= $this->Flash->render() ?>
      <img src="<?= $this->Url->build('/images/cherry-logo.png') ?>" width="300px" alt="logo">
      <?= $this->fetch('content') ?>
    </div>
  </section>
  <!-- end section -->

  <!-- BANNER INFERIOR Y FOOTER -->
  <?= $this->element('Partials/footer') ?>
  <!-- END BANNER INFERIOR Y FOOTER -->

  <!-- LENGUAGE -->
  <script type="text/javascript">
    const LANGUAGE = '<?= $language  ?>'
    const API = '<?= $this->Url->build("/api/") ?>'
    const URL_IMAGES = '<?= $this->Url->build('/images/') ?>'
    const ROOT = '<?= $this->Url->build('/') ?>'
  </script>
  <!-- END LENGUAGE -->

  <!-- JavaScript Files -->
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/jquery-3.1.1.min.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/bootstrap.min.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/owl.carousel.min.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/jquery.downCount.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/nouislider.min.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/jquery.sticky.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/pace.min.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/star-rating.min.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/wow.min.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/swiper.min.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/maqueta/js/main.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/js/translate.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/js/general.js') ?>"></script>
  <script type="text/javascript" src="<?= $this->Url->build('/js/sweetalert.min.js') ?>"></script>
  <?= $this->fetch('scripts') ?>
</body>
</html>
