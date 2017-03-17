app.controller('CandidateCtrl', ['$scope' ,'$timeout' ,'$window','$http', 'sessionService', '$location', '$filter','FileUploader',
	function ($scope , $timeout, $window, $http, sessionService, $location, $filter,  FileUploader) 
	{
		$scope.flushMessages = function(){
			$scope.Errmsg = '';
			$scope.insertmsg = '';
			$scope.errorMsg = '';
			$scope.insertmsg = '';
		}
		$scope.htmlcontent = $scope.orightml;//used for text-editor
		$scope.disabled = false;
		$scope.searchcandidateform = true;
		$scope.AddCandidateBtn = true;
		$scope.searchCadiBtn = true;
		$scope.getQualificationList = function(){
			var QualificationDtls = sessionService.getQualificationListSrvc();
			QualificationDtls.then(function(msg){
				if (msg.data.result == 'success') {
					$scope.ArrQualificationListDtls = angular.copy(msg.data.addndata);
					$scope.ArrQualificationList = msg.data.addndata;
				} 
				else{
					$scope.QualificationErr = msg.data.message;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});	
		}
		$scope.getQualificationList(); 
		
		$scope.getCertificationList = function(){
			var CertificationDtls = sessionService.getCertificationListSrvc();
			CertificationDtls.then(function(msg){
				if (msg.data.result == 'success') {
					$scope.ArrCertificationListDtls = msg.data.addndata;
				} 
				else{
					$scope.CertificationErr = msg.data.message;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});	
		}
		$scope.getCertificationList(); 
		
		
		$scope.getSkillList = function(){
		var SkillListDtls = sessionService.getSkillListSrvc();
			SkillListDtls.then(function(msg){
				if (msg.data.result == 'success') {
					$scope.ArrSkillListDtls = angular.copy(msg.data.addndata);
					$scope.ArrSkillList = msg.data.addndata;
				} 
				else{
					$scope.SkillNameErr = msg.data.message;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});	
		}
		$scope.getSkillList();
	
		$scope.getNonTechSkillList = function(){
		var NonTechSkillListDtls = sessionService.getNonTechSkillListSrvc();
			NonTechSkillListDtls.then(function(msg){
				if (msg.data.result == 'success') {
					$scope.ArrNonTechSkillListDtls = msg.data.addndata;
				} 
				else{
					$scope.NontechskillErr = msg.data.message;
				}	
				$scope.loading = false;
				$timeout($scope.flushMessages,3000);
			});	
		}
		$scope.getNonTechSkillList();
		
		$scope.getResumeFileType = function(){
			$scope.loading = true;
			var ResumeFileDtls = sessionService.getResumeFileTypeSrvc();
			ResumeFileDtls.then(function(msg){
					if (msg.data.result == 'success') {
						$scope.attchFileType = msg.data.addndata;
						$scope.loading = true;
					} 
					else{
						$scope.error = data.message;
						$scope.loading = true;
					}	
					$scope.loading = false;
					$timeout($scope.flushMessages,3000);
				});	
		}
		$scope.getResumeFileType();
	
	$scope.UploadResume = function(CandidateInfo){
		$scope.loading = true;
		$scope.CandiInfoDtls = "";
		$scope.CandiInfoDtls = CandidateInfo;
		$scope.uploader.uploadAll();
		if($scope.Resumeflag == 0 || $scope.Resumeflag == 1)
		{
			$scope.uploader.uploadAll();
		}
		if($scope.Resumeflag == 2)
		{
			$scope.updateCandidate();
		}
		/* if($scope.flag == 1)
		{
			$scope.SaveCandidate();
		}
		if($scope.flag == 2)
		{
			$scope.UpdateCandidate();
		} */
	}
	
	$scope.$watch('uploader.progress', function() {
		if($scope.uploader.progress == 100){
			$timeout($scope.checkForFiles,2000);
		}
	});
	
	$scope.checkForFiles = function(){
		var fileObj = {};
		fileObj.folderName = 'Attachment';
		fileObj.queueArray = $scope.queueArray;
		$http.post('data/index.php/checkForFiles',fileObj).success(function(data){
			if(data.result == 'success'){
				if($scope.flag == 1)
				{
					$scope.SaveCandidate();
				}
				if($scope.flag == 2)
				{
					$scope.UpdateCandidate();
				}
			}
			else{
				$scope.checkForFiles();
			}
		});
	}; 
	var uploader = $scope.uploader = new FileUploader({
		url: 'UploadFunc/upload.php',
		queueLimit: 100
	});
	uploader.filters.push({
		name: 'imageFilter',
		fn: function(item /*{File|FileLikeObject}*/, options) {
			var type = '|' + item.name.slice(item.name.lastIndexOf('.') + 1) + '|';
			if(angular.lowercase($scope.attchFileType).indexOf(angular.lowercase(type)) == -1){
				$scope.Errmsg = "Not a valid file type. Please select another file";
				$timeout($scope.flushMessages,4000);
			}
			return $scope.attchFileType.indexOf(type) !== -1;
		}
	});
	
	$scope.fileChanged =function(element,index,index1){
		$scope.file = element.files[0];
		$scope.Candi.Resume = $scope.file.name;
		$scope.queueArray = [];
		$scope.queueArray.push($scope.Candi.Resume);
		$scope.index = index;
	};
	$scope.SaveCandidate = function()
	{
			var data = {};
			data = $scope.CandiInfoDtls;
			$scope.Regdatetime = new Date();
			$scope.Regdatetime = $filter('date')($scope.Regdatetime, "h:mm:ss");
			data.RegDate = data.RegDate+ ' ' +$scope.Regdatetime;
			var CandiDtls = sessionService.SaveCandidateSrvc(data);
			CandiDtls.then(function(msg)
			{
				var type= typeof msg.data;
				if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
				{
					$scope.$parent.logout();	
				}
				else
				if (msg.data.result == 'success') {
					$scope.insertmsg = msg.data.message;
					$scope.Candi = {};
					$scope.Candi.RegDate = new Date();
					$scope.Candi.RegDate = $filter('date')($scope.Candi.RegDate, "yyyy-MM-dd");
					$scope.getQualificationList(); 
					$scope.getCertificationList(); 
					$scope.getSkillList();
					$scope.getNonTechSkillList();
					$scope.Candi.Role = '';
					$scope.Candi.Responsibility = '';
				} 
				else{
					$scope.Errmsg = msg.data.message;
				}
				$timeout($scope.flushMessages,3000);
			});
	}
		$scope.showCandiList = function()
		{
			$scope.searchCadiBtn = true;
			$scope.AddCandidateBtn = true;
			$scope.CandiSearch = false;
			$scope.CandiEdit = false;
			$scope.CandiAdd = false;
			$scope.Candiview = false;
			$scope.searchCandiItem = false;
			$scope.addcandidateform = false;
			$scope.viewcandidateform = false;
			$scope.searchcandidateform = true;
			$scope.SearchCandidate($scope.Candidatedata);
		}
		$scope.Candidatedata = {};
		$scope.SearchCandidate = function(CandidateDtls)
			{
				$scope.CandiTableDiv = true;
				$scope.Candidatedata = CandidateDtls;
				var SearchCandiReq = sessionService.SearchCandiSrvc(CandidateDtls);
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
					}
					else
					{
						$scope.errorMsg = msg.data.message;
						$scope.ArrCandiSearch =[];
					}
					$timeout($scope.flushMessages,3000);
				});
			}
		$scope.searchDtls = {};
		$scope.SearchCandidate($scope.searchDtls);
		$scope.cancelCandiBtn = function()
		{
			$scope.addcandidateform = false;
			$scope.viewcandidateform = false;
			$scope.CandiSearch = false;
			$scope.CandiAdd = false;
			$scope.CandiEdit = false;
			$scope.Candiview = false;
			$scope.searchcandidateform = true;
			$scope.AddCandidateBtn = true;
			
			if($scope.searchCandiItem == true)
			{
				$scope.searchCadiBtn = false;
				$scope.searchCandiItem = true;
				$scope.CandiSearch = true;
			}
			else
			{
				$scope.searchCadiBtn = true;
				$scope.searchCandiItem = false;
				$scope.CandiSearch = false;
			}
			$scope.SearchCandidate($scope.Candidatedata);
		}
		$scope.open3 = function($event) 
		{
			$event.preventDefault();
			$event.stopPropagation();
			$scope.opened3 = true;
		};
		$scope.clearsearchCandiBtn = function() 
		{
			$scope.candidate = {};
			$scope.getQualificationList(); 
			$scope.getSkillList();
			$scope.ArrCandiSearch = '';
			$scope.SearchCandidate($scope.searchDtls);
		}; 
		
		$scope.EditCandidate = function(Candidate)
		{
			$scope.savecandidatebtn = false;
			$scope.viewcandidateform = false;
			$scope.flag = 2;
			$scope.addcandidateform = true;
			$scope.Updatecandidatebtn = true;
			$scope.idCandidate = Candidate.idCandidate;
			$scope.CandidateDtls  = Candidate;	
			$scope.Candi = {};
			$scope.Candi.RegDate = new Date();
			$scope.Candi.RegDate = $filter('date')($scope.Candi.RegDate, "yyyy-MM-dd");
			$scope.Candi.CandidateName = $scope.CandidateDtls.CandidateName;
			$scope.Candi.Resume = $scope.CandidateDtls.Resume;
			$scope.Candi.CurrentCompany = $scope.CandidateDtls.CurrentCompany;
			$scope.Candi.CurrentDesignation = $scope.CandidateDtls.CurrentDesignation;
			$scope.Candi.CurrentSalary = $scope.CandidateDtls.CurrentSalary;
			$scope.Candi.NoticePeriod = $scope.CandidateDtls.NoticePeriod;
			$scope.Candi.Role = $scope.CandidateDtls.Role;
			$scope.Candi.Responsibility = $scope.CandidateDtls.Responsibility;
			$scope.Candi.TotalExp = $scope.CandidateDtls.TotalExp;
			$scope.Candi.RelevantExp = $scope.CandidateDtls.RelevantExp;
			$scope.Candi.MobNo = $scope.CandidateDtls.MobNo;
			$scope.Candi.AltContactNo = $scope.CandidateDtls.AltContactNo;
			$scope.Candi.EmailId = $scope.CandidateDtls.EmailId;
			$scope.Candi.Address = $scope.CandidateDtls.Address;
			$scope.Candi.QualiName = $scope.CandidateDtls.QualiArr;
			$scope.Candi.SkillName = $scope.CandidateDtls.TechSkillArr;
			$scope.Candi.Certification = $scope.CandidateDtls.CertificationArr;
			$scope.Candi.NonTechSkill = $scope.CandidateDtls.NonTechSkillArr;
			for(var n=0;n < $scope.ArrQualificationList.length; n++)
			{
				$scope.ArrQualificationList[n].ticked = false;	
			}
			for(var m=0;m<$scope.ArrQualificationList.length;m++)
			{
				for(var n=0;n<$scope.CandidateDtls.QualiArr.length;n++)
				{
					if($scope.ArrQualificationList[m].QualiName == $scope.CandidateDtls.QualiArr[n]){
						$scope.ArrQualificationList[m].ticked = true;
					}
				}
			} 
			for(var n=0;n < $scope.ArrCertificationListDtls.length; n++)
			{
				$scope.ArrCertificationListDtls[n].ticked = false;	
			}
			for(var m=0;m<$scope.ArrCertificationListDtls.length;m++)
			{
				for(var n=0;n<$scope.CandidateDtls.CertificationArr.length;n++)
				{
					if($scope.ArrCertificationListDtls[m].Certification == $scope.CandidateDtls.CertificationArr[n]){
						$scope.ArrCertificationListDtls[m].ticked = true;
					}
				}
			} 
			
			for(var n=0;n < $scope.ArrSkillList.length; n++)
			{
				$scope.ArrSkillList[n].ticked = false;	
			}
			for(var m=0;m<$scope.ArrSkillList.length;m++)
			{
				for(var n=0;n<$scope.CandidateDtls.TechSkillArr.length;n++)
				{
					if($scope.ArrSkillList[m].SkillName == $scope.CandidateDtls.TechSkillArr[n]){
						$scope.ArrSkillList[m].ticked = true;
					}
				}
			} 
			for(var n=0;n < $scope.ArrNonTechSkillListDtls.length; n++)
			{
				$scope.ArrNonTechSkillListDtls[n].ticked = false;	
			}
			for(var m=0;m<$scope.ArrNonTechSkillListDtls.length;m++)
			{
				for(var n=0;n<$scope.CandidateDtls.NonTechSkillArr.length;n++)
				{
					if($scope.ArrNonTechSkillListDtls[m].NonTechSkill == $scope.CandidateDtls.NonTechSkillArr[n]){
						$scope.ArrNonTechSkillListDtls[m].ticked = true;
					}
				}
			} 
			$scope.AddCandiLabel = false;
			$scope.EditCandiLabel = true;
			
			
		}
		$scope.ShowSearchCandidate = function() 
		{
			$scope.addcandidateform = false;
			$scope.viewcandidateform = false;
			$scope.searchcandidateform = true;
			$scope.CandiSearch = true;
			$scope.AddCandidateBtn = true;
			$scope.CandiAdd = false;
			$scope.searchCadiBtn = false;
			$scope.Candiview = false;
			$scope.searchCaniBtn = false;
			$scope.searchCandiItem = true;
			$scope.getSkillList();
			$scope.getQualificationList(); 
			$scope.SearchCandidate($scope.Candidatedata);
		}; 
		
		$scope.AddCandidate = function() 
		{
			$scope.Candi = {};
			$scope.flag = 1;
			$scope.Candi.RegDate = new Date();
			$scope.Candi.RegDate = $filter('date')($scope.Candi.RegDate, "yyyy-MM-dd");
			$scope.minDate = new Date();
			$scope.Updatecandidatebtn = false;
			$scope.searchcandidateform = false;
			$scope.addcandidateform = true;
			$scope.savecandidatebtn = true;
			$scope.CandiSearch = false;
			$scope.AddCandidateBtn = false;
			$scope.Candiview = false;
			$scope.CandiAdd = true;
			$scope.searchCadiBtn = true;
			$scope.AddCandiLabel = true;
			$scope.EditCandiLabel = false;
			$scope.getSkillList();
			$scope.getQualificationList(); 
			$scope.getNonTechSkillList();
			$scope.getCertificationList(); 
		}; 
		$scope.UpdateCandidate = function()
		{
			var Candi = {};
			Candi = $scope.CandiInfoDtls;
			Candi.RegDate = $filter('date')(Candi.RegDate, 'dd-MM-yyyy');
			Candi.idCandidate = $scope.idCandidate;
			var CandiDtls = sessionService.updateCandidateSrvc(Candi);
				CandiDtls.then(function(msg)
				{
					var type= typeof msg.data;
					if(type == 'string' && msg.data.replace(/\s/g, '') == 'SessionTimeOut')	
					{
						$scope.$parent.logout();	
					}
					else
					if (msg.data.result == 'success') {
						$scope.loading = false;
						$scope.Candi = {};
						$scope.Candi.RegDate = new Date();
						$scope.Candi.RegDate = $filter('date')($scope.Candi.RegDate, "yyyy-MM-dd"); 
						$scope.Candi = {};
						$scope.cancelCandiBtn();
						$scope.insertmsg = msg.data.message;
						$scope.Candi.Role = '';
						$scope.Candi.Responsibility = '';
					}  
					else{
						$scope.Errmsg = msg.data.message;
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
		}
		$scope.candiview = function(obj)
			{
				$scope.Candi = obj;
				$scope.searchcandidateform = false;
				$scope.viewcandidateform = true;
				$scope.CandiSearch = false;
				$scope.Candiview = true;
				$scope.AddCandidateBtn = true;
				$scope.searchCadiBtn = true;
				
			} 
		$scope.getModalData = function(Role,Responsibility)
		{
			$scope.Role = Role;
			$scope.Responsibility = Responsibility;
		} 
		var orderBy = $filter('orderBy');
		$scope.order = function(predicate, reverse) {
		$scope.ArrCandiSearch = orderBy($scope.ArrCandiSearch, predicate, reverse);
		$scope.currentPage = 1;
		}; 
	}
]);
