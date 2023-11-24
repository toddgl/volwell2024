<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<?php
$view->inc('elements/home_header.php');
?>

<div class="container">
	<div class="row">
		<?php
			$areaSearch = new Area('Introduction');
			$areaSearch->display($c);
		?>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<?php
				$areaLContent1 = new Area('Left_Content1');
				$areaLContent1->display($c);
			?>
		</div>
		<div class="col-md-4">
			<?php
				$areaMContent1 = new Area('Middle_Content1');
				$areaMContent1->display($c);
			?>
		</div>
		<div class="col-md-4">
			<?php
				$areaRContent1 = new Area('Right_Content1');
				$areaRContent1->display($c);
			?>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<?php
			$areaMiddle = new Area('Middle');
			$areaMiddle->display($c);
		?>
	</div>
</div>
<div class="container">
	<div class="row bg-separator">
		<?php
			$areaSeparator = new Area('Separator');
			$areaSeparator->display($c);
		?>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<?php
				$areaNews = new Area('Events');
				$areaNews->display($c);
			?>
		</div>
		<div class="col-md-6">
			<?php
				$areaNews = new Area('News');
				$areaNews->display($c);
			?>
		</div>
	</div>
</div>

<?php
$view->inc('elements/footer.php');
?>
