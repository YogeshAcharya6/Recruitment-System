<?php
class Role
{
	private $idRole;
	private $roleDescription ;
	
	public function setroleId($idRole)
	{
		$this->idRole = $idRole;
	}
	
	public function getroleId(){ return $this->idRole; }
	
	public function setroleDescription($roleDescription)
	{
		$this->roleDescription = $roleDescription;
	}
	
	public function getRoleName(){ return $this->roleDescription; }
	
	public function getRole(){
		$ArrRole = array();
		$ArrRole['idRole'] = $this->idRole;
		$ArrRole['roleDescription'] = $this->roleDescription;
		$ArrRole['IsActive'] = '0';
		return $ArrRole;
	}
	
	public function Save($db)
	{
		try
		{
			$cnt = 0;
			$sql = "select roleDescription from dimrole where roleDescription = ?";
			$sql = $sql;
			$stmt = $db->prepare($sql);
			$stmt->bindParam(1, $this->roleDescription, PDO::PARAM_STR,24);
			$stmt->execute();
			while ($result = $stmt->fetch()) {
				$cnt++;
			}
			if($cnt > 0)
			{
				return 0;	
			}
			else
			{
				try
				{
					$SqlRole = "insert into dimrole (roleDescription) values ( ? )";	
					$stmt = $db->prepare($SqlRole);
					$stmt->bindParam(1, $this->roleDescription, PDO::PARAM_STR,24);
					$stmt->execute();
					return 1;
				}
				catch(Exception $e)
				{
					throw new Exception ('Error in SQL:ErrI002' . $SqlRole . " " . $e->getMessage());
				}	
			}
		} 
		catch(Exception $e)
		{
			throw new Exception ('Error in SQL:ErrI002' . $sql . " " . $e->getMessage());
		}	
	} 
	
	public function Update($db)
	{
		try
		{
			$cnt = 0;
			$sql = "select roleDescription from dimrole where roleDescription = ?";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(1, $this->roleDescription, PDO::PARAM_STR,24);
			$stmt->execute();
			while ($result = $stmt->fetch()) {
				$cnt++;
			}
			if($cnt > 0)
			{
				return 0;	
			}
			else
			{
					try
					{
						$SqlRoleUpdate = "update dimrole set 
										roleDescription = ?
										where idrole = ?";	
						$stmt = $db->prepare($SqlRoleUpdate);
						$stmt->bindParam(1, $this->roleDescription, PDO::PARAM_STR,24);
						$stmt->bindParam(2,  $this->idRole, PDO::PARAM_STR,24);
						$stmt->execute();
						return 1;
					}
					catch(Exception $e)
					{
						throw new Exception ('Error in SQL:ErrI003' . $SqlRoleUpdate . " " . $e->getMessage());
					}
			}
			 
		}
		catch(Exception $e)
		{
			throw new Exception ('Error in SQL:ErrI002' . $sql . " " . $e->getMessage());
		}	
	}
	public function loadRolePermission($db)
	{
		try
		{
			$IsPermitted = 1;
			$sql = "select rp.idRollPerm,rp.roleId,rp.elementId,rp.IsPermitted,e.idElement,
					e.elementTypeId,e.elementDesc
					from tblrolepermissions rp
					join tblelements e on rp.elementId = e.idElement 
					where rp.IsPermitted = ? and rp.roleId = ? ORDER BY e.idElement";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(1, $IsPermitted, PDO::PARAM_INT);
			$stmt->bindParam(2, $this->idRole, PDO::PARAM_INT);
			$stmt->execute();
			$ArrRolepermissionDtls = array();
			$ArrRolepermissionDtls = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $ArrRolepermissionDtls;
		}
		catch(Exception $e)
		{
			throw new Exception ('Error in SQL:ErrI005' . $sql . " " . $e->getMessage());
		}	
	}
	public function saveRolePerm($db,$RolePermDetails){
		try
		{
			$sqlRolePerm = "select count(*) from tblrolepermissions where roleId = ? and elementId = ?";
			$sstmt = $db->prepare($sqlRolePerm);
			$sstmt->bindParam(1, $RolePermDetails->roleId, PDO::PARAM_INT);
			$sstmt->bindParam(2, $RolePermDetails->idElement, PDO::PARAM_INT);
			$sstmt->execute();
			$number_of_rows = $sstmt->fetchColumn(); 
			if($number_of_rows > 0)
			{
				try{
					$RolePermUpdate = "update tblrolepermissions set IsPermitted = ? where roleId = ? and elementId = ?";
					$stmt = $db->prepare($RolePermUpdate);
					$stmt->bindParam(1, $RolePermDetails->IsPermitted, PDO::PARAM_INT);
					$stmt->bindParam(2, $RolePermDetails->roleId, PDO::PARAM_INT);
					$stmt->bindParam(3, $RolePermDetails->idElement, PDO::PARAM_INT);
					$stmt->execute();	
					
					return 1;
					
				}
				catch(Exception $e)
				{
					$errMsg = $RolePermUpdate .   $e->getMessage() ;
					throw new Exception($errMsg);
				}  
				 
			}
			else{
					try{
						$sqlstoredRolePerm = "insert into tblrolepermissions
									(roleId, elementId, IsPermitted)
									values (?, ?, ?)";
						$istmt = $db->prepare($sqlstoredRolePerm);
						$istmt->bindParam(1, $RolePermDetails->roleId, PDO::PARAM_INT);
						$istmt->bindParam(2, $RolePermDetails->idElement , PDO::PARAM_INT);
						$istmt->bindParam(3,  $RolePermDetails->IsPermitted, PDO::PARAM_INT);
						$istmt->execute();
						return 1;
					}
					catch(Exception $e)
					{
						$errMsg = $sqlstoredRolePerm .   $e->getMessage() ;
						throw new Exception($errMsg);
					} 	
			}   
	
		}
		catch(Exception $e)
		{
			$errMsg = $sqlRolePerm .   $e->getMessage() ;
			throw new Exception($errMsg);
		} 
	
	}
}
?>