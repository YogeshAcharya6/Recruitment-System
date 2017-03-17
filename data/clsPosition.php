<?php
class Position
{
	private $idPosition;	
	private $Position;
	
	
	public function setidPosition($idPosition)
	{
		$this->idPosition = $idPosition;
	}
	
	public function getidPosition(){ return $this->idPosition; }
	
	public function setPosition($Position)
	{
		$this->Position = $Position;
	}
	
	public function getPosition(){ return $this->Position; }
	
	public function getPositions(){
		$ArrPosition = array();
		$ArrPosition['idPosition'] = $this->idPosition;
		$ArrPosition['Position'] = $this->Position;
		return $ArrPosition;
	}
	
	public function Save($db,$position)
	{
		try{
			$positionName = strtolower(trim($position));;
			$sqlPosition = "SELECT Position FROM `dimposition` 
			WHERE LCASE(REPLACE(`Position` ,  ' ',  ' ' ) ) = ?";
			$stmts = $db->prepare($sqlPosition);
			$stmts->bindParam(1, $positionName, PDO::PARAM_STR, 64);
			$stmts->execute();
			$countRow = $stmts->rowCount();
			if($countRow > 0)
			{
				return 0;
			}
			else
			{
				try{
					$sql = "insert into dimposition(Position) values ('".$position."')";
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$lastInsertId = $db->lastInsertId();
					if($lastInsertId > 0)
					{
						return $lastInsertId;
					}
					
				}
				catch(Exception $e)
				{
					throw new Exception($e->getMessage());
				}		
			}
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}			
	}
	public function Update($db)
	{
		try{
			$sqlPosition = "update `dimposition` set Position = ? where idPosition = ?";
			$stmt = $db->prepare($sqlPosition);
			$stmt->bindParam(1, $this->Position, PDO::PARAM_STR, 64);
			$stmt->bindParam(2, $this->idPosition, PDO::PARAM_INT);
			$stmt->execute();
			return 1;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}			
	}

}
?>