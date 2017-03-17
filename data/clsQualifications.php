<?php
Class Qualifications
{				
	private $idQuali;	
	private $QualiName;
	
	public function loadQualificationDtls($db)
	{
		try
		{
			$sqlQualifications = "select idQuali,QualiName from dimqualification ORDER BY QualiName ASC";
			$ArrQualifications = array();	
			$stmt = $db->prepare($sqlQualifications);
			$stmt->execute();
			$ArrQualificationsDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			for($i = 0; $i < sizeof($ArrQualificationsDtls); $i++){
				$idQuali = $ArrQualificationsDtls[$i]['idQuali'] ;
				$QualiName = $ArrQualificationsDtls[$i]['QualiName'] ;
				$objQualification = new Qualification();
				$objQualification->setidQuali($idQuali);
				$objQualification->setQualiName($QualiName);
				array_push($ArrQualifications, $objQualification->getQualifications());
			}  
			return  $ArrQualifications;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlQualifications. " " .$e->getMessage());
		}
	}
	
}
?>	