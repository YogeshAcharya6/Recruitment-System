app.controller('DashboardCtrl', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter',
	function ($scope , $timeout, $window, $http, sessionService, $location, $filter) 
	{
		$scope.flushMessages = function(){
			$scope.error = '';
		}
		
		$scope.getCntRRJDCandidate = function(){
			$scope.loading = true;
			var CountDtls = sessionService.getCountDtlsSrvc();
				CountDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.ArrCntDtls = msg.data.addndata;
						$scope.CntJD = $scope.ArrCntDtls.JD[0];
						$scope.CntRR = $scope.ArrCntDtls.RR[0];
						$scope.Cntcandidate = $scope.ArrCntDtls.candidate[0];
						$scope.loading = false;
					} 
					else{
						$scope.error = 'Error in sql';
						$scope.loading = false;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getCntRRJDCandidate();
		
		$scope.getCntAddRRJDCandidate = function(){
			$scope.loading = true;
			var AddCountDtls = sessionService.getAddCountDtlsSrvc();
			AddCountDtls.then(function(msg){
				if (msg.data.result == 'success') {
					$scope.ArrAddCntDtls = msg.data.addndata;
					if($scope.ArrAddCntDtls.JD[0] != 0)
					$scope.AddCntJD = $scope.ArrAddCntDtls.JD[0];
					if($scope.ArrAddCntDtls.RR[0] != 0)
					$scope.AddCntRR = $scope.ArrAddCntDtls.RR[0];
					if($scope.ArrAddCntDtls.candidate[0] != 0)
					$scope.AddCntcandidate = $scope.ArrAddCntDtls.candidate[0];
					$scope.loading = false;
				} 
				else{
					$scope.error = 'Error in sql';
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});	
		}
		$scope.getCntAddRRJDCandidate();
		
		$scope.ShowJDDtls = function(){
			$scope.loading = true;
			var JobDescDtls = sessionService.getJobDtlsSrvc();
			JobDescDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.ArrJobDesc = '';
					$scope.JDtbl = true;
					$scope.RRtbl = false;
					$scope.Candidatetbl = false;
					$scope.ArrJobDesc = msg.data.addndata;
					$scope.loading = false;
				} 
				else{
					$scope.error = msg.data.message;
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
		
		$scope.showAddJDDtls = function(){
			$scope.loading = true;
			var AddJobDescDtls = sessionService.getAddJobDtlsSrvc();
			AddJobDescDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.ArrJobDesc = '';
					$scope.JDtbl = true;
					$scope.RRtbl = false;
					$scope.Candidatetbl = false;
					$scope.ArrJobDesc = msg.data.addndata;
					$scope.loading = false;
				} 
				else{
					$scope.error = msg.data.message;
					$scope.loading = false;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});
		}
		
		$scope.ShowRRDtls = function(){
			$scope.loading = true;
			var RRDtls = sessionService.getRRDtlsSrvc();
			RRDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.ArrRR = '';
					$scope.ArrRR = msg.data.addndata;	
					$scope.JDtbl = false;
					$scope.RRtbl = true;
					$scope.Candidatetbl = false;
					$scope.loading = false;
				} 
				else{
					$scope.error = msg.data.message;
					$scope.ArrRR = {};
					$scope.loading = false;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});
		}
		
		$scope.showAddRRDtls = function(){
			$scope.loading = true;
			var AddRRDtls = sessionService.getAddRRDtlsSrvc();
			AddRRDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.ArrRR = '';
					$scope.JDtbl = false;
					$scope.RRtbl = true;
					$scope.Candidatetbl = false;
					$scope.ArrRR = msg.data.addndata;
					$scope.loading = false;
				} 
				else{
					$scope.error = msg.data.message;
					$scope.loading = false;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});
		}
		
		$scope.ShowCandidateDtls = function(){
				$scope.loading = true;
				var CandidateDtls = sessionService.getCandidateDtlsSrvc();
				CandidateDtls.then(function(msg)
				{
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') 
					{
						$scope.ArrCandiSearch = '';
						$scope.JDtbl = false;
						$scope.RRtbl = false;
						$scope.Candidatetbl = true;
						$scope.ArrCandiSearch = msg.data.addndata;
						$scope.loading = false;
					}
					else
					{
						$scope.errorMsg = msg.data.message;
						$scope.ArrCandiSearch =[];
						$scope.loading = false;
					}
					$timeout($scope.flushMessages,3000);
				});
		}
		
		$scope.showAddCandidateDtls = function(){
			$scope.loading = true;
			var AddCandidateDtls = sessionService.getAddCandidateDtlsSrvc();
			AddCandidateDtls.then(function(msg){
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.ArrCandiSearch = '';
					$scope.JDtbl = false;
					$scope.RRtbl = false;
					$scope.Candidatetbl = true;
					$scope.ArrCandiSearch = msg.data.addndata;
					$scope.loading = false;
				} 
				else{
					$scope.error = msg.data.message;
					$scope.loading = false;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});
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
		
		var orderBy = $filter('orderBy');
		$scope.order = function(predicate, reverse) {
		$scope.ArrCandiSearch = orderBy($scope.ArrCandiSearch, predicate, reverse);
		$scope.ArrRR = orderBy($scope.ArrRR, predicate, reverse);
		$scope.currentPage = 1;
		}; 
	}
]); 