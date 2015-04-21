  'use strict';


angular.module('phpOmiApp')
  .controller('RegisterCtrl', function ($scope, $http) {

    var username = $scope.username;
    var email = $scope.email;
    var password =  $scope.password;
    var rePassword = $scope.rePassword;
    var acceptTaC = $scope.acceptTaC;


$scope.url = 'http://localhost\:7000/users'; // The url of our search
    
  // The function that will be executed on button click (ng-click="search()")
  $scope.register = function() {
    

    var phpPOST = $http({
    method: 'POST',
    url: $scope.url,
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    transformRequest: function(obj) {
        var str = [];
        for(var p in obj)
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        return str.join("&");
    },
    data: {username: $scope.username, password: $scope.password}
    }).success(function () {console.log('Posted successfully');});
    };
        })
  .controller('ListCtrl', function ($scope, $http){
      $http.get($scope.url).success(function(data) {
      $scope.users = data;
  });
});
