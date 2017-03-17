<?php
class Qualification
{
	private $idQuali;	
	private $QualiName;
	
	
	public function setidQuali($idQuali)
	{
		$this->idQuali = $idQuali;
	}
	
	public function getidQuali(){ return $this->idQuali; }
	
	public function setQualiName($QualiName)
	{
		$this->QualiName = $QualiName;
	}
	
	public function getQualiName(){ return $this->QualiName; }
	
	public function getQualifications(){
		$ArrQualification = array();
		$ArrQualification['idQuali'] = $this->idQuali;
		$ArrQualification['QualiName'] = $this->QualiName;
		return $ArrQualification;
	}

}
?>