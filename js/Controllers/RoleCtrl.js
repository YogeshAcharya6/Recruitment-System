app.controller('RoleCtrl', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter',
	function ($scope , $timeout, $window, $http, sessionService, $location, $filter) 
	{
		$scope.flushMessages = function(){
			$scope.errmsg = '';
			$scope.insertmsg1 = '';
			$scope.errormsg1 = '';
			$scope.errormsg = '';
			$scope.errormsg2 = '';
			$scope.errormsg11 = '';
			$scope.insertmsg2 = '';
			$scope.insertmsg11 = '';
		}
	
		$scope.addRoleBtn = function(){
			$scope.AddRolePanel = true;
			$scope.EditRolePanel = false;
		}
	
		$scope.getRoleList = function(){
			$scope.panelRoleList = true;
			var RolelistDtls = sessionService.getUserRoleSrvc();
				RolelistDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.AllRoleDtls = msg.data.addndata;
					} 
					else
					{
						$scope.AllRoleDtls = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getRoleList(); 
		
		var orderBy = $filter('orderBy');
		  $scope.order = function(predicate, reverse) {
			$scope.AllRoleDtls = orderBy($scope.AllRoleDtls, predicate, reverse);
			$scope.currentPage = 1;
		}; 
	
		$scope.closeRole = function(){
			$scope.EditRolePanel = false;
			$scope.AddRolePanel = false;	
			$scope.panelRoleList = true;
		}
	
		$scope.saveRole = function(role){
			$scope.loading = true;
			var SavrRoleDtls = sessionService.SaveroleSrvc(role);
				SavrRoleDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg1 = msg.data.addndata;
						$scope.getRoleList();
						$scope.loading = false;
						$scope.AddRolePanel = false;
					} 
					else{
						$scope.errormsg1 = msg.data.addndata;
						$scope.loading = false;
					}	
					$scope.roleDtls = '';
					$timeout($scope.flushMessages,3000);
				});		
		}
	
		$scope.role = {};	
		$scope.editRoleLink = function(role){
			$scope.AddRolePanel = false;
			$scope.panelRoleList = false;
			$scope.EditRolePanel = true;
			$scope.editroleDtls = {};
			$scope.editroleDtls.roledescription = role.roleDescription;
			$scope.roleDescription = role.roleDescription;
			$scope.idRole = role.idRole;
			$scope.role = angular.copy(role);
			var RolePermissionDtls = sessionService.getRolePermissionSrvc(role);
				RolePermissionDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();
					}
					else
					if (msg.data.result == 'success') {
						$scope.rolePermissions = msg.data.addndata;
						$scope.ArrrolePermission = angular.copy($scope.rolePermissions);
						for(var i=0;i<$scope.rolePermissions.length;i++)
						{
							if($scope.rolePermissions[i].IsPermitted == 0)
							{
								$scope.rolePermissions[i].IsPermitted = false;
							}
							else
							{
								$scope.rolePermissions[i].IsPermitted = true;
							}
						}
						$scope.getRoleRelUserList();
					} 
					else{
						$scope.errormsg2 = msg.data.addndata;
						$scope.loading = false;
					}	
					$scope.roleDtls = '';
					$timeout($scope.flushMessages,3000);
				});		
		}
	
		$scope.editRole = function(editroleDtls){
			$scope.loading = true;
			var obj = {};
			obj.idRole = $scope.idRole;
			obj.roledescription = editroleDtls.roledescription;
			var RoleDtls = sessionService.EditroleSrvc(obj);
				RoleDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();		
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg2 = msg.data.addndata;
						$scope.getRoleList();
						$scope.loading = false;
						$scope.EditRolePanel = false;
					} 
					else{
						$scope.errormsg2 = msg.data.addndata;
						$scope.loading = false;
					}	
					$scope.roleDtls = '';
					$timeout($scope.flushMessages,3000);
				});		
		}
	
		$scope.getRoleRelUserList = function(){
			$scope.UserList = true;
			var obj = {};
			obj.idRole = $scope.idRole;
			var UserlistDtls = sessionService.getRoleRelUserListSrvc(obj);
				UserlistDtls.then(function(msg){
					$scope.loading = true;
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();		
					}
					else
					if (msg.data.result == 'success') {
						$scope.AllRoleRelUserDtls = msg.data.addndata;
						$scope.loading = false;
					} 
					else{
						$scope.errorm1 = msg.data.message;
						$scope.loading = false;
					}	
					$timeout($scope.flushMessages,3000);
				});	
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
	
		$scope.goToAddUser = function(){
			/* $location.path('/AddUser'); */
			$("#UserModal").modal('show');
		}
	
		$scope.saveRolePerm = function(RolePerm){
			$scope.RolePerm = RolePerm;
			$scope.ChangeRolePermDtls = [];
			for(var i = 0; i < $scope.RolePerm.length; i++)
			{
				if($scope.ArrrolePermission[i].IsPermitted == "0" && $scope.RolePerm[i].IsPermitted == true)
				{
					$scope.RolePerm[i].IsPermitted = 1;
					$scope.ChangeRolePermDtls.push($scope.RolePerm[i]);
				}
				else
				if($scope.ArrrolePermission[i].IsPermitted == "1" && $scope.RolePerm[i].IsPermitted == false)
				{
					$scope.RolePerm[i].IsPermitted = 0;
					$scope.ChangeRolePermDtls.push($scope.RolePerm[i]);
				}
				
			}
			var SavrRolePermDtls = sessionService.SaveRolePermSrvc($scope.ChangeRolePermDtls);
				SavrRolePermDtls.then(function(msg){
					$scope.loading = true;
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg2 = msg.data.message;
						$scope.loading = false;
						$scope.editRoleLink($scope.role);
					} 
					else{
						$scope.errormsg2 = msg.data.message;
						$scope.loading = false;
					}	
					$scope.ChangeRolePermDtls = '';
					$timeout($scope.flushMessages,3000);
				}); 
		}
	
		$scope.deActivateUser = function(userdata){
			$scope.loading = true;
			var Userresult = sessionService.deactivateUserSrvc(userdata);
				Userresult.then(function(msg){
					$scope.loading = true;
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg11 = msg.data.message;
						$scope.loading = false;
						$scope.getUserList();
					} 
					else{
						$scope.errormsg11 = msg.data.message;
						$scope.loading = true;
					}	
					$timeout($scope.flushMessages,3000);
				});	
		}
	}
]);
