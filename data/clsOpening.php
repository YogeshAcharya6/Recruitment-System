<?php

class Opening
{
	private $idOpening;
	private $RRId; 
	private $PositionUId;
	
	public function setRRId($RRId)
	{
		$this->RRId = $RRId;
	}
	public function getRRId(){ return $this->RRId; }
	
	public function setPositionUId($PositionUId)
	{
		$this->PositionUId = $PositionUId;
	}
	public function getPositionUId(){ return $this->PositionUId; }
	
	public function saveOpening($db,$idRR,$Openings)
	{	
		try {
					$PositionId = 0;
					for($j=1;$j<=$Openings;$j++)
					{
						 $a = $PositionId + $j;
						 $PositionUidId = $idRR.$a;
						 $OpStatusId = 1;
						 $sqlopening = "insert into  tblopening(`RRId`,`PositionUId`,OpStatusId) values (?,?,?)";
						 $stmti = $db->prepare($sqlopening);
						 $stmti->bindParam(1, $idRR, PDO::PARAM_INT);
						 $stmti->bindParam(2, $PositionUidId, PDO::PARAM_INT);
						 $stmti->bindParam(3, $OpStatusId, PDO::PARAM_INT);
						 $stmti->execute();
					}
				return 1;
			}
			catch(Exception $e)
			{
				throw new Exception($e->getMessage());
			}
	}
	
	public function Update($db,$RRDtls)
	{	
		try {
				$OpStatusId = 1;
				$sqlOpCnt = "SELECT * FROM `tblopening` where RRId = ? and OpStatusId = ?";
						$stmts = $db->prepare($sqlOpCnt);
						$stmts->bindParam(1, $RRDtls->idRR, PDO::PARAM_INT);
						$stmts->bindParam(2, $OpStatusId, PDO::PARAM_INT);
						$stmts->execute();
						$countRow = $stmts->rowCount();
				if($countRow >= $RRDtls->Openings)
				{
					$UpdateOpeningCnt = $countRow - $RRDtls->Openings;
					for($i=0;$i<$UpdateOpeningCnt;$i++)
					{
						$sqlDeleteRow = "DELETE FROM tblopening where `RRId`= ? and OpStatusId = ?
										ORDER BY `PositionUId` DESC LIMIT 1";
						$stmtd = $db->prepare($sqlDeleteRow);
						$stmtd->bindParam(1, $RRDtls->idRR, PDO::PARAM_INT);
						$stmtd->bindParam(2, $OpStatusId, PDO::PARAM_INT);
						$stmtd->execute();
					}
					return 1;
				}
				else
				{
					$UpdateOpeningCnt = $RRDtls->Openings - $countRow;
					$sql = "SELECT max(PositionUId) as PositionUId FROM `tblopening` where RRId = ?";
						$stmt = $db->prepare($sql);
						$stmt->bindParam(1, $RRDtls->idRR, PDO::PARAM_INT);
						$stmt->execute();
						$PositionUid1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$PositionUid = $PositionUid1[0]['PositionUId'];
						$countRow1 = $stmt->rowCount();
						if($countRow1  > 0)
						{
							$lenght = strlen($RRDtls->idRR);
							$positionUidLength = strlen($PositionUid);
							$RealPosition;
							for($i = $lenght; $i < $positionUidLength; $i++ ) {
								$char = substr($PositionUid, $i, 1 );
								$RealPosition = $RealPosition.$char;
							}
							for($j=1;$j<=$UpdateOpeningCnt;$j++)
							{
								 $incrementPositionId = $RealPosition + $j;
								 $NewPositionUid = $RRDtls->idRR.$incrementPositionId;
								 $sqlopening = "insert into  tblopening(`RRId`,`PositionUId`,OpStatusId) values (?,?,?)";
								 $stmti = $db->prepare($sqlopening);
								 $stmti->bindParam(1, $RRDtls->idRR, PDO::PARAM_INT);
								 $stmti->bindParam(2, $NewPositionUid, PDO::PARAM_INT);
								 $stmti->bindParam(3, $OpStatusId, PDO::PARAM_INT);
								 $stmti->execute();
							}	
							return 1;
						}
				}		
			}
			catch(Exception $e)
			{
				throw new Exception($e->getMessage());
			}
	}
	public function getOpeningPositionCnt($db,$ArrActivePositionDtls)
	{
		try{
			$OpStatusId = 1;
			$ArrRROpeningCnt = array();
			for($i=0;$i<sizeof($ArrActivePositionDtls);$i++)
			{
				$sqlOpCnt = "SELECT count(*) as count,`RRId` FROM `tblopening` WHERE `RRId` = ? and OpStatusId = ?";
						$stmts = $db->prepare($sqlOpCnt);
						$stmts->bindParam(1, $ArrActivePositionDtls[$i]['idRR'], PDO::PARAM_INT);
						$stmts->bindParam(2, $OpStatusId, PDO::PARAM_INT);
						$stmts->execute();
						array_push($ArrRROpeningCnt,$stmts->fetchAll(PDO::FETCH_ASSOC));
						
			}
			return $ArrRROpeningCnt;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
}
?>
