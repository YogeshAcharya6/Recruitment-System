<?php
	Class JobDescription
	{
		private $idJobDesc;	
		private $PositionId;
		private $ExperianceMin;	
		private $ExperianceMAx;
		private $Role;	
		private $Responsibility;
		public function Save($db,$JobDescDtls,$resultposition)
		{	
			$insert_id = 0;
			try
			{
				
				$TimeZone = new DateTimeZone("Asia/Kolkata");
				$date = new DateTime();
				$date->setTimezone($TimeZone);
				$TodaysDate = date('Y-m-d');
				$CurrentTime = $date->format("Y-m-d H:i:s");
				$SqlJobDesc = "insert into  tbljobdesc (PositionId,ExperianceMin,ExperianceMAx,Role,Responsibility,RegDate) 
								values (?, ?, ?, ?, ?, ?)";	
				$stmt = $db->prepare($SqlJobDesc);
				$stmt->bindParam(1,$resultposition, PDO::PARAM_INT);
				$stmt->bindParam(2, $JobDescDtls->minexp, PDO::PARAM_INT);
				$stmt->bindParam(3, $JobDescDtls->maxexp, PDO::PARAM_INT);
				$stmt->bindParam(4, $JobDescDtls->Role, PDO::PARAM_STR,64);
				$stmt->bindParam(5, $JobDescDtls->Responsibility, PDO::PARAM_STR,64);
				$stmt->bindParam(6, $CurrentTime, PDO::PARAM_STR);
				$stmt->execute();
				$insert_id = $db->lastInsertId();
				if($insert_id <= 0)
				{
					return 0;
				}
				else
				{
					
					for($i = 0;$i<sizeof($JobDescDtls->SkillName);$i++)
					{
						try
						{
							$isActive = 1;
							$Sqljdskill = "insert into  tbljdskill (JobDescId,SkillId,IsActive) 
									values (?, ?, ?)";	
							$istmt = $db->prepare($Sqljdskill);
							$istmt->bindParam(1,$insert_id, PDO::PARAM_INT);
							$istmt->bindParam(2, $JobDescDtls->SkillName[$i]->idSkill, PDO::PARAM_INT);
							$istmt->bindParam(3, $isActive, PDO::PARAM_INT);
							$istmt->execute();
						}
						catch(Exception $e)
						{
							
							throw new Exception ("Error:Err002" . $Sqljdskill. " " .$e->getMessage());
						}
					}
					
					for($j = 0;$j<sizeof($JobDescDtls->QualiName);$j++)
					{
						try
						{
							$isActive = 1;
							$Sqljdquali = "insert into tbljdquali(JobDescId,QualiId,IsActive) 
									values (?, ?, ?)";	
							$jstmt = $db->prepare($Sqljdquali);
							$jstmt->bindParam(1,$insert_id, PDO::PARAM_INT);
							$jstmt->bindParam(2, $JobDescDtls->QualiName[$j]->idQuali, PDO::PARAM_INT);
							$jstmt->bindParam(3, $isActive, PDO::PARAM_INT);
							$jstmt->execute();
						}
						catch(Exception $e)
						{
							
							throw new Exception ("Error:Err003" . $Sqljdquali. " " .$e->getMessage());
						}
					}
					
					return 1;
				}
			}
			catch(Exception $e)
			{
				throw new Exception ("Error:Err001" . $SqlJobDesc. " " .$e->getMessage());
			}
		}
		
		public function getJobDescPos($db)
		{
			try{
				$sql = "SELECT p.idPosition, p.Position
						FROM  `dimposition` p
						JOIN tbljobdesc jd ON jd.PositionId = p.idPosition"; 
					$stmt = $db->prepare($sql);
					$stmt->execute();
					$ArrJobDescPos = $stmt->fetchAll(PDO::FETCH_ASSOC);
					return $ArrJobDescPos;
			}
			catch(Exception $e)
			{
				throw new Exception ($e->getMessage());
			}
		}
		
		public function UpdateJDQual($db,$JDDtls)
		{
			try{
				$isActive = 0;
				$sql = "update tbljdquali set IsActive = ? where JobDescId = ?"; 
					$stmt = $db->prepare($sql);
					$stmt->bindParam(1, $isActive, PDO::PARAM_INT);
					$stmt->bindParam(2, $JDDtls->idJobDesc, PDO::PARAM_INT);
					$stmt->execute();
					return 1;
			}
			catch(Exception $e)
			{
				throw new Exception ($e->getMessage());
			}
		}
		public function UpdateJDSkill($db,$JDDtls)
		{
			try{
				$isActive = 0;
				$sql = "update tbljdskill set IsActive = ? where JobDescId = ?"; 
					$stmt = $db->prepare($sql);
					$stmt->bindParam(1, $isActive, PDO::PARAM_INT);
					$stmt->bindParam(2, $JDDtls->idJobDesc, PDO::PARAM_INT);
					$stmt->execute();
					return 1;
			}
			catch(Exception $e)
			{
				throw new Exception ($e->getMessage());
			}
		}
		public function UpdateJD($db,$JDDtls)
		{
			try{
				//	idJobDesc	PositionId	ExperianceMin	ExperianceMAx	Role	Responsibility
				$sql = "update tbljobdesc set ExperianceMin = ?,ExperianceMAx = ?,Role = ?,Responsibility = ? where idJobDesc = ?"; 
					$stmt = $db->prepare($sql);
					$stmt->bindParam(1, $JDDtls->minexp, PDO::PARAM_INT);
					$stmt->bindParam(2, $JDDtls->maxexp, PDO::PARAM_INT);
					$stmt->bindParam(3, $JDDtls->Role, PDO::PARAM_STR,64);
					$stmt->bindParam(4, $JDDtls->Responsibility, PDO::PARAM_STR,64);
					$stmt->bindParam(5, $JDDtls->idJobDesc, PDO::PARAM_INT);
					$stmt->execute();
					return 1;
			}
			catch(Exception $e)
			{
				throw new Exception ($e->getMessage());
			}
		}
		public function EditJDQual($db,$JDDtls)
		{
				for($i=0;$i<sizeof($JDDtls->QualiName);$i++)
				{
					$qualiId=0;
					if(isset($JDDtls->QualiName[$i]->idQuali) || $JDDtls->QualiName[$i]->idQuali != "")
					{
						$qualiId = $JDDtls->QualiName[$i]->idQuali;
					}
					else
					{
						$qualiId = $JDDtls->QualiName[$i]->QualiId;
					}
					try{
						$countRow = 0;
						$sql = "SELECT idJDQuali FROM `tbljdquali` where JobDescId = ? and QualiId = ?"; 
							$stmt = $db->prepare($sql);
							$stmt->bindParam(1, $JDDtls->idJobDesc, PDO::PARAM_INT);
							$stmt->bindParam(2, $qualiId, PDO::PARAM_INT);
							$stmt->execute();
							$countRow = $stmt->rowCount();
							if($countRow > 0)
							{
								try{
										$isActive = 1;
										$sqlJDQ = "update tbljdquali set IsActive = ? where JobDescId = ? and QualiId = ?"; 
											$stmtu = $db->prepare($sqlJDQ);
											$stmtu->bindParam(1, $isActive, PDO::PARAM_INT);
											$stmtu->bindParam(2, $JDDtls->idJobDesc, PDO::PARAM_INT);
											$stmtu->bindParam(3, $qualiId, PDO::PARAM_INT);
											$stmtu->execute();
											
									}
									catch(Exception $e)
									{
										throw new Exception ($e->getMessage());
									}
							}
							else
							{
								try
								{
									$isActive = 1;
									$Sqljdquali = "insert into tbljdquali(JobDescId,QualiId,IsActive) 
											values (?, ?, ?)";	
									$jstmt = $db->prepare($Sqljdquali);
									$jstmt->bindParam(1,$JDDtls->idJobDesc, PDO::PARAM_INT);
									$jstmt->bindParam(2, $qualiId, PDO::PARAM_INT);
									$jstmt->bindParam(3, $isActive, PDO::PARAM_INT);
									$jstmt->execute();
									
								}
								catch(Exception $e)
								{
									throw new Exception ($e->getMessage());
								}
							}
							
					}
					catch(Exception $e)
					{
						throw new Exception ($e->getMessage());
					}
				}
				return 1;
		}
		
public function EditJDSkill($db,$JDDtls)
		{
			
				for($i=0;$i<sizeof($JDDtls->SkillName);$i++)
				{
					$skillId=0;
					if(isset($JDDtls->SkillName[$i]->idSkill) || $JDDtls->SkillName[$i]->idSkill != "")
					{
						$skillId = $JDDtls->SkillName[$i]->idSkill;
					}
					else
					{
						$skillId = $JDDtls->SkillName[$i]->SkillId;
					}
					try{
						//idJDSkill	JobDescId	SkillId	IsActive
						$countRow = 0;
						$sql = "SELECT idJDSkill FROM `tbljdskill` where JobDescId = ? and SkillId = ?"; 
							$stmt = $db->prepare($sql);
							$stmt->bindParam(1, $JDDtls->idJobDesc, PDO::PARAM_INT);
							$stmt->bindParam(2, $skillId, PDO::PARAM_INT);
							$stmt->execute();
							$countRow = $stmt->rowCount();
							if($countRow > 0)
							{
								try{
										$isActive = 1;
										$sqlJDS = "update tbljdskill set IsActive = ? where JobDescId = ? and SkillId = ?"; 
											$stmtu = $db->prepare($sqlJDS);
											$stmtu->bindParam(1, $isActive, PDO::PARAM_INT);
											$stmtu->bindParam(2, $JDDtls->idJobDesc, PDO::PARAM_INT);
											$stmtu->bindParam(3, $skillId, PDO::PARAM_INT);
											$stmtu->execute();
									}
									catch(Exception $e)
									{
										throw new Exception ($e->getMessage());
									}
							}
							else
							{
								try
								{
									$isActive = 1;
									$SqljdSkill = "insert into tbljdskill(JobDescId,SkillId,IsActive) 
											values (?, ?, ?)";	
									$jstmt = $db->prepare($SqljdSkill);
									$jstmt->bindParam(1,$JDDtls->idJobDesc, PDO::PARAM_INT);
									$jstmt->bindParam(2, $skillId, PDO::PARAM_INT);
									$jstmt->bindParam(3, $isActive, PDO::PARAM_INT);
									$jstmt->execute();
								}
								catch(Exception $e)
								{
									throw new Exception ($e->getMessage());
								}
							}
							
					}
					catch(Exception $e)
					{
						throw new Exception ($e->getMessage());
					}
				}
				return 1;
		}
			
		public function getJobDescRelatedPosition($db, $poDtls)
		{
			try
			{	
				$sql = "select j.idJobDesc, j.PositionId, j.ExperianceMin, j.ExperianceMAx, j.Role,
						j.Responsibility , p.Position , js.SkillId, jq.QualiId,
						s.SkillName,q.QualiName
						from tbljobdesc j 
						join dimposition p on p.idPosition = j.PositionId 
						join tbljdskill js on js.JobDescId = j.idJobDesc
						join tbljdquali jq on jq.JobDescId = j.idJobDesc 
						join dimskill s on s.idSkill = js.SkillId 
						join dimqualification q on q.idQuali = jq.QualiId where j.PositionId = ? and js.IsActive = 1 and jq.IsActive = 1";
				$sth = $db->prepare($sql);
				$sth->bindParam(1, $poDtls->idPosition, PDO::PARAM_INT);
				$sth->execute();
				
				$ArrJobDescDtls = $sth->fetchAll(PDO::FETCH_ASSOC);
				return  $ArrJobDescDtls;
			}
			catch(Exception $e)
			{
				throw new Exception ("Err006" . $SqlStatement. " " .$e->getMessage());
			}
		}
		
		public function getJDCnt($db,$LoginTime)
		{
			try {
				
				$sqlJD = "select count(*) from tbljobdesc where RegDate < '".$LoginTime."'";
				$stmt = $db->prepare($sqlJD);
		
				$stmt->execute();
				$result = $stmt->fetch(); 
				return $result;
			}
			catch(Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		}
		public function getAddJDCnt($db,$LoginTime)
		{
			try {
				
				$sqlJD = "select count(*) from tbljobdesc where RegDate > '".$LoginTime."'";
				$stmt = $db->prepare($sqlJD);
		
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