<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>


<script src="https://cdn.ravenjs.com/3.20.1/raven.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
	Raven.config('https://606f3fd66dd04223a83abba161de7248@sentry.io/247542').install();
</script>

<script type="text/javascript">
window.onload = function() {

	$('#feeRadioBtns').on('click', function(e) {
		if ($('#inputnum').val()) {
		calcTotalCost()
		};
	});
	$('#inputnum').on('change', function(e) {
		calcTotalCost()
	});
	$('#inputnum').on('click', function(e) {
		calcTotalCost()
	});
};
var id;
var wkshptitle;
var ccurl;
var ccemail;
var isccurl;

$(document).ready(function() {
		$('#wkspModal').on('hidden.bs.modal', function () {
			$('#wkspModal').removeData('bs.modal');
			$('#wkspModal').find('.modal-content').empty;
			$('#wkspModal').find('form').trigger('reset');
	});
		$('#dialogModal').on('hidden.bs.modal', function () {
			$('#dialogModal').removeData('bs.modal');
			$('dialogModal').find('.modal-content').empty;
	});

	$('#wkspModal').on('shown.bs.modal', function(e) {
		gtag('event', 'view_item', {'event_label': 'training', 'items': [{'id': id, 'category': 'training'}]});
	});

	$('#wkspModal').on('show.bs.modal', function(e) {
		// Remove radio checkbox children
		var myNode = document.getElementById("feeRadioBtns");
		while (myNode.firstChild) {
			myNode.removeChild(myNode.firstChild);
		};
		id = $(e.relatedTarget).data('id');
		// alert(id);
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
			var linebreak = document.createElement('br');

			// Changes 20191201 to support non-vw workshop display
			wkshptitle = result['wksp'];
			if (result['cc'] == "1") {
        nonvw.style.visibility='visible';
        nonvw.style.display='block';
				isvw.style.visibility='hidden';
        isvw.style.display='none';
				//$('#nonvwwkspdescript').html('Register at ' + linkify(result['ccwww']));
				if (result['ccwww']==="") {
					ccemail = result['ccem'];
					$('#nonvwwkspdescript').html('Registration of this event is by email. Clicking on the Register for Workshop button will open a new email composer screen. Please include your name, the organisation and contact details before sending the email.');
					isccurl = 0;
				}
				else {
					ccurl = result['ccwww'];
					$('#nonvwwkspdescript').html('Registration of this event is by our partner organisation. Clicking on the Register for Workshop button will take you to the registration website.');
					isccurl = 1;
				}
    	}
    	if (result['cc'] == "0") {
        nonvw.style.visibility='hidden';
        nonvw.style.display='none';
				isvw.style.visibility='visible';
        isvw.style.display='block';
    	}
			//alert(data);
			$('.modal-title').html(result['wksp']);
			$('#description').html(result['bodytxt1'] + ' ' + result['bodytxt2'] + ' ' + result['bodytxt3'] + ' ' + result['bodytxt4'] + ' ' + result['bodytxt5']);
			$('#where').html(result['where1'] + ' ' + result['where2'] + ' ' + result['where3'] + ' ' + result['city']);
			$('#when').html(result['when'] + ' - ' + result['wktime']);
			$('#food').html(result['food']);
			$('#cost1').html(result['wlab1'] + ' - ' + result['fees1']);
			var cost1Btn = makeRadioButton("radio_1","costRadios",result['fees1'],result['wlab1']);
			feeRadioBtns.appendChild(cost1Btn);
			$("#radio_1").attr('checked', 'checked');
			if (result['wlab2'].length === 0) {
				// Empty #cost2
				$('#cost2').html(result['wlab2'] + result['fee2']);
			}
			else {
				// List the Price
				$('#cost2').html(result['wlab2'] + ' - ' + result['fee2']);
				// Create the radio button
				var cost2Btn = makeRadioButton("radio_2","costRadios",result['fee2'],result['wlab2']);
				feeRadioBtns.appendChild(linebreak);
				feeRadioBtns.appendChild(cost2Btn);
			}
			if (result['wlab3'].length === 0) {
				// Empty #cost3
				$('#cost3').html(result['wlab3'] + result['fees3']);
			}
			else {
				// List the Price
				$('#cost3').html(result['wlab3'] + ' - ' + result['fees3']);
				// Create the radio button
				var cost3Btn = makeRadioButton("radio_3","costRadios",result['fees3'],result['wlab3']);
				feeRadioBtns.appendChild(linebreak);
				feeRadioBtns.appendChild(cost3Btn);
			}
			if (result['wlab4'].length === 0) {
				// Empty #cost4
				$('#cost4').html(result['wlab4'] + result['fees4']);
			}
			else {
				// List the Price
				$('#cost4').html(result['wlab4'] + ' - ' + result['fees4']);
				// Create the radio button
				var cost4Btn = makeRadioButton("radio_4","costRadios",result['fees4'],result['wlab4']);
				feeRadioBtns.appendChild(linebreak);
				feeRadioBtns.appendChild(cost4Btn);
			}
			if (result['wlab5'].length === 0) {
				// Empty #cost5
				$('#cost5').html(result['wlab5'] + result['fees5']);
			}
			else {
				// List the Price
				$('#cost5').html(result['wlab5'] + ' - ' + result['fees5']);
				// Create the radio button
				var cost5Btn = makeRadioButton("radio_5","costRadios",result['fees5'],result['wlab5']);
				feeRadioBtns.appendChild(linebreak);
				feeRadioBtns.appendChild(cost5Btn);
			}
			if (result['wlab6'].length === 0) {
				// Empty #cost6
				$('#cost6').html(result['wlab6'] + result['fees6']);
			}
			else {
				// List the Price
				$('#cost6').html(result['wlab6'] + ' - ' + result['fees6']);
				// Create the radio button
				var cost6Btn = makeRadioButton("radio_6","costRadios",result['fees6'],result['wlab6']);
				feeRadioBtns.appendChild(linebreak);
				feeRadioBtns.appendChild(cost6Btn);
			}
			$('#facilitator').html(result['bodytxt6']);
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert("error");
		});
});
});

function calcTotalCost() {
var rateAmount = parseFloat((document.querySelector('input[name = "costRadios"]:checked').value).replace('$',''));
var attendNumber = parseFloat($('#inputnum').val());
var totalCost = (rateAmount * attendNumber);
$('#inputamount').val('$' + parseFloat(totalCost, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
//alert('inputName= '+ radioName.replace('$','') + 'number of attendees: ' + attendNumber);
};

function makeRadioButton(id, name, value, text) {

	var label = document.createElement("label");
	var radio = document.createElement("input");
	radio.type = "radio";
	radio.id = id;
	radio.name = name;
	radio.value = value;

	label.appendChild(radio);

	label.appendChild(document.createTextNode(text));
	return label;
};

function sendwkspRegister() {
var valid;
valid = validateForm();
if(valid) {
	var jsonData = [];
	var volContact ={};
	volContact.wid = id;
	volContact.num = $("#inputnum").val();
	volContact.attd1 = $("#inputattendee1").val();
	volContact.attd2 = $("#inputattendee2").val();
	volContact.attd3 = $("#inputattendee3").val();
	volContact.attd4 = $("#inputattendee4").val();
	volContact.attd5 = $("#inputattendee5").val();
	volContact.org = $("#inputorg").val();
	volContact.tel = $("#inputtel").val();
	volContact.email = $("#inputemail").val();
	volContact.amount = $("#inputamount").val();
	if ($("#radio_1").is(":checked") == true) {
		volContact.mem = "Yes";
	} else {
		volContact.mem = "No";
	}
	if ($("#ibPayment").is(":checked") == true) {
		volContact.ib = 1;
	} else {
		volContact.ib = 0;
	}
	//if ($("#chkPayment").is(":checked") == true) {
	//	volContact.cheq = 1;
	//} else {
	volContact.cheq = 0;
	//}
	if ($("#invPayment").is(":checked") == true) {
		volContact.inv = 1;
	} else {
		volContact.inv = 0;
	}
	jsonData.push({volContact: volContact});
	//alert (JSON.stringify(jsonData));
	$.ajax({
		type: 'POST',
		url: "<?=$view->action('regWorkshop')?>",
		datatype: 'json',
		data: {regData: JSON.stringify(jsonData)},
		cache: false,
	}).done(function(data, textStatus, jqXHR){
		//var result = $.parseJSON(data);
		//alert(textStatus);
		$('#wkspModal').modal('hide');
		$('#dialogSuccessModal').modal('show');
		gtag('event', 'purchase', {'event_label': 'training', 'items': [{'id': id, 'category': 'training'}]});
	}).fail(function(jqXHR, textStatus, errorThrown){
		// alert(errorThrown);
		$('#wkspModal').modal('hide');
		$('#dialogFailureModal').modal('show');
	});
	} else {
	$('#dialogMissingItemsModal').modal('show');
	};
};

function reqRegister() {
	if (isccurl == 1) {
		var win = window.open(ccurl,"_blank");
		win.focus();
	}
	else {
		var mail = document.createElement("a");
		mail.href = "mailto:" + ccemail + "?subject=Registration Notification - " + wkshptitle;
		mail.click();
	}
};

function linkify(inputText) {
    var replacedText, replacePattern1, replacePattern2, replacePattern3;

    //URLs starting with http://, https://, or ftp://
    replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

    //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
    replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
    replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

    //Change email addresses to mailto:: links.
    replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
    replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

    return replacedText;
}

function validateForm() {
var valid = true;
$("#wkspModal input[required=required], #wkspModal textarea[required=required], #wkspModal select[required=required], #wkspModal input[type=email], #wkspModal input[type=number], #wkspModal input[type=date]").each(function() {
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

/*$(function() {
$( document ).tooltip({
	position: {my: "left top", at: "right top"},
	items: "input[required=required], textarea[required=required], select[required=required]",
	content: function() {return $(this).attr( "title" );}
});
});*/
</script>

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
      <div class="col-md-3">
        <?php
  				$areaLContent1 = new Area('Left_Content1');
  				$areaLContent1->display($c);
  			?>
      </div>
      <div class="col-md-6">
        <h3 class="bg-secondary white" style="center">Professional Development Workshops </h3>
        <div>
          <table class="table table-hover wksp_table">
            <tbody>
              <?php
		if (empty($trnwksps)) { ?>
                <tr><td>There are no upcoming training events</td></tr>
                <?php
		} else {
                foreach ($trnwksps as $trnwksp) { ?>
                <tr>
                  <td><time class="icon"><strong><?php echo $trnwksp["when"]; ?></strong><em><?php echo $trnwksp["wktime"]; ?></em></time></td>
                  <td><h3 class="vol-font"><?php echo $trnwksp["wksp"]; ?></h3>
                    <p class="vol-font">Facilitator : <?php echo $trnwksp["who"]; ?></p>
                    <div class="blue" rel=<?php echo $trnwksp["wkno"]; ?>>
	                      <a href = "#" data-toggle="modal" data-target="#wkspModal" data-id="<?php echo $trnwksp_id= $trnwksp["id"]; ?>" >More details...</a>
	                  </div>
                  </td>
                </tr>
                <?php
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-3">
        <?php
  				$areaRContent1 = new Area('Right_Content1');
  				$areaRContent1->display($c);
  			?>
      </div>
    </div>
</div>

<!-- Dispay Detail Modal -->
<div id="wkspModal" class="modal fade" role="dialog">
  <div class="modal-dialog custom-class">
    <!-- Modal content-->
    <div class="modal-content">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title"></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="modal-detail col-sm-offset-1" id="description"></div>
				<br>
				<div class= "col-sm-3"><strong>Where:</strong></div>
				<div class="col-sm-8">
					<div class="modal-detail" id="where"></div>
				</div>
			</div>
			<div class="row">
				<div class= "col-sm-3"><strong>When:</strong></div>
				<div class="col-sm-8">
					<div class="modal-detail" id="when"></div>
				</div>
			</div>
			<div class="row">
				<div class= "col-sm-3"><strong>Food:</strong></div>
				<div class="col-sm-8">
					<div class="modal-detail" id="food"></div>
				</div>
			</div>
			<div class="row">
				<div class= "col-sm-3"><strong>Fees:</strong></div>
				<div class="col-sm-8">
					<div class="modal-detail" id="cost1"></div>
					<div class="modal-detail" id="cost2"></div>
					<div class="modal-detail" id="cost3"></div>
					<div class="modal-detail" id="cost4"></div>
					<div class="modal-detail" id="cost5"></div>
					<div class="modal-detail" id="cost6"></div>
				</div>
			</div>
			<div class="row">
				<div class= "col-sm-3"><strong>Facilitator::</strong></div>
				<div class="col-sm-8">
					<div class="modal-detail" id="facilitator"></div>
				</div>
			</div>
			<div class="row">
				<div class= "col-sm-12">
					<hr class=" half-rule"/>
					<div class="text-center"><h4>Registration:</h4></div>
				</div>
			</div>
			<div id="isvw">
    		<form class="form-horizontal" id="wkspRegisterForm">
					<div class="form-group required">
						<label  class="col-sm-4 control-label" for="inputnum">Number of Attendees: </label>
						<div class="col-sm-6">
		        	<input class="form-control" type="number" id="inputnum" placeholder="Number, e.g. 2" required="required">
			  	<span class="help-block"></span>
						</div>
		    	</div>
    			<div class="form-group required">
        		<label class="col-sm-4 control-label" for="inputattname1" >Name of attendee 1: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="text" id="inputattendee1" placeholder="Full Name" required="required">
			  	<span class="help-block"></span>
						</div>
    			</div>
					<div class="form-group">
        		<label class="col-sm-4 control-label" for="inputattname2" >Name of attendee 2: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="text" id="inputattendee2" placeholder="Full Name">
			  	<span class="help-block"></span>
						</div>
    			</div>
					<div class="form-group">
        		<label class="col-sm-4 control-label" for="inputattname3" >Name of attendee 3: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="text" id="inputattendee3" placeholder="Full Name">
			  	<span class="help-block"></span>
						</div>
    			</div>
					<div class="form-group">
        		<label class="col-sm-4 control-label" for="inputattname4" >Name of attendee 4: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="text" id="inputattendee4" placeholder="Full Name">
			  	<span class="help-block"></span>
						</div>
    			</div>
					<div class="form-group">
        		<label class="col-sm-4 control-label" for="inputattname5" >Name of attendee 5: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="text" id="inputattendee5" placeholder="Full Name">
			  	<span class="help-block"></span>
						</div>
    			</div>
    			<div class="form-group required">
        		<label class="col-sm-4 control-label" for="inputorg">Organisation: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="text" id="inputorg" placeholder="Organisation" required="required">
			  	<span class="help-block"></span>
						</div>
					</div>
    			<div class="form-group required">
        		<label class="col-sm-4 control-label" for="inputtel">Phone: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="text" id="inputtel" placeholder="Phone No" required="required">
			  	<span class="help-block"></span>
						</div>
					</div>
    			<div class="form-group required">
        		<label class="col-sm-4 control-label" for="inputemail">Email address: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="email" id="inputemail" placeholder="Email" required="required">
			  	<span class="help-block"></span>
						</div>
					</div>
    			<div class="form-group">
        		<label class="col-sm-4 control-label" for="feeRadios">Cost per person: </label>
						<div class="col-sm-6">
            	<div class="radio" id="feeRadioBtns"></div>
    				</div>
					</div>
					<div class="form-group">
        		<label class="col-sm-4 control-label" for="inputamount">Amount to be Paid: </label>
						<div class="col-sm-6">
        			<input class="form-control" type="text" id="inputamount" placeholder="Calculated Cost" readonly="readonly">
                <label>
                    <input type="radio" name="paymentRadios" id="ibPayment" checked="checked">Internet Banking Acc No 06-0513-0116471-25. <br />Include your name and the workshop name
								</label>
                <!-- <label>
                    <input type="radio" name="paymentRadios" id="chkPayment">By Check. Pay to <strong>Volunteer Wellington</strong><br />Send to: <strong>Volunteer Wellington, PO Box 24130, Wellington.</strong>
                </label> -->
            </div>
					</div>
					<div class="form-group">
							<label class="col-sm-4 control-label" for="invPayment"></label>
							<div class="col-sm-6">
	              <label>
	                  <input type="checkbox" name="paymentCheckbox" id="invPayment">An invoice is required
	              </label>
	            </div>
					</div>
					<div class="form-group">
							<div class="col-sm-6">
								<button type="button" class="btn btn-primary center-block" onClick="sendwkspRegister();" >Register for Workshop</button>
							</div>
							<div class="col-sm-6">
								<button type="button" class="btn btn-primary center-block" data-dismiss="modal">Close</button>
							</div>
					</div>
			</form>
		</div>
		<div id="nonvw">
			<form class="form-horizontal" id="nonVWRegisterForm">
				<div class="modal-detail col-sm-offset-1" id="nonvwwkspdescript"></div>
				<div class="form-group">
						<div class="col-sm-6">
							<div class="col-sm-6">
								<button type="button" class="btn btn-primary center-block" onClick="reqRegister();" >Register for Workshop</button>
							</div>
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
</div>

<!-- Display registration success dialog modal-->
  <div class="modal fade" id="dialogSuccessModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="success-dialog-title">Registration Success</h4>
        </div>
        <div class="modal-body" id="success-dialog-message">
					<p> Your registration for the workshop has been recorded</p>
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
	          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
	          <h4 class="modal-title alert alert-warning" id="failure-dialog-title">Registration Failure</h4>
	        </div>
	        <div class="modal-body" id="failure-dialog-message">
						<p> Oh this is embarrassing, something has gone wrong please try to resubmit your registration</p>
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
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
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
