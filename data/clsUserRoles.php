<?php
class UserRoles
{
	private $idUserRole;
	private $userId;
	private $roleId;
	
	public function __construct($idUser)
	{
		$this->userId = $idUser;	
	}	

	public function getUserRolesDtls($db) {
		try{
			$isActive = 1;
			$sqlUserRole = "SELECT tbluserrole.userId,tbluserrole.roleId,tbluserrole.IsActive,dimrole.roleDescription 
							FROM tbluserrole
							INNER JOIN dimrole ON tbluserrole.roleId = dimrole.idRole
							where tbluserrole.userId = ? and tbluserrole.IsActive = ? order by tbluserrole.roleId";	
			$stmt = $db->prepare($sqlUserRole);
			$stmt->bindParam(1, $this->userId , PDO::PARAM_INT);
			$stmt->bindParam(2, $isActive , PDO::PARAM_INT);
			$stmt->execute();
			$ArrUserRolesDtls = array();
			//return $this->idUser;
			$ArrUserRoles = $stmt->fetchAll(PDO::FETCH_ASSOC);
			for($i = 0; $i < sizeof($ArrUserRoles); $i++){
				$userId = $ArrUserRoles[$i]['userId'] ;
				$roleId = $ArrUserRoles[$i]['roleId'] ;
				$IsActive = $ArrUserRoles[$i]['IsActive'] ;
				$roleDescription = $ArrUserRoles[$i]['roleDescription'] ;
				$objUserRole = new UserRole();
				$objUserRole->setroleId($roleId);
				$objUserRole->setuserId($userId);
				$objUserRole->setIsActive($IsActive);
				$objUserRole->setroleDescription($roleDescription);
				
				array_push($ArrUserRolesDtls, $objUserRole->getUserRole());
			}	   
			return $ArrUserRolesDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlUserRole. "iduser".$this->idUser  .$e->getMessage());
		}
	}
}
?>