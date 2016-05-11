angular.module('app.controllers')
    .controller('ProjectFileNewController', [
        '$scope', '$routeParams', '$location', 'Upload',
        function ($scope, $routeParams, $location, Upload) {
            $scope.projectFile = {
                project_id: $routeParams.id
            };

            $scope.save = function () {
                if ($scope.form.$valid) {
                    Upload.upload({
                        url: 'upload/url',
                        data: {
                            file: $scope.projectFile.file,
                            name: $scope.projectFile.name,
                            description: $scope.projectFile.description,
                        }
                    }).then(function (resp) {
                        console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
                        $location.path('/project/' + $routeParams.id + '/files');
                    }, function (resp) {
                        console.log('Error status: ' + resp.status);
                    }, function (evt) {
                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
                    });

                    /*$scope.projectFile.$save({id: $routeParams.id}).then(function () {
                     $location.path('/project/'+$routeParams.id+'/files');
                     })*/
                }
            }
        }]);