<?php 
include('db/connection.php');
require 'Slim/Slim.php';
require 'PHPMailer/PHPMailerAutoload.php';
//error_reporting('E_ALL');
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->config('debug', true);
//log errors
	$logger = $app->log;
	$logger->setEnabled(true);
	$logger->setLevel(\Slim\Log::DEBUG);
	$environment = \Slim\Environment::getInstance();
	$environment['slim.errors'] = fopen('log/errorslog.log', 'a');
// 

$app->get('/vendor', 'getVendorList');
$app->post('/saveCnsl', 'saveConsaltant');
$app->run();

function getVendorList(){
	$sql = "select * from tblvendor";
	
	try 
	{
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ); 
		$db = null;
		$result = array('result' => 'success','addndata'=>$wines);
	} catch(PDOException $e) {
		$result = array('result' => 'error','sql'=>$sql);
	}
	echo json_encode($result);
}
function saveConsaltant(){
	$app = \Slim\Slim::getInstance()->request();
	$User = json_decode($app->getBody());
	$todate = date('Y-m-d');
	
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$";
    $password = substr(str_shuffle($chars),0,8);
	
	$actCode = rand( 100000 , 999999);
	
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'phoenixsystech.in';
	$mail->SMTPAuth = true;
	$mail->Username = 'info@phoenixsystech.in';
	$mail->Password = 'm3rcL1f3$';
	$mail->SMTPSecure = 'tls';
	$mail-> Port = 587;
	$mail->From = 'info@phoenixsystech.in';
	$mail->FromName = 'Phoenixsystech';
	$mail->addAddress($User->cnslOffEmail, $User->cnslFName.' '.$User->cnslLName);
	$mail->addReplyTo('info@phoenixsystech.in', 'Phoenixsystech');
	$mail->WordWrap = 50;
	$mail->isHTML(true);
	//$mail->SMTPDebug = 1;
	$mail->Subject = 'Account Creation';
	$str = 'Hi,<br>';
	$str = $str.'Please check out your new account.<br>';
	$str = $str.'To login:<br>';
	$str = $str.'<a href="http://localhost/PoinTS/'.$actCode.'">'.'http://localhost/PoinTS/'.$actCode.'</a><br>';
	$str = $str.'Username:'.$User->cnslOffEmail.'<br>';
	$str = $str.'Password:'.$password.'<br>';
	$str = $str.'Activation Code:'.$actCode.'<br>';
	$mail->Body = $str;
	
	$sqlUser = "INSERT into tbluser(usrLoginId,usrPassword,usrIsActive,roleId,actCode,verifyId)
		values('".$User->cnslOffEmail."','".$password."','".$User->status."','0','".$actCode."','2')";
	if($mail->send()) 
	{
		try 
		{
			$db = getConnection();
			$result = $db->prepare($sqlUser); 
			$result->execute();
			$lastInsertId = $db->lastInsertId();	
			
			$sqlCnsl = "INSERT into tblcnsl(usrId,cnslTypeId,cnslTrackingTypeId,cnslIsAcive,cnslFName,cnslMName,
							cnslLName,cnslStatusId,cnslOffEmail,cnslPersEmail,cnslJoinDate,cnslLWorkingDate)
						values ('".$lastInsertId."','".$User->cnslTypeId."','0','".$User->status."','".$User->cnslFName."'
						,'".$User->cnslMName."','".$User->cnslLName."','0','".$User->cnslOffEmail."','".$User->cnslPersEmail."',
						'','')";
						
			$result = $db->prepare($sqlCnsl); 
			$result->execute();
			$lastInsertId = $db->lastInsertId();
			
			if($User->cnslTypeId == 1)
				$sql = "INSERT into tblemp(employeeCode,CnslId) values('".$User->code."','".$lastInsertId."')";
			else
				$sql = "INSERT into tblcontractor(CtrtVendorId,CtrtVendorEmpCode,cnslId) 
					values('".$User->vendor->idVendor."','".$User->code."','".$lastInsertId."')";
				
			$result = $db->prepare($sql); 
			$result->execute();
			$lastInsertId = $db->lastInsertId();	
			
			$message = 'Consultant Created Successfully!!';
			$result = array('result' => 'success', 'addndata' =>$User ,'message'=> $message);
		}
		catch(PDOException $e) {
			$result = array('result' => 'error','sql'=>$e->getMessage());
		}
	}
	else
	{
		$result = array('result' => 'error','message'=>$mail->ErrorInfo);
	}
	echo json_encode($result);
}