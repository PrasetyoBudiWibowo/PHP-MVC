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
                            data-bs-target="#modalTambahSumberInformasibukutamu">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblSumberInformasiBukutamu" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Sumber Informasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahSumberInformasibukutamu" tabindex="-1" aria-labelledby="modalTambahSumberInformasibukutamuLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahSumberInformasibukutamuLabel">Tambah Sumber Informasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="nm_sumber_informasi" class="form-label">Nama Sumber Informasi</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nm_sumber_informasi"
                                        placeholder="Masukkan Sumber Infomasi" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanSumberInfomasi">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalUbahSumberinformasiBukuTamu" tabindex="-1" aria-labelledby="modalUbahSumberinformasiBukuTamuLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahSumberInformasibukutamuLabel">Tambah Sumber Informasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <input type="hidden" name="kd_sumber_informasi_buku_tamu" id="kd_sumber_informasi_buku_tamu">
                                <div class="mb-3">
                                    <label for="ubah_nm_sumber_informasi" class="form-label">Nama Sumber Informasi</label>
                                    <input type="text" autocomplete="off" class="form-control" id="ubah_nm_sumber_informasi"
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
        let allDataSumberInformasi = [];

        const simpanDataSumberInformasi = async () => {
            const csrfToken = $('#csrf_token').val();
            let nmSumberInformasi = $('#nm_sumber_informasi').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            if (!validateInput(nmSumberInformasi, 'nama sumber inforamsi tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                nm_sumber_informasi: nmSumberInformasi,
                kd_user: user_input,
            }

            Swal.fire({
                title: 'Menyimpan data...',
                text: 'Harap tunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "<?= BASEURL; ?>/bukutamu/validaSimpanSumberInformasi",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan!',
                        }).then(() => {
                            $('#modalTambahSumberInformasibukutamu').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menyimpan data.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: `Terjadi kesalahan ${xhr.responseText}.`,
                    });
                }
            });
        };

        const ubahDataSumberInformasi = async () => {
            const csrfToken = $('#csrf_token').val();
            let kdSumberInformasi = $('#kd_sumber_informasi_buku_tamu').val();
            let ubahNmSumberInformasi = $('#ubah_nm_sumber_informasi').val();
            let tampilBukuTamu = $('#tampil_buku_tamu').val();

            if (!validateInput(ubahNmSumberInformasi, 'nama sumber inforamsi tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                kd_sumber_informasi_buku_tamu: kdSumberInformasi,
                nm_sumber_informasi: ubahNmSumberInformasi,
                tampil_buku_tamu: tampilBukuTamu,
            }

            console.log('sdbj', dataToSave)

            Swal.fire({
                title: 'Proses Ubah data...',
                text: 'Harap tunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "<?= BASEURL; ?>/bukutamu/validasiUbahSumberInformasi",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan!',
                        }).then(() => {
                            $('#modalUbahSumberinformasiBukuTamu').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menyimpan data.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: `Terjadi kesalahan ${xhr.responseText}.`,
                    });
                }
            });
        };

        $(document).ready(function() {
            getAllDataSumberInformasi(url).then(data => {
                loadDataSumberInformasi(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllDataSumberInformasi: ${err.statusText || err}`,
                });
            });

            $('#modalTambahSumberInformasibukutamu').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalTambahSumberInformasibukutamu').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
            });

            $('#nm_sumber_informasi, #ubah_nm_sumber_informasi').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#btnSimpanSumberInfomasi').on('click', () => {
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
                        simpanDataSumberInformasi()
                    }
                });
            });

            $('#btnUbahSumberinformasiBukuTamu').on('click', () => {
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
                        ubahDataSumberInformasi()
                    }
                });
            });

        });

        const tampilUbahSumberinformasiBukuTamu = (data) => {
            $('#kd_sumber_informasi_buku_tamu').val(data.kd_sumber_informasi_buku_tamu);
            $('#ubah_nm_sumber_informasi').val(data.nm_sumber_informasi);
            $('#tampil_buku_tamu').val(data.tampil_buku_tamu).trigger('change');
            $('#modalUbahSumberinformasiBukuTamu').modal('show');
        }

        const loadDataSumberInformasi = (data) => {
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
                        data: 'nm_sumber_informasi',
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbahSumberinformasiBukuTamu(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahSumberinformasiBukuTamu"></i>
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