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
	<header>
		<div class="container">
			<!--::menu section start::-->
			<div class = "row">
				<div class="col-lg-12">
					<nav class="navbar navbar-expand-lg navbar-light">
						<a class="navbar-brand" href="<?=URL::to('/')?>"><img src="<?=$view->getThemePath()?>/images/VolunteerWellington_pink_sm.png" alt="logo"> </a>
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse main-menu-item" id="navbarNav">
              <?php View::getInstance()->requireAsset('javascript', 'jquery');
                $nav = Loader::helper('navigation');
                $navItems = $controller->getNavItems();

                foreach ($navItems as $ni) {
                  $classes = array();
                  if ($ni->isCurrent) {
                  //class for the page currently being viewed
                    $classes[] = 'nav-selected';
                  }
                  if ($ni->inPath) {
                    //class for parent items of the page currently being viewed
                    $classes[] = 'nav-path-selected';
                  }
                  if ($ni->hasSubmenu) {
                  //class for items that have dropdown sub-menus
                    $classes[] = 'dropdown';
                  }
                  //Put all classes together into one space-separated string
                  $ni->classes = implode(" ", $classes);
                 }

                //*** Step 2 of 2: Output menu HTML ***/

                echo '<ul class="nav navbar-nav navbar-right">'; //opens the top-level menu

                foreach ($navItems as $ni) {

                  echo '<li class="' . $ni->classes . '">'; //opens a nav item

                  if ($ni->isEnabled) {
                    $ni->hasSubmenu;
                  }

                  if ($ni->hasSubmenu) {
                     echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">' . $ni->name . '</a>';
                  } else {
                      echo '<a href="' . $ni->url . '" target="' . $ni->target . '" class="' . $ni->classes . '"><span class="navwrap"><span class="navimg"><i class="material-icons">' . $ni->attrClass . '</i></span><span class="navtit">' . $ni->name . '</span><span class="navtxt">' . $beschrijving . '</span></span></a>';
                  }

                  if ($ni->hasSubmenu) {
                    echo '<ul class="dropdown-menu">'; //opens a dropdown sub-menu
                  } else {
                    echo '</li>'; //closes a nav item
                     echo str_repeat('</ul></li>', $ni->subDepth); //closes dropdown sub-menu(s) and their top-level nav item(s)
                  }
                }

                echo '</ul>'; //closes the top-level menu
                ?>
						</div>
					</nav>
				</div>
      </div>
    </div>
  </header>
