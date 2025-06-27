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
                            data-bs-target="#modalTambahSpvSales">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblSpvSales" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama SPV</th>
                                            <th>Status SPV</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahSpvSales" tabindex="-1" aria-labelledby="modalTambahSpvSalesLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahSpvSalesLabel">Tambah SPV Sales</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="kd_karyawan" class="form-label">Pilih SPV</label>
                                    <select class="form-control" name="kd_karyawan" id="kd_karyawan"></select>
                                    <input type="hidden" autocomplete="off" class="form-control" id="nama_spv_sales" placeholder="Masukkan Nama Spv" disabled>
                                    <input type="hidden" autocomplete="off" class="form-control" id="kd_posisi" disabled>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanSpvSales">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalUbahSpvSales" tabindex="-1" aria-labelledby="modalUbahSpvSalesLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahSpvSalesLabel">Ubah SPV Sales</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="status_spv_sales" class="form-label">SPV Sales</label>
                                    <input type="hidden" autocomplete="off" class="form-control" id="kd_spv_sales" placeholder="Masukkan Nama Spv" disabled>
                                    <input type="text" autocomplete="off" class="form-control" id="ubah_nama_spv_sales" placeholder="Masukkan Nama Spv" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="status_spv_sales" class="form-label">Pilih Status SPV Sales</label>
                                    <select class="form-control" name="status_spv_sales" id="status_spv_sales">
                                        <option value="ACTIVE">ACTIVE</option>
                                        <option value="NON ACTIVE">NON ACTIVE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnUbahSpvSales">
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
        let filterDataKaryawan = [];
        let posisition = [];
        let allSpvSales = [];

        const simpanDataSpvSales = async () => {
            const csrfToken = $('#csrf_token').val();
            let kdKaryawan = $('#kd_karyawan').val();
            let namaSpvSales = $('#nama_spv_sales').val();
            let kdPosisi = $('#kd_posisi').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            if (!validateInput(namaSpvSales, 'nama SPV tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                kd_karyawan: kdKaryawan,
                nama_spv_sales: namaSpvSales,
                kd_position: kdPosisi,
                kd_user: user_input,
            }

            try {
                const response = await fetch(`<?= BASEURL; ?>/sales/validasiSimpanSpvSales`, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(dataToSave)
                });

                const result = await response.json();
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

        const ubahDataSpvSales = async () => {
            const csrfToken = $('#csrf_token').val();
            let kdSpvSales = $('#kd_spv_sales').val();
            let namaSpvSales = $('#ubah_nama_spv_sales').val();
            let statusSpvSales = $('#status_spv_sales').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            let dataToSave = {
                csrf_token: csrfToken,
                kd_spv_sales: kdSpvSales,
                ubah_nama_spv_sales: namaSpvSales,
                status_spv_sales: statusSpvSales,
                kd_user: user_input,
            }

            try {
                const response = await fetch(`<?= BASEURL; ?>/sales/validasiUbahSpvSales`, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(dataToSave)
                });

                const result = await response.json();
                if (result.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message || 'Data berhasil Diubah!',
                    }).then(() => {
                        $('#modalUbahSpvSales').modal('hide');
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
            getAllDataKaryawanNew(url).then(data => {
                let filterData = data.filter((it) => it.kd_position === 'PST-202501-0003');
                filterDataKaryawan = data
                loadSelectSpv(filterData)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllDataKaryawanNew: ${err.statusText || err}`,
                });
            });

            getAllPosisiton(url).then(data => {
                let filterPosisi = data.filter((it) => it.kd_position === 'PST-202506-0000');
                posisition = filterPosisi
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllPosisiton: ${err.statusText || err}`,
                });
            });

            getAllSpvSales(url).then(data => {
                allSpvSales = data
                loadDataSpvSales(allSpvSales)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllPosisiton: ${err.statusText || err}`,
                });
            });

            $('#modalTambahSpvSales, #modalUbahSpvSales').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalTambahSpvSales').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
            });

            $('#modalTambahSpvSales').on('shown.bs.modal', function() {
                defaultSelect2("#kd_karyawan", "-- Pilih SPV --", '#modalTambahSpvSales');
            });

            $('#kd_karyawan').on('change', function() {
                const selectedKd = $(this).val();
                const selectedSpv = filterDataKaryawan.find(item => item.kd_karyawan === selectedKd);
                if (selectedSpv) {
                    $('#nama_spv_sales').val(selectedSpv.nama_karyawan);
                    $('#kd_posisi').val(posisition[0].kd_position);
                } else {
                    $('#nama_spv_sales').val('');
                    $('#kd_posisi').val('');
                }
            });

            $('#btnSimpanSpvSales').on('click', () => {
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
                        simpanDataSpvSales()
                    }
                });
            });

            $('#btnUbahSpvSales').on('click', () => {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mengubahah data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        ubahDataSpvSales()
                    }
                });
            });
        });

        const tampilUbahSpvSales = (data) => {
            $('#kd_spv_sales').val(data.kd_spv_sales);
            $('#ubah_nama_spv_sales').val(data.karyawan.nama_karyawan);
            $('#status_spv_sales').val(data.status_spv_sales).trigger('change')
        }

        const loadSelectSpv = (data) => {
            let filterSpv = data.filter((it) => it.daftar_spv_sales === "TIDAK")
            let tambahSpv = $('#kd_karyawan');

            tambahSpv.empty();
            tambahSpv.append('<option value="" disabled selected>-- PILIH SPV --</option>');

            filterSpv.forEach(item => {
                tambahSpv.append(`<option value="${item.kd_karyawan}">${item.nama_karyawan}</option>`);
            });
        };
        

        const loadDataSpvSales = (data) => {
            $('#tblSpvSales').DataTable({
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
                        data: null,
                        render: function(data, type, row, meta) {
                            let badge;
                            if (row.status_spv_sales === 'ACTIVE') {
                                badge = `<span class="badge bg-success">${row.status_spv_sales}</span>`
                            } else {
                                badge = `<span class="badge bg-danger">${row.status_spv_sales}</span>`
                            }
                            return `${badge}`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbahSpvSales(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahSpvSales"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick=''></i>
                            </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblSpvSales tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblSpvSales tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        };
    </script>