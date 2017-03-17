<?php

class RR
{
	//`idRR``EmpId``DeptId``Openings``JobDescId``RequestDate``ExpectedDate``SalaryMin``SalaryMax``Status`
	private $idRR;
	private $EmpId; 
	private $DeptId;
	private $Openings;
	private $JobDescId;
	private $RequestDate;
	private $ExpectedDate;
	private $SalaryMin;
	private $SalaryMax;
	private $Status;
	
	public function saveRR($db, $RRDtls)
	{			
		try {
			$TimeZone = new DateTimeZone("Asia/Kolkata");
			$date = new DateTime();
			$date->setTimezone($TimeZone);
			$TodaysDate = date('Y-m-d');
			$CurrentTime = $date->format("Y-m-d H:i:s");
			$sql = "insert into tblrr(`EmpId`,`DeptId`,`Openings`,`JobDescId`,`RequestDate`,`ExpectedDate`,`SalaryMin`,`SalaryMax`,`Status`,RegDate) 
					values (?,?,?,?,?,?,?,?,?,?)";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $RRDtls->EmpId, PDO::PARAM_INT);
			$stmti->bindParam(2, $RRDtls->Department->idDept, PDO::PARAM_INT);
			$stmti->bindParam(3, $RRDtls->Openings, PDO::PARAM_INT);
			$stmti->bindParam(4, $RRDtls->idJobDesc, PDO::PARAM_INT);
			$stmti->bindParam(5, $RRDtls->RequestDate, PDO::PARAM_STR,64);
			$stmti->bindParam(6, $RRDtls->ExpectedDate, PDO::PARAM_STR, 64);
			$stmti->bindParam(7, $RRDtls->minSalary, PDO::PARAM_INT);
			$stmti->bindParam(8, $RRDtls->maxSalary, PDO::PARAM_INT);
			$stmti->bindParam(9, $RRDtls->Status->idStatus, PDO::PARAM_INT);
			$stmti->bindParam(10, $CurrentTime, PDO::PARAM_STR);
			$stmti->execute();
			$countRow = $stmti->rowCount();
			$lastInsertId = $db->lastInsertId();
			if($countRow > 0)
			{
				return $lastInsertId;
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
	public function Update($db, $RRData)
	{			
		try {
				//	idRR	EmpId	DeptId	Openings	JobDescId	
				//RequestDate	ExpectedDate	SalaryMin	SalaryMax	Status
				$SqlRRUpdate = "update tblrr set 
				Openings = ?,ExpectedDate = ?,SalaryMin = ?,SalaryMax = ?,Status = ?
				where idRR = ?";	
						$stmt = $db->prepare($SqlRRUpdate);
						$stmt->bindParam(1, $RRData->Openings, PDO::PARAM_INT);
						$stmt->bindParam(2,  $RRData->ExpectedDate, PDO::PARAM_INT);
						$stmt->bindParam(3,  $RRData->minSalary, PDO::PARAM_INT);
						$stmt->bindParam(4,  $RRData->maxSalary, PDO::PARAM_INT);
						$stmt->bindParam(5,  $RRData->Status->idStatus, PDO::PARAM_INT);
						$stmt->bindParam(6,  $RRData->idRR, PDO::PARAM_INT);
						$stmt->execute();
						return 1;
			}
			catch(Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		
	}
public function getRRCnt($db,$LoginTime)
	{
		try {
			
		    $sqlRR = "select count(*) from tblrr where RegDate < '".$LoginTime."'";
			$stmt = $db->prepare($sqlRR);
	
			$stmt->execute();
			$result = $stmt->fetch(); 
			return $result;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}	
	public function getAddRRCnt($db,$LoginTime)
	{
		try {
			
		    $sqlRR = "select count(*) from tblrr where RegDate > '".$LoginTime."'";
			$stmt = $db->prepare($sqlRR);
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
