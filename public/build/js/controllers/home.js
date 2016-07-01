angular.module('app.controllers')
    .controller('HomeController', ['$scope','$cookies', '$timeout', '$pusher','appConfig','Project',
        function ($scope,$cookies,$timeout, $pusher,appConfig,Project) {

            $scope.projects = [];
            $scope.tasks = [];
            $scope.status = appConfig.project.status;

            $scope.getStatus = function($id) {
                for (var i = 0; i < $scope.status.length; i++) {
                    if($scope.status[i].value === $id){
                        return $scope.status[i].label;
                    }
                }
                return "";
            };

            Project.query({
                orderBy: 'created_at',
                sortedBy: 'desc'
            }, function (response) {
                $scope.projects = response.data;
            });

            /* código está bugando a listagem de projetos
            var pusher = $pusher(window.client);
            var channel = pusher.subscribe('user.' + $cookies.getObject('user').id);
            channel.bind('CodeProject\\Events\\TaskWasIncluded',
                function (data) {
                    if ($scope.tasks.length == 6) {
                        $scope.tasks.splice($scope.tasks.length - 1, 1);
                    }
                    $timeout(function () {
                        $scope.tasks.unshift(data.task);
                    }, 300);
                }
            );
            */
        }]);