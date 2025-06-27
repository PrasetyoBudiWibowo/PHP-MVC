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
                            data-bs-target="#modalTambahDivisi">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblDivisi" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Divisi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahDivisi" tabindex="-1" aria-labelledby="modalTambahDivisiLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahDivisiLabel">Tambah Divisi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="nama_divisi" class="form-label">Nama Divisi</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nama_divisi"
                                        placeholder="Masukkan Divisi" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanDivisi">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalUbahDivisi" tabindex="-1" aria-labelledby="modalUbahDivisiLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalUbahDivisiLabel">Tambah Divisi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <input type="hidden" name="kd_divisi" id="kd_divisi">
                                <div class="mb-3">
                                    <label for="ubah_nama_divisi" class="form-label">Nama Divisi</label>
                                    <input type="text" autocomplete="off" class="form-control" id="ubah_nama_divisi"
                                        placeholder="Masukkan Divisi" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnUbahDivisi">
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
        $(document).ready(function() {
            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataDivisi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        loadDivisi(res)
                    } else {
                        showAlert('error', 'Gagal', response.message ||
                            'Terjadi kesalahan saat menyimpan data.');
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
                }
            })

            $('#modalTambahDivisi, #modalUbahDivisi').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalTambahDivisi, #modalUbahDivisi').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
            });

            $('#nama_divisi, #ubah_nama_divisi').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#btnSimpanDivisi').on('click', () => {
                showConfirmationDialog(
                    'Konfirmasi',
                    'Apakah Anda yakin ingin menyimpan data ini?',
                    'Ya, Simpan!',
                    'Batal',
                    function() {
                        simpanDataDivisi();
                    }
                );
            });

            $('#btnUbahDivisi').on('click', () => {
                showConfirmationDialog(
                    'Konfirmasi',
                    'Apakah Anda yakin ingin menyimpan data ini?',
                    'Ya, Simpan!',
                    'Batal',
                    function() {
                        ubahDataDivisi();
                    }
                );
            });
        });

        const ubahDivisi = (data) => {
            $('#kd_divisi').val(data.kd_divisi);
            $('#ubah_nama_divisi').val(data.nama_divisi);
            $('#modalUbahDivisi').modal('show');
        };

        const loadDivisi = (data) => {
            $('#tblDivisi').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama_divisi',
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='ubahDivisi(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahDivisi"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick='tampilTempHapusDivisi(${JSON.stringify(data)})'></i>
                            </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblDivisi tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblDivisi tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        };

        const simpanDataDivisi = () => {
            const csrfToken = $('#_csrf_token').val();
            let namaDivisi = $('#nama_divisi').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            if (!validateInput(namaDivisi, 'Nama Divisi tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                nama_divisi: namaDivisi,
                kd_user: user_input,
            };

            console.log('qwdbuo', dataToSave)

            showLoadingAlert({
                title: 'Sedang Menyimpan Data...',
                text: 'Harap tunggu hingga proses selesai.'
            });

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiSimpanDivisi",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.status === 'success') {
                        showAlert('success', 'Berhasil', response.message || 'Data berhasil disimpan!',
                            () => {
                                $('#modalTambahDivisi').modal('hide');
                                location.reload();
                            });
                    } else {
                        showAlert('error', 'Gagal', response.message ||
                            'Terjadi kesalahan saat menyimpan data.');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
                }
            });
        };

        const ubahDataDivisi = () => {
            const csrfToken = $('#_csrf_token').val();
            let namaDivisi = $('#ubah_nama_divisi').val();
            let kdDivisi = $('#kd_divisi').val();

            let dataToSave = {
                csrf_token: csrfToken,
                nama_divisi: namaDivisi,
                kd_divisi: kdDivisi,
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
                url: "<?= BASEURL; ?>/hrd/validasiUbahDataDivisi",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.status === 'success') {
                        showAlert('success', 'Berhasil', response.message || 'Data berhasil disimpan!',
                            () => {
                                $('#modalTambahDivisi').modal('hide');
                                location.reload();
                            });
                    } else {
                        showAlert('error', 'Gagal', response.message ||
                            'Terjadi kesalahan saat menyimpan data.');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
                }
            });
        }
    </script>