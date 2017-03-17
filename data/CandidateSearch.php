<?php
	error_reporting('E_ALL');
	include('connection.php');
	//include('mysql.php');
	
	//Select Qualification------------------------------------------
	function LoadQualificationPhp()
	{
		$conn = connect_db();
		$sqlCandidate = "select * from dimqualification";
		$query = $conn->prepare($sqlCandidate);
		$query->execute();
		$result = $query->fetchAll( PDO::FETCH_ASSOC );
		$countrow = $query->rowCount();
		if($countrow > 0 ) {
			$result = array('result' => 'success', 'addndata'=>$result);
		}
		else {
			$result = array('result' => 'error', 'addndata' => 'Error');
		}	
		print json_encode($result);
	}
	
	//select Skills-----------------------------------------------
	function LoadSkillsPhp()
	{
		$conn = connect_db();
		$sqlCandidate = "select * from dimskill";
		$query = $conn->prepare($sqlCandidate);
		$query->execute();
		$result = $query->fetchAll( PDO::FETCH_ASSOC );
		$countrow = $query->rowCount();
		if($countrow > 0 ) {
			$result = array('result' => 'success', 'addndata'=>$result);
		}
		else {
			$result = array('result' => 'error', 'addndata' => 'Error');
		}	
		print json_encode($result);
	}
	
	//select Professional Certifications------------------------------------
	function LoadCertiPhp()
	{
		$conn = connect_db();
		$sqlCerti = "select * from dimcertification";
		$query = $conn->prepare($sqlCerti);
		$query->execute();
		$result = $query->fetchAll( PDO::FETCH_ASSOC );
		$countrow = $query->rowCount();
		if($countrow > 0 ) {
			$result = array('result' => 'success', 'addndata'=>$result);
		}
		else {
			$result = array('result' => 'error', 'addndata' => 'Error');
		}	
		print json_encode($result);
	}
	
	//select Nontechnical Skill-------------------------------
	function LoadNonTechSkillPhp()
	{
		$conn = connect_db();
		$sqlNontech = "select * from dimnontechskill";
		$query = $conn->prepare($sqlNontech);
		$query->execute();
		$result = $query->fetchAll( PDO::FETCH_ASSOC );
		$countrow = $query->rowCount();
		if($countrow > 0 ) {
			$result = array('result' => 'success', 'addndata'=>$result);
		}
		else {
			$result = array('result' => 'error', 'addndata' => 'Error');
		}	
		print json_encode($result);
	}
	
	//Save Candidate----------------------------------------
	$AddRecCnt = 0;
	function SaveCandidatePhp($jsonParam)
	{	
		try{
			$conn = connect_db();
			$conn->beginTransaction();
			$jsonParam->Resume = 'abc.pdf';
			$RegDate1 = $jsonParam->RegDate;
			$RegDate = date('Y-m-d', strtotime($RegDate1));
			
			$sqlSaveCandi = "insert into tblcandidate(CandidateName, RegDate, Resume, CurrentCompany ,
						CurrentDesignation , CurrentSalary , NoticePeriod , Role ,	Responsibility, TotalExp, ReleventExp , MobNo , AltContactNo , EmailId , Address)
						values('".trim($jsonParam->CandidateName)."' , '".trim($RegDate)."' , '".trim($jsonParam->Resume)."' , 
						'".trim($jsonParam->CurrentCompany)."' ,
						'".trim($jsonParam->CurrentDesignation)."' , 
						'".trim($jsonParam->CurrentSalary)."' , '".trim($jsonParam->NoticePeriod)."' , 
						'".trim($jsonParam->Role)."' , '".trim($jsonParam->Responsibility)."' , 
						'".trim($jsonParam->TotalExp)."' , '".trim($jsonParam->ReleventExp)."' , 
						'".trim($jsonParam->MobNo)."' , '".trim($jsonParam->AltContactNo)."' ,
						'".trim($jsonParam->EmailId)."' , '".trim($jsonParam->Address)."' )";
			
			$stmt = $conn->query($sqlSaveCandi);
			$lastInsertId = $conn->lastInsertId();
			
			$ArrQualification = $jsonParam->QualiName ;
			
			if($lastInsertId > 0)
			{
				for($i= 0  ; $i < sizeof($ArrQualification) ;$i++)
				{
					try{
						 $sqlQuali = "insert into tblcandiquali(CandidateId , QualiId) 
									values('" . trim($lastInsertId) . "' , '" .$ArrQualification[$i]->idQuali . "')";
						$query = $conn->prepare($sqlQuali);
						$query->execute();
						$lastIdcandiQual = $conn->lastInsertId();
						if($lastIdcandiQual > 0)
						{
							$result = array('result'=>'success','addndata'=>'tblcandiquali inserted');
						}
					}
					catch(Exception $e)
					{
						throw new Exception('Erro:002' . 'Sql tblcandiquali Error'. $e->getMessage());
					}
				} 
				for($j = 0  ; $j < sizeof($jsonParam->SkillName) ; $j++)
				{
					try{
						 $sqlSkill = "insert into tblcadiskill(CandidateId , SkillId) 
									values('" . trim($lastInsertId) . "' , '" . $jsonParam->SkillName[$j]->idSkill . "')";
						$query = $conn->prepare($sqlSkill);
						$query->execute();
						
						$lastIdcandiSkill = $conn->lastInsertId();
						if($lastIdcandiSkill > 0)
						{
							$result = array('result'=>'success','addndata'=>'tblcadiskill inserted');
						}
						
					}
					catch(Exception $e)
					{
						throw new Exception('Erro:003' . 'Sql tblcadiskill Error'. $e->getMessage());
					}
				}
				for($k= 0  ; $k < sizeof($jsonParam->NonTechSkill) ;$k++)
				{
					try{
						 $sqlNonTech = "insert into tblnontechskill(CandidateId , NonTechSkillId) 
									values('" . trim($lastInsertId) . "' , '" . trim($jsonParam->NonTechSkill[$k]->idNonTechSkill) . "')";
						$query = $conn->prepare($sqlNonTech);
						$query->execute();
						$lastIdcandiNontech = $conn->lastInsertId();
						if($lastIdcandiNontech > 0){
							$result = array('result'=>'success','addndata'=>'tblnontechskill inserted');
						}
					}
					catch(Exception $e)
					{
						throw new Exception('Erro:004' . 'Sql tblnontechskill Error'. $e->getMessage());
					}
				}
				for($l = 0  ; $l < sizeof($jsonParam->Certification) ;$l++)
				{
					try{
						$sqlcerti = "insert into tblcertification(CandidateId ,CertificationId) 
									values('" . trim($lastInsertId) . "','" . trim($jsonParam->Certification[$l]->idCertification) . "')";
						$query = $conn->prepare($sqlcerti);
						$query->execute();
						$lastIdcerti = $conn->lastInsertId();
						if($lastIdcerti > 0)
						{
							$conn->commit();
							$result = array('result'=>'success','addndata'=>'tblcertification inserted' ,'CandilastInsertId' =>$lastInsertId);
						}
						
					}
					catch(Exception $e)
					{
						$conn->commit();	
						$result = array ('result' => "error", 'message' => $e->getMessage());
					}
				} 
			}
			else
			{
				$result = array ('result' => "error", 'message' => $e->getMessage());
			}
		}
		catch(Exception $e){
			$conn->rollback();	
			$result = array ('result' => "error", 'message' => $e->getMessage());
		}
		print json_encode($result);
	} 
	
	//Search candidate--------------------------------------
	function SearchCandiPhp($jsonParam)
	{
		$conn = connect_db();
		try{
			 $SqlSearch = "select c.*,q.idCandiQuali,s.idCandiSkill, q.QualiId,dq.QualiName,ds.SkillName,
									dc.Certification,dnt.NonTechSkill
								from  tblcandidate as c
								LEFT JOIN  tblcandiquali as q ON q.CandidateId = c.idCandidate
								LEFT JOIN dimqualification as dq ON dq.idQuali = q.QualiId
								LEFT JOIN tblcadiskill as s ON s.CandidateId = c.idCandidate
								LEFT JOIN dimskill as ds ON ds.idSkill = s.SkillId
								LEFT JOIN tblcertification as tc ON tc.CandidateId = c.idCandidate
								LEFT JOIN dimcertification as dc ON dc.idCertification =  tc.CertificationId
								LEFT JOIN tblnontechskill as tns ON tns.CandidateId = c.idCandidate
								LEFT JOIN dimnontechskill as dnt ON dnt.idNonTechSkill =  tns.NonTechSkillId";
			
			$strWHERE = "";
			if(isset($jsonParam->CandidateName) && $jsonParam->CandidateName != "")
			{
				$strWHERE = "";
				$strWHERE = $strWHERE."c.CandidateName LIKE "."'$jsonParam->CandidateName%'";
			}
			if(isset($jsonParam->TotalExp) && $jsonParam->TotalExp != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."c.TotalExp = ".$jsonParam->TotalExp;	
			}
			if(isset($jsonParam->CurrentSalary) && $jsonParam->CurrentSalary != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."c.CurrentSalary = ".$jsonParam->CurrentSalary;	
			}
			
			if(isset($jsonParam->QualiName))
			{
				if(sizeof($jsonParam->QualiName) > 0)
				{
					$ArrayQuali = array();
					for($i = 0; $i < sizeof($jsonParam->QualiName); $i++)
					{
						array_push($ArrayQuali , $jsonParam->QualiName[$i]->idQuali);
					}
					$QualiIds = join(',' , $ArrayQuali);
					if ($strWHERE != "")
						$strWHERE = $strWHERE . ' AND ';
					$strWHERE = $strWHERE .  " dq.idQuali  in ($QualiIds)";
				}	 
			}
				
			if(isset($jsonParam->SkillName))
			{
				if(sizeof($jsonParam->SkillName) > 0)
				{
					$ArraySkill = array();
					for($i = 0; $i < sizeof($jsonParam->SkillName); $i++)
					{
						array_push($ArraySkill , $jsonParam->SkillName[$i]->idSkill);
					}
					$SkillIds = join(',' , $ArraySkill);
					if ($strWHERE != "")
						$strWHERE = $strWHERE . ' AND ';
					$strWHERE = $strWHERE .  " ds.idSkill  in ($SkillIds)";
				}
			}
					
			if ($strWHERE != "")
			{
				 $SqlSearch = $SqlSearch." WHERE ".$strWHERE." ORDER BY c.idCandidate";
			}
			else
			{
				 $SqlSearch = $SqlSearch." ORDER BY c.idCandidate";
			}	
			$query = $conn->prepare($SqlSearch);
			$query->execute();
			$countrow = $query->rowCount();
			$Sqlresult = $query->fetchAll( PDO::FETCH_ASSOC );
				$idCandidate = -1;
				$j = -1;
				$finalArray = array();
				//while($row = mysql_fetch_array($Sqlresult))
				for($i =0; $i < sizeof($Sqlresult); $i++)
				{	
					if($idCandidate != $Sqlresult[$i]['idCandidate'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $Sqlresult[$i]['idCandidate'];
						$finalArray[$j]['CandidateName'] = $Sqlresult[$i]['CandidateName'];
						$finalArray[$j]['TotalExp'] = $Sqlresult[$i]['TotalExp'];
						$finalArray[$j]['ReleventExp'] = $Sqlresult[$i]['ReleventExp'];
						$finalArray[$j]['NoticePeriod'] = $Sqlresult[$i]['NoticePeriod'];
						$finalArray[$j]['CurrentCompany'] = $Sqlresult[$i]['CurrentCompany'];
						$finalArray[$j]['CurrentSalary'] = $Sqlresult[$i]['CurrentSalary'];
						$finalArray[$j]['CurrentDesignation'] = $Sqlresult[$i]['CurrentDesignation'];
						$finalArray[$j]['MobNo'] = $Sqlresult[$i]['MobNo'];
						$finalArray[$j]['AltContactNo'] = $Sqlresult[$i]['AltContactNo'];
						$finalArray[$j]['EmailId'] = $Sqlresult[$i]['EmailId'];
						$finalArray[$j]['Address'] = $Sqlresult[$i]['Address'];
						$finalArray[$j]['RegDate'] = $Sqlresult[$i]['RegDate'];
						$finalArray[$j]['Role'] = $Sqlresult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $Sqlresult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$finalArray[$j]['NonTechSkillArr'] = array();
						$finalArray[$j]['CertificationArr'] = array();
						$idCandidate = $Sqlresult[$i]['idCandidate'];
					}
					if(!in_array($Sqlresult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],$Sqlresult[$i]['QualiName']);
					}
					if(!in_array($Sqlresult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$Sqlresult[$i]['SkillName']);
					}
					if(!in_array($Sqlresult[$i]['NonTechSkill'],$finalArray[$j]['NonTechSkillArr']))
					{
						array_push($finalArray[$j]['NonTechSkillArr'],$Sqlresult[$i]['NonTechSkill']);
					}
					if(!in_array($Sqlresult[$i]['Certification'],$finalArray[$j]['CertificationArr']))
					{
						array_push($finalArray[$j]['CertificationArr'],$Sqlresult[$i]['Certification']);
					}
				}
			if($countrow > 0)
			{
				$result = array('result' => 'success', 'addndata' =>$finalArray , 'CountCandidateRow' => sizeof($finalArray));
			}
			else
			{
				$result = array('result' => 'error', 'message' =>'Record Not Found');
			}
		}
		catch(Exception $e)
		{
			$Error = 'Error in SqlSearch:' .$SqlSearch .$e->getMessage();
		} 
		print json_encode($result);
	} 
	
	//Update Candidate--------------------------------------------------------------
	function UpdateCandiPhp($jsonParam)
	{
		try{
			$conn = connect_db();
			$conn->beginTransaction();
			$jsonParam->Resume = "abc.pdf";
			$EditCandiId = $jsonParam->idCandidate;
			$RegDate1 = $jsonParam->RegDate;
			$RegDate = date('Y-m-d', strtotime($RegDate1));
			$SqlUpdate = "Update tblcandidate set CandidateName = '".trim($jsonParam->CandidateName)."' ,
						RegDate = '".trim($RegDate)."' ,
						Resume = '".trim($jsonParam->Resume)."' ,
						CurrentCompany = '".trim($jsonParam->CurrentCompany)."' ,
						CurrentDesignation = '".trim($jsonParam->CurrentDesignation)."' , 
						CurrentSalary = '".trim($jsonParam->CurrentSalary)."' ,
						NoticePeriod = '".trim($jsonParam->NoticePeriod)."' , 
						Role = '".trim($jsonParam->Role)."' ,
						Responsibility = '".trim($jsonParam->Responsibility)."' ,
						TotalExp = '".trim($jsonParam->TotalExp)."' ,
						ReleventExp = '".trim($jsonParam->ReleventExp)."' ,
						MobNo = '".trim($jsonParam->MobNo)."' ,
						AltContactNo = '".trim($jsonParam->AltContactNo)."' , 
						EmailId = '".trim($jsonParam->EmailId)."' ,
						Address = '".trim($jsonParam->Address)."' 
						where idCandidate = '".$EditCandiId."'";
				
			$res = $conn->query($SqlUpdate);
			
			if($EditCandiId > 0)
			{
			
				try
				{
					$SqlDeleteQuali = "delete from tblcandiquali where CandidateId = '".$EditCandiId."'";
					$query = $conn->prepare($SqlDeleteQuali);
					$query->execute();
				}
				catch(Exception $e)
				{
					throw new Exception('Erro:002' . 'Sql tblcandiquali Error'. $e->getMessage());
				}
				
				try
				{
					$SqlDeleteCertification = "delete from tblcertification where CandidateId = '".$EditCandiId."'";
					$query = $conn->prepare($SqlDeleteCertification);
					$query->execute();
				}
				catch(Exception $e)
				{
					throw new Exception('Erro:003' . 'Sql tblcertification Error'. $e->getMessage());
				}
				
				try
				{
					$SqlDeleteTechSkill = "delete from tblcadiskill where CandidateId = '".$EditCandiId."'";
					$query = $conn->prepare($SqlDeleteTechSkill);
					$query->execute();
				}
				catch(Exception $e)
				{
					throw new Exception('Erro:004' . 'Sql tblcadiskill Error'. $e->getMessage());
				}
				
				try
				{
					$SqlDeleteNonTechSkill = "delete from tblnontechskill where CandidateId = '".$EditCandiId."'";
					$query = $conn->prepare($SqlDeleteNonTechSkill);
					$query->execute();
				}
				catch(Exception $e)
				{
					throw new Exception('Erro:005' . 'Sql tblnontechskill Error'. $e->getMessage());
				}
				
				for($i= 0  ; $i < sizeof($jsonParam->QualiName) ;$i++)
				{
					try{
						$sqlQuali = "insert into tblcandiquali(CandidateId , QualiId) 
									values('" . trim($EditCandiId) . "' , '" .$jsonParam->QualiName[$i]->idQuali . "')";
						$query = $conn->prepare($sqlQuali);
						$query->execute();
						$lastIdcandiQual = $conn->lastInsertId();
						if($lastIdcandiQual > 0)
						{
							$result = array('result'=>'success','addndata'=>'tblcandiquali inserted');
						}
					}
					catch(Exception $e)
					{
						throw new Exception('Erro:006' . 'Sql tblcandiquali Error'. $e->getMessage());
					}
				} 
				for($j = 0  ; $j < sizeof($jsonParam->SkillName) ; $j++)
				{
					try{
						$sqlSkill = "insert into tblcadiskill(CandidateId , SkillId) 
									values('" . trim($EditCandiId) . "' , '" . $jsonParam->SkillName[$j]->idSkill . "')";
						$query = $conn->prepare($sqlSkill);
						$query->execute();
						
						$lastIdcandiSkill = $conn->lastInsertId();
						if($lastIdcandiSkill > 0)
						{
							$result = array('result'=>'success','addndata'=>'tblcadiskill inserted');
						}
						
					}
					catch(Exception $e)
					{
						throw new Exception('Erro:007' . 'Sql tblcadiskill Error'. $e->getMessage());
					}
				}
				for($k= 0  ; $k < sizeof($jsonParam->NonTechSkill) ;$k++)
				{
					try{
						$sqlNonTech = "insert into tblnontechskill(CandidateId , NonTechSkillId) 
									values('" . trim($EditCandiId) . "' , '" . trim($jsonParam->NonTechSkill[$k]->idNonTechSkill) . "')";
						$query = $conn->prepare($sqlNonTech);
						$query->execute();
						$lastIdcandiNontech = $conn->lastInsertId();
						if($lastIdcandiNontech > 0){
							$result = array('result'=>'success','addndata'=>'tblnontechskill inserted');
						}
					}
					catch(Exception $e)
					{
						throw new Exception('Erro:008' . 'Sql tblnontechskill Error'. $e->getMessage());
					}
				}
				for($l = 0  ; $l < sizeof($jsonParam->Certification) ;$l++)
				{
					try{
						$sqlcerti = "insert into tblcertification(CandidateId ,CertificationId) 
									values('" . trim($EditCandiId) . "','" . trim($jsonParam->Certification[$l]->idCertification) . "')";
						$query = $conn->prepare($sqlcerti);
						$query->execute();
						$lastIdcerti = $conn->lastInsertId();
						if($lastIdcerti > 0)
						{
							$conn->commit();	
							$result = array('result'=>'success','addndata'=>'tblcertification inserted');
						}
						
					}
					catch(Exception $e)
					{
						$conn->commit();	
						$result = array ('result' => "error", 'message' => $e->getMessage());
					}
				} 
			}
			else
			{
				$result = array ('result' => "error", 'message' => $e->getMessage());
			}
			
		}
		catch(Exception $e){
			$conn->rollback();	
			$result = array ('result' => "error", 'message' => $e->getMessage());
		}
		print json_encode($result);
	} 
	
	
		
/*****************************************Job RR**********************************************************/	
//-------------------Load Department-----------------------
	function LoadDepartment()
	{
		$conn = connect_db();
		$SqlDeptRR = "select * from dimdept";
		$query = $conn->prepare($SqlDeptRR);
		$query->execute();
		$result = $query->fetchAll( PDO::FETCH_ASSOC );
		$countrow = $query->rowCount();
		if($countrow > 0 ) {
			$result = array('result' => 'success', 'addndata'=>$result);
		}
		else {
			$result = array('result' => 'error', 'addndata' => 'Error');
		}	
		print json_encode($result);
	}
//-------------------Load Position-----------------------
	function LoadPositionRR()
	{
		$conn = connect_db();
		$SqlPositionRR = "select dp.* , tj.idJobDesc from dimposition as dp
						join tbljobdesc as tj ON tj.PositionId = dp.idPosition";
		$query = $conn->prepare($SqlPositionRR);
		$query->execute();
		$result = $query->fetchAll( PDO::FETCH_ASSOC );
		$countrow = $query->rowCount();
		if($countrow > 0 ) {
			$result = array('result' => 'success', 'addndata'=>$result);
		}
		else {
			$result = array('result' => 'error', 'addndata' => 'Error');
		}	
		print json_encode($result);
	}
//-------------------Load Status-----------------------
	function LoadStatus()
	{
		$conn = connect_db();
		$SqlStatusRR = "select * from dimstatus";
		$query = $conn->prepare($SqlStatusRR);
		$query->execute();
		$result = $query->fetchAll( PDO::FETCH_ASSOC );
		$countrow = $query->rowCount();
		if($countrow > 0 ) {
			$result = array('result' => 'success', 'addndata'=>$result);
		}
		else {
			$result = array('result' => 'error', 'addndata' => 'Error');
		}	
		print json_encode($result);
	}
//-------------------Position Detail-----------------------
	function PositionDetail($jsonParam)
	{
		$conn = connect_db();
		$id = $jsonParam->idPosition;
		$SqlPositionDtlRR = "select * from tbljobdesc jd
						join  tbljdquali jdq on jdq.JobDescId =  jd.idJobDesc
						join  dimqualification dq on dq.idQuali =  jdq.QualiId
						join  tbljdskill jds on jds.JobDescId =  jd.idJobDesc
						join  dimskill ds on ds.idSkill =  jds.SkillId 
						join  dimposition dp on dp.idPosition =  jd.PositionId where 
						PositionId = $id";
		$query = $conn->prepare($SqlPositionDtlRR);
		$query->execute();
		$ResPositionDtlRR = $query->fetchAll( PDO::FETCH_ASSOC );
		$countrow = $query->rowCount();
		$idJobDesc = -1;
		$j = -1;
		$finalArray = array();
		for($i =0; $i < sizeof($ResPositionDtlRR); $i++)
		{	
			if($idJobDesc != $ResPositionDtlRR[$i]['idJobDesc'])
			{
				$j++;
				//$finalArray[$j]['Role'] = $SqlStatusRR[$i]['Role'];
				$finalArray[$j]['ExperianceMin'] = $ResPositionDtlRR[$i]['ExperianceMin'];
				$finalArray[$j]['ExperianceMAx'] = $ResPositionDtlRR[$i]['ExperianceMAx'];
				$finalArray[$j]['qualiArr'] = array();
				$finalArray[$j]['skillArr'] = array();
				$idJobDesc = $ResPositionDtlRR[$i]['idJobDesc'];
			}
			if(!in_array($ResPositionDtlRR[$i]['QualiName'],$finalArray[$j]['qualiArr']))
			{
				array_push($finalArray[$j]['qualiArr'],$ResPositionDtlRR[$i]['QualiName']);
			}
			if(!in_array($ResPositionDtlRR[$i]['SkillName'],$finalArray[$j]['skillArr']))
			{
				array_push($finalArray[$j]['skillArr'],$ResPositionDtlRR[$i]['SkillName']);
			}
		}
		if($countrow > 0 ) {
			$result = array('result' => 'success', 'addndata'=>$finalArray);
		}
		else {
			$result = array('result' => 'error', 'addndata' => 'Error');
		}	
		print json_encode($result);
	}
//-----------------------------Save RR-----------------------------------
    function SaveRRPhp($jsonParam)
	{   
		$RequestDate1 = $jsonParam->RequestDate;
		$RequestDate = date('Y-m-d', strtotime($RequestDate1));
		try{
			$conn = connect_db();
			$conn->beginTransaction();
			$SqlSaveRR = "insert into tblrr(EmpId,DeptId,Openings,JobDescId,RequestDate,ExpectedDate,SalaryMin,
							SalaryMax,StatusId)values('" . trim($jsonParam->EmpId) . "','" .trim($jsonParam->DeptId->idDept) . "' , '" . trim($jsonParam->Openings) . "','" . trim($jsonParam->Position->idJobDesc) . "','" . trim($RequestDate) . "','" . trim($jsonParam->ExpectedDate) . "','" . trim($jsonParam->SalaryMin) . "','" . trim($jsonParam->SalaryMax) ."',
							'" . trim($jsonParam->StatusId->idStatus) . "')";
			
			$query = $conn->query($SqlSaveRR);
			$lastInsertId = $conn->lastInsertId();
			if($lastInsertId > 0)
			{
				$conn->commit();
				$result = array('result'=>'success','addndata'=>'Record inserted','RRlastInsertId'=>$lastInsertId);
			}
			else
			{
				$result = array('result'=>'Error','message'=>$e->getMessage());
			}
		}
		catch(Exception $e)
		{
			$conn->rollback();
			$result = array('result'=>'Error','message'=>$e->getMessage());
		}
		print json_encode($result);
	} 
	function SearchRRPhp($jsonParam)
	{
		$RequestDate3 = $jsonParam->RequestDateS;
		$RequestDateS = date('Y-m-d', strtotime($RequestDate3));
		$ExpectedDate1 = $jsonParam->ExpectedDate;
		$ExpectedDate = date('Y-m-d', strtotime($ExpectedDate1));
		$conn = connect_db();
		try{
			 $SqlRRSearch = "select * from tblrr as rr
						LEFT JOIN dimdept as dd on dd.idDept =  rr.DeptId 
						LEFT JOIN tbljobdesc  as jd on jd.idJobDesc =  rr.JobDescId 
						LEFT JOIN dimposition as dp on dp.idPosition =  jd.PositionId 
						LEFT JOIN dimstatus as dst on dst.idStatus =  rr.StatusId
						LEFT JOIN tbljdquali jdq on rr.JobDescId =  jd.idJobDesc
						LEFT JOIN dimqualification dq on dq.idQuali =  jdq.QualiId
						LEFT JOIN tbljdskill jds on rr.JobDescId =  jd.idJobDesc
						LEFT JOIN dimskill ds on ds.idSkill =  jds.SkillId";
			
			$strWHERE = "";
			if(isset($jsonParam->EmpId) && $jsonParam->EmpId != "")
			{
				$strWHERE = "";
				$strWHERE = $strWHERE."rr.EmpId =".$jsonParam->EmpId;
			}
			if(isset($jsonParam->DeptId->idDept) && $jsonParam->DeptId->idDept != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."rr.DeptId = ".$jsonParam->DeptId->idDept;	
			}
			if(isset($RequestDate3) && $RequestDate3 != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."rr.RequestDate = '" . trim($RequestDateS). "' ";	
			}
			if(isset($ExpectedDate1) && $ExpectedDate1 != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."rr.ExpectedDate = '" . trim($ExpectedDate). "' ";	
			}
			if(isset($jsonParam->StatusId->idStatus) && $jsonParam->StatusId->idStatus != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."rr.StatusId = ".$jsonParam->StatusId->idStatus;	
			}
			
			if ($strWHERE != "")
			{
				$SqlRRSearch = $SqlRRSearch." WHERE ".$strWHERE." ORDER BY rr.EmpId";
			}
			else
			{
				$SqlRRSearch = $SqlRRSearch." ORDER BY rr.EmpId";
			}	
			$query = $conn->prepare($SqlRRSearch);
			$query->execute();
			$countrow = $query->rowCount();
			$SqlRRresult = $query->fetchAll( PDO::FETCH_ASSOC );
			$idJobDesc = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($SqlRRresult); $i++)
			{	
				if($idJobDesc != $SqlRRresult[$i]['idJobDesc'])
				{
					$j++;
					//$finalArray[$j]['Role'] = $SqlStatusRR[$i]['Role'];
					$finalArray[$j]['idRR'] = $SqlRRresult[$i]['idRR'];
					$finalArray[$j]['DeptName'] = $SqlRRresult[$i]['DeptName'];
					$finalArray[$j]['Position'] = $SqlRRresult[$i]['Position'];
					$finalArray[$j]['Openings'] = $SqlRRresult[$i]['Openings'];
					$finalArray[$j]['RequestDate'] = $SqlRRresult[$i]['RequestDate'];
					$finalArray[$j]['ExpectedDate'] = $SqlRRresult[$i]['ExpectedDate'];
					$finalArray[$j]['SalaryMin'] = $SqlRRresult[$i]['SalaryMin'];
					$finalArray[$j]['SalaryMax'] = $SqlRRresult[$i]['SalaryMax'];
					$finalArray[$j]['Status'] = $SqlRRresult[$i]['Status'];
					$finalArray[$j]['ExperianceMin'] = $SqlRRresult[$i]['ExperianceMin'];
					$finalArray[$j]['ExperianceMAx'] = $SqlRRresult[$i]['ExperianceMAx'];
					$finalArray[$j]['qualiArr'] = array();
					$finalArray[$j]['skillArr'] = array();
					$idJobDesc = $SqlRRresult[$i]['idJobDesc'];
				}
				if(!in_array($SqlRRresult[$i]['QualiName'],$finalArray[$j]['qualiArr']))
				{
					array_push($finalArray[$j]['qualiArr'],$SqlRRresult[$i]['QualiName']);
				}
				if(!in_array($SqlRRresult[$i]['SkillName'],$finalArray[$j]['skillArr']))
				{
					array_push($finalArray[$j]['skillArr'],$SqlRRresult[$i]['SkillName']);
				}
			}
			if($countrow > 0)
			{
				$result = array('result' => 'success', 'addndata' =>$finalArray, 'CountRRRow' => sizeof($finalArray));
			}
			else
			{
				$result = array('result' => 'error', 'addndata' => $SqlRRresult,'sql'=>$SqlRRSearch, '');
			}
		}
		catch(Exception $e)
		{
			$Error = 'Error in SqlSearch:' .$SqlRRSearch .$e->getMessage();
		} 
		print json_encode($result);
	}
	
	$jsonRequest = json_decode(file_get_contents('php://input')); // read the json input
	//decode the jsonRequest;
	switch($jsonRequest->functionCode) 
	{ 
		case "LoadQualificationPhp":
			//$arrParams = $jsonRequest->jsonParam;
			LoadQualificationPhp();
			break;
		case "LoadSkillsPhp":
			//$arrParams = $jsonRequest->jsonParam;
			LoadSkillsPhp();
			break;
		case "LoadCertiPhp":
			//$arrParams = $jsonRequest->jsonParam;
			LoadCertiPhp();
			break;
		case "LoadNonTechSkillPhp":
			//$arrParams = $jsonRequest->jsonParam;
			LoadNonTechSkillPhp();
			break;	
		case "SaveCandidatePhp":
			$arrParams = $jsonRequest->jsonParam;
			SaveCandidatePhp($arrParams);
			break;	
		case "SearchCandiPhp":
			$arrParams = $jsonRequest->jsonParam;
			SearchCandiPhp($arrParams);
			break;
		case "UpdateCandiPhp":
			$arrParams = $jsonRequest->jsonParam;
			UpdateCandiPhp($arrParams);
			break;	
		
			
			
		//RR-------------
		case "LoadDepartment":
			LoadDepartment();
			break;
		case "LoadPositionRR":
			LoadPositionRR();
			break;
		case "LoadStatus":
			LoadStatus();
			break;
		case "PositionDetail":
			$arrParams = $jsonRequest->jsonParam;
			PositionDetail($arrParams);
			break;
		case "SaveRRPhp":
			$arrParams = $jsonRequest->jsonParam;
			SaveRRPhp($arrParams);
			break;
		case "SearchRRPhp":
			$arrParams = $jsonRequest->jsonParam;
			SearchRRPhp($arrParams);
			break;
		
		default:
			$result = array ('result' => "error", 'addndata' => -9998);		// unknown function
			print json_encode($result);
	}	
?>
