angular.module('app.directives')
    .directive('projectFileDownload',
        ['$timeout', '$window','appConfig', 'ProjectFile', function ($timeout, $window, appConfig, ProjectFile) {
            return {
                restrict: 'E',
                templateUrl: appConfig.baseUrl + '/build/views/templates/projectFileDownload.html',
                link: function (scope, element, attr) {
                    var img = element.children()[0];
                    var anchor = element.children()[1];


                    scope.icoDirDefault = 'build/images/icons/default.png';
                    scope.setSrcImg(img);

                    scope.$on('salvar-arquivo', function (event, data) {
                        $(anchor).removeClass('disabled');
                        $(anchor).text('Salvar arquivo');
                        blobUtil.base64StringToBlob(data.file).then(function(blob){
                            $(anchor).attr({
                                href: $window.URL.createObjectURL(blob, data.mime_type),
                                download: data.name
                            });
                        });

                        $timeout(function () {
                            scope.downloadFile = function () {
                            };
                            $(anchor)[0].click();
                        }, 0);
                    });
                },
                controller: ['$scope', '$element', '$attrs',
                    function ($scope, $element, $attrs) {
                        $scope.downloadFile = function () {
                            var anchor = $element.children()[0];
                            $(anchor).addClass('disabled');
                            $(anchor).text('Baixando..');
                            ProjectFile.download({id: $attrs.idProject, idFile: $attrs.idFile}, function (data) {
                                $scope.$emit('salvar-arquivo', data);
                            });

                        };

                        $scope.setSrcImg = function (img) {
                            ProjectFile.get({id: $attrs.idProject, idFile: $attrs.idFile}, function (data) {
                                var icoDir = 'build/images/icons/ico-'+data.extension+'.png';

                                $.get(icoDir, function() {
                                    $(img).attr({src: icoDir});
                                }).fail(function() {
                                    $(img).attr({src: $scope.icoDirDefault});
                                });
                            });

                        };
                    }]
            };
        }]);