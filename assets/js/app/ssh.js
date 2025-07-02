mainApp.controller('ssh', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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

    function setInputFilter(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
            if (textbox != null) {
                textbox.addEventListener(event, function () {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    } else {
                        this.value = "";
                    }
                });
            }
        });
    }

	$scope.no = 1;
	$scope.itemsPerPage = 10;
	$scope.keyword = {};
    $scope.table_header = [];
	$scope.search_Method ={};
	$scope.total_count = 0;
	$scope.message = null;
    $scope.tab_ssh = true;
    $scope.tab_sbu = false;
    $scope.tab_type = "SSH";
    $scope.input_tab = 'SSH';
    $scope.year = "";

	$scope.getData = function (pageno) {
        $scope.loading = true;
		if(pageno == 0)
            $scope.no = 1;
        else
            $scope.no = (pageno*$scope.itemsPerPage) - ($scope.itemsPerPage - 1);

		$scope.total_count = 0;
		$scope.message = null;

		var params = {
            tipe: $scope.tab_type,
			keyword: $scope.keyword,
			limit: $scope.itemsPerPage,
			offset: pageno != 0 ? pageno : 1,
		}

		httpHandler.send({
			method: 'GET',
			url: urls + 'ssh/getData',
			params: params
		}).then(
			function successCallbacks(response) {
				$scope.loading = false;
				$scope.data = response.data.data;
                $scope.year = response.data.year;
				$scope.table_header = response.data.header;
				$scope.total_count = response.data.count;
				$scope.message = response.data.message;
				$scope.curPage = pageno;

                setTimeout(function() {
                    setInputFilter(document.getElementById('year'), function (value) {
                        return /^-?\d*$/.test(value);
                    });
                }, 100);
			}
		);
	}

	$scope.getData(0);

    $scope.tab = function (tab) {
        if (tab == 'ssh'){
            $scope.tab_ssh = true;
            $scope.tab_sbu = false;
            $scope.tab_type = "SBU";
        }else if (tab == 'sbu'){
            $scope.phone = null;
            $scope.tab_ssh = false;
            $scope.tab_sbu = true;
            $scope.tab_type = "SBU";
        }
        $scope.getData(0);
    }

	$scope.searchMethod = function(keyname, val)
	{
        if(keyname == 'TahunHarga'){
            if(Number(val)){
                $scope.keyword[keyname] = val;
                $scope.getData(0); 
            }else if (val == ''){
                $scope.keyword['TahunHarga'] = val;
                $scope.getData(0); 
            }
        }else{
            $scope.keyword[keyname] = val;
            $scope.getData(0); 
        }
    }

    $scope.reset = function(is_master)
    {
    	$scope.keyword = {};
    	$scope.search_Method = {};
    	if(is_master == "master"){
    		$scope.getData(0);
    	}
    }

    $scope.show_modal = function($data){
        $('#modal_detail').modal('show');
        $scope.view($data);

    }

    $scope.view = function($id){
    httpHandler.send({
            method: 'GET',
            url: urls + 'ssh/getById',
            params: {'id': $id}
        }).then(
            function successCallbacks(response) {
                
                if(response.data.status == 200){
                    $scope.loading = false;
                    $scope.kegiatan = response.data.data.kegiatan;
                    $scope.spesifikasi = response.data.data.spesifikasi;
                    $scope.total = response.data.data.total;
                    $scope.tableKelompok = $scope.spesifikasi;

                    $scope.viewKegiatan = $scope.kegiatan

                }else{
                    Swal.close();
                    Swal.fire({
                        title: 'Failed',
                        text: response.data.message,
                        icon: response.data.status == 500 ? 'error' : 'warning',
                        showCancelButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        confirmButtonColor: "#fc544b",
                        confirmButtonText: "Oke",
                    }).then((result) => {
                        if (result.value) {
                        }
                    });
                }
            }
        );
    }

}]);
