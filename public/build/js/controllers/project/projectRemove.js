angular.module('app.controllers')
    .controller('ProjectRemoveController', ['$scope', '$location', '$routeParams','Notification','Project',
        function($scope, $location, $routeParams, Notification,Project){
            $scope.project = Project.get({id: $routeParams.id},function(){

            },function(error){
                if(error.data.hasOwnProperty('error') && error.data.error){
                    Notification.error(error.data.message);
                    $location.path('/projects/dashboard');
                }
            });

        $scope.remove = function(){
            $scope.project.$delete({id: $scope.project.id}).then(function(){
                $location.path('/projects');
            },function(error){
                if(error.data.hasOwnProperty('error') && error.data.error){
                    Notification.error(error.data.message);
                }
            });
        }
    }]);