angular.module('app.controllers')
    .controller('ProjectFileListController', ['$scope', '$routeParams', '$location','Notification','ProjectFile',
        function($scope, $routeParams,$location,Notification, ProjectFile){
        $scope.projectFiles = ProjectFile.query({id: $routeParams.id},function(){

        },function(error){
            if(error.data.hasOwnProperty('error') && error.data.error){
                Notification.error(error.data.message);
                $location.path('/projects/dashboard');
            }
        });
    }]);