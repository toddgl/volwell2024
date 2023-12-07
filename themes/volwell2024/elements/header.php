<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<!DOCTYPE html>
<html lang ="en">
	<head>
    <meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="canonical" href="https://volunteerwellington.nz/">
		<!--HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lte IE 8]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
		<![endif]-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo $view->getStylesheet('main.less')?>" >
		<?php Loader::element('header_required');?>
  </head>
  <body class="main-ctnr">
	<div class="<?php echo $c->getPageWrapperClass()?>">
	<?php
		$as = new GlobalArea('Header Logo');
		$blocks = $as->getTotalBlocksInArea();
		$displayThirdColumn = $blocks > 0 || $c->isEditMode();
	?>
    <?php $cp = new Permissions($c); if($cp->canViewToolbar()){?>
        <style media="screen">
            .navbar {top:48px;}
            .home_banner_image {top:48px;}
            /* Other CSS Style */
        </style>
    <?php } ?>
  <header>
      <div class="container">
          <nav class="navbar navbar-expand-md navbar-dark bg-transparent">
            <a class="navbar-brand mb-0 h1" href="<?=URL::to('/')?>"><img src="<?=$view->getThemePath()?>/images/VolunteerWellington_white_sm.png" alt="logo">Volunteer Wellington</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php
                    $globalNav = new GlobalArea('Header Navigation');
                    $globalNav->display();
                ?>
	        </div>
          </nav>
      </div>
          <!--::banner section start::-->
          <section class="home_banner_part">
              <div class="container home_banner_image">

                  <!-- Lead in page Image -->
                  <?php
                  $areaHdrImage = new GlobalArea('Image');
                  $areaHdrImage->display();
                  ?>

                  <div class="home_banner_text justify-content-center">
                      <!-- Lead in page Welcome msg -->
                      <?php
                      $areaHdrBanner = new Area('Banner');
                      $areaHdrBanner->display();
                      ?>
                  </div>
              </div>
          </section>
          <!--::banner section end::-->
  </header>
