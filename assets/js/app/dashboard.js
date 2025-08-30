mainApp.controller('dashboard', ['$scope', 'httpHandler', '$http', '$window', function ($scope, httpHandler, $http, $window) {

    $scope.no = 1;
    $scope.itemsPerPage = 10;
    $scope.keyword = {};
    $scope.search_Method = {};
    $scope.total_count = 0;
    $scope.message = null;

    $scope.getData = function (pageno) {
        if (pageno == 0)
            $scope.no = 1;
        else
            $scope.no = (pageno * $scope.itemsPerPage) - ($scope.itemsPerPage - 1);

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

    $scope.searchMethod = function (keyname, val) {
        $scope.keyword[keyname] = val;
        $scope.getData(1);
    }

    $scope.reset = function (is_master) {
        $scope.keyword = {};
        $scope.search_Method = {};
        if (is_master == "master") {
            $scope.getData(1);
        }
    }

    $scope.chartdiv = function () {

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

                segment.events.on("over", function (event) {
                    processOver(event.target.parent.parent.parent);
                });

                segment.events.on("out", function (event) {
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

            chart.legend.itemContainers.template.events.on("over", function (event) {
                processOver(event.target.dataItem.dataContext);
            })

            chart.legend.itemContainers.template.events.on("out", function (event) {
                processOut(event.target.dataItem.dataContext);
            })

            function processOver(hoveredSeries) {
                hoveredSeries.toFront();

                hoveredSeries.segments.each(function (segment) {
                    segment.setState("hover");
                })

                hoveredSeries.legendDataItem.marker.setState("default");
                hoveredSeries.legendDataItem.label.setState("default");

                chart.series.each(function (series) {
                    if (series != hoveredSeries) {
                        series.segments.each(function (segment) {
                            segment.setState("dimmed");
                        })
                        series.bulletsContainer.setState("dimmed");
                        series.legendDataItem.marker.setState("dimmed");
                        series.legendDataItem.label.setState("dimmed");
                    }
                });
            }

            function processOut() {
                chart.series.each(function (series) {
                    series.segments.each(function (segment) {
                        segment.setState("default");
                    })
                    series.bulletsContainer.setState("default");
                    series.legendDataItem.marker.setState("default");
                    series.legendDataItem.label.setState("default");
                });
            }
        })
    }

    $scope.chartLokasi = function () {
        httpHandler.send({
            method: 'GET',
            url: urls + "dashboard/getDataLokasi"
        }).then(function (response) {
            // ambil array lokasi dari nested data
            var lokasi = response.data.data.data;
            console.log(lokasi);

            am4core.useTheme(am4themes_animated);
            var chart = am4core.create("chartLokasi", am4charts.XYChart);

            chart.data = lokasi.map(item => ({
                tahun: item.tahun,
                jumlah: parseInt(item.jumlah)
            }));

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "tahun";
            categoryAxis.title.text = "Tahun Survey";

            categoryAxis.renderer.minGridDistance = 40;
            categoryAxis.renderer.labels.template.rotation = 0;
            categoryAxis.renderer.labels.template.horizontalCenter = "middle";
            categoryAxis.renderer.labels.template.verticalCenter = "top";

            categoryAxis.sortBySeries = chart.series.values[0];

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.title.text = "Jumlah Lokasi";
            valueAxis.min = 0;
            valueAxis.strictMinMax = true;
            valueAxis.renderer.minGridDistance = 30;
            valueAxis.renderer.minLabelPosition = 0.01;
            valueAxis.renderer.maxLabelPosition = 0.99;
            valueAxis.renderer.grid.template.disabled = false;


            valueAxis.extraMin = 1;
            valueAxis.extraMax = 5;
            valueAxis.renderer.minGridDistance = 50;
            valueAxis.renderer.ticks.template.disabled = false;
            valueAxis.renderer.ticks.template.strokeOpacity = 0.5;
            valueAxis.renderer.ticks.template.stroke = am4core.color("#000");
            valueAxis.renderer.ticks.template.length = 10;
            valueAxis.renderer.line.strokeOpacity = 1;
            valueAxis.strictMinMax = true;
            valueAxis.calculateTotals = true;
            valueAxis.renderer.baseGrid.disabled = true;
            valueAxis.renderer.labels.template.adapter.add("text", function (text) {
                return text;
            });

            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "jumlah";
            series.dataFields.categoryX = "tahun";
            series.columns.template.fill = am4core.color("#4dd0e1");
            series.columns.template.tooltipText = "Tahun {categoryX}: [bold]{valueY} Lokasi[/]";
        });
    };

    $scope.chartKelompok = function () {
        httpHandler.send({
            method: 'GET',
            url: urls + "dashboard/dataAll"
        }).then(function successCallback(response) {
            $scope.dataKelompok = response.data.data;

            // Proses data supaya jadi jumlah item per tahun
            var groupedData = {};
            angular.forEach($scope.dataKelompok, function (items, kelompok) {
                angular.forEach(items, function (val, tahun) {
                    if (!groupedData[tahun]) {
                        groupedData[tahun] = 0;
                    }
                    // hitung jumlah item yang ada di tahun itu
                    if (val.harga && val.harga > 0) {
                        groupedData[tahun] += 1;
                    }
                });
            });

            // convert ke array untuk chart
            var chartData = [];
            angular.forEach(groupedData, function (jumlah, tahun) {
                chartData.push({
                    tahun: tahun,
                    jumlah: jumlah
                });
            });

            am4core.useTheme(am4themes_animated);
            var chart = am4core.create("chartKelompok", am4charts.XYChart);
            chart.data = chartData;

            // Axis Y = Tahun
            var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "tahun";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.title.text = "Tahun Survey";

            // Axis X = Jumlah item
            var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
            valueAxis.title.text = "Jumlah Item";

            // Series bar horizontal
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueX = "jumlah";
            series.dataFields.categoryY = "tahun";
            series.columns.template.fill = am4core.color("#4dd0e1");
            series.columns.template.strokeWidth = 0;
            series.tooltipText = "Tahun {categoryY}: [bold]{valueX} Item[/]";

            chart.cursor = new am4charts.XYCursor();
        });
    };




    $scope.chartLokasi();
    $scope.chartKelompok();

    $scope.chartdiv();

}]);