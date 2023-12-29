<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<?php
$view->inc('elements/header.php');
?>
<div class="main-container first-container">
  <div class="row">
    <div class="col-sm-2">
      <?php
        $areaLMain1 = new Area('Left_Main1');
        $areaLMain1->display($c);
      ?>
    </div>
    <div class="col-sm-10">
      <?php
        $areaRMain1 = new Area('Right_Main1');
        $areaRMain1->display($c);
      ?>
    </div>
  </div>
</div>

<div class="main-container">
  <div class="row">
    <div class="col-sm-2">
      <?php
        $areaLMain2 = new Area('Left_Main2');
        $areaLMain2->display($c);
      ?>
    </div>
    <div class="col-sm-10">
      <?php
        $areaRMain2 = new Area('Right_Main2');
        $areaRMain2->display($c);
      ?>
    </div>
  </div>
</div>

<?php
$view->inc('elements/footer.php');
?>
