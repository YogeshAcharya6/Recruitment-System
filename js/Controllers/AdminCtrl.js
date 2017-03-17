app.controller('AdminCtrl', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter','$rootScope',
	function ($scope , $timeout, $window, $http, sessionService, $location, $filter) 
	{
		$scope.flushMessages = function(){
			$scope.errmsg = '';
			$scope.error = '';
			$scope.insertmsg1 = '';
			$scope.errormsg1 = '';
			$scope.ElementListError = '';
			$scope.ElementTypeError = '';
		}	
		$scope.getDeviceName = function(){
				$http.post('data/index.php/detectDevice').success(function(data) {
					$scope.deviceName = data.addndata;
					if($scope.deviceName == 'phone')
					{
						$scope.flag = 1;
					}
				});
		}
		
		$scope.getDeviceName();
		$scope.userRolePermissionDtls = {};
		$scope.$on("userRolePermission",function(e,args)
		{
			
			$scope.userdiv = true;
			$scope.header2 = true;
			$scope.header1 = true;
			if($scope.flag == 1)
			{
				$scope.Alerts = true;
				$location.path("/Alerts");
			}
			else
			{
				
				if($scope.userRolePermissionDtls && $scope.userRolePermissionDtls.length > 0){
					for(var i=0;i<$scope.userRolePermissionDtls.length;i++){
						var elementDesc = $scope.userRolePermissionDtls[i].elementDesc;
						$scope[elementDesc] = false;
					}
				}
				$scope.userRolePermissionDtls = angular.copy(args.userRolePermission);
				if($scope.userRolePermissionDtls != undefined){
					for(var i=0;i<$scope.userRolePermissionDtls.length;i++)
					{
						var elementDesc = $scope.userRolePermissionDtls[i].elementDesc;
						$scope[elementDesc] = true;
					}
				}
				
				if($scope.ChangePassword == true){ $scope.Maintain = true; $location.path( "/ChangePassword");}
				else if($scope.User == true){ $scope.Maintain = true;$location.path( "/UserList");} 
				else if($scope.Roles == true){ $scope.Maintain = true;$location.path( "/RoleList");} 
				else if($scope.Configure == true){ $scope.Maintain = true;$location.path( "/ConfigureList");}
				else if($scope.Elements == true){ $scope.Maintain = true;$location.path( "/Element");}
				
				if($scope.Alerts == true) 
					$location.path( "/Alerts");
				
				if($scope.AddUser == true){$scope.Admin = true; $location.path( "/AddUser");}
				
				if($scope.JobDescription == true){$scope.page = 'JobDescription';$scope.Admin = true; $location.path( "/JobDescription");}
				else if($scope.RecruitmentRequest == true){$scope.page = 'RecruitmentRequest';$scope.Admin = true; $location.path( "/RecruitmentRequest");}
				else if($scope.Candidates == true){$scope.page = 'candidates';$scope.Admin = true; $location.path( "/Candidates");}
				else if($scope.RRSchedule == true){$scope.page = 'RRSchedule';$scope.Admin = true; $location.path( "/RRSchedule");}
				else if($scope.InterviewerInterface == true){$scope.page = 'InterviewerInterface';$scope.Admin = true; $location.path( "/InterviewerInterface");}
				else if($scope.Dashboard == true){$scope.page = 'Dashboard';$scope.Admin = true; $location.path( "/Dashboard");}
			}
		});
		
		$scope.$on("LoginId",function(e,args)
		{
			$scope.LoginId = angular.copy(args.LoginId);
		});
		
		$scope.$on("AlertsCount",function(e,args)
		{
			$scope.AlertsCount = angular.copy(args.AlertsCount);
		});
		
		$scope.$on("userName",function(e,args)
		{
			$scope.userdiv = true;
			$scope.userName = args.userName;
			$scope.cesh1 = true;
			$scope.cesh = true;
		});
		
		$scope.logout = function() 
		{
			var LogoutDtls = sessionService.LogoutUserSrvc();
				LogoutDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						sessionService.removeSession();
						$scope.LoginId = '';
						$scope.Login = {};
						if($scope.UserRolePermissionDtls != undefined){
							for(var i=0;i<$scope.UserRolePermissionDtls.length;i++)
							{
								var elementDesc = $scope.UserRolePermissionDtls[i].elementDesc;
								$scope[elementDesc] = false;
							}
						}
						$scope.userName = '';
						$scope.AlertsCount = '';
						$scope.userdiv = false;
						$location.path('/');
						$scope.cesh1 = false;
						$scope.cesh = false;
						$scope.header2 = false;
						$scope.header1 = false;
						$scope.errmsg = "Session TimeOut!Please Login Again"; 
					}
					else
					if (msg.data.result == "success") {
						sessionService.removeSession();
						$scope.LoginId = '';
						$scope.Login = {};
						
						if($scope.UserRolePermissionDtls != undefined){
							for(var i=0;i<$scope.UserRolePermissionDtls.length;i++)
							{
								var elementDesc = $scope.UserRolePermissionDtls[i].elementDesc;
								$scope[elementDesc] = false;
							}
						}
						$scope.userName = '';
					    $scope.AlertsCount = '';
						$scope.userdiv = false;
						$location.path('/');
						$scope.cesh1 = false;
						$scope.cesh = false;
						$scope.header2 = false;
						$scope.header1 = false;
					} 
					else{
						$scope.errmsg = msg.data.message; 
						$scope.header2 = true;
						$scope.header1 = true;
					}	
					$timeout($scope.flushMessages,5000);
				});		 
		}
		
		$scope.definepage = function(pageName){
			$scope.page = pageName;
			$location.path( "/" + $scope.page);
		}; 
		
		$scope.date = new Date();
		$scope.day = weekday[$scope.date.getDay()];
		$scope.format = 'HH:mm:ss';
		
		$scope.addRoleBtn = function(){
			$scope.AddRolePanel = true;
			$scope.EditRolePanel = false;	
		}
	
		$scope.getConfigureList = function(){
		
			var ConfigurelistDtls = sessionService.getConfigureSrvc();
				ConfigurelistDtls.then(function(msg){
					$scope.loading = true;
					if (msg.data.result == 'success') {
						$scope.ArrCofigureDtls = msg.data.addndata;
						$scope.Configure = angular.copy($scope.ArrCofigureDtls);
					} 
					else{
						$scope.error = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getConfigureList(); 
	
		$scope.EditConfigureDtls = function(ConfigureDtls){
		
			$scope.ConfigureDtls = ConfigureDtls;
			$scope.ChangeConfigureDtls = [];
			var j = 0;
			for(var i = 0; i < $scope.ConfigureDtls.length; i++)
			{
				if($scope.Configure[i].searchvalue != $scope.ConfigureDtls[i].searchvalue)
				{
					$scope.ChangeConfigureDtls[j] = $scope.ConfigureDtls[i];
					j++;
				}
			}
			var EditConfigure = sessionService.EditConfigureSrvc($scope.ChangeConfigureDtls);
				EditConfigure.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg1 = msg.data.message;
					} 
					else{
						$scope.errormsg1 = msg.data.message;
						$scope.loading = false;
					}	
					$scope.roleDtls = '';
					$timeout($scope.flushMessages,3000);
				});		
		}
	
		$scope.getElementList = function(){
		
			var ElementListDtls = sessionService.getElementListSrvc();
				ElementListDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.ElementListDtls = msg.data.addndata;
					} 
					else{
						$scope.ElementListError = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getElementList(); 
	
		$scope.goElementList = function(){
			$scope.AddElementPanel = false;
		}
	
		$scope.goElement = function(){
			$scope.AddElementPanel = true;
		}
	
		$scope.saveElement = function(Element){
			$scope.loading = true;
			var SavrElementDtls = sessionService.SaveElementSrvc(Element);
				SavrElementDtls.then(function(msg){
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();
					}
					else
					if (msg.data.result == 'success') {
						$scope.insertmsg1 = msg.data.addndata;
						$scope.getElementList();
						$scope.loading = false;
						$scope.AddElementPanel = false;
					} 
					else{
						$scope.errormsg1 = msg.data.message;
						$scope.loading = false;
					}	
					$scope.Element = '';
					$timeout($scope.flushMessages,3000);
				});		
		}
	
		$scope.getElementType = function(){
			var ElementTypeDtls = sessionService.getElementTypeSrvc();
				ElementTypeDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.ElementTypeDtls = msg.data.addndata;
					} 
					else{
						$scope.ElementTypeError = msg.data.message;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getElementType(); 
	}
]);