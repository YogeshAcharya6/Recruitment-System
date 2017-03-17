app.controller('UserCtrl', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter',
	function ($scope , $timeout, $window, $http, sessionService, $location, $filter) 
	{
		$scope.flushMessages = function(){
			$scope.errmsg = '';
			$scope.insertmsg = '';
			$scope.errormsg = '';
			$scope.errormsg1 = '';
			$scope.errormsg2 = '';
			$scope.error = '';
			$scope.ElementTypeError = '';
			$scope.insertmsg11 = '';
			$scope.errormsg11 = '';
		}
		
		$scope.closeChagePassword = function()
		{
			$scope.chhpassword = true;
		}
	
		$scope.createUser = function(UserDtls) 
		{
			$scope.loading = true;
			var newUserDtls = sessionService.CreateUserSrvc(UserDtls);
				newUserDtls.then(function(msg){
						var type= typeof msg.data;
						if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
						{
							$scope.$parent.logout();
						}
						else	
						if (msg.data.result == 'success') {
							$scope.loading = false;
							$scope.insertmsg = msg.data.message;
							$scope.register = '';	
						} 
						else{
							
							$scope.errmsg = msg.data.message; 
						}	
						$scope.loading = false;
						$timeout($scope.flushMessages,3000);
				});		 
		}
	
		$scope.getDesignationList = function(){
		
			var DesignationDtls = sessionService.getDesignationSrvc();
				DesignationDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.DesignationListDtls = msg.data.addndata;
					} 
					else{
						$scope.errmsg = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getDesignationList();
	
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
	
		$scope.ChangeUserPassword = function(ChkPassword) 
		{
			$scope.loading = true;
			var a = sessionService.ChangePasswordSrvc(ChkPassword);
				a.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();
					}
					else			
					if (msg.data.result == 'success') {
						$scope.insertmsg = msg.data.addndata;
					}			
					else{
						$scope.errormsg = msg.data.message;	
					}	
					$scope.ChkPassword = '';
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
					
					//$timeout($scope.$parent.flushMessages,3000);
				});	
		}

		$scope.SaveUserRole = function(UserRole){
			$scope.loading = true;
			$scope.UserRole = UserRole;
			$scope.ArrUserRole = [];
			
			for(var i=0;i<$scope.RoleUserDtls.length;i++)
			{
				if($scope.RoleUserDtls[i].IsActive == "0" && $scope.UserRole[i].IsActive == true || $scope.RoleUserDtls[i].IsActive == "1" && $scope.UserRole[i].IsActive == false)
				{
					$scope.UserRole[i].idUser = $scope.idUser;
					$scope.ArrUserRole.push($scope.UserRole[i]);
				}
			}
		
			var UserRolelistDtls = sessionService.saveUserRoleSrvc($scope.ArrUserRole);
				UserRolelistDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg = msg.data.message;
						$scope.loading = false;
						$scope.UserRoleList = false;
						$scope.UserList = true;
						
					} 
					else{
						$scope.errormsg = msg.data.message;
						$scope.loading = true;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	 
		}
		
		$scope.LoadUserRoleList = function(User){
			$scope.loading = true;
			$scope.obj = {};
			$scope.idUser = User.idUser;
			$scope.obj.UserName = User.userId;
			var UserRoleDtls = sessionService.LoadUserRoleSrvc(User);
				UserRoleDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.AllUserRoleDtls = msg.data.addndata;
						$scope.RoleUserDtls = angular.copy($scope.AllUserRoleDtls);
						
						for(var i=0;i<$scope.AllUserRoleDtls.length;i++)
						{
							if($scope.AllUserRoleDtls[i].IsActive == 0)
							{
								$scope.AllUserRoleDtls[i].IsActive = false;
							}
							else
							{
								$scope.AllUserRoleDtls[i].IsActive = true;
							}
						}
						
						$scope.UserList = false;
						$scope.UserRoleList = true;
						$scope.loading = false;
					} 
					else
					{
						$scope.loading = true;
						$scope.errormsg = msg.data.message;
					}
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
	
		$scope.UserRoleDetails = function(){
			$scope.UserRoleList = false;
			$scope.UserList = true;	
		}
	
		$scope.getUserList = function(){
			$scope.UserList = true;
			var UserlistDtls = sessionService.getUserListSrvc();
				UserlistDtls.then(function(msg){
					$scope.loading = true;
					if (msg.data.result == 'success') {
						$scope.AllUserDtls = msg.data.addndata;
						$scope.loading = false;
					} 
					else{
						$scope.errormsg = msg.data.message;
						$scope.loading = true;
					}	
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getUserList(); 
	
		var orderBy = $filter('orderBy');
		  $scope.orderUser = function(predicate, reverse) {
			$scope.AllUserDtls = orderBy($scope.AllUserDtls, predicate, reverse);
			$scope.currentPage = 1;
		};
	}
]);
