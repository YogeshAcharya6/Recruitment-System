<?php

class SearchRecruitmentRequest
{
	private $idDept;
	private $idStatus;
	private $clouser;
	private $request;
	private $EmpId;
	public function setidDept($idDept){
		$this->idDept = $idDept ;
	}

	public function getidDept() { return $this->idDept ;}
	
	public function setEmpId($EmpId){
		$this->EmpId = $EmpId ;
	}
	public function getEmpId() { return $this->EmpId ;}
	
	public function setidStatus($idStatus){
		$this->idStatus = $idStatus;
		
	}
	public function getidStatus() { return $this->idStatus ; }
	
	public function setclouser($clouser){
		$this->clouser = $clouser ;
	}
	public function getclouser() { return $this->clouser ; }

	public function setrequest($request){
		$this->request = $request ;
	}
	public function getrequest(){ return $this->request ; }
	
	//***************************************************************************
	
	public function getRRSqlStatement(){
		try{
		
		$SearchRRSql = "select rr.idRR,rr.EmpId,rr.DeptId,rr.Openings,rr.JobDescId,rr.RequestDate
						,rr.ExpectedDate,rr.SalaryMin,rr.SalaryMax,rr.Status,p.idPosition,p.Position,
						d.idDept,d.DeptName,s.idStatus,s.Status
						from tblrr rr
						join tbljobdesc jd on jd.idJobDesc = rr.JobDescId
						join dimposition p on p.idPosition = jd.PositionId
						join dimdept d on d.idDept = rr.DeptId
						join dimstatus s on s.idStatus = rr.Status";
			$strwhere = '';
			
			if (isset($this->idDept) && $this->idDept != "") 
			{
				if ($strwhere != ""){
					$strwhere = $strwhere." AND rr.DeptId = '".$this->idDept."'";
				}
				else{ 
					$strwhere = " rr.DeptId = '".$this->idDept."'";
				}
			}
			if (isset($this->EmpId) && $this->EmpId != "") 
			{	
				if ($strwhere != ""){
					 $strwhere = $strwhere." AND  rr.EmpId = '".$this->EmpId."'";
				}
				else{
					 $strwhere = "rr.EmpId = '".$this->EmpId."'";
				}
			}
			if (isset($this->idStatus) && $this->idStatus != "") 
			{	
				if ($strwhere != ""){
					 $strwhere = $strwhere." AND  rr.Status = '".$this->idStatus."'";
				}
				else{
					 $strwhere = "rr.Status = '".$this->idStatus."'";
				}
			}
			if (isset($this->clouser) && $this->clouser != "") 
			{	
				if ($strwhere != ""){
					 $strwhere = $strwhere." AND  rr.ExpectedDate = '".$this->clouser."'";
				}
				else{
					 $strwhere = "rr.ExpectedDate = '".$this->clouser."'";
				}
			}
			if (isset($this->request) && $this->request != "") 
			{	
				if ($strwhere != ""){
					 $strwhere = $strwhere." AND  rr.RequestDate = '".$this->request."'";
				}
				else{
					 $strwhere = "rr.RequestDate = '".$this->request."'";
				}
			}
			if ($strwhere != ""){
				$SearchRRSql = $SearchRRSql." where ".$strwhere;
			}	
			else{
				$SearchRRSql = $SearchRRSql;
			}
			return $SearchRRSql;
		}
		catch(Exception $e){
			$errMsg = "Err001: ".  $SearchRRSql . $e->getMessage();
			throw new Exception($errMsg);
		}
	}
	
	public function Search($db, $SqlStatement)
	{
		try
		{	
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$ArrRRDescDtls = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			return  $ArrRRDescDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err002" . $SqlStatement. " " .$e->getMessage());
		}
	}
	public function getRRDtls($db, $SqlStatement, $LoginTime)
	{
		try
		{	
			$SqlStatement = $SqlStatement." where rr.Regdate < '".$LoginTime."'";
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$ArrRRDescDtls = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			return  $ArrRRDescDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err002" . $SqlStatement. " " .$e->getMessage());
		}
	}
	public function getAddRRDtls($db, $SqlStatement, $LoginTime)
	{
		try
		{	
			$SqlStatement = $SqlStatement." where rr.Regdate > '".$LoginTime."'";
			$sth = $db->prepare($SqlStatement);
			$sth->execute();
			$ArrAddRRDescDtls = $sth->fetchAll(PDO::FETCH_ASSOC);
			
			return  $ArrAddRRDescDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Err002" . $SqlStatement. " " .$e->getMessage());
		}
	}
	
	public function getActivePositionDtls($db){
		try{
		$SearchRRSql = "select rr.idRR,rr.EmpId,rr.DeptId,rr.Openings,rr.JobDescId,rr.RequestDate
						,rr.ExpectedDate,rr.SalaryMin,rr.SalaryMax,rr.Status,p.idPosition,p.Position,
						d.idDept,d.DeptName,s.idStatus,s.Status,jd.Role,jd.Responsibility
						from tblrr rr
						join tbljobdesc jd on jd.idJobDesc = rr.JobDescId
						join dimposition p on p.idPosition = jd.PositionId
						join dimdept d on d.idDept = rr.DeptId
						join dimstatus s on s.idStatus = rr.Status
						where rr.Status = 1 or rr.Status = 2";
		$stmt = $db->prepare($SearchRRSql);
		$stmt->execute();
		$ArrPositionDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return  $ArrPositionDtls;					
		}
		catch(Exception $e){
			
			throw new Exception($errMsg);
		
		}
	}
}

?>