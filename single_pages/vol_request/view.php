<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<script src="https://cdn.ravenjs.com/3.20.1/raven.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
	Raven.config('https://606f3fd66dd04223a83abba161de7248@sentry.io/247542').install();
</script>

<script type="text/javascript">
$(document).ready(function() {
	$('#volRequestModal').on('hidden.bs.modal', function (e) {
			$('#volRequestModal').removeData('bs.modal');
			$('#volRequestModal').find('.modal-content').empty;
	});
	$('#dialogSuccessModal').on('show.bs.modal', function(e) {
			$('#dialog-title').html('Registration Success');
			$('#dialog-message').html('You registration request has been submitted');
	});
	$('#dialogFailureModal').on('show.bs.modal', function(e) {
			$('#dialog-title').html('Registration Failure');
			$('#dialog-message').html('Oh this is embarrasing, something has gone wrong please try again');
	});
});

function volRequestFunction() {
	$('#volRequestModal').modal('show');
	gtag('event', 'begin_checkout', {'event_label': 'volunteer', 'items': [{'category': 'volunteer'}]});
};

function sendVolRequest() {
	// alert ("Got here");
	var valid;
	valid = validateForm();
	if(valid) {
		var jsonData = [];
		var volrContact ={};
		volrContact.org = $("#inputOrgName").val();
		volrContact.branch = $("#inputBranchName").val();
		volrContact.title = $("#inputJobTitle").val();
		volrContact.descrip = $("#inputJobDescript").val();
		volrContact.skills = $("#inputJobSkills").val();
		volrContact.supervisor = $("#inputSupervisorName").val();
		volrContact.jobadd1 = $("#inputJobAddress1").val();
		volrContact.jobadd2 = $("#inputJobAddress2").val();
		volrContact.jobsub = $("#inputJobSuburb").val();
		volrContact.jobcity = $("#inputJobCity").val();
		volrContact.jem = $("#inputContactEmail").val();
		volrContact.jtel = $("#inputContactTel").val();
		volrContact.ref = $("#inputReferralPerson").val();
		volrContact.training = $("#inputTraining").val();
		volrContact.hstraining = $("#inputHSTraining").val();
		if ($("#inputHasReimburse").is(":checked")) {
			volrContact.reimbursement = 1;
		} else {
			volrContact.reimbursement = 0;
		}
		volrContact.dayshours = $("#inputDayHours").val();
		volrContact.personality = $("#inputVolPersonatity").val();
		if ($("#inputReqPoliceChk").is(":checked")) {
			volrContact.policeck = 1;
		} else {
			volrContact.policeck = 0;
		}
		if ($("#inputOfferOnLine").is(":checked")) {
			volrContact.online = 1;
		} else {
			volrContact.online = 0;
		}
		if ($("#inputC19vac").is(":checked")) {
			volrContact.c19vac = 1;
		} else {
			volrContact.c19vac = 0;
		}
		if ($("#inputIsOngoingRol").is(":checked")) {
			volrContact.ong = 1;
		} else {
			volrContact.ong = 0;
		}
		if ($("#inputIsShortTermRol").is(":checked")) {
			volrContact.st = 1;
		} else {
			volrContact.st = 0;
		}
		if ($("#inputIsSpecialEvent").is(":checked")) {
			volrContact.sp = 1;
		} else {
			volrContact.sp = 0;
		}
		if ($("#inputIsWeekendsOnly").is(":checked")) {
			volrContact.wes = 1;
		} else {
			volrContact.wes = 0;
		}
		/* Removed start and endate at VW request 24 June 2023
		var stdteTmp = getDate($("#inputStartDate").val());
		if (stdteTmp == null) {
			volrContact.stdte = "";
		} else {
			volrContact.stdte = formatDate(stdteTmp);
		}
		var edteTmp = getDate($("#inputEndDate").val());
		if (edteTmp == null) {
			volrContact.edte = "";
		} else {
			volrContact.edte = formatDate(edteTmp);
		}
		*/
		jsonData.push({volrContact: volrContact});
		//alert (JSON.stringify(jsonData));

		$.ajax({
			type: 'POST',
			url: "<?=$view->action('reqVolunteers')?>",
			datatype: 'json',
			data: {reqData: JSON.stringify(jsonData)},
			cache: false,
		}).done(function(data, textStatus, jqXHR){
			//var result = $.parseJSON(data);
			//alert(textStatus);
			$('#volRequestModal').modal('hide');
			$('#dialogSuccessModal').modal('show');
			gtag('event', 'purchase', {'event_label': 'volunteer', 'items': [{'category': 'volunteer'}]});
		}).fail(function(jqXHR, textStatus, errorThrown){
			// alert(errorThrown);
			$('#volRequestModal').modal('hide');
			$('#dialogFailureModal').modal('show');
		});
	} else {
		$('#dialogMissingItemsModal').modal('show');
	};
};

function validateForm() {
	var valid = true;
	// removed , #volRequestModal #inputStartDate, #volRequestModal #inputEndDate at VW request 24 June2023
	$("#volRequestModal input[required=required], #volRequestModal textarea[required=required], #volRequestModal select[required=required], #volRequestModal input[type=email], #volRequestModal input[type=number]").each(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).parent().find("> span.help-block").text("");
		if($(this).attr("required")=="required" && !$(this).val()){
			$(this).parent().parent().addClass('has-error');
			$(this).parent().find("> span.help-block").text("This field is required");
			valid = false;
		} else if($(this).attr("type")=="email"  && !$(this).val().match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)){
			$(this).parent().parent().addClass('has-error');
			$(this).parent().find("> span.help-block").text("Invalid email address");
			valid = false;
		} else if($(this).attr("id")=="inputContactTel"  && !$(this).val().match(/^\(?([0-9]{2,4})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{0,4})$/)){
			$(this).parent().parent().addClass('has-error');
			$(this).parent().find("> span.help-block").text("Only a single valid contact phone number can be submitted, e.g (04)1234567 or (027)-123-4567 or 041234567 or 04 123 4567");
			valid = false;
		} else if($(this).attr("type")=="number" && $(this).val()!="" && !$(this).val().match(/^\d+$/)){
			$(this).parent().parent().addClass('has-error');
			$(this).parent().find("> span.help-block").text("A number is expected");
			valid = false;
		/* removed start and end date at VW request 24 June 2023
		} else if($(this).attr("id")=="inputStartDate" && !isDate($(this).val())){
			$(this).parent().parent().addClass('has-error');
			$(this).parent().find("> span.help-block").text("Invalid date");
			valid = false;
		} else if($(this).attr("id")=="inputEndDate" && !isDate($(this).val())){
			$(this).parent().parent().addClass('has-error');
			$(this).parent().find("> span.help-block").text("Invalid date");
			valid = false;
		*/
		}
	});

	return valid;
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

function getDate(txtDate)
{
  var currVal = txtDate;
  if(currVal == '')
    return null;

  //Declare Regex
  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray != null) {
    //Checks for dd/mm/yyyy format.
    var dtMonth = dtArray[3];
    var dtDay= dtArray[1];
    var dtYear = dtArray[5];

    if (dtMonth < 1 || dtMonth > 12)
      return null;
    else if (dtDay < 1 || dtDay> 31)
      return null;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return null;
    else if (dtMonth == 2)
    {
      var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
      if (dtDay> 29 || (dtDay ==29 && !isleap))
          return null;
    }
    return new Date(dtYear, dtMonth - 1, dtDay);
  }

  var rxDatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray != null) {
    //Checks for yyyy-mm-dd format.
    var dtMonth = dtArray[3];
    var dtDay= dtArray[5];
    var dtYear = dtArray[1];

    if (dtMonth < 1 || dtMonth > 12)
      return null;
    else if (dtDay < 1 || dtDay> 31)
      return null;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return null;
    else if (dtMonth == 2)
    {
      var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
      if (dtDay> 29 || (dtDay ==29 && !isleap))
          return null;
    }
    return new Date(dtYear, dtMonth - 1, dtDay);
  }

  return null;
}

function isDate(txtDate)
{
  var currVal = txtDate;
  if(currVal == '')
    return true;

  //Declare Regex
  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray != null) {
    //Checks for dd/mm/yyyy format.
    var dtMonth = dtArray[3];
    var dtDay= dtArray[1];
    var dtYear = dtArray[5];

    if (dtMonth < 1 || dtMonth > 12)
      return false;
    else if (dtDay < 1 || dtDay> 31)
      return false;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
    else if (dtMonth == 2)
    {
      var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
      if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
    }
    return true;
  }

  var rxDatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray != null) {
    //Checks for yyyy-mm-dd format.
    var dtMonth = dtArray[3];
    var dtDay= dtArray[5];
    var dtYear = dtArray[1];

    if (dtMonth < 1 || dtMonth > 12)
      return false;
    else if (dtDay < 1 || dtDay> 31)
      return false;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
    else if (dtMonth == 2)
    {
      var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
      if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
    }
    return true;
  }

  return false;
}


/*$(function() {
	$( document ).tooltip({
		position: {my: "left top", at: "right top"},
		items: "input[required=required], text[required=required], textarea[required=required], select[required=required]",
		content: function() {return $(this).attr( "title" );}
	});
});*/

</script>
<div class="main-container text-center">
		<div class="row">

			<div class="col-md-6">
     		<h3 style="text-align: center;">Request for Volunteers</h3>
				<?php
	  			$areaContent0 = new Area('Content0');
	  			$areaContent0->display($c);
	  		?>
       	<h3 class="bg-secondary white" style="center">Policies for Volunteer Role Registration</h3>
        <?php
  				$areaContent1 = new Area('Content1');
  				$areaContent1->display($c);
  			?>
				<p><em>Please confirm your understanding and acceptance of the above:</em></p>
					<div class="text-center">
						<!-- Button trigger modal -->
					<button class="btn btn-primary shadow" id="btnMbrRegister" onclick="volRequestFunction()">Confirm</button>
					</div>
			</div>
    <div class="col-md-6">
			<?php
				$areaRightContent = new Area('RightContent');
				$areaRightContent->display($c);
			?>
		</div>
	</div>
</div>

		<!-- Membership submission Modal -->
		<div class="modal fade" id="volRequestModal" tabindex="-1" role="dialog" aria-labelledby="mbrRegModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		    		<div class="modal-content">
		         	<!-- Modal Header -->
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal">
		                	<span aria-hidden="true">&times;</span>
		                  <span class="sr-only">Close</span>
		                </button>
		                <h4 class="modal-title" id="mbrRegModalLabel">Request for Volunteers</h4>
		            </div>
		            <!-- Shortlist Modal Body -->
		            <div class="modal-body">
		            	<form class="form-horizontal" id="mbrRegisterForm">
											<div class="subHeading">Organisation Details</div>
		                  <div class="form-group required">
		                    	<label  class="col-sm-4 control-label" for="inputOrgName">Organisation Name</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputOrgName" placeholder="Organisation Name" required="required"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
		                  <div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputBranchName">Branch Name</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputBranchName" placeholder="Branch Name"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="subHeading">Role Details</div>
											<div class="form-group required">
		                    	<label  class="col-sm-4 control-label" for="inputJobTitle">Volunteer role title</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputJobTitle" placeholder="Title" required="required"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group required">
		                    	<label  class="col-sm-4 control-label" for="inputJobDescript">Outline of duties. Please also provide a role description</label>
		                    	<div class="col-sm-8">
														<textarea class="form-control" id="inputJobDescript" rows="5" placeholder="Job Description" required="required"></textarea>
			  		   <span class="help-block"></span>
													</div>
		                  </div>
											<div class="form-group required">
		                    	<label  class="col-sm-4 control-label" for="inputJobSkills">Any special skills or education required</label>
		                    	<div class="col-sm-8">
														<textarea class="form-control" id="inputJobSkills" rows="5" placeholder="Skills Required" required="required"></textarea>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group required">
		                    	<label  class="col-sm-4 control-label" for="inputSupervisorName">Person to whom volunteer is responsible</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputSupervisorName" placeholder="Supervisor Names" required="required"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
													<label  class="col-sm-4 control-label" for="inputJobAddress1">Job Street address</label>
												  <div class="col-sm-8">
															  <input type="text" class="form-control" id="inputJobAddress1" placeholder="Job Street address"/>
			  		   <span class="help-block"></span>
													</div>
											</div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputJobAddress2">Address Line 2</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputJobAddress2" placeholder="Job Address Line 2"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputJobSuburb">Job Suburb</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputJobSuburb" placeholder="Job Suburb"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputJobCity">City</label>
		                    	<div class="col-sm-8">
									<select class="form-control" id="inputJobCity" >
										<option selected="" value="" >Select</option>
										<?php
											foreach($cities as $city) { ?>
												<option value="<?php echo $city["city"]; ?>"><?php echo $city["city"]; ?></option>
											<?php }
										?>
									</select>
									<span class="help-block"></span>
		                    	</div>
		                  </div>
		                  <div class="form-group required">
		                    	<label  class="col-sm-4 control-label" for="inputContactEmail">Email</label>
		                    	<div class="col-sm-8">
		                        <input type="email" class="form-control" id="inputContactEmail" placeholder="Email" required="required"/>
			  		   							<span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group required">
													<label  class="col-sm-4 control-label" for="inputContactTel">Contact Telephone</label>
													<div class="col-sm-8">
															<input type="text" class="form-control" id="inputContactTel" placeholder="Single Contact Telephone Only" required="required"/>
			  		   								<span class="help-block"></span>
													</div>
											</div>
											<div class="form-group required">
													<label  class="col-sm-4 control-label" for="inputReferralPerson">Referrals Person</label>
												  <div class="col-sm-8">
															<input type="text" class="form-control" id="inputReferralPerson" placeholder="Referrals Person" required="required"/>
			  		   								<span class="help-block"></span>
													</div>
											</div>
											<div class="form-group required">
													<label  class="col-sm-4 control-label" for="inputTraining">What training is provided (Please explain when and where the training is, length of training, whether there is any cost to the volunteer)</label>
												  <div class="col-sm-8">
														<textarea class="form-control" id="inputTraining" rows="6" placeholder="Training" required="required"></textarea>
			  		   							<span class="help-block"></span>
													</div>
											</div>
											<div class="form-group required">
													<label  class="col-sm-4 control-label" for="inputHSTraining">What Health & Safety training is provided</label>
												  <div class="col-sm-8">
														<textarea class="form-control" id="inputHSTraining" rows="6" placeholder="Health & Safety Training" required="required"></textarea>
			  		   							<span class="help-block"></span>
													</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Requires a police check on volunteers</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputReqPoliceChk" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Volunteers must be fully Covid-19 vaccinated</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputC19vac" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
													<div class="col-sm-4">
														<div class="checkbox left-checkbox">
															<label>Do NOT make this role available for on-line registration</label>
														</div>
													</div>
														<div class="col-sm-8">
															<input type="checkbox" id="inputOfferOnLine" value ="">
						            		</div>
											</div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputDayHours">Specific days and hours</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputDayHours" placeholder="Days and Hours"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputVolPersonatity">Any special personality requirements</label>
		                    	<div class="col-sm-8">
														<textarea class="form-control" id="inputVolPersonatity" rows="5" placeholder="Special personality requirements" required="required"></textarea>
			  		   <span class="help-block"></span>
													</div>
		                  </div>
											<div class="form-group">
												<div class="col-sm-4">
													<div class="checkbox left-checkbox">
          									<label>Reimburses travel expenses</label>
													</div>
        								</div>
		                    	<div class="col-sm-8">
														<input type="checkbox" id="inputHasReimburse" value ="">
		                    	</div>
		                  </div>
						  <!-- Removed requirement for users to input start and enddates at VW request 24 June 2023
											<div class="form-group">
												<label  class="col-sm-4 control-label" for="inputStartDate">Has a start date of: (dd/mm/yyyy)</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="inputStartDate"/>
			  		   <span class="help-block"></span>
												</div>
											</div>
											<div class="form-group">
												<label  class="col-sm-4 control-label" for="inputEndDate">Has an end date of: (dd/mm/yyyy)</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="inputEndDate"/>
			  		   <span class="help-block"></span>
												</div>
											</div>
							-->				
											<div class="form-group">
												<label class="col-sm-4 control-label" for="typeRadios">Role Type: </label>
												<div class="col-sm-6">
													<label>
					                    <input type="radio" name="typeRadios" id="inputIsOngoingRol" checked="checked">Is an on-going role
													</label>
					                <label>
					                    <input type="radio" name="typeRadios" id="inputIsShortTermRol">Is a short-term role
					                </label>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Is a special event or project</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputIsSpecialEvent" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Runs weekends only</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputIsWeekendsOnly" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
													<div class="col-sm-6">
		                      	<button type="button" class="btn btn-primary center-block" onClick="sendVolRequest();" >Register Volunteer Role</button>
													</div>
													<div class="col-sm-6">
														<button type="button" class="btn btn-primary center-block" data-dismiss="modal">Close</button>
		                    	</div>
		                  </div>
		            </form>
						</div>
					</div>
			</div>
		</div>

		<!-- Display registration success dialog modal-->
		  <div class="modal fade" id="dialogSuccessModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="success-dialog-title">Volunteer Role Submission Success</h4>
		        </div>
		        <div class="modal-body" id="success-dialog-message">
							<p> Your Volunteeer Role request has been recorded</p>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
		        </div>
		      </div>
		    </div>
		  </div>

			<!-- Display registration Failure dialog modal-->
			  <div class="modal fade" id="dialogFailureModal" role="dialog">
			    <div class="modal-dialog modal-sm">
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title alert alert-warning" id="failure-dialog-title">Volunteer Role Submission Failure</h4>
			        </div>
			        <div class="modal-body" id="failure-dialog-message">
								<p> Oh this is embarrasing, something has gone wrong please try to resubmit your registration</p>
			        </div>
			        <div class="modal-footer">
			          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			        </div>
			      </div>
			    </div>
			  </div>

				<!-- Display missing form mandatory items dialogue-->
					<div class="modal fade" id="dialogMissingItemsModal" role="dialog">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title alert alert-warning" id="missing-dialog-title">The form is incomplete</h4>
								</div>
								<div class="modal-body" id="missing-dialog-message">
									<p>Some fields require your attention</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
