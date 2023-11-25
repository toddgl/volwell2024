<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<?php
$view->inc('elements/header.php');
?>
<div class="container-fluid">
	<div class="row">
		<div id="body">
			<?php
			Loader::element('system_errors', array('error' => $error));
			print $innerContent;
			?>
		</div>

	</div>
</div>
<?php
$view->inc('elements/footer.php');
?>
