mainApp

    .directive('customOnChange', function () {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var onChangeFunc = scope.$eval(attrs.customOnChange);
                element.bind('change', onChangeFunc);
            }
        };
    })

    .controller('tambah_tahun_kegiatan_asb', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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

        $scope.getData = function (pageno = 1) {
            httpHandler.send({
                method: 'GET',
                url: urls + 'tahun_kegiatan_asb/kegiatan'
            }).then(
                function successCallbacks(response) {
                    $scope.options = response.data.data;
                }
            );
        }

        $scope.id = !$("#id").val() ? null : $("#id").val();

        if ($scope.id) {

            $scope.loading = true;
            httpHandler.send({
                method: 'GET',
                url: urls + 'tahun_kegiatan_asb/getById',
                params: { 'id': $scope.id }
            }).then(
                function successCallbacks(response) {
                    if (response.data.status == 200) {
                        $scope.loading = false;
                        $scope.idASB = response.data.data.idASB;
                        $scope.tahun = response.data.data.tahunASB;
                        $scope.perbub_nomor = response.data.data.perbub_nomor;
                        if ($scope.tahun && $scope.tahun.length === 4) {
                            $scope.onChangeTahun();
                        }
                    } else {
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
                                window.location.replace(urls + 'tahun_kegiatan_asb');
                            }
                        });
                    }
                    $scope.getData();
                }
            );
        } else {
            $scope.getData();
        }

        $(document).ready(function () {
            $('#tahun').on('change', function () {
                var val = $(this).val();

                var scope = angular.element($('#tahun')).scope();
                scope.$apply(function () {
                    scope.tahun = val;

                    if (scope.onChangeTahun) {
                        scope.onChangeTahun();
                    }
                });
            });
        });

        $scope.onChangeTahun = function () {
            let tahun = $scope.tahun;

            if (!tahun || tahun.length !== 4) return;

            httpHandler.send({
                method: "GET",
                url: urls + 'spesifikasi_harga/getPerbub',
                params: { tahun: tahun }
            }).then(function (res) {
                if (res.data.status == 200) {
                    $scope.perbub_nomor = res.data.nomor_dokumen;
                } else {
                    $scope.perbub_nomor = "";
                }
            });
        };

        $scope.save = function () {
            var idASB = $('#idASB').val();
            var tahun = $('#tahun').val();
            var perbub_nomor = $('#perbub_nomor').val();

            if (idASB == null) {
                $('#idASB').focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Pilih ID Kegiatan!',
                });
            } else if (tahun == "") {
                $('#tahun').focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Masukan Tahun!',
                });
            } else if (tahun.length != 4) {
                $('#tahun').focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Input Tahun 4 Karakter!',
                });
            }

            var formData = new FormData();

            if ($scope.id) {
                formData.append("id", $scope.id);
            }
            formData.append("idASB", idASB);
            formData.append("tahunASB", tahun);
            formData.append("perbub_nomor", perbub_nomor);

            Swal.fire({
                title: 'Loading...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                imageUrl: urls + "assets/img/loadertsel.gif",
            });

            httpHandler.send({
                url: urls + 'tahun_kegiatan_asb/saveData',
                data: formData,
                method: 'POST',
                headers: {
                    'Content-Type': undefined
                }
            }).then(
                function successCallbacks(response) {
                    Swal.close();
                    Swal.fire({
                        title: 'Success',
                        text: response.data.message,
                        icon: 'success',
                        showCancelButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        confirmButtonColor: "#39edab",
                        confirmButtonText: "Oke",
                    }).then((result) => {
                        if (result.value) {
                            if ($scope.id) {
                                window.location.replace(urls + 'tahun_kegiatan_asb');
                            } else {
                                location.reload();
                            }
                        }
                    });
                },
                function errorCallback(response) {
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
                            // location.reload();
                        }
                    });
                });
        }

        $scope.redirect = function () {
            window.location.replace(urls + 'tahun_kegiatan_asb');
        }
    }]);

$(document).ready(function () {
    $('#idASB').select2({
        placeholder: "Pilih Kode Kegiatan"
    });
});