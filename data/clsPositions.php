<?php
Class Positions
{
	private $idPosition;	
	private $Position;
	
	public function loadPositionDtls( $db )
	{
		try
		{
			$sqlPositions = "select idPosition, Position from dimposition ORDER BY Position ASC";
			$ArrPositions = array();	
			$stmt = $db->prepare($sqlPositions);
			$stmt->execute();
			$ArrPositionsDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			for($i = 0; $i < sizeof($ArrPositionsDtls); $i++){
				$idPosition = $ArrPositionsDtls[$i]['idPosition'] ;
				$Position = $ArrPositionsDtls[$i]['Position'] ;
				$objPosition = new Position();
				$objPosition->setidPosition($idPosition);
				$objPosition->setPosition($Position);
				array_push($ArrPositions, $objPosition->getPositions());
			}
			return  $ArrPositions;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlPositions. " " .$e->getMessage());
		}
	}
	
}
?>	