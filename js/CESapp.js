'use strict';
var app = angular.module('CESapp',['ngRoute','multi-select','ui.bootstrap','angularFileUpload','textAngular','scrollable-table']);

app.config(['$routeProvider', 
	function($routeProvider) {
		$routeProvider.
		when('/',{templateUrl: 'partials/Login.html' , controller: 'LoginCtrl'}).
		when('/AddUser',{templateUrl: 'partials/AddUser.html' , controller: 'UserCtrl'}).
		when('/RoleList',{templateUrl: 'partials/RoleList.html' , controller: 'RoleCtrl'}).
		when('/ChangePassword',{templateUrl: 'partials/ChangePassword.html' , controller: 'UserCtrl'}).
		when('/UserList',{templateUrl: 'partials/UserRole.html' , controller: 'UserCtrl'}).
		when('/Element',{templateUrl: 'partials/element.html' , controller: 'AdminCtrl'}).
		when('/ConfigureList',{templateUrl: 'partials/Configure.html' , controller: 'AdminCtrl'}).
		when('/candidates',{templateUrl: 'partials/CandidateSearch.html' , controller: 'CandidateCtrl'}).
		when('/JobDescription',{templateUrl: 'partials/JobDescription.html' , controller: 'JobDescCtrl'}).
		when('/RecruitmentRequest',{templateUrl: 'partials/JobRecruitmentReqabc.html' , controller: 'JobDescCtrl'}).
		when('/Dashboard',{templateUrl: 'partials/Dashboard.html' , controller: 'DashboardCtrl'}).
		when('/RRSchedule',{templateUrl: 'partials/RRSchedule.html' , controller: 'PositionCtrl'}).
		when('/InterviewerInterface',{templateUrl: 'partials/InterviewerInterface.html' , controller: 'InteractionInterface'}).
		when('/Alerts',{templateUrl: 'partials/Alerts.html' , controller: 'PositionCtrl'}).
		otherwise({redirectTo: '/'})
}]);
var weekday = new Array(7);
	weekday[0]=  "Sun";
	weekday[1] = "Mon";
	weekday[2] = "Tue";
	weekday[3] = "Wed";
	weekday[4] = "Thu";
	weekday[5] = "Fri";
	weekday[6] = "Sat";
	
app.directive("mycurrenttime", function(dateFilter){
    return function(scope, element, attrs){
        var format;
        
        scope.$watch(attrs.mycurrenttime, function(value) {
            format = value;/* 'dd/MM/yyyy HH:mm:ss'; */
            updateTime();
        });
        
        function updateTime(){
            var dt = dateFilter(new Date(), format);
            element.text(dt);
        }
        
        function updateLater() {
            setTimeout(function() {
              updateTime(); // update DOM
              updateLater(); // schedule another update
            }, 1000);
        }
        updateLater();
    }
	
});
app.directive('countdown', [
    'Util', '$interval', function(Util, $interval) {
      return {
        restrict: 'A',
        scope: {
          date: '@'
        },
        link: function(scope, element) {
          var future;
          future = new Date(scope.date);
          $interval(function() {
            var diff;
            diff = Math.floor((future.getTime() - new Date().getTime()) / 1000);
            return element.text(Util.dhms(diff));
          }, 1000);
        }
      };
    }
  ])
app.factory('Util', [
    function() {
      return {
        dhms: function(t) {
          var days, hours, minutes, seconds;
          days = Math.floor(t / 86400);
          t -= days * 86400;
          hours = Math.floor(t / 3600) % 24;
          t -= hours * 3600;
          minutes = Math.floor(t / 60) % 60;
          t -= minutes * 60;
          seconds = t % 60;
		  
			if(days == 0 && hours == 0)
				return [ minutes + 'm', seconds + 's'].join(' ');
			else if(days == 0)
				return [ hours + 'h', minutes + 'm', seconds + 's'].join(' ');
			else
				return [days + 'd', hours + 'h', minutes + 'm', seconds + 's'].join(' ');
        }
      };
    }
  ]);