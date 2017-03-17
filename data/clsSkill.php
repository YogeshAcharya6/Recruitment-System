<?php
class Skill
{
	private $idSkill;	
	private $SkillName;

	public function setidSkill($idSkill)
	{
		$this->idSkill = $idSkill;
	}
	
	public function getidSkill(){ return $this->idSkill; }
	
	public function setSkillName($SkillName)
	{
		$this->SkillName = $SkillName;
	}
	
	public function getSkillName(){ return $this->SkillName; }
	
	public function getSkills(){
		$ArrSkill = array();
		$ArrSkill['idSkill'] = $this->idSkill;
		$ArrSkill['SkillName'] = $this->SkillName;
		return $ArrSkill;
	}

}
?>