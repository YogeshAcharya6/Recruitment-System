<?php

class SearchCandidate
{
	private $QualiName;
	private $SkillName;
	private $TotalExp;
	private $CurrentSalary;
	private $CandidateName;
	public function setQualiName($QualiName){
		$this->QualiName = $QualiName ;
	}

	public function getQualiName() { return $this->QualiName ;}
	
	public function setSkillName($SkillName){
		$this->SkillName = $SkillName;
		
	}
	public function getSkillName() { return $this->SkillName ; }
	
	public function setTotalExp($TotalExp){
		$this->TotalExp = $TotalExp ;
	}
	public function getTotalExp() { return $this->TotalExp ; }

	public function setCurrentSalary($CurrentSalary){
		$this->CurrentSalary = $CurrentSalary ;
	}
	public function getCurrentSalary(){ return $this->CurrentSalary ; }
	
	public function setCandidateName($CandidateName){
		$this->CandidateName = $CandidateName ;
	}
	public function getCandidateName(){ return $this->CandidateName ; }
	
	//***************************************************************************
	//get item idUnit of measurement
	public function getSQLQual()
	{
		try{
			if(isset($this->QualiName) ){
				if(sizeof($this->QualiName) > 1){
					$ArrayQual = array();
					
					for($i = 0; $i < sizeof($this->QualiName); $i++){
						array_push($ArrayQual ,$this->QualiName[$i]->idQuali);
					}

					$QualIds = join(',' , $ArrayQual);
			
					$SQLQual =  " q.QualiId in ($QualIds)";
				}
				else{
					$SQLQual = " q.QualiId = ".($this->QualiName[0]->idQuali);
				}	
				return $SQLQual;
			}		
		}
		catch(Exception $e)
		{
			throw new Exception ("Err001" . $SQLQual. " " .$e->getMessage());
		}	
		
	}	
	
	public function getSQLSkillName()
	{
		try{
			if(isset($this->SkillName) ){
				if(sizeof($this->SkillName) > 1){
					$ArraySkill = array();
					
					for($i = 0; $i < sizeof($this->SkillName); $i++){
						array_push($ArraySkill ,$this->SkillName[$i]->idSkill);
					}
					$SkillIds = join(',' , $ArraySkill);
			
					$SQLSkill =  "s.SkillId in ($SkillIds)";
				}
				else{
					$SQLSkill = "s.SkillId = ".($this->SkillName[0]->idSkill);
				}	 
				return $SQLSkill;
			}		
		}
		catch(Exception $e)
		{
			throw new Exception ("Err001" . $SQLSkill. " " .$e->getMessage());
		}	
		
	}
	
	public function getCandidateSqlStatement(){
		try{
		
		$SearchCandidateSql = "select c.*,q.idCandiQuali,s.idCandiSkill, 
		q.QualiId,dq.QualiName,ds.SkillName,dc.Certification,dnt.NonTechSkill,cs.idCandidateStatus,cs.candidateStatusDesc 
		from  tblcandidate as c 
		JOIN  tblcandiquali as q ON q.CandidateId = c.idCandidate
		JOIN dimqualification as dq ON dq.idQuali = q.QualiId 
		JOIN tblcadiskill as s ON s.CandidateId = c.idCandidate 
		JOIN dimskill as ds ON ds.idSkill = s.SkillId 
		JOIN tblcertification as tc ON tc.CandidateId = c.idCandidate
		JOIN dimcertification as dc ON dc.idCertification =  tc.CertificationId 
		JOIN tblnontechskill as tns ON tns.CandidateId = c.idCandidate 
		JOIN dimnontechskill as dnt ON dnt.idNonTechSkill =  tns.dimNonTechSkillId
		JOIN dimcandidatestatus as cs ON cs.idCandidateStatus =  c.CandidateStatusId";
							
			$strwhere = '';
			/* /* `Resume``CurrentCompany``CurrentDesignation`
	`CurrentSalary``NoticePeriod``Role``Responsibility`
	`TotalExp``RelevantExp``MobNo``AltContactNo``EmailId`
	`Address``CandidateName``idCandidate` */ 
			if (isset($this->TotalExp) && $this->TotalExp != "") 
			{
				if ($strwhere != ""){
					$strwhere = $strwhere." AND c.TotalExp = '".$this->TotalExp."'";
				}
				else{ 
					$strwhere = " c.TotalExp = '".$this->TotalExp."'";
				}
			}
			if (isset($this->CurrentSalary) && $this->CurrentSalary != "") 
			{	
				if ($strwhere != ""){
					 $strwhere = $strwhere." AND  c.CurrentSalary = '".$this->CurrentSalary."'";
				}
				else{
					 $strwhere = "c.CurrentSalary = '".$this->CurrentSalary."'";
				}
			}
			if(isset($this->CandidateName) && $this->CandidateName != "") 
			{	
				if ($strwhere != ""){
					 $strwhere = $strwhere." AND  c.CandidateName = '".$this->CandidateName."'";
				}
				else{
					 $strwhere = "c.CandidateName = '".$this->CandidateName."'";
				}
			}
			
			if (isset($this->QualiName) && $this->QualiName != "" ) 
			{
				if ($strwhere != ""){
					$strwhere = $strwhere." AND  " . $this->getSQLQual() ; 
				}
				else{
					$strwhere = $this->getSQLQual(); 
				}
			} 	
			
			if (isset($this->SkillName) &&  $this->SkillName != "" )
			{  
				if ($strwhere != ""){
					$strwhere = $strwhere." AND  ". $this->getSQLSkillName() ." "; 
				}
				else{
					$strwhere = $this->getSQLSkillName(); 
				}
			} 	 
			
			if ($strwhere != ""){
				$SearchCandidateSql = $SearchCandidateSql. " where ".$strwhere." and (cs.idCandidateStatus = 1 or cs.idCandidateStatus = 4)";
			}	
			else{
				$SearchCandidateSql = $SearchCandidateSql. " where (cs.idCandidateStatus = 1 or cs.idCandidateStatus = 4)";
			}
			return $SearchCandidateSql;
		}
		catch(Exception $e){
			$errMsg = "Err005: ".  $SearchCandidateSql . $e->getMessage();
			throw new Exception($errMsg);
		}
	}
	
	public function Search($db, $SqlStatement)
	{
		try
		{	
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$Sqlresult = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $Sqlresult;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err006" . $SqlStatement. " " .$e->getMessage());
		}
	}
	public function getCandidateDtls($db, $SqlStatement, $LoginTime)
	{
		try
		{	
			$SqlStatement = $SqlStatement." and c.RegDate < '".$LoginTime."'";
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$Sqlresult = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $Sqlresult;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err006" . $SqlStatement. " " .$e->getMessage());
		}
	}
	public function getAddCandidateDtls($db, $SqlStatement, $LoginTime)
	{
		try
		{	
			$SqlStatement = $SqlStatement." and c.RegDate > '".$LoginTime."'";
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$Sqlresult = $sth->fetchAll(PDO::FETCH_ASSOC);
			return $Sqlresult;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err006" . $SqlStatement. " " .$e->getMessage());
		}
	}
}

?>