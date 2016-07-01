angular.module('app.controllers')
    .controller('ProjectMemberRemoveController',
        ['$scope', '$location', '$routeParams','Notification','ProjectMember',
        function($scope, $location,$routeParams,Notification,ProjectMember){
            $scope.projectMember = ProjectMember.get({
                id: $routeParams.id,
                idProjectMember: $routeParams.idProjectMember
            },function(){

            },function(error){
                if(error.data.hasOwnProperty('error') && error.data.error){
                    Notification.error(error.data.message);
                    $location.path('/projects/dashboard');
                }
            });

        $scope.remove = function(){
            $scope.projectMember.$delete({
                id: $routeParams.id,
                idProjectMember: $routeParams.idProjectMember
            }).then(function(){
                $location.path('/project/'+$routeParams.id+'/members');
            },function(error){
                if(error.data.hasOwnProperty('error') && error.data.error){
                    Notification.error(error.data.message);
                    $location.path('/projects/dashboard');
                }
            });
        }
    }]);