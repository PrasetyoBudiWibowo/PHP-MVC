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
                            data-bs-target="#modalTambahSumberInformasiDetailbukutamu">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="filterSumberInformasi" class="form-label">Pilih Sumber Informasi</label>
                            <select id="filterSumberInformasi" class="form-select">
                                <option value="">-- Pilih Sumber Informasi --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button id="btnSearch" class="btn btn-success"><i class="fas fa-search"></i> Cari</button>
                            <button id="btnReset" class="btn btn-danger"><i class="fas fa-sync-alt"></i> Reset</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblSumberInformasiBukutamu" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Sumber Informasi</th>
                                            <th>Sumber Informasi Detail</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahSumberInformasiDetailbukutamu" tabindex="-1" aria-labelledby="modalTambahSumberInformasiDetailbukutamuLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahSumberInformasiDetailbukutamuLabel">Tambah Sumber Informasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="kd_sumber_informasi_buku_tamu" class="form-label">Pilih Sumber Informasi</label>
                                    <select class="form-control" name="kd_sumber_informasi_buku_tamu" id="kd_sumber_informasi_buku_tamu"></select>
                                </div>
                                <div class="mb-3">
                                    <label for="nm_sumber_informasi_detail" class="form-label">Nama Sumber Informasi Detail</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nm_sumber_informasi_detail"
                                        placeholder="Masukkan Sumber Infomasi" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanSumberInfomasiDetail">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalUbahSumberinformasiDetailBukuTamu" tabindex="-1" aria-labelledby="modalUbahSumberinformasiDetailBukuTamuLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalUbahSumberinformasiDetailBukuTamuLabel">Tambah Sumber Informasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <input type="hidden" name="kd_sumber_informasi_detail_buku_tamu" id="kd_sumber_informasi_detail_buku_tamu">
                                <div class="mb-3">
                                    <label for="ubah_kd_sumber_informasi_buku_tamu" class="form-label">Pilih Sumber Informasi</label>
                                    <select class="form-control" name="ubah_kd_sumber_informasi_buku_tamu" id="ubah_kd_sumber_informasi_buku_tamu"></select>
                                </div>
                                <div class="mb-3">
                                    <label for="ubah_nm_sumber_informasi_detail" class="form-label">Nama Sumber Informasi</label>
                                    <input type="text" autocomplete="off" class="form-control" id="ubah_nm_sumber_informasi_detail"
                                        placeholder="Masukkan Sumber Infomasi" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tampil_buku_tamu" class="form-label">Tampil Di Buku Tamu</label>
                                    <select class="form-select" id="tampil_buku_tamu" required>
                                        <option value="YA">YA</option>
                                        <option value="TIDAK">TIDAK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnUbahSumberinformasiBukuTamu">
                                    <i class="fa-solid fa-paper-plane"></i> Ubah
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

        const simpanDataSumberInformasiDetail = async () => {
            const csrfToken = $('#csrf_token').val();
            let kdSumberInformasi = $('#kd_sumber_informasi_buku_tamu').val();
            let nmSumberInformasiDetail = $('#nm_sumber_informasi_detail').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            if (!validateInput(nmSumberInformasiDetail, 'nama sumber inforamsi detail tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                kd_sumber_informasi_buku_tamu: kdSumberInformasi,
                nm_sumber_informasi_detail: nmSumberInformasiDetail,
                kd_user: user_input,
            }

            try {
                const response = await fetch(`<?= BASEURL; ?>/bukutamu/validaSimpanSumberInformasiDetail`, {
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
                        text: result.message || 'Data berhasil disimpan!',
                    }).then(() => {
                        $('#modalTambahSumberInformasiDetailbukutamu').modal('hide');
                        location.reload();
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

        const ubahDataSumberInformasiDetail = async () => {
            const csrfToken = $('#csrf_token').val();
            let kdSumberInformasiDetail = $('#kd_sumber_informasi_detail_buku_tamu').val();
            let kdSumberInformasi = $('#ubah_kd_sumber_informasi_buku_tamu').val();
            let nmSumberInformasiDetail = $('#ubah_nm_sumber_informasi_detail').val();
            let tampilBukuTamu = $('#tampil_buku_tamu').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            if (!validateInput(nmSumberInformasiDetail, 'nama sumber inforamsi detail tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                kd_sumber_informasi_detail_buku_tamu: kdSumberInformasiDetail,
                kd_sumber_informasi_buku_tamu: kdSumberInformasi,
                nm_sumber_informasi_detail: nmSumberInformasiDetail,
                tampil_buku_tamu: tampilBukuTamu,
                kd_user: user_input,
            }

            try {
                const response = await fetch(`<?= BASEURL; ?>/bukutamu/validasiubahSumberInformasiDetail`, {
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
                        text: result.message || 'Data berhasil diUbah!',
                    }).then(() => {
                        $('#modalUbahSumberinformasiDetailBukuTamu').modal('hide');
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
            getAllDataSumberInformasi(url).then(data => {
                loadSelectSumberInformasi(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllDataSumberInformasi: ${err.statusText || err}`,
                });
            });

            fetchDataSumberInformasiDetail(url).then(data => {
                loadDataSumberInformasiDetail(data);
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllDataSumberInformasi: ${err.statusText || err}`,
                });
            });

            $('#modalTambahSumberInformasiDetailbukutamu, #modalUbahSumberinformasiDetailBukuTamu').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalTambahSumberInformasiDetailbukutamu').on('shown.bs.modal', function() {
                defaultSelect2("#kd_sumber_informasi_buku_tamu", "-- Pilih Sumber Informasi --", '#modalTambahSumberInformasiDetailbukutamu');
            });

            $('#modalUbahSumberinformasiDetailBukuTamu').on('shown.bs.modal', function() {
                defaultSelect2("#ubah_kd_sumber_informasi_buku_tamu", "-- Pilih Sumber Informasi --", '#modalUbahSumberinformasiDetailBukuTamu');
            });

            defaultSelect2("#filterSumberInformasi", "-- Pilih Sumber Informasi --");

            $('#modalTambahSumberInformasiDetailbukutamu').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $(this).find('select').val(null).trigger('change');
            });

            $('#nm_sumber_informasi_detail, #ubah_nm_sumber_informasi_detail').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#btnSimpanSumberInfomasiDetail').on('click', function() {
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
                        simpanDataSumberInformasiDetail()
                    }
                });
            })

            $('#btnUbahSumberinformasiBukuTamu').on('click', function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mengubah data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        ubahDataSumberInformasiDetail()
                    }
                });
            })

            $('#btnSearch').on('click', function() {
                let sumberInformasi = $('#filterSumberInformasi').val();

                if (sumberInformasi) {
                    fetchDataSumberInformasiDetail(url).then(data => {

                        let filterData = data.filter(it => it.kd_sumber_informasi_buku_tamu === sumberInformasi);
                        $('#tblSumberInformasiBukutamu').DataTable().clear().destroy();
                        loadDataSumberInformasiDetail(filterData);
                    }).catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: `Terjadi kesalahan btnSearch getAllDataSumberInformasi: ${err.statusText || err}`,
                        });
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: `Pilih Sumber Informasi Terlebih Dahulu.`,
                    });
                }
            });

            $('#btnReset').on('click', function() {
                fetchDataSumberInformasiDetail(url).then(data => {

                    $('#filterSumberInformasi').val('')
                    $('#tblSumberInformasiBukutamu').DataTable().clear().destroy();
                    loadDataSumberInformasiDetail(data);
                }).catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: `Terjadi kesalahan btnSearch getAllDataSumberInformasi: ${err.statusText || err}`,
                    });
                });

            })
        })

        const tampilUbahSumberInfomasiDetail = (data) => {
            $('#kd_sumber_informasi_detail_buku_tamu').val(data.kd_sumber_informasi_detail_buku_tamu);
            $('#ubah_kd_sumber_informasi_buku_tamu').val(data.kd_sumber_informasi_buku_tamu).trigger('change')
            $('#ubah_nm_sumber_informasi_detail').val(data.nm_sumber_informasi_detail);
            $('#tampil_buku_tamu').val(data.tampil_buku_tamu).trigger('change');
        }

        const populateSelect = (selectId, options) => {
            const select = $(selectId);
            select.empty().append('<option value="" disabled selected>-- Pilih Sumber Informasi --</option>');
            options.forEach(item => {
                select.append(`<option value="${item.kd_sumber_informasi_buku_tamu}">${item.nm_sumber_informasi}</option>`);
            });
        };

        const loadSelectSumberInformasi = (data) => {
            const filterData = data.filter(item => item.tampil_buku_tamu === 'YA');
            populateSelect('#kd_sumber_informasi_buku_tamu', filterData);
            populateSelect('#ubah_kd_sumber_informasi_buku_tamu', filterData);
            populateSelect('#filterSumberInformasi', filterData);
        };

        const loadDataSumberInformasiDetail = (data) => {
            $('#tblSumberInformasiBukutamu').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'sumber_informasi.nm_sumber_informasi',
                    },
                    {
                        data: 'nm_sumber_informasi_detail',
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbahSumberInfomasiDetail(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahSumberinformasiDetailBukuTamu"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick=''></i>
                            </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblSumberInformasiBukutamu tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblSumberInformasiBukutamu tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        }
    </script>