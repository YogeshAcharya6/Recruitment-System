<?php

class RRCandidate
{
	private $idRRCandidate;
	private $PositionId; 
	private $CandidateId;
	private $IsActive;
	
	public function getRRCandidate($db,$Candidatedtls)
	{			
		try {
			$IsActive = 1;
			
			$sql = "select idRRCandidate,PositionId,CandidateId,IsActive from tblrrcandidate where IsActive = ? and PositionId = ? and RRId = ?";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmti->bindParam(2, $Candidatedtls->idPosition, PDO::PARAM_INT);
			$stmti->bindParam(3, $Candidatedtls->idRR, PDO::PARAM_INT);
			$stmti->execute();
			$ArrRRCandidateDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrRRCandidateDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	public function getActiveFeedbackDtls($db)
	{			
		try {
			$IsActive = 1;
			$sql = "select * from tblActivefeedback where IsActive = ?";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmti->execute();
			$ArrActiveFeedbackDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrActiveFeedbackDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	
	public function saveCandiFeedback($db,$Candidatefeedbackdtls)
	{			
		try {
			$TimeZone = new DateTimeZone("Asia/Kolkata");
			$date = new DateTime();
			$date->setTimezone($TimeZone);
			$TodaysDate = date('Y-m-d H:i:s');
			$IsActive = 0;
			$sql = "insert into tblinteractionfeedback(`feedback`, `ScheduleRRId`, RegId, LUDT, IsActive)
						values(?, ?, ?, ?, ?)";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $Candidatefeedbackdtls->feedback, PDO::PARAM_STR);
			$stmti->bindParam(2, $Candidatefeedbackdtls->idRRSchedule, PDO::PARAM_INT);
			$stmti->bindParam(3, $Candidatefeedbackdtls->objContext->idUser, PDO::PARAM_INT);
			$stmti->bindParam(4, $TodaysDate, PDO::PARAM_STR);
			$stmti->bindParam(5, $IsActive, PDO::PARAM_INT);
			$stmti->execute();
			
			$sqls = "SELECT `idActivefeedback`, `CandidateRRId`, `IsActive` FROM `tblActivefeedback` WHERE CandidateRRId = ? and IsActive = 1";
			$stmts = $db->prepare($sqls);
			$stmts->bindParam(1, $Candidatefeedbackdtls->idRRCandidate, PDO::PARAM_INT);
			$stmts->execute();
			$countRow = $stmts->rowCount();
			if($countRow == 0)
			{
				$Active = 1;
				$sqlAF = "insert into tblActivefeedback(`CandidateRRId`, `IsActive`)
						values(?, ?)";
				$stmtAF = $db->prepare($sqlAF);
				$stmtAF->bindParam(1, $Candidatefeedbackdtls->idRRCandidate, PDO::PARAM_STR);
				$stmtAF->bindParam(2, $Active, PDO::PARAM_INT);
				$stmtAF->execute();
			}
			
			$sqlu = "update tblinteraction set interactionStatusId = ?,IsActiveInteraction = 0 where idRRSchedule = ?";
			$stmtu = $db->prepare($sqlu);
			$stmtu->bindParam(1, $Candidatefeedbackdtls->intStatusId, PDO::PARAM_STR);
			$stmtu->bindParam(2, $Candidatefeedbackdtls->idRRSchedule, PDO::PARAM_STR);
			$stmtu->execute();
			$CandiPosStatusId = 0;
			if($Candidatefeedbackdtls->intStatusId == 1)
			{
				$CandiPosStatusId = 4;
			}
			if($Candidatefeedbackdtls->intStatusId == 2)
			{
				$CandiPosStatusId = 3;
			}
			if($Candidatefeedbackdtls->intStatusId == 3)
			{
				$CandiPosStatusId = 2;
			}
			$sqlurrc = "update tblrrcandidate set CandiPosStatusId = ? where idRRCandidate = ?";
			$stmturrc = $db->prepare($sqlurrc);
			$stmturrc->bindParam(1,$CandiPosStatusId, PDO::PARAM_STR);
			$stmturrc->bindParam(2, $Candidatefeedbackdtls->idRRCandidate, PDO::PARAM_STR);
			$stmturrc->execute();
			return 1;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	
	public function updateCandiFeedback($db,$Candidatefeedbackdtls)
	{			
		try {
			$TimeZone = new DateTimeZone("Asia/Kolkata");
			$date = new DateTime();
			$date->setTimezone($TimeZone);
			$TodaysDate = date('Y-m-d H:i:s');
			
			$sql = "update tblinteractionfeedback set feedback = ?,LUDT = ? where idRRCandidateFeedback = ?";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $Candidatefeedbackdtls->feedback, PDO::PARAM_STR);
			$stmti->bindParam(2, $TodaysDate, PDO::PARAM_STR);
			$stmti->bindParam(3, $Candidatefeedbackdtls->idRRCandidateFeedback, PDO::PARAM_INT);
			$stmti->execute();
			$sql = "update  tblinteraction set interactionStatusId = ? where idRRSchedule = ?";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $Candidatefeedbackdtls->interactionStatusId, PDO::PARAM_INT);
			$stmti->bindParam(2, $Candidatefeedbackdtls->idRRSchedule, PDO::PARAM_INT);
			$stmti->execute();
			$CandiPosStatusId = 0;
			if($Candidatefeedbackdtls->interactionStatusId == 1)
			{
				$CandiPosStatusId = 4;
			}
			if($Candidatefeedbackdtls->interactionStatusId == 2)
			{
				$CandiPosStatusId = 3;
			}
			if($Candidatefeedbackdtls->interactionStatusId == 3)
			{
				$CandiPosStatusId = 2;
			}
			$sql = "update tblrrcandidate set CandiPosStatusId = ? where idRRCandidate = ?";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $CandiPosStatusId, PDO::PARAM_INT);
			$stmti->bindParam(2, $Candidatefeedbackdtls->idRRCandidate, PDO::PARAM_INT);
			$stmti->execute();
			return 1;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	public function updateRRSchedule($db,$sceduledtls)
	{			
		try {
			$sqls = "select * from tblinteraction where `CandidateRRId` = ?";
			$stmts = $db->prepare($sqls);
			$stmts->bindParam(1,$sceduledtls->idRRCandidate, PDO::PARAM_INT);
			$stmts->execute();
			$ArrInteractionDtls = $stmts->fetchAll(PDO::FETCH_ASSOC);
			
			$sqlu = "DELETE FROM `tblInterviewer` WHERE `RRScheduleId` = ?"; 
			$stmtu = $db->prepare($sqlu);
			$stmtu->bindParam(1, $ArrInteractionDtls[0]['idRRSchedule'], PDO::PARAM_INT);
			$stmtu->execute();
			
			$sqlus = "update `tblinteraction` set `IsActiveInteraction` = 2 where CandidateRRId = ?"; 
			$stmtus = $db->prepare($sqlus);
			$stmtus->bindParam(1, $sceduledtls->idRRCandidate, PDO::PARAM_INT);
			$stmtus->execute();
			$IsActiveInteraction = 1;
			$sql = "insert into tblinteraction(`CandidateRRId`,`InterviewTypeId`,`intDate`, `altIntDate`,interactionStatusId,IsActiveInteraction)
						values(?, ?, ?, ?, ?, ?)";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $sceduledtls->idRRCandidate, PDO::PARAM_INT);
			$stmti->bindParam(2, $sceduledtls->interviewTypeDesc->idInterviewType, PDO::PARAM_INT);
			$stmti->bindParam(3, $sceduledtls->InteractionDate, PDO::PARAM_STR);
			$stmti->bindParam(4, $sceduledtls->AInteractionDate, PDO::PARAM_STR);
			$stmti->bindParam(5, $ArrInteractionDtls[0]['interactionStatusId'], PDO::PARAM_INT);
			$stmti->bindParam(6, $IsActiveInteraction, PDO::PARAM_INT);
			$stmti->execute();
			$lastInsertId = $db->lastInsertId(); 
			for($i=0;$i<sizeof($sceduledtls->Name);$i++)
			{
				$sqlinterviewer = "insert into tblInterviewer(`RRScheduleId`, `RegId`)
						values(?, ?)";
				$stmtii = $db->prepare($sqlinterviewer);
				$stmtii->bindParam(1, $lastInsertId, PDO::PARAM_INT);
				$stmtii->bindParam(2, $sceduledtls->Name[$i]->idReg, PDO::PARAM_INT);
				$stmtii->execute();
			}
			return 1;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}

	public function SaveRRSchedule($db,$Scheduledtls)
	{			
		try {
				if($Scheduledtls->IsActiveInteraction == 0)
				{
					$sqlgets = "SELECT max(`idRRSchedule`) as idRRSchedule FROM `tblinteraction` where CandidateRRId = ? and `IsActiveInteraction` = 0";
					$stmtgets = $db->prepare($sqlgets);
					$stmtgets->bindParam(1, $Scheduledtls->idRRCandidate, PDO::PARAM_INT);
					$stmtgets->execute();
					$ArrDtls = $stmtgets->fetchAll(PDO::FETCH_ASSOC);
					
					$sqlget = "select * from tblinteraction where idRRSchedule = ?";
					$stmtget = $db->prepare($sqlget);
					$stmtget->bindParam(1,  $ArrDtls[0]['idRRSchedule'], PDO::PARAM_INT);
					$stmtget->execute();
					$ArrInteractionDtls = $stmtget->fetchAll(PDO::FETCH_ASSOC);
					
					$sqluif = "update tblinteractionfeedback set IsActive = 0 where `ScheduleRRId`= ?"; 
					$stmtuif = $db->prepare($sqluif);
					$stmtuif->bindParam(1, $ArrInteractionDtls[0]['idRRSchedule'], PDO::PARAM_INT);
					$stmtuif->execute();
				
					$IsActive = 1;
					$interactionStatusId = $ArrInteractionDtls[0]['interactionStatusId'];
					$sql = "insert into tblinteraction(`CandidateRRId`,`InterviewTypeId`,`intDate`, `altIntDate`,interactionStatusId,IsActiveInteraction)
							values(?, ?, ?, ?, ?, ?)";
					$stmti = $db->prepare($sql);
					$stmti->bindParam(1, $Scheduledtls->idRRCandidate, PDO::PARAM_INT);
					$stmti->bindParam(2, $Scheduledtls->interviewTypeDesc->idInterviewType, PDO::PARAM_INT);
					$stmti->bindParam(3, $Scheduledtls->InteractionDate, PDO::PARAM_STR);
					$stmti->bindParam(4, $Scheduledtls->AInteractionDate, PDO::PARAM_STR);
					$stmti->bindParam(5, $interactionStatusId, PDO::PARAM_INT);
					$stmti->bindParam(6, $IsActive, PDO::PARAM_INT);
					$stmti->execute();
					$lastInsertId = $db->lastInsertId(); 
					
					if($lastInsertId > 0)
					{
						for($i=0;$i<sizeof($Scheduledtls->Name);$i++)
						{
							$sqlInt = "insert into tblInterviewer(`RRScheduleId`, `RegId`)
								values(?, ?)";
							$stmtii = $db->prepare($sqlInt);
							$stmtii->bindParam(1, $lastInsertId, PDO::PARAM_INT);
							$stmtii->bindParam(2, $Scheduledtls->Name[$i]->idReg, PDO::PARAM_INT);
							$stmtii->execute();
						}
						return 1;
					}
					
				}
				if($Scheduledtls->IsActiveInteraction == 1)
				{
					$IsActive = 1;
					$interactionStatusId = 4;
					$sql = "insert into tblinteraction(`CandidateRRId`,`InterviewTypeId`,`intDate`, `altIntDate`,interactionStatusId,IsActiveInteraction)
							values(?, ?, ?, ?, ?, ?)";
					$stmti = $db->prepare($sql);
					$stmti->bindParam(1, $Scheduledtls->idRRCandidate, PDO::PARAM_INT);
					$stmti->bindParam(2, $Scheduledtls->interviewTypeDesc->idInterviewType, PDO::PARAM_INT);
					$stmti->bindParam(3, $Scheduledtls->InteractionDate, PDO::PARAM_STR);
					$stmti->bindParam(4, $Scheduledtls->AInteractionDate, PDO::PARAM_STR);
					$stmti->bindParam(5, $interactionStatusId, PDO::PARAM_INT);
					$stmti->bindParam(6, $IsActive, PDO::PARAM_INT);
					$stmti->execute();
					$lastInsertId = $db->lastInsertId(); 
					if($lastInsertId > 0)
					{
						for($i=0;$i<sizeof($Scheduledtls->Name);$i++)
						{
							$sqlInt = "insert into tblInterviewer(`RRScheduleId`, `RegId`)
								values(?, ?)";
							$stmtii = $db->prepare($sqlInt);
							$stmtii->bindParam(1, $lastInsertId, PDO::PARAM_INT);
							$stmtii->bindParam(2, $Scheduledtls->Name[$i]->idReg, PDO::PARAM_INT);
							$stmtii->execute();
						}
						return 1;
					}
				}
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}

	public function getPosRelated($db,$RRDtls)
	{			
		try {
			$IsActive = 1;
			$sql = "select c.*,rc.*,rs.*,r.*,i.*,cps.*,ti.*
					from tblcandidate c
					LEFT join tblrrcandidate rc on rc.CandidateId = c.idCandidate 
					LEFT join tblinteraction rs on rs.CandidateRRId = rc.idRRCandidate
					LEFT join tblInterviewer ti on ti.RRScheduleId = rs.idRRSchedule
					LEFT join tblregistration r on r.idReg = ti.RegId 
					LEFT join diminterviewtype i on i.idInterviewType = rs.InterviewTypeId
					LEFT join dimcandiposstatus cps on cps.idCandiPosStatus = rc.CandiPosStatusId
					where rc.IsActive = ? and rc.PositionId = ? and rc.RRId = ? and (rc.CandiPosStatusId = 1 or rc.CandiPosStatusId = 3 or rc.CandiPosStatusId = 4) order by c.idCandidate";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmti->bindParam(2, $RRDtls->idPosition, PDO::PARAM_INT);
			$stmti->bindParam(3, $RRDtls->idRR, PDO::PARAM_INT);
			$stmti->execute();
			$ArrRRCandidateDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrRRCandidateDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	
	public function getAssignCandiDtls($db)
	{			
		try {
			$IsActive = 1;
			$sql = "select c.*,rc.*,rs.*,r.*,i.*,cps.*,ti.*,p.*,date(rs.intDate) as intDate1,date(rs.altIntDate) as altIntDate1
					from tblcandidate c
					join tblrrcandidate rc on rc.CandidateId = c.idCandidate 
					join tblinteraction rs on rs.CandidateRRId = rc.idRRCandidate 
					join tblInterviewer ti on ti.RRScheduleId = rs.idRRSchedule
					join tblregistration r on r.idReg = ti.RegId 
					join diminterviewtype i on i.idInterviewType = rs.InterviewTypeId
					join dimposition p on p.idPosition = rc.PositionId
					join dimcandiposstatus cps on cps.idCandiPosStatus = rc.CandiPosStatusId
					where rc.IsActive = ? and (rc.CandiPosStatusId = 4 or rc.CandiPosStatusId = 1 OR rc.CandiPosStatusId = 3) and (rs.interactionStatusId = 1 or rs.interactionStatusId = 2 or rs.interactionStatusId = 4) and rs.IsActiveInteraction = 1 order by c.idCandidate";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmti->execute();
			$ArrRRCandidateDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrRRCandidateDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	public function getRoleRelAssignCandiDtls($db,$strUserId)
	{			
		try {
			$IsActive = 1;
			$sql = "select c.*,rc.*,rs.*,r.*,i.*,cps.*,ti.*,p.*,date(rs.intDate) as intDate1,date(rs.altIntDate) as altIntDate1
					from tblcandidate c
					join tblrrcandidate rc on rc.CandidateId = c.idCandidate 
					join tblinteraction rs on rs.CandidateRRId = rc.idRRCandidate 
					join tblInterviewer ti on ti.RRScheduleId = rs.idRRSchedule
					join tblregistration r on r.idReg = ti.RegId 
					join diminterviewtype i on i.idInterviewType = rs.InterviewTypeId
					join dimposition p on p.idPosition = rc.PositionId
					join dimcandiposstatus cps on cps.idCandiPosStatus = rc.CandiPosStatusId
					where rc.IsActive = ? and (rc.CandiPosStatusId = 4 or rc.CandiPosStatusId = 1 OR rc.CandiPosStatusId = 3) and (rs.interactionStatusId = 1 or rs.interactionStatusId = 2 or rs.interactionStatusId = 4) and rs.IsActiveInteraction = 1 and ti.RegId = ? order by c.idCandidate";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmti->bindParam(2, $strUserId, PDO::PARAM_STR);
			$stmti->execute();
			$ArrRRCandidateDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrRRCandidateDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	public function getInteractionCandiDtls($db)
	{			
		try {
			$IsActive = 1;
			$sql = "select c.*,rc.*,rs.*,r.*,i.*,cps.*,cf.*,ti.*
					from tblcandidate c
					join tblrrcandidate rc on rc.CandidateId = c.idCandidate 
					join tblinteraction rs on rs.CandidateRRId = rc.idRRCandidate 
					join tblInterviewer ti on ti.RRScheduleId = rs.idRRSchedule
					join tblregistration r on r.idReg = ti.RegId
					join diminterviewtype i on i.idInterviewType = rs.InterviewTypeId
					join dimcandiposstatus cps on cps.idCandiPosStatus = rc.CandiPosStatusId
					LEFT join tblinteractionfeedback cf on cf.ScheduleRRId = rs.idRRSchedule
					where rc.IsActive = ? and rs.IsActiveInteraction = 1 and (rc.CandiPosStatusId = 4 or rc.CandiPosStatusId = 1 OR rc.CandiPosStatusId = 3) order by c.idCandidate";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmti->execute();
			$ArrRRCandidateDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrRRCandidateDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	
	public function getInteractionHistoryDtls($db,$InteractionDtls)
	{			
		try {
			$IsActive = 1;
			$sql = "select c.*,rc.*,rs.*,r.*,i.*,cps.*,cf.*,ti.*
			from tblcandidate c 
			join tblrrcandidate rc on rc.CandidateId = c.idCandidate
			join tblinteraction rs on rs.CandidateRRId = rc.idRRCandidate
			join tblInterviewer ti on ti.RRScheduleId = rs.idRRSchedule
			join diminterviewtype i on i.idInterviewType = rs.InterviewTypeId
			join diminteractionstatus cps on cps.idinteractionStatus = rs.interactionStatusId
			LEFT join tblinteractionfeedback cf on cf.ScheduleRRId = rs.idRRSchedule
			join tblregistration r on r.idReg = cf.RegId
			where rc.IsActive = ? and rs.IsActiveInteraction = 0 and (rc.CandiPosStatusId = 4 or rc.CandiPosStatusId = 1 OR rc.CandiPosStatusId = 3) and rs.`CandidateRRId` IN (select `CandidateRRId` from tblinteraction where rs.`CandidateRRId` = ?)";
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmti->bindParam(2, $InteractionDtls->idRRCandidate, PDO::PARAM_INT);
			$stmti->execute();
			$ArrRRCandidateDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrRRCandidateDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	public function getUserFeedback($db,$UserId)
	{			
		try {
			$sql;
			$IsActive = 1;
			$str = "select c.*,rc.*,rs.*,r.*,i.*,cps.*,cf.*,ti.*,p.*
			from tblcandidate c 
			join tblrrcandidate rc on rc.CandidateId = c.idCandidate
			join tblrr rr on rr.idRR = rc.RRId
			join tbljobdesc jd on jd.idJobDesc = rr.JobDescId
			join dimposition p on p.idPosition = jd.PositionId
			join tblinteraction rs on rs.CandidateRRId = rc.idRRCandidate
			join tblInterviewer ti on ti.RRScheduleId = rs.idRRSchedule
			join diminterviewtype i on i.idInterviewType = rs.InterviewTypeId
			join diminteractionstatus cps on cps.idinteractionStatus = rs.interactionStatusId
			LEFT join tblinteractionfeedback cf on cf.ScheduleRRId = rs.idRRSchedule
			join tblregistration r on r.idReg = cf.RegId
			where rc.IsActive = ? and rs.IsActiveInteraction = 0 and (rc.CandiPosStatusId = 4 or rc.CandiPosStatusId = 1 OR rc.CandiPosStatusId = 3)";
			if($UserId == "")
			{
				$sql = $str;
			}
			else
			{
				$sql = $str." and cf.RegId = '".$UserId."'";
			}
			$stmti = $db->prepare($sql);
			$stmti->bindParam(1, $IsActive, PDO::PARAM_INT);
			$stmti->execute();
			$ArrHistoryDtls = $stmti->fetchAll(PDO::FETCH_ASSOC);
			return $ArrHistoryDtls;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}

	public function deactivateCandiRelPos($db,$candidateDtls)
	{			
		try {
			$sqlget = "select * from tblrrcandidate where idRRCandidate = ?";
			$stmtget = $db->prepare($sqlget);
			$stmtget->bindParam(1, $candidateDtls->idRRCandidate, PDO::PARAM_INT);
			$stmtget->execute();
			$ArrRRCandidateDtls = $stmtget->fetchAll(PDO::FETCH_ASSOC);
			
			$sqld = "DELETE FROM `tblrrcandidate` WHERE `idRRCandidate` = ?"; 
			$stmtd = $db->prepare($sqld);
			$stmtd->bindParam(1, $candidateDtls->idRRCandidate, PDO::PARAM_INT);
			$stmtd->execute();
			
			$sqlgetc = "select * from tblrrcandidate where CandidateId = ? and IsActive = 1 and (CandiPosStatusId = 1 or CandiPosStatusId = 2)";
			$stmtgetc = $db->prepare($sqlgetc);
			$stmtgetc->bindParam(1, $ArrRRCandidateDtls[0]['CandidateId'], PDO::PARAM_INT);
			$stmtgetc->execute();
			$countRow = $stmtgetc->rowCount();
			if($countRow == 0)
			{
				$CandidateStatusId = 1;
				$sqlu = "update tblcandidate set CandidateStatusId = ? where `idCandidate`= ?"; 
				$stmtu = $db->prepare($sqlu);
				$stmtu->bindParam(1, $CandidateStatusId, PDO::PARAM_INT);
				$stmtu->bindParam(2, $ArrRRCandidateDtls[0]['CandidateId'], PDO::PARAM_INT);
				$stmtu->execute();
				
			}
			return 1;
			
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}

	public function SaveRRCandidate($db,$RRCandidateDtls)
	{			
		try {
			/* `idRRCandidate`, `RRId`, `PositionId`, `CandidateId`, `IsActive` */
			for($i=0;$i<sizeof($RRCandidateDtls);$i++)
			{
				$countRow = 0;
				$sqls = "select idRRCandidate,RRId,PositionId,CandidateId,IsActive from tblrrcandidate where PositionId = ? and CandidateId = ? and RRId = ?";
				$stmt = $db->prepare($sqls);
				$stmt->bindParam(1, $RRCandidateDtls[0]->idPosition, PDO::PARAM_INT);
				$stmt->bindParam(2, $RRCandidateDtls[$i]->idCandidate, PDO::PARAM_INT);
				$stmt->bindParam(3, $RRCandidateDtls[0]->RRId, PDO::PARAM_INT);
				$stmt->execute();
				$countRow = $stmt->rowCount();
				if($countRow > 0)
				{
					$Active = 0;
					if($RRCandidateDtls[$i]->RRCandiActive == true)
					{
						$Active=1;
					}
					$sqlu = "update tblrrcandidate set IsActive = ? where PositionId = ? and CandidateId = ? and RRId = ?"; 
							$stmtu = $db->prepare($sqlu);
							$stmtu->bindParam(1, $Active, PDO::PARAM_INT);
							$stmtu->bindParam(2, $RRCandidateDtls[0]->idPosition, PDO::PARAM_INT);
							$stmtu->bindParam(3, $RRCandidateDtls[$i]->idCandidate, PDO::PARAM_INT);
							$stmtu->bindParam(4, $RRCandidateDtls[0]->RRId, PDO::PARAM_INT);
							$stmtu->execute();
					
				
					$ArrRRCandidateDtls  = array();
					if($Active == 0)
					{
						$sqlget = "select * from tblrrcandidate where IsActive = ? and PositionId = ? and CandidateId = ? and RRId = ?";
						$stmtget = $db->prepare($sqlget);
							$stmtget->bindParam(1, $Active, PDO::PARAM_INT);
							$stmtget->bindParam(2, $RRCandidateDtls[0]->idPosition, PDO::PARAM_INT);
							$stmtget->bindParam(3, $RRCandidateDtls[$i]->idCandidate, PDO::PARAM_INT);
							$stmtget->bindParam(4, $RRCandidateDtls[0]->RRId, PDO::PARAM_INT);
							$stmtget->execute();
							$ArrRRCandidateDtls = $stmtget->fetchAll(PDO::FETCH_ASSOC);
							
							$sqlgets = "select * from tblinteraction where CandidateRRId = ?";
							$stmtgets = $db->prepare($sqlgets);
							$stmtgets->bindParam(1, $ArrRRCandidateDtls[0]['idRRCandidate'], PDO::PARAM_INT);
							$stmtgets->execute();
							$ArrInterctionDtls = $stmtgets->fetchAll(PDO::FETCH_ASSOC);
							
							$sqlus = "DELETE FROM `tblInterviewer` WHERE `RRScheduleId` = ?"; 
							$stmtus = $db->prepare($sqlus);
							$stmtus->bindParam(1, $ArrInterctionDtls[0]['idRRSchedule'], PDO::PARAM_INT);
							$stmtus->execute();
							
							$sqlui = "DELETE FROM `tblinteraction` WHERE `CandidateRRId` = ?"; 
							$stmtui = $db->prepare($sqlui);
							$stmtui->bindParam(1, $ArrRRCandidateDtls[0]['idRRCandidate'], PDO::PARAM_INT);
							$stmtui->execute();
							
					}
				}
				else
				{
					if($RRCandidateDtls[$i]->RRCandiActive == true)
					{
						$IsActive = 1;
						$CandiPosStatusId = 1;
						$sqli = "insert into tblrrcandidate(RRId,PositionId,CandidateId,IsActive,CandiPosStatusId) values (?, ?, ?, ?, ?)";
						$stmti = $db->prepare($sqli);
						$stmti->bindParam(1, $RRCandidateDtls[0]->RRId , PDO::PARAM_INT);
						$stmti->bindParam(2, $RRCandidateDtls[0]->idPosition , PDO::PARAM_INT);
						$stmti->bindParam(3, $RRCandidateDtls[$i]->idCandidate, PDO::PARAM_INT);
						$stmti->bindParam(4, $IsActive, PDO::PARAM_INT);
						$stmti->bindParam(5, $CandiPosStatusId, PDO::PARAM_INT);
						$stmti->execute();
					}
				}
				
			}
			return 1;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
		
	}
	public function CountRRCandidate($db,$RRCandidateDtls)
	{
		try{
			$CandidateAvailabelArray = array();
			for($i=0;$i<sizeof($RRCandidateDtls);$i++)
			{
				$countRow = 0;
				$IsActive = 1;
				$sqls = "select * from tblrrcandidate where CandidateId = ? and IsActive = ?";
				$stmt = $db->prepare($sqls);
				$stmt->bindParam(1, $RRCandidateDtls[$i]->idCandidate, PDO::PARAM_INT);
				$stmt->bindParam(2, $IsActive, PDO::PARAM_INT);
				$stmt->execute();
				$countRow = $stmt->rowCount();
				if($countRow == 0)
				{
					array_push($CandidateAvailabelArray,$RRCandidateDtls[$i]->idCandidate);
				}
			}
			return $CandidateAvailabelArray;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
}
?>
