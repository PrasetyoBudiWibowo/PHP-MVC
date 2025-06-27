<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mb-4 mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-table me-1"></i><?= $data['judul']; ?></span>
                    <span id="realTimeClock"></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalTambahModule">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblModule" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>Nama Module</td>
                                            <td>Status Module</td>
                                            <td>Aksi</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalTambahModule" tabindex="-1" aria-labelledby="modalTambahModuleLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahModuleLabel">Tambah Module</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                            <div class="mb-3">
                                <label for="nama_module" class="form-label">Nama Module</label>
                                <input type="text" autocomplete="off" class="form-control" id="nama_module"
                                    placeholder="Masukkan Nama Module" required>
                            </div>
                            <div class="mb-3">
                                <label for="url_module" class="form-label">Url Module</label>
                                <input type="text" autocomplete="off" class="form-control" id="url_module"
                                    placeholder="" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                <i class="fa-solid fa-xmark"></i> Tutup
                            </button>
                            <button type="button" class="btn btn-primary" id="btnSimpanModule">
                                <i class="fa-solid fa-paper-plane"></i> Simpan Module
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
                url: `<?= BASEURL; ?>/module/allDataModule`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let res = response.data
                        console.log('🔑🔑', res)
                        loadModule(res)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            $('#modalTambahModule').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#nama_module').on('input', function() {
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

            $('#btnSimpanModule').on('click', SimpanModule)
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

        const loadModule = (data) => {
            $('#tblModule').DataTable({
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
                        data: 'nama_module'
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            const badgeClass = row.status_module === "ACTIVE" ? "text-bg-success" : "text-bg-danger";
                            return `<span class="badge ${badgeClass}">${row.status_module}</span>`;
                        }
                    },
                    {
                        data: null,
                        title: "Aksi",
                        render: function(data) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbah(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahProvinsi"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick='tampilTempHapus(${JSON.stringify(data)})'></i>
                            </div>
                        `;
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblModule tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblModule tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                },
            });
        }

        const SimpanModule = () => {
            const csrfToken = $('#_csrf_token').val();
            let namaModule = $('#nama_module').val();
            let urlModule = $('#url_module').val();
            let userInput = $('#kd_asli_user').data('kd_asli_user');

            let dataToSave = {
                csrf_token: csrfToken,
                nama_module: namaModule,
                url_module: urlModule,
                kd_user: userInput,
            }

            showModalNotificaton(
                'Konfirmasi Tambah Kecamatan',
                `Apakah Anda yakin ingin Menyimapan Data Ini ?`,
                function() {
                    console.log('Ya Simpan Buat Baru Module');
                    $.ajax({
                        url: "<?= BASEURL; ?>/module/validasiSimpanModule",
                        method: 'POST',
                        data: dataToSave,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                showModalMessage('Success', response.message, 'success');
                                $('#modalTambahModule').modal('hide');
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