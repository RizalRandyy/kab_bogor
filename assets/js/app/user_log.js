mainApp.controller('user_log', ['$scope', 'httpHandler', '$filter', '$attrs', function ($scope, httpHandler, $filter, $attrs) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: false,
        allowEscapeKey: false,
        allowOutsideClick: false,
        showClass: {
            popup: "animated lightSpeedIn",
        },
        hideClass: {
            popup: "animated lightSpeedOut",
        },
        onOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });

    $scope.no = 1;
    $scope.itemsPerPage = 10;
    $scope.keyword = {};
    $scope.search_Method ={};
    $scope.total_count = 0;
    $scope.message = null;
    $scope.date = $("#date").val();

    $scope.getData = function (pageno) {
        if(pageno == 0)
            $scope.no = 1;
        else
            $scope.no = (pageno*$scope.itemsPerPage) - ($scope.itemsPerPage - 1);

        $scope.total_count = 0;
        $scope.message = null;

        var params = {
            date: $scope.date,
            keyword: $scope.keyword,
            limit: $scope.itemsPerPage,
            offset: pageno != 0 ? pageno : 1,
        }

        $scope.loading = true;

        httpHandler.send({
            method: 'GET',
            url: urls + 'user_log/getData',
            params: params
        }).then(
            function successCallbacks(response) {
                $scope.loading = false;
                $scope.data = response.data.data;
                $scope.table_header = response.data.header;
                $scope.total_count = response.data.total;
                $scope.dates = response.data.dates;
                $scope.message = response.data.message;
                $scope.curPage = pageno;
            }
        );
    }

    $scope.getData(0);

    $scope.searchMethod = function(keyname, val)
    {
        $scope.date = $("#date").val();
        $scope.keyword[keyname] = val;
        $scope.getData(1);
    }

    $scope.reset = function(is_master)
    {
        $scope.date = ""
        $scope.keyword = {};
        $scope.search_Method = {};
        if(is_master == "master"){
            $scope.getData(1);
        }
    }

}]);
