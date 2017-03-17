<?php

class User
{
	//User variable
	private $idUser;
	private $userId; 
	private $userPassword;
	private $IsActive;
	private $activeChgDT;
	private $activeChgBy;
	private $createDT;
	private $createBy;
	private $pwdSetDT;
	private $idReg;
	private $Name;
	private $EmpId;

	public function setidUser($IntidUser)
	{
		$this->idUser = $IntidUser;
	}
	
	public function getidUser(){ return $this->idUser; }
	
	public function setuserId($userId)
	{
		$this->userId = $userId;
	}
	
	public function getuserId(){ return $this->userId; }
			
	public function setidReg($idReg)
	{
		$this->idReg = $idReg;
	}
	
	public function getidReg(){ return $this->idReg; }
	public function setName($Name)
	{
		$this->Name = $Name;
	}
	
	public function getName(){ return $this->Name; }
	public function setEmpId($EmpId)
	{
		$this->EmpId = $EmpId;
	}
	
	public function getEmpId(){ return $this->EmpId; }
	
	public function getUser(){
		$ArrUser = array();
		$ArrUser['idUser'] = $this->idUser;
		$ArrUser['userId'] = $this->userId;
		return $ArrUser;
	}
	public function getEmployee(){
		$ArrUser = array();
		$ArrUser['idReg'] = $this->idReg;
		$ArrUser['Name'] = $this->Name;
		$ArrUser['EmpId'] = $this->EmpId;
		return $ArrUser;
	}
	
	public function __construct() 
	{
		$a = func_get_args();
		$i = func_num_args();
		if($i > 0){
			if(method_exists($this, $f = '__construct1')){
				call_user_func_array(array($this, $f), $a);
			}
		}
	}
	public function __construct1($db, $idUser ) 
	{
		try {						
				$sql = "select u.idUser, u.userId, u.userPassword, u.IsActive,
						u.createBy,  u.pwdSetDT ,l.IdString
						from tbluser u
						join tblloginhist l on l.userId = u.idUser
						where l.userId = ? "; 
						
				$stmt = $db->prepare($sql);
				$stmt->bindParam(1, $idUser, PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetch();
				
				$this->idUser = $result['idUser'];
				$this->userId = $result['userId'];
				$this->userPassword = $result['userPassword'];
				$this->IsActive = $result['IsActive'];
				$this->createBy = $result['createBy'];
				$this->pwdSetDT = $result['pwdSetDT'];   
				$this->IdString = $result['IdString'];   
			}
			catch(Exception $e)
			{
				$errMsg = "Userdtls: ErrorS001: "  . "\nCaughtEx:" . $e->getMessage() . "\n"; 
				throw new Exception($errMsg);
			}
	} 
	
	public static function loginValidate($db, $userId, $userPassword) {
		$cnt = 0;
		$IDUser = 0;
		try {
			$sql = "select idUser 
					from tbluser 
					where userId = ?
					and userPassword = ?
					and IsActive = 1";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(1, $userId, PDO::PARAM_STR, 24);
			$stmt->bindParam(2, $userPassword, PDO::PARAM_STR, 24);
			$stmt->execute();
			$stmt->bindColumn(1, $IDUser);
			while ($result = $stmt->fetch(PDO::FETCH_BOUND)) {
				$cnt++;
			}
			if($cnt == 1)	
			{
				return $IDUser;		// Valid
			}
			else		
			{
				return $sql.$cnt.$userId.$userPassword;  // Invalid	
			}
		}
		catch(Exception $e)
		{
			$errMsg = "loginValidate: Error ID: " . $IDUser . " cnt: " . $cnt
				 . "\nCaughtEx:" . $e->getMessage() . "\n";
			throw new Exception($errMsg);
		}
	}
	public static function deActivateUserUser($db,$userDtls) {
		$IsActive = 0;
		try {
			$sql = "update tbluser set IsActive = ? where idUser = ?"; 
			$stmt = $db->prepare($sql);
			$stmt->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmt->bindParam(2, $userDtls->idUser, PDO::PARAM_INT);
			$stmt->execute();
			$countRow = $stmt->rowCount();
			if($countRow == 1)	
			{
				return 1;		
			}
			else		
			{
				return 0;  
			}
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	   
	public function ChangeUserPassword($db,  $userPasswordDtls)
	{
		$cnt = 0;
		if($this->userPassword == $userPasswordDtls->OldPassword)
		{
			$cnt++;
		}
		if($cnt == 1)
			{
				$cnt1 = 0;
				if($this->userPassword == $userPasswordDtls->NewPassword)
				{
					$cnt1++;
				}
				if($cnt1 == 1)
				{
					$error = "New Password cannot be same as old Password";
					return $error;
				}
				else
				{
					try
					{
						$TimeZone = new DateTimeZone("Asia/Kolkata");
						$date = new DateTime();
						$date->setTimezone($TimeZone);
						$TodaysDate = date('Y-m-d H:i:s');
						$db->beginTransaction();
						$sqlUpdate = "UPDATE tbluser 
											set userPassword = ?,
											pwdSetDT = ?
											WHERE idUser= ?";
						$stmt = $db->prepare($sqlUpdate);
						$stmt->bindParam(1, $userPasswordDtls->NewPassword, PDO::PARAM_STR);
						$stmt->bindParam(2, $TodaysDate, PDO::PARAM_STR);
						$stmt->bindParam(3, $this->idUser, PDO::PARAM_INT);
						
						$stmt->execute();
						$db->commit();
						return $stmt->rowCount() ? 1 : 0;
					}
					catch(Exception $e)
					{
						$db->rollback();
						$errMsg = "Update Err004: ".    $e->getMessage() ; 
						throw new Exception($errMsg);
					}
				}
			}
			else
			{
				$error = "Old Password Not found";
				return $error;
			}
		}
	//Create user	
	public function AddUser($db, $userDtls)
	{

		$lastInsertId = 0;
		$cnt = 0;
		try {
			$sqlChkUsername = "select idUser
								from tbluser 
								where userId = ?"; 
			$stmt = $db->prepare($sqlChkUsername);
			$stmt->bindParam(1, $userDtls->userName, PDO::PARAM_STR, 24);
			$stmt->execute();
			
			while ($result = $stmt->fetch()) {
				$cnt++;
			}
			if($cnt == 1)
			{
				return 0;
			}
			else
			{
				try {
					$sql = "insert into tblregistration(Name,EmpId,DeptId,DesignationId,MobNo,AltContactNo,EmailId,Address) 
							values (?,?,?,?,?,?,?,?)";
					$stmti = $db->prepare($sql);
					$stmti->bindParam(1, $userDtls->name, PDO::PARAM_STR, 64);
					$stmti->bindParam(2, $userDtls->employeeId, PDO::PARAM_INT);
					$stmti->bindParam(3, $userDtls->departmentId->idDept, PDO::PARAM_INT);
					$stmti->bindParam(4, $userDtls->designation->idDesignation, PDO::PARAM_INT);
					$stmti->bindParam(5, $userDtls->mobileNo, PDO::PARAM_INT);
					$stmti->bindParam(6, $userDtls->altContactNo, PDO::PARAM_INT);
					$stmti->bindParam(7, $userDtls->emailId, PDO::PARAM_STR, 64);
					$stmti->bindParam(8, $userDtls->address, PDO::PARAM_STR, 64);
					$stmti->execute();
					$countRow = $stmti->rowCount();
					$lastInsertId = $db->lastInsertId();
					if($countRow > 0)
					{
						try
						{
							$TimeZone = new DateTimeZone("Asia/Kolkata");
							$date = new DateTime();
							$date->setTimezone($TimeZone);
							$TodaysDate = date('Y-m-d H:i:s');
							
							$cnt1 = 0;
							$IsActive = 1;
							$sqlCreateUser = "insert into tbluser
											(userId, userPassword, IsActive, createDT, createBy, regId)
											values (?, ?, ?, ?, ?, ?)";
							$istmt = $db->prepare($sqlCreateUser);
							$istmt->bindParam(1, $userDtls->userName, PDO::PARAM_STR, 24);
							$istmt->bindParam(2, $userDtls->password, PDO::PARAM_STR, 24);
							$istmt->bindParam(3, $IsActive, PDO::PARAM_INT);
							$istmt->bindParam(4, $TodaysDate, PDO::PARAM_STR);
							$istmt->bindParam(5, $userDtls->objContext->idUser, PDO::PARAM_INT);
							$istmt->bindParam(6, $lastInsertId, PDO::PARAM_INT);
									
							$istmt->execute();
							return 1;
					
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
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	public function getEmpDeptDtls($db,$Emp)
	{
		try{
			$sql = "SELECT DeptId
					FROM tblregistration
					WHERE EmpId = ? "; 
			$stmt = $db->prepare($sql);
			$stmt->bindParam(1, $Emp->EmpId, PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch();
			
			return $result;
			
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

}
?>
