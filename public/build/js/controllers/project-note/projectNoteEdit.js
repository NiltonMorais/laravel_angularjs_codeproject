angular.module('app.controllers')
    .controller('ProjectNoteEditController', ['$scope','$location','$routeParams','Notification','ProjectNote',
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

        $scope.save = function(){
            if($scope.form.$valid) {
                ProjectNote.update({id: $routeParams.id, idNote: $scope.projectNote.id}, $scope.projectNote, function(){
                    $location.path('/project/'+$routeParams.id+'/notes');
                });
            }
        }
    }]);