<?php
namespace Application\Controller\SinglePage;
//use Concrete\Core\Page\Controller\PageController;
use Core;
use PageController;
use AssetList;
use Asset;
use Mail;

class JobSearch extends PageController
{
	public function getAgeList() {
		$conn = \Database::connection('jobsearch');
		$sql = "SELECT  ageband
		FROM agelist
		WHERE status = 1";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getEthnicityList() {
		$conn = \Database::connection('jobsearch');
		$sql = "SELECT  *
		FROM ethnicitylist
		WHERE status = 1
		ORDER BY elist";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getWorkStatus() {
		$conn = \Database::connection('jobsearch');
		$sql = "SELECT  *
		FROM worklist
		WHERE styn = 1";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getHeardList() {
		$conn = \Database::connection('jobsearch');
		$sql = "SELECT  *
		FROM heardlist
		WHERE status = 1";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getCity() {
		$conn = \Database::connection('jobsearch');
		$sql = "SELECT  *
		FROM citylist
		WHERE status = 1";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getVolReason() {
		$conn = \Database::connection('jobsearch');
		$sql = "SELECT  *
		FROM volreason
		WHERE status = 1";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getDetail() {
		$jobID = $_POST['key'];
    $conn = \Database::connection('jobsearch');
    $sql = "SELECT  ID, title, descrip, skills, training, reimbursement, personality, dayshours, policeck, c19vac, eveonly
    FROM jobs
    WHERE id = ?";

		$stmt = $conn->prepare($sql);

		$stmt->bindValue(1, $jobID);

		$stmt->execute();

		$details = $stmt->fetch();

		echo json_encode($details, JSON_FORCE_OBJECT);
		exit;
	}


	public function getCategory(){
		$dbc = \Database::connection('jobsearch');
		$cats = $dbc->fetchAll("SELECT `posid`,`scat` FROM `seekcats` ORDER BY `scat`  ");
		return $cats;
	}

	public function getKeywords(){
		$dbc = \Database::connection('jobsearch');
		$cats = $dbc->fetchAll("SELECT * FROM `keywords` WHERE `status` = 1 order by 'keyword'");
		return $cats;
	}

	public function test_input($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
	}

	public function getIsNotOnline () {
		$jobID = $_POST['key'];
		//$jobID = 3502;
		$dbc = \Database::connection('jobsearch');
		$sql ="SELECT case when
			(a.online = 1 or j.online = 1)
			then 'true'
			else 'false'
			end as success
			FROM agencies a
			LEFT JOIN jobs j
			ON a.id = j.agencyid
			WHERE j.id = ?";
			$stmt = $dbc->prepare($sql);
			$stmt->bindValue(1, $jobID);
			$stmt->execute();
			$details = $stmt->fetch();
			echo json_encode($details, JSON_FORCE_OBJECT);
			exit;
	}

	public function getLocation(){
		$dbc = \Database::connection('jobsearch');
		$loc = $dbc->fetchAll("SELECT `office`,`tex` FROM `jobloc` ORDER BY `tex`");
		return $loc;
	}

	public function role($roleID = null)
	{
		$filters = array(
			'roleId'=>FILTER_VALIDATE_INT,
		);
		$options = array(
		);
  		//Search the database
		$roleId = filter_var($roleID, $filters['roleId'], $options['roleId']);
		if (!empty($roleId)) {
			$this->searchJobDataImpl("", "", "", $roleId, 0);
			$this->set('displayRoleDetail', $roleId);
		}
	}

	public function searchJobData() {
		$filters = array(
			'keyword'=>FILTER_SANITIZE_STRING,
			'roleId'=>FILTER_VALIDATE_INT,
			'page'=>FILTER_VALIDATE_INT,
		);
		$options = array(
			'keyword'=>array(
				'flags'=>FILTER_NULL_ON_FAILURE
			),
			'page'=>array(
				'flags'=>FILTER_NULL_ON_FAILURE
			)
		);
  		//Search the database
    	$category = $this->get('sCategory');
    	$location = $this->test_input($this->get('sLocation'));
    	$keyword = filter_var($this->get("sWord"),$filters['keyword'], $options['keyword']);
		$roleId = filter_var($this->get("sWord"),$filters['roleId'], $options['roleId']);
		$page = filter_var($this->get("sPage"),$filters['page'], $options['page']);
		if (empty($page))
			$page = 0;

		$this->searchJobDataImpl($category, $location, $keyword, $roleId, $page);

   		$this->set('resultCategory', $this->get('sCategory'));
   		$this->set('resultLocation', $this->get('sLocation'));
   		$this->set('resultKeyword', $this->get("sWord"));
	}

	private function searchJobDataImpl($category, $location, $keyword, $roleId, $page) {
		$pageSize = 20;

  		$dbc = \Database::connection('jobsearch');
   		$sql =  "SELECT  `title`, `keyword`, `jobsub`, `descrip`, `ID`, 'policeck', 'eveonly', 'reimbursement' FROM `jobs`";
		$where = "WHERE `status` = 1";
		if (!empty($category)) {
    		$where .= " AND `keyword` = ?";
		}
		if (!empty($location)) {
    		$where .= " AND `location` = ?";
		}
		if (!empty($keyword)) {
    		$where .= " AND (";
			$where .= " MATCH(`descrip`, `title`) AGAINST(? IN BOOLEAN MODE)";
			if (!empty($roleId)) {
				$where .= " OR `ID` = ?";
			}
    		$where .= " )";
		} else if (!empty($roleId)) {
    		$where .= " AND `ID` = ?";
		}
		$order .= " ORDER BY `dateposted` DESC, `ID` ASC LIMIT " . ($pageSize + 1) . " OFFSET " . ($page * $pageSize);
		$sql = $sql . $where . $order;
		$stmt = $dbc->prepare($sql);
		$bindIdx = 1;
		if (!empty($category)) {
    		$stmt->bindValue($bindIdx, $category);
			$bindIdx++;
		}
		if (!empty($location)) {
    		$stmt->bindValue($bindIdx, $location);
			$bindIdx++;
		}
		if (!empty($keyword)) {
    		$stmt->bindValue($bindIdx, $keyword . "*");
			$bindIdx++;
			if (!empty($roleId)) {
				$stmt->bindValue($bindIdx, $roleId);
				$bindIdx++;
			}
		} else if (!empty($roleId)) {
			$stmt->bindValue($bindIdx, $roleId);
			$bindIdx++;
		}
		$stmt->execute();
		$results = $stmt->fetchAll();
   		$this->set('resultPosted', $results);
   		$this->set('resultPage', $page);
   		$this->set('resultPageSize', $pageSize);
	}

	public function jobRegister() {
		$jobSubmission = $_POST['jobData'];
		$filters = array(
			'fname'=>FILTER_SANITIZE_STRING,
			'lname'=>FILTER_SANITIZE_STRING,
			'add1'=>FILTER_SANITIZE_STRING,
			'add2'=>FILTER_SANITIZE_STRING,
			'suburb'=>FILTER_SANITIZE_STRING,
			'city'=>FILTER_SANITIZE_STRING,
			'pcode'=>FILTER_SANITIZE_STRING,
			'phone'=>FILTER_SANITIZE_NUMBER_INT,
			'evetel'=>FILTER_SANITIZE_NUMBER_INT,
			'mobile'=>FILTER_SANITIZE_NUMBER_INT,
			'email'=>FILTER_VALIDATE_EMAIL,
			'gender'=>FILTER_SANITIZE_STRING,
			'ageband'=>FILTER_SANITIZE_STRING,
			'ethnicity'=>FILTER_SANITIZE_STRING,
			'migrant'=>FILTER_SANITIZE_STRING,
			'refugee'=>FILTER_SANITIZE_STRING,
			'heard'=>FILTER_SANITIZE_STRING,
			'labour'=>FILTER_SANITIZE_STRING,
			'reason'=>FILTER_SANITIZE_STRING,
			'office'=>FILTER_SANITIZE_STRING,
			'emvol'=>FILTER_SANITIZE_NUMBER_INT,
			'oneoff'=>FILTER_SANITIZE_NUMBER_INT,
			'posimpact'=>FILTER_SANITIZE_NUMBER_INT,
		);
		$options = array(
    	'fname'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'lname'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'add1'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'add2'=>array(
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
			'tel'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'evetel'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'mobile'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'email'=>array(
    	),
			'gender'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'ageband'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'ethnicity'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'migrant'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'refugee'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'heard'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'labour'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'reason'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'office'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'emvol'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'oneoff'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
			'posimpact'=>array(
        'flags'=>FILTER_NULL_ON_FAILURE
    	),
		);


		//$inputs = json_decode(json_encode(json_decode($jobSubmission)), True);
		$inputs = json_decode($jobSubmission, True);

		// set variables for email
		$volFirstName=filter_var($inputs[0]['volContact']['fname'], $filters['fname'], $options['fname']);
		$volName=filter_var($inputs[0]['volContact']['fname'], $filters['fname'], $options['fname']). ' ' .filter_var($inputs[0]['volContact']['lname'], $filters['lname'], $options['lname']);
		$volAdd1=filter_var($inputs[0]['volContact']['add1'], $filters['add1'], $options['add1']);
		$volAdd2=filter_var($inputs[0]['volContact']['add2'], $filters['add2'], $options['add2']);
		$volSuburb=filter_var($inputs[0]['volContact']['suburb'], $filters['suburb'], $options['suburb']);
		$volCity=filter_var($inputs[0]['volContact']['city'], $filters['city'], $options['city']);
		$volPcode=filter_var($inputs[0]['volContact']['pcode'], $filters['pcode'], $options['pcode']);
		$volPhone=filter_var($inputs[0]['volContact']['phone'], $filters['phone'], $options['phone']);
		$volEvetel=filter_var($inputs[0]['volContact']['evetel'], $filters['evetel'], $options['evetel']);
		$volMobile=filter_var($inputs[0]['volContact']['mobile'], $filters['mobile'], $options['mobile']);
		$volEmail=filter_var($inputs[0]['volContact']['email'], $filters['email'], $options['email']);
		$volOffice=filter_var($inputs[0]['volContact']['office'], $filters['office'], $options['office']);
		$volReason=filter_var($inputs[0]['volContact']['reason'], $filters['reason'], $options['reason']);
		$rawEmergency=filter_var($inputs[0]['volContact']['emvol'], $filters['emvol'], $options['emvol']);
		if ($rawEmergency==1) {
			$volEmergency='Yes';
		} else {
			$volEmergency='No';
		}
		$rawOneOffVol=filter_var($inputs[0]['volContact']['oneoff'], $filters['oneoff'], $options['oneoff']);
		if ($rawOneOffVol==1) {
			$volOneOff='Yes';
		} else {
			$volOneOff='No';
		}
		$rawPositiveImpactVol=filter_var($inputs[0]['volContact']['posimpact'], $filters['posimpact'], $options['posimpact']);
		if ($rawPositiveImpactVol==1) {
			$volPositiveImpact='Yes';
		} else {
			$volPositiveImpact='No';
		}
		$volGender=filter_var($inputs[0]['volContact']['gender'], $filters['gender'], $options['gender']);
		$volAgeband=filter_var($inputs[0]['volContact']['ageband'], $filters['ageband'], $options['ageband']);
		$volLabour=filter_var($inputs[0]['volContact']['labour'], $filters['labour'], $options['labour']);
		$volHeard=filter_var($inputs[0]['volContact']['heard'], $filters['heard'], $options['heard']);
		$volEthnicity=filter_var($inputs[0]['volContact']['ethnicity'], $filters['ethnicity'], $options['ethnicity']);
		$volMigrant=filter_var($inputs[0]['volContact']['migrant'], $filters['migrant'], $options['migrant']);
		$volRefugee=filter_var($inputs[0]['volContact']['refugee'], $filters['refugee'], $options['refugee']);
		$volReDate = date('Y-m-d');
    	$volCreateDate = new \DateTime($volReDate);
		$displayCreateDate = $volCreateDate->format('d/m/Y');

		// Add the Volunteer Health & safety brochure this is to go out as an attachment to the volunteers email
    $attachment = \Concrete\Core\File\File::getByID(97);


		$conn = \Database::connection('jobsearch');
		$conn -> beginTransaction();
		try {
			/* Find out the next auto-increment ID for the webregister table */

			$sql = "SELECT ID
			FROM webregister
			ORDER BY ID
			DESC LIMIT 1";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$last_id = $stmt->fetch();
			$reg_id = $last_id['ID'] + 1;


			// Set email to office body content
			$mailOfficeContent = "
			<p>You have received this mail from a volunteer registration on the VW web site. The data below is that which has been entered by the potential volunteer. The requirements on the data entry have been set at Volunteer Name and at least one contact number. The contact details must be correctly entered. They cannot send the form until they have fulfilled these requirements.</p>
			<p>Please do not try to reply-to this automatically generated email.</p>
			<p>The database entry date: $displayCreateDate</p>
			<fieldset>
			<legend>THE VOLUNTEER</legend>
			<ul>
			<li><label>ID</label><b> &nbsp; $reg_id</b></li>
			<li><label>Name</label><b> &nbsp; $volName</b></li>
			<li><label>Address Line 1</label><b> &nbsp; $volAdd1</b></li>
			<li><label>Address Line 2</label><b> &nbsp; $volAdd2</b></li>
			<li><label>Suburb</label><b> &nbsp; $volSuburb</b></li>
			<li><label>City</label><b> &nbsp; $volCity</b></li>
			<li><label>Post Code</label><b> &nbsp; $volPcode</b></li>
			<li><label><b>Contact details</b></label><b> (at least one must have been entered)</b></li>
			<li><label>Phone</label><b> &nbsp; $volPhone</b></li>
			<li><label>Email</label><b> &nbsp; <a href=\"mailto:$volEmail\">$volEmail</a></b></li>
			<li><label>Nearest VW Office</label><b> &nbsp; $volOffice</b></li>
			<li><label>Reason to Volunteer</label><b> &nbsp; $volReason</b></li>
			<li><label>One Off volunteer list</label><b> &nbsp; $volOneOff</b></li>
			<li><label>Positive Imapct list</label><b> &nbsp; $volPositiveImpact</b></li>
			<li><label>Emergency volunteer list</label><b> &nbsp; $volEmergency</b></li>
			<li><label><font color=\"#ffffff\">stats</font></label><b>Stats details</b></li>
			<li><label>Gender</label><b> &nbsp; $volGender</b></li>
			<li><label>Ageband</label><b> &nbsp; $volAgeband</b></li>
			<li><label>Labour Status</label><b> &nbsp; $volLabour</b></li>
			<li><label>How heard</label><b> &nbsp; $volHeard</b></li>
			<li><label>Ethnicity</label><b> &nbsp; $volEthnicity</b></li>
			<li><label>Migrant</label><b> &nbsp; $volMigrant</b></li>
			<li><label>Refugee</label><b> &nbsp; $volRefugee</b></li>
			</ul>
			</fieldset>
			<p>This is the roles that the volunteer has registered for:</p>
			<fieldset>
			<legend>THE ROLES</legend>
			";

			// Set email to volunteer body content
			$mailVolContent = "
			<p>Kia Ora &nbsp; $volFirstName</p>
			<br/>
			<p>Thank you for your interest with Volunteer Wellington! You applied for the following volunteering role(s):</p>
			<br/>
			";

			$webregister = array(
				'ID' => NULL,
				'lastname'=>filter_var($inputs[0]['volContact']['lname'], $filters['lname'], $options['lname']),
				'firstname'=>filter_var($inputs[0]['volContact']['fname'], $filters['fname'], $options['fname']),
				'suburb'=>$volSuburb,
				'city'=>$volCity,
				'phone'=>$volPhone,
				'evetel'=>$volEvetel,
				'mobile'=>$volMobile,
				'email'=>$volEmail,
				'createdate' => $volReDate,
				'posvol1'=>$rawPositiveImpactVol,
				'oovol1'=>$rawOneOffVol,
				'emvol'=>$rawEmergency,
				'c19'=>0,
				'gender'=>$volGender,
				'ageband'=>$volAgeband,
				'ethnicity'=>$volEthnicity,
				'heard'=>$volHeard,
				'labour'=>$volLabour,
				'off'=>$volOffice,
				'migrant'=>$volMigrant,
				'refugee'=>$volRefugee,
				'wi' => 0,
				'status' => 1,
				'reason'=>$volReason,
				'add1' =>$volAdd1,
				'add2' =>$volAdd2,
				'pcode' =>$volPcode
			);
			$conn->insert('webregister', $webregister);

			$webrefs = array();
			for ($x = 1; $x <= 10; $x++) {
				$job_id = $inputs[$x]['item']['id'];
				if (isset($job_id)) {
					$sql = "SELECT `agencyID`, `title`, `descrip`, `location`, `jobemail`, `dayshours`, `jobsub`, `ID`
					FROM `jobs`
					WHERE `ID` = ?";
					$stmt = $conn->prepare($sql);
					$stmt->bindValue(1, intval($job_id));
					$stmt->execute();
					$job_data = $stmt->fetch();
					$agency_id = intval($job_data['agencyID']);
					$ref_location = $job_data['location'];
					$ref_title = $job_data['title'];
					$ref_descrip= $job_data['descrip'];

					if (isset($agency_id)) {
						$sql = "SELECT `name`, `email`, `phone`
						FROM `agencies`
						WHERE `ID` = ?";
						$stmt = $conn->prepare($sql);
						$stmt->bindValue(1, intval($agency_id));
						$stmt->execute();
						$agency_data = $stmt->fetch();
						$agency_name = $agency_data['name'];
						$agency_phone = $agency_data['phone'];

						// Get valid email address for sending agency email
						if(!empty($job_data['jobemail'])){
							$job_email=$job_data['jobemail'];
							$agency_email=$job_email;
						}
						else{
							if(!empty($agency_data['email'])){
								$agency_email=$agency_data['email'];
							}
						}
					}
					// Set email to agency body content
					$mailAgencyContent = "
					<p>Kia Ora,</p>
					<br/>
					<p>Great news! Someone has offered to volunteer for you.</p>
					<p>This is an automated email from Volunteer Wellington. A volunteer has registered for a role of yours that is listed on our website.</p>
					<p>To ensure that the correct email has reached you, please check that you are the organisation named below. If you are not the organisation named below, please notify Volunteer Wellington ASAP.</p>
					<p>The information below has been entered by the potential volunteer.</p>
					</br>
					<p>Volunteer registered for role on $displayCreateDate</p>
					<fieldset>
					<legend>THE VOLUNTEER</legend>
					<ul>
					<li><label>ID</label><b> &nbsp; $reg_id</b> (Use this if you want to contact Volunteer Wellington concerning this volunteer)</li>
					<li><label>Name</label><b> &nbsp; $volName</b></li>
					<li><label>Address Line 1</label><b> &nbsp; $volAdd1</b></li>
					<li><label>Address Line 2</label><b> &nbsp; $volAdd2</b></li>
					<li><label>Suburb</label><b> &nbsp; $volSuburb</b></li>
					<li><label>City</label><b> &nbsp; $volCity</b></li>
					<li><label>Post Code</label><b> &nbsp; $volPcode</b></li>
					<li><label><b>Contact details</b></label><b>(at least one must have been entered)</b></li>
					<li><label>Phone</label><b> &nbsp; $volPhone</b></li>
					<li><label>Email</label><b> &nbsp; <a href=\"mailto:$volEmail\">$volEmail</a></b></li>
					</ul>
					</fieldset>
					<fieldset>
					<legend>ROLE APPLIED FOR</legend>
					<p><b>Details held by Volunteer Wellington of the role listed in the Database</b></p>
					<ul>
					<li><label>Role ID</label><b> &nbsp; $job_id</b></li>
					<li><label>Role Title</label><b> &nbsp; $ref_title</b></li>
					<li><label>For Organisation</label><b> &nbsp; $agency_name</b></li>
					</ul>
					</br>
					<p><b>Please note:</b></p>
					<ol>
					<li>This email indicates the volunteer is interested in the role you have registered.  The process is self-selection only and the volunteer has not been through an interview process at Volunteer Wellington.</li>
					<li>We recommend you respond to the volunteer within 48 hrs and discuss the next steps such as an interview, completion of application forms or providing further information about the voluntary role.</li>
					</ol>
					<br/>
					<p>If you have any questions or queries, or just want to chat about how we can support your organisation, please feel free to reach out to us! We're here to help. You can email us at <a href=mailt:info@volunteerwellington.nz>info@volunteerwellington.nz</a> or call us on 04 499 4570.</p>
					</br>
					<p>Ngā mihi,</p>
					<p>The Volunteer Wellington team</p>
					<p>Tel: Wellington office on 04 499 4570</p>
					<p>Email: &nbsp; <a href=mailt:info@volunteerwellington.nz>info@volunteerwellington.nz</a></p>
					<p>Web: <a href='https://www.volunteerwellington.nz'>https:www.volunteerwellington.nz</a></p>
					<p>Registered charity: CC26471</p>
					</fieldset>";


					$mailVolContent .= "
					<fieldset>
					<legend>THE ROLE</legend>
					<ul>
					<li><label>Role Title</label><b> &nbsp; $ref_title</b></li>
					<li><label>Role Description</label><b> &nbsp; $ref_descrip</b></li>
					<li><label>Role Location</label><b> &nbsp; $ref_location</b></li>
					</ul>
					</fieldset>
					<fieldset>
					<legend>THE ORGANISTION</legend>
					<ul>
					<li><label>Organisation</label><b> &nbsp;$agency_name</b></li>
					<li><label>Organisation email</label><b> &nbsp;$agency_email</b></li>
					<li><label>Organisation phone</label><b> &nbsp;$agency_phone</b></li>
					</ul>
					</fieldset>
 					";

					$conn->insert('webrefs',
						array(
							'refID' => NULL,
							'referID' => 0,
							'webvol' => $reg_id,
							'jobID' => $job_id,
							'agencyID' => $agency_id,
							'referdate' => $volReDate,
							'reflocation' => $ref_location
						)
					);
					// add the roles registered to the office email
					$mailOfficeContent .= "
					<ul>
					<li><label>Role ID</label><b> &nbsp; $job_id</b></li>
					<li><label>Role Title</label><b> &nbsp; $ref_title</b></li>
					<li><label>Organisation</label><b> &nbsp;$agency_name</b></li>
					</ul>
					";
					// send volunteer registeration to the agency
					list($agencyUser, $agencyDomain) = explode('@', $agency_email);
					$mailService = Core::make('mail');
					$mailService->setTesting(false); // or true to throw an exception on error.
					$mailService->load('mail_template');

					// Set email parameters
					$mailService->to($agency_email);
					$mailService->to('info@volunteerwellington.nz');
					$mailService->replyto('office@volunteerwellington.nz', 'Online Job Registration');
					if ($agencyDomain == 'gmail.com'){
						$mailService->setSubject('GM - Volunteer Wellington OnLine Role Registration');
					}
					else {
						$mailService->setSubject('Volunteer Wellington OnLine Role Registration');
					}
					$mailService->setSubject('Volunteer Wellington OnLine Volunteer Registration');
					$mailService->setBodyHTML($mailAgencyContent);

					// Send email
					$mailService->sendMail();

					$webrefs[$job_id] = array(
						'job_data'=>$job_data,
						'agency_data'=>$agency_data
					);
				}
			}
			// close off the additions to the office email
			$mailOfficeContent .= "
			</fieldset>
			";
			// Send Volunteer registration to VW office
			$mailService = Core::make('mail');
			$mailService->setTesting(false); // or true to throw an exception on error.
			$mailService->load('mail_template');

	    // Set email parameters
			$mailService->to('info@volunteerwellington.nz, office@volunteerwellington.nz');
			$mailService->replyto('office@volunteerwellington.nz', 'Online Job Registration');
			$mailService->setSubject('Volunteer Wellington OnLine Volunteer Registration');
			$mailService->setBodyHTML($mailOfficeContent);

			// Send email
			$mailService->sendMail();

			//close off the additions to the Volunteer exif_thumbnail

			if (!empty($volEmail)) {
				$mailVolContent .= "
				<br/>
				<p>Your details have been sent to the organisation, and they will get in contact with you. If you don’t hear from them within four days, we recommend you contact the organisation(s) at the information above, so you can directly arrange your volunteering with them.</p>
				<p>It is important to Volunteer Wellington that all of the volunteers we work with or refer have a safe and enjoyable experience whilst undertaking their voluntary roles. As a volunteer, you must take reasonable care of your own safety and take care not to do anything which could harm another person. Please read the attached document 'Health and Safety when volunteering'.</p>
				<p>If you have any questions or queries, or just want to chat about your options, please feel free to reach out to us! We're here to help. You can email us at &nbsp; <a href=mailt:info@volunteerwellington.nz> info@volunteerwellington.nz</a> or call us on 04 499 4570.</p>
				<p>Thank you for your enthusiasm for volunteering :)</p>
				<br/>
				<p>Ngā mihi,</p>
				<p>The Volunteer Wellington team</p>
				<p>Tel: Wellington office on 04 499 4570</p>
				<p>Email: &nbsp; <a href=mailt:info@volunteerwellington.nz>info@volunteerwellington.nz</a></p>
				<p>Web: <a href='https://www.volunteerwellington.nz'>https:www.volunteerwellington.nz</a></p>
				<p>Registered charity: CC26471</p>
				";

				// send registeration to the volunteer
				list($volUser, $volDomain) = explode('@', $volEmail);
				$mailService = Core::make('mail');
				$mailService->setTesting(false); // or true to throw an exception on error.
				$mailService->load('mail_template');

				// Set email parameters
				$mailService->to($volEmail);
				$mailService->to('info@volunteerwellington.nz');
				$mailService->replyto('office@volunteerwellington.nz', 'Online Role Registration');
				if ($volDomain == 'gmail.com'){
					$mailService->setSubject('GM - Volunteer Wellington OnLine Role Registration');
				}
				else {
					$mailService->setSubject('Volunteer Wellington OnLine Role Registration');
				}
				$mailService->setBodyHTML($mailVolContent);
				$mailService->addAttachment($attachment);

				// Send email
				$mailService->sendMail();
			}

			// Store $input in session
			$_SESSION['VW']['registration']['webregister'] = $webregister;
			$_SESSION['VW']['registration']['webrefs'] = $webrefs;

			$conn->commit();
		}
		catch(\Exception $e) {
			$conn->rollback();
			throw $e;
		}
		exit;
	}

	public function registration() {
		// set variables from session
		$webregister = $_SESSION['VW']['registration']['webregister'];
		$webrefs = $_SESSION['VW']['registration']['webrefs'];
   		$this->set('webregister', $webregister);
   		$this->set('webrefs', $webrefs);
		$this->render('/job_search/registration');
	}

}
?>
