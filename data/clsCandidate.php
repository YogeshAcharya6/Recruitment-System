<?php

class Candidate
{
	/* `Resume``CurrentCompany``CurrentDesignation`
	`CurrentSalary``NoticePeriod``Role``Responsibility`
	`TotalExp``RelevantExp``MobNo``AltContactNo``EmailId`
	`Address``CandidateName``idCandidate` */
	//User variable
	private $idCandidate;
	private $CandidateName; 
	private $Address;
	private $EmailId;
	private $AltContactNo;
	private $MobNo;
	private $RelevantExp;
	private $TotalExp;
	private $Responsibility;
	private $Role;
	private $NoticePeriod;
	private $CurrentSalary;
	private $CurrentDesignation;
	private $CurrentCompany;
	private $Resume;
	private $RegDate;

	public function SaveCandidate($db, $CandidateDtls)
	{

		try {
				$TimeZone = new DateTimeZone("Asia/Kolkata");
					$date = new DateTime();
					$date->setTimezone($TimeZone);
					$TodaysDate = date('Y-m-d');
					$CandidateStatusId = 1;
					$CurrentTime = $date->format("Y-m-d H:i:s");
				$sql = "INSERT INTO `tblcandidate`(`CandidateName`, `Resume`, `CurrentCompany`, 
				`CurrentDesignation`, `CurrentSalary`, `NoticePeriod`, `Role`, `Responsibility`, `TotalExp`, `RelevantExp`, 
				`MobNo`, `AltContactNo`, `EmailId`, `Address`, `RegDate`,CandidateStatusId) 
				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
					$stmti = $db->prepare($sql);
					$stmti->bindParam(1, $CandidateDtls->CandidateName, PDO::PARAM_STR, 64);
					$stmti->bindParam(2, $CandidateDtls->Resume, PDO::PARAM_STR, 64);
					$stmti->bindParam(3, $CandidateDtls->CurrentCompany, PDO::PARAM_STR, 64);
					$stmti->bindParam(4, $CandidateDtls->CurrentDesignation, PDO::PARAM_STR, 64);
					$stmti->bindParam(5, $CandidateDtls->CurrentSalary, PDO::PARAM_INT);
					$stmti->bindParam(6, $CandidateDtls->NoticePeriod, PDO::PARAM_INT);
					$stmti->bindParam(7, $CandidateDtls->Role, PDO::PARAM_STR, 64);
					$stmti->bindParam(8, $CandidateDtls->Responsibility, PDO::PARAM_STR, 64);
					$stmti->bindParam(9, $CandidateDtls->TotalExp, PDO::PARAM_INT);
					$stmti->bindParam(10, $CandidateDtls->RelevantExp, PDO::PARAM_INT);
					$stmti->bindParam(11, $CandidateDtls->MobNo, PDO::PARAM_INT);
					$stmti->bindParam(12, $CandidateDtls->AltContactNo, PDO::PARAM_INT);
					$stmti->bindParam(13, $CandidateDtls->EmailId, PDO::PARAM_STR, 64);
					$stmti->bindParam(14, $CandidateDtls->Address, PDO::PARAM_STR, 64);
					$stmti->bindParam(15, $CurrentTime, PDO::PARAM_STR);
					$stmti->bindParam(16, $CandidateStatusId, PDO::PARAM_INT);
					$stmti->execute();
					$lastInsertId = $db->lastInsertId();
					if($lastInsertId > 0)
					{ 
						if(isset($CandidateDtls->Resume)){
						$path_info = pathinfo(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../Attachment' . DIRECTORY_SEPARATOR . 
						$CandidateDtls->Resume);
						
						$extension = $path_info['extension'];
						
						if(@rename(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../Attachment' . DIRECTORY_SEPARATOR .$CandidateDtls->Resume,
							dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '../Attachment' . DIRECTORY_SEPARATOR .$lastInsertId.".".$extension) === true){
							$newPath = "Attachment/".$lastInsertId.".".$extension;
							$sqlUpdate = "UPDATE tblcandidate set Resume = :newPath
								WHERE idCandidate = :lastInsertId";	
							$stmt = $db->prepare($sqlUpdate);
							$stmt->bindParam(':newPath', $newPath);
							$stmt->bindParam(':lastInsertId', $lastInsertId);
							$stmt->execute();
						}
						}
						for($i=0  ; $i < sizeof($CandidateDtls->QualiName) ;$i++)
						{
							try{
								$sqlQuali = "INSERT INTO `tblcandiquali`(`CandidateId`, `QualiId`)
											VALUES (?,?)";
								$stmtqi = $db->prepare($sqlQuali);
								$stmtqi->bindParam(1,$lastInsertId, PDO::PARAM_INT);
								$stmtqi->bindParam(2,$CandidateDtls->QualiName[$i]->idQuali, PDO::PARAM_INT);
								$stmtqi->execute();
							}
							catch(PDOException $e)
							{
								throw new PDOException('Erro:002' . 'Sql tblcandiquali Error'. $e->getMessage());
							}
						} 
						
						for($j = 0  ; $j < sizeof($CandidateDtls->SkillName) ; $j++)
						{
							try{
								$sqlSkill = "INSERT INTO `tblcadiskill`(`CandidateId`, `SkillId`) VALUES (?,?)";
								$stmtsi = $db->prepare($sqlSkill);
								$stmtsi->bindParam(1,$lastInsertId, PDO::PARAM_INT);
								$stmtsi->bindParam(2,$CandidateDtls->SkillName[$j]->idSkill, PDO::PARAM_INT);
								$stmtsi->execute();
							}
							catch(PDOException $e)
							{
								throw new Exception('Erro:003' . 'Sql tblcadiskill Error'. $e->getMessage());
							}
						}
						for($k= 0  ; $k < sizeof($CandidateDtls->NonTechSkill) ;$k++)
						{
							try{
								 $sqlNonTech = "INSERT INTO `tblnontechskill`(`CandidateId`, `dimNonTechSkillId`) 
								 VALUES (?,?)";
								$stmtni = $db->prepare($sqlNonTech);
								$stmtni->bindParam(1,$lastInsertId, PDO::PARAM_INT);
								$stmtni->bindParam(2,$CandidateDtls->NonTechSkill[$k]->idNonTechSkill, PDO::PARAM_INT);
								$stmtni->execute();
							}
							catch(PDOException $e)
							{
								throw new Exception('Erro:004' . 'Sql tblnontechskill Error'. $e->getMessage());
							}
						}
						for($l = 0  ; $l < sizeof($CandidateDtls->Certification) ;$l++)
						{
							try{
								$sqlcerti = "INSERT INTO `tblcertification`(`CandidateId`, `CertificationId`) 
								VALUES (?,?)";
								$stmtci = $db->prepare($sqlcerti);
								$stmtci->bindParam(1,$lastInsertId, PDO::PARAM_INT);
								$stmtci->bindParam(2,$CandidateDtls->Certification[$l]->idCertification, PDO::PARAM_INT);
								$stmtci->execute();
							}
							catch(PDOException $e)
							{
								$result = array ('result' => "error", 'message' => $e->getMessage());
							}
						}  
						return 1;	 
					}
				
			}
			catch(PDOException $e)
			{
				throw new PDOException($e->getMessage());
			}
	}

/*
Address:"manjarwadi"
AltContactNo:"2147483647"
CandidateName:"Yogesh"

Certification:Array[2]
0:Object
$$hashKey:"object:565"
Certification:"C"
idCertification:"7"

CurrentCompany:"LSL"
CurrentDesignation:"Developer"
CurrentSalary:"100000"
EmailId:"acharyayogesh694@gmail.com"
MobNo:"2147483647"

NonTechSkill:Array[2]
0:Object
$$hashKey:"object:573"
NonTechSkill:"Client Relationship Management"
idNonTechSkill:"8"

NoticePeriod:"1"
QualiName:Array[2]
0:Object
$$hashKey:"object:595"
QualiName:"BCA"
idQuali:"3"

RegDate:Fri Apr 29 2016 00:00:00 GMT+0530 (IST)
RelevantExp:"6"
Responsibility:"software developer"
Resume:"changesList.txt"
Role:"developer"

SkillName:Array[2]
0:Object
$$hashKey:"object:569"
SkillName:"ASP.Net"
idSkill:"5"

TotalExp:"9"
_
 */ 
	public function UpdateCandidate($db, $CandidateDtls)
	{

		try {
				$TimeZone = new DateTimeZone("Asia/Kolkata");
					$date = new DateTime();
					$date->setTimezone($TimeZone);
					$TodaysDate = date('Y-m-d');
					$CurrentTime = $date->format("Y-m-d H:i:s");
				$sql = "UPDATE `tblcandidate` SET `CandidateName`= ?,`Resume`= ?,`CurrentCompany`= ?,`CurrentDesignation`= ?,`CurrentSalary`= ?,`NoticePeriod`= ?,`Role`= ?,`Responsibility`= ?,`TotalExp`= ?,`RelevantExp`= ?,`MobNo`= ?,`AltContactNo`= ?,`EmailId`= ?,`Address`= ?,`RegDate`= ? WHERE idCandidate = ?";
					$stmti = $db->prepare($sql);
					$stmti->bindParam(1, $CandidateDtls->CandidateName, PDO::PARAM_STR, 64);
					$stmti->bindParam(2, $CandidateDtls->Resume, PDO::PARAM_STR, 64);
					$stmti->bindParam(3, $CandidateDtls->CurrentCompany, PDO::PARAM_STR, 64);
					$stmti->bindParam(4, $CandidateDtls->CurrentDesignation, PDO::PARAM_STR, 64);
					$stmti->bindParam(5, $CandidateDtls->CurrentSalary, PDO::PARAM_INT);
					$stmti->bindParam(6, $CandidateDtls->NoticePeriod, PDO::PARAM_INT);
					$stmti->bindParam(7, $CandidateDtls->Role, PDO::PARAM_STR, 64);
					$stmti->bindParam(8, $CandidateDtls->Responsibility, PDO::PARAM_STR, 64);
					$stmti->bindParam(9, $CandidateDtls->TotalExp, PDO::PARAM_INT);
					$stmti->bindParam(10, $CandidateDtls->RelevantExp, PDO::PARAM_INT);
					$stmti->bindParam(11, $CandidateDtls->MobNo, PDO::PARAM_INT);
					$stmti->bindParam(12, $CandidateDtls->AltContactNo, PDO::PARAM_INT);
					$stmti->bindParam(13, $CandidateDtls->EmailId, PDO::PARAM_STR, 64);
					$stmti->bindParam(14, $CandidateDtls->Address, PDO::PARAM_STR, 64);
					$stmti->bindParam(15, $CurrentTime, PDO::PARAM_STR);
					$stmti->bindParam(16, $CandidateDtls->idCandidate, PDO::PARAM_STR, 64);
					$stmti->execute();
					$rowCount = $stmti->rowCount();
					if($rowCount > 0)
					{ 
						try
						{
							$SqlDeleteQuali = "delete from tblcandiquali where CandidateId = '".$CandidateDtls->idCandidate."'";
							$stmtcq = $db->prepare($SqlDeleteQuali);
							$stmtcq->execute();
							
						}
						catch(PDOException $e)
						{
							throw new PDOException('Erro:002' . 'Sql tblcandiquali Error'. $e->getMessage());
							return 0;
						}
						
						try
						{
							$SqlDeleteCertification = "delete from tblcertification where CandidateId = '".$CandidateDtls->idCandidate."'";
							$stmtcc = $db->prepare($SqlDeleteCertification);
							$stmtcc->execute();
						}
						catch(PDOException $e)
						{
							throw new PDOException('Erro:003' . 'Sql tblcertification Error'. $e->getMessage());
							return 0;
						}
						
						try
						{
							$SqlDeleteTechSkill = "delete from tblcadiskill where CandidateId = '".$CandidateDtls->idCandidate."'";
							$stmtcts = $db->prepare($SqlDeleteTechSkill);
							$stmtcts->execute();
						}
						catch(PDOException $e)
						{
							throw new PDOException('Erro:004' . 'Sql tblcadiskill Error'. $e->getMessage());
							return 0;
						}
						
						try
						{
							$SqlDeleteNonTechSkill = "delete from tblnontechskill where CandidateId = '".$CandidateDtls->idCandidate."'";
							$stmtcnts = $db->prepare($SqlDeleteNonTechSkill);
							$stmtcnts->execute();
						}
						catch(PDOException $e)
						{
							throw new PDOException('Erro:005' . 'Sql tblnontechskill Error'. $e->getMessage());
							return 0;
						} 
						
						for($i=0  ; $i < sizeof($CandidateDtls->QualiName) ;$i++)
						{
							try{
								$sqlQuali = "INSERT INTO `tblcandiquali`(`CandidateId`, `QualiId`)
											VALUES (?,?)";
								$stmtqi = $db->prepare($sqlQuali);
								$stmtqi->bindParam(1,$CandidateDtls->idCandidate, PDO::PARAM_INT);
								$stmtqi->bindParam(2,$CandidateDtls->QualiName[$i]->idQuali, PDO::PARAM_INT);
								$stmtqi->execute();
							}
							catch(PDOException $e)
							{
								throw new PDOException('Erro:002' . 'Sql tblcandiquali Error'. $e->getMessage());
								return 0;
							}
						} 
						
						for($j = 0  ; $j < sizeof($CandidateDtls->SkillName) ; $j++)
						{
							try{
								$sqlSkill = "INSERT INTO `tblcadiskill`(`CandidateId`, `SkillId`) VALUES (?,?)";
								$stmtsi = $db->prepare($sqlSkill);
								$stmtsi->bindParam(1,$CandidateDtls->idCandidate, PDO::PARAM_INT);
								$stmtsi->bindParam(2,$CandidateDtls->SkillName[$j]->idSkill, PDO::PARAM_INT);
								$stmtsi->execute();
							}
							catch(PDOException $e)
							{
								throw new PDOException('Erro:003' . 'Sql tblcadiskill Error'. $e->getMessage());
								 return 0; 
							}
						}
						for($k= 0  ; $k < sizeof($CandidateDtls->NonTechSkill) ;$k++)
						{
							try{
								 $sqlNonTech = "INSERT INTO `tblnontechskill`(`CandidateId`, `dimNonTechSkillId`) 
								 VALUES (?,?)";
								$stmtni = $db->prepare($sqlNonTech);
								$stmtni->bindParam(1,$CandidateDtls->idCandidate, PDO::PARAM_INT);
								$stmtni->bindParam(2,$CandidateDtls->NonTechSkill[$k]->idNonTechSkill, PDO::PARAM_INT);
								$stmtni->execute();
							}
							catch(PDOException $e)
							{
								throw new PDOException('Erro:004' . 'Sql tblnontechskill Error'. $e->getMessage());
								return 0; 
							}
						}
						for($l = 0  ; $l < sizeof($CandidateDtls->Certification) ;$l++)
						{
							try{
								$sqlcerti = "INSERT INTO `tblcertification`(`CandidateId`, `CertificationId`) 
								VALUES (?,?)";
								$stmtci = $db->prepare($sqlcerti);
								$stmtci->bindParam(1,$CandidateDtls->idCandidate, PDO::PARAM_INT);
								$stmtci->bindParam(2,$CandidateDtls->Certification[$l]->idCertification, PDO::PARAM_INT);
								$stmtci->execute();
								return 1; 
							}
							catch(PDOException $e)
							{
								throw new PDOException('Erro:004' . 'Sql tblcertification Error'. $e->getMessage());
								return 0; 
							}
						} 	
						
					} 
				
			}
			catch(PDOException $e)
			{
				throw new PDOException($e->getMessage());
				return 0;
			}
	}
	public function getCandidateCnt($db,$LoginTime)
	{
		try {
			
		    $sqlcandidate = "select count(*) from tblcandidate where RegDate < '".$LoginTime."'";
			$stmt = $db->prepare($sqlcandidate);
	
			$stmt->execute();
			$result = $stmt->fetch(); 
			return $result;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	public function UpdateCandidateStatus($db,$scheduleDtls)
	{
		try {
			$CandidateStatusId = 4;
		    $sqlcandidate = "update tblcandidate set `CandidateStatusId`= ? where `idCandidate`= ?";
			$stmt = $db->prepare($sqlcandidate);
			$stmt->bindParam(1,$CandidateStatusId, PDO::PARAM_INT);
			$stmt->bindParam(2,$scheduleDtls->idCandidate, PDO::PARAM_INT);
			$stmt->execute();
			return 1;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	public function changeCandidateStatus($db,$RRDtls)
	{
		try {
			$CandidateStatusId = 1;
			for($i=0;$i<sizeof($RRDtls);$i++)
			{
				$sqlcandidate = "update tblcandidate set `CandidateStatusId`= ? where `idCandidate`= ? and CandidateStatusId = 4";
				$stmt = $db->prepare($sqlcandidate);
				$stmt->bindParam(1,$CandidateStatusId, PDO::PARAM_INT);
				$stmt->bindParam(2,$RRDtls[$i], PDO::PARAM_INT);
				$stmt->execute();
			}
			return 1;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	public function getAddCandidateCnt($db,$LoginTime)
	{
		try {
			
		    $sqlcandidate = "select count(*) from tblcandidate where RegDate > '".$LoginTime."'";
			$stmt = $db->prepare($sqlcandidate);
	
			$stmt->execute();
			$result = $stmt->fetch(); 
			return $result;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	/* function checkForFiles(){
	$app = \Slim\Slim::getInstance()->request();
	$obj = json_decode($app->getBody());
	$folderName = $obj->folderName;
	$queueArray = $obj->queueArray;
	$flag = 1;
	for($i=0; $i < sizeof($queueArray); $i++){
		if(!file_exists(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '/../'. $folderName . DIRECTORY_SEPARATOR . $queueArray[$i]))
		{
			$flag = 2;
			break;
		}	
	}
	if($flag == 1){
		$result = array('result' => 'success');
	}
	else{
		$result = array('result' => 'error');
	}
	echo json_encode($result);
} */
	public function checkForFiles($db,$folderName,$queueArray)
	{
		for($i=0; $i < sizeof($queueArray); $i++){
			if(!file_exists(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '/../'. $folderName . DIRECTORY_SEPARATOR . $queueArray[$i]))
			{
				$flag = 2;
				break;
			}	
		}
		if($flag == 1){
			return 1;
			/* $result = array('result' => 'success'); */
		}
		else{
			return 0;
			/* $result = array('result' => 'error'); */
		}
	}
		
	
	

}
?>
