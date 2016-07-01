angular.module('app.controllers')
    .controller('ProjectTaskRemoveController', ['$scope','$location','$routeParams','Notification','ProjectTask',
        function($scope,$location,$routeParams,Notification,ProjectTask){
            $scope.projectTask = ProjectTask.get({
                id: $routeParams.id,
                idTask: $routeParams.idTask
            },function(){
            },function(error){
                if(error.data.hasOwnProperty('error') && error.data.error){
                    Notification.error(error.data.message);
                    $location.path('/projects/dashboard');
                }
            });

        $scope.remove = function(){
            $scope.projectTask.$delete({
                id: $routeParams.id,
                idTask: $scope.projectTask.id
            }).then(function(){
                $location.path('/project/'+$routeParams.id+'/tasks');
            });
        }
    }]);