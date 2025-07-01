mainApp.controller('dashboard', ['$scope', 'httpHandler', '$http', '$window', function ($scope, httpHandler, $http, $window) {

    $scope.no = 1;
    $scope.itemsPerPage = 10;
    $scope.keyword = {};
    $scope.search_Method ={};
    $scope.total_count = 0;
    $scope.message = null;

    $scope.getData = function (pageno) {
        if(pageno == 0)
            $scope.no = 1;
        else
            $scope.no = (pageno*$scope.itemsPerPage) - ($scope.itemsPerPage - 1);

        $scope.total_count = 0;
        $scope.message = null;

        var params = {
            keyword: $scope.keyword,
            limit: $scope.itemsPerPage,
            offset: pageno != 0 ? pageno : 1,
        }

        $scope.loading = true;

        httpHandler.send({
            method: 'GET',
            url: urls + 'dashboard/getDataLokasi',
            params: params
        }).then(
            function successCallbacks(response) {
                $scope.loading = false;
                $scope.datalokasi = response.data.data;
                $scope.table_header = response.data.header;
                $scope.total_count = response.data.count;
                $scope.message = response.data.message;
                $scope.curPage = pageno;
            }
        );
    }

    $scope.getData(0);

    $scope.searchMethod = function(keyname, val)
    {
        $scope.keyword[keyname] = val;
        $scope.getData(1);
    }

    $scope.reset = function(is_master)
    {
        $scope.keyword = {};
        $scope.search_Method = {};
        if(is_master == "master"){
            $scope.getData(1);
        }
    }

	$scope.chartdiv = function() {

		const params = {};
		httpHandler.send({
            method: 'GET',
            url: urls + "dashboard/data",
            params: params
            
        }).then(function successCallback(response) {
            $scope.data = response.data.data;

            am4core.useTheme(am4themes_animated);
            var chart = am4core.create("chartdiv", am4charts.XYChart);

            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            var i = 0;
            angular.forEach($scope.data, function (value, key) {
                createSeries("value" + i, key, value);
                i++;
            });

            function createSeries(s, name, datas) {
                var series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.valueY = "value" + s;
                series.dataFields.dateX = "year";
                series.name = name;

                var segment = series.segments.template;
                segment.interactionsEnabled = true;

                var hoverState = segment.states.create("hover");
                hoverState.properties.strokeWidth = 3;

                var dimmed = segment.states.create("dimmed");
                dimmed.properties.stroke = am4core.color("#dadada");

                segment.events.on("over", function(event) {
                    processOver(event.target.parent.parent.parent);
                });

                segment.events.on("out", function(event) {
                    processOut(event.target.parent.parent.parent);
                });

                var data = [];
                angular.forEach(datas, function (value, key) {
                    var dataItem = { year: new Date(value.year) };
                    dataItem["value" + s] = value.harga;
                    data.push(dataItem);
                    
                });

                series.data = data;
                
                return series;
            }

            chart.legend = new am4charts.Legend();
            chart.legend.position = "button";
            chart.legend.scrollable = true;

            chart.legend.markers.template.states.create("dimmed").properties.opacity = 0.3;
            chart.legend.labels.template.states.create("dimmed").properties.opacity = 0.3;

            chart.legend.itemContainers.template.events.on("over", function(event) {
                processOver(event.target.dataItem.dataContext);
            })

            chart.legend.itemContainers.template.events.on("out", function(event) {
                processOut(event.target.dataItem.dataContext);
            })

            function processOver(hoveredSeries) {
                hoveredSeries.toFront();

                hoveredSeries.segments.each(function(segment) {
                    segment.setState("hover");
                })
              
              hoveredSeries.legendDataItem.marker.setState("default");
              hoveredSeries.legendDataItem.label.setState("default");

                chart.series.each(function(series) {
                    if (series != hoveredSeries) {
                        series.segments.each(function(segment) {
                            segment.setState("dimmed");
                        })
                        series.bulletsContainer.setState("dimmed");
                  series.legendDataItem.marker.setState("dimmed");
                  series.legendDataItem.label.setState("dimmed");
                    }
                });
            }

            function processOut() {
                chart.series.each(function(series) {
                    series.segments.each(function(segment) {
                        segment.setState("default");
                    })
                    series.bulletsContainer.setState("default");
                series.legendDataItem.marker.setState("default");
                series.legendDataItem.label.setState("default");
                });
            }
		})
	}

    $scope.chartdiv();

}]);