<?php

class Designation
{
	private $idDesignation;
	private $Designation; 
	

	public function setidDesignation($idDesignation)
	{
		$this->idDesignation = $idDesignation;
	}
	
	public function getidDesignation(){ return $this->idDesignation; }
	
	public function setDesignation($Designation)
	{
		$this->Designation = $Designation;
	}
	
	public function getDesignation(){ return $this->Designation; }
	
	
	
	public function loadDesignationList($db)
	{
		try {
			$sqlDesignationList = "select idDesignation,Designation from dimdesignation order by idDesignation"; 
			$stmt = $db->prepare($sqlDesignationList);
			$stmt->execute();
			$ArrDesignationDtls = array();
			$ArrDesignationDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ArrDesignationDtls;
		}
		catch(Exception $e)
		{
			$errMsg = "Err001: ".    $e->getMessage() . "\n";
			throw new Exception($errMsg);
		}   
	}

}
?>
