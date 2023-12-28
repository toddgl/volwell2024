<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>

<script src="https://cdn.ravenjs.com/3.20.1/raven.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
	Raven.config('https://606f3fd66dd04223a83abba161de7248@sentry.io/247542').install();
</script>

<script type="text/javascript">

function sendEVRegFunction() {
	var valid;
	// alert ("Got here");
	valid = validateForm();
	if(valid) {
		var jsonData = [];
		var evContact ={};
		evContact.name = $("#inputContactName").val();
		evContact.org = $("#inputOrgName").val();
		evContact.addr1 = $("#inputAddress1").val();
		evContact.addr2 = $("#inputAddress2").val();
		evContact.pobox = $("#inputPOBox").val();
		evContact.suburb = $("#inputSuburb").val();
		evContact.city = $("#inputCity").val();
		evContact.pcode = $("#inputPostCode").val();
		evContact.email = $("#inputEmail").val();


		jsonData.push({evContact: evContact});
		// alert(JSON.stringify(jsonData));

		$.ajax({
			type: 'POST',
			url: "<?=$view->action('regEVMember')?>",
			datatype: 'json',
			data: {evRegData: JSON.stringify(jsonData)},
			cache: false,
		}).done(function(data, textStatus, jqXHR){
			// alert("Success: " + data);
			$('#dialogSuccessModal').modal('show');
			gtag('event', 'purchase', {'event_label': 'member', 'items': [{'category': 'member'}]});
		}).fail(function(jqXHR, textStatus, errorThrown){
			// alert("Failure: " + errorThrown);
			$('#dialogFailureModal').modal('show');
		});
		} else {
		$('#dialogMissingItemsModal').modal('show');
		};
	};

function validateForm() {
	var valid = true;
	$("#evRegForm input[required=required], #evRegForm text[required=required], #evRegForm input[type=email]").each(function() {
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

</script>

<div class="main-container text-center">
	<div class="row">
		<div class="col-md-8">
			<?php
				$areaLMain1 = new Area('Left_Main1');
				$areaLMain1->display($c);
			?>
		</div>
		<div class="col-md-4" >
			<form class="form-vertical" id="evRegForm">
  			<div class="form-group required">
    			<label class="control-label" for="inputContactName">Contact Name</label>
    			<input type="text" class="form-control" id="inputContactName" placeholder="Enter contact name" required="required" />
					<span class="help-block"></span>
  			</div>
				<div class="form-group required">
    			<label class="control-label" for="inputOrgName">Organisation Name</label>
    			<input type="text" class="form-control" id="inputOrgName" placeholder="Enter organisation name" required="required" />
					<span class="help-block"></span>
  			</div>
				<div class="form-group">
    			<label class="control-label" for="inputPOBox">Post Box</label>
    			<input type="text" class="form-control" id="inputPOBox" placeholder="Enter Post Box" />
  			</div>
				<div class="form-group">
    			<label class="control-label" for="inputAddress1">Address 1</label>
    			<input type="text" class="form-control" id="inputAddress1" placeholder="Enter Address Line 1" />
  			</div>
				<div class="form-group">
    			<label class="control-label" for="inputAddress2">Address 2</label>
    			<input type="text" class="form-control" id="inputAddress2" placeholder="Enter Address Line 2" />
  			</div>
				<div class="form-group">
    			<label class="control-label" for="inputSuburb">Suburb</label>
    			<input type="text" class="form-control" id="inputSuburb" placeholder="Enter Suburb" />
  			</div>
				<div class="form-group">
    			<label class="control-label" for="inputCity">City</label>
    			<input type="text" class="form-control" id="inputCity" placeholder="Enter City" />
  			</div>
				<div class="form-group">
    			<label class="control-label" for="inputPostCode">Post Postcode</label>
    			<input type="text" class="form-control" id="inputPostCode" placeholder="Enter Postcode" />
  			</div>
				<div class="form-group required">
    			<label class="control-label" for="inputEmail">Email</label>
    			<input type="email" class="form-control" id="inputEmail" placeholder="Enter Email" required="required" />
					<span class="help-block"></span>
  			</div>
				<div class="form-group">
					<button type="button" class="btn btn-primary shadow" id="btnEVRegister" onClick="sendEVRegFunction();">
						Register your interest
					</button>
				</div>
			</form>
		</div>
	</div>
</div >

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
