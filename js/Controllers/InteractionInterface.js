app.controller('InteractionInterface', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter',
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
		
		$scope.myRRPanel = true;
		$scope.showFeedbackBtn = true; 
		
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
		
		$scope.openDocument = function(data)
		{
			if(data!='')
			{
				window.open(data);	
			}
		};
		
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
		$scope.getInteractionInterfaceList();
		
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
		
		$scope.shortListed = function()
		{
			$scope.statusId = 1;
			$scope.showfeedbackbtn = true;
			$scope.ShortListedBtn = true;
			$scope.OnHoldBtn = false;
			$scope.RejectedBtn = false;
		
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
		
		$scope.getCandidateFeedback = function(feedbackDtls)
		{
			$scope.feeback = "";
			$scope.feeback = feedbackDtls.feedback;
			$("#myModal30").modal('show');
		}
		
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
					$scope.getEmployeeList();
				} 
				else{
					$scope.errmsg = msg.data.message;
					$scope.schedule = {};
					$scope.goInterviewerInt();
				}	
				$timeout($scope.flushMessages,3000);
			});		
		}
		
		$scope.goInterviewerInt = function()
		{
			$scope.myRRPanel = true;
			$scope.InteractionSchedulepanel = false;
		}
	}
]);

