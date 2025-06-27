<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="col-12 mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahKecamatan">
                            <i class="fas fa-plus"></i> Tambah Kecamatan
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="filterProvinsi" class="form-label">Pilih Provinsi</label>
                            <select id="filterProvinsi" class="form-select">
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterKota" class="form-label">Pilih Kota/Kabupaten</label>
                            <select id="filterKota" class="form-select" disabled>
                                <option value="">-- Pilih Kota/Kabupaten --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button id="btnSearch" class="btn btn-success"><i class="fas fa-search"></i> Cari</button>
                            <button id="btnReset" class="btn btn-danger"><i class="fas fa-sync-alt"></i> Reset</button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblKecamatan" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Provinsi</th>
                                            <th>Nama Kota/Kabupaten</th>
                                            <th>Nama Kecamatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalTambahKecamatan" tabindex="-1" aria-labelledby="modalTambahKecamatanLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKecamatanLabel">Tambah Kecamatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                            <div class="mb-3">
                                <label for="selectProvKota" class="form-label">Pilih Provinsi dan Kota</label>
                                <select class="form-control" name="selectProvKota" id='selectProvKota' required></select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="select_provinsi" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" name="select_provinsi" id="select_provinsi" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="select_kota_kabupaten" class="form-label">Kota/Kabupaten</label>
                                    <input type="text" class="form-control" name="select_kota_kabupaten" id="select_kota_kabupaten" disabled>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="nama_kecamatan" class="form-label">Nama Kecamatan</label>
                                <input type="text" autocomplete="off" class="form-control" id="nama_kecamatan"
                                    placeholder="Masukkan Nama Kota/Kabupaten" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                <i class="fa-solid fa-xmark"></i> Tutup
                            </button>
                            <button type="button" class="btn btn-primary" id="btnSimpanKecamatan">
                                <i class="fa-solid fa-paper-plane"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalUbahKecamatan" tabindex="-1" aria-labelledby="modalUbahKecamatanLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKecamatanLabel">Ubah Kecamatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="token_ubah" id="token_ubah" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                            <input type="hidden" name="kd_kecamatan" id="kd_kecamatan">
                            <div class="mb-3">
                                <label for="ubah_selectProvKota" class="form-label">Pilih Provinsi dan Kota</label>
                                <select class="form-control" name="ubah_selectProvKota" id='ubah_selectProvKota' required></select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ubah_select_provinsi" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" name="ubah_select_provinsi" id="ubah_select_provinsi" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ubah_select_kota_kabupaten" class="form-label">Kota/Kabupaten</label>
                                    <input type="text" class="form-control" name="ubah_select_kota_kabupaten" id="ubah_select_kota_kabupaten" disabled>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="ubah_nama_kecamatan" class="form-label">Nama Kecamatan</label>
                                <input type="text" autocomplete="off" class="form-control" id="ubah_nama_kecamatan"
                                    placeholder="Masukkan Nama Kota/Kabupaten" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                <i class="fa-solid fa-xmark"></i> Tutup
                            </button>
                            <button type="button" class="btn btn-primary" id="btnUbahKecamatan">
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
        let kecamatanData = [];
        let selectProvKota, ubahSelectProvKota;

        $(document).ready(function() {
            loadDataAndFilters();

            selectProvKota = $('#selectProvKota');
            let selectProvinsi = $('#select_provinsi');
            let selectKota = $('#select_kota_kabupaten');

            ubahSelectProvKota = $('#ubah_selectProvKota');

            $('#filterProvinsi').select2({
                placeholder: "-- Pilih Provinsi --",
                allowClear: true,
                width: '100%'
            });

            $('#filterKota').select2({
                placeholder: "-- Pilih Kota/Kabupaten --",
                allowClear: true,
                width: '100%'
            });

            $('#modalTambahKecamatan, #modalUbahKecamatan').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalTambahKecamatan').on('shown.bs.modal', function() {
                $('#selectProvKota').select2({
                    dropdownParent: $('#modalTambahKecamatan'),
                    placeholder: "-- Pilih Provinsi dan Kota --",
                    allowClear: true,
                    width: '100%'
                });
            });

            $('#modalUbahKecamatan').on('shown.bs.modal', function() {
                $('#ubah_selectProvKota').select2({
                    dropdownParent: $('#modalUbahKecamatan'),
                    placeholder: "-- Pilih Provinsi dan Kota --",
                    allowClear: true,
                    width: '100%'
                });
            });

            $('#modalTambahKecamatan, #modalUbahKecamatan').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $(this).find('select').val(null).trigger('change');
            });

            $('#nama_kecamatan, #ubah_nama_kecamatan').on('input', function() {
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

            $('#btnSearch').on('click', function() {
                let provinsi = $('#filterProvinsi').val();
                let kota = $('#filterKota').val();

                let filteredData = kecamatanData.filter(item => {
                    let matchProvinsi = provinsi ? item.kota_kabupaten.provinsi.kd_provinsi === provinsi : true;
                    let matchKota = kota ? item.kota_kabupaten.kd_kota_kabupaten === kota : true;
                    return matchProvinsi && matchKota;
                });

                loadKecamatan(filteredData);
            });

            $('#btnReset').on('click', function() {
                $('#filterProvinsi').val('');
                $('#filterKota').html('<option value="">-- Pilih Kota/Kabupaten --</option>').prop('disabled', true);
                loadKecamatan(kecamatanData);
            });

            $('#filterProvinsi').on('change', function() {
                let selectedProvinsi = $(this).val();
                $('#filterKota').html('<option value="">-- Pilih Kota/Kabupaten --</option>').prop('disabled', true);

                if (selectedProvinsi) {
                    let filteredKota = kecamatanData.filter(item =>
                        item.kota_kabupaten.provinsi.kd_provinsi === selectedProvinsi
                    );

                    let kotaList = filteredKota.map(item => {
                        return {
                            kd_kota_kabupaten: item.kota_kabupaten.kd_kota_kabupaten,
                            nama_kota_kabupaten: item.kota_kabupaten.nama_kota_kabupaten
                        };
                    }).reduce((acc, curr) => {
                        if (!acc.some(kota => kota.kd_kota_kabupaten === curr.kd_kota_kabupaten)) {
                            acc.push(curr);
                        }
                        return acc;
                    }, []);

                    for (let kota of kotaList) {
                        $('#filterKota').append(`<option value="${kota.kd_kota_kabupaten}">${kota.nama_kota_kabupaten}</option>`);
                    }

                    $('#filterKota').prop('disabled', false);
                }
            });

            $.ajax({
                url: `<?= BASEURL; ?>/wilayah/allDataKotaKabupatenWithProvinsi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let resProvKota = response.data
                        populateSelect(resProvKota);
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            selectProvKota.on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue) {
                    const [kdProvinsi, kdKotaKabupaten] = selectedValue.split('|');
                    const selectedText = selectProvKota.find('option:selected').text();
                    const [namaProvinsi, namaKotaKabupaten] = selectedText.split(' - ');

                    selectProvinsi.val(namaProvinsi);
                    selectKota.val(namaKotaKabupaten);
                } else {
                    selectProvinsi.val('');
                    selectKota.val('');
                }
            });

            ubahSelectProvKota.on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue) {
                    const [kdProvinsi, kdKotaKabupaten] = selectedValue.split('|');
                    const selectedText = $(this).find('option:selected').text();
                    const [namaProvinsi, namaKotaKabupaten] = selectedText.split(' - ');
                    $('#ubah_select_provinsi').val(namaProvinsi);
                    $('#ubah_select_kota_kabupaten').val(namaKotaKabupaten);
                } else {
                    $('#ubah_select_provinsi').val('');
                    $('#ubah_select_kota_kabupaten').val('');
                }

            });

            $('#btnSimpanKecamatan').on('click', simpanDataKecamatan);
            $('#btnUbahKecamatan').on('click', ubahDataKecamatan);
        });

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

        const populateSelect = (data) => {
            selectProvKota.empty().append('<option value="" disabled selected>Pilih Provinsi dan Kota</option>');
            ubahSelectProvKota.empty().append('<option value="" disabled selected>Pilih Provinsi dan Kota</option>');

            for (let i = 0; i < data.length; i++) {
                const value = `${data[i].provinsi.kd_provinsi}|${data[i].kd_kota_kabupaten}`;
                const text = `${data[i].provinsi.nama_provinsi} - ${data[i].nama_kota_kabupaten}`;
                selectProvKota.append(`<option value="${value}">${text}</option>`);
                ubahSelectProvKota.append(`<option value="${value}">${text}</option>`);
            }
        };


        const loadDataAndFilters = () => {
            $.ajax({
                url: `<?= BASEURL; ?>/wilayah/allDataKecamatanWithKabKotaWithProvinsi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        kecamatanData = response.data;

                        let provinsiList = kecamatanData.map(item => {
                            return {
                                kd_provinsi: item.kota_kabupaten.provinsi.kd_provinsi,
                                nama_provinsi: item.kota_kabupaten.provinsi.nama_provinsi
                            };
                        }).reduce((acc, curr) => {
                            if (!acc.some(provinsi => provinsi.kd_provinsi === curr.kd_provinsi)) {
                                acc.push(curr);
                            }
                            return acc;
                        }, []);


                        for (const provinsi of provinsiList) {
                            $('#filterProvinsi').append(`<option value="${provinsi.kd_provinsi}">${provinsi.nama_provinsi}</option>`);
                        }

                        loadKecamatan(kecamatanData);
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            });
        };

        const tampilUbahKecamatan = (data) => {
            let valueUbah = `${data.kota_kabupaten.provinsi.kd_provinsi}|${data.kota_kabupaten.kd_kota_kabupaten}`

            $('#kd_kecamatan').val(data.kd_kecamatan);
            $('#ubah_selectProvKota').val(valueUbah).trigger('change');
            $('#ubah_nama_kecamatan').val(data.nama_kecamatan);
            $('#modalUbahKecamatan').modal('show');
        }

        const loadKecamatan = (data) => {
            $('#tblKecamatan').DataTable({
                destroy: true,
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'kota_kabupaten.provinsi.nama_provinsi'
                    },
                    {
                        data: 'kota_kabupaten.nama_kota_kabupaten'
                    },
                    {
                        data: 'nama_kecamatan'
                    },
                    {
                        data: null,
                        title: "Aksi",
                        render: function(data) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbahKecamatan(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahProvinsi"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick='tampilTempHapusKotaKabupaten(${JSON.stringify(data)})'></i>
                            </div>
                        `;
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblKecamatan tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblKecamatan tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                },
            });
        };

        const simpanDataKecamatan = () => {
            const csrfToken = $('#_csrf_token').val();
            let userInput = $('#kd_asli_user').data('kd_asli_user');
            let kdProvKot = $('#selectProvKota').val();
            const [kdProvinsiSelect, kdKotaKabupatenSelect] = kdProvKot.split('|');
            let kdProvinsi = kdProvinsiSelect;
            let kdKotaKabupaten = kdKotaKabupatenSelect;
            let namaKecamatan = $('#nama_kecamatan').val();

            let dataToSave = {
                csrf_token: csrfToken,
                kd_provinsi: kdProvinsi,
                kd_kota_kabupaten: kdKotaKabupaten,
                nama_kecamatan: namaKecamatan,
                kd_user: userInput,
            }

            showModalNotificaton(
                'Konfirmasi Tambah Kecamatan',
                `Apakah Anda yakin ingin Menyimapan Data Ini ?`,
                function() {
                    console.log('Ya Simpan data Kecamatan');
                    $.ajax({
                        url: "<?= BASEURL; ?>/wilayah/validasiSimpanKecamatan",
                        method: 'POST',
                        data: dataToSave,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                showModalMessage('Success', response.message, 'success');
                                $('#modalTambahKecamatan').modal('hide');
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

        const ubahDataKecamatan = () => {
            const csrfToken = $('#token_ubah').val();
            let userInput = $('#kd_asli_user').data('kd_asli_user');
            let kdProvKot = $('#ubah_selectProvKota').val();
            const [kdProvinsiSelect, kdKotaKabupatenSelect] = kdProvKot.split('|');
            let kdProvinsi = kdProvinsiSelect;
            let kdKotaKabupaten = kdKotaKabupatenSelect;
            let kdKecamatan = $('#kd_kecamatan').val();
            let namaKecamatan = $('#ubah_nama_kecamatan').val();

            let dataToSave = {
                csrf_token: csrfToken,
                kd_kota_kabupaten: kdKotaKabupaten,
                kd_kecamatan: kdKecamatan,
                nama_kecamatan: namaKecamatan,
            }

            showModalNotificaton(
                'Konfirmasi Ubah Kecamatan',
                `Apakah Anda yakin ingin Merubah Data Ini ?`,
                function() {
                    console.log('Ya Simpan data Kecamatan');
                    $.ajax({
                        url: "<?= BASEURL; ?>/wilayah/validasiUbhaKecamatan",
                        method: 'POST',
                        data: dataToSave,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                showModalMessage('Success', response.message, 'success');
                                $('#modalUbahKecamatan').modal('hide');
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
    </script>