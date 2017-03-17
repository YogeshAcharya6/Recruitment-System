<?php

class Department
{

	private $idDept;
	private $DeptName; 
	

	public function setidDept($idDept)
	{
		$this->idDept = $idDept;
	}
	
	public function getidDept(){ return $this->idDept; }
	
	public function setDeptName($DeptName)
	{
		$this->DeptName = $DeptName;
	}
	
	public function getDeptName(){ return $this->DeptName; }
	
	
	
	public function loadDepartmentList($db)
	{
		try {
			$sqlgetDeptList = "select idDept,DeptName from dimdept "; 
			$stmt = $db->prepare($sqlgetDeptList);
			$stmt->execute();
			$ArrDepartmentDtls = array();
			$ArrDepartmentDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ArrDepartmentDtls;
		}
		catch(Exception $e)
		{
			$errMsg = "Err001: ".    $e->getMessage() . "\n";
			throw new Exception($errMsg);
		}   
	}

}
?>
