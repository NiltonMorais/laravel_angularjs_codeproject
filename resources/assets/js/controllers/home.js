angular.module('app.controllers')
    .controller('HomeController', ['$scope','Project', function($scope, Project){
        $scope.projects = {};

        Project.query({
            orderBy: 'created_at',
            sortedBy: 'desc'
        }, function (response) {
            $scope.projects = response.data;
        });
    }]);