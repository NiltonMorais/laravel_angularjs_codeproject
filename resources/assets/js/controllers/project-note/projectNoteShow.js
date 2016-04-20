angular.module('app.controllers')
    .controller('ProjectNoteShowController', ['$scope', 'Client', function($scope, Client){
        $scope.clients = Client.query();
    }]);