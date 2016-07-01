angular.module('app.controllers')
    .controller('ProjectTaskEditController', [
        '$scope', '$routeParams', '$location','Notification','appConfig','ProjectTask',
        function($scope,$routeParams,$location,Notification,appConfig,ProjectTask){
            $scope.projectTask = new ProjectTask.get({
                id: $routeParams.id,
                idTask: $routeParams.idTask
            },function(){
            },function(error){
                if(error.data.hasOwnProperty('error') && error.data.error){
                    Notification.error(error.data.message);
                    $location.path('/projects/dashboard');
                }
            });

            $scope.status = appConfig.projectTask.status;

            $scope.start_date = {
                status: {
                    opened: false
                }
            };

            $scope.due_date = {
                status: {
                    opened: false
                }
            };

            $scope.openStartDatePicker = function($event){
                $scope.start_date.status.opened = true;
            };

            $scope.openDueDatePicker = function($event){
                $scope.due_date.status.opened = true;
            };

            $scope.save = function(){
                if($scope.form.$valid) {
                    ProjectTask.update({
                        id: $routeParams.id,
                        idTask: $scope.projectTask.id
                    },$scope.projectTask, function () {
                        $location.path('/project/'+$routeParams.id+'/tasks');
                    })
                }
            }
    }]);