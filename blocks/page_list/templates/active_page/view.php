<?php

	defined('C5_EXECUTE') or die(_("Access Denied."));

	$pg = Page::getCurrentPage();

	$textHelper = Loader::helper("text");
	// now that we're in the specialized content file for this block type,
	// we'll include this block type's class, and pass the block to it, and get
	// the content

 { ?>

	<div class="banner_nav_list_wrapper">
		<div class="banner_nav_list"><?php

				foreach ($pages as $page) {
					$title = $page->getCollectionName();
					$is_active_page = $page->getCollectionID() == $pg->getCollectionID(); ?>


							<div class="banner_nav_list_item<?php echo $is_active_page ? ' active' : '';  ?>"><a href="<?php echo $nh->getLinkToCollection($page)?>"><?php echo $title?></a>
								<div class="ccm-block-page-list-description"><?php

									if(!$controller->truncateSummaries) {
										echo $page->getCollectionDescription();
									} else {
										echo $textHelper->shorten($page->getCollectionDescription(),$controller->truncateChars);
									} ?>

								</div>
							</div>

					<?php

						}

								if (!$previewMode && $controller->rss) {
									$btID = $b->getBlockTypeID();
									$bt = BlockType::getByID($btID);
									$uh = Loader::helper('concrete/urls');
									$rssUrl = $controller->getRssUrl($b); ?>

								<div class="rssIcon">
									<a href="<?php echo $rssUrl?>" target="_blank"><img src="<?php echo $uh->getBlockTypeAssetsURL($bt, 'rss.png')?>" width="14" height="14" /></a>
								</div>
								<link href="<?php echo $rssUrl?>" rel="alternate" type="application/rss+xml" title="<?php echo $controller->rssTitle?>" /><?php
						} ?>

		</div>
	</div>
<?php
	}

	if ($paginate && $num > 0 && is_object($pl)) {
		$pl->displayPaging();
	}
?>
