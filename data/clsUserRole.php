<?php
Class UserRole
{
	private $userId ;
	private $roleId ;
	private $IsActive;
	private $idUserRole;
	private $roleDescription;
	
	public function setroleId($IntroleId)
	{
		$this->roleId = $IntroleId;
	}
	public function setuserId($userId)
	{
		$this->userId = $userId;
	}
	public function setIsActive($IsActive)
	{
		$this->IsActive = $IsActive;
	}
	public function setroleDescription($roleDescription)
	{
		$this->roleDescription = $roleDescription;
	}
	public function getroleId(){ return $this->roleId; }
	
	public function getUserRole(){
		$ArrUserRole = array();
		$ArrUserRole['roleId'] =$this->roleId;
		$ArrUserRole['userId'] =$this->userId;
		$ArrUserRole['IsActive'] =$this->IsActive;
		$ArrUserRole['roleDescription'] =$this->roleDescription;
		return $ArrUserRole;
	}
	
	public function save($db,$userRoleDetails){
		try
		{
		
			if($userRoleDetails->IsActive == true)
			{
				$this->IsActive = 1;
			}
			if($userRoleDetails->IsActive == false)
			{
				$this->IsActive = 0;
			}

			$sqlUserRole = "select count(*) from tbluserrole where userId = ? and roleId = ?";
			$sstmt = $db->prepare($sqlUserRole);
			$sstmt->bindParam(1, $this->userId, PDO::PARAM_INT);
			$sstmt->bindParam(2, $userRoleDetails->idRole, PDO::PARAM_INT);
			$sstmt->execute();
			$number_of_rows = $sstmt->fetchColumn(); 
			if($number_of_rows > 0)
			{
				try{
					$UserRoleUpdate = "update tbluserrole set IsActive = ? where userId = ? and roleId = ?";
					$stmt = $db->prepare($UserRoleUpdate);
					$stmt->bindParam(1, $this->IsActive, PDO::PARAM_INT);
					$stmt->bindParam(2, $this->userId, PDO::PARAM_INT);
					$stmt->bindParam(3, $userRoleDetails->idRole, PDO::PARAM_INT);
					$stmt->execute();	
					if($stmt)
					{
						return 1;
					}
					else{
						return 0;
					}
				}
				catch(Exception $e)
				{
					$errMsg = $UserRoleUpdate .   $e->getMessage() ;
					throw new Exception($errMsg);
				}  
				 
			}
			else{
					try{
						$sqlstoredUserRole = "insert into tbluserrole
									(userId, roleId, IsActive)
									values (?, ?, ?)";
						$istmt = $db->prepare($sqlstoredUserRole);
						$istmt->bindParam(1, $this->userId, PDO::PARAM_INT);
						$istmt->bindParam(2, $userRoleDetails->idRole , PDO::PARAM_INT);
						$istmt->bindParam(3, $this->IsActive, PDO::PARAM_INT);
						$istmt->execute();
						if($istmt)
						{
							return 1;
						}
						else{
							return 0;
						}
					}
					catch(Exception $e)
					{
						$errMsg = $sqlstoredUserRole .   $e->getMessage() ;
						throw new Exception($errMsg);
					} 	
			}  
		
		}
		catch(Exception $e)
		{
			$errMsg = $sqlUserRole .   $e->getMessage() ;
			throw new Exception($errMsg);
		} 
	
	}
	
	public static function getRoleRelUser($db,$idRole)
	{
		try
		{
			$sqlRole = "select u.* 
					    from tbluser u 
						join tbluserrole ur on ur.userId = u.idUser 
						where ur.roleId = '".$idRole."' and ur.IsActive = 1";
				
			$stmt = $db->prepare($sqlRole);
			$stmt->execute();
			$ArruserDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ArruserDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ("Error:Err001" . $sqlRole. " " .$e->getMessage());
		}
	}
}

?>