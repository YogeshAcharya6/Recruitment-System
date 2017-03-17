<?php
Class Roles
{
	private $idRole;
	private $roleDescription;
	
	public function loadRoleDtls( $db )
	{
		try
		{
			$sqlRoles = "select idrole, roleDescription from dimrole ORDER BY roleDescription ASC";
			$ArrRoles = array();	
			$stmt = $db->prepare($sqlRoles);
			$stmt->execute();
			$ArrRolesDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			for($i = 0; $i < sizeof($ArrRolesDtls); $i++){
				$idrole = $ArrRolesDtls[$i]['idrole'] ;
				$roleDescription = $ArrRolesDtls[$i]['roleDescription'] ;
				$objRole = new Role();
				$objRole->setroleId($idrole);
				$objRole->setroleDescription($roleDescription);
				array_push($ArrRoles, $objRole->getRole());
			}
			return  $ArrRoles;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlRoles. " " .$e->getMessage());
		}
	}
	
}
?>	