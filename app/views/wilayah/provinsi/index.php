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
                            data-bs-target="#modalTambahProvinsi">
                            <i class="fas fa-plus"></i> Tambah Provinsi
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tableProvinsi" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Provinsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalTambahProvinsi" tabindex="-1" aria-labelledby="modalTambahProvinsiLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahProvinsiLabel">Tambah Provinsi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                            <div class="mb-3">
                                <label for="nama_provinsi" class="form-label">Nama Provinsi</label>
                                <input type="text" autocomplete="off" class="form-control" id="nama_provinsi"
                                    placeholder="Masukkan Nama Provisi" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                <i class="fa-solid fa-xmark"></i> Tutup
                            </button>
                            <button type="button" class="btn btn-primary" id="btnSimpanProvinsi">
                                <i class="fa-solid fa-paper-plane"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalUbahProvinsi" tabindex="-1" aria-labelledby="modalUbahProvinsiLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalUbahProvinsiLabel">Ubah Provinsi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="token_ubah" id="token_ubah" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                            <input type="hidden" name="kd_provinsi" id="kd_provinsi">
                            <div class="mb-3">
                                <label for="ubah_nama_provinsi" class="form-label">Nama Provinsi</label>
                                <input type="text" autocomplete="off" class="form-control" id="ubah_nama_provinsi"
                                    placeholder="Masukkan Nama Provisi" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                <i class="fa-solid fa-xmark"></i> Tutup
                            </button>
                            <button type="button" class="btn btn-primary" id="btnUbahProvinsi">
                                <i class="fa-solid fa-paper-plane"></i> Ubah Nama Provisi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php require APPROOT . '/views/modalNotification/modalConfirmation.php'; ?>

            <?php require APPROOT . '/views/modalNotification/modalMessage.php'; ?>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: `<?= BASEURL; ?>/wilayah/allDataProvinsi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        loadDataProvinsi(res)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            $('#modalTambahProvinsi, #modalUbahProvinsi').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalTambahProvinsi, #modalUbahProvinsi').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
            });

            $('#nama_provinsi, #ubah_nama_provinsi').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#modalAlert').click(function() {
                if ($('#modalMessageTitle').text() === 'Success') {
                    $('#modalMessage').modal('hide');
                    location.reload();
                } else {
                    $('#modalMessage').modal('hide');
                }
            });

            $('#btnSimpanProvinsi').on('click', simpanDataProvinsi)
            $('#btnUbahProvinsi').on('click', ubahDataProvinsi)
        })

        const showModalMessage = (title, message, type) => {
            $('#modalMessageTitle').text(title);
            $('#modalMessageBody').text(message);
            $('#modalMessage').modal('show');
        };

        const showModalNotificaton = (title, message, onYesCallback, onNoCallback) => {
            $('#modalConfirmationTitle').text(title);
            $('#modalConfirmationBody').text(message);

            $('#modalConfirmationYesButton').off('click').on('click', function() {
                if (typeof onYesCallback === 'function') {
                    onYesCallback();
                }
                $('#modalConfirmation').modal('hide');
            });

            $('#modalConfirmationNoButton').off('click').on('click', function() {
                if (typeof onNoCallback === 'function') {
                    onNoCallback();
                }
                $('#modalMessage').modal('hide');
            });

            $('#modalConfirmation').modal('show');
        };

        const tampilubah = (data) => {
            $('#kd_provinsi').val(data.kd_provinsi);
            $('#ubah_nama_provinsi').val(data.nama_provinsi);
            $('#modalUbahProvinsi').modal('show');
        }

        const tampilTempHapusProvinsi = (data) => {
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            let dataToSend = {
                type: 'status',
                kd_provinsi: data.kd_provinsi,
                kd_user: user_input,
            }

            showModalNotificaton(
                'Konfirmasi Hapus Provinsi',
                `Apakah Anda yakin ingin Menghapus provnisi`,
                function() {
                    console.log('Ya Hapus temp Data provinsi');
                    $.ajax({
                        url: "<?= BASEURL; ?>/wilayah/validasiTempHapusProvinsi",
                        method: 'POST',
                        data: dataToSend,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                showModalMessage('Success', response.message, 'success');
                                $('#modalTambahProvinsi').modal('hide');
                            } else {
                                showModalMessage('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            showModalMessage('Error', xhr.responseText, 'error');
                        }
                    })
                },
                function() {
                    console.log('Tutup Modal Notification');
                }
            )
        }

        const loadDataProvinsi = (data) => {
            $('#tableProvinsi').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama_provinsi',
                    },
                    {
                        data: null,
                        title: "Aksi",
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilubah(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahProvinsi"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick='tampilTempHapusProvinsi(${JSON.stringify(data)})'></i>
                            </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tableProvinsi tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tableProvinsi tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        }

        const simpanDataProvinsi = () => {
            const csrfToken = $('#_csrf_token').val();
            let namaProvinsi = $('#nama_provinsi').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            let dataToSave = {
                csrf_token: csrfToken,
                nama_provinsi: namaProvinsi,
                kd_user: user_input,
            }

            $.ajax({
                url: "<?= BASEURL; ?>/wilayah/validasiSimpanDataProvinsi",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showModalMessage('Success', response.message, 'success');
                        $('#modalTambahProvinsi').modal('hide');
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })
        }

        const ubahDataProvinsi = () => {
            const csrfToken = $('#token_ubah').val();
            let kdProvinsi = $('#kd_provinsi').val()
            let namaProvinsi = $('#ubah_nama_provinsi').val()

            let dataToSave = {
                csrf_token: csrfToken,
                type: 'nama',
                nama_provinsi: namaProvinsi,
                kd_provinsi: kdProvinsi,
            }

            $.ajax({
                url: "<?= BASEURL; ?>/wilayah/valiidasiUbahDataProvinsi",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showModalMessage('Success', response.message, 'success');
                        $('#modalUbahProvinsi').modal('hide');
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })
        }
    </script>