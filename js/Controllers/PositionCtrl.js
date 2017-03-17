app.controller('PositionCtrl', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter','$rootScope',
	function ($scope , $timeout, $window, $http, sessionService, $location, $filter) 
	{
		$scope.flushMessages = function(){
			$scope.errmsg = '';
			$scope.error = '';
			$scope.errorMsg = '';
			$scope.insermsg = '';
			$scope.successmsg = '';
			$scope.errormessage = '';
		}
		
		$scope.$on("flag",function(e,args)
		{
			$scope.flag = angular.copy(args.flag);
			alert($scope.flag);
		});
		
		$scope.myRRPanel = true;
		
		if($scope.flag == 1)
		{
			$scope.showFeedbackBtn = false;
			$scope.MoAlertsPanel = true;
			$scope.AlertsPanel = false;
		}
		else
		{
			$scope.showFeedbackBtn = true;
			$scope.MoAlertsPanel = false;
			$scope.AlertsPanel = true;			
		}
		
		$scope.showfeedbacks = function()
		{
			$scope.FeedbackPanel = true;
			$scope.AlertsPanel = false;
			$scope.showFeedbackBtn = false;
			$scope.AlertsBtn = true;
			$scope.geetFeedbackRelateduser();
		}
		
		$scope.getAlertsPanel = function()
		{
			if($scope.flag == 1)
			{
				$scope.AlertsPanel = false;
				$scope.showFeedbackBtn = false;
				$scope.MoAlertsPanel = true;
			}
			else
			{
				$scope.FeedbackPanel = false;
				$scope.AlertsBtn = false;
				$scope.AlertsPanel = true;
				$scope.showFeedbackBtn = true;
				$scope.MoAlertsPanel = false;
			}
			
		}
		
		$scope.getArrFeedbackIndex = function(index)
		{
			$scope.indexofFeedBack = index;
		}
		
		$scope.updateCandiFeedback = function(data)
		{
			var objFeedback = {};
			$("#myModal1").modal('hide');
			$scope.loading = true;
			$scope.ArrFeedbackDtls[$scope.indexofFeedBack].feedback = "";
			$scope.ArrFeedbackDtls[$scope.indexofFeedBack].feedback = data;
			$scope.ArrFeedbackDtls[$scope.indexofFeedBack].interactionStatusId = $scope.statusId;
			objFeedback = $scope.ArrFeedbackDtls[$scope.indexofFeedBack];
			$scope.Data = '';
			var feedbackListDtls = sessionService.updateCandiFeedbackSrvc(objFeedback);
			feedbackListDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.successmsg = msg.data.message;
					$scope.geetFeedbackRelateduser();
					$scope.loading = false;
				} 
				else{
					$scope.errmsg = msg.data.message;
					$scope.loading = false;
				}	
				$timeout($scope.flushMessages,3000);
			});		
		}
		
		$scope.geetFeedbackRelateduser = function()
		{
			$scope.loading = true;
			var feedbackListDtls = sessionService.getUserRelFeedbackListSrvc();
			feedbackListDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.Data = '';
					$scope.ArrFeedbackDtls = msg.data.addndata;
					$scope.loading = false;
				} 
				else{
					$scope.errmsg = msg.data.message;
					$scope.loading = false;
				}	
				$timeout($scope.flushMessages,3000);
			});		
		}
		
		$scope.getEmployeeList = function()
		{
			var EmployeeListDtls = sessionService.getEmployeeListSrvc();
			EmployeeListDtls.then(function(msg){
				if (msg.data.result == 'success') {
					$scope.ArrEmployeeDtls = msg.data.addndata;
				} 
				else{
					$scope.errmsg = msg.data.message;
				}	
				$timeout($scope.flushMessages,3000);
			});		
		}
		$scope.getEmployeeList();
		
		$scope.getAlertsDtlsList = function()
		{
			var EmployeeListDtls = sessionService.getUserAlertsDtlsSrvc();
			EmployeeListDtls.then(function(msg){
				if (msg.data.result == 'success') {
					$scope.ArrAlertsDtls = msg.data.addndata;
				} 
				else{
					$scope.errmsg = msg.data.message;
				}	
				$timeout($scope.flushMessages,3000);
			});		
		}
		$scope.getAlertsDtlsList();
			
		$scope.getInterviewTypeList = function()
		{
			var interviewTypeListDtls = sessionService.getInterviewTypeListSrvc();
			interviewTypeListDtls.then(function(msg){
				if (msg.data.result == 'success') {
					$scope.ArrInterviewTypeDtls = msg.data.addndata;
				} 
				else{
					$scope.errmsg = msg.data.message;
				}	
				$timeout($scope.flushMessages,3000);
			});		
		}
		$scope.getInterviewTypeList();
		
		$scope.getActivePositionList = function()
		{
			$scope.loading = true; 
			var PositionDtls = sessionService.PositionDtlsSrvc();
				PositionDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.ArrActivePosDtls = msg.data.addndata;				
					} 
					else{
						$scope.error = msg.data.message;
						$scope.ArrActivePosDtls = {};
					}	
					$scope.loading = false; 
					$timeout($scope.flushMessages,3000);
				});
		}
		$scope.getActivePositionList();
		
		$scope.getArrIntInterfaceIndex = function(index)
		{
			$scope.index = index;
			$scope.feedback = "";
			
			$("#myModal1").modal('show');
			$scope.showfeedbackbtn = false;
			$scope.ShortListedBtn = true;
			$scope.OnHoldBtn = true;
			$scope.RejectedBtn = true;
		}
		
		$scope.saveCandiFeedback = function(feedbackDtls)
		{
			var objfeedback = {};
			$("#myModal1").modal('hide');
			$scope.ArrIntInterfaceDtlsDtls[$scope.index].feedback = feedbackDtls;
			objfeedback = $scope.ArrIntInterfaceDtlsDtls[$scope.index];
			objfeedback.intStatusId = $scope.statusId;
			var Feedback = sessionService.saveCandiFeedbackSrvc(objfeedback);
			Feedback.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.successmsg = msg.data.message;
					$scope.feedback = "";
					$scope.getInteractionInterfaceList();
				} 
				else{
					$scope.errmsg = msg.data.message;
					$scope.feedback = "";
				}	
				$timeout($scope.flushMessages,3000);
			});	 
		}
		
		$scope.updateRRSchedule = function(scheduleData)
		{
			scheduleData.idRRCandidate = $scope.CandidateRRId;
			scheduleData.InteractionDate = $filter('date')(scheduleData.InteractionDate, "yyyy-MM-dd");
			scheduleData.AInteractionDate = $filter('date')(scheduleData.AInteractionDate, "yyyy-MM-dd");
			var scheduleDtls = sessionService.updateRRScheduleSrvc(scheduleData);
			scheduleDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.successmsg = msg.data.message;
					$scope.schedule = {};
					$scope.goInterviewerInt();
					$scope.getInteractionInterfaceList();
				} 
				else{
					$scope.errmsg = msg.data.message;
					$scope.schedule = {};
					$scope.goInterviewerInt();
				}	
				$timeout($scope.flushMessages,3000);
			});		
		}
		
		$scope.getInteractionInterfaceList = function()
		{
			$scope.loading = true; 
			var InteractionInterfaceDtls = sessionService.IntInterfaceDtlsSrvc();
				InteractionInterfaceDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.ArrIntInterfaceDtlsDtls = msg.data.addndata;	
					} 
					else{
						$scope.errmsg = msg.data.message;
						$scope.ArrIntInterfaceDtlsDtls = {};
					}	
					$scope.loading = false; 
					$timeout($scope.flushMessages,3000);
				});
		}
		
		$scope.deactivateCandiRelPos = function(deactivateCandidtls)
		{
			$scope.loading = true; 
			var deactivateCandidate = sessionService.deactivateCandiRelPosSrvc(deactivateCandidtls);
				deactivateCandidate.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.successmsg = msg.data.message;	
						$scope.getPositionRelCandidate($scope.positionData);
						$scope.ArrPosRelCandidatesDtls = "";
					} 
					else{
						$scope.errormessage = msg.data.message;
						$scope.ArrActivePosDtls = {};
					}	
					$scope.loading = false; 
					$timeout($scope.flushMessages,3000);
				});
		}
		$scope.getInteractionInterfaceList();
		
		$scope.getPositionRelCandidate = function(posDtls)
		{
			$scope.myRRPanel = false;
			$scope.positionData = posDtls;
			$scope.PositionDetailsPanel = true;
			$scope.loading = true;
			$scope.Position = posDtls.Position;
			$scope.Openings = posDtls.Openings;
			$scope.remainingOpening = posDtls.remainingOpening;
			$scope.RequestDate = posDtls.RequestDate;
			$scope.ExpectedDate = posDtls.ExpectedDate;
			for(var i=0;i<$scope.ArrEmployeeDtls.length;i++)
			{
				if($scope.ArrEmployeeDtls[i].EmpId == posDtls.EmpId)
				{
					$scope.Name = $scope.ArrEmployeeDtls[i].Name;
					$scope.EmpId = $scope.ArrEmployeeDtls[i].EmpId;
					$scope.EmailId = $scope.ArrEmployeeDtls[i].EmailId;
				}
			}
	
			var PositionRelCandiDtls = sessionService.getPositionRelCandidateSrvc(posDtls);
			PositionRelCandiDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.ArrPosRelCandidate = msg.data.addndata;
					for(var i=0;i<$scope.ArrPosRelCandidate.length;i++)
					{
						if($scope.ArrPosRelCandidate[i].IsActiveInteraction == 0)
						{
							$scope.ArrPosRelCandidate[i].intDate = "";							
							$scope.ArrPosRelCandidate[i].altIntDate = "";							
							$scope.ArrPosRelCandidate[i].interviewTypeDesc = "";
							$scope.ArrPosRelCandidate[i].NameArr = [];								
						}
					}
					$scope.ArrPosRelCandidatesDtls = $scope.ArrPosRelCandidate;
				} 
				else{
					$scope.errormessage = msg.data.message;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});	
		}
		
		$scope.PlanISBtn = function(obj)
		{
			$scope.idRRCandidate = obj.idRRCandidate;
			$scope.idCandidate = obj.idCandidate;
			if(obj.IsActiveInteraction == null)
			{
				$scope.IsActiveInteraction = 1;
			}
			else
			{
				$scope.IsActiveInteraction = obj.IsActiveInteraction;
			}
			
			$scope.PositionDetailsPanel = false;
			$scope.InteractionSchedulepanel = true;
		}
		$scope.myRRPanel = true;
		
		$scope.showReshedulePanel = function(IntInterfaceDtls)
		{
			$scope.CandidateRRId = IntInterfaceDtls.idRRCandidate;
			$scope.schedule = {};
			$scope.schedule.InteractionDate = IntInterfaceDtls.intDate;
			$scope.schedule.AInteractionDate = IntInterfaceDtls.altIntDate;
			$scope.schedule.Name = IntInterfaceDtls.NameArr;
			for(var j=0;j<$scope.ArrInterviewTypeDtls.length;j++)
			{
				if($scope.ArrInterviewTypeDtls[j].idInterviewType == IntInterfaceDtls.idInterviewType)
				{
					$scope.schedule.interviewTypeDesc = $scope.ArrInterviewTypeDtls[j];
				}
			}
			for(var n=0;n < $scope.ArrEmployeeDtls.length; n++)
			{
				$scope.ArrEmployeeDtls[n].ticked = false;	
			}
			
			for(var m=0;m<$scope.ArrEmployeeDtls.length;m++){
				for(var n=0;n<IntInterfaceDtls.NameArr.length;n++)
				{
					if($scope.ArrEmployeeDtls[m].Name == IntInterfaceDtls.NameArr[n]){
					
						$scope.ArrEmployeeDtls[m].ticked = true;
					}
				}
			}
			$scope.myRRPanel = false;
			$scope.InteractionSchedulepanel = true;
		}
		
		$scope.goInterviewerInt = function()
		{
			$scope.myRRPanel = true;
			$scope.InteractionSchedulepanel = false;
		}
		
		$scope.date = new Date();
		$scope.todate = $filter('date')($scope.date, "yyyy-MM-dd");
		
		$scope.getAllAlerts = function(alertsDtls)
		{
			$scope.date = new Date();
			$scope.todate = $filter('date')($scope.date, "yyyy-MM-dd");
			$scope.alertsDtls = alertsDtls;
			$scope.AlertsPosition = alertsDtls.Position;
		}
		
		$scope.cancelISBtn = function()
		{
			$scope.schedule = {};
			$scope.InteractionSchedulepanel = false;
			$scope.PositionDetailsPanel = true;
		}
		
		$scope.showIIHistory = function(IIDtls)
		{
			$scope.IICadiName = IIDtls.CandidateName;
			$scope.IIPositionName = IIDtls.Position;
			$scope.IIPositionId = IIDtls.idPosition;
			var IntHistoryDtls = sessionService.getIntHistoryDtlsSrvc(IIDtls);
			IntHistoryDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.ArrIntHistory = msg.data.addndata;
					$("#myModal2").modal('show');
				} 
				else{
					$scope.errmsg = msg.data.message;
				}	
				$timeout($scope.flushMessages,3000);
			});	
		}
		
		$scope.saveRRSchedule = function(scheduledata)
		{
			scheduledata.idRRCandidate = $scope.idRRCandidate;
			scheduledata.idCandidate = $scope.idCandidate;
			scheduledata.IsActiveInteraction = $scope.IsActiveInteraction;
			var ScheduleDtls = sessionService.saveRRScheduleSrvc(scheduledata);
			ScheduleDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.InteractionSchedulepanel = false;
					$scope.PositionDetailsPanel = true;
					$scope.getPositionRelCandidate($scope.positionData);
					$scope.successmsg = msg.data.message;
					$scope.obj = {};
					$scope.schedule = {};
				} 
				else{
					$scope.errormessage = msg.data.message;
					$scope.obj = {};
					$scope.schedule = {};
				}	
				$timeout($scope.flushMessages,3000);
			});	
		}
		
		$scope.RRScheduleBCrumb = function()
		{
			$scope.PositionDetailsPanel = false;
			$scope.InteractionSchedulepanel = false;
			$scope.AddRRCandidatePanel = false;
			$scope.myRRPanel = true;
			$scope.ArrPosRelCandidatesDtls = '';
		}
		
		$scope.getRRCandidateDtls = function(obj)
		{
			$scope.RRCandidateDtls = obj;
			$scope.RRId = obj.idRR;
			$scope.AddRRCandidatePanel = true;
			$scope.myRRPanel = false;
			$scope.loading = true;
			$scope.idPosition = obj.idPosition;
			$scope.PositionName = obj.Position;
			$scope.Role = obj.Role;
			$scope.Responsibility = obj.Responsibility;
			var SearchCandiReq = sessionService.getRRCandidateDtlsSrvc(obj);
			SearchCandiReq.then(function(msg)
			{
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') 
				{
					$scope.ArrCandiSearch = msg.data.addndata;
					for(var i=0;i<$scope.ArrCandiSearch.length;i++)
					{
						if($scope.ArrCandiSearch[i].RRCandiActive != undefined && $scope.ArrCandiSearch[i].RRCandiActive == 1)
						{
							$scope.ArrCandiSearch[i].RRCandiActive = true;
						}
						else
						{
							$scope.ArrCandiSearch[i].RRCandiActive = false;
						}
					}
				}
				else
				{
					$scope.errorMsg = msg.data.message;
					$scope.ArrCandiSearch =[];
				}
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});
		}
		
		$scope.AddRRCandidate = function(objRRCandidate)
		{
			$scope.loading = true;
			objRRCandidate[0].idPosition = $scope.idPosition;
			objRRCandidate[0].RRId = $scope.RRId;
			var CandidateDtls = sessionService.AddRRCandidateSrvc(objRRCandidate);
			CandidateDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.insermsg = msg.data.message;
					$scope.getRRCandidateDtls($scope.RRCandidateDtls);
				} 
				else{
					$scope.errmsg = msg.data.message;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});		
		}
		
		$scope.getModalData = function(Role,Responsibility)
		{
			$scope.Role = Role;
			$scope.Responsibility = Responsibility;
		} 
		
		$scope.shortListed = function()
		{
			$scope.statusId = 1;
			$scope.showfeedbackbtn = true;
			$scope.ShortListedBtn = true;
			$scope.OnHoldBtn = false;
			$scope.RejectedBtn = false;
		
		} 
		$scope.getCandidateFeedback = function(feedbackDtls)
		{
			$scope.feeback = "";
			$scope.feeback = feedbackDtls.feedback;
			$("#myModal30").modal('show');
		} 
		
		$scope.onHold = function()
		{
			$scope.statusId = 2;
			$scope.showfeedbackbtn = true;
			$scope.ShortListedBtn = false;
			$scope.OnHoldBtn = true;
			$scope.RejectedBtn = false;
		} 
		
		$scope.rejected = function()
		{
			$scope.statusId = 3;
			$scope.showfeedbackbtn = true;
			$scope.ShortListedBtn = false;
			$scope.OnHoldBtn = false;
			$scope.RejectedBtn = true;
		}  
		
		$scope.getRoleHtml = function(obj)
		{
			$scope.objRole = {};
			$scope.RoleDiv = true;
			$scope.objRole = obj;
		}
		
		$scope.leaveRoleHtml = function()
		{
			$scope.RoleDiv = false;
			$scope.objRole = {};
		}

		$scope.getFeedbackHtml = function(objDtls)
		{
			$scope.showFeedback = '';
			$scope.FeedbackDiv = true;
			$scope.showFeedback = objDtls;
		}
		
		$scope.getFeedbackDtls = function(obj)
		{
			$scope.Data = '';
			$scope.Data = obj;
			$scope.ShortListedBtn = true;
			$scope.RejectedBtn = true;
			$scope.OnHoldBtn = true;
		}
		
		$scope.leaveFeedbackHtml = function()
		{
			$scope.showFeedback = '';
			$scope.FeedbackDiv = false;
		}
		
		$scope.RemoveData = function()
		{
			$scope.Data = '';
		}
		
		$scope.goAddcadidateForm = function()
		{
			$location.path('/candidates');
		}
		
		$scope.getAlertdata = function(AlertsData)
		{
			$("#myModal30").modal('show');
			$scope.AlertsPosDtls = AlertsData.Position;
			$scope.AlertsCandiDtls = AlertsData.CandidateName;
			$scope.AlertsCandiDtls = AlertsData.CandidateName;
			$scope.intDate = AlertsData.intDate;
			$scope.altIntDate = AlertsData.altIntDate;
			$scope.interviewTypeDesc = AlertsData.interviewTypeDesc;
			$scope.candiPosStatusDesc = AlertsData.candiPosStatusDesc;
			$scope.NameArr = AlertsData.NameArr;
			$scope.Name = AlertsData.Name;
		}
		
		$scope.cancelBtn = function()
		{
			$scope.AddRRCandidatePanel = false;
			$scope.myRRPanel = true;
			$scope.PositionDetailsPanel = false;
			$scope.ArrCandiSearch = '';
			$scope.ArrPosRelCandidatesDtls = '';
			$scope.getActivePositionList();
		}
		
		$scope.open2 = function($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.opened = false;
		$scope.opened2 = true;
		$scope.minDate = new Date();
		};
		
		$scope.open1 = function($event) {
			$event.preventDefault();
			$event.stopPropagation();
			$scope.opened = true;
			$scope.opened2 = false;
			$scope.minDate = new Date();
		};
		
		var orderBy = $filter('orderBy');
		$scope.order = function(predicate, reverse) {
		$scope.ArrCandiSearch = orderBy($scope.ArrCandiSearch, predicate, reverse);
		$scope.currentPage = 1;
		}; 
	}
]);

