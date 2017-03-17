<?php
include('db/connection.php');
require 'Slim/Slim.php';
require_once('Admin.php');
require_once 'Mobile_Detect.php';
require_once('clsInterviewType.php');
require_once('clsUser.php');
require_once('clsConfig.php');
require_once('clsRRCandidate.php');
require_once('clsLoginHistory.php');
require_once('clsRole.php');
require_once('clsUserRole.php');
require_once('clsUserRoles.php');
require_once('clsRoles.php');
require_once('clsUsers.php');
require_once('clsElement.php');
require_once('clsElements.php');
require_once('clsDepartment.php');
require_once('clsDesignation.php');
require_once('clsPositions.php');
require_once('clsPosition.php');
require_once('clsSkill.php');
require_once('clsSkills.php');
require_once('clsQualifications.php');
require_once('clsQualification.php');
require_once('clsJobDesc.php');
require_once('clsJobDescSearch.php');
require_once('clsStatus.php');
require_once('clsRR.php');
require_once('clsOpening.php');
require_once('clsRRSearch.php');
require_once('clscertification.php');
require_once('clsCandidate.php');
require_once('clscandidatesearch.php');
 

\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();
	$app->config('debug', true);
	$environment = \Slim\Environment::getInstance();
					
	$authenticateMW = function(){
		$app = \Slim\Slim::getInstance()->request();
		$Obj = json_decode($app->getBody());
		
		$IdString = $Obj->objContext->IDString;
		$clientIP = $app->getIP();
		$SessionTimeOut = $Obj->objContext->arrConfig->SessionTimeOut; 
		$timeout =  floatval($SessionTimeOut * 60); 
		$cnt = 0;
		try{
			$db = connect_db();
			
		 	$sqlhistory = "SELECT lastActionDateTime
							from tblloginhist 
							WHERE idString = ? 
							AND clientIP = ? ";
			$stmt = $db->prepare($sqlhistory);
			$stmt->bindParam(1, $IdString, PDO::PARAM_STR);
			$stmt->bindParam(2, $clientIP, PDO::PARAM_STR);
			$stmt->execute();
			while($result = $stmt->fetch())
			{
				$lastActionDateTime = $result['lastActionDateTime'];
				$cnt++;
			}
			$timezone = new DateTimeZone("Asia/Kolkata" );
			$date = new DateTime();
			$date->setTimezone($timezone);
			$currTime = $date->format("Y-m-d H:i:s");
			$elapsed_time = strtotime($currTime) - strtotime( $lastActionDateTime);
								
			if($cnt == 1)
			{
				if($elapsed_time >= $timeout)
					{
						try
						{
							$db->beginTransaction();
							  $sqlUpdate = " UPDATE tblloginhist  
												set IsTimeOut = 1,LogoutTime = ?
												WHERE idString = ? ";
							$stmt = $db->prepare($sqlUpdate);
							$stmt->bindParam(1, $currTime, PDO::PARAM_STR);
							$stmt->bindParam(2, $IdString , PDO::PARAM_STR, 24);
							$stmt->execute();
							$db->commit();
							echo "SessionTimeOut";
							$app = \Slim\Slim::getInstance();
							$app->stop();
						}
						catch(Exception $e)
						{
							$db->rollback();
							$errMsg = "Update Err001: ".  $sqlUpdate . $e->getMessage() ; 
							throw new Exception($errMsg);
						}
					}
					else
					{
						try
						{
							$db->beginTransaction();
							$sqlUpdate = "UPDATE tblloginhist  
												set lastActionDateTime = ?
												WHERE idString = ? ";
							$stmt = $db->prepare($sqlUpdate);
							$stmt->bindParam(1, $currTime, PDO::PARAM_STR);
							$stmt->bindParam(2, $IdString, PDO::PARAM_STR, 24);
							$stmt->execute();
							$db->commit();
						}
						catch(Exception $e)
						{
							$db->rollback();
							$errMsg = "Update Err002: ". $sqlUpdate . $e->getMessage() ; 
							throw new Exception($errMsg);
						}
					} 
					$result = array('result' => 'success');
			}
			else{
				echo "SessionTimeOut";
				$app->stop();
			} 
		}	
		catch(PDOException $e) {
			$app->stop();
		}  
	};
	
	$Logout = function(){
		$db = connect_db();
		$app = \Slim\Slim::getInstance()->request();
		$Obj = json_decode($app->getBody());
		$timezone = new DateTimeZone("Asia/Kolkata" );
		$date = new DateTime();
		$date->setTimezone($timezone);
		$IdString = $Obj->objContext->IDString;
		$currTime = $date->format("Y-m-d H:i:s");
		try
		{
			$sqlUpdate = "UPDATE tblloginhist  
							set IsTimeOut = 1,LogoutTime = ?
							WHERE idString = ? ";
			$stmt = $db->prepare($sqlUpdate);
			$stmt->bindParam(1, $currTime, PDO::PARAM_STR);
			$stmt->bindParam(2, $IdString , PDO::PARAM_STR, 24);
			$stmt->execute();
			$countRow = $stmt->rowCount();
						
			if($countRow > 0 ) {
				$result = array('result' => 'success', 'message'=>'logout');
			}
			else {
					$result = array('result' => 'error','message'=>'error in executing query');
			}
		}
		catch(Exception $e)
		{
			$errMsg = $e->getMessage(); 
			$result = array('result' => 'error','message'=>	$errMsg);
		}
		echo json_encode($result);
	};
	
	$app->post('/chkLogin' , 'Login');
	$app->post('/detectDevice', 'detectDevice');
	$app->post('/createUser' , $authenticateMW , 'createUser');
	$app->post('/ChangePassword', $authenticateMW , 'changePassword');
	$app->post('/RoleList' , 'RoleList');
	$app->post('/ElementTypeList' , 'ElementTypeList');
	$app->post('/getRolePermission' , $authenticateMW , 'RolePermissionList');
	$app->post('/ElementList' , 'ElementList');
	$app->post('/UserList' , 'UserList');
	$app->post('/ConfigureList' , 'ConfigureList');
	$app->post('/EditConfigure' , $authenticateMW , 'EditConfigure');
	$app->post('/saveRole' , $authenticateMW , 'saveRole');
	$app->post('/saveElement' , $authenticateMW , 'saveElement');
	$app->post('/saveRRSchedule' , $authenticateMW , 'saveRRSchedule');
	$app->post('/LoadUserRoleDtls' , 'LoadUserRoleDtls');
	$app->post('/editRole' , $authenticateMW , 'editRole');
	$app->post('/saveUserRole' , $authenticateMW , 'saveUserRole');
	$app->post('/saveRolePermissions' , $authenticateMW , 'saveRolePermissions');
	$app->post('/DesignationList' , 'DesignationList');
	$app->post('/DepartmentList' , 'DepartmentList');
	$app->post('/deactivateUser' , $authenticateMW , 'deactivateUser');
	$app->post('/PositionList' , $authenticateMW , 'PositionList');
	$app->post('/SkillList' , $authenticateMW , 'SkillList');
	$app->post('/QualificationList' , $authenticateMW , 'QualificationList');
	$app->post('/saveJobDescDtls' , $authenticateMW , 'saveJobDescDtls');
	$app->post('/getEmpDept' , $authenticateMW , 'getEmpDept');
	$app->post('/searchJobDescDtls' , $authenticateMW , 'searchJobDescDtls');
	$app->post('/getJobDtlsSrvc' , $authenticateMW , 'getJobDtls');
	$app->post('/getAddJobDtlsSrvc' , $authenticateMW , 'getAddJobDtls');
	$app->post('/searchRRDtls' , $authenticateMW , 'searchRRDtls');
	$app->post('/getActivePositionList' , $authenticateMW , 'getActivePositionDtlsList');
	$app->post('/saveCandiFeedback' , $authenticateMW , 'saveCandiFeedback');
	$app->post('/updateRRSchedule' , $authenticateMW , 'updateRRSchedule');
	$app->post('/getRoleRelUserList' , $authenticateMW , 'getRoleRelUserList');
	$app->post('/AddUserRoleDtls' , $authenticateMW , 'AddUserRoleDtls');
	$app->post('/deactivateCandiRelPos' , $authenticateMW , 'deactivateCandiRelPos');
	$app->post('/updateCandiFeedback' , $authenticateMW , 'updateCandiFeedback');
	$app->post('/IntInterfaceDtls' , $authenticateMW , 'IntInterfaceDtls');
	$app->post('/getIntHistoryDtls' , $authenticateMW , 'getIntHistoryDtls');
	$app->post('/getEmployeeList' , $authenticateMW , 'getEmployeeList');
	$app->post('/getRRDtlsSrvc' , $authenticateMW , 'getRRDtlsSrvc');
	$app->post('/getAddRRDtlsSrvc' , $authenticateMW , 'getAddRRDtls');
	$app->post('/getJDDtls' , $authenticateMW , 'getJDRelPosDtls');
	$app->post('/getJD' , $authenticateMW , 'getRelPosJDDtls');
	$app->post('/editJDDtls' , $authenticateMW , 'editJDDtls');
	$app->post('/UpdateRR' , $authenticateMW , 'UpdateRR');
	$app->post('/AddRRCandidate' , 'AddRRCandidate');
	$app->post('/SaveRR' , $authenticateMW , 'SaveRR');
	$app->post('/getCountDtls' , $authenticateMW , 'getCountRRJDCandidate');
	$app->post('/getAddCountDtls' , $authenticateMW , 'getAddCountDtls');
	$app->post('/SaveCandidate' , $authenticateMW , 'SaveCandidate');
	$app->post('/SearchCandidate' , $authenticateMW , 'SearchCandidate');
	$app->post('/getRRCandidateDtls' , $authenticateMW , 'getRRCandidateDtls');
	$app->post('/getCandidateDtls' , $authenticateMW , 'getCandidateDtls');
	$app->post('/getAddCandidateDtlsSrvc' , $authenticateMW , 'getAddCandidateDtls');
	$app->post('/getPositionRelCandidate' , $authenticateMW , 'getPositionRelCandidate');
	$app->post('/getInterviewTypeList' , $authenticateMW , 'getInterviewTypeList');
	$app->post('/UpdateCandidate' , $authenticateMW , 'UpdateCandidate');
	$app->post('/getUserRelFeedbackList' , $authenticateMW , 'getUserRelFeedbackList');
	$app->post('/getUserRelFeedbackList' , $authenticateMW , 'getUserRelFeedbackList');
	$app->post('/RolePermissionList' , 'getRolePermission'); //user
	$app->post('/getUserAlert' , 'getUserAlert'); //user
	$app->post('/getUserAlertDtls' , 'getUserAlertDtls'); //user
	$app->post('/UserRoleList' , 'UserRoleList'); //user
	$app->post('/CertificationList' , 'CertificationList'); //user
	$app->post('/NonTechSkillList' , 'NonTechSkillList'); //user
	$app->post('/getJobDescPosList' , 'JobDescPosList'); 
	$app->post('/getStatusList' , 'StatusList'); 
	$app->post('/checkForFiles' , 'checkForFilesDtls'); 
	$app->post('/getResumeFileType' , 'getResumeFileType'); 
	$app->post('/LogoutUser' , $authenticateMW , $Logout);
	
	$app->run();
function detectDevice(){
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	$result = array('result' => 'success','addndata'=> $deviceType);
	echo json_encode($result);
	};
	
?>
