<?php
namespace Application\Controller\SinglePage;
// use Concrete\Core\Page\Controller\PageController;
use Core;
use PageController;
use AssetList;
use Asset;
use Mail;

class VolRequest extends PageController
{
  public function view()
  {
    //query to get current membership fees
    $conn = \Database::connection('jobsearch');
		$sql = "SELECT  `amount`, `detail`
		FROM `agencyfees`
		ORDER BY `order`";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll();
		$this->set('agencyfees', $results);

		$sql = "SELECT  *
		FROM citylist
		WHERE status = 1";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll();
		$this->set('cities', $results);
  }

  public function reqVolunteers() {
    $reqSubmission = $_POST['reqData'];
    $inputs = json_decode(json_encode(json_decode($reqSubmission)), True);
    // echo print_r($inputs);
    $filters = array(
    	'org'=>FILTER_SANITIZE_STRING,
      'branch'=>FILTER_SANITIZE_STRING,
      'title'=>FILTER_SANITIZE_STRING,
      'descrip'=>FILTER_SANITIZE_STRING,
      'skills'=>FILTER_SANITIZE_STRING,
      'supervisor'=>FILTER_SANITIZE_STRING,
      'jobadd1'=>FILTER_SANITIZE_STRING,
      'jobadd2'=>FILTER_SANITIZE_STRING,
			'jobsub'=>FILTER_SANITIZE_STRING,
      'jobcity'=>FILTER_SANITIZE_STRING,
			'jem'=>FILTER_SANITIZE_STRING,
      'jtel'=>FILTER_SANITIZE_STRING,
      'ref'=>FILTER_SANITIZE_STRING,
      'training'=>FILTER_SANITIZE_STRING,
      'hstraining'=>FILTER_SANITIZE_STRING,
      'reimbursement'=>FILTER_SANITIZE_NUMBER_INT,
      'dayshours'=>FILTER_SANITIZE_STRING,
      'personality'=>FILTER_SANITIZE_STRING,
      'policeck'=>FILTER_SANITIZE_NUMBER_INT,
      'online'=>FILTER_SANITIZE_NUMBER_INT,
      'ong'=>FILTER_SANITIZE_NUMBER_INT,
      'st'=>FILTER_SANITIZE_NUMBER_INT,
      'sp'=>FILTER_SANITIZE_NUMBER_INT,
      'wes'=>FILTER_SANITIZE_NUMBER_INT,
      'c19vac'=>FILTER_SANITIZE_NUMBER_INT
		);
		$options = array(
    	'org'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'branch'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'title'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'descrip'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'skills'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'supervisor'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'jobadd1'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'jobadd2'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'jobsub'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'jobcity'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'jem'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'jtel'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'ref'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'training'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'hstraining'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'reimbursement'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'personality'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'policeck'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'online'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'ong'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'st'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'sp'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'wes'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'c19vac'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	)
		);
    $reqOrg = filter_var($inputs[0]['volrContact']['org'],$filters['org'], $options['org']);
    $reqBranch = filter_var($inputs[0]['volrContact']['branch'],$filters['branch'], $options['branch']);
    $reqTitle = filter_var($inputs[0]['volrContact']['title'],$filters['title'], $options['title']);
    $reqDescrip = filter_var($inputs[0]['volrContact']['descrip'],$filters['descrip'], $options['descrip']);
    $reqSkills = filter_var($inputs[0]['volrContact']['skills'],$filters['skills'], $options['skills']);
    $reqSupervisor = filter_var($inputs[0]['volrContact']['supervisor'],$filters['supervisor'], $options['supervisor']);
    $reqJobadd1 = filter_var($inputs[0]['volrContact']['jobadd1'],$filters['jobadd1'], $options['jobadd1']);
    $reqJobadd2 = filter_var($inputs[0]['volrContact']['jobadd2'],$filters['jobadd2'], $options['jobadd2']);
    $reqJobsub = filter_var($inputs[0]['volrContact']['jobsub'],$filters['jobsub'], $options['jobsub']);
    $reqJobcity = filter_var($inputs[0]['volrContact']['jobcity'],$filters['jobcity'], $options['jobcity']);
    $reqJem = filter_var($inputs[0]['volrContact']['jem'],$filters['jem'], $options['jem']);
    $reqJtel = filter_var($inputs[0]['volrContact']['jtel'],$filters['jtel'], $options['jtel']);
    $reqRef = filter_var($inputs[0]['volrContact']['ref'],$filters['ref'], $options['ref']);
    $reqTraining = filter_var($inputs[0]['volrContact']['training'],$filters['training'], $options['training']);
    $reqHSTraining = filter_var($inputs[0]['volrContact']['hstraining'],$filters['hstraining'], $options['hstraining']);
    $rawReimbursement = filter_var($inputs[0]['volrContact']['reimbursement'],$filters['reimbursement'], $options['reimbursement']);
    if ($rawReimbursement==1) {
      $reqReimbursement='Yes';
    } else {
      $reqReimbursement='No';
    }
    $reqDateposted = date('Y-m-d');
    $reqDayshours = filter_var($inputs[0]['volrContact']['dayshours'],$filters['dayshours'], $options['dayshours']);
    $reqPersonality = filter_var($inputs[0]['volrContact']['personality'],$filters['personality'], $options['personality']);
    $rawPoliceck = filter_var($inputs[0]['volrContact']['policeck'],$filters['policeck'], $options['policeck']);
    if ($rawPoliceck==1) {
      $reqPoliceck='Yes';
    } else {
      $reqPoliceck='No';
    }
    $rawOnline = filter_var($inputs[0]['volrContact']['online'],$filters['online'], $options['online']);
    if ($rawOnline==1) {
      $reqOnline='Do NOT make this role available';
    } else {
      $reqOnline='Yes';
    }
    $rawOng = filter_var($inputs[0]['volrContact']['ong'],$filters['ong'], $options['ong']);
    if ($rawOng==1) {
      $reqOng='Yes';
    } else {
      $reqOng='No';
    }
    $rawSt = filter_var($inputs[0]['volrContact']['st'],$filters['st'], $options['st']);
    if ($rawSt==1) {
      $reqSt='Yes';
    } else {
      $reqSt='No';
    }
    $rawSp = filter_var($inputs[0]['volrContact']['sp'],$filters['sp'], $options['sp']);
    if ($rawSp==1) {
      $reqSp='Yes';
    } else {
      $reqSp='No';
    }
    $rawWes = filter_var($inputs[0]['volrContact']['wes'],$filters['wes'], $options['wes']);
    if ($rawWes==1) {
      $reqWes='Yes';
    } else {
      $reqWes='No';
    }
    $rawC19vac = filter_var($inputs[0]['volrContact']['c19vac'],$filters['c19vac'], $options['c19vac']);
    if ($rawC19vac==1) {
      $reqC19vac='Yes';
    } else {
      $reqC19vac='No';
    }
    $reqStdte = $inputs[0]['volrContact']['stdte'];
    if (empty($reqStdte)) {
      $reqStdte=NULL;
    }
    $startDate = new \DateTime($reqStdte);
    $displayStartDate = $startDate->format('d/m/Y');

    $reqEdte = $inputs[0]['volrContact']['edte'];
    if (empty($reqEdte)) {
      $reqEdte=NULL;
    }
    $endDate = new \DateTime($reqEdte);
    $displayEndDate = $endDate->format('d/m/Y');

    // Set email content
    $mailContent = "
    <p>You have received this mail from the Volunteer Wellington web site. The data below is that which has been entered by the Requesting Member.</p>
    <p>The applying organisation has also received this email, if they have entered a valid email address.</p>
    <p>Please do not try to reply-to this automatically generated email.</p>
    <p><b>REQUEST FOR VOLUNTEERS On-line form</b></p>
    <fieldset>
    <legend>ORGANISATION</legend>
    <ul>
    <li><label>Organisation:</label><b> &nbsp; $reqOrg</b></li>
    <li><label>Branch:</label><b> &nbsp; $reqBranch</b></li>
    <li><label>Address1:</label><b> &nbsp; $reqJobadd1</b></li>
    <li><label>Address2:</label><b> &nbsp; $reqJobadd2</b></li>
    <li><label>Suburb:</label><b> &nbsp; $reqJobsub</b></li>
    <li><label>City:</label><b> &nbsp; $reqJobcity</b></li>
    <li><label>Email:</label><b> &nbsp; $reqJem</b></li>
    <li><label>Phone:</label><b> &nbsp; $reqJtel</b></li>
    <li><label>Referrals Person:</label><b> &nbsp; $reqRef</b></li>
    </ul>
    </fieldset>
    <fieldset>
    <legend>THE JOB</legend>
    <ul>
    <li><label>Job title:</label><b> &nbsp; $reqTitle</b></li>
    <li><label>On-going:</label><b> &nbsp; $reqOng</b></li>
    <li><label>Is Short-term:</label><b> &nbsp; $reqSt</b></li>
    <li><label>Is a Special Project:</label><b> &nbsp; $reqSp</b></li>
    <li><label>Runs W/ends only:</label><b> &nbsp; $reqWes</b></li>
    <li><label>Expenses Reimbursed:</label><b> &nbsp; $reqReimbursement</b></li>
    <li><label>Requires a Police check:</label><b> &nbsp; $reqPoliceck</b></li>
    <li><label>Has specific Days and Hours:</label><b> &nbsp; $reqDayshours</b></li>
    <li><label>Has a start date:</label><b> &nbsp; $displayStartDate</b></li>
    <li><label>Has an end date:</label><b> &nbsp; $displayEndDate</b></li>
    </ul>
    </fieldset>
    <fieldset>
    <legend>JOB SPECIFICS</legend>
    <ul>
    <li class=\"spacer\"><label>Supervisor:</label><b> &nbsp; $reqSupervisor</b></li>
    <li class=\"spacer\"><label>Job Description:</label><b> &nbsp; $reqDescrip</b></li>
    <li class=\"spacer\"><label>Training offered:</label><b> &nbsp; $reqTraining</b></li>
    <li class=\"spacer\"><label>Health & Safety Training provided:</label><b> &nbsp; $reqHSTraining</b></li>
    <li class=\"spacer\"><label>Special Skills needed:</label><b> &nbsp; $reqSkills</b></li>
    <li class=\"spacer\"><label>Type of personality:</label><b> &nbsp; $reqPersonality</b></li>
    <li class=\"spacer\"><label>Fully Covid19 Vaccinated required:</label><b> &nbsp; $reqC19vac</b></li>
    <li class=\"spacer\"><label>On-Line:</label><b> &nbsp; $reqOnline</b></li>
    </ul>
    </fieldset>";

    $conn = \Database::connection('jobsearch');
    $conn -> beginTransaction();
    try {

      $conn->insert('jobswebtemp',
      array(
        'jid' => NULL,
        'org' => $reqOrg,
        'branch' => $reqBranch,
        'title' => $reqTitle,
        'descrip' => $reqDescrip,
        'skills' => $reqSkills,
        'supervisor' => $reqSupervisor,
        'jobadd1' => $reqJobadd1,
        'jobadd2' => $reqJobadd2,
        'jobsub' => $reqJobsub,
        'jobcity' => $reqJobcity,
        'jem' => $reqJem,
        'jtel' => $reqJtel,
        'ref' => $reqRef,
        'training' => $reqTraining,
        'hstraining' => $reqHSTraining,
        'reimbursement' => $rawReimbursement,
        'dateposted' => $reqDateposted,
        'dayshours' => $reqDayshours,
        'personality' => $reqPersonality,
        'policeck' => $rawPoliceck,
        'online' => $rawOnline,
        'ong' => $rawOng,
        'st' => $rawSt,
        'sp' => $rawSp,
        'wes' => $rawWes,
        'stdte' => $reqStdte,
        'edte' => $reqEdte,
        'status' => 1,
        'c19vac' => $rawC19vac
    ));
    $conn->commit();
    // Send Volunteer Request to VW office
    $mailService = Core::make('mail');
    $mailService->setTesting(false); // or true to throw an exception on error.
    $mailService->load('mail_template');

    // Set email parameters
    $mailService->to('member@volunteerwellington.nz, office@volunteerwellington.nz');
    $mailService->from('Registrationonline@volunteerwellington.nz');
    $mailService->replyto('office@volunteerwellington.nz', 'Online Member Registration');
    $mailService->setSubject('VW OnLine Request for Volunteers form');
    $mailService->setBodyHTML($mailContent);

    // Send email
    $mailService->sendMail();

    // Send email to Member Request Organisation
    $mailService = Core::make('mail');
    $mailService->setTesting(false); // or true to throw an exception on error.
    $mailService->load('mail_template');

    // Set email parameters
    $mailService->to($reqJem);
    $mailService->from('Registrationonline@volunteerwellington.nz');
    $mailService->replyto('office@volunteerwellington.nz', 'Online Member Registration');
    $mailService->setSubject('VW OnLine Request for Volunteers form');
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
