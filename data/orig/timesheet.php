<?php 
include('db/connection.php');
require 'Slim/Slim.php';
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

$app->get('/timesheet', 'getTimesheet');
$app->post('/saveTS', 'saveTimeSheet');
$app->run();

function getTimesheet(){
	$app = \Slim\Slim::getInstance();
	$usrId = $app->request()->params('usrId');
	
	$todate = date('Y-m-d');
	$sql = "SELECT * FROM `tblengagement` tg 
		JOIN tblbillingcycle tbc on tg.`idEgmt` = tbc.egmtId 
		JOIN tblegmtcnsl tgc on tg.`idEgmt` = tgc.egmtId
		JOIN tblcnsl tc on tc.idCnsl = tgc.cnslId
		JOIN tblcnsldate tcd1 on tcd1.cnslId = tc.idCnsl
		left outer join tblcnslwhrs tcw on tcd1.idCnslDate = tcw.cnslDateId 
        JOIN tblegmtcalendar tec on tec.`egmtId` = tg.`idEgmt`
		WHERE '".$todate."' between `egmtStartDate` and `egmtEndDate`
		and tc.usrId = '".$usrId."'";
	
	$sqlPaid = "select * from tblpaidhol";
	try 
	{
		$db = getConnection();
		$stmt = $db->query($sql);  
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$stmt = $db->query($sqlPaid);  
		$paidArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		for($i=0;$i<sizeof($result);$i++)
		{
			if($result[$i]['hrsWorked'] == null)
			{
				$result[$i]['hrsWorked'] = '';
				$result[$i]['op'] = 'insert';
			}
			else
			{
				$result[$i]['op'] = 'update';
			}
			if($result[$i]['workNote'] == null)
			{
				$result[$i]['workNote'] = '';
			}
			
			if($result[$i]['isWorkingDay'] == 1)
			{
				$result[$i]['flag'] = 1;
				$result[$i]['placeholder'] = $result[$i]['ecHrsPerDay'];
			}
			else
			{
				$flag = 0;
				$result[$i]['placeholder'] = '';
				for($j=0;$j<sizeof($paidArray);$j++)
				{
					if($result[$i]['calendarDate'] == $paidArray[$j]['paidHoliDate'])
					{
						$flag = 1;
						break;
					}
				}
				if($flag == 1)
				{
					$result[$i]['flag'] = 2;
				}
				else
				{
					$result[$i]['flag'] = 3;
				}
			}
		}
		$db = null;
		$result = array('result' => 'success','addndata'=>$result);
	} catch(PDOException $e) {
		$result = array('result' => 'error','sql'=>$sql);
	}
	echo json_encode($result);
}

function saveTimeSheet()
{
	$app = \Slim\Slim::getInstance()->request();
	$timesheet = json_decode($app->getBody());
	$db = getConnection();
	
	for($i=0;$i<sizeof($timesheet);$i++)
	{
		if($timesheet[$i]->hrsWorked != '')
		{
			if($timesheet[$i]->op == 'insert')
			{
				$sql = "INSERT into tblcnslwhrs(cnslDateId,hrsWorked,workNote)
					values ('".$timesheet[$i]->idCnslDate."','".$timesheet[$i]->hrsWorked."','".$timesheet[$i]->workNote."')";
			}
			else if($timesheet[$i]->op == 'update')
			{
				$sql = "UPDATE tblcnslwhrs set hrsWorked = '".$timesheet[$i]->hrsWorked."',
					workNote = '".$timesheet[$i]->workNote."'
					WHERE cnslDateId = '".$timesheet[$i]->cnslDateId."'";
			}
			$stmt = $db->query($sql); 
		}
	}
	$result = array('result' => 'success','message'=>'Record Saved Successfully!!!');
	echo json_encode($result);
}