<?php
namespace Application\Controller\SinglePage;
// use Concrete\Core\Page\Controller\PageController;
use Core;
use PageController;
use AssetList;
use Asset;
use Mail;

class MemberRegister extends PageController
{
  public function view()
  {
    //query to get current membership fees
    $conn = \Database::connection('jobsearch');
		$sql = "SELECT  `id`, `amount`, `detail`
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

  public function regMember() {
    $regSubmission = $_POST['regData'];
    $inputs = json_decode(json_encode(json_decode($regSubmission)), True);
    // echo print_r($inputs);
    $filters = array(
    	'name'=>FILTER_SANITIZE_STRING,
      'branch'=>FILTER_SANITIZE_STRING,
      'pobox'=>FILTER_SANITIZE_STRING,
      'address'=>FILTER_SANITIZE_STRING,
      'address2'=>FILTER_SANITIZE_STRING,
      'suburb'=>FILTER_SANITIZE_STRING,
      'city'=>FILTER_SANITIZE_STRING,
      'pcode'=>FILTER_SANITIZE_STRING,
			'phone'=>FILTER_SANITIZE_STRING,
      'mobile'=>FILTER_SANITIZE_STRING,
			'email'=>FILTER_VALIDATE_EMAIL,
      'affiliated'=>FILTER_SANITIZE_STRING,
      'multibranch'=>FILTER_SANITIZE_STRING,
      'branches'=>FILTER_SANITIZE_STRING,
      'grossannual'=>FILTER_SANITIZE_STRING,
      'memfee'=>FILTER_SANITIZE_STRING,
      'president'=>FILTER_SANITIZE_STRING,
      'ceo'=>FILTER_SANITIZE_STRING,
      'volcoord'=>FILTER_SANITIZE_STRING,
      'hours'=>FILTER_SANITIZE_STRING,
      'vmpaid'=>FILTER_SANITIZE_NUMBER_INT,
      'vmwork'=>FILTER_SANITIZE_NUMBER_INT,
      'nopaid'=>FILTER_SANITIZE_NUMBER_INT,
      'novol'=>FILTER_SANITIZE_NUMBER_INT,
      'disabled'=>FILTER_SANITIZE_NUMBER_INT,
      'esl'=>FILTER_SANITIZE_NUMBER_INT,
      'volbudget'=>FILTER_SANITIZE_NUMBER_INT,
      'vjd'=>FILTER_SANITIZE_NUMBER_INT,
      'train'=>FILTER_SANITIZE_NUMBER_INT,
      'volint'=>FILTER_SANITIZE_NUMBER_INT,
      'veval'=>FILTER_SANITIZE_NUMBER_INT,
      'reimb'=>FILTER_SANITIZE_NUMBER_INT,
      'vref'=>FILTER_SANITIZE_NUMBER_INT,
      'hspolicy'=>FILTER_SANITIZE_NUMBER_INT,
      'hstraining'=>FILTER_SANITIZE_NUMBER_INT,
      'hsrisks'=>FILTER_SANITIZE_NUMBER_INT,
      'mission'=>FILTER_SANITIZE_STRING,
      'services'=>FILTER_SANITIZE_STRING
		);
		$options = array(
    	'name'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'branch'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'pobox'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'address'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'address2'=>array(
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
			'phone'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'mobile'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'email'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'affiliated'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'multibranch'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'branches'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'grossannual'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'memfee'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'president'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'ceo'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'volcoord'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'hours'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'vmpaid'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'vmwork'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'nopaid'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'novol'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'disabled'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'esl'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'volbudget'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'vjd'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'train'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'volint'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'veval'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'reimb'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'vref'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'hspolicy'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'hstraining'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'hsrisks'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'mission'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
      'services'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	)
		);
    $orgName = filter_var($inputs[0]['mbrContact']['name'],$filters['name'], $options['name']);
    $orgBranch = filter_var($inputs[0]['mbrContact']['branch'],$filters['branch'], $options['branch']);
    $orgPOBox = filter_var($inputs[0]['mbrContact']['pobox'],$filters['pobox'], $options['pobox']);
    $orgAddress = filter_var($inputs[0]['mbrContact']['address'],$filters['address'], $options['address']);
    $orgAdress2 = filter_var($inputs[0]['mbrContact']['address2'],$filters['address2'], $options['address2']);
    $orgSuburb = filter_var($inputs[0]['mbrContact']['suburb'],$filters['suburb'], $options['suburb']);
    $orgCity = filter_var($inputs[0]['mbrContact']['city'],$filters['city'], $options['city']);
    $orgPcode = filter_var($inputs[0]['mbrContact']['pcode'],$filters['pcode'], $options['pcode']);
    $orgJoindate = date('Y-m-d');
    $orgPhone = filter_var($inputs[0]['mbrContact']['phone'],$filters['phone'], $options['phone']);
    $orgMobile = filter_var($inputs[0]['mbrContact']['mobile'],$filters['mobile'], $options['mobile']);
    $orgEmail = filter_var($inputs[0]['mbrContact']['email'],$filters['email'], $options['email']);
    $orgAffiliated = filter_var($inputs[0]['mbrContact']['affiliated'],$filters['affiliated'], $options['affiliated']);
    $orgmultibranch = filter_var($inputs[0]['mbrContact']['multibranch'],$filters['multibranch'], $options['multibranch']);
    $orgBranches = filter_var($inputs[0]['mbrContact']['branches'],$filters['branches'], $options['branches']);
    $orgGrossAnnual = filter_var($inputs[0]['mbrContact']['grossannual'],$filters['grossannual'], $options['grossannual']);
    $orgMemFee = filter_var($inputs[0]['mbrContact']['memfee'],$filters['memfee'], $options['memfee']);
    $orgPresident = filter_var($inputs[0]['mbrContact']['president'],$filters['president'], $options['president']);
    $orgCeo = filter_var($inputs[0]['mbrContact']['ceo'],$filters['ceo'], $options['ceo']);
    $orgVolcoord = filter_var($inputs[0]['mbrContact']['volcoord'],$filters['volcoord'], $options['volcoord']);
    $orgHours = filter_var($inputs[0]['mbrContact']['hours'],$filters['hours'], $options['hours']);
    $rawVmpaid = filter_var($inputs[0]['mbrContact']['vmpaid'],$filters['vmpaid'], $options['vmpaid']);
    if ($rawVmpaid==1) {
			$orgVmpaid='Yes';
		} else {
			$orgVmpaid='No';
		}
    $orgVmwork = filter_var($inputs[0]['mbrContact']['vmwork'],$filters['vmwork'], $options['vmwork']);
    $orgNopaid = filter_var($inputs[0]['mbrContact']['nopaid'],$filters['nopaid'], $options['nopaid']);
    if (empty($orgNopaid)) {
      $orgNopaid = 0;
    }
    else {
      $orgNopaid=intval($orgNopaid);
    }
    $orgNovol = filter_var($inputs[0]['mbrContact']['novol'],$filters['novol'], $options['novol']);
    if (empty($orgNovol)) {
      $orgNovol = 0;
    } else {
      $orgNovol=intval($orgNovol);
    }
    $rawDisabled = filter_var($inputs[0]['mbrContact']['disabled'],$filters['disabled'], $options['disabled']);
    if ($rawDisabled==1) {
			$orgDisabled='Yes';
		} else {
			$orgDisabled='No';
		}
    $rawEsl = filter_var($inputs[0]['mbrContact']['esl'],$filters['esl'], $options['esl']);
    if ($rawEsl==1) {
			$orgEsl='Yes';
		} else {
			$orgEsl='No';
		}
    $rawVolbudget = filter_var($inputs[0]['mbrContact']['volbudget'],$filters['volbudget'], $options['volbudget']);
    if ($rawVolbudget==1) {
			$orgVolbudget='Yes';
		} else {
			$orgVolbudget='No';
		}
    $rawVjd = filter_var($inputs[0]['mbrContact']['vjd'],$filters['vjd'], $options['vjd']);
    if ($rawVjd==1) {
      $orgVjd='Yes';
    } else {
      $orgVjd='No';
    }
    $rawTrain = filter_var($inputs[0]['mbrContact']['train'],$filters['train'], $options['train']);
    if ($rawTrain==1) {
      $orgTrain='Yes';
    } else {
      $orgTrain='No';
    }
    $rawVolint = filter_var($inputs[0]['mbrContact']['volint'],$filters['volint'], $options['volint']);
    if ($rawVolint==1) {
      $orgVolint='Yes';
    } else {
      $orgVolint='No';
    }
    $rawVeval = filter_var($inputs[0]['mbrContact']['veval'],$filters['veval'], $options['veval']);
    if ($rawVeval==1) {
      $orgVeval='Yes';
    } else {
      $orgVeval='No';
    }
    $rawReimb = filter_var($inputs[0]['mbrContact']['reimb'],$filters['reimb'], $options['reimb']);
    if ($rawReimb==1) {
      $orgReimb='Yes';
    } else {
      $orgReimb='No';
    }
    $rawVref = filter_var($inputs[0]['mbrContact']['vref'],$filters['vref'], $options['vref']);
    if ($rawVref==1) {
      $orgVref='Yes';
    } else {
      $orgVref='No';
    }
    $rawHSPolicy = filter_var($inputs[0]['mbrContact']['hspolicy'],$filters['hspolicy'], $options['hspolicy']);
    if ($rawHSPolicy==1) {
      $orgHSPolicy='Yes';
    } else {
      $orgHSPolicy='No';
    }
    $rawHSTraining = filter_var($inputs[0]['mbrContact']['hstraining'],$filters['hstraining'], $options['hstraining']);
    if ($rawHSTraining==1) {
      $orgHSTraining='Yes';
    } else {
      $orgHSTraining='No';
    }
    $rawHSRisks = filter_var($inputs[0]['mbrContact']['hsrisks'],$filters['hsrisks'], $options['hsrisks']);
    if ($rawHSRisks==1) {
      $orgHSRisks='Yes';
    } else {
      $orgHSRisks='No';
    }
    $orgMission = filter_var($inputs[0]['mbrContact']['mission'],$filters['mission'], $options['mission']);
    if (empty($orgMission)) {
      $orgMission='Nil';
    }
    $orgServices = filter_var($inputs[0]['mbrContact']['services'],$filters['services'], $options['services']);
    if (empty($orgServices)) {
      $orgServices='Nil';
    }
    // Set email content
    $mailContent = "
    <p>Kia ora!</p>
    <p>Thanks for signing up to be a member of Volunteer Wellington! You will be hearing shortly from either Tracy, Kim or Leta, who will walk you through our services and the benefits of membership with us.</p>
    <p>Below is the data you have entered for our records. Please double check that the information is accurate, and let us know as soon as possible if anything needs to be changed.</p>
    <p>We look forward to connecting with you!</p>
    </br>
    <fieldset>
    <legend>ORGANISATION REGISTRATION</legend>
    <ul>
    <li><label>Organisation: </label><b> &nbsp;$orgName</b></li>
    <li><label>Branch: </label><b> &nbsp;$orgBranch</b></li>
    <li><label>PO Box: </label><b> &nbsp;$orgPOBox</b></li>
    <li><label>Address1: </label><b> &nbsp;$orgAddress</b></li>
    <li><label>Address2: </label><b> &nbsp;$orgAdress2</b></li>
    <li><label>Suburb: </label><b> &nbsp;$orgSuburb</b></li>
    <li><label>City: </label><b> &nbsp;$orgCity</b></li>
    <li><label>Postcode: </label><b> &nbsp;$orgPcode</b></li>
    <li><label>Email: </label><b> &nbsp;$orgEmail</b></li>
    <li><label>Day Phone: </label><b> &nbsp;$orgPhone</b></li>
    <li><label>Out of Hours: </label><b> &nbsp;$orgMobile</b></li>
    </ul>
    </fieldset>
    <fieldset>
    <legend>JOINING FEE INFORMATION</legend>
    <ul>
    <li><label>Affiliated Membership Request: </label><b> &nbsp;$orgAffiliated</b></li>
    <li><label>Multi-branch Organisation: </label><b> &nbsp;$orgmultibranch</b></li>
    <li><label>Number of Branches: </label><b> &nbsp;$orgBranches</b></li>
    <li><label>Annual Gross Income: </label><b> &nbsp;$orgGrossAnnual</b></li>
    <li><label>Membership Fee: </label><b> &nbsp;$orgMemFee</b></li>
    <li><label>Board President/Chairperson: </label><b> &nbsp;$orgPresident</b></li>
    <li><label>Executive Director/Manager: </label><b> &nbsp;$orgCeo</b></li>
    <li><label>Manager of Volunteers: </label><b> &nbsp;$orgVolcoord</b></li>
    <li><label>Our Volunteer Manager is paid: </label><b> &nbsp;$orgVmpaid</b></li>
    <li><label>Work hours of Volunteer Manager: </label><b> &nbsp;$orgVmwork</b></li>
    <li><label>Number of paid staff: </label><b> &nbsp;$orgNopaid</b></li>
    <li><label>Number of volunteers: </label><b> &nbsp;$orgNovol</b></li>
    <li><label>Office days and hours: </label><b> &nbsp;$orgHours</b></li>
    </ul></fieldset>
    <fieldset>
    <legend>MISSION AND SERVICES</legend>
    <ul>
    <li><label>Mission: </label><b> &nbsp;$orgMission</b></li><!---->
    <li><label>Services: </label><b> &nbsp;$orgServices</b></li>
    </ul>
    </fieldset>
    <fieldset>
    <legend>VOLUNTEER PROGRAMME</legend>
    <ul>
    <li><label>We provide disabled access: </label><b> &nbsp;$orgDisabled</b></li>
    <li><label>We accept volunteers with English as a second language: </label><b> &nbsp;$orgEsl</b></li>
    <li><label>We have funds budgeted for a volunteer programme: </label><b> &nbsp;$orgVolbudget</b></li>
    <li><label><font color=\"#ffffff\">,</font></label><b> &nbsp;As part of of our volunteer programme we:</b></li>
    <li><label>Have written job descriptions: </label><b> &nbsp;$orgVjd</b></li>
    <li><label>Offer orientation and training to volunteers: </label><b> &nbsp;$orgTrain</b></li>
    <li><label>Conduct interviews: </label><b> &nbsp;$orgVolint</b></li>
    <li><label>Evaluate volunteers' performance: </label><b> &nbsp;$orgVeval</b></li>
    <li><label>Reimburse out-of-pocket expenses: </label><b> &nbsp;$orgReimb</b></li>
    <li><label>Provide a reference after a period of service: </label><b> &nbsp;$orgVref</b></li>
    </ul>
    </fieldset>
    <fieldset>
    <legend>VOLUNTEER HEALTH & SAFETY PROGRAMME</legend>
    <ul>
    <li><label>We have an Health & Safety Policy: </label><b> &nbsp;$orgHSPolicy</b></li>
    <li><label>We provide Health & Safety volunteer induction training: </label><b> &nbsp;$orgHSTraining</b></li>
    <li><label>We have identified the Health & Safety risks associated with our operation: </label><b> &nbsp;$orgHSRisks</b></li>
    </ul>
    </fieldset>";


    $conn = \Database::connection('jobsearch');
    $conn -> beginTransaction();
    try {
      $conn->insert('agswebtemp',
      array(
        'id' => NULL,
        'name' => $orgName,
        'branch' => $orgBranch,
        'pobox' => $orgPOBox,
        'address' => $orgAddress,
        'address2' => $orgAdress2,
        'suburb' => $orgSuburb,
        'city' => $orgCity,
        'pcode' => $orgPcode,
        'joindate' => $orgJoindate,
        'phone' => $orgPhone,
        'mobile' => $orgMobile,
        'fax' => '',
        'email' => $orgEmail,
        'president' => $orgPresident,
        'ceo' => $orgCeo,
        'volcoord' => $orgVolcoord,
        'hours' => $orgHours,
        'comments' => '',
        'moddate' => date('Y-m-d'),
        'offloc' => '',
        'eitcref' => '',
        'status' => 1,
        'inactdate' => date('Y-m-d'),
        'newsby' => '',
        'online' => 0,
        'vmpaid' => $rawVmpaid,
        'vmwork' => $orgVmwork,
        'nopaid' => $orgNopaid,
        'novol' => $orgNovol,
        'disabled' => $rawDisabled,
        'esl' => $rawEsl,
        'volbudget' => $rawVolbudget,
        'vjd' => $rawVjd,
        'train' => $rawTrain,
        'volint' => $rawVolint,
        'veval' => $rawVeval,
        'reimb' => $rawReimb,
        'vref' => $rawVref,
        'hspolicy' => $rawHSPolicy,
        'hstraining' => $rawHSTraining,
        'hsrisks' => $rawHSRisks,
        'memfee' => $orgMemFee
      ));

      // Only insert into mswebtemp if agency doesn't exist
      $sql = "INSERT IGNORE INTO mswebtemp(agency, mission, services) VALUES (?, ?, ?)";
  		$stmt = $conn->prepare($sql);
  		$stmt->bindValue(1, $orgName);
      $stmt->bindValue(2, $orgMission);
      $stmt->bindValue(3, $orgServices);
  		$stmt->execute();

      // Send Membership Request to VW office
  		$mailService = Core::make('mail');
  		$mailService->setTesting(false); // or true to throw an exception on error.
  		$mailService->load('mail_template');

      // Set email parameters
      $mailService->to('members@volunteerwellington.nz');
      $mailService->cc('julie@volunteerwellington.nz');
      $mailService->from('Registrationonline@volunteerwellington.nz');
  		$mailService->replyto('office@volunteerwellington.nz', 'Online Member Registration');
  		$mailService->setSubject('OnLine Membership registration form');
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
  		$mailService->replyto('office@volunteerwellington.nz', 'Online Member Registration');
  		$mailService->setSubject('OnLine Membership registration form');
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
