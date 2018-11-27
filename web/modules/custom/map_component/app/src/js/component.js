angular.module('map-component',[
    'templates-map-component',
    'uiGmapgoogle-maps'
])
.config(['uiGmapGoogleMapApiProvider','$logProvider',function(uiGmapGoogleMapApiProvider,$logProvider) {
    var app = document.querySelector('*[ng-app]'),
        apiKeyAttr = app ? app.attributes.getNamedItem('api-key') : undefined;
    uiGmapGoogleMapApiProvider.configure({
        key: apiKeyAttr.value,
        v: '3.27'
    });
    var debug = window.location.hash && window.location.hash.match(/^#.*#debug/);
    $logProvider.debugEnabled(debug);
}])
.directive('mapComponent',['$log','uiGmapGoogleMapApi',function($log,uiGmapGoogleMapApi) {
    return {
        restrict: 'C',
        scope: {
            markerSources: '@'
        },
        template: '<ui-gmap-google-map ng-if="map" center="map.center" zoom="map.zoom" options="map.options" events="map.events">'+
        '<map-component-markers ng-repeat="source in sources" source="{{source}}"></map-component-markers>'+
        '</ui-gmap-google-map>',
        link: function($scope) {
            $scope.sources = $scope.markerSources.split(',');
            uiGmapGoogleMapApi.then(function(maps) {
                var api = maps;
                $scope.map = {
                    center: {
                        latitude: 43.807507, longitude: -74.330217
                        //latitude: 38.8402805, longitude: -97.61142369999999
                    },
                    zoom: 6,
                    options: {
                        mapTypeId: maps.MapTypeId.TERRAIN,
                        mapTypeControl: true,
                        streetViewControl: false,
                        zoomControl: true
                    }
                };
            });
        }
    };
}])
.directive('mapComponentMarkers',['$log','$http','$compile','$timeout',function($log,$http,$compile,$timeout){
    return {
        restrict: 'E',
        scope: {
            source: '@'
        },
        template: '<ui-gmap-markers models="results.markers" idKey="\'id\'" coords="\'self\'" icon="\'icon\'" options="\'markerOpts\'" type="type" control="mapControl" events="markerEvents"></ui-gmap-markers>',
        link: function($scope) {
            var infoWindow;
            $scope.results = {
                markers: []
            };
            $scope.mapControl = {};
            /*
            doCluster="doCluster" clusterOptions="clusterOptions"
            $scope.doCluster = true;*/
            $scope.markerEvents = {
                'click': function(m) {
                    $log.debug('click',m);
                    $scope.$apply(function(){
                        $scope.infoMarker = m.model;
                        var html = '<div id="infoWindow" class="ng-cloak" ng-cloak><div class="marker-info">';
                        html += '<h3 ng-cloak><a ng-href="{{infoMarker._self}}">{{infoMarker.title}}</a></h3>';
                        //html += '<h4>[{{infoMarker.latitude | number}}, {{infoMarker.longitude | number}}]</h4>';
                        html += '<p>{{infoMarker.description}}</p>';
                        html += '</div></div>';
                        var compiled = $compile(html)($scope);
                        if(!infoWindow) {
                            infoWindow = new google.maps.InfoWindow({
                                maxWidth: 500,
                                content: 'info window contents'
                            });
                        }
                        // using timeout avoids the InfoWindow flashing while angular compiles its contents
                        // ensures the compiled dom elements are all resolved properly.
                        $timeout(function(){
                            infoWindow.setContent(compiled.html());
                            infoWindow.setPosition(m.getPosition());
                            infoWindow.open(m.map);
                        });
                    });
                }
            };
            $http.get('/api/map_component/markers/'+$scope.source).then(function(response){
                var icon = response.data.icon,
                    list = response.data.list;
                $scope.type = response.data.type;
                if(icon) {
                    list.forEach(function(m) {
                        if(!m.icon) {
                            m.icon = icon;
                        }
                    });
                }
                $scope.results.markers = list;
            });
        }
    };
}]);
