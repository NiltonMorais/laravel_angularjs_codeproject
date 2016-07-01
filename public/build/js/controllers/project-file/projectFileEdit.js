angular.module('app.controllers')
    .controller('ProjectFileEditController', ['$scope', '$location','$routeParams','Notification','ProjectFile',
        function($scope, $location, $routeParams,Notification,ProjectFile){
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

        $scope.save = function(){
            if($scope.form.$valid) {
                ProjectFile.update({id: $routeParams.id, idFile: $routeParams.idFile}, $scope.projectFile, function(){
                    $location.path('/project/'+$routeParams.id+'/files');
                });
            }
        }
    }]);