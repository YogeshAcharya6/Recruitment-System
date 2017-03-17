app.controller('LoginCtrl', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter','$rootScope',
	function ($scope , $timeout, $window, $http, sessionService, $location, $filter, $rootScope) 
	{
		$scope.flushMessages = function(){
			$scope.errmsg = '';
			$scope.errormsg = '';
		}
		
		$scope.chkLogin = function(UserLogin){
			$scope.loading = true;
			$http.post('data/index.php/chkLogin',UserLogin).success(function(data) {
				if(data.result == 'success')
				{	
					$scope.user= angular.copy(UserLogin.userId);
					var obj ={};
						obj = data.addndata;
						$scope.StoreObjDetails = obj;
						$scope.LoginId = $scope.StoreObjDetails.idUser;
						sessionService.createSession( $scope.StoreObjDetails );
						$scope.getUserRolePermissionList($scope.user,$scope.LoginId);
						$scope.getUserRoleList($scope.user,$scope.LoginId);
				}
				else
				{
					$scope.Login = {};
					$scope.errmsg = data.addndata;	
				}
				$scope.loading = false;
				$timeout($scope.flushMessages , 3000);
			});
		}
	
		$scope.getUserRolePermissionList = function(userName,LoginId){
			var UserRolePermissionlistDtls = sessionService.getUserRolePermissionSrvc();
				UserRolePermissionlistDtls.then(function(msg){
					$scope.loading = true;
					if (msg.data.result == 'success') {
						$scope.UserRolePermissionDtls = angular.copy(msg.data.addndata);
						$scope.loading = false;
						$scope.$root.$broadcast("LoginId",{LoginId:LoginId})
						$scope.$root.$broadcast("userRolePermission",{userRolePermission:$scope.UserRolePermissionDtls})
						$scope.$root.$broadcast("userName",{userName:userName})
					} 
					else{
						$scope.errormsg = msg.data.message;
						$scope.loading = true;
					}	
					$timeout($scope.flushMessages,3000);
				});	
		}
		
		$scope.getUserRoleList = function(userName,LoginId){
			var UserRolelistDtls = sessionService.getUserRoleDtlsSrvc();
				UserRolelistDtls.then(function(msg){
					$scope.loading = true;
					if (msg.data.result == 'success') {
						$scope.UserRoleDtls = angular.copy(msg.data.addndata);
						$scope.loading = false;
						sessionService.createSession1($scope.UserRoleDtls);
						$scope.getAlertCount();	
					} 
					else{
						$scope.errormsg = msg.data.message;
						$scope.loading = true;
					}	
					$timeout($scope.flushMessages,3000);
				});	
		}
		
		$scope.getAlertCount = function(){
			var UserAlertsDtls = sessionService.getUserAlertsSrvc();
				UserAlertsDtls.then(function(msg){
					$scope.loading = true;
					if (msg.data.result == 'success') {
						$scope.AlertsCount = angular.copy(msg.data.addndata);
						$rootScope.$broadcast("AlertsCount",{AlertsCount:$scope.AlertsCount})
					} 
					else{
						$scope.error = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	 
		}
	
	}
]);