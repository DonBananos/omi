'use strict';


angular
  .module('phpOmiApp', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ui.router',
    'ui.router.stateHelper',
    'ngSanitize',
    'ngTouch',
    'ui.bootstrap',
    'ct.ui.router.extras',
    'cgBusy'
  ])
  .config(function($stateProvider, stateHelperProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/home');

    $stateProvider
    .state({
        name: 'home',
        url: '/home',
        templateUrl: './app/views/home.html'
    });

    // setup a default child route for search
    $urlRouterProvider.when('/search', '/search/list');

    stateHelperProvider
    .setNestedState({
        name: 'search',
        url: '/search',
        abstract: true,
        templateUrl: './app/views/search.html',
        controller: 'SearchCtrl',
        deepStateRedirect: true,
        sticky: true,
        children: [
            {
                name: 'list',
                url: '/list',
                views: {
                    'content': {
                        templateUrl: './app/views/list.html'
                    }
                }
            },
            {
                name: 'details',
                url: '/details',
                views: {
                    'content': {
                        templateUrl: './app/views/details.html'
                    }
                }
            }
        ]
    });

    $stateProvider
    .state({
        name: 'about',
        url: '/about',
        templateUrl: './app/views/about.html'
    });

    $stateProvider
    .state({
        name: 'register',
        controller: 'RegisterCtrl',
        url: '/register',
        templateUrl: './app/views/register.html'
    });
});
