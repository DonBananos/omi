'use strict';


angular.module('phpOmiApp')
.controller('MainCtrl', function ($scope, $state) {

    $scope.tabs = [
        { heading: 'Home',     route: 'home',           active: false },
        { heading: 'Search',   route: 'search.list',    active: false },
        { heading: 'Details',  route: 'search.details', active: false }
    ];

    $scope.go = function(route) {
        console.log('routing to...' + route);
        $state.go(route);
    };

    $scope.active = function(route) {
        return $state.is(route);
    };

    $scope.$on('$stateChangeSuccess', function() {
        $scope.tabs.forEach(function(tab) {
            tab.active = $scope.active(tab.route);
        });
    });

});
