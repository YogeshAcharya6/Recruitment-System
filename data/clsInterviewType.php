<?php

class InterviewType
{
	private $idInterviewType;
	private $interviewTypeDesc; 

	public function LoadInterviewTypeList($db)
	{			
		try {
			$sql = "select idInterviewType,interviewTypeDesc from diminterviewtype order by interviewTypeDesc ASC";
			$stmti = $db->prepare($sql);
			$stmti->execute();
			$ArrInterviewTypeDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrInterviewTypeDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
}
?>
