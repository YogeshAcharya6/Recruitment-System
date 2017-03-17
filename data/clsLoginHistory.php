<?php
class LoginHistory
{
	private $idLoginHist;
	private $userId;
	private $LoginTime;
	private $LogoutTime;
	private $IsTimeOut;
	private $lastActionDateTime;
	private $idString; 
	
	public function getIDString(){  return  $this->IdString; }
	
	public function __construct($db, $clientIP,  $userId) {
		
		$TimeZone = new DateTimeZone("Asia/Kolkata");
		$date = new DateTime();
		$date->setTimezone($TimeZone);
		$TodaysDate = date('Y-m-d');
		$CurrentTime = $date->format("Y-m-d H:i:s");
		
		$chars = "abcdefqwertyuiopasdfghjklzxcvbnm1234567890";
		$this->IdString = substr(str_shuffle($chars),0,10); //Create new IdString
			
		try{
			$db->beginTransaction();
			$IsTimeOut = 0;
			$LogoutTime = 0 ;
			$SqlHistory = "insert into tblloginhist
						(userId, idString, LoginTime, IsTimeOut, lastActionDateTime, clientIP)
						values (?, ?, ?, ?, ?, ?)";
						
			$pstmt = $db->prepare($SqlHistory);
			$pstmt->bindParam(1, $userId, PDO::PARAM_INT);
			$pstmt->bindParam(2, $this->IdString, PDO::PARAM_STR,24);
			$pstmt->bindParam(3, $CurrentTime, PDO::PARAM_STR);
			$pstmt->bindParam(4, $IsTimeOut, PDO::PARAM_INT);
			$pstmt->bindParam(5, $CurrentTime, PDO::PARAM_STR);
			$pstmt->bindParam(6, $clientIP, PDO::PARAM_STR);
			$pstmt->execute();				
			$db->commit();
			
		} catch(Exception $e) {
			$db->rollback();
			throw new Exception ('Error:Err001' . $SqlHistory . " " . $e->getMessage());
		}	
	} 
	
	public static function CheckActiveSession($db, $clientIP, $userId )
	{
		$cnt = 0;
		try{
			$sqlHistory  = "select userId
					from tblloginhist
					where userId =  ?  AND 
					LogoutTime IS NULL
					AND IsTimeOut = 0";
			$stmt = $db->prepare($sqlHistory);
			$stmt->bindParam(1, $userId, PDO::PARAM_STR, 24);
			$stmt->execute();
		
			while ($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
				$cnt++;
			}
			return $cnt;
		}
		catch(Exception $e){
			throw new Exception ('Error:Err001' . $sqlHistory . " " . $e->getMessage());
		}	
	}

	public static function getLoginTime($db, $loginhistDtls) {
		try {
			
		    $sqlhistory = "SELECT LoginTime from tblloginhist WHERE idString = ? ";
			$stmt = $db->prepare($sqlhistory);
			$stmt->bindParam(1, $loginhistDtls->objContext->IDString, PDO::PARAM_STR, 64);
			$stmt->execute();
			$result = $stmt->fetch(); 
			return $result['LoginTime'];
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
}
?>
