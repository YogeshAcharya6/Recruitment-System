<?php
	error_reporting('E_ALL');
	include ('connection.php');
	
	//Select Department------------------------------------------
	function LoadQualification()
	{
		$conn = connect_db();
		$sqlQuali = " select * from  dimqualification ";
		$query = $conn->prepare($sqlQuali);
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
	
	//Select Skill--------------------
	function LoadSkill()
	{
		$conn = connect_db();
		$sqlSkill = " select * from dimskill ";
		$query = $conn->prepare($sqlSkill);
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
	
	function LoadPosition()
	{
		$conn = connect_db();
		$sqlPosition = "select * from dimposition ";
		$query = $conn->prepare($sqlPosition);
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
			
	function InsertJobDesc($jsonParam){
	try{
		$conn = connect_db();
		$conn->beginTransaction();
		$sqlJobDesc= "insert into tbljobdesc(PositionId,ExperianceMin,ExperianceMAx,Role,Responsibility)
					values('".trim($jsonParam->Position->idPosition	)."',
					'".trim($jsonParam->ExperianceMin)."',
					'".trim($jsonParam->ExperianceMAx)."',
					'".trim($jsonParam->Role)."',
					'".trim($jsonParam->Responsibility)."')";
		$query = $conn->prepare($sqlJobDesc);
		$query->execute();
		$LastId = $conn->lastInsertId();
		$ArrQualification = $jsonParam->QualiName ;
		$ArrSkill= $jsonParam->SkillName ;
		
		if ($LastId > 0)
		{
			for($i= 0  ; $i < sizeof($ArrQualification) ;$i++)
			{
				try{
				$sqlQuali = "insert into tbljdquali(JobDescId , QualiId) 
							values('" . trim($LastId) . "' , 
							'" .$ArrQualification[$i]->idQuali . "')";
							$query = $conn->prepare($sqlQuali);
							$query->execute();
							$lastIdjdQual = $conn->lastInsertId();
							if($lastIdjdQual > 0){
								$result = array('result'=>'success','addndata'=>'tbljdquali inserted');
							}
				
				}
				catch(Exception $e)
				{
					throw new Exception('Erro:002' . 'Sql tbljdquali Error'. $e->getMessage());
				}				
			}	
			for($j = 0  ; $j < sizeof($ArrSkill) ; $j++)
			{
				try{
				$sqlSkill = "insert into  tbljdskill(JobDescId ,SkillId) 
							values('" . trim($LastId) . "' , 
							'" .$ArrSkill[$j]->idSkill. "')";
							$query = $conn->prepare($sqlSkill);
							$query->execute();
							$lastIdjdSkill = $conn->lastInsertId();
							if($lastIdjdSkill > 0){
								$conn->commit();	
								$result = array('result'=>'success','addndata'=>' tbljdskill inserted' , 'JDlastInsertId' => $LastId);
							}				
				}
				catch(Exception $e)
				{
					$conn->commit();	
					throw new Exception('Erro:003' . 'Sql  tbljdskill Error'. $e->getMessage());
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
	
	function DispyJd()
	{
	$conn = connect_db();
	$sqlJdview= "select dp.Position,q.idJobDesc,q.Role,q.Responsibility,dq.QualiName,ds.SkillName,
				q.ExperianceMin,q.ExperianceMAx from tbljobdesc q
				 JOIN tbljdquali tj ON tj.JobDescId = q.idJobDesc
				 JOIN dimqualification dq ON dq.idQuali = tj.QualiId
				 JOIN tbljdskill S ON S.JobDescId = q.idJobDesc
				 JOIN dimposition dp ON dp.idPosition = q.PositionId
				 JOIN  dimskill ds ON ds.idSkill = S.SkillId group by idJobDesc";
	$query = $conn->prepare($sqlJdview);
	$query->execute();
	$result = $query->fetchAll( PDO::FETCH_ASSOC );
	$countrow = $query->rowCount();
	
	if($countrow  > 0 ) 
	{
		$result = array('result' => 'success', 'addndatap'=>$result);
	}
	else 
	{
		$result = array('result' => 'error', 'addndata' => 'Error');
	}		
	
	
	print json_encode($result);			
}

function SearchJd($jsonParam)	
	{
		$conn = connect_db();
		try
		{
			$sqlSearchjoin = "select q.idJobDesc,dq.QualiName,ds.SkillName,q.ExperianceMin,
						q.ExperianceMAx,q.Role,q.Responsibility,dp.Position,q.PositionId,tj.QualiId,S.SkillId
						from tbljobdesc q
						LEFT OUTER JOIN tbljdquali tj ON tj.JobDescId = q.idJobDesc
						LEFT OUTER JOIN dimqualification dq ON dq.idQuali = tj.QualiId
						LEFT OUTER JOIN tbljdskill S ON S.JobDescId = q.idJobDesc
						LEFT OUTER JOIN dimskill ds ON ds.idSkill = S.SkillId
						LEFT OUTER JOIN dimposition dp ON dp.idPosition = q.PositionId";
			$strWHERE = "";
			if(isset($jsonParam->ExperianceMin) && $jsonParam->ExperianceMin != "")
			{
				$strWHERE = "";
				$strWHERE = $strWHERE."q.ExperianceMin =".$jsonParam->ExperianceMin;
			}			
			if(isset($jsonParam->ExperianceMAx) && $jsonParam->ExperianceMAx != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."q.ExperianceMAx =".$jsonParam->ExperianceMAx;	
			}
			if(isset($jsonParam->Role) && $jsonParam->Role != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."q.Role =".$jsonParam->Role;	
			}
			if(isset($jsonParam->Responsibility) && $jsonParam->Responsibility != "")
			{
				if ($strWHERE != "")
					$strWHERE = $strWHERE . ' AND ';
				$strWHERE = $strWHERE."q.Responsibility =".$jsonParam->Responsibility;	
			}
			if(isset($jsonParam->QualiName))
			{
				if(sizeof($jsonParam->QualiName) > 1)
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
				else
				{
					if ($strWHERE != "")
						$strWHERE = $strWHERE . ' AND ';
					$strWHERE = $strWHERE . " dq.idQuali = "  .($jsonParam->QualiName[0]->idQuali);
				}	 
			}
			if(isset($jsonParam->SkillName))
			{
				if(sizeof($jsonParam->SkillName) > 1)
				{
					$ArraySkill = array();
					for($j = 0; $j < sizeof($jsonParam->SkillName); $j++)
					{
						array_push($ArraySkill , $jsonParam->SkillName[$j]->idSkill);
					}
					$SkillIds = join(',' , $ArraySkill);
					if ($strWHERE != "")
						$strWHERE = $strWHERE . ' AND ';
					$strWHERE = $strWHERE .  " ds.idSkill  in ($SkillIds)";
				}
				else
				{
					if ($strWHERE != "")
						$strWHERE = $strWHERE . ' AND ';
					$strWHERE = $strWHERE . " ds.idSkill = "  .($jsonParam->SkillName[0]->idSkill);
				}	 
			}
			if ($strWHERE != "")
			{
				$sqlSearchjoin = $sqlSearchjoin." WHERE ".$strWHERE." ORDER BY q.idJobDesc";
			}
			else
			{
				$sqlSearchjoin = $sqlSearchjoin." ORDER BY q.idJobDesc";
			}				
			$query = $conn->prepare($sqlSearchjoin);
			$query->execute();
			$countrow = $query->rowCount();
			$Sqlresult = $query->fetchAll( PDO::FETCH_ASSOC );
			$idJobDesc = -1;
				$j = -1;
				$finalArray = array();
				//while($row = mysql_fetch_array($Sqlresult))
				for($i =0; $i < sizeof($Sqlresult); $i++)
				{	
					if($idJobDesc != $Sqlresult[$i]['idJobDesc'])
					{
						$j++;
						$finalArray[$j]['idJobDesc'] = $Sqlresult[$i]['idJobDesc'];
						$finalArray[$j]['QualiName'] = $Sqlresult[$i]['QualiName'];
						$finalArray[$j]['SkillName'] = $Sqlresult[$i]['SkillName'];
						$finalArray[$j]['ExperianceMin'] = $Sqlresult[$i]['ExperianceMin'];
						$finalArray[$j]['ExperianceMAx'] = $Sqlresult[$i]['ExperianceMAx'];
						$finalArray[$j]['Position'] = $Sqlresult[$i]['Position'];
						$finalArray[$j]['Role'] = $Sqlresult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $Sqlresult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$idJobDesc = $Sqlresult[$i]['idJobDesc'];
					}
					if(!in_array($Sqlresult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],$Sqlresult[$i]['QualiName']);
					}
					if(!in_array($Sqlresult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$Sqlresult[$i]['SkillName']);
					}
				}
				if($countrow > 0)
				{
					$result = array('result' => 'success', 'addndata' =>$finalArray , 'CountJDRow'=>sizeof($finalArray));
				}
				else
				{
					$result = array('result' => 'error', 'addndata' => $Sqlresult);
				}
		}
		catch(Exception $e)
		{
			$Error = 'Error in sqlSearchjoin:' .$sqlSearchjoin .$e->getMessage();
		} 
	print json_encode($result);		
	}

	$jsonRequest = json_decode(file_get_contents('php://input')); // read the json input
	//decode the jsonRequest;
	switch($jsonRequest->functionCode) 
	{ 
		case "LoadQualification":
			LoadQualification();
			break;
			case "LoadSkill":
			LoadSkill();
			break;
			case "LoadPosition":
			LoadPosition();
			break;
			case "InsertJobDesc":
			$arrParams = $jsonRequest->jsonParam;
			InsertJobDesc($arrParams);
			break;	
			case "DispyJd":
			DispyJd();
			break;
			case "SearchJd":
			$arrParams = $jsonRequest->jsonParam;
			SearchJd($arrParams);
			break;
		default:
			$result = array ('result' => "error", 'addndata' => -9998);		// unknown function
			print json_encode($result);
	}	
?>	