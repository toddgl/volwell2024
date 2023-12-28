<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<!DOCTYPE html>
<html lang ="en">
	<head>
    <meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">
		<link rel="canonical" href="https://volunteerwellington.nz/">
		<!--HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lte IE 8]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
		<![endif]-->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
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
            .navbar {top:30px;}
            .header-banner {top: 30px;}
            /* Other CSS Style */
        </style>
    <?php } ?>
    </div>
  <header>
      <div class="header-banner">
          <div class="banner_text justify-content-center">
             <!-- Lead in page Welcome msg -->
            <?php
                  $areaHdrBanner = new Area('Banner');
                  $areaHdrBanner->display();
                  ?>
        </div>
      </div>
       <div class="container">
          <nav class="navbar navbar-expand-md navbar-dark bg-transparent">
            <a class="navbar-brand mb-0 h1" href="<?=URL::to('/')?>"><img src="<?=$view->getThemePath()?>/images/VolunteerWellington_white_sm.png" alt="logo">Volunteer Wellington</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#vw_navbar" aria-controls="#vw_navbar" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="vw_navbar">
                <?php
                    $globalNav = new GlobalArea('Header Navigation');
                    $globalNav->display();
                ?>
	        </div>
          </nav>
          
        
      </div>
 </header>
          
