<?php
class Elements
{
	private $idElementType;
	private $elementType;
	
	public function loadElementTypeDtls($db)
	{
		try
		{
			$sqlElement = "select idElementType,elementType from dimelementtype ORDER BY elementType ASC";
			$stmt = $db->prepare($sqlElement);
			$ArrElementList = array();
			$stmt->execute();
			$arrElementDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			for($i = 0; $i < sizeof($arrElementDtls); $i++){
				$idElementType = $arrElementDtls[$i]['idElementType'] ;
				$elementType = $arrElementDtls[$i]['elementType'] ;
				$objElement = new Element();
				$objElement->setidElementType($idElementType);
				$objElement->setelementType($elementType);
				array_push($ArrElementList, $objElement->getElementDtls());
			}	
			
			return $ArrElementList;
		}
		catch(PDOException $e)
		{
			throw new Exception ("Error:Err001" . $sqlElement. " " .$e->getMessage());
		}
	}

	public function getAllElementList($db)
	{
		try
		{
			$sqlElement = "select idElement,elementTypeId, elementDesc from tblelements ORDER BY idElement ASC";
			$stmt = $db->prepare($sqlElement);
			$ArrElementList = array();
			$stmt->execute();
			$arrElementDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			  for($i = 0; $i < sizeof($arrElementDtls); $i++){
				$idElement = $arrElementDtls[$i]['idElement'] ;
				$elementTypeId = $arrElementDtls[$i]['elementTypeId'] ;
				$elementDesc = $arrElementDtls[$i]['elementDesc'] ;
				$objElement = new Element();
				$objElement->setidElement($idElement);
				$objElement->setelementTypeId($elementTypeId);
				$objElement->setelementDesc($elementDesc);
				array_push($ArrElementList, $objElement->getElementList());
			}	  
			
			return $ArrElementList;
		}
		catch(PDOException $e)
		{
			throw new Exception ("Error:Err001" . $sqlElement. " " .$e->getMessage());
		}
	}
}


?>