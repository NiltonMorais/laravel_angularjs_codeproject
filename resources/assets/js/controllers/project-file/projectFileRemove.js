angular.module('app.controllers')
    .controller('ProjectFileRemoveController', ['$scope','$location','$routeParams','Notification','ProjectFile',
        function($scope,$location,$routeParams,Notification,ProjectFile){
            $scope.projectFile = ProjectFile.get({
                id: $routeParams.id,
                idFile: $routeParams.idFile
            },function(){
            },function(error){
                if(error.data.hasOwnProperty('error') && error.data.error){
                    Notification.error(error.data.message);
                    $location.path('/projects/dashboard');
                }
            });

        $scope.remove = function(){
            $scope.projectFile.$delete({id: $routeParams.id,idFile: $scope.projectFile.id}).then(function(){
                $location.path('/project/'+$routeParams.id+'/files');
            });
        }
    }]);