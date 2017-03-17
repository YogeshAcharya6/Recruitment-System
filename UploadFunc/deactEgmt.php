<?php 
include('../data/db/connection.php');
function deactEngagement()
{
	$db = getConnection();	
	$today = date('Y-m-d');	

	$sqlEgmt = "select idEgmt from tblengagement 
					WHERE egmtEndDate = '2015-12-31'";
					
				$stmt = $db->query($sqlEgmt);	
				$resultEgmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$dateArray = array();
				
		if(sizeof($resultEgmt) > 0){
				for($i = 0; $i < sizeof($resultEgmt); $i++){
					array_push($dateArray,$resultEgmt[$i]['idEgmt']);
				}
				$ids = join(',',$dateArray);  
				$sqlUpdateEgmt = "UPDATE tblengagement
				SET egmtIsActive = '2'
				WHERE idEgmt in ($ids)";
				$stmt = $db->query($sqlUpdateEgmt);
				$sqlEgmtCnsl = "UPDATE tblegmtcnsl
							SET 
							isActive = '2'
							WHERE egmtId in ($ids)";
				$sqlEgmtAppr = "UPDATE tblegmtappr
							SET 
							isApprIsActive = '2'
							WHERE egmtId in ($ids)";
						
				try
				{	
					$stmt2 = $db->query($sqlEgmtCnsl); 				
					$stmt3 = $db->query($sqlEgmtAppr); 				
					$result = array('result' => 'success','message'=>'Engangement Deactivated Successfully');
				}
				catch(PDOException $e)
				{
					$result = array('result' => 'error','message'=>$e->getMessage());
				}
		}
}
deactEngagement();
?>