<?php
namespace Application\Controller\SinglePage;
// use Concrete\Core\Page\Controller\PageController;
use Core;
use PageController;
use AssetList;
use Asset;
use Mail;

class VwForum extends PageController
{

  public function view()
  {
    //query to get current forum offerings
    $year = date('Y');
    $conn = \Database::connection('jobsearch');
	$sql = "SELECT  *
	FROM `fora`
	WHERE `year` = ?
    AND  `status` = 1
    AND `cd` >= CURDATE()
    ORDER BY `cd` ASC";
	$stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $year);
	$stmt->execute();
	$results = $stmt->fetchAll();
    $this->set('forums', $results);

	$sql = "SELECT  *
	FROM citylist
	WHERE status = 1";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$results = $stmt->fetchAll();
	$this->set('cities', $results);
  }

  public function getDetail() {
    $forumID = $_POST['key'];
    $conn = \Database::connection('jobsearch');
    $sql = "SELECT  *
    FROM `fora`
    WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $forumID);
    $stmt->execute();
    $details = $stmt->fetch();
    //echo print_r($details);
    echo json_encode($details, JSON_FORCE_OBJECT);
    exit;
  }

  public function regForum() {
    $forumSubmission = $_POST['regData'];
    $inputs = json_decode(json_encode(json_decode($forumSubmission)), True);
    // echo print_r($inputs);
    $filters = array(
	    'fid'=>FILTER_SANITIZE_NUMBER_INT,
			'num'=>FILTER_SANITIZE_NUMBER_INT,
    	'org'=>FILTER_SANITIZE_STRING,
      'city'=>FILTER_SANITIZE_STRING,
			'tel'=>FILTER_SANITIZE_STRING,
			'email'=>FILTER_VALIDATE_EMAIL
		);
		$options = array(
    	'fid'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'num'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'org'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'city'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'tel'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'email'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	)
		);

    $fid = intval($inputs[0]['volContact']['fid']);
    $num = intval($inputs[0]['volContact']['num']);
    $org = filter_var($inputs[0]['volContact']['org'],$filters['org'], $options['org']);
    $city = filter_var($inputs[0]['volContact']['city'],$filters['city'], $options['city']);
    $tel = filter_var($inputs[0]['volContact']['tel'],$filters['tel'], $options['tel']);
    $email = filter_var($inputs[0]['volContact']['email'],$filters['email'], $options['email']);


    $conn = \Database::connection('jobsearch');
    $conn -> beginTransaction();
    try {
      /* Find out the next auto-increment ID for the forumregstemp table */
      $sql = "SELECT id
      FROM forumregstemp
      ORDER BY id
      DESC LIMIT 1";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $last_id = $stmt->fetch();
      $reg_rid = intval($last_id['id']) + 1;

      /* query to get forum details */
  		$sql = "SELECT  *
  		FROM `fora`
  		WHERE `id` = ?";
  		$stmt = $conn->prepare($sql);
      $stmt->bindValue(1, $fid);
  		$stmt->execute();
  		$results = $stmt->fetch();
      $wksp = $results['wksp'];
      $year = $results['year'];
      $subject = "Forum $wksp $year";

      $conn->insert('forumregstemp',
      array(
        'id' => NULL,
        'fid' => $fid,
        'num' => $num,
        'org' => $org,
        'city' => $city,
        'tel' => $tel,
        'email' => $email,
        'regdate' => date('Y-m-d'),
        'status' => 1
      ));

      for ($x = 1; $x <= 5; $x++) {
        $key = 'attd'.$x;
        if ($inputs[0]['volContact'][$key]) {
          $$key = $inputs[0]['volContact'][$key];
          $conn->insert('forumattendeestemp',
          array(
            'id' => NULL,
            'frid' => $reg_rid,
            'reg' => $inputs[0]['volContact'][$key],
            'registered' => 0,
            'att' => 0
            )
          );
        }
      }

      /* query to get total number of registered attendees */
      $conn = \Database::connection('jobsearch');
  		$sql = "SELECT  *
  		FROM `forumregstemp`
  		WHERE `fid` = ?";
  		$stmt = $conn->prepare($sql);
      $stmt->bindValue(1, $fid);
  		$stmt->execute();
  		while($results = $stmt->fetch()) {
        $total = $total + $results['num'];
      }


      // Set email content
      $mailContent = "
      <p>To Volunteer Wellington Admin. This mail is generated from the on-line registration form for the Forum on \"$wksp\", $wkmth $wkyr, on the Volunteer Wellington web site. To reply to the registrant use the address supplied by the registrant included below. These data have been entered into the temporary database. Please do not enter them again.</p>
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
      <p><b>Number attending:</b>
      <br />$num</p>
      <p>~~~~~~~~~~~~</p>
      <p><b>The Organisation:</b></p>
      <p>$org</p>
      <p>~~~~~~~~~~~~~~</p>
      <p><b>Phone:</b>
      <br />$tel</p>
      <p>~~~~~~~~~~~~~</p>
      <p><b>Email:</b>
      <br /><a href=\"mailto:$email\">$email</a></p>
      <p>~~~~~~~~~~~~~</p>";
      $mailContent .= "<p>Counting only the number entered each time (not names) we have:
      Number of Registrants entered for this Forum at this point:
      <br />$total</p>
      <p>~~~~~~~~~~~~~~</p>";

      // Send Forum Event Request to VW office
      $mailService = Core::make('mail');
      $mailService->setTesting(false); // or true to throw an exception on error.
      $mailService->load('mail_template');

      // Set email parameters
      $mailService->to('julie@volunteerwellington.nz, office@volunteerwellington.nz');
      $mailService->from('vwforumreg@volunteerwellington.nz');
      $mailService->replyto('vwforumreg@volunteerwellington.nz', 'Online Forum Registration');
      $mailService->setSubject($subject);
      $mailService->setBodyHTML($mailContent);

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
