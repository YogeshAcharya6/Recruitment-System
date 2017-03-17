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

$app->post('/chkLogin', 'Login');
$app->run();

function Login(){
	$app = \Slim\Slim::getInstance()->request();
	$login = json_decode($app->getBody());
	$todate = date('Y-m-d');
	$sql = "SELECT * FROM tbluser where usrLoginId = '".$login->userName."' and usrPassword = '".$login->Password."'";
	
	try 
	{
		$db = getConnection();
		$result = $db->prepare($sql); 
		$result->execute();
		$User = $result->fetchAll(PDO::FETCH_ASSOC);
		$number_of_rows = $result->rowCount();
		if($number_of_rows > 0)
		{
			if($User[0]['verifyId'] == '1')
			{
				$result = array('result' => 'success','addndata'=>$login,'message'=>'Login Successful');
			}
			else
			{
				if(isset($login->actCode))
				{
					if($User[0]['actCode'] == $login->actCode)
					{
						$sqlVerify = "UPDATE tbluser set verifyId='1' where idUser = '".$User[0]['idUser']."'";
						$result = $db->prepare($sqlVerify); 
						$result->execute();
						$result = array('result' => 'success','addndata'=>$User,'message'=>'Login Successful');
					}
					else
					{
						$result = array('result' => 'error','message'=>'Please Enter Valid Information');
					}
				}
				else
				{
					$result = array('result' => 'success','addndata'=>$User,'flag'=>'1');
				}
			}
			
		}
	}
	catch(PDOException $e) {
		$result = array('result' => 'error','sql'=>$sql,'message'=>$e->getMessage());
	}
	echo json_encode($result);
}
