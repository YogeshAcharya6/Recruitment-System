<?php
function Login()
{
	$ObjDtlsofUser = (object) array();
	try
	{
		$db = connect_db();
		$app = \Slim\Slim::getInstance()->request();
		$login = json_decode($app->getBody());
		
		
		$resultVal = User::loginValidate($db, $login->userId, $login->userPassword);
		
		
		if (is_numeric($resultVal) && $resultVal > 0) {
			
			$idUser = $resultVal;
			$ObjDtlsofUser->idUser = $idUser;
			$clientIP = $app->getIp(); //get Ip address of user machine
			$ObjDtlsofUser->clientIP = $clientIP;
			 
			$arrConfig = Config::loadConfiguration($db);
			$ObjDtlsofUser->arrConfig = $arrConfig;
			
			$objConfig = new Config($arrConfig);
			$allowMultLogins = $objConfig->AllowMulSimultaneousLogins ;
			$SessionTimeOut  = $objConfig->SessionTimeOut ;
		
			$configarray = array();
			//separate key and value variable
			for($i = 0 ; $i < count($arrConfig); $i++)
			{
				$key = $arrConfig[$i]['searchkey'];
				$value = $arrConfig[$i]['searchvalue'];
				$configarray[$key] = $value;
			}
			
			$ObjDtlsofUser->arrConfig = $configarray;
			
			if($allowMultLogins == "N") {	//check wheather another session exists.
				
				$countActiveSession = LoginHistory::CheckActiveSession($db, $clientIP, $idUser);
				
				if($countActiveSession == 0){
					$fContinueWithLogin = true;
				} 
				else {
					$fContinueWithLogin = false;
					$result = array('result'=>'error','addndata'=>'Another session for the same user is already active');
				} 
			} else {
				$fContinueWithLogin = true;
			}
			if ($fContinueWithLogin) 
			{
				$objSession = new LoginHistory($db, $clientIP , $idUser);
				$IdString = $objSession->getIDString();
				$ObjDtlsofUser->IDString = $IdString;
				$result = array('result'=>'success','addndata'=>$ObjDtlsofUser); 
			}
		}
		else{
			$result = array('result'=>'error','addndata'=>'Username and Password does not match');
		} 
	}
	catch(Exception $e)
	{
		$result = array('result'=>'error','message'=> $e->getMessage());
	}
	echo json_encode($result);
}
function createUser()
	{
		$app = \Slim\Slim::getInstance()->request();
		$userDtls = json_decode($app->getBody());
		try
		{
			
			$db = connect_db();
			$db->beginTransaction();
			$objUser = new User();
			$resultCreateUser = $objUser->AddUser($db,$userDtls); 
			
			if ($resultCreateUser > 0) 
			{
				$db->commit();
				$result = array('result'=>'success','message'=> "Record inserted successfully");	
			}
			else
			if($resultCreateUser == 0)
			{
				$result = array('result'=>'error','message'=> "Username already exist");
			}   
			else
			{
				$db->rollback();
				$result = array('result'=>'error','message'=> "Record not inserted successfully");
			}
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		} 
		echo json_encode($result);
	}
function deactivateUser()
	{
		$app = \Slim\Slim::getInstance()->request();
		$userDtls = json_decode($app->getBody());
		try
		{
			
			$db = connect_db();
			$objUser = new User();
			$resultDeActivateUser = $objUser->deActivateUserUser($db,$userDtls); 
			if ($resultDeActivateUser == 1) 
			{
				$result = array('result'=>'success','message'=> "Record updated successfully");	
			}
			else
			if($resultDeActivateUser == 0)
			{
				$result = array('result'=>'error','message'=> "Record not updated successfully");
			}   

		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		} 
		echo json_encode($result);
	}

function SaveRR()
	{
		$app = \Slim\Slim::getInstance()->request();
		$RRdtls = json_decode($app->getBody());
		try
		{
			$db = connect_db();
			$db->beginTransaction();
			$objRR = new RR();
			$resultRR = $objRR->saveRR($db,$RRdtls); 
			if($resultRR > 0)
			{
				$objOpening = new Opening();
				$resultOpening = $objOpening->saveOpening($db,$resultRR,$RRdtls->Openings);
				if($resultOpening == 1)
				{
					$db->commit();
					$result = array('result'=>'success','message'=>"Record inserted successfully");
				}
				else
				{
					$db->rollback();
					$result = array('result'=>'error','message'=>"Record not inserted successfully");
				}
			}
			else
			{
				$db->rollback();
				$result = array('result'=>'error','message'=>"Record not inserted successfully");
			}
			
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
	function getEmpDept()
	{
		$app = \Slim\Slim::getInstance()->request();
		$empdtls = json_decode($app->getBody());
		try
		{
			$db = connect_db();
			$objUser = new User();
			$resultEmpDept = $objUser->getEmpDeptDtls($db,$empdtls); 
			if ($resultEmpDept) 
			{
				$result = array('result'=>'success','addndata'=>$resultEmpDept);	
			}
			else
			{
				$result = array('result'=>'error','message'=>"invalid employee no");
			}   
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
	function CertificationList()
	{
		$app = \Slim\Slim::getInstance()->request();
		
		try
		{
			$db = connect_db();
			$objCertification = new Certification();
			$resultCertification = $objCertification->getobjCertificationDtls($db); 
			if ($resultCertification) 
			{
				$result = array('result'=>'success','addndata'=>$resultCertification);	
			}
			else
			{
				$result = array('result'=>'error','message'=>"Error in sql");
			}   
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function JobDescPosList()
	{
		try
		{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objJobDescription = new JobDescription();
			$resultJobDescPos = $objJobDescription->getJobDescPos($db); 
			$ArrJobDescPos = array();
			$ArrJobDescPos = super_unique($resultJobDescPos,'Position');
			if ($ArrJobDescPos) 
			{
				$result = array('result'=>'success','addndata'=>$ArrJobDescPos);	
			}  
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}	
	

function changePassword()
	{

		try
		{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$password = json_decode($app->getBody());
			$objUser = new User( $db, $password->objContext->idUser);
			$resultChangePassword = $objUser->ChangeUserPassword($db, $password);
			if($resultChangePassword == 1)
			{
				$result = array('result'=>'success','addndata'=>"Updated successfully");
			}
			else
			{
				$result = array('result'=>'error','message'=>$resultChangePassword);
			}
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getJDRelPosDtls()
	{
		try
		{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$posDtls = json_decode($app->getBody());
			$objJobDescription = new JobDescription();
			$resultJd = $objJobDescription->getJobDescRelatedPosition($db,$posDtls);
			$ArrSkillJD = array();
			$ArrQualificationJD = array();
			$finalArrayJD = array();
			$strSkillName;
			$strQualiName;
			$strExperianceMin;
			$strExperianceMAx;
			$stridJobDesc;
			for($i=0;$i<sizeof($resultJd);$i++)
			{
				if($i == 0)
				{
					$strExperianceMin = $resultJd[$i]['ExperianceMin'];
					$strExperianceMAx =$resultJd[$i]['ExperianceMAx'];
					$stridJobDesc =$resultJd[$i]['idJobDesc'];
				}
				$strSkillName = $strSkillName.$resultJd[$i]['SkillName']." ";
				$strQualiName = $strQualiName.$resultJd[$i]['QualiName']." ";
			}
			$ArrQualificationJD = array_filter(array_unique(explode(" ",$strQualiName)));
			$ArrSkillJD = array_filter(array_unique(explode(" ",$strSkillName)));
			$finalArrayJD[0]->Skill = $ArrSkillJD;
			$finalArrayJD[0]->Qualification = $ArrQualificationJD;
			$finalArrayJD[0]->ExperianceMin = $strExperianceMin;
			$finalArrayJD[0]->ExperianceMAx = $strExperianceMAx;
			$finalArrayJD[0]->idJobDesc = $stridJobDesc;
			$result = array('result'=>'success','addndata'=>$finalArrayJD);
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function is_val_exists($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && is_val_exists($needle, $element))
               return true;
     }
   return false;
}
function getRelPosJDDtls()
	{
		try
		{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$posDtls = json_decode($app->getBody());
			$objJobDescription = new JobDescription();
			$ArrsearchResult = $objJobDescription->getJobDescRelatedPosition($db,$posDtls);
			$idJobDesc = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($ArrsearchResult); $i++)
				{	
					if($idJobDesc != $ArrsearchResult[$i]['idJobDesc'])
					{
						$j++;
						$finalArray[$j]['idJobDesc'] = $ArrsearchResult[$i]['idJobDesc'];
						$finalArray[$j]['QualiName'] = $ArrsearchResult[$i]['QualiName'];
						$finalArray[$j]['SkillName'] = $ArrsearchResult[$i]['SkillName'];
						$finalArray[$j]['ExperianceMin'] = $ArrsearchResult[$i]['ExperianceMin'];
						$finalArray[$j]['ExperianceMAx'] = $ArrsearchResult[$i]['ExperianceMAx'];
						$finalArray[$j]['Position'] = $ArrsearchResult[$i]['Position'];
						$finalArray[$j]['PositionId'] = $ArrsearchResult[$i]['PositionId'];
						$finalArray[$j]['Role'] = $ArrsearchResult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $ArrsearchResult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$idJobDesc = $ArrsearchResult[$i]['idJobDesc'];
					}//is_val_exists('your_value', $your_array)
					//if(!in_array($ArrsearchResult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					if(!is_val_exists($ArrsearchResult[$i]['QualiName'], $finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],array('QualiName' => $ArrsearchResult[$i]['QualiName'],'QualiId' => $ArrsearchResult[$i]['QualiName']));
					}
					if(!in_array($ArrsearchResult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$ArrsearchResult[$i]['SkillName']);
					}
				}
				if(sizeof($finalArray) > 0)
				{
					$result = array('result' => 'success','addndata'=>$finalArray);
				}
				else
				{
					
					$result = array('result' => 'error','message'=>'Record not found');
				}
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function RoleList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objRoles = new Roles();
			$arrRoleDtls = $objRoles->loadRoleDtls($db);
			if($arrRoleDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrRoleDtls);
			}
			else
			{
				$result = array('result'=>'error','addndata'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function StatusList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objStatus = new Status();
			$arrStatusDtls = $objStatus->loadStatusDtls($db);
	
			if($arrStatusDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrStatusDtls);
			}
			else
			{
				$result = array('result'=>'error','addndata'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function PositionList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objPositions = new Positions();
			$arrPositionDtls = $objPositions->loadPositionDtls($db);
	
			if($arrPositionDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrPositionDtls);
			}
			else
			{
				$result = array('result'=>'error','addndata'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function SkillList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objSkills = new Skills();
			$arrSkillDtls = $objSkills->loadSkillDtls($db);
	
			if($arrSkillDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrSkillDtls);
			}
			else
			{
				$result = array('result'=>'error','addndata'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function NonTechSkillList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objSkills = new Skills();
			$arrNonTechSkillDtls = $objSkills->loadNonTechSkillDtls($db);
	
			if($arrNonTechSkillDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrNonTechSkillDtls);
			}
			else
			{
				$result = array('result'=>'error','addndata'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function getResumeFileType()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$posDtls = json_decode($app->getBody());
			$arrConfig = Config::loadConfiguration($db);
			$objConfig = new Config($arrConfig);
			$ResumeFileType = $objConfig->getAttachedFileType($db);
			$result = array('result'=>'success','addndata'=>$ResumeFileType);
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function getEmployeeList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objUsers = new Users();
			$arrEmployeeDtls = $objUsers->loadEmployeeDtls($db);
	
			if(sizeof($arrEmployeeDtls))
			{
				$result = array('result'=>'success','addndata'=>$arrEmployeeDtls);
			}
			else
			{
				$result = array('result'=>'error','addndata'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function QualificationList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objQualifications = new Qualifications();
			$arrQualificationDtls = $objQualifications->loadQualificationDtls($db);
	
			if($arrQualificationDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrQualificationDtls);
			}
			else
			{
				$result = array('result'=>'error','addndata'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}

function saveRole()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$role = json_decode($app->getBody());
			$objRole = new Role();
			$objRole->setroleDescription($role->roledescription);
			$ResultAddRole = $objRole->Save($db);
			
			if($ResultAddRole == 1){
				$result = array('result' => 'success','addndata'=>'Record inserted successfully');
			}
			else{
				$result = array('result' => 'error','addndata'=>'Duplicate Entry');
			}  
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
	
function SaveCandidate()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$candidatedtls = json_decode($app->getBody());
			$db->beginTransaction();
			$objCandidate = new Candidate();
			$resultCandidate = $objCandidate->SaveCandidate($db,$candidatedtls);
			$db->commit(); 
			$result = array('result' => 'success','message'=>"Record Inserted Successfully");
			
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}

function editJDDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$JDDtls = json_decode($app->getBody());
			
			$db->beginTransaction();
			$objPosition = new Position();
			$objPosition->setidPosition($JDDtls->PositionId);
			$objPosition->setPosition($JDDtls->Position);
			$resultPosition = $objPosition->Update($db);
			if($resultPosition)
			{
				$objJobDescription = new JobDescription();
				$resultUpdateJDQual = $objJobDescription->UpdateJDQual($db,$JDDtls);
				if($resultUpdateJDQual)
				{
					$resultUpdateJDSkill = $objJobDescription->UpdateJDSkill($db,$JDDtls);
					if($resultUpdateJDSkill)
					{
						$resultJD = $objJobDescription->UpdateJD($db,$JDDtls);
						if($resultJD)
						{
							$resultEditJDQual = $objJobDescription->EditJDQual($db,$JDDtls);
							if($resultEditJDQual)
							{
								$resultEditJDSkill = $objJobDescription->EditJDSkill($db,$JDDtls);
								
								if($resultEditJDSkill)
								{
									$db->commit();
									$result = array('result' => 'success','message'=>'Record updated successfully');
								}
								else
								{
									$db->rollback();
									$result = array('result' => 'error','message'=>'Record not updated successfully');
								}
							}
							else
							{
								$db->rollback();
								$result = array('result' => 'error','message'=>'Error in sql5');
							}  
						}
						else
						{
							$result = array('result' => 'error','message'=>'Error in sql4');
						} 
					}
					else
					{
						$result = array('result' => 'error','message'=>'Error in sql3');
					}
				}
				else
				{
					$result = array('result' => 'error','message'=>'Error in sql2');
				}
			}
			else
			{
				$result = array('result' => 'error','message'=>'Error in sql1');
			} 
		}
		catch(Exception $e)
		{
			$db->rollback(); 
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function saveJobDescDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$JobDescDtls = json_decode($app->getBody());
			$db->beginTransaction();
			$objPosition = new Position();
			$resultposition = $objPosition->Save($db,$JobDescDtls->Position);
			if($resultposition > 0)
			{
				$objJobDescription = new JobDescription();
				$resultJobDesc = $objJobDescription->Save($db,$JobDescDtls,$resultposition); 
				
				if($resultJobDesc == 1)
				{
					$db->commit();
					$result = array('result' => 'success','message'=>'Record inserted successfully');
				}
				else
				{
					$db->rollback();
					$result = array('result' => 'error','message'=>'Record not inserted successfully');
				}	
			}
			else
			{
				$result = array('result' => 'error','message'=>'job description of this Position allready exists');
			} 
			
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function saveElement()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$Element = json_decode($app->getBody());
			$objElement = new Element();
			$objElement->setelementDesc($Element->elementDesc);
			$objElement->setelementTypeId($Element->elementType->idElementType);
			$ResultAddElement = $objElement->Save($db);
			
			if($ResultAddElement == 1){
				$result = array('result' => 'success','addndata'=>'Record inserted successfully');
			}
			else{
				$result = array('result' => 'error','addndata'=>'Duplicate Entry');
			}   
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function saveRRSchedule()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$scheduleDtls = json_decode($app->getBody());
		
			
			/* $result = array('result' => 'success','message'=>$scheduleDtls); */
			$db->beginTransaction();
		    $objRRCandidate = new RRCandidate();
			$ResultRRSchedule = $objRRCandidate->SaveRRSchedule($db,$scheduleDtls);
			
			if($ResultRRSchedule == 1){
				$objCandidate = new Candidate();
				$resultCandidate = $objCandidate->UpdateCandidateStatus($db,$scheduleDtls);
				if($resultCandidate == 1)
				{
					$db->commit();
					$result = array('result' => 'success','message'=>'Record inserted successfully');
				}
				else
				{
					$bd->rollback();
					$result = array('result' => 'error','message'=>'Record not inserted successfully');
				}
			}
			else{
				$db->rollback();
				$result = array('result' => 'error','message'=>'Record not inserted successfully');
			}   
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function updateRRSchedule()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$scheduleDtls = json_decode($app->getBody());
			$db->beginTransaction();
		    $objRRCandidate = new RRCandidate();
			$ResultRRSchedule = $objRRCandidate->updateRRSchedule($db,$scheduleDtls);
			if($ResultRRSchedule == 1)
			{
				$db->commit();
				$result = array('result' => 'success','message'=>'Record updated successfully');
			}
			else
			{
				$db->rollback();
				$result = array('result' => 'success','message'=>'Record not updated successfully');
			}
			
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}

function editRole()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$roledtl = json_decode($app->getBody());
			$objRole = new Role();
			$objRole->setroleDescription($roledtl->roledescription);
			$objRole->setroleId($roledtl->idRole);
			
			$ResultUpdateRole = $objRole->Update($db);
			
			if($ResultUpdateRole == 1){
				$result = array('result' => 'success','addndata'=>'Record Updated successfully');
			}
			else{
				$result = array('result' => 'error','addndata'=>'Duplicate Entry');
			}  
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
	
function UpdateRR()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$RRDtls = json_decode($app->getBody());
			$db->beginTransaction();
			$objRR = new RR();
			$ResultUpdateRR = $objRR->Update($db,$RRDtls);
			if($ResultUpdateRR == 1)
			{
				$objOpening = new Opening();
				$ResultUpdateOpening = $objOpening->Update($db,$RRDtls);
				if($ResultUpdateOpening == 1)
				{
					$db->commit();
					$result = array('result' => 'success','message'=>'Record Updated successfully');
				}
				else
				{
					$db->rollback();
					$result = array('result' => 'error','message'=>'Record not Updated successfully');
				}
			}
			else
			{
				$result = array('result' => 'error','message'=>'Record not Updated successfully');
			} 
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function AddRRCandidate()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$RRDtls = json_decode($app->getBody());
			$objRRCandidate = new RRCandidate();
			$resultSaveRRCandidate = $objRRCandidate->SaveRRCandidate($db,$RRDtls);
			if($resultSaveRRCandidate == 1)
			{
				$resultCountRRCandidateArr = $objRRCandidate->CountRRCandidate($db,$RRDtls);
				if(sizeof($resultCountRRCandidateArr) == 0)
				{
					$result = array('result' => 'success','message'=>'Record inserted successfully');
				}
				else
				{
					$objCandidate = new Candidate();
					$resultcandidate = $objCandidate->changeCandidateStatus($db,$resultCountRRCandidateArr);
					if($resultcandidate == 1)
					{
						$result = array('result' => 'success','message'=>'Record inserted successfully');
					}
					else
					{
						$result = array('result' => 'success','message'=>'Record not inserted successfully');
					}
				}
			}	
			else
			{
				$result = array('result' => 'success','message'=>'Record not inserted successfully');
			}
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getPositionRelCandidate()
	{
		try{

			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$RRDtls = json_decode($app->getBody());
			$objRRCandidate = new RRCandidate();
			$ArrPosRelCandidate = $objRRCandidate->getPosRelated($db,$RRDtls);		
			$idCandidate = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($ArrPosRelCandidate); $i++)
				{	
					if($idCandidate != $ArrPosRelCandidate[$i]['idCandidate'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $ArrPosRelCandidate[$i]['idCandidate'];
						$finalArray[$j]['CandidateName'] = $ArrPosRelCandidate[$i]['CandidateName'];
						
						$finalArray[$j]['RegDate'] = $ArrPosRelCandidate[$i]['RegDate'];
						
						$finalArray[$j]['idRRCandidate'] = $ArrPosRelCandidate[$i]['idRRCandidate'];
						$finalArray[$j]['idCandiPosStatus'] = $ArrPosRelCandidate[$i]['idCandiPosStatus'];
						$finalArray[$j]['candiPosStatusDesc'] = $ArrPosRelCandidate[$i]['candiPosStatusDesc'];
						$finalArray[$j]['NameArr'] = array();
						$idCandidate = $ArrPosRelCandidate[$i]['idCandidate'];
					}
					if($ArrPosRelCandidate[$i]['IsActiveInteraction'] == 1 || 
						($ArrPosRelCandidate[$i]['IsActiveInteraction'] == 0 &&
						!isset($finalArray[$j]['IsActiveInteraction']))){
						$finalArray[$j]['IsActiveInteraction'] = $ArrPosRelCandidate[$i]['IsActiveInteraction'];
						if($ArrPosRelCandidate[$i]['IsActiveInteraction'] == 1){
							if(!in_array($ArrPosRelCandidate[$i]['Name'],$finalArray[$j]['NameArr']))
							{
								array_push($finalArray[$j]['NameArr'],$ArrPosRelCandidate[$i]['Name']);
							}
							$finalArray[$j]['interviewTypeDesc'] = $ArrPosRelCandidate[$i]['interviewTypeDesc'];
							$finalArray[$j]['intDate'] = $ArrPosRelCandidate[$i]['intDate'];
							$finalArray[$j]['altIntDate'] = $ArrPosRelCandidate[$i]['altIntDate'];
						}
					}

					
				}
			if(sizeof($finalArray)>0)
			{
				$result = array('result' => 'success','addndata'=>$finalArray);
			}
			else
			{
				$result = array('result' => 'error','message'=>'Record not found');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getInterviewTypeList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objInterviewType = new InterviewType();
			$ArrInterviewType = $objInterviewType->LoadInterviewTypeList($db);
			if(sizeof($ArrInterviewType)>0)
			{
				$result = array('result' => 'success','addndata'=>$ArrInterviewType);
			}
			else
			{
				$result = array('result' => 'success','message'=>'Record not found');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}

function searchJobDescDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$jobdtls = json_decode($app->getBody());
			$objJobDescription = new SearchJobDescription();
			
			if(sizeof($jobdtls->QualiName) > 0){  
				$ArrQualification = $jobdtls->QualiName;
				$objJobDescription->setQualiName($ArrQualification);
			}
			if(sizeof($jobdtls->SkillName) > 0){  
				$ArrSkill = $jobdtls->SkillName;
				$objJobDescription->setSkillName($ArrSkill);
			}
			if($jobdtls->maxexp != ""){  
				$objJobDescription->setmaxexp($jobdtls->maxexp);
			}
			if($jobdtls->minexp != ""){  
				$objJobDescription->setminexp($jobdtls->minexp);
			}
			$JobDescSqlStatement = $objJobDescription->getJobDescSqlStatement();
			$ArrsearchResult = $objJobDescription->Search($db,$JobDescSqlStatement);
			//$result = array('result' => 'success','addndata'=>$ArrsearchResult);
			$idJobDesc = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($ArrsearchResult); $i++)
				{	
					if($idJobDesc != $ArrsearchResult[$i]['idJobDesc'])
					{
						$j++;
						$finalArray[$j]['idJobDesc'] = $ArrsearchResult[$i]['idJobDesc'];
						$finalArray[$j]['QualiName'] = $ArrsearchResult[$i]['QualiName'];
						$finalArray[$j]['SkillName'] = $ArrsearchResult[$i]['SkillName'];
						$finalArray[$j]['ExperianceMin'] = $ArrsearchResult[$i]['ExperianceMin'];
						$finalArray[$j]['ExperianceMAx'] = $ArrsearchResult[$i]['ExperianceMAx'];
						$finalArray[$j]['Position'] = $ArrsearchResult[$i]['Position'];
						$finalArray[$j]['PositionId'] = $ArrsearchResult[$i]['PositionId'];
						$finalArray[$j]['Role'] = $ArrsearchResult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $ArrsearchResult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$idJobDesc = $ArrsearchResult[$i]['idJobDesc'];
					}
					if(!is_val_exists($ArrsearchResult[$i]['QualiName'], $finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],array('QualiName' => $ArrsearchResult[$i]['QualiName'],'QualiId' => $ArrsearchResult[$i]['QualiId']));
					}
					if(!is_val_exists($ArrsearchResult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],array('SkillName' => $ArrsearchResult[$i]['SkillName'],'SkillId' => $ArrsearchResult[$i]['SkillId']));
					}
				}
				if(sizeof($finalArray) > 0)
				{
					$result = array('result' => 'success','addndata'=>$finalArray);
				}
				else
				{
					
					$result = array('result' => 'error','message'=>'Record not found');
				}  
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getJobDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$LoginTime = LoginHistory::getLoginTime($db, $CandidateHisTDtls);
			$objJobDescription = new SearchJobDescription();
			$JobDescSqlStatement = $objJobDescription->getJobDescSqlStatement();
			$ArrsearchResult = $objJobDescription->getJDDtls($db,$JobDescSqlStatement,$LoginTime);
			$idJobDesc = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($ArrsearchResult); $i++)
				{	
					if($idJobDesc != $ArrsearchResult[$i]['idJobDesc'])
					{
						$j++;
						$finalArray[$j]['idJobDesc'] = $ArrsearchResult[$i]['idJobDesc'];
						$finalArray[$j]['QualiName'] = $ArrsearchResult[$i]['QualiName'];
						$finalArray[$j]['SkillName'] = $ArrsearchResult[$i]['SkillName'];
						$finalArray[$j]['ExperianceMin'] = $ArrsearchResult[$i]['ExperianceMin'];
						$finalArray[$j]['ExperianceMAx'] = $ArrsearchResult[$i]['ExperianceMAx'];
						$finalArray[$j]['Position'] = $ArrsearchResult[$i]['Position'];
						$finalArray[$j]['PositionId'] = $ArrsearchResult[$i]['PositionId'];
						$finalArray[$j]['Role'] = $ArrsearchResult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $ArrsearchResult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$idJobDesc = $ArrsearchResult[$i]['idJobDesc'];
					}
					if(!in_array($ArrsearchResult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],$ArrsearchResult[$i]['QualiName']);
					}
					if(!in_array($ArrsearchResult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$ArrsearchResult[$i]['SkillName']);
					}
				}
				if(sizeof($finalArray) > 0)
				{
					$result = array('result' => 'success','addndata'=>$finalArray);
				}
				else
				{
					
					$result = array('result' => 'error','message'=>'Record not found');
				}  
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getAddJobDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$LoginTime = LoginHistory::getLoginTime($db, $CandidateHisTDtls);
			$objJobDescription = new SearchJobDescription();
			$JobDescSqlStatement = $objJobDescription->getJobDescSqlStatement();
			$ArrsearchResult = $objJobDescription->getAddJDDtls($db,$JobDescSqlStatement,$LoginTime);
			//$result = array('result' => 'success','addndata'=>$ArrsearchResult);
			$idJobDesc = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($ArrsearchResult); $i++)
				{	
					if($idJobDesc != $ArrsearchResult[$i]['idJobDesc'])
					{
						$j++;
						$finalArray[$j]['idJobDesc'] = $ArrsearchResult[$i]['idJobDesc'];
						$finalArray[$j]['QualiName'] = $ArrsearchResult[$i]['QualiName'];
						$finalArray[$j]['SkillName'] = $ArrsearchResult[$i]['SkillName'];
						$finalArray[$j]['ExperianceMin'] = $ArrsearchResult[$i]['ExperianceMin'];
						$finalArray[$j]['ExperianceMAx'] = $ArrsearchResult[$i]['ExperianceMAx'];
						$finalArray[$j]['Position'] = $ArrsearchResult[$i]['Position'];
						$finalArray[$j]['PositionId'] = $ArrsearchResult[$i]['PositionId'];
						$finalArray[$j]['Role'] = $ArrsearchResult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $ArrsearchResult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$idJobDesc = $ArrsearchResult[$i]['idJobDesc'];
					}
					if(!in_array($ArrsearchResult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],$ArrsearchResult[$i]['QualiName']);
					}
					if(!in_array($ArrsearchResult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$ArrsearchResult[$i]['SkillName']);
					}
				}
				if(sizeof($finalArray) > 0)
				{
					$result = array('result' => 'success','addndata'=>$finalArray);
				}
				else
				{
					
					$result = array('result' => 'error','message'=>'Record not found');
				}  
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getAddRRDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$LoginTime = LoginHistory::getLoginTime($db, $CandidateHisTDtls);
			$objSearchRecruitmentRequest = new SearchRecruitmentRequest();
			$RRSqlStatement = $objSearchRecruitmentRequest->getRRSqlStatement();
			$ArrsearchResult = $objSearchRecruitmentRequest->getAddRRDtls($db,$RRSqlStatement,$LoginTime);
				if(sizeof($ArrsearchResult) > 0)
				{
					$result = array('result' => 'success','addndata'=>$ArrsearchResult);
				}
				else
				{
					
					$result = array('result' => 'error','message'=>'Error in sql');
				}  
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function saveCandiFeedback()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateFeedbackDtls = json_decode($app->getBody());
			$db->beginTransaction();
			$objRRCandidate = new RRCandidate();
			$resultfeedback = $objRRCandidate->saveCandiFeedback($db,$CandidateFeedbackDtls);
			if($resultfeedback == 1)
			{
				$db->commit();
				$result = array('result' => 'success','message'=>"Record inserted successfully");
			}
			else
			{
				$db->rollback();
				$result = array('result' => 'success','addndata'=>"Record not inserted successfully");
			} 
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function updateCandiFeedback()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateFeedbackDtls = json_decode($app->getBody());
			$db->beginTransaction();
			/* $result = array('result' => 'success','message'=>$CandidateFeedbackDtls); */
			$objRRCandidate = new RRCandidate();
			$resultfeedback = $objRRCandidate->updateCandiFeedback($db,$CandidateFeedbackDtls);
			if($resultfeedback == 1)
			{
				$db->commit();
				$result = array('result' => 'success','message'=>"Record updated successfully");
			}
			else
			{
				$db->rollback();
				$result = array('result' => 'error','message'=>"Record not updated successfully");
			} 
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}

function getAddCandidateDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$LoginTime = LoginHistory::getLoginTime($db, $CandidateHisTDtls);
			$objSearchCandidate = new SearchCandidate();
			$CandidateSqlStatement = $objSearchCandidate->getCandidateSqlStatement();
			$Sqlresult = $objSearchCandidate->getAddCandidateDtls($db,$CandidateSqlStatement,$LoginTime);
			$idCandidate = -1;
				$j = -1;
				$finalArray = array();
				for($i =0; $i < sizeof($Sqlresult); $i++)
				{	
					if($idCandidate != $Sqlresult[$i]['idCandidate'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $Sqlresult[$i]['idCandidate'];
						$finalArray[$j]['CandidateName'] = $Sqlresult[$i]['CandidateName'];
						$finalArray[$j]['TotalExp'] = $Sqlresult[$i]['TotalExp'];
						$finalArray[$j]['RelevantExp'] = $Sqlresult[$i]['RelevantExp'];
						$finalArray[$j]['NoticePeriod'] = $Sqlresult[$i]['NoticePeriod'];
						$finalArray[$j]['Resume'] = $Sqlresult[$i]['Resume'];
						$finalArray[$j]['CurrentCompany'] = $Sqlresult[$i]['CurrentCompany'];
						$finalArray[$j]['CurrentSalary'] = $Sqlresult[$i]['CurrentSalary'];
						$finalArray[$j]['CurrentDesignation'] = $Sqlresult[$i]['CurrentDesignation'];
						$finalArray[$j]['MobNo'] = $Sqlresult[$i]['MobNo'];
						$finalArray[$j]['AltContactNo'] = $Sqlresult[$i]['AltContactNo'];
						$finalArray[$j]['EmailId'] = $Sqlresult[$i]['EmailId'];
						$finalArray[$j]['Address'] = $Sqlresult[$i]['Address'];
						$finalArray[$j]['RegDate'] = $Sqlresult[$i]['RegDate'];
						$finalArray[$j]['Role'] = $Sqlresult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $Sqlresult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$finalArray[$j]['NonTechSkillArr'] = array();
						$finalArray[$j]['CertificationArr'] = array();
						$idCandidate = $Sqlresult[$i]['idCandidate'];
					}
					if(!in_array($Sqlresult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],$Sqlresult[$i]['QualiName']);
					}
					if(!in_array($Sqlresult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$Sqlresult[$i]['SkillName']);
					}
					if(!in_array($Sqlresult[$i]['NonTechSkill'],$finalArray[$j]['NonTechSkillArr']))
					{
						array_push($finalArray[$j]['NonTechSkillArr'],$Sqlresult[$i]['NonTechSkill']);
					}
					if(!in_array($Sqlresult[$i]['Certification'],$finalArray[$j]['CertificationArr']))
					{
						array_push($finalArray[$j]['CertificationArr'],$Sqlresult[$i]['Certification']);
					}
				}
				if(sizeof($finalArray)>0)
				{
					$result = array('result' => 'success','addndata'=>$finalArray);
				}
				else
				{
					$result = array('result' => 'error','addndata'=>'Record not found');
				}
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function searchRRDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$RRdtls = json_decode($app->getBody());
	
			$objSearchRecruitmentRequest = new SearchRecruitmentRequest();
			if(isset($RRdtls->Department) && $RRdtls->Department != "")
			{
				$objSearchRecruitmentRequest->setidDept($RRdtls->Department->idDept);
			}
			if(isset($RRdtls->Status) && $RRdtls->Status != "")
			{
				$objSearchRecruitmentRequest->setidStatus($RRdtls->Status->idStatus);
			}
			if(isset($RRdtls->EmpId) && $RRdtls->EmpId != "")
			{
				$objSearchRecruitmentRequest->setEmpId($RRdtls->EmpId);
			}
			if(isset($RRdtls->Expected) && $RRdtls->Expected != "")
			{
				$objSearchRecruitmentRequest->setclouser($RRdtls->Expected);
			}
			if(isset($RRdtls->Request) && $RRdtls->Request != "")
			{
				$objSearchRecruitmentRequest->setrequest($RRdtls->Request);
			}
			$RRSqlStatement = $objSearchRecruitmentRequest->getRRSqlStatement();
			
			$ArrsearchResult = $objSearchRecruitmentRequest->Search($db,$RRSqlStatement);
			if($ArrsearchResult)
			{
				$result = array('result' => 'success','addndata'=>$ArrsearchResult);
			}
			else
			{
				$result = array('result' => 'error','message'=>'Record not found');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getActivePositionDtlsList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			
			$objSearchRecruitmentRequest = new SearchRecruitmentRequest();
			$ArrActivePositionDtls = $objSearchRecruitmentRequest->getActivePositionDtls($db);
			$objRRCandidate = new RRCandidate();
			$ArrAssignCaniSchedule = $objRRCandidate->getAssignCandiDtls($db);
			$idRRSchedule = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($ArrAssignCaniSchedule); $i++)
				{	
					if($idRRSchedule != $ArrAssignCaniSchedule[$i]['idRRSchedule'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $ArrAssignCaniSchedule[$i]['idCandidate'];
						$finalArray[$j]['RRId'] = $ArrAssignCaniSchedule[$i]['RRId'];
						$finalArray[$j]['idRRSchedule'] = $ArrAssignCaniSchedule[$i]['idRRSchedule'];
						$finalArray[$j]['IsActiveInteraction'] = $ArrAssignCaniSchedule[$i]['IsActiveInteraction'];
						$finalArray[$j]['CandidateName'] = $ArrAssignCaniSchedule[$i]['CandidateName'];
						$finalArray[$j]['RegDate'] = $ArrAssignCaniSchedule[$i]['RegDate'];
						$finalArray[$j]['interviewTypeDesc'] = $ArrAssignCaniSchedule[$i]['interviewTypeDesc'];
						$finalArray[$j]['intDate'] = $ArrAssignCaniSchedule[$i]['intDate'];
						$finalArray[$j]['intDate1'] = $ArrAssignCaniSchedule[$i]['intDate1'];
						$finalArray[$j]['altIntDate'] = $ArrAssignCaniSchedule[$i]['altIntDate'];
						$finalArray[$j]['altIntDate1'] = $ArrAssignCaniSchedule[$i]['altIntDate1'];
						$finalArray[$j]['idRRCandidate'] = $ArrAssignCaniSchedule[$i]['idRRCandidate'];
						$finalArray[$j]['idCandiPosStatus'] = $ArrAssignCaniSchedule[$i]['idCandiPosStatus'];
						$finalArray[$j]['candiPosStatusDesc'] = $ArrAssignCaniSchedule[$i]['candiPosStatusDesc'];
						$finalArray[$j]['NameArr'] = array();
						$idRRSchedule = $ArrAssignCaniSchedule[$i]['idRRSchedule'];
					}
					if(!in_array($ArrAssignCaniSchedule[$i]['Name'],$finalArray[$j]['NameArr']))
					{
						array_push($finalArray[$j]['NameArr'],$ArrAssignCaniSchedule[$i]['Name']);
					}
				}
			
			if(sizeof($ArrActivePositionDtls)>0)
			{
				$objOpening = new Opening();
				$ArrOpeningCnt = $objOpening->getOpeningPositionCnt($db,$ArrActivePositionDtls);
				for($i=0;$i<sizeof($ArrActivePositionDtls);$i++)
				{
					for($j=0;$j<sizeof($ArrOpeningCnt);$j++)
					{
						if($ArrActivePositionDtls[$i]['idRR'] == $ArrOpeningCnt[$j][0]['RRId'])
						{
							if($ArrActivePositionDtls[$i]['Openings'] == $ArrOpeningCnt[$j][0]['count'])
							{
								$Fill = 0;
							}
							else
							{
								$Fill = $ArrActivePositionDtls[$i]['Openings']-$ArrOpeningCnt[$j][0]['count'];
							}
							$ArrActivePositionDtls[$i]['Fill'] = $Fill;
							$ArrActivePositionDtls[$i]['remainingOpening'] = $ArrOpeningCnt[$j][0]['count'];
						}
					}
				}
				for($k=0;$k<sizeof($ArrActivePositionDtls);$k++)
				{
					$count = 0;
					for($l=0;$l<sizeof($finalArray);$l++)
					{
						if($ArrActivePositionDtls[$k]['idRR'] == $finalArray[$l]['RRId'])
						{
							$ArrActivePositionDtls[$k]['rrScedule'][$count] = $finalArray[$l];
							$count++;
						}
					}
					if($count==0)
					{
						$ArrActivePositionDtls[$k]['rrScedule'] = array();
					}
				}
				if(sizeof($ArrOpeningCnt)>0)
				{
					$result = array('result' => 'success','addndata'=>$ArrActivePositionDtls);
				}
				else
				{
					$result = array('result' => 'error','message'=>'Error in sql');
				}
			}
			else
			{
				$result = array('result' => 'error','message'=>'Record not found');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function IntInterfaceDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			
			$objSearchRecruitmentRequest = new SearchRecruitmentRequest();
			$ArrActivePositionDtls = $objSearchRecruitmentRequest->getActivePositionDtls($db);
			
			$objRRCandidate = new RRCandidate();
			$ArrActiveFeedback = $objRRCandidate->getActiveFeedbackDtls($db);
			$ArrAssignCaniSchedule = $objRRCandidate->getInteractionCandiDtls($db);
			$idCandidate = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($ArrAssignCaniSchedule); $i++)
				{	
					if($idCandidate != $ArrAssignCaniSchedule[$i]['idCandidate'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $ArrAssignCaniSchedule[$i]['idCandidate'];
						$finalArray[$j]['RRId'] = $ArrAssignCaniSchedule[$i]['RRId'];
						$finalArray[$j]['idRRCandidateFeedback'] = $ArrAssignCaniSchedule[$i]['idRRCandidateFeedback'];
						$finalArray[$j]['Resume'] = $ArrAssignCaniSchedule[$i]['Resume'];
						$finalArray[$j]['idRRSchedule'] = $ArrAssignCaniSchedule[$i]['idRRSchedule'];
						$finalArray[$j]['CandidateName'] = $ArrAssignCaniSchedule[$i]['CandidateName'];
						$finalArray[$j]['RegDate'] = $ArrAssignCaniSchedule[$i]['RegDate'];
						$finalArray[$j]['interviewTypeDesc'] = $ArrAssignCaniSchedule[$i]['interviewTypeDesc'];
						$finalArray[$j]['idInterviewType'] = $ArrAssignCaniSchedule[$i]['idInterviewType'];
						$finalArray[$j]['intDate'] = $ArrAssignCaniSchedule[$i]['intDate'];
						$finalArray[$j]['altIntDate'] = $ArrAssignCaniSchedule[$i]['altIntDate'];
						$finalArray[$j]['idRRCandidate'] = $ArrAssignCaniSchedule[$i]['idRRCandidate'];
						$finalArray[$j]['idCandiPosStatus'] = $ArrAssignCaniSchedule[$i]['idCandiPosStatus'];
						$finalArray[$j]['candiPosStatusDesc'] = $ArrAssignCaniSchedule[$i]['candiPosStatusDesc'];
						$finalArray[$j]['NameArr'] = array();
						$idCandidate = $ArrAssignCaniSchedule[$i]['idCandidate'];
					}
					if(!in_array($ArrAssignCaniSchedule[$i]['Name'],$finalArray[$j]['NameArr']))
					{
						array_push($finalArray[$j]['NameArr'],$ArrAssignCaniSchedule[$i]['Name']);
					}
				}
			for($j=0;$j<sizeof($finalArray);$j++)
			{
				$count = 0;
				for($k=0;$k<sizeof($ArrActiveFeedback);$k++)
				{
					if($finalArray[$j]['idRRCandidate'] == $ArrActiveFeedback[$k]['CandidateRRId'])
					{
						$finalArray[$j]['ActiveFeedback'] = 1;
						$count++;
					}
				}
				if($count == 0)
				{
					$finalArray[$j]['ActiveFeedback'] = 0;
				}
			}
			
			if(sizeof($ArrActivePositionDtls)>0)
			{
				$objOpening = new Opening();
				$ArrOpeningCnt = $objOpening->getOpeningPositionCnt($db,$ArrActivePositionDtls);
				for($i=0;$i<sizeof($ArrActivePositionDtls);$i++)
				{
					for($j=0;$j<sizeof($ArrOpeningCnt);$j++)
					{
						if($ArrActivePositionDtls[$i]['idRR'] == $ArrOpeningCnt[$j][0]['RRId'])
						{
							if($ArrActivePositionDtls[$i]['Openings'] == $ArrOpeningCnt[$j][0]['count'])
							{
								$Fill = 0;
							}
							else
							{
								$Fill = $ArrActivePositionDtls[$i]['Openings']-$ArrOpeningCnt[$j][0]['count'];
							}
							$ArrActivePositionDtls[$i]['Fill'] = $Fill;
							$ArrActivePositionDtls[$i]['remainingOpening'] = $ArrOpeningCnt[$j][0]['count'];
						}
					}
				}
				$ArrIntInterface = array();
				$$ArrIntInt = array();
				for($k=0;$k<sizeof($ArrActivePositionDtls);$k++)
				{
					$count = 0;
					for($l=0;$l<sizeof($finalArray);$l++)
					{
						if($ArrActivePositionDtls[$k]['idRR'] == $finalArray[$l]['RRId'])
						{
							/* $ArrActivePositionDtls[$k]['rrScedule'][$count] = $finalArray[$l];
							$count++; */
							/* array_merge($ArrActivePositionDtls[$k],$finalArray[$l]); */
							$ArrIntInt = array_merge($ArrActivePositionDtls[$k], $finalArray[$l]);
							array_push($ArrIntInterface,$ArrIntInt);
						}
					}
					if($count==0)
					{
						$ArrActivePositionDtls[$k]['rrScedule'] = array();
					}
				}
				if(sizeof($ArrIntInterface)>0)
				{
					$result = array('result' => 'success','addndata'=>$ArrIntInterface);
				}
				else
				{
					$result = array('result' => 'error','message'=>'Record not found');
				} 
			}
			else
			{
				$result = array('result' => 'error','message'=>'Record not found');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getIntHistoryDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$objRRCandidate = new RRCandidate();
			$ArrIntHistory = $objRRCandidate->getInteractionHistoryDtls($db,$CandidateHisTDtls);
			if(sizeof($ArrIntHistory)>0)
			{
				$result = array('result' => 'success','addndata'=>$ArrIntHistory);
			}
			else
			{
				$result = array('result' => 'error','message'=>'Error in sql');
			}
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function getRRDtlsSrvc()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$LoginTime = LoginHistory::getLoginTime($db, $CandidateHisTDtls);
			$objSearchRecruitmentRequest = new SearchRecruitmentRequest();
			$RRSqlStatement = $objSearchRecruitmentRequest->getRRSqlStatement();
			
			$ArrsearchResult = $objSearchRecruitmentRequest->getRRDtls($db,$RRSqlStatement,$LoginTime);
			if($ArrsearchResult)
			{
				$result = array('result' => 'success','addndata'=>$ArrsearchResult);
			}
			else
			{
				$result = array('result' => 'error','message'=>'Record not found');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function deactivateCandiRelPos()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateDtls = json_decode($app->getBody());
			$db->beginTransaction();
			/* $result = array('result' => 'success','addndata'=>$CandidateDtls); */
			$objRRCandidate = new RRCandidate();
			$resultDeactivateCandidate = $objRRCandidate->deactivateCandiRelPos($db,$CandidateDtls);
			if($resultDeactivateCandidate == 1)
			{
				$db->commit();
				$result = array('result' => 'success','message'=>"Record updated successfully");
			}
			else
			{
				$db->rollback();
				$result = array('result' => 'error','message'=>'Record not updated successfully');
			} 
		}
		catch(Exception $e)
		{
			$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function SearchCandidate()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$Candidatedtls = json_decode($app->getBody());
	
			$objSearchCandidate = new SearchCandidate();
			if(sizeof($Candidatedtls->QualiName) > 0){  
				$ArrQualification = $Candidatedtls->QualiName;
				$objSearchCandidate->setQualiName($ArrQualification);
			}
			if(sizeof($Candidatedtls->SkillName) > 0){  
				$ArrSkill = $Candidatedtls->SkillName;
				$objSearchCandidate->setSkillName($ArrSkill);
			}
			if($Candidatedtls->TotalExp != ""){  
				$objSearchCandidate->setTotalExp($Candidatedtls->TotalExp);
			}
			if($Candidatedtls->CurrentSalary != ""){  
				$objSearchCandidate->setCurrentSalary($Candidatedtls->CurrentSalary);
			}
			if($Candidatedtls->CandidateName != ""){  
				$objSearchCandidate->setCandidateName($Candidatedtls->CandidateName);
			}
			$CandidateSqlStatement = $objSearchCandidate->getCandidateSqlStatement();
		
			 
			$Sqlresult = $objSearchCandidate->Search($db,$CandidateSqlStatement);
	
			$idCandidate = -1;
				$j = -1;
				$finalArray = array();
				for($i =0; $i < sizeof($Sqlresult); $i++)
				{	
					if($idCandidate != $Sqlresult[$i]['idCandidate'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $Sqlresult[$i]['idCandidate'];
						$finalArray[$j]['CandidateName'] = $Sqlresult[$i]['CandidateName'];
						$finalArray[$j]['idCandidateStatus'] = $Sqlresult[$i]['idCandidateStatus'];
						$finalArray[$j]['candidateStatusDesc'] = $Sqlresult[$i]['candidateStatusDesc'];
						$finalArray[$j]['TotalExp'] = $Sqlresult[$i]['TotalExp'];
						$finalArray[$j]['RelevantExp'] = $Sqlresult[$i]['RelevantExp'];
						$finalArray[$j]['NoticePeriod'] = $Sqlresult[$i]['NoticePeriod'];
						$finalArray[$j]['Resume'] = $Sqlresult[$i]['Resume'];
						$finalArray[$j]['CurrentCompany'] = $Sqlresult[$i]['CurrentCompany'];
						$finalArray[$j]['CurrentSalary'] = $Sqlresult[$i]['CurrentSalary'];
						$finalArray[$j]['CurrentDesignation'] = $Sqlresult[$i]['CurrentDesignation'];
						$finalArray[$j]['MobNo'] = $Sqlresult[$i]['MobNo'];
						$finalArray[$j]['AltContactNo'] = $Sqlresult[$i]['AltContactNo'];
						$finalArray[$j]['EmailId'] = $Sqlresult[$i]['EmailId'];
						$finalArray[$j]['Address'] = $Sqlresult[$i]['Address'];
						$finalArray[$j]['RegDate'] = $Sqlresult[$i]['RegDate'];
						$finalArray[$j]['Role'] = $Sqlresult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $Sqlresult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$finalArray[$j]['NonTechSkillArr'] = array();
						$finalArray[$j]['CertificationArr'] = array();
						$idCandidate = $Sqlresult[$i]['idCandidate'];
					}
					if(!in_array($Sqlresult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],$Sqlresult[$i]['QualiName']);
					}
					if(!in_array($Sqlresult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$Sqlresult[$i]['SkillName']);
					}
					if(!in_array($Sqlresult[$i]['NonTechSkill'],$finalArray[$j]['NonTechSkillArr']))
					{
						array_push($finalArray[$j]['NonTechSkillArr'],$Sqlresult[$i]['NonTechSkill']);
					}
					if(!in_array($Sqlresult[$i]['Certification'],$finalArray[$j]['CertificationArr']))
					{
						array_push($finalArray[$j]['CertificationArr'],$Sqlresult[$i]['Certification']);
					}
				}
				if(sizeof($finalArray)>0)
				{
					$result = array('result' => 'success','addndata'=>$finalArray);
				}
				else
				{
					$result = array('result' => 'error','message'=>'Record not found');
				}
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
	
function getRRCandidateDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$Candidatedtls = json_decode($app->getBody());
		
			$objSearchCandidate = new SearchCandidate();
			if(sizeof($Candidatedtls->QualiName) > 0){  
				$ArrQualification = $Candidatedtls->QualiName;
				$objSearchCandidate->setQualiName($ArrQualification);
			}
			if(sizeof($Candidatedtls->SkillName) > 0){  
				$ArrSkill = $Candidatedtls->SkillName;
				$objSearchCandidate->setSkillName($ArrSkill);
			}
			if($Candidatedtls->TotalExp != ""){  
				$objSearchCandidate->setTotalExp($Candidatedtls->TotalExp);
			}
			if($Candidatedtls->CurrentSalary != ""){  
				$objSearchCandidate->setCurrentSalary($Candidatedtls->CurrentSalary);
			}
			if($Candidatedtls->CandidateName != ""){  
				$objSearchCandidate->setCandidateName($Candidatedtls->CandidateName);
			}
			$CandidateSqlStatement = $objSearchCandidate->getCandidateSqlStatement();
		
			 
			$Sqlresult = $objSearchCandidate->Search($db,$CandidateSqlStatement);
							
			$objRRCandidate = new RRCandidate();
			$ArrRRCandidate = $objRRCandidate->getRRCandidate($db,$Candidatedtls);
			$idCandidate = -1;
				$j = -1;
				$finalArray = array();
				for($i =0; $i < sizeof($Sqlresult); $i++)
				{	
					if($idCandidate != $Sqlresult[$i]['idCandidate'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $Sqlresult[$i]['idCandidate'];
						$finalArray[$j]['CandidateName'] = $Sqlresult[$i]['CandidateName'];
						$finalArray[$j]['idCandidateStatus'] = $Sqlresult[$i]['idCandidateStatus'];
						$finalArray[$j]['candidateStatusDesc'] = $Sqlresult[$i]['candidateStatusDesc'];
						$finalArray[$j]['TotalExp'] = $Sqlresult[$i]['TotalExp'];
						$finalArray[$j]['RelevantExp'] = $Sqlresult[$i]['RelevantExp'];
						$finalArray[$j]['NoticePeriod'] = $Sqlresult[$i]['NoticePeriod'];
						$finalArray[$j]['Resume'] = $Sqlresult[$i]['Resume'];
						$finalArray[$j]['CurrentCompany'] = $Sqlresult[$i]['CurrentCompany'];
						$finalArray[$j]['CurrentSalary'] = $Sqlresult[$i]['CurrentSalary'];
						$finalArray[$j]['CurrentDesignation'] = $Sqlresult[$i]['CurrentDesignation'];
						$finalArray[$j]['MobNo'] = $Sqlresult[$i]['MobNo'];
						$finalArray[$j]['AltContactNo'] = $Sqlresult[$i]['AltContactNo'];
						$finalArray[$j]['EmailId'] = $Sqlresult[$i]['EmailId'];
						$finalArray[$j]['Address'] = $Sqlresult[$i]['Address'];
						$finalArray[$j]['RegDate'] = $Sqlresult[$i]['RegDate'];
						$finalArray[$j]['Role'] = $Sqlresult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $Sqlresult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$finalArray[$j]['NonTechSkillArr'] = array();
						$finalArray[$j]['CertificationArr'] = array();
						$idCandidate = $Sqlresult[$i]['idCandidate'];
					}
					if(!in_array($Sqlresult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],$Sqlresult[$i]['QualiName']);
					}
					if(!in_array($Sqlresult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$Sqlresult[$i]['SkillName']);
					}
					if(!in_array($Sqlresult[$i]['NonTechSkill'],$finalArray[$j]['NonTechSkillArr']))
					{
						array_push($finalArray[$j]['NonTechSkillArr'],$Sqlresult[$i]['NonTechSkill']);
					}
					if(!in_array($Sqlresult[$i]['Certification'],$finalArray[$j]['CertificationArr']))
					{
						array_push($finalArray[$j]['CertificationArr'],$Sqlresult[$i]['Certification']);
					}
				} 
			
				for($i=0;$i<sizeof($ArrRRCandidate);$i++)
				{
					for($j=0;$j<sizeof($finalArray);$j++)
					{
						if($ArrRRCandidate[$i]['CandidateId'] == $finalArray[$j]['idCandidate'])
						{
							$finalArray[$j]['RRCandiActive'] = 1;
						}
					}	
				}
				if(sizeof($finalArray)>0)
				{
/* 					$result = array('result' => 'success','addndata'=>$finalArray); */
/* idJobDesc,PositionId,ExperianceMin,ExperianceMAx,Role,Responsibility,
Position,SkillId,QualiId,SkillName,QualiName */
					$objJobDescription = new JobDescription();
					$ArrJD = $objJobDescription->getJobDescRelatedPosition($db,$Candidatedtls); 
					$PositionId = -1;
					$j = -1;
					$finalArray1 = array();
					for($i =0; $i < sizeof($ArrJD); $i++)
					{	
						if($PositionId != $ArrJD[$i]['PositionId'])
						{
							$j++;
							$finalArray1[$j]['idJobDesc'] = $ArrJD[$i]['idJobDesc'];
							$finalArray1[$j]['PositionId'] = $ArrJD[$i]['PositionId'];
							$finalArray1[$j]['ExperianceMin'] = $ArrJD[$i]['ExperianceMin'];
							$finalArray1[$j]['ExperianceMAx'] = $ArrJD[$i]['ExperianceMAx'];
							$finalArray1[$j]['Role'] = $ArrJD[$i]['Role'];
							$finalArray1[$j]['Responsibility'] = $ArrJD[$i]['Responsibility'];
							$finalArray1[$j]['Position'] = $ArrJD[$i]['Position'];
							$finalArray1[$j]['QualiArr'] = array();
							$finalArray1[$j]['TechSkillArr'] = array();
							$PositionId = $ArrJD[$i]['PositionId'];
						}
						if(!in_array($ArrJD[$i]['QualiName'],$finalArray1[$j]['QualiArr']))
						{
							array_push($finalArray1[$j]['QualiArr'],$ArrJD[$i]['QualiName']);
						}
						if(!in_array($ArrJD[$i]['SkillName'],$finalArray1[$j]['TechSkillArr']))
						{
							array_push($finalArray1[$j]['TechSkillArr'],$ArrJD[$i]['SkillName']);
						}
					} 
					if(sizeof($finalArray1)>0)
					{
						$ArrSplice = array();
						/* 
						if($finalArray1) */
						/* ExperianceMAx:"10"
						ExperianceMin:"6"
						Position:"Java Developer"
						PositionId:"2"

						QualiArr:Array[2]
						0:"BCA"
						1:"BCS"

						Responsibility:"hfgdfsds"
						Role:"gfhgdfzss"

						TechSkillArr:Array[5]
						0:"ASP.Net"
						1:"C"
						2:"Java"
						3:"PHP"
						4:"SQL" BE,ME,BCA,MCA,BCS,MCS,BTech,MTech,BE(Comp/IT)*/
						for($k=0;$k<sizeof($finalArray);$k++)
						{
							$countQualification = 0;
							for($m=0;$m<sizeof($finalArray1[0]['QualiArr']);$m++)
							{
								for($l=0;$l<sizeof($finalArray[$k]['QualiArr']);$l++)
								{
									if($finalArray1[0]['QualiArr'][$m] == "BE" || $finalArray1[0]['QualiArr'][$m] == "BTech" || $finalArray1[0]['QualiArr'][$m] == "BE(Comp/IT)")
									{
										if($finalArray[$k]['QualiArr'][$l] == "BE" || $finalArray[$k]['QualiArr'][$l] == "BTech" || $finalArray[$k]['QualiArr'][$l] == "BE(Comp/IT)")
										$countQualification++;
									}
									if($finalArray1[0]['QualiArr'][$m] == "ME" || $finalArray1[0]['QualiArr'][$m] == "MTech")
									{
										if($finalArray[$k]['QualiArr'][$l] == "ME" || $finalArray[$k]['QualiArr'][$l] == "MTech")
										$countQualification++;
									}
									if($finalArray1[0]['QualiArr'][$m] == "BCA" || $finalArray1[0]['QualiArr'][$m] == "BCS")
									{
										if($finalArray[$k]['QualiArr'][$l] == "BCS" || $finalArray[$k]['QualiArr'][$l] == "BCS")
										$countQualification++;
									}
									if($finalArray1[0]['QualiArr'][$m] == "MCA" || $finalArray1[0]['QualiArr'][$m] == "MCS")
									{
										if($finalArray[$k]['QualiArr'][$l] == "MCA" || $finalArray[$k]['QualiArr'][$l] == "MCS")
										$countQualification++;
									}
									if($countQualification > 0)
									{
										break;
									}
								}
								if($countQualification > 0)
								{
									break;
								}
							}
							if($countQualification > 0)
							{
								$finalArray[$k]['QualiMatch'] = 100;
								array_push($ArrSplice,$finalArray[$k]);
							}
						}
						
						if(sizeof($ArrSplice) > 0)
						{
							$requirdpercentage = 70;
							$ArrFinalCandi = array();
							for($x=0;$x<sizeof($ArrSplice);$x++)
							{
								$countTeckSkill = 0;
								$lengthTSf1 = 0;
								for($y=0;$y<sizeof($finalArray1[0]['TechSkillArr']);$y++)
								{
									$lengthTSf1 = sizeof($finalArray1[0]['TechSkillArr']);			
									for($z=0;$z<sizeof($ArrSplice[$x]['TechSkillArr']);$z++)
									{
										if($finalArray1[0]['TechSkillArr'][$y] == $ArrSplice[$x]['TechSkillArr'][$z])
										{
											$countTeckSkill++;
										}
									}
								}
								if($countTeckSkill > 0)
								{
									$onePercentage = $lengthTSf1/100;
									$CandiSkillPer = $countTeckSkill / $onePercentage;
									if($CandiSkillPer >= $requirdpercentage)
									{
										$ArrSplice[$x]['skillPercentage'] = $CandiSkillPer;
										array_push($ArrFinalCandi,$ArrSplice[$x]);
									}
								}
							}
							if(sizeof($ArrFinalCandi)>0)
							{
								$ArrFinalCandi1 = array();
								$requirdpercentageExp = 70;
								for($n=0;$n<sizeof($ArrFinalCandi);$n++)
								{
									if($ArrFinalCandi[$n]['RelevantExp'] >= $finalArray1[0]['ExperianceMin'])
									{
										$ArrFinalCandi[$n]['Expmatch'] = 100;
										array_push($ArrFinalCandi1,$ArrFinalCandi[$n]);
									}
									if($ArrFinalCandi[$n]['RelevantExp'] < $finalArray1[0]['ExperianceMin'])
									{
										$ExpminOnePer = $finalArray1[0]['ExperianceMin']/100;
										$candidateExpPer = $ArrFinalCandi[$n]['RelevantExp']/$ExpminOnePer;
										if($candidateExpPer >= $requirdpercentageExp)
										{
											$ArrFinalCandi[$n]['Expmatch'] = intval($candidateExpPer);
											array_push($ArrFinalCandi1,$ArrFinalCandi[$n]);
										}
									}
									
								}
								if(sizeof($ArrFinalCandi1)>0)
								{
									$result = array('result' => 'success','addndata'=>$ArrFinalCandi1);
								}
								else
								{
									$result = array('result' => 'error','message'=>"Job Description Related Record not found");
								}
								
							}
							else
							{
								$result = array('result' => 'error','message'=>"Job Description Related Record not found");
							}
							
						}
						else
						{
							$result = array('result' => 'error','message'=>"Job Description Related Record not found");
						}
					}
					else
					{
						$result = array('result' => 'error','message'=>'Record not found');
					} 
					
				}
				else
				{
					$result = array('result' => 'error','message'=>'Record not found');
				}
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}

function getCandidateDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$LoginTime = LoginHistory::getLoginTime($db, $CandidateHisTDtls);
			$objSearchCandidate = new SearchCandidate();
			$CandidateSqlStatement = $objSearchCandidate->getCandidateSqlStatement();
			$Sqlresult = $objSearchCandidate->getCandidateDtls($db,$CandidateSqlStatement,$LoginTime);
	
			$idCandidate = -1;
				$j = -1;
				$finalArray = array();
				for($i =0; $i < sizeof($Sqlresult); $i++)
				{	
					if($idCandidate != $Sqlresult[$i]['idCandidate'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $Sqlresult[$i]['idCandidate'];
						$finalArray[$j]['CandidateName'] = $Sqlresult[$i]['CandidateName'];
						$finalArray[$j]['TotalExp'] = $Sqlresult[$i]['TotalExp'];
						$finalArray[$j]['RelevantExp'] = $Sqlresult[$i]['RelevantExp'];
						$finalArray[$j]['NoticePeriod'] = $Sqlresult[$i]['NoticePeriod'];
						$finalArray[$j]['Resume'] = $Sqlresult[$i]['Resume'];
						$finalArray[$j]['CurrentCompany'] = $Sqlresult[$i]['CurrentCompany'];
						$finalArray[$j]['CurrentSalary'] = $Sqlresult[$i]['CurrentSalary'];
						$finalArray[$j]['CurrentDesignation'] = $Sqlresult[$i]['CurrentDesignation'];
						$finalArray[$j]['MobNo'] = $Sqlresult[$i]['MobNo'];
						$finalArray[$j]['AltContactNo'] = $Sqlresult[$i]['AltContactNo'];
						$finalArray[$j]['EmailId'] = $Sqlresult[$i]['EmailId'];
						$finalArray[$j]['Address'] = $Sqlresult[$i]['Address'];
						$finalArray[$j]['RegDate'] = $Sqlresult[$i]['RegDate'];
						$finalArray[$j]['Role'] = $Sqlresult[$i]['Role'];
						$finalArray[$j]['Responsibility'] = $Sqlresult[$i]['Responsibility'];
						$finalArray[$j]['QualiArr'] = array();
						$finalArray[$j]['TechSkillArr'] = array();
						$finalArray[$j]['NonTechSkillArr'] = array();
						$finalArray[$j]['CertificationArr'] = array();
						$idCandidate = $Sqlresult[$i]['idCandidate'];
					}
					if(!in_array($Sqlresult[$i]['QualiName'],$finalArray[$j]['QualiArr']))
					{
						array_push($finalArray[$j]['QualiArr'],$Sqlresult[$i]['QualiName']);
					}
					if(!in_array($Sqlresult[$i]['SkillName'],$finalArray[$j]['TechSkillArr']))
					{
						array_push($finalArray[$j]['TechSkillArr'],$Sqlresult[$i]['SkillName']);
					}
					if(!in_array($Sqlresult[$i]['NonTechSkill'],$finalArray[$j]['NonTechSkillArr']))
					{
						array_push($finalArray[$j]['NonTechSkillArr'],$Sqlresult[$i]['NonTechSkill']);
					}
					if(!in_array($Sqlresult[$i]['Certification'],$finalArray[$j]['CertificationArr']))
					{
						array_push($finalArray[$j]['CertificationArr'],$Sqlresult[$i]['Certification']);
					}
				}
				if(sizeof($finalArray)>0)
				{
					$result = array('result' => 'success','addndata'=>$finalArray);
				}
				else
				{
					$result = array('result' => 'success','addndata'=>'Record not found');
				}
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function UpdateCandidate()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$Candidatedtls = json_decode($app->getBody());
			$objCandidate = new Candidate();
			$resultUpdateCandidate = $objCandidate->UpdateCandidate($db,$Candidatedtls);
			if($resultUpdateCandidate == 1)
			{
				 
				$result = array('result' => 'success','message'=>'Record updated successfully');
			}
			else
			{
				$result = array('result' => 'success','message'=>'Record not updated successfully');
			} 
		}
		catch(PDOException $e)
		{
			//$db->rollback();
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function checkForFilesDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$Candidatedtls = json_decode($app->getBody());
			/* 
folderName:"Attachment"
queueArray:Array[1]
0:"Noticlass.txt" */
			$objCandidate = new Candidate();
			$resultCandidate = $objCandidate->checkForFiles($db,$Candidatedtls->folderName,$Candidatedtls->queueArray);
			if($resultCandidate == 1)
			{
				 
				$result = array('result' => 'success');
			}
			else
			{
				$result = array('result' => 'error');
			}  
		}
		catch(PDOException $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		echo json_encode($result);
	}
function UserList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objUsers = new Users();
			$arrUserDtls = $objUsers->loadUserDtls($db);
	
			if($arrUserDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrUserDtls);
			}
			else
			{
				$result = array('result'=>'error','message'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function getCountRRJDCandidate()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$LoginTime = LoginHistory::getLoginTime($db, $CandidateHisTDtls);
			$objCandidate = new Candidate();
			$objJobDescription = new JobDescription(); 
			$objRR = new RR();
			$ArrCnt = array();
			$Cntcandidate = $objCandidate->getCandidateCnt($db,$LoginTime);
			$ArrCnt['candidate'] = $Cntcandidate;
			$CntJD = $objJobDescription->getJDCnt($db,$LoginTime);
			$ArrCnt['JD'] = $CntJD;
			$CntRR = $objRR->getRRCnt($db,$LoginTime);
			$ArrCnt['RR'] = $CntRR;
			$result = array('result'=>'success','addndata'=>$ArrCnt); 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function getAddCountDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$CandidateHisTDtls = json_decode($app->getBody());
			$LoginTime = LoginHistory::getLoginTime($db, $CandidateHisTDtls);
			$objCandidate = new Candidate();
			$objJobDescription = new JobDescription(); 
			$objRR = new RR();
			$ArrCnt = array();
			$Cntcandidate = $objCandidate->getAddCandidateCnt($db,$LoginTime);
			$ArrCnt['candidate'] = $Cntcandidate;
			$CntJD = $objJobDescription->getAddJDCnt($db,$LoginTime);
			$ArrCnt['JD'] = $CntJD;
			$CntRR = $objRR->getAddRRCnt($db,$LoginTime);
			$ArrCnt['RR'] = $CntRR; 
			$result = array('result'=>'success','addndata'=>$ArrCnt); 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}

function ElementTypeList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objElements = new Elements();
			$arrElementTypeDtls = $objElements->loadElementTypeDtls($db);
	
			if($arrElementTypeDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrElementTypeDtls);
			}
			else
			{
				$result = array('result'=>'error','message'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}

function ElementList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objElements = new Elements();
			$arrElementListDtls = $objElements->getAllElementList($db);
	
			if($arrElementListDtls)
			{
				$result = array('result'=>'success','addndata'=>$arrElementListDtls);
			}
			else
			{
				$result = array('result'=>'error','message'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
	
function LoadUserRoleDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$userDtls = json_decode($app->getBody());
			
			$objUserRoles = new UserRoles($userDtls->idUser);
			$ArrUserRoles = $objUserRoles->getUserRolesDtls($db); //particular userRoles
			
			$objRoles = new Roles();
			$ArrRoleDtls = $objRoles->loadRoleDtls($db); //all roles get
			
			for($i = 0 ;$i < sizeof($ArrUserRoles); $i++)
			{
				for($j = 0 ;$j < sizeof($ArrRoleDtls); $j++)
				{
					if($ArrUserRoles[$i]['roleId']==$ArrRoleDtls[$j]['idRole'])
					{
						$ArrRoleDtls[$j]['IsActive'] = 1;
					}
				}
			}	
			$result = array('result' => 'success','addndata'=>$ArrRoleDtls); 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		
		echo json_encode($result);	
	}
function RolePermissionList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$RoleDtls = json_decode($app->getBody());

			$objRole = new Role();
			$objRole->setroleId($RoleDtls->idRole);
			$ArrRolePermission = $objRole->loadRolePermission($db);
			$objElements = new Elements();
			$ArrElementList = $objElements->getAllElementList($db);
	
			$FinalArrRolePerm = array();
			$FinalArrRolePerm = $ArrRolePermission;
			for($i=0;$i<sizeof($ArrElementList);$i++)
			{
				$cnt = 0;
				for($j=0;$j<sizeof($ArrRolePermission);$j++)
				{
					if($ArrElementList[$i]['idElement'] == $ArrRolePermission[$j]['idElement'])
					{
						$cnt++;
					}	
				}
				if($cnt == 0)
				{
					$ArrElementList[$i]['IsPermitted'] = 0;
					$ArrElementList[$i]['roleId'] = $RoleDtls->idRole;
					array_push($FinalArrRolePerm,$ArrElementList[$i]);
				}
			} 
			$result = array('result' => 'success','addndata'=>$FinalArrRolePerm);
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error','message'=> $e->getMessage());
		}
		
		echo json_encode($result);	
	}
	
	function ConfigureList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$arrConfig = Config::loadConfiguration($db);
	
			if($arrConfig)
			{
				$result = array('result'=>'success','addndata'=>$arrConfig);
			}
			else
			{
				$result = array('result'=>'error','message'=>'Error in sql');
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
	
	function EditConfigure()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$ConfigureDtls = json_decode($app->getBody());
			$arrConfig = Config::loadConfiguration($db);
			$objConfig = new Config($arrConfig);

			$Arrresult = array();
			for($i = 0; $i < sizeof($ConfigureDtls); $i++)
			{
				$result = $objConfig->Edit($db,$ConfigureDtls->data[$i]->searchkey,$ConfigureDtls->data[$i]->searchvalue);
				array_push($Arrresult,$result);
			} 
			
			$cnt=0;
			for($i = 0;$i < sizeof($Arrresult);$i++)
			{
				if($Arrresult[$i] == 1)
				{
					$cnt++;
				}
			}
			
			if($cnt == sizeof($ConfigureDtls))
			{
				$result = array('result'=>'success','message'=>"Record Updated Successfully");
			}
			else
			{
				$result = array('result'=>'error','message'=>"Record Not Updated Successfully");
			}    
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
	function saveUserRole()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$userRoleDtls = json_decode($app->getBody());
			$objUserRole = new UserRole();

			$Arrresult = array();
			
			for($i=0;$i<sizeof($userRoleDtls->data);$i++)
			{
				$objUserRole->setuserId($userRoleDtls->data[$i]->idUser);
				$result = $objUserRole->save($db,$userRoleDtls->data[$i]);
				array_push($Arrresult,$result);
			} 
			$cnt=0;
			for($i = 0;$i < sizeof($Arrresult);$i++)
			{
				if($Arrresult[$i] == 1)
				{
					$cnt++;
				}
			}
			if($cnt == sizeof($userRoleDtls->data))
			{
				$result = array('result'=>'success','message'=>"Record Inserted Successfully");
			}
			else
			{
				$result = array('result'=>'error','message'=>"Record Not Inserted Successfully");
			}
			
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function getRoleRelUserList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$RoleDtls = json_decode($app->getBody());
			$objUserRole = new UserRole();
			$UserDtls = $objUserRole->getRoleRelUser($db,$RoleDtls->idRole);
			if(sizeof($UserDtls) > 0)
			{
				$result = array('result'=>'success','addndata'=>$UserDtls);
			}
			else
			{
				$result = array('result'=>'error','message'=>"not found");
			} 
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function AddUserRoleDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$UserDtls = json_decode($app->getBody());
			$result = array('result'=>'success','addndata'=>"Record inserted");
			/* $objUserRole = new UserRole();
			$UserDtls = $objUserRole->getRoleRelUser($db,$RoleDtls->idRole);
			if(sizeof($UserDtls) > 0)
			{
				$result = array('result'=>'success','addndata'=>$UserDtls);
			}
			else
			{
				$result = array('result'=>'error','message'=>"not found");
			}  */
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
	
function DesignationList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objDesignation = new Designation();
			$ArrDesignation = $objDesignation->loadDesignationList($db);
			$result = array('result'=>'success','addndata'=>$ArrDesignation);
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
	
function DepartmentList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$objDepartment = new Department();
			$ArrDepartment = $objDepartment->loadDepartmentList($db);
			$result = array('result'=>'success','addndata'=>$ArrDepartment);
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function saveRolePermissions()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$RolePermissionsDtls = json_decode($app->getBody());
			
			$objRole = new Role();
			
			$Arrresult = array();
			
			for($i=0;$i<sizeof($RolePermissionsDtls->data);$i++)
			{
				$objRole->setroleId($RolePermissionsDtls->data[$i]->roleId);
				$result = $objRole->saveRolePerm($db,$RolePermissionsDtls->data[$i]);
				array_push($Arrresult,$result);
			} 
			$cnt=0;
			for($i = 0;$i < sizeof($Arrresult);$i++)
			{
				if($Arrresult[$i] == 1)
				{
					$cnt++;
				}
			}
			if($cnt == sizeof($RolePermissionsDtls->data))
			{
				$result = array('result'=>'success','message'=>"Record Inserted Successfully");
			}
			else
			{
				$result = array('result'=>'error','message'=>"Record Not Inserted Successfully");
			}
			
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function getRolePermission()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$UserDtls = json_decode($app->getBody());
			$objUserRoles = new UserRoles($UserDtls->objContext->idUser);
			$ArrUserRoles = $objUserRoles->getUserRolesDtls($db);
			$objRole = new Role();
			$userAllRolePerm = array();
			for($i = 0; $i < sizeof($ArrUserRoles); $i++)
			{
				$objRole->setroleId($ArrUserRoles[$i]['roleId']);
				$ArrRolePermission = $objRole->loadRolePermission($db);
				for($j=0;$j<sizeof($ArrRolePermission);$j++)
				{
					array_push($userAllRolePerm,$ArrRolePermission[$j]);
				}
			}
			$ArrUserRolePerm = array();
			$ArrUserRolePerm = super_unique($userAllRolePerm,'elementId');
			
			$result = array('result'=>'success','addndata'=>$ArrUserRolePerm);
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function UserRoleList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$UserDtls = json_decode($app->getBody());
			$objUserRoles = new UserRoles($UserDtls->objContext->idUser);
			$ArrUserRoles = $objUserRoles->getUserRolesDtls($db);
			
			$result = array('result'=>'success','addndata'=>$ArrUserRoles);
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
function getUserAlert()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$UserRoleDtls = json_decode($app->getBody());
			/* 
RoleDtls:Array[1]
0:Object
IsActive:"1"
roleDescription:"Interviewer"
roleId"4"
userId:"1" */
			$count = 0;
			$ArrRole = array();
			for($i=0;$i<sizeof($UserRoleDtls->RoleDtls);$i++)
			{
				if($UserRoleDtls->RoleDtls[$i]->roleId == 1 || $UserRoleDtls->RoleDtls[$i]->roleId == 2)
				{
					$count++;
				}
			}
			$objRRCandidate = new RRCandidate();
			if($count > 0)
			{
			$ArrAssignCaniSchedule = $objRRCandidate->getAssignCandiDtls($db);
			$idCandidate = -1;
			$j = -1;
			$finalArray = array();
			for($i =0; $i < sizeof($ArrAssignCaniSchedule); $i++)
				{	
					if($idCandidate != $ArrAssignCaniSchedule[$i]['idCandidate'])
					{
						$j++;
						$finalArray[$j]['idCandidate'] = $ArrAssignCaniSchedule[$i]['idCandidate'];
						$finalArray[$j]['RRId'] = $ArrAssignCaniSchedule[$i]['RRId'];
						$finalArray[$j]['IsActiveInteraction'] = $ArrAssignCaniSchedule[$i]['IsActiveInteraction'];
						$finalArray[$j]['CandidateName'] = $ArrAssignCaniSchedule[$i]['CandidateName'];
						$finalArray[$j]['RegDate'] = $ArrAssignCaniSchedule[$i]['RegDate'];
						$finalArray[$j]['interviewTypeDesc'] = $ArrAssignCaniSchedule[$i]['interviewTypeDesc'];
						$finalArray[$j]['intDate'] = $ArrAssignCaniSchedule[$i]['intDate'];
						$finalArray[$j]['altIntDate'] = $ArrAssignCaniSchedule[$i]['altIntDate'];
						$finalArray[$j]['idRRCandidate'] = $ArrAssignCaniSchedule[$i]['idRRCandidate'];
						$finalArray[$j]['idCandiPosStatus'] = $ArrAssignCaniSchedule[$i]['idCandiPosStatus'];
						$finalArray[$j]['candiPosStatusDesc'] = $ArrAssignCaniSchedule[$i]['candiPosStatusDesc'];
						$finalArray[$j]['NameArr'] = array();
						$idCandidate = $ArrAssignCaniSchedule[$i]['idCandidate'];
					}
					if(!in_array($ArrAssignCaniSchedule[$i]['Name'],$finalArray[$j]['NameArr']))
					{
						array_push($finalArray[$j]['NameArr'],$ArrAssignCaniSchedule[$i]['Name']);
					}
				}
				$AlertsCount = sizeof($finalArray);
				$result = array('result'=>'success','addndata'=>$AlertsCount);
				
			}
			else
			{
				$strUserId = $UserRoleDtls->RoleDtls[0]->userId;
				$ArrAssignCaniSchedule1 = $objRRCandidate->getRoleRelAssignCandiDtls($db,$strUserId);
					$AlertsCount1 = sizeof($ArrAssignCaniSchedule1);
					$result = array('result'=>'success','addndata'=>$AlertsCount1);
				}
			
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
	function getUserAlertDtls()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$UserRoleDtls = json_decode($app->getBody());
		
			$count = 0;
			$ArrRole = array();
			for($i=0;$i<sizeof($UserRoleDtls->RoleDtls);$i++)
			{
				if($UserRoleDtls->RoleDtls[$i]->roleId == 1 || $UserRoleDtls->RoleDtls[$i]->roleId == 2)
				{
					$count++;
				}
			}
			$objRRCandidate = new RRCandidate();
			if($count > 0)
			{
				$ArrAssignCaniSchedule = $objRRCandidate->getAssignCandiDtls($db);
				if(sizeof($ArrAssignCaniSchedule)>0)
				{
					$idCandidate = -1;
					$j = -1;
					$finalArray = array();
					for($i =0; $i < sizeof($ArrAssignCaniSchedule); $i++)
						{	
							if($idCandidate != $ArrAssignCaniSchedule[$i]['idCandidate'])
							{
								$j++;
								$finalArray[$j]['idCandidate'] = $ArrAssignCaniSchedule[$i]['idCandidate'];
								$finalArray[$j]['RRId'] = $ArrAssignCaniSchedule[$i]['RRId'];
								$finalArray[$j]['IsActiveInteraction'] = $ArrAssignCaniSchedule[$i]['IsActiveInteraction'];
								$finalArray[$j]['CandidateName'] = $ArrAssignCaniSchedule[$i]['CandidateName'];
								$finalArray[$j]['RegDate'] = $ArrAssignCaniSchedule[$i]['RegDate'];
								$finalArray[$j]['interviewTypeDesc'] = $ArrAssignCaniSchedule[$i]['interviewTypeDesc'];
								$finalArray[$j]['intDate'] = $ArrAssignCaniSchedule[$i]['intDate'];
								$finalArray[$j]['intDate1'] = $ArrAssignCaniSchedule[$i]['intDate1'];
								$finalArray[$j]['altIntDate'] = $ArrAssignCaniSchedule[$i]['altIntDate'];
								$finalArray[$j]['altIntDate1'] = $ArrAssignCaniSchedule[$i]['altIntDate1'];
								$finalArray[$j]['idRRCandidate'] = $ArrAssignCaniSchedule[$i]['idRRCandidate'];
								$finalArray[$j]['idCandiPosStatus'] = $ArrAssignCaniSchedule[$i]['idCandiPosStatus'];
								$finalArray[$j]['candiPosStatusDesc'] = $ArrAssignCaniSchedule[$i]['candiPosStatusDesc'];
								$finalArray[$j]['Position'] = $ArrAssignCaniSchedule[$i]['Position'];
								$finalArray[$j]['NameArr'] = array();
								$idCandidate = $ArrAssignCaniSchedule[$i]['idCandidate'];
							}
							if(!in_array($ArrAssignCaniSchedule[$i]['Name'],$finalArray[$j]['NameArr']))
							{
								array_push($finalArray[$j]['NameArr'],$ArrAssignCaniSchedule[$i]['Name']);
							}
						}
						$result = array('result'=>'success','addndata'=>$finalArray);
				}
				else
				{
					$result = array('result'=>'error','message'=>"Record not found");
				}
			
				
			}
			else
			{
				$strUserId = $UserRoleDtls->RoleDtls[0]->userId;
				$ArrAssignCaniSchedule1 = $objRRCandidate->getRoleRelAssignCandiDtls($db,$strUserId);
				if(sizeof($ArrAssignCaniSchedule1)>0)
				{
					$result = array('result'=>'success','addndata'=>$ArrAssignCaniSchedule1);
				}
				else
				{
					$result = array('result'=>'error','message'=>"Record not found");
				}
			}
			
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
	function getUserRelFeedbackList()
	{
		try{
			$db = connect_db();
			$app = \Slim\Slim::getInstance()->request();
			$UserRoleDtls = json_decode($app->getBody());
			$result = array('result'=>'success','message'=>$UserRoleDtls);
		 
			$count = 0;
			for($i=0;$i<sizeof($UserRoleDtls->RoleDtls);$i++)
			{
				if($UserRoleDtls->RoleDtls[$i]->roleId == 1 || $UserRoleDtls->RoleDtls[$i]->roleId == 2)
				{
					$count++;
				}
			}
			$objRRCandidate = new RRCandidate();
			if($count > 0)
			{
				$UserId = "";
				$ArrUserFeedback1 = $objRRCandidate->getUserFeedback($db,$UserId);
				if(sizeof($ArrUserFeedback1)>0)
				{
					$result = array('result'=>'success','addndata'=>$ArrUserFeedback1);
				}
				else
				{
					$result = array('result'=>'error','message'=>"Record not found");
				}
			}
			else
			{
				$UserId = $UserRoleDtls->RoleDtls[0]->userId;
				$ArrUserFeedback2 = $objRRCandidate->getUserFeedback($db,$UserId);
				if(sizeof($ArrUserFeedback2)>0)
				{
					$result = array('result'=>'success','addndata'=>$ArrUserFeedback2);
				}
				else
				{
					$result = array('result'=>'error','message'=>"Record not found");
				}
			}
			
		}
		catch(Exception $e)
		{
			$result = array('result'=>'error' , 'message'=> $e->getMessage());
		}	
		echo json_encode($result);
	}
	
	function super_unique($array,$key)
	{
		$temp_array = array();
		foreach ($array as &$v)
		{
			if (!isset($temp_array[$v[$key]]))
			$temp_array[$v[$key]] =& $v;
		}
		$array = array_values($temp_array);
		return $array;
	}
	
?>