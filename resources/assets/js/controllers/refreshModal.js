angular.module('app.controllers')
    .controller('RefreshModalController',
        ['$rootScope', '$scope', '$location', '$modalInstance','authService', 'OAuth', 'OAuthToken', 'User',
            function ($rootScope, $scope, $location, $modelInstance, authService, OAuth, OAuthToken, User) {

                $scope.$on('event::auth-loginConfirmed', function () {
                    $rootScope.loginModalOpened = false;
                    $modelInstance.close();
                });

                $scope.$on('$routeChangeStart', function () {
                    $rootScope.loginModalOpened = false;
                    $modelInstance.dismiss('cancel');
                });

                $scope.$on('event::auth-loginCancelled', function () {
                    OAuthToken.removeToken();
                });

                $scope.cancel = function () {
                    authService.loginCancelled();
                    $location.path('login');
                };

                OAuth.getRefreshToken().then(function () {
                    authService.loginConfirmed();
                    $modelInstance.close();
                }, function (data) {
                    $scope.cancel();
                });

            }]);