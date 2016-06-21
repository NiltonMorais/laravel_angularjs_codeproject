angular.module('app.controllers')
    .controller('HomeController', ['$scope', 'Project', '$timeout', '$pusher',
        function ($scope, Project, $timeout, $pusher) {

            $scope.projects = [];
            $scope.tasks = [];


            Project.query({
                orderBy: 'created_at',
                sortedBy: 'desc'
            }, function (response) {
                $scope.projects = response.data;
            });

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
        }]);