angular.module('app.controllers')
.controller('LoginModalController',
    ['$rootScope', '$scope','$location','$cookies','$modalInstance','authService','OAuth','OAuthToken','User',
    function($rootScope, $scope, $location, $cookies, $modelInstance, authService, OAuth, OAuthToken, User){
    $scope.user = {
        username: '',
        password: ''
    };

    $scope.error = {
        message: '',
        error: false
    };

    $scope.$on('$routeChangeStart',function(){
        $rootScope.loginModalOpened = false;
        $modelInstance.close();
    });

        $scope.$on('event::auth-loginConfirmed', function(){
            $rootScope.loginModalOpened = false;
            $modelInstance.close();
        });

        $scope.$on('event::auth-loginCancelled', function(){
            OAuthToken.removeToken();
        });

    $scope.login = function(){
        if($scope.form.$valid) {
            OAuth.getAccessToken($scope.user).then(function () {
                User.authenticated({}, {}, function(data){
                    $cookies.putObject('user', data);
                    authService.loginConfirmed();
                    $modelInstance.close();
                })
            }, function (data) {
                $scope.error.error = true;
                $scope.error.message = data.data.error_description;
            });
        }
    };

    $scope.cancel = function(){
        authService.loginCancelled();
        $location.path('login');
    };
}]);