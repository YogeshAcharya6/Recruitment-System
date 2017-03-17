'use strict';

app.factory('sessionService', ['$http', function($http){
	 var objDetails = {};
	 var RoleDtls = {};
		return{
			createSession: function(data) {
					this.objDetails = data;
			},
			createSession1: function(data) {
					this.RoleDtls = data;
			},
			CreateUserSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/createUser', data);
				return $promise;
			},
			getUserAlertsSrvc: function() {
				var data = {};
				var RoleDtls = this.RoleDtls;
					data.RoleDtls = RoleDtls;
				var $promise = $http.post('data/index.php/getUserAlert', data);
				return $promise;
			},
			getUserAlertsDtlsSrvc: function() {
				var data = {};
				var RoleDtls = this.RoleDtls;
					data.RoleDtls = RoleDtls;
				var $promise = $http.post('data/index.php/getUserAlertDtls', data);
				return $promise;
			},
			getUserRelFeedbackListSrvc: function() {
				var data = {};
				var RoleDtls = this.RoleDtls;
				var objContext = this.objDetails;
					data.RoleDtls = RoleDtls;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getUserRelFeedbackList', data);
				return $promise;
			},
			deactivateUserSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/deactivateUser', data);
				return $promise;
			},
			removeSession: function() {
					 var objDetails = {};
			},
			ChangePasswordSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/ChangePassword', data);
				return $promise;
			},
			UpdateRRSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/UpdateRR', data);
				return $promise;
			},
			SearchCandiSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/SearchCandidate', data);
				return $promise;
			},
			getRRCandidateDtlsSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getRRCandidateDtls', data);
				return $promise;
			},
			getPositionRelCandidateSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getPositionRelCandidate', data);
				return $promise;
			},
			updateCandidateSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/UpdateCandidate', data);
				return $promise;
			},
			SaveCandidateSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/SaveCandidate', data);
				return $promise;
			},
			saveCandiFeedbackSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/saveCandiFeedback', data);
				return $promise;
			},
			updateRRScheduleSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/updateRRSchedule', data);
				return $promise;
			},
			deactivateCandiRelPosSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/deactivateCandiRelPos', data);
				return $promise;
			},
			updateCandiFeedbackSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/updateCandiFeedback', data);
				return $promise;
			},
			saveRRScheduleSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/saveRRSchedule', data);
				return $promise;
			},
			getUserRoleSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/RoleList', data);
				return $promise;
			}, 
			getRRDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getRRDtlsSrvc', data);
				return $promise;
			}, 
			getCandidateDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getCandidateDtls', data);
				return $promise;
			}, 
			getInterviewTypeListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getInterviewTypeList', data);
				return $promise;
			}, 
			getAddCountDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getAddCountDtls', data);
				return $promise;
			}, 
			getUserListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/UserList', data);
				return $promise;
			},
			getRoleRelUserListSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getRoleRelUserList', data);
				return $promise;
			},
			AddUserRoleSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/AddUserRoleDtls', data);
				return $promise;
			},
			getCountDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getCountDtls', data);
				return $promise;
			},
			getJobDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getJobDtlsSrvc', data);
				return $promise;
			},
			getAddJobDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getAddJobDtlsSrvc', data);
				return $promise;
			},
			getAddRRDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getAddRRDtlsSrvc', data);
				return $promise;
			},
			getAddCandidateDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getAddCandidateDtlsSrvc', data);
				return $promise;
			},
			getCertificationListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/CertificationList', data);
				return $promise;
			}, 
			getNonTechSkillListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/NonTechSkillList', data);
				return $promise;
			},
			getResumeFileTypeSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getResumeFileType', data);
				return $promise;
			}, 
			getPositionListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/PositionList', data);
				return $promise;
			}, 
			getSkillListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/SkillList', data);
				return $promise;
			}, 			
			getQualificationListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/QualificationList', data);
				return $promise;
			}, 
			getConfigureSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/ConfigureList', data);
				return $promise;
			}, 
			getElementTypeSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/ElementTypeList', data);
				return $promise;
			},
			getElementListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/ElementList', data);
				return $promise;
			}, 
			getUserRolePermissionSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/RolePermissionList', data);
				return $promise;
			},
			getUserRoleDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/UserRoleList', data);
				return $promise;
			}, 
			getDesignationSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/DesignationList', data);
				return $promise;
			},
			getDepartmentSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/DepartmentList', data);
				return $promise;
			},			
			LogoutUserSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/LogoutUser', data);
				return $promise;
			},
			getJobDescPosListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getJobDescPosList', data);
				return $promise;
			},
			getStatusSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getStatusList', data);
				return $promise;
			},
			PositionDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getActivePositionList', data);
				return $promise;
			},
			IntInterfaceDtlsSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/IntInterfaceDtls', data);
				return $promise;
			},
			getIntHistoryDtlsSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getIntHistoryDtls', data);
				return $promise;
			},
			getEmployeeListSrvc: function() {
				var data = {};
				var objContext = this.objDetails;
					data.objContext = objContext;	
				var $promise = $http.post('data/index.php/getEmployeeList', data);
				return $promise;
			},
			
			LoadUserRoleSrvc: function(data)
			{
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/LoadUserRoleDtls', data);
				return $promise;
			},
			getEmpDeptSrvc: function(data)
			{
				
					data.objContext = this.objDetails;;
				var $promise = $http.post('data/index.php/getEmpDept', data);
				return $promise;
			},
			
			getJDDtlsSrvc: function(data)
			{
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getJDDtls', data);
				return $promise;
			},
			
			getJDSrvc: function(data)
			{
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getJD', data);
				return $promise;
			},
			editJDDtlsSrvc: function(data)
			{
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/editJDDtls', data);
				return $promise;
			},
			AddRRCandidateSrvc: function(data)
			{
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/AddRRCandidate', data);
				return $promise;
			},
			SaveRRSrvc: function(data)
			{
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/SaveRR', data);
				return $promise;
			},
			addJobDescSrvc: function(data)
			{
				var objContext = this.objDetails;
				data.objContext = objContext;
				var $promise = $http.post('data/index.php/saveJobDescDtls', data); 
				return $promise;
			},
			searchJobDtlsSrvc: function(data)
			{
				var objContext = this.objDetails;
				data.objContext = objContext;
				var $promise = $http.post('data/index.php/searchJobDescDtls', data); 
				return $promise;
			},
			searchRRDtlsSrvc: function(data)
			{
				data.objContext = this.objDetails;
				var $promise = $http.post('data/index.php/searchRRDtls', data); 
				return $promise;
			},
			SaveroleSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/saveRole', data);
				return $promise;
			},
			getRolePermissionSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/getRolePermission', data);
				return $promise;
			},
			
			SaveElementSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/saveElement', data);
				return $promise;
			},
			EditConfigureSrvc: function(data) {
				var data1 = {};
				data1.data = data;
				var objContext = this.objDetails;
					data1.objContext = objContext;
				var $promise = $http.post('data/index.php/EditConfigure', data1);
				return $promise;
			},
			saveUserRoleSrvc: function(data) {
				var data1 = {};
				data1.data = data;
				var objContext = this.objDetails;
					data1.objContext = objContext;
				var $promise = $http.post('data/index.php/saveUserRole', data1);
				return $promise;
			},
			SaveRolePermSrvc: function(data) {
				var data1 = {};
				data1.data = data;
				var objContext = this.objDetails;
					data1.objContext = objContext;
				var $promise = $http.post('data/index.php/saveRolePermissions', data1);
				return $promise;
			},
			EditroleSrvc: function(data) {
				var objContext = this.objDetails;
					data.objContext = objContext;
				var $promise = $http.post('data/index.php/editRole', data);
				return $promise;
			},
			
	} 
}]); 	

