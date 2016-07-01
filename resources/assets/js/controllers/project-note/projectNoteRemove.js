angular.module('app.controllers')
    .controller('ProjectNoteRemoveController', ['$scope','$location','$routeParams','Notification','ProjectNote',
        function($scope,$location,$routeParams,Notification,ProjectNote){
            $scope.projectNote = ProjectNote.get({
                id: $routeParams.id,
                idNote: $routeParams.idNote
            },function(){
            },function(error){
                if(error.data.hasOwnProperty('error') && error.data.error){
                    Notification.error(error.data.message);
                    $location.path('/projects/dashboard');
                }
            });

        $scope.remove = function(){
            $scope.projectNote.$delete({id: $routeParams.id, idNote: $scope.projectNote.id}).then(function(){
                $location.path('/project/'+$routeParams.id+'/notes');
            });
        }
    }]);