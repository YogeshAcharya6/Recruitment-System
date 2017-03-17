<?php

class SearchJobDescription
{
	private $QualiName;
	private $SkillName;
	private $maxexp;
	private $minexp;
	public function setQualiName($QualiName){
		$this->QualiName = $QualiName ;
	}

	public function getQualiName() { return $this->QualiName ;}
	
	public function setSkillName($SkillName){
		$this->SkillName = $SkillName;
		
	}
	public function getSkillName() { return $this->SkillName ; }
	
	public function setmaxexp($maxexp){
		$this->maxexp = $maxexp ;
	}
	public function getmaxexp() { return $this->maxexp ; }

	public function setminexp($minexp){
		$this->minexp = $minexp ;
	}
	public function getminexp(){ return $this->minexp ; }
	
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
			
					$SQLQual =  " jq.QualiId in ($QualIds)";
				}
				else{
					$SQLQual = " jq.QualiId = ".($this->QualiName[0]->idQuali);
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
			
					$SQLSkill =  "js.SkillId in ($SkillIds)";
				}
				else{
					$SQLSkill = "js.SkillId = ".($this->SkillName[0]->idSkill);
				}	 
				return $SQLSkill;
			}		
		}
		catch(Exception $e)
		{
			throw new Exception ("Err001" . $SQLSkill. " " .$e->getMessage());
		}	
		
	}
	
	public function getJobDescSqlStatement(){
		try{
		
		$SearchJobSql = "select j.idJobDesc, j.PositionId, j.ExperianceMin, j.ExperianceMAx, j.Role,
						j.Responsibility , p.Position , js.SkillId, jq.QualiId,
						s.SkillName,q.QualiName
						from tbljobdesc j 
						join dimposition p on p.idPosition = j.PositionId 
						join tbljdskill js on js.JobDescId = j.idJobDesc
						join tbljdquali jq on jq.JobDescId = j.idJobDesc 
						join dimskill s on s.idSkill = js.SkillId 
						join dimqualification q on q.idQuali = jq.QualiId";
							
			$strwhere = '';
			
			if (isset($this->maxexp) && $this->maxexp != "") 
			{
				if ($strwhere != ""){
					$strwhere = $strwhere." AND j.ExperianceMAx = '".$this->maxexp."'";
				}
				else{ 
					$strwhere = " j.ExperianceMAx = '".$this->maxexp."'";
				}
			}
			if (isset($this->minexp) && $this->minexp != "") 
			{	
				if ($strwhere != ""){
					 $strwhere = $strwhere." AND  j.ExperianceMin = '".$this->minexp."'";
				}
				else{
					 $strwhere = "j.ExperianceMin = '".$this->minexp."'";
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
				$SearchJobSql = $SearchJobSql. " where ".$strwhere." and js.IsActive = 1 and jq.IsActive = 1";
			}	
			else{
				$SearchJobSql = $SearchJobSql." where js.IsActive = 1 and jq.IsActive = 1";
			}
			return $SearchJobSql;
		}
		catch(Exception $e){
			$errMsg = "Err005: ".  $SearchJobSql . $e->getMessage();
			throw new Exception($errMsg);
		}
	}
	
	public function Search($db, $SqlStatement)
	{
		try
		{	
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$ArrJobDescDtls = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			return  $ArrJobDescDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err006" . $SqlStatement. " " .$e->getMessage());
		}
	}
	public function getJDDtls($db, $SqlStatement, $LoginTime)
	{
		try
		{	
			$SqlStatement = $SqlStatement." and j.RegDate < '".$LoginTime."'";
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$ArrJobDescDtls = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			return  $ArrJobDescDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err006" . $SqlStatement. " " .$e->getMessage());
		}
	}
	public function getAddJDDtls($db, $SqlStatement, $LoginTime)
	{
		try
		{	
			$SqlStatement = $SqlStatement." and j.RegDate > '".$LoginTime."'";
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$ArrJobDescDtls = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			return  $ArrJobDescDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err006" . $SqlStatement. " " .$e->getMessage());
		}
	}
}

?>