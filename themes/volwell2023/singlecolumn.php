<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<?php
$view->inc('elements/header.php');
?>
<div class="container">
			<?php
				$areaMain1 = new Area('Main1');
				$areaMain1->display($c);
			?>
</div>

<div class="container">
			<?php
				$areaMain2 = new Area('Main2');
				$areaMain2->display($c);
			?>
</div>

<div class="container">
			<?php
				$areaMain3 = new Area('Main3');
				$areaMain3->display($c);
			?>
</div>

<?php
$view->inc('elements/footer.php');
?>