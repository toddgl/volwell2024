<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<script src="https://cdn.ravenjs.com/3.20.1/raven.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
	Raven.config('https://606f3fd66dd04223a83abba161de7248@sentry.io/247542').install();
</script>

	<div class="container">
		<h3> Your registration details</h3>
		<div class="col-md-3">First Name:</div>
		<div class="col-md-3"><?php echo $webregister['firstname']; ?>&nbsp;</div>
		<div class="col-md-3">Last Name:</div>
		<div class="col-md-3"><?php echo $webregister['lastname']; ?>&nbsp;</div>
		<div class="col-md-3">Email:</div>
		<div class="col-md-3"><?php echo $webregister['email']; ?>&nbsp;</div>
		<div class="col-md-3">Day time phone:</div>
		<div class="col-md-3"><?php echo $webregister['phone']; ?>&nbsp;</div>
		<div class="col-md-3">Phone:</div>
		<div class="col-md-3"><?php echo $webregister['add1']; ?>&nbsp;</div>
		<div class="col-md-3">Address Line 1:</div>
		<div class="col-md-3"><?php echo $webregister['add2']; ?>&nbsp;</div>
		<div class="col-md-3">Address Line 2:</div>
		<div class="col-md-3"><?php echo $webregister['suburb']; ?>&nbsp;</div>
		<div class="col-md-3">City:</div>
		<div class="col-md-3"><?php echo $webregister['city']; ?>&nbsp;</div>
		<div class="col-md-3">Postcode:</div>
		<div class="col-md-3"><?php echo $webregister['pcode']; ?>&nbsp;</div>
		<div class="col-md-3">Age band:</div>
		<div class="col-md-3"><?php echo $webregister['ageband']; ?>&nbsp;</div>
		<div class="col-md-3">Gender:</div>
		<div class="col-md-3"><?php echo $webregister['gender']; ?>&nbsp;</div>
		<div class="col-md-3">Ethnicity:</div>
		<div class="col-md-3"><?php echo $webregister['ethnicity']; ?>&nbsp;</div>
		<div class="col-md-3">Recent migrant:</div>
		<div class="col-md-3"><?php echo $webregister['migrant']; ?>&nbsp;</div>
		<div class="col-md-3">Refugee status:</div>
		<div class="col-md-3"><?php echo $webregister['refugee']; ?>&nbsp;</div>
		<div class="col-md-3">Referral:</div>
		<div class="col-md-3"><?php echo $webregister['heard']; ?>&nbsp;</div>
		<div class="col-md-3">Work status:</div>
		<div class="col-md-3"><?php echo $webregister['labour']; ?>&nbsp;</div>
		<div class="col-md-3">Reason:</div>
		<div class="col-md-3"><?php echo $webregister['reason']; ?>&nbsp;</div>
		<div class="col-md-3">VW office:</div>
		<div class="col-md-3"><?php echo $webregister['off']; ?>&nbsp;</div>
		<div class="col-md-3">Emergency list:</div>
		<div class="col-md-3"><?php echo $webregister['emvol']; ?>&nbsp;</div>
		<div class="col-md-12">
				<div class="row">
					<?php
						if (!empty($webrefs)) {
							foreach($webrefs as $webref) { ?>
								<div class="dk-blue-wrapper white">
									<h3><?php echo $webref["job_data"]["title"]; ?></h3>
								</div>
								<div class="blue-wrapper">
								<table class="table">
								<thead>
								<tr>
								<th style="width: 70%"><?php echo $webref["job_data"]["jobsub"]; ?></th>
    							<th style="width: 30%">Role ID: <?php echo $webref["job_data"]["ID"]; ?></th>
								</tr>
								</thead>
								</table>
								<p><?php echo $webref["job_data"]["descrip"]; ?></p>
								<p>Days, Hours for role: <?php echo $webref["job_data"]["dayshours"]; ?></p>
								</div>
					<?php }
					} ?>
				</div>
		</div>
</div>
