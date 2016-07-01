angular.module('app.controllers')
    .controller('ProjectNoteListController', ['$scope','$routeParams','$location','Notification','ProjectNote',
        function($scope,$routeParams,$location,Notification,ProjectNote){
        $scope.projectNotes = ProjectNote.query({id: $routeParams.id},function(){
        },function(error){
            if(error.data.hasOwnProperty('error') && error.data.error){
                Notification.error(error.data.message);
                $location.path('/projects/dashboard');
            }
        });
    }]);