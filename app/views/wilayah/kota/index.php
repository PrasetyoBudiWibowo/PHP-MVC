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
                            data-bs-target="#modalTambahKotaKabupaten">
                            <i class="fas fa-plus"></i> Tambah Kota Kabupaten
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblKotaKabupaten" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Provinsi</th>
                                            <th>Nama Kota/Kabupaten</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalTambahKotaKabupaten" tabindex="-1" aria-labelledby="modalTambahKotaKabupatenLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKotaKabupatenLabel">Tambah Provinsi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                            <div class="mb-3">
                                <label for="kd_provinsi" class="form-label">Pilih Provinsi</label>
                                <select class="form-control" name="kd_provinsi" id='kd_provinsi' required></select>
                            </div>
                            <div class="mb-3">
                                <label for="nama_kota_kabupaten" class="form-label">Nama Provinsi</label>
                                <input type="text" autocomplete="off" class="form-control" id="nama_kota_kabupaten"
                                    placeholder="Masukkan Nama Kota/Kabupaten" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                <i class="fa-solid fa-xmark"></i> Tutup
                            </button>
                            <button type="button" class="btn btn-primary" id="btnSimpanKotaKabupaten">
                                <i class="fa-solid fa-paper-plane"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalUbahKotaKabupaten" tabindex="-1" aria-labelledby="modalUbahKotaKabupatenLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalUbahKotaKabupatenLabel">Tambah Provinsi <p id="nama_kota"></p>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="token_ubah" id="token_ubah" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                            <input type="hidden" name="kd_kota_kabupaten" id="kd_kota_kabupaten">
                            <div class="mb-3">
                                <label for="ubah_kd_provinsi" class="form-label">Pilih Provinsi</label>
                                <select class="form-control" name="ubah_kd_provinsi" id='ubah_kd_provinsi' required></select>
                            </div>
                            <div class="mb-3">
                                <label for="ubah_nama_kota_kabupaten" class="form-label">Nama Provinsi</label>
                                <input type="text" autocomplete="off" class="form-control" id="ubah_nama_kota_kabupaten"
                                    placeholder="Masukkan Nama Kota/Kabupaten" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                <i class="fa-solid fa-xmark"></i> Tutup
                            </button>
                            <button type="button" class="btn btn-primary" id="btnUbahKotaKabupaten">
                                <i class="fa-solid fa-paper-plane"></i> Simpan
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
                url: `<?= BASEURL; ?>/wilayah/allDataKotaKabupatenWithProvinsi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        loadDataKotaKabupaten(res)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            $.ajax({
                url: `<?= BASEURL; ?>/wilayah/allDataProvinsi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let allProvinsi = response.data
                        loadProvinsi(allProvinsi)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            $('#modalTambahKotaKabupaten').on('shown.bs.modal', function() {
                $('#kd_provinsi').select2({
                    dropdownParent: $('#modalTambahKotaKabupaten'),
                    placeholder: "Pilih Provinsi",
                    allowClear: true
                });
            });

            $('#modalUbahKotaKabupaten').on('shown.bs.modal', function() {
                $('#ubah_kd_provinsi').select2({
                    dropdownParent: $('#modalUbahKotaKabupaten'),
                    placeholder: "Pilih Provinsi",
                    allowClear: true
                });
            });

            $('#modalAlert').click(function() {
                if ($('#modalMessageTitle').text() === 'Success') {
                    $('#modalMessage').modal('hide');
                    location.reload();
                } else {
                    $('#modalMessage').modal('hide');
                }
            });

            $('#modalTambahKotaKabupaten, #modalUbahKotaKabupaten').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#nama_kota_kabupaten, #ubah_nama_kota_kabupaten').on('input', function() {
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


            $('#btnSimpanKotaKabupaten').on('click', simpanDataKotaKabupaten)
            $('#btnUbahKotaKabupaten').on('click', UbahDataKotaKabupaten)
        })

        const loadProvinsi = (data) => {
            const tambahSelect = $('#kd_provinsi');
            const ubahSelect = $('#ubah_kd_provinsi');

            [tambahSelect, ubahSelect].forEach(select => {
                select.empty().append('<option value="" disabled selected>-- Pilih --</option>');
                data.forEach(item => {
                    select.append(`<option value="${item.kd_provinsi}">${item.nama_provinsi}</option>`);
                });
            });
        };

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

        const tampilUbahKotaKabupaten = (data) => {
            $('#kd_kota_kabupaten').val(data.kd_kota_kabupaten);
            $('#ubah_kd_provinsi').val(data.kd_provinsi).trigger('change');
            $('#ubah_nama_kota_kabupaten').val(data.nama_kota_kabupaten);
            $('#modalUbahKotaKabupaten').modal('show');
        }

        const loadDataKotaKabupaten = (data) => {
            $('#tblKotaKabupaten').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'provinsi.nama_provinsi'
                    },
                    {
                        data: 'nama_kota_kabupaten'
                    },
                    {
                        data: null,
                        title: "Aksi",
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbahKotaKabupaten(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahProvinsi"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick='tampilTempHapusKotaKabupaten(${JSON.stringify(data)})'></i>
                            </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblKotaKabupaten tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblKotaKabupaten tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                },
            })
        };

        const simpanDataKotaKabupaten = () => {
            const csrfToken = $('#_csrf_token').val();
            let kdProvinsi = $('#kd_provinsi').val();
            let namaKotaKabupaten = $('#nama_kota_kabupaten').val();
            let userInput = $('#kd_asli_user').data('kd_asli_user');

            let dataToSave = {
                csrf_token: csrfToken,
                kd_provinsi: kdProvinsi,
                nama_kota_kabupaten: namaKotaKabupaten,
                user_input: userInput,
            }

            showModalNotificaton(
                'Konfirmasi Tambah Kota / Kabupaten',
                `Apakah Anda yakin ingin Menyimapan Data Ini ?`,
                function() {
                    console.log('Ya Simpan data kota');
                    $.ajax({
                        url: "<?= BASEURL; ?>/wilayah/validasiSimpanKotaKabupaten",
                        method: 'POST',
                        data: dataToSave,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                showModalMessage('Success', response.message, 'success');
                                $('#modalTambahKotaKabupaten').modal('hide');
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

        };

        const UbahDataKotaKabupaten = () => {
            const csrfToken = $('#token_ubah').val();
            let kdKabupaten = $('#kd_kota_kabupaten').val()
            let kdProvinsi = $('#ubah_kd_provinsi').val()
            let ubahNamaKota = $('#ubah_nama_kota_kabupaten').val()

            let dataToSave = {
                csrf_token: csrfToken,
                type: 'nama',
                kd_kota_kabupaten: kdKabupaten,
                kd_provinsi: kdProvinsi,
                nama_kota_kabupaten: ubahNamaKota,
            }

            showModalNotificaton(
                'Konfirmasi Ubah Kota / Kabupaten',
                `Apakah Anda yakin ingin Mengubah Data ini ?`,
                function() {
                    console.log('Ya Simpan data kota');
                    $.ajax({
                        url: "<?= BASEURL; ?>/wilayah/validasiUbahKotaKabupaten",
                        method: 'POST',
                        data: dataToSave,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                showModalMessage('Success', response.message, 'success');
                                $('#modalTambahKotaKabupaten').modal('hide');
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

        };

        const tampilTempHapusKotaKabupaten = (data) => {
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            let dataToSend = {
                type: 'status',
                kd_kota_kabupaten: data.kd_kota_kabupaten,
                kd_provinsi: data.kd_provinsi,
                kd_user: user_input,
            }

            showModalNotificaton(
                'Konfirmasi Ubah Kota / Kabupaten',
                `Apakah Anda yakin ingin Menghapus kota ${data.nama_kota_kabupaten} ?`,
                function() {
                    console.log('Ya Simpan data kota');
                    $.ajax({
                        url: "<?= BASEURL; ?>/wilayah/validasiTempHapusKotaKabupaten",
                        method: 'POST',
                        data: dataToSend,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                showModalMessage('Success', response.message, 'success');
                                $('#modalTambahKotaKabupaten').modal('hide');
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
        };
    </script>