<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<?php
$view->inc('elements/header.php');
?>
<div class="main-container text-center first-container"> <div class="row">
    <div class="col-md-3">
      <?php
        $areaRMain1 = new Area('Left_Sidebar1');
        $areaRMain1->display($c);
      ?>
    </div>
    <div class="col-md-6">
      <?php
        $areaLMain1 = new Area('Main1');
        $areaLMain1->display($c);
      ?>
    </div>
    <div class="col-md-3">
      <?php
        $areaRMain1 = new Area('Right_Sidebar1');
        $areaRMain1->display($c);
      ?>
    </div>
  </div>
</div>

<div class="main-container text-center">
  <div class="row">
    <div class="col-md-3">
      <?php
        $areaRMain2 = new Area('Left_Sidebar2');
        $areaRMain2->display($c);
      ?>
    </div>
    <div class="col-md-6">
      <?php
        $areaLMain2 = new Area('Main2');
        $areaLMain2->display($c);
      ?>
    </div>
    <div class="col-md-3">
      <?php
        $areaRMain2 = new Area('Right_Sidebar2');
        $areaRMain2->display($c);
      ?>
    </div>
  </div>
</div>

<?php
$view->inc('elements/footer.php');
?>
