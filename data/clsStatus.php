<?php
Class Status
{
	private $idStatus;	
	private $Status;
	
	public function loadStatusDtls( $db )
	{
		try
		{
			$sqlStatus = "select idStatus, Status from dimstatus ORDER BY Status ASC";
			$stmt = $db->prepare($sqlStatus);
			$stmt->execute();
			$ArrStatusDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return  $ArrStatusDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlStatus. " " .$e->getMessage());
		}
	}
	
}
?>	