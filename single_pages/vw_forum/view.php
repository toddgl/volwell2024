<?php
defined('C5_EXECUTE') or die('Access Denied.')
?>
<script src="https://cdn.ravenjs.com/3.20.1/raven.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
	Raven.config('https://606f3fd66dd04223a83abba161de7248@sentry.io/247542').install();
</script>

<script type="text/javascript">
var id;

$(document).ready(function() {
		$('#forumModal').on('hidden.bs.modal', function () {
			$('#forumModal').removeData('bs.modal');
			$('#forumModal').find('.modal-content').empty;
	});
		$('#dialogModal').on('hidden.bs.modal', function () {
			$('#dialogModal').removeData('bs.modal');
			$('dialogModal').find('.modal-content').empty;
	});

	$('#forumModal').on('shown.bs.modal', function(e) {
		gtag('event', 'view_item', {'event_label': 'forum', 'items': [{'id': id, 'category': 'forum'}]});
	});

	$('#forumModal').on('show.bs.modal', function(e) {
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
			var closedate = new Date(result['cd']);
			var forumdate = new Date(result['fdate']);
			var dateoptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

			// Changes 20180313 to support non-vw forum display
			if (result['cc'] == "1") {
        nonvw.style.visibility='visible';
        nonvw.style.display='block';
				isvw.style.visibility='hidden';
        isvw.style.display='none';
				if (result['fee'] ==1) {
					$('#nonvwforumcosts').html(result['feeam']);
				}
				$('#nonvwforumdescript').html('RSVP: by ' + closedate.toLocaleDateString("en-GB", dateoptions) + ' to ' + linkify(result['ccem']) + ' Please include which community organisation you represent in your email.');
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
			$('#when').html(forumdate.toLocaleDateString("en-GB", dateoptions) + ' - ' + result['wktime']);
			$('#facilitator').html(result['who']);
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert("error");
		});
});
});

function sendForumRegister() {
var valid;
valid = validateForm();
if(valid) {
	var jsonData = [];
	var volContact ={};
	volContact.fid = id;
	volContact.num = $("#inputnum").val();
	volContact.attd1 = $("#inputattendee1").val();
	volContact.attd2 = $("#inputattendee2").val();
	volContact.attd3 = $("#inputattendee3").val();
	volContact.attd4 = $("#inputattendee4").val();
	volContact.attd5 = $("#inputattendee5").val();
	volContact.org = $("#inputorg").val();
	volContact.city = $("#inputcity").val();
	volContact.tel = $("#inputtel").val();
	volContact.email = $("#inputemail").val();

	jsonData.push({volContact: volContact});
	//alert (JSON.stringify(jsonData));
	$.ajax({
		type: 'POST',
		url: "<?=$view->action('regForum')?>",
		datatype: 'json',
		data: {regData: JSON.stringify(jsonData)},
		cache: false,
	}).done(function(data, textStatus, jqXHR){
		//var result = $.parseJSON(data);
		//alert(textStatus);
		$('#forumModal').modal('hide');
		$('#dialogSuccessModal').modal('show');
		gtag('event', 'purchase', {'event_label': 'forum', 'items': [{'id': id, 'category': 'forum'}]});
	}).fail(function(jqXHR, textStatus, errorThrown){
		// alert(errorThrown);
		$('#forumModal').modal('hide');
		$('#dialogFailureModal').modal('show');
	});
	} else {
	$('#dialogMissingItemsModal').modal('show');
	};
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
	$("#forumModal input[required=required], #forumModal textarea[required=required], #forumModal select[required=required], #forumModal input[type=email], #forumModal input[type=number], #forumModal input[type=date]").each(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).parent().find("> span.help-block").text("");
		if(!$(this).val()){
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
        <h3 class="bg-secondary white" style="center">Forums</h3>
        <div>
          <table class="table table-hover wksp_table">
            <tbody>
              <?php
		if (empty($forums)) { ?>
                <tr><td>There are no upcoming forums</td></tr>
                <?php
		} else {
                foreach ($forums as $forum) { ?>
                <tr>
                  <td><time class="icon"><strong><?php echo $forum["wday"] . ' ' . $forum["whendy"] . ' ' . $forum["whenm"]; ?></strong><em><?php echo $forum["wktime"]; ?></em></time></td>
                  <td><h3 class="vol-font"><?php echo $forum["wksp"]; ?></h3>
                    <p class="vol-font">Facilitator : <?php echo $forum["who"]; ?></p>
                    <div class="blue" >
	                      <a href = "#" data-toggle="modal" data-target="#forumModal" data-id="<?php echo $forum_id= $forum["id"]; ?>" >More details...</a>
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
<div id="forumModal" class="modal fade" role="dialog">
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
    			<form class="form-horizontal" id="forumRegisterForm">
					<div class="form-group required">
						<label  class="col-sm-4 control-label" for="inputnum">Number of Attendees: </label>
						<div class="col-sm-6">
							<input class="form-control" type="text" id="inputnum" placeholder="Number, e.g. 2" required="required">
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
						<label  class="col-sm-4 control-label" for="inputCity">City</label>
						  <div class="col-sm-6">
							<select name="city" class="form-control" id="inputcity" required="required">
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
							<div class="col-sm-6">
								<button type="button" class="btn btn-primary center-block" onClick="sendForumRegister();" >Register for Forum</button>
							</div>
							<div class="col-sm-6">
								<button type="button" class="btn btn-primary center-block" data-dismiss="modal">Close</button>
							</div>
					</div>
				</form>
			</div>
			<div id="nonvw">
				<form class="form-horizontal" id="nonVWRegisterForm">
					<div class="modal-detail col-sm-offset-1" id="nonvwforumcosts"></div>
					<div class="modal-detail col-sm-offset-1" id="nonvwforumdescript"></div>
					<div class="form-group">
							<div class="col-sm-6">
								<!-- empty -->
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
					<p> Your registration for the forum has been recorded</p>
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
