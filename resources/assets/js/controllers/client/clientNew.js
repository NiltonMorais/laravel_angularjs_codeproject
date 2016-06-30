angular.module('app.controllers')
    .controller('ClientNewController', ['$scope', '$location','Client', function($scope, $location, Client){
        $scope.client = new Client();

        $scope.error = {
            message: '',
            error: false
        };

        $scope.save = function(){
            if($scope.form.$valid) {
                $scope.client.$save().then(function(){
                    $location.path('/clients');
                },function(error){
                    if(error.data.hasOwnProperty('error') && error.data.error){
                        $scope.error = {
                            error: true,
                            message: error.data.message
                        };
                    }
                });
            }
        }
    }]);