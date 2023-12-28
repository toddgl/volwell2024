<?php
namespace Application\Controller\SinglePage;
// use Concrete\Core\Page\Controller\PageController;
use Core;
use PageController;
use AssetList;
use Asset;
use Mail;

class VwTraining extends PageController
{

  public function view()
  {
    //query to get current training offerings
    $conn = \Database::connection('jobsearch');
    $sql = "SELECT  `id`, `wksp`, `when`, `wkdte`, `who`, `wkno`, `wktime`
    FROM `trainwksp`
    WHERE `status` = 1
    AND `wkdte` >= CURDATE()
    ORDER BY `wkdte` ASC";
	  $stmt = $conn->prepare($sql);
	  $stmt->execute();
	   $results = $stmt->fetchAll();
    $this->set('trnwksps', $results);
  }

  public function getDetail() {
    $wkspID = $_POST['key'];
    $conn = \Database::connection('jobsearch');
    $sql = "SELECT  *
    FROM `trainwksp`
    WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $wkspID);
    $stmt->execute();
    $details = $stmt->fetch();
    //echo print_r($details);
    echo json_encode($details, JSON_FORCE_OBJECT);
    exit;
  }

  public function regWorkshop() {
    $wkspSubmission = $_POST['regData'];
    $inputs = json_decode(json_encode(json_decode($wkspSubmission)), True);
    // echo print_r($inputs);
    $filters = array(
	    'wid'=>FILTER_SANITIZE_NUMBER_INT,
			'num'=>FILTER_SANITIZE_NUMBER_INT,
    	'org'=>FILTER_SANITIZE_STRING,
			'tel'=>FILTER_SANITIZE_STRING,
			'email'=>FILTER_VALIDATE_EMAIL,
			'amount'=>FILTER_SANITIZE_STRING,
			'mem'=>FILTER_SANITIZE_STRING,
			'ib'=>FILTER_SANITIZE_NUMBER_INT,
			'cheq'=>FILTER_SANITIZE_NUMBER_INT,
			'inv'=>FILTER_SANITIZE_NUMBER_INT
		);
		$options = array(
    	'wid'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'num'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'org'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'tel'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'email'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'amount'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'mem'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'ib'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'cheq'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'inv'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	)
		);

    $wid = intval($inputs[0]['volContact']['wid']);
    $amount = filter_var(ltrim($inputs[0]['volContact']['amount'], "$"),$filters['amount'], $options['amount']);
    $tel = filter_var($inputs[0]['volContact']['tel'],$filters['tel'], $options['tel']);
    $email = filter_var($inputs[0]['volContact']['email'],$filters['email'], $options['email']);
    $ismem = filter_var($inputs[0]['volContact']['mem'],$filters['mem'], $options['mem']);
    $org = filter_var($inputs[0]['volContact']['org'],$filters['org'], $options['org']);
    $ib = filter_var($inputs[0]['volContact']['ib'],$filters['ib'], $options['ib']);
    $chq = filter_var($inputs[0]['volContact']['cheq'],$filters['cheq'], $options['cheq']);
    $inv = filter_var($inputs[0]['volContact']['inv'],$filters['inv'], $options['inv']);

    $conn = \Database::connection('jobsearch');
    $conn -> beginTransaction();
    try {
      /* Find out the next auto-increment ID for the trainwkspregstemp table */
      $sql = "SELECT rid
      FROM trainwkspregstemp
      ORDER BY rid
      DESC LIMIT 1";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $last_id = $stmt->fetch();
      $reg_rid = intval($last_id['rid']) + 1;

      /* query to get selected training details */
  		$sql = "SELECT  *
  		FROM `trainwksp`
  		WHERE `id` = ?";
  		$stmt = $conn->prepare($sql);
      $stmt->bindValue(1, $wid);
  		$stmt->execute();
  		$results = $stmt->fetch();
      $wkno = $results['wkno'];
      $wkmth = $results['wkmth'];
      $wkyr = $results['wkyr'];
      $wksp = $results['wksp'];
      $city = $results['city'];
      $mnum = $results['maxnum'];
      $subject = "$wksp Workshop $wkno $wkyr";

      $conn->insert('trainwkspregstemp',
      array(
        'rid' => NULL,
        'wid' => $wid,
        'num' => intval($inputs[0]['volContact']['num']),
        'org' => $org,
        'add1' => '',
        'add2' => '',
        'sub' => '',
        'city' => '',
        'pcode' => '',
        'pob' => '',
        'tel' => $tel,
        'email' => $email,
        'amount' => $amount,
        'mem' => $ismem,
        'ib' => $ib,
        'chq' => $chq,
        'inv' => $inv,
        'regdate' => date('Y-m-d'),
        'status' => 1,
        'paid' => 0
      ));
      for ($x = 1; $x <= 5; $x++) {
        $key = 'attd'.$x;
        if ($inputs[0]['volContact'][$key]) {
          $$key = $inputs[0]['volContact'][$key];
          $conn->insert('trainwkspattendeestemp',
          array(
            'aid' => NULL,
            'wid' => $reg_rid,
            'reg' => $inputs[0]['volContact'][$key],
            'registered' => 0,
            'att' => 0
          )
        );
        }
      }
    // Set email content
    $mailContent = "
    <p>To Volunteer Wellington Admin. This mail is generated from the on-line registration form for the Training Workshop on \"$wksp\", $wkmth $wkyr, on the Volunteer Wellington web site. To reply to the registrant use the address supplied by the registrant included below. These data have been entered into the temporary database. Please do not enter them again.</p>
    <p><b>N.B.</b> It is possible to \"Reply\" directly back to the registering organisation from this email. Do CHECK the validity of the email address. </p>
    <p><b>Name(s) attending:</b></p>";
    if($attd1!=''){
    $mailContent .= "<p>$attd1</p>";
    }
    if($attd2!=''){
    $mailContent .= "<p>$attd2</p>";
    }
    if($attd3!=''){
    $mailContent .= "<p>$attd3</p>";
    }
    if($attd4!=''){
    $mailContent .= "<p>$attd4</p>";
    }
    if($attd5!=''){
    $mailContent .= "<p>$attd5</p>";
    }
    $mailContent .="<p>~~~~~~~~~~~~</p>
    <p><b>The Organisation:</b></p>
    <p>$org</p>
    <p>~~~~~~~~~~~~~~</p>
    <p><b>Phone:</b>
    <br />$tel</p>
    <p>~~~~~~~~~~~~~</p>
    <p><b>Email:</b>
    <br /><a href=\"mailto:$email\">$email</a></p>
    <p>~~~~~~~~~~~~~</p>
    <p><b>Amount to be paid:</b>
    <br />\$$amount</p>
    <p>~~~~~~~~~~~~~</p>
    <p><b>VW Member:</b>
    <br />$ismem</p>
    <p>~~~~~~~~~~~~~</p>
    <p><b>Payment by:</b>";
    if($ib==1){
    $mailContent .= "<br />Internet Banking</p>";
    }
    if($chq==1){
    $mailContent .= "<br />Cheque</p>";
    }
    if($ib==0 && $chq==0){
    $mailContent .= "<br />No payment method selected</p>";
    }
    $mailContent .="<p>--------------</p>";
    if($inv==1){
    $mailContent .= "<p><b>Yes, an invoice is required</b></p>";
    }

    // Send Workshop Event Request to VW office
    $mailService = Core::make('mail');
    $mailService->setTesting(false); // or true to throw an exception on error.
    $mailService->load('mail_template');

    // Set email parameters
    $mailService->to('julie@volunteerwellington.nz, office@volunteerwellington.nz');
    $mailService->from('vwwrkshpreg@volunteerwellington.nz');
    $mailService->replyto('vwwrkshpreg@volunteerwellington.nz', 'Online Workshop Registration');
    $mailService->setSubject($subject);
    $mailService->setBodyHTML($mailContent);

    // Send email
    $mailService->sendMail();

  }
  catch(\Exception $e) {
    $conn->rollback();
    throw $e;
  }
  exit;
  }
}
?>
