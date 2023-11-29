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
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo $view->getStylesheet('main.less')?>" >
		<?php Loader::element('header_required');?>
  </head>
  <body class="main-ctnr">
	<div class="<?php echo $c->getPageWrapperClass()?>">
	<?php
		$as = new GlobalArea('Header Search');
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
							<ul class="navbar-nav ml-auto">
							<?php
								$c = Page::getCurrentPage();
								$currentPagePath = $c->getCollectionPath();
								// Cache results of getResults() to improve page load time performance
								$cache = Core::make('cache/expensive');
								$cacheItem = $cache->getItem('header_navigation_pages');
								if ($cacheItem ->isMiss())
								{
									$cacheItem->lock();
									$home = Page::getByID($c->getHomePageID());
									$list = new PageList();
									$list->filterByParentID($home->getCollectionID());
									// $list->ignorePermissions();
									$list->sortByDisplayOrder();
									$results = $list->getResults();

									$cacheItem->set($results);
									$cacheItem->expiresAfter(7200);
									$cache->save($cacheItem);

								} else {
									$results = $cacheItem->get();
								}


								$p = Page::getCurrentPage();
								if(!$p->isError() && $p->getCollectionID() == HOME_CID)
								{
									//It is the home page
									$active = true;
								} else { //other page
									$active = false;
								}

								?>
								<!--::include Home page link in Nav::-->
								<li class="nav-item <?php if ($active) { ?>active<?php } ?>">
										<a class="nav-link" href="<?=URL::to('/')?>">Home</a>
								</li>
								<?php
								foreach($results as $page)
									{
										$active = false;
										if (strpos($page->getCollectionPath(), $currentPagePath) === 0)
										{
											$active = true;
										}
										?>
										<li class= "nav-item <?php if ($active) { ?>active<?php } ?>">
											<a class="nav-link" href="<?=$page->getCollectionLink() ?>"> <?=$page->getCollectionName() ?></a>
										</li>
									<?php } ?>
							</ul>
						</div>
					</nav>
				</div>
			</div>
			 <!--::menu section end::-->

		<!--::banner section start::-->
   	<!---<section class="banner_part d-flex flex-grow"> -->
		<section class="wrap">
			<div class="container banner_image">

					<!-- Lead in page Image -->
					<?php
						$areaHdrImage = new Area('Image');
						$areaHdrImage->display($c);
					?>

						<!-- <div class="container banner_nav"> -->
							<!-- Section navigation buttons -->
							<div class="navcontainer justify-content-center">
								<div class="banner_text">
									<!-- Lead in page Title, Welcome msg and section navigation buttons -->
									<?php
										$areaHdrBanner = new Area('Banner');
										$areaHdrBanner->display($c);
									?>
                </div>

								<ul class = "banner_nav">

									<?php
									$nh= Loader::helper('navigation');
									$c = Page::getCurrentPage();
									$ctitle = $c->getCollectionName();
									$currentPagePath = $c->getCollectionPath();
									$plist = new PageList();
									$plist->filterByParentID($c->getCollectionID($oneLevelOnly = TRUE));
                  $plist->sortByDisplayOrder();
                  $subPages = $plist->get(0);
									$pages = $plist->getResults();
									?>

									<!--::include Parent in Navigation buttons::-->
									<!-- <div class="banner_nav_list_item active">-->
									<div class="element active">
										<a href="<?php echo $nh->getLinkToCollection($c)?>"><?php echo $ctitle?></a>
									</div>
								  <?php
 									foreach ($pages as $page) {
 								 		$title = $page->getCollectionName();
 								 		$is_active_page = $page->getCollectionID() == $c->getCollectionID(); ?>
			 							<div class="element <?php echo $is_active_page ? ' active' : '';  ?>"><a href="<?php echo $nh->getLinkToCollection($page)?>"><?php echo $title?></a>
                    </div>
                    <?php
                    if (count($page->getChildren()) > 0) { 
                    ?>
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle<?= $page->isActive() ? " active" : ""; ?>" data-concrete-toggle="dropdown" target="<?=$controller->getPageItemNavTarget($page)?>" href="<?php echo $page->getUrl(); ?>">
                          <?=$page->getName()?>
                        </a>
                        <ul class="dropdown-menu">
                          <?php foreach ($page->getChildren() as $dropdownChild) { ?>
                              <li><a class="dropdown-item<?= $dropdownChild->isActive() ? " active" : ""; ?>" target="<?=$controller->getPageItemNavTarget($dropdownChild)?>" href="<?=$dropdownChild->getUrl()?>"><?=$dropdownChild->getName()?></a></li>
                                  <?php } ?>
                        </ul>
                      </li>
                      <?php } else { ?>
                          <li class="nav-item"><a class="nav-link<?= $page->isActive() ? " active" : ""; ?>" target="<?=$thisTarget ?>" href="<?=$thisLink?>"><?=$page->getName()?></a></li>
                            <?php } ?>
                        <?php } ?> 
									<?php } ?>
								 </ul>
							</div>
	          </div>
      </div>

    </section>
   	<!--::banner section end::-->

  </header>
