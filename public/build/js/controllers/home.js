angular.module('app.controllers')
    .controller('HomeController', ['$scope','$cookies', '$timeout', '$pusher','$filter','appConfig','Project',
        function ($scope,$cookies,$timeout, $pusher,$filter,appConfig,Project) {

            $scope.projects = [];
            $scope.notifications = [];
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

            var pusher = $pusher(window.client);
            var channel = pusher.subscribe('user.' + $cookies.getObject('user').id);
            channel.bind('CodeProject\\Events\\TaskWasIncluded',
                function (data) {
                    var start_date = data.task.start_date;
                    var msgm = "Tarefa '"+data.task.name+"' foi incluída. ";
                    var msgm2 = "";

                    if(start_date != null){
                        msgm2 = "Data de início: "+$filter('dateBr')(start_date);
                    }

                    $scope.insertNotificationInPanel({msgm: msgm, msgm2: msgm2});
                }
            );
            channel.bind('CodeProject\\Events\\TaskChanged',
                function (data) {
                    var msgm = "Tarefa '"+data.task.name+"' foi alterada";

                    if(data.task.status == 2){
                        msgm = "Tarefa '"+data.task.name+"' foi concluída";
                    }
                    $scope.insertNotificationInPanel({msgm: msgm});
                }
            );

            $scope.insertNotificationInPanel = function($msgm){
                if ($scope.notifications.length == 6) {
                    $scope.notifications.splice($scope.notifications.length - 1, 1);
                }
                $timeout(function () {
                    $scope.notifications.unshift($msgm);
                }, 300);
            };

        }]);