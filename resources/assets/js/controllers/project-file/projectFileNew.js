angular.module('app.controllers')
    .controller('ProjectFileNewController', [
        '$scope', '$routeParams', '$location', 'appConfig','Url', 'Upload',
        function ($scope, $routeParams, $location, appConfig, Url, Upload) {

            $scope.save = function () {
                if ($scope.form.$valid) {
                    var url = appConfig.baseUrl + Url.getUrlFromUrlSymbol(appConfig.urls.projectFile, {
                            id: $routeParams.id,
                            idFile: '',
                        });
                    Upload.upload({
                        url: url,
                        fields: {
                            name: $scope.projectFile.name,
                            description: $scope.projectFile.description,
                            project_id: $routeParams.id
                        },
                        file: $scope.projectFile.file
                    }).then(function (data, status, headers, config) {
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