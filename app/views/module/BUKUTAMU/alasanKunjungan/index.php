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
                            data-bs-target="#modalAlasanKunjunganBukuTamu">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblAlasanKunjungan" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Alasan Kunjungan</th>
                                            <th>Tampil Di Buku Tamu</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalAlasanKunjunganBukuTamu" tabindex="-1" aria-labelledby="modalAlasanKunjunganBukuTamuLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAlasanKunjunganBukuTamuLabel">Tambah Alasan Kunjungan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="nama_alasan_kunjungan" class="form-label">Nama Alasan Kunjungan</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nama_alasan_kunjungan"
                                        placeholder="Masukkan Alasan Kunjungan" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanAlasanKunjungan">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalUbahAlasanKunjungan" tabindex="-1" aria-labelledby="modalUbahAlasanKunjunganLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalUbahAlasanKunjunganLabel">Ubah Alasan Kunjungan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="ubah_csrf_token" id="ubah_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="ubah_nama_alasan_kunjungan" class="form-label">Nama Alasan Kunjungan</label>
                                    <input type="hidden" autocomplete="off" name="kd_alasan_kunjungan_buku_tamu" id="kd_alasan_kunjungan_buku_tamu" disabled>
                                    <input type="text" autocomplete="off" class="form-control" id="ubah_nama_alasan_kunjungan"
                                        placeholder="Masukkan Alasan Kunjungan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tampil_buku_tamu" class="form-label">Pilih Status Tampil Di Buku Tamu</label>
                                    <select class="form-control" name="tampil_buku_tamu" id="tampil_buku_tamu">
                                        <option value="YA">YA</option>
                                        <option value="TIDAK">TIDAK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnUbahAlasanKunjungan">
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

        const simpanDataAlasanKunjungan = async () => {
            const csrfToken = $('#csrf_token').val();
            let namaAlasanKunjungan = $('#nama_alasan_kunjungan').val().trim();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            if (!validateInput(namaAlasanKunjungan, 'nama alasan kunjungan tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                nama_alasan_kunjungan: namaAlasanKunjungan,
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

                const response = await fetch(`<?= BASEURL; ?>/bukutamu/validasiSimpanAlasanKunjungan`, {
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
                        $('#modalAlasanKunjunganBukuTamu').modal('hide');
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

        const ubahDataAlasanKunjungan = async () => {
            const csrfToken = $('#ubah_csrf_token').val();
            let kdAlasanKunjungan = $('#kd_alasan_kunjungan_buku_tamu').val();
            let ubahNamaAlasanKunjungan = $('#ubah_nama_alasan_kunjungan').val().trim();
            let statusTampil = $('#tampil_buku_tamu').val();

            let dataToSave = {
                csrf_token: csrfToken,
                kd_alasan_kunjungan_buku_tamu: kdAlasanKunjungan,
                ubah_nama_alasan_kunjungan: ubahNamaAlasanKunjungan,
                tampil_buku_tamu: statusTampil,
            }

            try {
                const response = await fetch(`<?= BASEURL; ?>/bukutamu/validasiUbahAlasanKunjungan`, {
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
                        $('#modalUbahAlasanKunjungan').modal('hide');
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
            getAllAlasanKunjunganBukuTamu(url).then(data => {
                loadDataAlasanKunjungan(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllPosisiton: ${err.statusText || err}`,
                });
            });

            $('#modalAlasanKunjunganBukuTamu, #modalUbahAlasanKunjungan').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalAlasanKunjunganBukuTamu').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
            });

            $('#nama_alasan_kunjungan, #ubah_nama_alasan_kunjungan').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#btnSimpanAlasanKunjungan').on('click', () => {
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
                        simpanDataAlasanKunjungan()
                    }
                });
            });

            $('#btnUbahAlasanKunjungan').on('click', () => {
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
                        ubahDataAlasanKunjungan()
                    }
                });
            });
        })

        const tampilUbahAlasanKunjungan = (data) => {
            $('#kd_alasan_kunjungan_buku_tamu').val(data.kd_alasan_kunjungan_buku_tamu);
            $('#ubah_nama_alasan_kunjungan').val(data.nama_alasan_kunjungan);
            $('#tampil_buku_tamu').val(data.tampil_buku_tamu).trigger('change')
        }

        const loadDataAlasanKunjungan = (data) => {
            $('#tblAlasanKunjungan').dataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama_alasan_kunjungan',
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            let badge;
                            if (row.tampil_buku_tamu === 'YA') {
                                badge = `<span class="badge bg-success">${row.tampil_buku_tamu}</span>`
                            } else {
                                badge = `<span class="badge bg-danger">${row.tampil_buku_tamu}</span>`
                            }
                            return `${badge}`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbahAlasanKunjungan(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahAlasanKunjungan"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick=''></i>
                            </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblAlasanKunjungan tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblAlasanKunjungan tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        }
    </script>