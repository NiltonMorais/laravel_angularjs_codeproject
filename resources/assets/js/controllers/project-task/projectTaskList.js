angular.module('app.controllers')
    .controller('ProjectTaskListController', [
        '$scope','$routeParams','$location','Notification','appConfig', 'ProjectTask',
        function($scope,$routeParams,$location,Notification,appConfig, ProjectTask){
            $scope.projectTask = new ProjectTask();

            $scope.save = function(){
                if($scope.form.$valid){
                    $scope.projectTask.status = appConfig.projectTask.status[0].value;
                    $scope.projectTask.$save({id: $routeParams.id}).then(function(){
                        $scope.projectTask = new ProjectTask();
                        $scope.loadTask();
                    });
                }
            };

            $scope.loadTask = function(){
                $scope.projectTasks = ProjectTask.query({
                    id: $routeParams.id,
                    orderBy: 'id',
                    sortedBy: 'desc'
                },function(){
                },function(error){
                    if(error.data.hasOwnProperty('error') && error.data.error){
                        Notification.error(error.data.message);
                        $location.path('/projects/dashboard');
                    }
                });
            };

            $scope.loadTask();

    }]);