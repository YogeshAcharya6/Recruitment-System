'use strict';
app.factory('CESService', function($http){
		var ArrRRSearch = [];
		var RRLastid = 0;
		var ArrCandiSearch = [];
		var CandiLastid = 0;
		var ArrJDSearch = [];
		var JDLastid = 0;
	return {
		
		saveSearchRR :function(data){
				ArrRRSearch = data;
			},
			saveRRLastId:function(RRlastId){
				RRLastid = RRlastId;
			},
			recoverRRLastId :function(){
				return RRLastid;
			}, 
			recoversSearchRR :function(){
				return ArrRRSearch;
			}, 
		saveSearchCandi :function(data){
				ArrCandiSearch = data;
			},
			saveCandiLasId:function(CandilastId){
				CandiLastid = CandilastId;
			},
			recoverCandiLastId :function(){
				return CandiLastid;
			}, 
			recoversSearchCandi :function(){
				return ArrCandiSearch;
			}, 
		saveSearchJD :function(data){
				ArrJDSearch = data;
			},
			saveJDLasId:function(JDlastId){
				JDLastid = JDlastId;
			},
			recoverJDLastId :function(){
				return JDLastid;
			}, 
			recoversSearchJD :function(){
				return ArrJDSearch;
			},
		LoadQualificationSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadQualificationPhp',
				jsonParam : {}
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		LoadSkillsSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadSkillsPhp',
				jsonParam : {}
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		LoadCertiSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadCertiPhp',
				jsonParam : {}
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		LoadNonTechSkillSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadNonTechSkillPhp',
				jsonParam : {}
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		//Save Candidate
		 SaveCandidateSrvc : function(data)
		{
			var jsonRequest = {
				functionCode : 'SaveCandidatePhp',
				jsonParam :data
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		//Search candidate
		SearchCandiSrvc : function(data)
		{
			var jsonRequest = {
				functionCode : 'SearchCandiPhp',
				jsonParam : data
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},		
		//Update candidate
		UpdateCandiSrvc : function(data)
		{
			var jsonRequest = {
				functionCode : 'UpdateCandiPhp',
				jsonParam : data
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		
		
		
		
		//Job Description Services-------------------------------------------------------------------------
		
		LoadQualiSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadQualification' ,
				jsonParam : {}
			}
			var $promise = $http.post('data/JobDesc.php',jsonRequest);
			return $promise;
		},
		
		LoadSkillSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadSkill' ,
				jsonParam : {}
			}
			var $promise = $http.post('data/JobDesc.php',jsonRequest);
			return $promise;
		},
		LoadPositionSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadPosition' ,
				jsonParam : {}
			}
			var $promise = $http.post('data/JobDesc.php',jsonRequest);
			return $promise;
		},
		
		LoadJobDescSrvc : function(data)
		{
			var jsonRequest = {
				functionCode : 'InsertJobDesc' ,
				jsonParam : data
			}
			var $promise = $http.post('data/JobDesc.php',jsonRequest);
			return $promise;
		},
		
		LoadDispSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'DispyJd' ,
				jsonParam : {}
			}
			var $promise = $http.post('data/JobDesc.php',jsonRequest);
			return $promise;
		},
		
		LoadSearchJdSrvc : function(data)
		{
			var jsonRequest = {
				functionCode : 'SearchJd' ,
				jsonParam : data
			}
			var $promise = $http.post('data/JobDesc.php',jsonRequest);
			return $promise;
		},
		
		//Job RR Services-------------------------------------------------------------------------
		LoadDepartmentSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadDepartment' ,
				jsonParam : {}
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		LoadPositionRRSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadPositionRR' ,
				jsonParam : {}
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		LoadStatusSrvc : function()
		{
			var jsonRequest = {
				functionCode : 'LoadStatus' ,
				jsonParam : {}
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		PositionDetailSrvc : function(data)
		{
			var jsonRequest = {
				functionCode : 'PositionDetail' ,
				jsonParam : data
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		SaveRRSrvc : function(data)
		{
			var jsonRequest = {
				functionCode : 'SaveRRPhp' ,
				jsonParam : data
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		SearchRRSrvc : function(data)
		{
			var jsonRequest = {
				functionCode : 'SearchRRPhp' ,
				jsonParam : data
			}
			var $promise = $http.post('data/CandidateSearch.php',jsonRequest);
			return $promise;
		},
		
		
	};
});