<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahSales">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblSales" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Sales</th>
                                            <th>SPV Sales</th>
                                            <th>Status Sales</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahSales" tabindex="-1" aria-labelledby="modalTambahSalesLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahSalesLabel">Tambah SPV Sales</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="kd_spv_sales" class="form-label">Pilih SPV</label>
                                    <select class="form-control" name="kd_spv_sales" id="kd_spv_sales"></select>
                                </div>
                                <div class="mb-3">
                                    <label for="kd_karyawan" class="form-label">Pilih Sales</label>
                                    <select class="form-control" name="kd_karyawan" id="kd_karyawan"></select>
                                    <input type="hidden" autocomplete="off" class="form-control" id="nama_sales" placeholder="Masukkan Nama Sales" disabled>
                                    <input type="hidden" autocomplete="off" class="form-control" id="kd_posisi" disabled>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanSales">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <script>
        const url = "<?= BASEURL ?>";
        let spv = [];
        let dataSales = [];
        let posisition = [];
        let filterSpv = [];

        const simpanDataSales = async () => {
            const csrfToken = $('#csrf_token').val();
            let kdKaryawan = $('#kd_karyawan').val();
            let kdSpvSalaes = $('#kd_spv_sales').val();
            let namaSales = $('#nama_sales').val();
            let kdPosisi = $('#kd_posisi').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            let dataToSave = {
                csrf_token: csrfToken,
                kd_karyawan: kdKaryawan,
                kd_spv_sales: kdSpvSalaes,
                nama_sales: namaSales,
                kd_position: kdPosisi,
                kd_user: user_input,
            }

            try {
                Swal.fire({
                    title: 'Menyimpan data...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                })

                const response = await fetch(`<?= BASEURL; ?>/sales/validasiSimpanSales`, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(dataToSave)
                });

                const result = await response.json();
                Swal.close();
                if (result.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message || 'Data berhasil Disimpan!',
                    }).then(() => {
                        $('#modalTambahSpvSales').modal('hide');
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                    });
                }
            } catch (error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan ${error.message}.`,
                });
            }
        }

        $(document).ready(function() {
            getAllSpvSales(url).then(data => {
                loadSelectSpv(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllPosisiton: ${err.statusText || err}`,
                });
            });

            getAllDataKaryawanNew(url).then(data => {
                let filterData = data.filter((it) => it.kd_position === 'PST-202501-0003' && it.daftar_sales === 'TIDAK');
                filterSpv = data
                loadSelectSales(filterData)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllDataKaryawanNew: ${err.statusText || err}`,
                });
            });

            getAllPosisiton(url).then(data => {
                let filterPosisi = data.filter((it) => it.kd_position === 'PST-202501-0003');
                posisition = filterPosisi
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllPosisiton: ${err.statusText || err}`,
                });
            });

            getAllSales(url).then(data => {
                dataSales = data
                loadDataSales(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllPosisiton: ${err.statusText || err}`,
                });
            });

            $('#modalTambahSales').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalTambahSales').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
            });

            $('#modalTambahSales').on('shown.bs.modal', function() {
                defaultSelect2("#kd_spv_sales", "-- Pilih SPV --", '#modalTambahSales');
                defaultSelect2("#kd_karyawan", "-- Pilih SALES --", '#modalTambahSales');
            });

            $('#kd_spv_sales').on('change', function() {
                const selectedValue = $(this).val();

                if (selectedValue) {
                    $('#kd_karyawan').prop('disabled', false);
                } else {
                    $('#kd_karyawan').prop('disabled', true);
                }
            });

            $('#kd_karyawan').prop('disabled', true);

            $('#kd_karyawan').on('change', function() {
                const selectedKd = $(this).val();
                const selectedSpv = filterSpv.find(item => item.kd_karyawan === selectedKd);
                if (selectedSpv) {
                    $('#nama_sales').val(selectedSpv.nama_karyawan);
                    $('#kd_posisi').val(posisition[0].kd_position);
                } else {
                    $('#nama_sales').val('');
                    $('#kd_posisi').val('');
                }
            });

            $('#btnSimpanSales').on('click', () => {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menyimpan data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        simpanDataSales()
                    }
                });
            });
        });

        const loadSelectSpv = (data) => {
            let spvActive = data.filter((it) => it.status_spv_sales === 'ACTIVE')
            let selectedSpv = $('#kd_spv_sales');

            selectedSpv.empty()
            selectedSpv.append('<option value="" disabled selected>-- PILIH SPV --</option>');

            spvActive.forEach(item => {
                selectedSpv.append(`<option value="${item.kd_spv_sales}">${item.karyawan.nama_karyawan}</option>`);
            })
        }

        const loadSelectSales = (data) => {
            let selectSales = $('#kd_karyawan');

            selectSales.empty()
            selectSales.append('<option value="" disabled selected>-- PILIH SALES --</option>');

            data.forEach(item => {
                selectSales.append(`<option value="${item.kd_karyawan}">${item.nama_karyawan}</option>`);
            })
        }

        const loadDataSales = (data) => {
            $('#tblSales').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'karyawan.nama_karyawan',
                    },
                    {
                        data: 'spv_sales.karyawan.nama_karyawan'
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            let badge;
                            if (row.status_sales === 'ACTIVE') {
                                badge = `<span class="badge bg-success">${row.status_sales}</span>`
                            } else {
                                badge = `<span class="badge bg-danger">${row.status_sales}</span>`
                            }
                            return `${badge}`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='' data-bs-toggle="modal" data-bs-target="#modalUbahSpvSales"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick=''></i>
                            </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblSales tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblSales tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        }
    </script>