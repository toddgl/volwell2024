<?php
namespace Application\Controller\SinglePage;
// use Concrete\Core\Page\Controller\PageController;
use Core;
use PageController;
use AssetList;
use Asset;
use Mail;

class EvCorpRegister extends PageController
{

  public function regEVMember() {
    $evRegSubmission = $_POST['evRegData'];

    $inputs = json_decode(json_encode(json_decode($evRegSubmission)), True);$filters = array(
      'name'=>FILTER_SANITIZE_STRING,
      'org'=>FILTER_SANITIZE_STRING,
      'addr1'=>FILTER_SANITIZE_STRING,
      'addr2'=>FILTER_SANITIZE_STRING,
      'pobox'=>FILTER_SANITIZE_STRING,
      'suburb'=>FILTER_SANITIZE_STRING,
      'city'=>FILTER_SANITIZE_STRING,
      'pcode'=>FILTER_SANITIZE_STRING,
      'email'=>FILTER_VALIDATE_EMAIL
    );
    $options = array(
      'name'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'org'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
      ),
      'addr1'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
      ),
      'addr2'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
      ),
      'pobox'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'suburb'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'city'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
      ),
      'pcode'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
      ),
      'email'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
      )
    );

    $orgContact = filter_var($inputs[0]['evContact']['name'],$filters['name'], $options['name']);
    $orgName = filter_var($inputs[0]['evContact']['org'],$filters['org'], $options['org']);
    $orgAddress1 = filter_var($inputs[0]['evContact']['addr1'],$filters['addr1'], $options['addr1']);
    $orgAddress2 = filter_var($inputs[0]['evContact']['addr2'],$filters['addr2'], $options['addr2']);
    $orgPOBox = filter_var($inputs[0]['evContact']['pobox'],$filters['pobox'], $options['pobox']);
    $orgSuburb = filter_var($inputs[0]['evContact']['suburb'],$filters['suburb'], $options['suburb']);
    $orgCity = filter_var($inputs[0]['evContact']['city'],$filters['city'], $options['city']);
    $orgPcode = filter_var($inputs[0]['evContact']['pcode'],$filters['pcode'], $options['pcode']);
    $orgEmail = filter_var($inputs[0]['evContact']['email'],$filters['email'], $options['email']);
    $orgCreatedate = date('Y-m-d');

    // Set email content
    $mailContent = "
    <p>You have received this mail from the Volunteer Wellington web site. The data below is that which has been entered by the organisation registering an interest in the Employee Volunteer programme.</p>
    <p>The registering organisation have also been sent an email confirming their registration of interest.</p>
    <fieldset>
    <legend>CORPORATION REGISTRATION OF EV INTEREST</legend>
    <ul>
    <li><label>Contact: </label><b> &nbsp;$orgContact</b></li>
    <li><label>Organisation: </label><b> &nbsp;$orgName</b></li>
    <li><label>Address1: </label><b> &nbsp;$orgAddress1</b></li>
    <li><label>Address2: </label><b> &nbsp;$orgAddress2</b></li>
    <li><label>PO Box: </label><b> &nbsp;$orgPOBox</b></li>
    <li><label>Suburb: </label><b> &nbsp;$orgSuburb</b></li>
    <li><label>City: </label><b> &nbsp;$orgCity</b></li>
    <li><label>Postcode: </label><b> &nbsp;$orgPcode</b></li>
    <li><label>Email: </label><b> &nbsp;$orgEmail</b></li>
    </ul>
    </fieldset>";

    $mailEVRegContent = "
    <p>Thank you for registering your interest in Volunteer Wellington's Employee Volunteering programme. </p>
    <p>We will be making contact shortly to discuss the options available for your organisation.</p>
    ";


    $conn = \Database::connection('jobsearch');
    $conn -> beginTransaction();
    try {
      $conn->insert('evcoregtemp',
      array(
        'id' => NULL,
        'con' => $orgContact,
        'org' => $orgName,
        'add1' => $orgAddress1,
        'add2' => $orgAddress2,
        'pob' => $orgPOBox,
        'sub' => $orgSuburb,
        'city' => $orgCity,
        'pcode' => $orgPcode,
        'email' => $orgEmail,
        'createdate' => $orgCreatedate

      ));
      // Send Membership Request to VW office
  		$mailService = Core::make('mail');
  		$mailService->setTesting(true); // or true to throw an exception on error.
  		$mailService->load('mail_template');

      // Set email parameters
      $mailService->to('ev@volunteerwellington.nz');
      $mailService->from('Registrationonline@volunteerwellington.nz');
  		$mailService->replyto('ev@volunteerwellington.nz', 'Online EV Registration of Interest');
  		$mailService->setSubject('OnLine Corporation EV Registration of Interest');
  		$mailService->setBodyHTML($mailContent);

  		// Send email
  		$mailService->sendMail();

      // Send email to Member Request Organisation
  		$mailService = Core::make('mail');
  		$mailService->setTesting(false); // or true to throw an exception on error.
  		$mailService->load('mail_template');

      // Set email parameters
  		$mailService->to($orgEmail);
      $mailService->from('Registrationonline@volunteerwellington.nz');
      $mailService->replyto('ev@volunteerwellington.nz', 'Online EV Registration of Interest');
  		$mailService->setSubject('OnLine Corporation EV Registration of Interest');
  		$mailService->setBodyHTML($mailEVRegContent);

  		// Send email
  		$mailService->sendMail();

      $conn->commit();
    }
    catch(\Exception $e) {
    $conn->rollback();
    throw $e;
    }
    exit;
  }
}
?>
