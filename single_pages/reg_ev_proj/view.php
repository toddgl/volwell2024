<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>

<script src="https://cdn.ravenjs.com/3.20.1/raven.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
	Raven.config('https://606f3fd66dd04223a83abba161de7248@sentry.io/247542').install();
</script>

<script type="text/javascript">
$(document).ready(function() {
		$('#volProjectModal').on('hidden.bs.modal', function () {
			$('#volProjectModal').removeData('bs.modal');
			$('#volProjectModal').find('.modal-content').empty;
	});
});

function volProjectFunction() {
	$('#volProjectModal').modal('show');
	gtag('event', 'begin_checkout', {'event_label': 'project', 'items': [{'category': 'project'}]});
};

function regEVProject() {
	var valid;
	valid = validateForm();
	if(valid) {
		var jsonData = [];
		var evProject ={};
		evProject.agency = $("#inputAgencyName").val();
		evProject.agencyid = $("#inputAgencyidName").val();
		evProject.title = $("#inputTitle").val();
		evProject.background = $("#inputBackground").val();
    evProject.descript = $("#inputDescript").val();
		evProject.skills = $("#inputJobSkills").val();
		evProject.supervisor = $("#inputSupervisorName").val();
    evProject.tel = $("#inputContactTel").val();
    evProject.email = $("#inputContactEmail").val();
    evProject.dayshours = $("#inputDayHours").val();
		evProject.personality = $("#inputVolPersonatity").val();
		evProject.jobaddress = $("#inputJobAddress").val();
		evProject.jobsuburb = $("#inputJobSuburb").val();
		evProject.jobcity = $("#cityList").val();
		evProject.volnums = $("#inputVolNumbers").val();
		evProject.comments = $("#inputComments").val();
		if ($("#isWeekend").is(":checked")) {
			evProject.wends = 'wends';
		} else {
			evProject.wends = 'wdays';
		}
		if ($("#isMember").is(":checked")) {
			evProject.mem = 'Yes';
		} else {
			evProject.mem = 'No';
		}
		if ($("#isForChallenge").is(":checked")) {
			evProject.ischallenge = 1;
		} else {
			evProject.ischallenge = 0;
		}
    evProject.currentyear = new Date().getFullYear();
		if ($("#ProjCoordRadio").is(":checked")) {
			evProject.hsname = $("#inputSupervisorName").val();
	    evProject.hstel = $("#inputContactTel").val();
	    evProject.hsemail = $("#inputContactEmail").val();
		} else {
			evProject.hsname = $("#OtherName").val();
	    evProject.hstel = $("#OtherTel").val();
	    evProject.hsemail = $("#OtherEmail").val();
		}
		evProject.hsinduct = $("#inputHSInduction").val();
		evProject.hstasks = $("#inputHSTasks").val();
		evProject.hsrisks = $("#inputHSRisks").val();
		if ($("#HSBriefChk").is(":checked")) {
			evProject.isbrief = 1;
		} else {
			evProject.isbrief = 0;
		}
		if ($("#HSSupervisionChk").is(":checked")) {
			evProject.issuper = 1;
		} else {
			evProject.issuper = 0;
		}
		if ($("#HSEquipChk").is(":checked")) {
			evProject.isequip = 1;
		} else {
			evProject.isequip = 0;
		}
		if ($("#HSAdviceChk").is(":checked")) {
			evProject.isadvice = 1;
		} else {
			evProject.isadvice = 0;
		}
		evProject.othernotes = $("#inputHSOther").val();
		evProject.hsequip = $("#inputHSProEquip").val();
		evProject.hsreport = $("#inputHSReporting").val();
		evProject.hsother = $("#inputHSAdditional").val();

		jsonData.push({evProject: evProject});
		//alert (JSON.stringify(jsonData));

		$.ajax({
			type: 'POST',
			url: "<?=$view->action('regEVProject')?>",
			datatype: 'json',
			data: {projData: JSON.stringify(jsonData)},
			cache: false,
		}).done(function(data, textStatus, jqXHR){
			//var result = $.parseJSON(data);
			//alert(textStatus);
			$('#volProjectModal').modal('hide');
			$('#dialogSuccessModal').modal('show');
			gtag('event', 'purchase', {'event_label': 'project', 'items': [{'category': 'project'}]});
		}).fail(function(jqXHR, textStatus, errorThrown){
			// alert(errorThrown);
			$('#volProjectModal').modal('hide');
			$('#dialogFailureModal').modal('show');
		});
		} else {
			$('#dialogMissingItemsModal').modal('show');
		};
};

function validateForm() {
	var valid = true;
	$("#volProjectModal input[required=required ], #volProjectModal text[required=required ], #volProjectModal textarea[required=required ], #volProjectModal select[required=required], #volProjectModal input[type=email], #volProjectModal input[type=number], #volProjectModal input[type=date]").each(function() {
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
		items: "input[required=required ], textarea[required=required ], select[required=required ]",
		content: function() {return $(this).attr( "title" );},
	});
});*/

function toggleOtherTextboxVisible()
  {
    if (document.getElementById('OtherRadio').checked) {
			document.getElementById('OtherLabel').style.display = 'block';
      document.getElementById('OtherName').style.display = 'block';
			document.getElementById('OtherTel').style.display = 'block';
			document.getElementById('OtherEmail').style.display = 'block';
    }
    else if (document.getElementById('ProjCoordRadio').checked) {
			document.getElementById('OtherLabel').style.display = 'none';
			document.getElementById('OtherName').style.display = 'none';
			document.getElementById('OtherTel').style.display = 'none';
			document.getElementById('OtherEmail').style.display = 'none';
		}
  }

function toggleOtherHSVisible()
	{
		if (document.getElementById('HSOtherChk').checked) {
			document.getElementById('inputHSOther').style.display = 'block';
		}
		else {
			document.getElementById('inputHSOther').style.display = 'none';
		}
	}


</script>

<div class="main-container text-center">
	<div class="row">
		<div class="col-md-7">
     	<h3 style="text-align: center;">Project Registration</h3>
			<?php
				$areaContent0 = new Area('Content0');
				$areaContent0->display($c);
			?>

       	<h3 class="bg-secondary white" style="center">Policies for Project Registration</h3>
        <?php
  				$areaContent1 = new Area('Content1');
  				$areaContent1->display($c);
  			?>
				<p><em>Please confirm your understanding and acceptance of the above:</em></p>


				<div class="form-group">
						<div class="text-center">
						<!-- Button trigger modal -->
						<button class="btn btn-primary shadow" id="btnProjRegister" onclick="volProjectFunction()">Confirm</button>
					</div>
				</div>

    	</div>
    	<div class="col-md-5">
				<?php
					$areaRightContent = new Area('RightContent');
					$areaRightContent->display($c);
				?>
			</div>
		</div>
	</div>

	<!-- Membership submission Modal -->
	<div class="modal fade" id="volProjectModal" tabindex="-1" role="dialog" aria-labelledby="volProjectModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		  <div class="modal-content">
		    <!-- Modal Header -->
		    <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">
		            <span aria-hidden="true">&times;</span>
		            <span class="sr-only">Close</span>
		        </button>
		        <h4 class="modal-title" id="volProjectModalLabel">Request for Volunteers</h4>
		    </div>
		  	<!-- Shortlist Modal Body -->
		    <div class="modal-body">
		        <form class="form-horizontal" id="volProjectForm">
								<div class="subHeading">Project Details</div>
								<div class="form-group required " >
		              <label  class="col-sm-4 control-label" for="inputTitle">Project title</label>
		              <div class="col-sm-8">
		                <input type="text" class="form-control" id="inputTitle" placeholder="Project Title" required="required" />
				  <span class="help-block"></span>
		              </div>
		            </div>
                <div class="form-group required " >
		              <label  class="col-sm-4 control-label" for="inputBackground">Project Background N.B. This can be your organisation's services</label>
		              <div class="col-sm-8">
								<textarea class="form-control" id="inputBackground" rows="5" placeholder="Project Background" required="required" ></textarea>
								  <span class="help-block"></span>
									</div>
		            </div>
								<div class="form-group">
									<div class="col-sm-4">
										<div class="checkbox left-checkbox">
											<label>Is this project for the coporate Challenge:</label>
										</div>
									</div>
									<div class="col-sm-8">
										<div class="checkbox left-checkbox">
											<input type="checkbox" id="isForChallenge" value ="">
										</div>
									</div>
								</div>
								<div class="form-group required ">
		              <label  class="col-sm-4 control-label" for="inputDescript">Project Description</label>
		              <div class="col-sm-8">
				<textarea class="form-control" id="inputDescript" rows="5" placeholder="Project Description" required="required" ></textarea>
				  <span class="help-block"></span>
									</div>
		            </div>
								<div class="form-group required " >
		              <label  class="col-sm-4 control-label" for="inputJobSkills">Special Skills Requirements e.g. type of people,health status, cultural considerations etc.</label>
		                <div class="col-sm-8">
							<textarea class="form-control" id="inputJobSkills" rows="5" placeholder="Skills Requirements" required="required" ></textarea>
							  <span class="help-block"></span>
		                </div>
		            </div>
                <div class="form-group">
		              <label  class="col-sm-4 control-label" for="inputVolPersonatity">Project Instructions e.g clothing, tools etc.</label>
		              <div class="col-sm-8">
										<textarea class="form-control" id="inputVolPersonatity" rows="5" placeholder="Special personsility requirements" required="required" ></textarea>
				  <span class="help-block"></span>
									</div>
		            </div>
                <div class="subHeading">Contact Details</div>
                <div class="form-group">
		              <label  class="col-sm-4 control-label" for="inputAgencyidName">If you have your Agency ID from Volunteer Wellington enter it here</label>
		                <div class="col-sm-8">
		                  <input type="number" class="form-control" id="inputAgencyidName" placeholder="Agencyid"/>
				  <span class="help-block"></span>
		                </div>
		            </div>
                <div class="form-group required " >
		              <label  class="col-sm-4 control-label" for="inputAgencyName">Agency Name</label>
		              <div class="col-sm-8">
		                <input type="text" class="form-control" id="inputAgencyName" placeholder="Agency Name" required="required" />
				  <span class="help-block"></span>
		              </div>
		            </div>
                <div class="form-group">
                	<label class="col-sm-4 control-label" for="isMember">We are a member of Volunteer Wellington: </label>
            				<div class="col-sm-6">
                    	<label>
                        <input type="radio" name="memberRadios" id="isMember" checked="checked">  Yes
            					</label>
                      <label>
                        <input type="radio" name="memberRadios" id="isNotMember">  No
                      </label>
                    </div>
                	</div>
                  <div class="form-group required " >
		                <label  class="col-sm-4 control-label" for="inputSupervisorName">Person to whom volunteer is responsible</label>
		                  <div class="col-sm-8">
		                    <input type="text" class="form-control" id="inputSupervisorName" placeholder="Supervisor Names" required="required" />
					  						<span class="help-block"></span>
		                  </div>
		              </div>
                  <div class="form-group required " >
										<label  class="col-sm-4 control-label" for="inputContactTel">Contact Telephone</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="inputContactTel" placeholder="Contact Telephone" required="required" />
				  						<span class="help-block"></span>
										</div>
									</div>
		              <div class="form-group required " >
		                <label  class="col-sm-4 control-label" for="inputContactEmail">Email</label>
		                <div class="col-sm-8">
		                  <input type="email" class="form-control" id="inputContactEmail" placeholder="Email" required="required" />
				  						<span class="help-block"></span>
		                </div>
		              </div>
                  <div class="subHeading">Other Details</div>
                    <div class="form-group">
		                <label  class="col-sm-4 control-label" for="inputDayHours">Specific days and hours</label>
		                  <div class="col-sm-8">
		                    <input type="text" class="form-control" id="inputDayHours" placeholder="Days and Hours"/>
				  						<span class="help-block"></span>
		                  </div>
		              </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label" for="isWeekend">This project is planned for: </label>
            				<div class="col-sm-6">
                    	<label>
                        <input type="radio" name="weekRadios" id="isWeekend" checked="checked">  Weekends
            					</label>
                      <label>
                        <input type="radio" name="weekRadios" id="isWeekdays">  Weekdays
                      </label>
                  	</div>
                	</div>
                  <div class="form-group">
										<label  class="col-sm-4 control-label" for="inputJobAddress">Job Street address</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="inputJobAddress" placeholder="Job Street address"/>
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
										<label  class="col-sm-4 control-label" for="cityList">City</label>
										<div class="col-sm-8">
											<select class="form-control" id="cityList" >
												<option selected="" value="" >Select</option>
												<?php
													foreach($cities as $city) { ?>
														<option value="<?php echo $city["city"]; ?>"><?php echo $city["city"]; ?></option>
													<?php }
												?>
		     								</select>
										</div>
									</div>
									<div class="form-group required " >
										<label  class="col-sm-4 control-label" for="inputVolNumbers" >Number of Volunteers needed</label>
										<div class="col-sm-8">
											<input type="number" class="form-control" id="inputVolNumbers" placeholder="Number, e.g. 2" required="required" />
				  						<span class="help-block"></span>
										</div>
									</div>
									<div class="form-group">
										<label  class="col-sm-4 control-label" for="inputComments" >Additional comments</label>
										<div class="col-sm-8">
											<textarea class="form-control" id="inputComments" rows="6" placeholder="Comments" ></textarea>
				  						<span class="help-block"></span>
										</div>
									</div>
									<div class="subHeading">Health & Safety</div>
                    <div class="form-group">
											<div>
												<label  class="col-sm-4 control-label" for="inputDelivery" >Who from your organisation will be delivering the Health and Safety Induction on the day?</label>
												<div class="col-sm-8">
		                    	<label>
		                        <input type="radio" name="HSRadios" id="ProjCoordRadio" value="Project Coordinator" onchange="javascript:toggleOtherTextboxVisible()" />  Project Coordinator
													</label>
		                      <label>
		                        <input type="radio" name="HSRadios" id="OtherRadio" value="Others" required="required" onchange="javascript:toggleOtherTextboxVisible()" />  Other
		                      </label>
		                  	</div>
											</div>
											<div>
												<label  class="col-sm-4 control-label" id="OtherLabel" for="inputDelivery" style="display:none" >Please specify name and contact details. </label>
												<div class="col-sm-8">
		                    	<input class="form-control" id="OtherName" type="text" placeholder="Name required" style="display:none" />
													<input class="form-control" id="OtherTel" type="text"  placeholder="Phone contact required" style="display:none" />
													<input class="form-control" id="OtherEmail" type="text" placeholder="Email required" style="display:none" />
													<span class="help-block"></span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label  class="col-sm-4 control-label" for="inputHSInduction" >What information is included in the induction process?</label>
											<div class="col-sm-8">
												<textarea class="form-control" id="inputHSInduction" rows="6" required="required" placeholder="Your Answer" ></textarea>
					  						<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group">
											<label  class="col-sm-4 control-label" for="inputHSTasks" >What tasks or activities are planned for the day?</label>
											<div class="col-sm-8">
												<textarea class="form-control" id="inputHSTasks" rows="6" required="required" placeholder="Your Answer" ></textarea>
					  						<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group">
											<label  class="col-sm-4 control-label" for="inputHSRisks" >What hazards or risks have you identified for this project? (eg ladders, tools, animals, water)</label>
											<div class="col-sm-8">
												<textarea class="form-control" id="inputHSRisks" rows="6" required="required" placeholder="Your Answer" ></textarea>
					  						<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group">
											<label  class="col-sm-4 control-label" for="inputHSChk" >How will you manage/mitigate any hazards/risks associated with the tasks/activities?</label>
											<div class="col-sm-8">
												<label>
													<input type="checkbox" id="HSBriefChk" />  Volunteer briefing/induction (incl. evacuation procedure, emergency exits)
												</label>
												<label>
													<input type="checkbox" id="HSSupervisionChk" />  On-site supervision
												</label>
												<label>
													<input type="checkbox" id="HSEquipChk" />  Personal Protective Equipment
												</label>
												<label>
													<input type="checkbox" id="HSAdviceChk" />  Advice about appropriate clothing/footwear provided prior to the project
												</label>
												<label>
													<input type="checkbox" id="HSOtherChk" value="Others" onchange="javascript:toggleOtherHSVisible()" />  Other
												</label>
												<textarea class="form-control" id="inputHSOther" rows="6" style="display:none" placeholder="Your Answer" ></textarea>
					  						<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group">
											<label  class="col-sm-4 control-label" for="inputHSProEquip" >What personal protective equipment will you be providing?</label>
											<div class="col-sm-8">
												<textarea class="form-control" id="inputHSProEquip" rows="6" required="required" placeholder="Your Answer" ></textarea>
					  						<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group">
											<label  class="col-sm-4 control-label" for="inputHSReporting" >How will you report any accidents (if any) that occur on the volunteering project?</label>
											<div class="col-sm-8">
												<textarea class="form-control" id="inputHSReporting" rows="6" required="required" placeholder="Your Answer" ></textarea>
												<span class="help-block"></span>
											</div>
										</div>
										<div class="form-group">
											<label  class="col-sm-4 control-label" for="inputHSAdditional" >Any other additional health and safety information?</label>
											<div class="col-sm-8">
												<textarea class="form-control" id="inputHSAdditional" rows="6" required="required" placeholder="Your Answer" ></textarea>
												<span class="help-block"></span>
											</div>
										</div>
									<div class="form-group">
										<div class="col-sm-6">
		                  <button type="button" class="btn btn-primary center-block" onClick="regEVProject();" >Register Project</button>
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
		          <h4 class="modal-title" id="success-dialog-title">Volunteer Project Submission Success</h4>
		        </div>
		        <div class="modal-body" id="success-dialog-message">
							<p> Your Volunteeer Project request has been recorded</p>
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
			          <h4 class="modal-title alert alert-warning" id="failure-dialog-title">Volunteer Project Submission Failure</h4>
			        </div>
			        <div class="modal-body" id="failure-dialog-message">
								<p> Oh this is embarrasing, something has gone wrong please try to resubmit your Project registration</p>
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
