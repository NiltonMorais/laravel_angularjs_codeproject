angular.module('app.controllers')
    .controller('ProjectNoteShowController', ['$scope','$location','Notification','Client',
        function($scope,$location,Notification,Client){
        $scope.clients = Client.query({},function(){
        },function(error){
            if(error.data.hasOwnProperty('error') && error.data.error){
                Notification.error(error.data.message);
                $location.path('/projects/dashboard');
            }
        });
    }]);