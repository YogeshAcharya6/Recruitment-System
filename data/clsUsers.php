<?php
class Users{
	
	private $idUser;
	private $userId;
	private $idReg;
	private $Name;
	private $EmpId;

	public function loadUserDtls( $db )
	{
		try
		{
			$sqlUsers = "select idUser, userId from tbluser where IsActive = '1' ORDER BY userId ASC";
			$ArrUsers = array();	
			$stmt = $db->prepare($sqlUsers);
			$stmt->execute();
			$ArrUsersDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			for($i = 0; $i < sizeof($ArrUsersDtls); $i++){
				$idUser = $ArrUsersDtls[$i]['idUser'] ;
				$userId = $ArrUsersDtls[$i]['userId'] ;
				$objUser = new User();
				$objUser->setidUser($idUser);
				$objUser->setuserId($userId);
				array_push($ArrUsers, $objUser->getUser());
			}
			return  $ArrUsers;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlRoles. " " .$e->getMessage());
		}
	}
	
	public function loadEmployeeDtls( $db )
	{
		try
		{
			$sqlUsers = "select * from tblregistration order by Name";
			$ArrUsers = array();	
			$stmt = $db->prepare($sqlUsers);
			$stmt->execute();
			$ArrUsersDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return  $ArrUsersDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlRoles. " " .$e->getMessage());
		}
	}
}
?>