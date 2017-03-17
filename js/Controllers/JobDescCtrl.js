app.controller('JobDescCtrl', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter',
	function ($scope , $timeout, $window, $http, sessionService, $location, $filter) 
	{
		$scope.flushMessages = function(){
			$scope.errmsg = '';
			$scope.insertmsg = '';
			$scope.error = '';
			$scope.successmsg = '';
			$scope.EditRRerror = '';
		}
		
		$scope.htmlcontent = $scope.orightml;//used for text-editor
		$scope.disabled = false;
		$scope.searchjobpanel = true;
		$scope.searchbreadcrumb = true;
		$scope.searchRRPanel = true;
		$scope.AddRecruitmentRequestBtn = true;
		$scope.RRSearchBtn = true;
		$scope.menuSearchBtn = true;
		
		$scope.JDList = function()
		{
			$scope.menuSearchBtn = true;
			$scope.searchJDItem = false;
			$scope.addjobpanel = false;
			$scope.Editbreadcrumb = false;
			$scope.Addbreadcrumb = false;
			$scope.JDsearch = false;
			$scope.searchbreadcrumb = true;
			$scope.searchjobpanel = true;
			$scope.searchJobDesc($scope.job);
		}

		$scope.getPositionList = function(){
		var PositionListDtls = sessionService.getPositionListSrvc();
				PositionListDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.ArrPositionListDtls = msg.data.addndata;
					} 
					else{
						$scope.errmsg = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getPositionList();  
	
		$scope.getSkillList = function(){
		var SkillListDtls = sessionService.getSkillListSrvc();
				SkillListDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.ArrSkillListDtls = angular.copy(msg.data.addndata);
						$scope.ArrSkillList = msg.data.addndata;
					} 
					else{
						$scope.errmsg = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getSkillList(); 
	
		$scope.getDepartmentList = function(){
			var DepartmentDtls = sessionService.getDepartmentSrvc();
				DepartmentDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.DepartmentListDtls = msg.data.addndata;
					} 
					else{
						$scope.errmsg = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getDepartmentList(); 
	
		$scope.getStatusList = function(){
		
			var StatusDtls = sessionService.getStatusSrvc();
				StatusDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.StatusListDtls = msg.data.addndata;
					} 
					else{
						$scope.errmsg = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getStatusList(); 
	
		$scope.getJdRelPos = function(posDtls){
			var JDDtls = sessionService.getJDDtlsSrvc(posDtls);
				JDDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.ArrJdRelPos = msg.data.addndata;
						$scope.ExperianceMin = $scope.ArrJdRelPos[0].ExperianceMin;
						$scope.ExperianceMAx = $scope.ArrJdRelPos[0].ExperianceMAx;
						$scope.Qualification = $scope.ArrJdRelPos[0].Qualification;
						$scope.Skill = $scope.ArrJdRelPos[0].Skill;
						$scope.idJobDesc = angular.copy($scope.ArrJdRelPos[0].idJobDesc);
					} 
					else{
						$scope.error = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}

		$scope.SaveRR = function(RRDtls){
			RRDtls.idJobDesc = $scope.idJobDesc;
			var RRDtls = sessionService.SaveRRSrvc(RRDtls);
				RRDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.successmsg = msg.data.message;
						$scope.RR = {};
						$scope.Skill = '';
						$scope.Qualification = '';
						$scope.ExperianceMin = '';
						$scope.ExperianceMAx = '';
						$scope.date = new Date();
						$scope.RR.RequestDate = $filter('date')($scope.date, "yyyy-MM-dd");
						
					} 
					else{
						$scope.error = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	 
		}
	
		$scope.getQualificationList = function(){
		var QualificationDtls = sessionService.getQualificationListSrvc();
				QualificationDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.ArrQualificationListDtls = angular.copy(msg.data.addndata);
						$scope.ArrQualificationList = msg.data.addndata;
					} 
					else{
						$scope.errmsg = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getQualificationList(); 
	
		$scope.getAddedJobDescPositionList = function(){
		var JobDescPosDtls = sessionService.getJobDescPosListSrvc();
				JobDescPosDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.ArrJobDescPosDtls = msg.data.addndata;
					} 
					else{
						$scope.error = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getAddedJobDescPositionList();
	
		$scope.addJobDesc = function(JobDtls)
		{
			$scope.loading = true;
			var JobDesc = sessionService.addJobDescSrvc(JobDtls);
				JobDesc.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg = msg.data.message;
						$scope.loading = false;
						$scope.jobDtls = {};
						$scope.getQualificationList(); 
						$scope.getSkillList(); 
						$scope.jobDtls.Responsibility = '';
					} 
					else{
						$scope.errmsg = msg.data.message;
						$scope.jobDtls = {};
						$scope.getQualificationList(); 
						$scope.getSkillList(); 
						$scope.jobDtls.Responsibility = '';
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				}); 
		}
	
		$scope.getEmpDept = function(EmpDtls)
		{
			if(EmpDtls.EmpId != undefined)
			{
				var EmpDept = sessionService.getEmpDeptSrvc(EmpDtls);
				EmpDept.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.EmpDeptDtls = msg.data.addndata;
						for(var j=0;j<$scope.DepartmentListDtls.length;j++){
							if($scope.DepartmentListDtls[j].idDept == $scope.EmpDeptDtls.DeptId){
									$scope.RR.Department= $scope.DepartmentListDtls[j];
								break;
							}
						}
						$scope.loading = false;
					} 
					else{
						$scope.error = msg.data.message;
						$scope.RR = {}; 
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});
			}
		}
	
		$scope.job = {};
		$scope.searchJobDesc = function(searchjobDtls)
		{
			$scope.loading = true;
			$scope.job = searchjobDtls;
			var JobDescDtls = sessionService.searchJobDtlsSrvc(searchjobDtls);
				JobDescDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.ArrJobDesc = msg.data.addndata;
						$scope.loading = false;
					} 
					else{
						$scope.error = msg.data.message;
						$scope.ArrJobDesc = "";
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});
		}
		
		$scope.searchjob = {};
		$scope.searchJobDesc($scope.searchjob);
		$scope.RRData = {};
	
		$scope.searchRR = function(searchRRDtls)
		{
			$scope.RR = searchRRDtls;
			searchRRDtls.Expected = $filter('date')(searchRRDtls.Expected, "yyyy-MM-dd");
			searchRRDtls.Request = $filter('date')(searchRRDtls.Request, "yyyy-MM-dd");
			$scope.loading = true;
			var RRDtls = sessionService.searchRRDtlsSrvc(searchRRDtls);
				RRDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.ArrRR = msg.data.addndata;	
					} 
					else{
						$scope.error = msg.data.message;
						$scope.ArrRR = {};
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});
		}
	
		$scope.searchrr = {};
		$scope.searchRR($scope.searchrr);
	
		$scope.editJD = function(editjobDtls)
		{
			editjobDtls.idJobDesc = $scope.idJD;
			editjobDtls.PositionId = $scope.PositionId;
			$scope.loading = true;
			var editJobDescDtls = sessionService.editJDDtlsSrvc(editjobDtls);
				editJobDescDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg = msg.data.message;
						$scope.jobDtls = {};
						$scope.jobDtls.Role = '';
						$scope.jobDtls.Responsibility = '';
						$scope.cancelJDBtn();
					} 
					else{
						 $scope.errmsg = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				}); 
		}

		$scope.editJDDtls = function(jdDtls)
		{
			$scope.idJD = jdDtls.idJobDesc;
			$scope.PositionId = jdDtls.PositionId;
			$scope.searchbreadcrumb = false;
			$scope.searchjobpanel = false;
			$scope.AddJDSaveBtn = false;
			$scope.Editbreadcrumb = true;
			$scope.addjobpanel = true;
			$scope.EditJDBtn = true;
			$scope.EditJobLabel = true;
			$scope.jobDtls.QualiName = jdDtls.QualiArr;
			$scope.jobDtls.SkillName = jdDtls.TechSkillArr;
			for(var n=0;n < $scope.ArrQualificationList.length; n++)
			{
				$scope.ArrQualificationList[n].ticked = false;	
			}
			for(var m=0;m<$scope.ArrQualificationList.length;m++){
				for(var n=0;n<jdDtls.QualiArr.length;n++)
				{
					if($scope.ArrQualificationList[m].QualiName == jdDtls.QualiArr[n].QualiName){
					
						$scope.ArrQualificationList[m].ticked = true;
					}
				}
			}
			for(var n=0;n < $scope.ArrSkillList.length; n++)
			{
				$scope.ArrSkillList[n].ticked = false;	
			}
			for(var m=0;m<$scope.ArrSkillList.length;m++){
				for(var n=0;n<jdDtls.TechSkillArr.length;n++)
				{
					if($scope.ArrSkillList[m].SkillName == jdDtls.TechSkillArr[n].SkillName){
						$scope.ArrSkillList[m].ticked= true;
					}
				}
			} 
			$scope.jobDtls.minexp = jdDtls.ExperianceMin;
			$scope.jobDtls.maxexp = jdDtls.ExperianceMAx;
			$scope.jobDtls.Position = jdDtls.Position;
			$scope.jobDtls.Role = jdDtls.Role;
			$scope.jobDtls.Responsibility = jdDtls.Responsibility;
		}
	
		$scope.AddJobBtn = function()
		{
			$scope.jobDtls = {};
			$scope.searchjobpanel = false;
			$scope.Editbreadcrumb = false;
			$scope.EditJobLabel = false;
			$scope.EditJDBtn = false;
			$scope.searchbreadcrumb = false;
			$scope.addjobpanel = true;
			$scope.AddJDSaveBtn = true;
			$scope.Addbreadcrumb = true;
			for(var n=0;n < $scope.ArrSkillList.length; n++)
			{
				$scope.ArrSkillList[n].ticked = false;	
			}
			for(var n=0;n < $scope.ArrQualificationList.length; n++)
			{
				$scope.ArrQualificationList[n].ticked = false;	
			}
		}
	
		$scope.clearRR = function()
		{
			$scope.getQualificationList();
			$scope.getSkillList();
			$scope.ArrRR = '';
			$scope.searchRR($scope.searchrr);
		}
	
		$scope.SearchJobBtn = function()
		{
			$scope.searchjobpanel = true;
			$scope.searchbreadcrumb = true;
			$scope.addjobpanel = false;
			$scope.Editbreadcrumb = false;
			$scope.Addbreadcrumb = false;
			$scope.searchJDItem = true;
			$scope.menuSearchBtn = false;
			$scope.JDsearch = true;
			$scope.searchJobDesc($scope.job);
		}
	
		$scope.AddRRBtn = function()
		{
			$scope.searchRRPanel = false;
			$scope.RRSearch = false;
			$scope.AddRecruitmentRequestBtn = false;
			$scope.addRRPanel = true;
			$scope.RRAdd = true;
			$scope.RRSearchBtn = true;
			$scope.editRRPanel = false;
			$scope.RREdit = false;
			$scope.RR = {};
			$scope.RR.RequestDate = new Date();
			$scope.RR.Openings = 1;
			$scope.RR.RequestDate = $filter('date')($scope.RR.RequestDate, "yyyy-MM-dd");
			$scope.minDate = new Date();
			$scope.getStatusList();
		}
	
		$scope.clearJobDesc = function()
		{
			$scope.getQualificationList();
			$scope.getSkillList();
			$scope.searchjobDtls = {};
			$scope.ArrJobDesc = '';
			$scope.searchJobDesc($scope.searchjob);
		}
	
		$scope.SearchRRBtn = function()
		{
			$scope.addRRPanel = false;
			$scope.RRAdd = false;
			$scope.RRSearchBtn = false;
			$scope.searchRRPanel = true;
			$scope.RRSearch = true;
			$scope.AddRecruitmentRequestBtn = true;
			$scope.editRRPanel = false;
			$scope.RREdit = false;
			$scope.searchRRItem = true;
			$scope.RRSearchBtn = false;
			$scope.getStatusList();
			$scope.RR = {};
			$scope.searchRR($scope.RR);
			$scope.RR.RequestDate = new Date();
			$scope.RR.RequestDate = $filter('date')($scope.RR.RequestDate, "yyyy-MM-dd");
			$scope.minDate = new Date();
		}
	
		$scope.showRRlist = function()
		{
			$scope.RRSearchBtn = true;
			$scope.AddRecruitmentRequestBtn = true;
			$scope.RRSearch = false;
			$scope.searchRRItem = false;
			$scope.addRRPanel = false;
			$scope.editRRPanel = false;
			$scope.RRAdd = false;
			$scope.RREdit = false;
			$scope.searchRRPanel = true;
			$scope.searchRR($scope.RR);
		}
	
		$scope.cancelRRBtn = function()
		{
			$scope.addRRPanel = false;
			$scope.RRAdd = false;
			$scope.searchRRPanel = true;
			$scope.RRSearch = true;
			$scope.AddRecruitmentRequestBtn = true;
			$scope.editRRPanel = false;
			$scope.RREdit = false;
			if($scope.searchRRItem == true)
			{
				$scope.RRSearchBtn = false;
				$scope.searchRRItem = true;
				$scope.RRSearch = true;
			}
			else
			{
				$scope.RRSearchBtn = true;
				$scope.searchRRItem = false;
				$scope.RRSearch = false;
			}
			$scope.searchRR($scope.RR);
		}
	
		$scope.cancelJDBtn = function()
		{
			$scope.addjobpanel = false;
			$scope.Addbreadcrumb = false;
			$scope.Editbreadcrumb = false;
			$scope.searchbreadcrumb = true;
			$scope.searchjobpanel = true;
			if($scope.searchJDItem == true)
			{
				$scope.menuSearchBtn = false;
				$scope.searchJDItem = true;
				$scope.JDsearch = true;
			}
			else
			{
				$scope.menuSearchBtn = true;
				$scope.searchJDItem = false;
				$scope.JDsearch = false;
			}
			$scope.jobDtls = {};
			$scope.jobDtls.Role = '';
			$scope.jobDtls.Responsibility = '';
			$scope.searchJobDesc($scope.job);
		}
	
		$scope.editRR = function(RRData){
			$scope.idRR = RRData.idRR;
			$scope.idJobDesc = RRData.JobDescId;
				$scope.searchRRPanel = false;
				$scope.RRSearch = false;
				$scope.AddRecruitmentRequestBtn = true;
				$scope.RRSearchBtn = true;
				$scope.editRRPanel = true;
				$scope.RREdit = true;
				$scope.RRDtls = {};
				$scope.RRDtls.Openings = RRData.Openings;
				$scope.RRDtls.minSalary = RRData.SalaryMin;
				$scope.RRDtls.maxSalary = RRData.SalaryMax;
				$scope.minDate = RRData.ExpectedDate;
				$scope.minDate = $filter('date')($scope.minDate, "yyyy-MM-dd"); 
				$scope.RRDtls.ExpectedDate = RRData.ExpectedDate;
				$scope.RRDtls.PositionName = RRData.Position;
				$scope.EditStatelistDtls = $scope.StatusListDtls;
				for(var i=0;i<$scope.StatusListDtls.length;i++){
					if($scope.StatusListDtls[i].Status == RRData.Status){
						$scope.RRDtls.Status = $scope.EditStatelistDtls[i];
					}
				} 
		}
	
		$scope.UpdateRR = function(RecReqDtls) 
		{
			RecReqDtls.idRR = $scope.idRR;
			RecReqDtls.idJobDesc = $scope.idJobDesc;
				RecReqDtls.ExpectedDate = $filter('date')(RecReqDtls.ExpectedDate, "yyyy-MM-dd"); 
			var RRDtls = sessionService.UpdateRRSrvc(RecReqDtls);
				RRDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.successmsg = msg.data.message;
						/* $scope.SearchRRBtn(); */
						$scope.cancelRRBtn();
					} 
					else{
						$scope.EditRRerror = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	 
		};
	
		$scope.toggleMin = function() 
		{
			$scope.RR = {};
			$scope.RR.RequestDate = new Date();
			$scope.RR.RequestDate = $filter('date')($scope.RR.RequestDate, "yyyy-MM-dd");
			$scope.minDate = new Date();
		};  
		$scope.toggleMin();
		
		$scope.open2 = function($event) {
			$event.preventDefault();
			$event.stopPropagation();
			$scope.opened = false;
			$scope.opened2 = true;
		};
		
		$scope.open1 = function($event) {
			$event.preventDefault();
			$event.stopPropagation();
			$scope.opened = true;
			$scope.opened2 = false;
		};
	
		$scope.open3 = function($event) {
			$event.preventDefault();
			$event.stopPropagation();
			$scope.opened4 = false;
			$scope.opened3 = true;
		};
	
		$scope.open4 = function($event) {
			$event.preventDefault();
			$event.stopPropagation();
			$scope.opened4 = true;
			$scope.opened3 = false;
		};
	
		$scope.open5 = function($event) {
			$event.preventDefault();
			$event.stopPropagation();
			$scope.opened5 = true;
		};
	
		var orderBy = $filter('orderBy');
		  $scope.order = function(predicate, reverse) {
			$scope.ArrRR = orderBy($scope.ArrRR, predicate, reverse);
			$scope.currentPage = 1;
		}; 
	
		$scope.getModalData = function(Role,Responsibility)
		{
			$scope.Role = Role;
			$scope.Responsibility = Responsibility;
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
	}
]);

