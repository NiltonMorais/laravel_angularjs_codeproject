angular.module('app.controllers')
    .controller('ProjectsMemberDashboardController', ['$scope', '$location', '$routeParams', 'Project',
        function ($scope, $location, $routeParams, Project) {

            $scope.projects = {};

            Project.projectsMember({
                orderBy: 'created_at',
                sortedBy: 'desc',
                limit: 5
            }, function (response) {
                $scope.projects = response.data;
            });

            $scope.showProject = function (project) {
                $scope.project = project;
            };

        }]);