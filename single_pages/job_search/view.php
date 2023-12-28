<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<script src="https://cdn.ravenjs.com/3.20.1/raven.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.dotdotdot/3.1.0/jquery.dotdotdot.js"></script>

<script type="text/javascript">
	Raven.config('https://606f3fd66dd04223a83abba161de7248@sentry.io/247542').install();
</script>

<script>
  window.fbAsyncInit = function() {
    FB.init({
	  <?php if (Config::get('concrete.env.name') == 'prod') { ?>
      appId            : '407446293006950',
	  <?php } else { ?>
      appId            : '157296625032750',
	  <?php } ?>
      autoLogAppEvents : true,
      xfbml            : false,
      version          : 'v2.11'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<script type="text/javascript">
	var prefix = "roleShortlist-";  // Set localStorage key prefix
	var maxItems = 10;  //Set LocalStorage shortlist limit

	$(document).ready(function() {
	    $(".to-truncate").dotdotdot({
			height: 120
	    });

		$('#myModal').on('hidden.bs.modal', function () {
			$('#myModal').removeData('bs.modal');
			$('#myModal').find('.modal-content').empty;
        });

        $('#inputNoEmail').change(function () {
            //var name = $(this).val();
            var check = $(this).prop('checked');
			if (check == true) {
				$("#inputEmail").attr("disabled", "disabled");
				$("#inputEmail").val("");
				$("#inputEmail").removeAttr("required");
				$("#inputEmail").parent().parent().removeClass("required");
			} else {
				$("#inputEmail").removeAttr("disabled");
				$("#inputEmail").attr("required", "required");
				$("#inputEmail").parent().parent().addClass("required");
			}
			//console.log("Change: " + name + " to " + check);
		});

		$("#myModalFB").click(function() {
			return fbShare('<?php echo $this->url("/be-volunteer/job_search", "role"); ?>', $('#myModal .modal-header button').data('id'), $('#myModal .modal-header button').data('title').replace(/[\r\n]+/g," "), $('#myModal #descrip').html().replace(/[\r\n]+/g," "));
		});

		$("#myModalAdd").click(function() {
			addmyJobFunction($('#myModal .modal-header button').data('id'), $('#myModal .modal-header button').data('title'));
		});

		$('#myModal').on('shown.bs.modal', function(e) {
			var id = $(e.relatedTarget).data('id');
			var title = $(e.relatedTarget).data('title');
			gtag('event', 'view_item', {'event_label': title, 'items': [{'id': id, 'category': 'role'}]});
		});

		$('#myModal').on('show.bs.modal', function(e) {
			var id = $(e.relatedTarget).data('id');
			//alert(id);
			$(this).find('.modal-title').text('Role detail for Job ID: ' + id);
			$.ajaxSetup ({
				// Disable caching of AJAX responses
				cache: false
			});
			$.ajax({
				type: 'POST',
				url: "<?=$view->action('getDetail')?>",
				datatype: 'json',
				data: ({ key: id}),
				cache: false,
			}).done(function(data, textStatus, jqXHR){
				var result = $.parseJSON(data);
				// alert(data);
				$("#myModal .modal-header button").data("id", id);
				$("#myModal .modal-header button").data("title", result['title']);
				if (result['title'] === undefined) {
					// pass
				}
				else {
					$('#detailTitle').html(result['title'] + ' (ID: ' + id + ')');
				}
				if (result['descrip'] === undefined) {
					// pass
				}
				else {
					$('#tdescrip').html('Role Description:  ');
					$('#descrip').html(result['descrip']);
				}
				if (result['skills'] === undefined) {
					// pass
				}
				else {
					$('#tskills').html('Special skills required:  ');
					$('#skills').html(result['skills']);
				}
				if ((result['policeck'] == 0 ) || (result['policeck'] === undefined)) {
						// pass
						$('#tpolice').html('');
						$('#police').html('');
				}
				else {
						$('#tpolice').html('Police Check:  ');
						$('#police').html('The Organisation has stipulated that this role will require a Police Check');
				}
				if ((result['c19vac'] == 0 ) || (result['c19vac'] === undefined)) {
						// pass
						$('#tc19vac').html('');
						$('#c19vac').html('');
				}
				else {
						$('#tc19vac').html('COVID-19 VACCINATION:  ');
						$('#c19vac').html('The Organisation has stipulated that this role will require the volunteer to be fully Covid-19 vaccinated');
				}
				if (result['personality'] === undefined) {
					// pass
				}
				else {
					$('#tpersonality').html('Expected personality:  ');
					$('#personality').html(result['personality']);
				}
				if (result['dayshours'] === undefined) {
					// pass
				}
				else {
					$('#tdayshours').html('Days, Hours for role:  ');
					$('#dayshours').html(result['dayshours']);
				}
				if (result['training'] === undefined) {
					// pass
				}
				else {
					$('#ttraining').html('Training provided:  ');
					$('#training').html(result['training']);
				}
				if ((result['eveonly'] == 0 ) || (result['eveonly'] === undefined)) {
						// clear modal data
						$('#teveonly').html('');
						$('#eveonly').html('');
				}
				else {
						$('#teveonly').html('Specific Arrangements:');
						$('#eveonly').html('This role is Evenings Only');
				}
				if ((result['reimbursement'] == 0 ) || (result['reimbursement'] === undefined)) {
						$('#treimbursement').html('Reimbursements:');
						$('#reimbursement').html('No');
				}
				else {
						$('#treimbursement').html('Reimbursements   :');
						$('#reimbursement').html('Yes');
				}

			}).fail(function(jqXHR, textStatus, errorThrown){
				if (textStatus === 'parsererror') {
				alert('Requested JSON parse failed.');
				} else if (textStatus === 'timeout') {
				alert('Time out error.');
				} else if (textStatus === 'abort') {
				alert('Ajax request aborted.');
				} else if (jqXHR.status === 0) {
				alert('Not connected.\n Verify Network.');
				} else if (jqXHR.status == 404) {
				alert('Requested controller page not found. [404]');
				} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
				} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
			});
		});

		<?php
			// If displayRoleDetail is set, display details of the role whose id is displayRoleDetail
			if (!empty($displayRoleDetail)) {
				echo "$('button[data-id=\"" . $displayRoleDetail . "\"][data-target=\"#myModal\"]').trigger( 'click' );\n";
			}
		?>
	});

	$(window).on ('load', function() {
		RewriteFromStorage();
	});

	function navigateToPage(page) {
		if (page === -1)
			return false;
		$("#selPage").val(page);
		document.getElementById("searchForm").submit();
		return false;
	};

	function addmyJobFunction(id, title){
		// Test to ensure that online registration is allowed
		$.ajax({
			type: 'POST',
			url: "<?=$view->action('getIsNotOnline')?>",
			datatype: 'json',
			data: ({ key: id}),
			cache: false,
		}).done(function(data, textStatus, jqXHR){
			var result = $.parseJSON(data);
			$("#myModal").modal('hide');
			if (result["success"] == 'true') {
				$('#noOnlineRegistrationModal').modal('show');
				gtag('event', 'role_must_be_registered_by_phone', {'event_label': title, 'value': id});
			}
			else {
				// add job to storage
				//console.log("start log");
				//alert(localStorage.length-1);
				if (localStorage.length <=maxItems) {
					// Does prefix+id index already exist in localStorage?
					if (localStorage.getItem(prefix+id) === null) {
						// No. Store it in localStorage
						//localStorage.setItem(prefix + id, title);      //******* setItem()
						localStorage[prefix+id] = title;  // This also works instead of using setItem
						//console.log("inside addmyJob Click function job id: " + id + "added.");
						RewriteFromStorage();
						$('#addedToShortlistModal').modal('show');
						gtag('event', 'add_to_cart', {'event_label': title, 'items': [{'id': id, 'category': 'role'}]});
					} else {
						$('#alreadyInShortlistModal').modal('show');
					}
				}
				else {
					$('#maxShortlist').modal('show');
				}
			}
		}).fail(function(jqXHR, textStatus, errorThrown) {
			alert("error");
		});
	};

	function RewriteFromStorage() {
    $("#cont_shortlist").empty();
    for(var i = 0; i < localStorage.length; i++)    //******* length
    {
        var key = localStorage.key(i);              //******* key()
        if(key.indexOf(prefix) == 0) {
            var value = localStorage.getItem(key);  //******* getItem()
            //var value = localStorage[key]; also works
            var shortkey = key.replace(prefix, "");
            $("#cont_shortlist").append(
                $("<li class='shortlist'>").html(shortkey + " - " + value)
                   .append("&nbsp;").append($("<input type='reset' value=' X '></li>")
                           .attr('key', key)
                           .click(function() {      //****** removeItem()
								var id = $(this).attr('key');
                                localStorage.removeItem(id);
								console.log("Job id: " + id + " removed from local storage")
                                RewriteFromStorage();
								gtag('event', 'remove_from_cart', {'event_label': value, 'items': [{'id': id, 'category': 'role'}]});
                            })
                          )
            );
        }
    }
	}

	RewriteFromStorage();

	function sendJobRegister() {
		var valid;
		valid = validateForm();
		if(valid) {
			var jsonData = [];
			var volContact = {}
			var volContact ={};
			volContact.fname = $("#inputFirstName").val();
			volContact.lname = $("#inputLastName").val();
			volContact.add1	= $("#inputAddress1").val();
			volContact.add2	= $("#inputAddress2").val();
			volContact.suburb = $("#inputSuburb").val();
			volContact.city = $("#cityList").val();
			volContact.pcode = $("#inputPostcode").val();
			volContact.phone = $("#inputPhone").val();
			volContact.email = $("#inputEmail").val();
			volContact.gender = $("#genderList").val();
			volContact.ageband = $("#ageBand").val();
			volContact.ethnicity = $("#ethnicityList").val();
			volContact.migrant = $("#migrantStatusList").val();
			volContact.refugee = $("#refugeeStatusList").val();
			volContact.heard = $("#heardList").val();
			volContact.labour = $("#workStatusList").val();
			volContact.reason = $("#volReasonList").val();
			volContact.office = $("#volWelOfficeList").val();
			if ($("#volOneOffList").is(":checked")) {
				volContact.oneoff = 1;
			} else {
				volContact.oneoff = 0;
			}
			if ($("#volPosImpactList").is(":checked")) {
				volContact.posimpact = 1;
			} else {
				volContact.posimpact = 0;
			}
			if ($("#volEmergList").is(":checked")) {
				volContact.emvol = 1;
			} else {
				volContact.emvol = 0;
			}
			// Covid 19 variable left set to null 11/06/2020 supporting frontend code removed
			//volContact.c19 = 0;

			jsonData.push({volContact: volContact});
			var gaRoles = [];
			for(var i = 0; i < localStorage.length; i++)    //******* length
			{
				var item = {};
				var key = localStorage.key(i);              //******* key()
				if(key.indexOf(prefix) == 0) {
					var value = localStorage.getItem(key);  //******* getItem()
					var shortkey = key.replace(prefix, "");
					item.id = shortkey;
					jsonData.push({item: item});
					gaRoles.push({'id': shortkey, 'category': 'role'});
				}
			}
			//alert (JSON.stringify(jsonData));
			$.ajax({
				type: 'POST',
				url: "<?=$view->action('jobRegister')?>",
				datatype: 'json',
				//data: {jobData: jsonData},
				data: {jobData: JSON.stringify(jsonData)},
				cache: false,
			}).done(function(data, textStatus, jqXHR){
 				//alert (data);
				clearShortlist();
				$('#JobRegisterModal').modal('hide');
				$('#dialogSuccessModal').modal('show');
				gtag('event', 'purchase', {'event_label': 'roles', 'items': gaRoles});
			}).fail(function(jqXHR, textStatus, errorThrown){
				// alert(errorThrown);
				$('#JobRegisterModal').modal('hide');
				$('#dialogFailureModal').modal('show');
			});
		} else {
			$('#dialogMissingItemsModal').modal('show');
		};
	};

	function clearShortlist() {
		var i = localStorage.length;
		while(i--) {
  		var key = localStorage.key(i);
			if(key.indexOf(prefix) == 0) {
				// only remove localstorage items that hve the prefix in the key
    		localStorage.removeItem(key);
  		}
		}
		RewriteFromStorage();
	}

	function validateForm() {
		var valid = true;
		$('#jobRegisterForm').find("> div.form-group").removeClass('has-error');
		$('#jobRegisterForm').find("span.help-block").text("");
		$("#JobRegisterModal input[required=required], #JobRegisterModal text[required=required], #JobRegisterModal textarea[required=required], #JobRegisterModal select[required=required]").each(function() {
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
		// Check that at least one contact number is provided
		var inputPhone = $("#inputPhone");
		//var inputEveningTel = $("#inputEveningTel");
		//var inputMobile = $("#inputMobile");
		if (!inputPhone.val()) {
			inputPhone.parent().parent().addClass('has-error');
			inputPhone.parent().find("> span.help-block").text("A contact phone number is required");
			valid = false;
		}

		return valid;
	}

	/*$(function() {
		$( document ).tooltip({
			position: {my: "left top", at: "right top"},
			items: "input[required=required], text[required=required], textarea[required=required], select[required=required]",
			content: function() {return $(this).attr( "title" );}
		});
	});*/

	function jobRegisterFunction() {
		// First check if there are any jobs in the shortlist. If not, show message to the user. Otherwise show the job registration modal
		var hasRoles = false;
		var gaRoles = [];
		for(var i = 0; i < localStorage.length; i++)
	    {
			var key = localStorage.key(i);
	        if(key.indexOf(prefix) == 0) {
				hasRoles = true;
				var id = key.replace(prefix, "");
				gaRoles.push({'id': id, 'category': 'role'});
			}
		}
		if (hasRoles === true)
		{
			$('#JobRegisterModal').modal('show');
			gtag('event', 'begin_checkout', {'event_label': 'roles', 'items': gaRoles});
		} else {
			$('#NoShortlistedJobsModal').modal('show');
		}
	};

	function copyTextToClipboard(text) {
	   var textArea = document.createElement( "textarea" );
	   textArea.value = text;
	   document.body.appendChild( textArea );

	   textArea.select();

	   try {
		  var successful = document.execCommand( 'copy' );
		  if (successful === true) {
			alert("Copied link to role");
		  }
	   } catch (err) {
		  console.log('Oops, unable to copy');
	   }

	   document.body.removeChild( textArea );
	}

	function fbShare(urlPrefix, roleId, title, description) {
		FB.ui({
			method: 'share_open_graph',
			action_type: 'og.shares',
			action_properties: JSON.stringify({
				object : {
				   'og:url': urlPrefix + '/' + roleId,
				   'og:title': "Volunteer Role - " + title,
				   'og:description': description,
				   'og:image': '<?php echo BASE_URL . $this->getThemePath(); ?>/images/VolunteerWellington_white_with_red_bg.png'
				}
			})
			},
			// callback
			function(response) {
			if (response && !response.error_message) {
				// then get post content
				//alert('successfully posted. Status id : '+response.post_id);
			} else {
				//alert('Something went error.');
			}
		});
		return false;
	}
</script>

	<div class="main-container text-center">
			<div class="row">
				<div class="col-md-3">
					<?php
	  				$areaLeftContent = new Area('LeftContent');
	  				$areaLeftContent->display($c);
	  			?>
				</div>
				<div class="col-md-6">
					<h3 style="text-align: center;"> Search For a Volunteer Role</h3>
					<?php
					$cats = $controller->getKeywords();
					$locs = $controller->getLocation();
					?>
     				<h4 style="text-align: center;"> Select your search options</h4>
    				<form id="searchForm" class="form-horizontal" method="get" action="<?php echo $this->action('searchJobData'); ?>">
    					<div class="form-group">
							<label for="selCategory" class="control-label col-xs-4">Role Category</label>
							<select name="sCategory" class="frmfield" id="selCategory">
								<option value="">All...</option>
								<?php foreach($cats as $category){?>
 									<option value="<?php echo $category["keyword"]; ?>" <?php if ($resultCategory == $category["keyword"]) echo "selected=\"selected\""; ?>> <?php echo $category["keyword"]; ?></option>
   								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label for="selLocation" class="control-label col-xs-4">Location</label>
							<select name="sLocation" class="frmfield" id="selLocation">
								<option value="">All...</option>
								<?php foreach($locs as $location){?>
									<option value="<?php echo $location["office"]; ?>" <?php if ($resultLocation == $location["office"]) echo "selected=\"selected\""; ?>> <?php echo $location["tex"]; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label for="selSearch" class="control-label col-xs-4">Search words</label>
							<input type="text" name="sWord" value="<?php echo $resultKeyword; ?>" class="frmfield" id="selSearch">
						</div>
						<div class="form-group">
							<div class="col-xs-offset-4 col-xs-10">
								<input type="hidden" id="selPage" name="sPage" value="0">
								<button type="submit" class="btn btn-primary shadow">Search</button>
							</div>
						</div>
					</form>
				</div>

				<div class="col-md-3">
					<h4>My Shortlist</h4>
    				<ol id="cont_shortlist">
    				</ol>
    				<!-- Button trigger modal -->
					<button class="btn btn-primary shadow" id="btnJobRegister" onclick="jobRegisterFunction()">
    						Register interest in these roles
					</button>
				</div>
		</div>
		<div class="row">
				<div class="col-md-3">
					<?php
	  				$areaLeftContentLower = new Area('LeftContentLower');
	  				$areaLeftContentLower->display($c);
	  			?>
				</div>
				<div class="col-md-6">
				<div class="row extraspace">
							<?php
							if (empty($resultPosted)) {
								//echo "Select your search options";
							}	else {
								for ($i = 0; $i < $resultPageSize; $i++) {
									if ($i + 1 > count($resultPosted))
										break;
									$job = $resultPosted[$i]; ?>

									<div class="dk-blue-wrapper white">
									<h3>
										<?php echo $job["title"]; ?>
									</h3>
									</div>
									<div class="blue-wrapper">
									<table class="table">
									<thead>
									<tr>
									<th style="width: 70%"><?php echo $job["jobsub"]; ?></th>
									<th style="width: 30%">Role ID: <?php echo $job["ID"]; ?></th>
									</tr>
									</thead>
									</table>
									<p class="to-truncate"><?php echo $job["descrip"]; ?></p>
									</div>
									<div class="lt-dk-blue-wrapper">
										<table class="table">
											<thead>
												<tr>
													<th style="width: 46%"><button type="button" class="btn btn-primary shadow" data-toggle="modal" data-target="#myModal" data-id="<?php echo $job_id= $job["ID"]; ?>" >View Details</button></th>
													<th style="width: 46%"><button class="btn btn-primary shadow" onclick="addmyJobFunction('<?php echo $job["ID"]; ?>', '<?php echo $job["title"]; ?>')">Add to Shortlist</button></th>
													<th style="width: 8%">
														<?php
															$u = new User();
															if ($u->isLoggedIn()) {
														?>
														<div style="border: 1px solid transparent">
															<a href="#" onclick="copyTextToClipboard('<?php echo $this->url("/be-volunteer/job_search", "role", $job["ID"]); ?>'); return false;"><i class="fa fa-link" aria-hidden="true"></i></a>
														</div>
														<?php
															}
														?>
														<div style="border: 1px solid transparent">
															<a href="#" onclick="return fbShare('<?php echo $this->url("/be-volunteer/job_search", "role"); ?>', '<?php echo $job["ID"]; ?>', '<?php echo trim(preg_replace('/\s\s+/', ' ', $job["title"])); ?>', '<?php echo trim(preg_replace('/\s\s+/', ' ', $job["descrip"])); ?>')"><i class="fa fa-facebook-square fa-lg" aria-hidden="true"></i></a></div>
													</th>
												</tr>
											</thead>
										</table>
									</div>
								<?php }
								$prevDisabled = ($resultPage == 0);
								$nextDisabled = (count($resultPosted) <= $resultPageSize);
								if ($resultPage != 0 || count($resultPosted) > $resultPageSize) { ?>
								<ul class="pager">
									<li class="previous <?php if ($prevDisabled) echo "disabled"; ?>"><a href="#" onclick="return navigateToPage(<?php echo $prevDisabled ? -1 : $resultPage - 1; ?>);">&larr; Previous</a></li>
									<li class="next <?php if ($nextDisabled) echo "disabled"; ?>"><a href="#" onclick="return navigateToPage(<?php echo $nextDisabled ? -1 : $resultPage + 1; ?>);">Next &rarr;</a></li>
								</ul>
								<?php }
							} ?>
					</div>
				</div>
				<div class="col-md-3">
					<?php
	  				$areaRightContent = new Area('RightContent');
	  				$areaRightContent->display($c);
	  			?>
				</div>
	</div>

<!-- Dispay Detail Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog custom-class">
    <!-- Modal content-->
    <div class="modal-content">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" data-id="" data-title=""><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title" id="detailTitle"></h4>
      	</div>
      	<div class="modal-body">
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="tdescrip"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="descrip"></div>
				</div>
			</div>
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="tskills"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="skills"></div>
				</div>
			</div>
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="tpolice"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="police"></div>
				</div>
			</div>
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="tc19vac"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="c19vac"></div>
				</div>
			</div>
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="tpersonality"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="personality"></div>
				</div>
			</div>
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="tdayshours"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="dayshours"></div>
				</div>
			</div>
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="ttraining"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="training"></div>
				</div>
			</div>
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="treimbursement"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="reimbursement"></div>
				</div>
			</div>
			<div class="row">
				<div class="modal-lead col-md-3">
					<div id="teveonly"></div>
				</div>
				<div class="modal-detail col-md-9">
					<div id="eveonly"></div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<div class="row">
				<div class="col-xs-5">
					<button type="button" class="btn btn-primary center-block" data-dismiss="modal">Close</button>
				</div>
				<div class="col-xs-5">
					<button id="myModalAdd" class="btn btn-primary center-block">Add to Shortlist</button>
				</div>
				<div class="col-xs-2">
					<a href="#" id="myModalFB" class="pull-right"><i class="fa fa-facebook-square fa-lg" aria-hidden="true"></i></a>
				</div>
			</div>
		</div>
	</div>
  </div>
 <.div>
</div>

<!-- Shortlist submission Modal -->
<div class="modal fade" id="JobRegisterModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    	<div class="modal-content">
         	<!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                	<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Register your details</h4>
            </div>
            <!-- Shortlist Modal Body -->
            <div class="modal-body">
            	<form class="form-horizontal" id="jobRegisterForm">
                  <div class="form-group required">
                    	<label  class="col-sm-4 control-label" for="inputFirstName">First Name</label>
                    	<div class="col-sm-8">
							<input type="text" class="form-control" id="inputFirstName" placeholder="First Name" required="required"/>
							<span class="help-block"></span>
                    	</div>
                  </div>
                  <div class="form-group required">
                    	<label  class="col-sm-4 control-label" for="inputLastName">Last Name</label>
                    	<div class="col-sm-8">
							<input type="text" class="form-control" id="inputLastName" placeholder="Last Name" required="required"/>
							<span class="help-block"></span>
                    	</div>
                  </div>
                  <div class="form-group required">
                    	<label  class="col-sm-4 control-label" for="inputEmail">Email</label>
                    	<div class="col-sm-8">
							<input type="email" class="form-control" id="inputEmail" placeholder="Email" required="required"/>
							<div class="checkbox">
							    <label><input type="checkbox" value="" id="inputNoEmail">I do not have an email address</label>
							</div>
							<span class="help-block"></span>
                    	</div>
                  </div>
				  <div class="form-group">
                    	<label  class="col-sm-4 control-label" for="inputPhone">Contact Phone Number</label>
                    	<div class="col-sm-8">
							<input type="text" class="form-control" id="inputPhone" placeholder="Phone"/>
							<span class="help-block"></span>
                    	</div>
                  </div>
									<div class="form-group">
                    	<label  class="col-sm-4 control-label" for="inputAddress1">Address Line 1</label>
                    	<div class="col-sm-8">
												<input type="text" class="form-control" id="inputAddress1" maxlength="25" placeholder="Address 1"/>
												<span class="help-block"></span>
                    	</div>
                  </div>
									<div class="form-group">
                    	<label  class="col-sm-4 control-label" for="inputAddress2">Address Line 2</label>
                    	<div class="col-sm-8">
												<input type="text" class="form-control" id="inputAddress2" maxlength="25" placeholder="Address 2"/>
												<span class="help-block"></span>
                    	</div>
                  </div>
									<div class="form-group">
                    	<label  class="col-sm-4 control-label" for="inputSuburb">Suburb</label>
                    	<div class="col-sm-8">
												<input type="text" class="form-control" id="inputSuburb" maxlength="25" placeholder="Suburb"/>
												<span class="help-block"></span>
                    	</div>
                  </div>
					<div class="form-group">
							<label  class="col-sm-4 control-label" for="cityList">City</label>
						  <div class="col-sm-8">
								<select class="form-control" name="city" id="cityList" >
									<option selected="" value="">Select</option>
									<?php
										$cities = $controller->getCity();
										foreach($cities as $city) { ?>
											<option value="<?php echo $city["city"]; ?>"><?php echo $city["city"]; ?></option>
										<?php }
									?>
								</select>
								<span class="help-block"></span>
							</div>
					</div>
					<div class="form-group">
							<label  class="col-sm-4 control-label" for="inputPostcode">Post Code</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="inputPostcode" maxlength="4" placeholder="Post Code"/>
	 						<span class="help-block"></span>
							</div>
					</div>
									<div class="form-group required">
											<label  class="col-sm-4 control-label" for="ageBand">Age Band</label>
										  <div class="col-sm-8">
												<select class="form-control" name="age" id="ageBand" required="required">
													<option selected="" value="">Select</option>
													<?php
														$ages = $controller->getAgeList();
														foreach($ages as $age) { ?>
															<option value="<?php echo $age["ageband"]; ?>"><?php echo $age["ageband"]; ?></option>
														<?php }
													?>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group required">
											<label  class="col-sm-4 control-label" for="genderList">Gender</label>
										  <div class="col-sm-8">
													<select class="form-control" name="gender" id="genderList" required="required">
            								<option selected="" value="">Select</option>
														<option value="Male">Male</option>
														<option value="Female">Female</option>
														<option value="Gender Diverse">Gender Diverse</option>
														<option value="Not Stated">Not Stated</option>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group required">
											<label  class="col-sm-4 control-label" for="ethnicityList">What is your ethnicity?</label>
										  <div class="col-sm-8">
												<select  class="form-control" name="ethnicity" id="ethnicityList" required="required">
													<option selected="" value="">Select</option>
													<?php
														$ethnicityList = $controller->getEthnicityList();
														foreach($ethnicityList as $ethnicity) { ?>
															<option value="<?php echo $ethnicity["elist"]; ?>"><?php echo $ethnicity["elist"]; ?></option>
														<?php }
													?>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label  class="col-sm-4 control-label" for="migrantStatusList">Are you a recent migrant to NZ?</label>
										  <div class="col-sm-8">
													<select class="form-control" name="migrantStatus" id="migrantStatusList">
            								<option selected="" value="">Select</option>
														<option value="Yes">Yes</option>
														<option value="No">No</option>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label  class="col-sm-4 control-label" for="refugeeStatusList">Do you have legal Refugee status?</label>
										  <div class="col-sm-8">
													<select class="form-control" name="refugeeStatus" id="refugeeStatusList">
            								<option selected="" value="">Select</option>
														<option value="Yes">Yes</option>
														<option value="No">No</option>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label  class="col-sm-4 control-label" for="heardList">How did you hear about Volunteer Wellington?</label>
										  <div class="col-sm-8">
												<select class="form-control" name="heard" id="heardList">
													<option selected="" value="">Select</option>
													<?php
														$heardList = $controller->getHeardList();
														foreach($heardList as $heard) { ?>
															<option value="<?php echo $heard["howheard"]; ?>"><?php echo $heard["howheard"]; ?></option>
														<?php }
													?>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label  class="col-sm-4 control-label" for="workStatusList">What is your work status?</label>
										  <div class="col-sm-8">
												<select class="form-control" name="workStatus" id="workStatusList">
													<option selected="" value="">Select</option>
													<?php
														$workList = $controller->getWorkStatus();
														foreach($workList as $work) { ?>
															<option value="<?php echo $work["status"]; ?>"><?php echo $work["status"]; ?></option>
														<?php }
													?>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label  class="col-sm-4 control-label" for="volReasonList">Reason for Volunteering?</label>
										  <div class="col-sm-8">
												<select class="form-control" name="volReason" id="volReasonList">
													<option selected="" value="">Select</option>
													<?php
														$reasonList = $controller->getVolReason();
														foreach($reasonList as $reason) { ?>
															<option value="<?php echo $reason["reason"]; ?>"><?php echo $reason["reason"]; ?></option>
														<?php }
													?>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
											<label  class="col-sm-4 control-label" for="volWelOfficeList">Which is your Volunteer Wellington Office?</label>
										  <div class="col-sm-8">
													<select class="form-control" name="volWelOffice" id="volWelOfficeList">
            								<option selected="" value="">Select</option>
														<option value="Well">Wellington</option>
														<option value="Lhutt">Lower Hutt</option>
														<option value="Por">Porirua</option>
     											</select>
												<span class="help-block"></span>
											</div>
									</div>
									<div class="form-group">
										<div class="checkbox">
											<label  class="col-sm-10 control-label" for="volEmergList">I am willing to be added to an emergency volunteer list to be contacted in the event of an emergency/disaster in the Wellington region.</label>
  										<div class="col-sm-2">
												<input type="checkbox" id="volEmergList" value="">
												<span class="help-block"></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-10 control-label" for="volContactList">I am willing to be contacted about:</label>
										<div class="checkbox">
											<label  class="col-sm-10 control-label" for="volPosImpactList">The positive impact Volunteer Wellington has in our local
 communities and how I can support your mahi/work.</label>
											<div class="col-sm-2">
												<input type="checkbox" id="volPosImpactList" value="">
												<span class="help-block"></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="checkbox">
											<label  class="col-sm-10 control-label" for="volOneOffList">One off volunteering events such as street appeals.</label>
											<div class="col-sm-2">
												<input type="checkbox" id="volOneOffList" value="">
												<span class="help-block"></span>
											</div>
										</div>
									</div>
                 	<div class="form-group">
											<div class="col-sm-6">
                      	<button type="button" class="btn btn-primary center-block" onClick="sendJobRegister();" >Register for jobs</button>
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

<!-- No shortlisted modal---->
<div class="modal fade" id="NoShortlistedJobsModal" tabindex="-1" role="dialog" aria-labelledby="NoShortlistedJobsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
           	<span aria-hidden="true">&times;</span>
		</button>
        <h4 class="modal-title alert alert-warning" id="NoShortlistedJobsModalLabel">No Roles in Shortlist</h4>
      </div>
      <div class="modal-body">
        <p>Add roles to your shortlist before proceeding to register.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary center-block" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- No online registration modal---->
<div class="modal fade" id="noOnlineRegistrationModal" tabindex="-1" role="dialog" aria-labelledby="noOnlineRegistrationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
           	<span aria-hidden="true">&times;</span>
				</button>
        <h4 class="modal-title alert alert-warning" id="noOnlineRegistrationModalLabel">Applying for this role requires an appointment</h4>
      </div>
      <div class="modal-body">
        <p>To apply for this role please call the Volunteer Wellington Office nearest you to arrange an appointment time.</p>
				<p>Wellington – 04 499 4570 or <a href="https://calendly.com/volunteerwellington/chat-with-us" target="_blank">book online</a></p>
				<p>Lower Hutt – 04 566 6786or <a href="https://calendly.com/managerhutt/book-a-chat" target="_blank">book online</a></p>
				<p>Porirua – 04 237 5355or <a href="https://calendly.com/volunteerwgtn/informal-interview" target="_blank">book online</a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary center-block" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="maxShortlist" tabindex="-1" role="dialog" aria-labelledby="maxShortlistLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
           	<span aria-hidden="true">&times;</span>
		</button>
        <h4 class="modal-title alert alert-warning" id="maxShortlistLabel">Warning</h4>
      </div>
      <div class="modal-body">
        <p>You can only have <b>ten</b> items on your shortlist.  Remove those you don't want to add more.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary center-block" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <!-- Added to shortlist dialog modal-->
  <div class="modal fade" id="addedToShortlistModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
           	<span aria-hidden="true">&times;</span>
		  </button>
		  <h4 class="modal-title">Added to Shortlist</h4>
        </div>
        <div class="modal-body">
					<p> This role is now in your shortlist.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Already in shortlist dialog modal-->
  <div class="modal fade" id="alreadyInShortlistModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
           	<span aria-hidden="true">&times;</span>
		  </button>
					<h4 class="modal-title">Already Shortlisted</h4>
        </div>
        <div class="modal-body">
					<p> This role is already in your shortlist.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Display registration success dialog modal-->
  <div class="modal fade" id="dialogSuccessModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
           	<span aria-hidden="true">&times;</span>
		  </button>
					<h4 class="modal-title">Registration Success</h4>
        </div>
        <div class="modal-body">
			<p> Your registration for volunteer roles have been recorded.</p>
			<p> <a href="<?php echo $this->action('registration'); ?>">Show Registration Details</a></p>
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
	          <button type="button" class="close" data-dismiss="modal">
           	<span aria-hidden="true">&times;</span>
			  </button>
						<h4 class="modal-title alert alert-warning" id="failure-dialog-title">Registration Failure</h4>
	        </div>
	        <div class="modal-body" id="failure-dialog-message">
						<p> Oh this is embarrasing, something has gone wrong please try to resubmit your role registrations</p>
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
							<button type="button" class="close" data-dismiss="modal">
           	<span aria-hidden="true">&times;</span>
							</button>
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
