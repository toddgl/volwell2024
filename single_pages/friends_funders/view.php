<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<div class="main-container text-center">
    <div class="row">
      <div class="col-md-12">
        <?php
  				$areaTContent1 = new Area('Top_Content1');
  				$areaTContent1->display($c);
				?>
	  </div>
	</div>
    <div class="row">
      <div class="col-md-8">
				<?php
					$areaFundersContent = new Area('Funders_Content');
					$areaFundersContent->display($c);
				?>
        <?php
					if (empty($funders)) { ?>
        		<!-- pass -->
        <?php
					} else { ?>
						<ul>
						<?php
            foreach ($funders as $funder) {
              if (empty($funder["web"])) { ?>
                <li><?php echo $funder["name"]; ?></li>
              <?php
              } else { ?>
							<li><a href="<?php echo $funder["web"]; ?>"><?php echo $funder["name"]; ?></a></li>
              <?php }
            }?>
						<ul>
					<?php	} ?>
      </div>
      <div class="col-md-4">
        <?php
  				$areaRContent1 = new Area('Right_Content1');
  				$areaRContent1->display($c);
  			?>
      </div>
    </div>
		<div class="row">
      <div class="col-md-8">
				<?php
					$areaPremiumContent = new Area('Premium_Content');
					$areaPremiumContent->display($c);
				?>
        <?php
					if (empty($premiumfriends)) { ?>
        		<!-- pass -->
        <?php
					} else { ?>
						<ul>
						<?php
            foreach ($premiumfriends as $premiumfriend) {
              if (empty($premiumfriend["web"])) { ?>
                <li><?php echo $premiumfriend["name"]; ?></li>
              <?php
              } else { ?>
							<li><a href="<?php echo $premiumfriend["web"]; ?>"><?php echo $premiumfriend["name"]; ?></a></li>
              <?php }
            }?>
						<ul>
					<?php	} ?>
      </div>
      <div class="col-md-4">
         <!-- Unused -->
      </div>
    </div>
		<div class="row">
      <div class="col-md-8">
				<?php
					$areaBusinessContent = new Area('Business_Content');
					$areaBusinessContent->display($c);
				?>
        <?php
					if (empty($businessfriends)) { ?>
        		<!-- pass -->
        <?php
					} else { ?>
						<ul>
						<?php
            foreach ($businessfriends as $businessfriend) {
              if (empty($businessfriend["web"])) { ?>
                <li><?php echo $businessfriend["name"]; ?></li>
              <?php
              } else { ?>
							<li><a href="<?php echo $businessfriend["web"]; ?>"><?php echo $businessfriend["name"]; ?></a></li>
              <?php }
            }?>
						<ul>
					<?php	} ?>
      </div>
      <div class="col-md-4">
         <!-- Unused -->
      </div>
    </div>
		<div class="row">
			<div class="col-md-8">
				<?php
					$areaInKindContent = new Area('InKind_Content');
					$areaInKindContent->display($c);
				?>
				<?php
					if (empty($inkindfriends)) { ?>
						<!-- pass -->
				<?php
					} else { ?>
						<ul>
						<?php
						foreach ($inkindfriends as $inkindfriend) {
              if (empty($inkindfriend["web"])) { ?>
                <li><?php echo $inkindfriend["name"]; ?></li>
              <?php
              } else { ?>
							  <li><a href="<?php echo $inkindfriend["web"]; ?>"><?php echo $inkindfriend["name"]; ?></a></li>
					<?php }
            }?>
						<ul>
					<?php	} ?>
			</div>
			<div class="col-md-4">
				 <!-- Unused -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<?php
					$areaIndividualContent = new Area('Individual_Content');
					$areaIndividualContent->display($c);
				?>
				<?php
					if (empty($individualfriends)) { ?>
						<!-- pass -->
				<?php
					} else { ?>
            <ul>
						<?php
						foreach ($individualfriends as $individualfriend) {
              if (empty($individualfriend["web"])) { ?>
                <li><?php echo $individualfriend["name"]; ?></li>
              <?php
              } else { ?>
							<li><a href="<?php echo $individualfriend["web"]; ?>"><?php echo $individualfriend["name"]; ?></a></li>
              <?php }
            }?>
						<ul>
					<?php	} ?>
			</div>
			<div class="col-md-4">
				 <!-- Unused -->
			</div>
		</div>
</div>
