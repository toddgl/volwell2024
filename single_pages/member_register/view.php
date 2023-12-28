<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<script src="https://cdn.ravenjs.com/3.20.1/raven.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
	Raven.config('https://606f3fd66dd04223a83abba161de7248@sentry.io/247542').install();
</script>

<script type="text/javascript">
$(document).ready(function() {
		$('#mbrRegisterModal').on('hidden.bs.modal', function () {
			$('#mbrRegisterModal').removeData('bs.modal');
			$('#mbrRegisterModal').find('.modal-content').empty;
	});
		document.getElementById("feeAmount").readOnly = true;
		$("#orgcountlist").hide();
		$('input:radio[name="affiliated"]').change(function() {
			if ($(this).val() == 'Yes') {
				$('#feeAmount').val('POA');
				$("#labelmultibranch").hide();
				$("#radiomultibranch").hide();
				$("#labelOrgTurnOver").hide();
				$("#orgTurnOverlist").hide();
				$("#orgTurnOverlist").get(0).selectedIndex = 0;
				$("#orgcountlist").get(0).selectedIndex = 0;
			} else {
				$('#feeAmount').val('');
				$("#orgTurnOverlist").get(0).selectedIndex = 0;
				$("#orgcountlist").get(0).selectedIndex = 0;
				$("#labelmultibranch").show();
				$("#radiomultibranch").show();
				$("#labelOrgTurnOver").show();
				$("#orgcountlist").show();
			}
		});
		$('input:radio[name="multibranch"]').change(function() {
			if ($(this).val() == 'Yes') {
				$("#orgcountlist").show();
				$("#labelOrgTurnOver").hide();
				$("#orgTurnOverlist").hide();
				$("#orgTurnOverlist").get(0).selectedIndex = 0;
			} else {
				$("#orgcountlist").hide();
				$("#labelOrgTurnOver").show();
				$("#orgTurnOverlist").show();
				$("#orgcountlist").get(0).selectedIndex = 0;
			}
		});
		$("#orgcountlist").change (function () {
			$('#feeAmount').val(this.value);
		});
		$("#orgTurnOverlist").change (function () {
			$('#feeAmount').val(this.value);
		});

});


function mbrRegisterFunction() {
	$('#mbrRegisterModal').modal('show');
	gtag('event', 'begin_checkout', {'event_label': 'member', 'items': [{'category': 'member'}]});
};

function sendMbrRegister() {
	// alert ("Got here");
	var valid;
	valid = validateForm();
	if(valid) {
		var jsonData = [];
		var mbrContact ={};
		mbrContact.name = $("#inputOrgName").val();
		mbrContact.branch = $("#inputBranchName").val();
		mbrContact.pobox = $("#inputPOBox").val();
		mbrContact.address = $("#inputAddress1").val();
		mbrContact.address2 = $("#inputAddress2").val();
		mbrContact.suburb = $("#inputSuburb").val();
		mbrContact.city = $("#cityList").val();
		mbrContact.pcode = $("#inputPostCode").val();
		mbrContact.phone = $("#inputDayPhone").val();
		mbrContact.mobile = $("#inputMobile").val();
		mbrContact.email = $("#inputEmail").val();
		if ($("#affiliatedyes").is(":checked")) {
			mbrContact.affiliated = "Yes";
		} else {
			mbrContact.affiliated = "No";
		}
		if ($("#multibranchyes").is(":checked")) {
			mbrContact.multibranch = "Yes";
		} else {
			mbrContact.multibranch = "No";
		}
		if ($("#orgcountlist")[0].selectedIndex <= 0) {
			mbrContact.branches = "N/A";
		} else {
			mbrContact.branches = $("#orgcountlist :selected").text();
		}
		if ($("#orgTurnOverlist")[0].selectedIndex <= 0) {
			mbrContact.grossannual = "N/A";
		} else {
			mbrContact.grossannual = $("#orgTurnOverlist :selected").text();
		}
		mbrContact.memfee = $("#feeAmount").val();
		mbrContact.president = $("#inputChairPerson").val();
		mbrContact.ceo = $("#inputExecutivePerson").val();
		mbrContact.volcoord = $("#inputCoordinator").val();
		mbrContact.hours = $("#inputOfficeDetails").val();
		if ($("#inputManagerPaid").is(":checked")) {
			mbrContact.vmpaid = 1;
		} else {
			mbrContact.vmpaid = 0;
		}
		if ($("#fullTime").is(":checked")) {
			mbrContact.vmwork = 1;
		} else {
			mbrContact.vmwork = 0;
		}
		mbrContact.nopaid = $("#inputPaidStaff").val();
		mbrContact.novol = $("#inputVolunteers").val();
		mbrContact.mission = $("#inputMission").val();
		mbrContact.services = $("#inputServices").val();
		if ($("#inputDisableAccess").is(":checked")) {
			mbrContact.disabled = 1;
		} else {
			mbrContact.disabled = 0;
		}
		if ($("#inputESOL").is(":checked")) {
			mbrContact.esl = 1;
		} else {
			mbrContact.esl = 0;
		}
		if ($("#inputFundsBudget").is(":checked")) {
			mbrContact.volbudget = 1;
		} else {
			mbrContact.volbudget = 0;
		}
		if ($("#inputRoleDesc").is(":checked")) {
			mbrContact.vjd = 1;
		} else {
			mbrContact.vjd = 0;
		}
		if ($("#inputVolTrng").is(":checked")) {
			mbrContact.train = 1;
		} else {
			mbrContact.train = 0;
		}
		if ($("#inputVolInterviews").is(":checked")) {
			mbrContact.volint = 1;
		} else {
			mbrContact.volint = 0;
		}
		if ($("#inputVolPerf").is(":checked")) {
			mbrContact.veval = 1;
		} else {
			mbrContact.veval = 0;
		}
		if ($("#inputVolReimb").is(":checked")) {
			mbrContact.reimb = 1;
		} else {
			mbrContact.reimb = 0;
		}
		if ($("#inputVolRefs").is(":checked")) {
			mbrContact.vref = 1;
		} else {
			mbrContact.vref = 0;
		}
		if ($("#inputHSPolicy").is(":checked")) {
			mbrContact.hspolicy = 1;
		} else {
			mbrContact.hspolicy = 0;
		}
		if ($("#inputHSTraining").is(":checked")) {
			mbrContact.hstraining = 1;
		} else {
			mbrContact.hstraining = 0;
		}
		if ($("#inputHSRisks").is(":checked")) {
			mbrContact.hsrisks = 1;
		} else {
			mbrContact.hsrisks = 0;
		}
		jsonData.push({mbrContact: mbrContact});
		// alert (JSON.stringify(jsonData));

		$.ajax({
			type: 'POST',
			url: "<?=$view->action('regMember')?>",
			datatype: 'json',
			data: {regData: JSON.stringify(jsonData)},
			cache: false,
		}).done(function(data, textStatus, jqXHR){
			//var result = $.parseJSON(data);
			//alert(textStatus);
			$('#mbrRegisterModal').modal('hide');
			$('#dialogSuccessModal').modal('show');
			gtag('event', 'purchase', {'event_label': 'member', 'items': [{'category': 'member'}]});
		}).fail(function(jqXHR, textStatus, errorThrown){
			// alert(errorThrown);
			$('#mbrRegisterModal').modal('hide');
			$('#dialogFailureModal').modal('show');
		});
		} else {
		$('#dialogMissingItemsModal').modal('show');
	};
};

function validateForm() {
	var valid = true;
	$("#mbrRegisterModal input[required=required], #mbrRegisterModal textarea[required=required], #mbrRegisterModal select[required=required], #mbrRegisterModal input[type=email], #mbrRegisterModal input[type=number], #mbrRegisterModal input[type=date]").each(function() {
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
		} else if($(this).attr("type")=="number" && $(this).val()!="" && !$(this).val().match(/^\d+$/)){
			$(this).parent().parent().addClass('has-error');
			$(this).parent().find("> span.help-block").text("A number is expected");
			valid = false;
		} else if($(this).attr("type")=="date" && !isDate($(this).val())){
			$(this).parent().parent().addClass('has-error');
			$(this).parent().find("> span.help-block").text("Invalid date");
			valid = false;
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

  <div class="container">
	<div class="row">
		<div class="bg-lred vol-font white shadow">
     	<h3 style="text-align: center;">Non-profit Organisation Registration</h3>
    </div>
  </div>
  <div class="main-container text-center">
  	<div class="row">
  		<div class="col-md-7">
       	<h3 class="bg-secondary white" style="center">Policies for registration</h3>
				<?php
  				$areaContent1 = new Area('Content1');
  				$areaContent1->display($c);
  			?>
				<p><em>Please confirm your understanding and acceptance of the above:</em></p>
				<div class="form-group">
					<div class="text-center">
						<!-- Button trigger modal -->
					<button class="btn btn-primary shadow" id="btnMbrRegister" onclick="mbrRegisterFunction()">Confirm</button>
					</div>
				</div>
      </div>
      <div class="col-md-5">
  			<h3 class="bg-secondary white" style="center">Membership Fees, 1 April to 31 March</h3>
          <table class="table table-hover wksp_table">
            <tbody>
							<?php
							foreach ($agencyfees as $agencyfee) { ?>
                <tr>
                  <td><strong><?php echo $agencyfee["amount"]; ?></strong></td>
                  <td><?php echo $agencyfee["detail"]; ?></td>
                </tr>
              <?php
                  } ?>
            </tbody>
          </table>
        </div>
      </div>
  	</div>
  	</div>

		<!-- Membership submission Modal -->
		<div class="modal fade" id="mbrRegisterModal" tabindex="-1" role="dialog" aria-labelledby="mbrRegModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		    		<div class="modal-content">
		         	<!-- Modal Header -->
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal">
		                	<span aria-hidden="true">&times;</span>
		                  <span class="sr-only">Close</span>
		                </button>
		                <h4 class="modal-title" id="mbrRegModalLabel">Register your Organisation</h4>
		            </div>
		            <!-- Shortlist Modal Body -->
		            <div class="modal-body">
		            	<form class="form-horizontal" id="mbrRegisterForm">
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
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputPOBox">PO Box</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputPOBox" placeholder="PO Box"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputAddress1">Street Address</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputAddress1" placeholder="Street Address"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputAddress2">Address2</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputAddress2" placeholder="Address2"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputSuburb">Suburb</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputSuburb" placeholder="Suburb"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
												<label  class="col-sm-4 control-label" for="inputCity">City</label>
												  <div class="col-sm-8">
													<select name="city" class="form-control" id="cityList">
														<option selected="" value="">Select</option>
														<?php
															foreach($cities as $city) { ?>
																<option value="<?php echo $city["city"]; ?>"><?php echo $city["city"]; ?></option>
															<?php }
														?>
		     										</select>
											  		<span class="help-block"></span>
												  </div>
											</div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputPostCode">Post Code</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputPostCode" placeholder="Post Code"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
		                  <div class="form-group required">
		                    	<label  class="col-sm-4 control-label" for="inputEmail">Email</label>
		                    	<div class="col-sm-8">
		                        <input type="email" class="form-control" id="inputEmail" placeholder="Email" required="required"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputDayPhone">Day time phone</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputDayPhone" placeholder="Day Phone"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputMobile">Out of Hours Phone</label>
		                    	<div class="col-sm-8">
		                        <input type="text" class="form-control" id="inputMobile" placeholder="Mobile"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="subHeading">Joining Information</div>
											<div class="form-group">
													<div class="col-sm-4">
														<div class="checkbox left-checkbox">
															<label>Is this for an Affiliated Membership?</label>
														</div>
													</div>
													<div class="col-sm-8">
														<label>
															<input type="radio" id="affiliatedyes" name="affiliated" value="Yes" >Yes
														</label>
														<label>
															<input type="radio" id="affiliatedno" name="affiliated" value="No" checked="checked">No
														</label>
													</div>
											</div>
											<div class="form-group">
													<div class="col-sm-4">
														<div class="checkbox left-checkbox" id="labelmultibranch">
															<label>Is this an organisation with multiple branches?</label>
														</div>
													</div>
													<div class="col-sm-8" id="radiomultibranch">
														<label>
															<input type="radio" id="multibranchyes" name="multibranch" value="Yes" >Yes
														</label>
														<label>
															<input type="radio" id="multibranchno" name="multibranch" value="No" checked="checked" > No
														</label>
														<select name="orgno" class="form-control" id="orgcountlist">
															<option selected="" value="">Select</option>
															<?php
																$a = array ("7", "8");
																foreach($agencyfees as $agencyfee) {
																	if (in_array($agencyfee["id"], $a, true)) { ?>
																	<option value="<?php echo $agencyfee["amount"]; ?>"><?php echo $agencyfee["detail"]; ?></option>
																<?php }}
															?>
															</select>
													</div>
											</div>
											<div class="form-group">
													<div class="col-sm-4">
														<div class="checkbox left-checkbox" id="labelOrgTurnOver">
															<label>Select organisation annual gross income</label>
														</div>
													</div>
													<div class="col-sm-8">
														<select name="orgturnover" class="form-control" id="orgTurnOverlist">
															<option selected="" value="">Select</option>
															<?php
																$a = array ("1", "2", "3", "4", "5", "6", "12", "13");
																foreach($agencyfees as $agencyfee) {
																	if (in_array($agencyfee["id"], $a, true)) { ?>
																	<option value="<?php echo $agencyfee["amount"]; ?>"><?php echo $agencyfee["detail"]; ?></option>
																<?php }}
															?>
															</select>
													</div>
											</div>
											<div class="form-group">
													<label  class="col-sm-4 control-label" for="feeDetermination">Membership Fee</label>
													<div class="col-sm-8">
																<input type="text" class="form-control" id="feeAmount" />
													</div>
											</div>
											<div class="form-group required">
													<label  class="col-sm-4 control-label" for="inputChairPerson">Board President / Chairperson</label>
												  <div class="col-sm-8">
															<input type="text" class="form-control" id="inputChairPerson" placeholder="Name" required="required"/>
			  		   <span class="help-block"></span>
													</div>
											</div>
											<div class="form-group required">
													<label  class="col-sm-4 control-label" for="inputExecutivePerson">Executive Director/Manager</label>
												  <div class="col-sm-8">
															<input type="text" class="form-control" id="inputExecutivePerson" placeholder="Name" required="required"/>
			  		   <span class="help-block"></span>
													</div>
											</div>
											<div class="form-group required">
													<label  class="col-sm-4 control-label" for="inputCoordinator">Manager of Volunteers</label>
												  <div class="col-sm-8">
															<input type="text" class="form-control" id="inputCoordinator" placeholder="Name" required="required"/>
			  		   <span class="help-block"></span>
													</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Our Manager is paid</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputManagerPaid" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
													<div class="col-sm-4">
														<div class="checkbox left-checkbox">
															<label>Our Manager works...</label>
														</div>
													</div>
													<div class="col-sm-8">
						        			  <label>
						                    <input type="radio" name="workRadios" id="fullTime" checked="checked">Full-Time
														</label>
						                <label>
						                    <input type="radio" name="workRadios" id="partTime">Part-Time
						                </label>
						            	</div>
											</div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputPaidStaff">Number of Paid Staff</label>
		                    	<div class="col-sm-4">
		                        <input type="text" class="form-control" id="inputPaidStaff" placeholder="Number, e.g. 2"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputVolunteers">Number of Volunteers</label>
		                    	<div class="col-sm-4">
		                        <input type="number" class="form-control" id="inputVolunteers" placeholder="Number, e.g. 2"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="form-group">
		                    	<label  class="col-sm-4 control-label" for="inputOfficeDetails">Office Days and Hours</label>
		                    	<div class="col-sm-4">
		                        <input type="text" class="form-control" id="inputOfficeDetails" placeholder="Office Days and Hours"/>
			  		   <span class="help-block"></span>
		                    	</div>
		                  </div>
											<div class="subHeading">YOUR MISSION AND SERVICES</div>
											<div class="subHeading">Mission</div>
											<div class="form-group">
		                    	<div class="col-sm-12">
    										<textarea class="form-control" id="inputMission" rows="5" required="required"></textarea>
								  		   <span class="help-block"></span>
					</div>
											</div>
											<div class="subHeading">Services</div>
											<div class="form-group">
		                    	<div class="col-sm-12">
    										<textarea class="form-control" id="inputServices" rows="5" required="required"></textarea>
								  		   <span class="help-block"></span>
					</div>
											</div>
											<div class="subHeading">VOLUNTEER PROGRAMME</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>We provide disabled access</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputDisableAccess" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>We welcome volunteers with English as a second language</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputESOL" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>We have funds budgeted for a volunteer programme</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputFundsBudget" value ="">
          								</div>
        								</div>
											</div>
											<div class="subHeading">As part of of our volunteer programme we:</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Have written volunteer role descriptions</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputRoleDesc" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Offer orientation and training to volunteers</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputVolTrng" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Conduct interviews</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputVolInterviews" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Evaluate volunteers' performance</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputVolPerf" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Reimburse out-of-pocket expenses</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputVolReimb" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Provide a reference after a period of service</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputVolRefs" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Have an Health & Safety Policy</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputHSPolicy" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Provide Health & Safety training</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputHSTraining" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
          								<div class="checkbox left-checkbox">
            								<label>Health & Safety Risks have been identified</label>
          								</div>
        								</div>
        								<div class="col-sm-8">
          								<div class="checkbox left-checkbox">
            								<input type="checkbox" id="inputHSRisks" value ="">
          								</div>
        								</div>
											</div>
											<div class="form-group">
													<div class="col-sm-6">
		                      	<button type="button" class="btn btn-primary center-block" onClick="sendMbrRegister();" >Register Organisation</button>
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
		          <h4 class="modal-title" id="success-dialog-title">Registration Success</h4>
		        </div>
		        <div class="modal-body" id="success-dialog-message">
							<p> Your membership registration has been recorded</p>
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
			          <h4 class="modal-title alert alert-warning" id="failure-dialog-title">Registration Failure</h4>
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
