'use strict';


angular.module('phpOmiApp')
        .controller('SearchCtrl', function ($scope, SearchService, $log, $state, $http) {
            console.log("In searchCtrl");
            $scope.movieData = SearchService.movieData;

            $scope.thePromise = null;

            $scope.search = function () {
                $scope.thePromise = SearchService.searchByTitle();
                console.log('Searcing for ' + JSON.stringify($scope.movieData));
            };

            $scope.viewDetails = function (movieId) {
                $scope.thePromise = SearchService.searchById(movieId)
                        .success(function () {
                            $state.go('search.details');
                        });
            };

            $scope.addMovie = function (movieId) {
                $scope.thePromise = SearchService.searchById(movieId)
                        .success(function () {
                            // add json data to php
                            // Simple POST request example (passing data) :
                            $http.post('', {msg: 'hello word!'}).
                                    success(function (data, status, headers, config) {
                                        // this callback will be called asynchronously
                                        // when the response is available
                                    }).
                                    error(function (data, status, headers, config) {
                                        // called asynchronously if an error occurs
                                        // or server returns response with an error status.
                                    });
                            alert("test");
                        });
            };

            $scope.reset = function () {
                SearchService.reset();
            };

            // var rCounter = 0;

            // $scope.recommendAMovie = function() {
            //     var movie = recommendedMovies[rCounter];
            //     rCounter = (rCounter + 1) % recommendedMovies.length;
            //     SearchService.movieData.searchInput.title = movie.title;
            //     $scope.search();
            // };
        });
