<?php
Class Skills
{		
	private $idSkill;	
	private $SkillName;
	
	public function loadSkillDtls( $db )
	{
		try
		{
			$sqlSkills = "select idSkill, SkillName from dimskill ORDER BY SkillName ASC";
			$ArrSkills = array();	
			$stmt = $db->prepare($sqlSkills);
			$stmt->execute();
			$ArrSkillsDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			for($i = 0; $i < sizeof($ArrSkillsDtls); $i++){
				$idSkill = $ArrSkillsDtls[$i]['idSkill'] ;
				$SkillName = $ArrSkillsDtls[$i]['SkillName'] ;
				$objSkill = new Skill();
				$objSkill->setidSkill($idSkill);
				$objSkill->setSkillName($SkillName);
				array_push($ArrSkills, $objSkill->getSkills());
			} 
			return  $ArrSkillsDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlSkills. " " .$e->getMessage());
		}
	}
	
	public function loadNonTechSkillDtls( $db )
	{
		try
		{
			$sqlNonTechSkills = "select idNonTechSkill, NonTechSkill from dimnontechskill ORDER BY NonTechSkill ASC";
			$stmt = $db->prepare($sqlNonTechSkills);
			$stmt->execute();
			$ArrNonTechSkills = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return  $ArrNonTechSkills;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlNonTechSkills. " " .$e->getMessage());
		}
	}
	
}
?>	