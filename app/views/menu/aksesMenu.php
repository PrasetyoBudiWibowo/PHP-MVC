<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mb-4 mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-table me-1"></i>Akses Menu</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kd_user" class="form-label">Pilih User</label>
                            <select class="form-select" name="kd_user" id="kd_user" required>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kode_module" class="form-label">Pilih Module</label>
                            <select class="form-select" name="kode_module" id="kode_module" required>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" id="addAccessMenu">
                                <i class="fas fa-plus"></i> Tambah Akses Menu
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                    <div class="row d-none" id="addAcess">
                        <div class="col-12">
                            <table class="table table-bordered" id="tblAksesModule">
                                <thead>
                                    <tr>
                                        <th>Kode User</th>
                                        <th>User</th>
                                        <th>Kode Module</th>
                                        <th>Module</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="btnSimpanAksesModule" disabled>
                        Simpan Akses User
                    </button>
                </div>
            </div>

            <?php require APPROOT . '/views/modalNotification/modalConfirmation.php'; ?>
            <?php require APPROOT . '/views/modalNotification/modalMessage.php'; ?>
        </div>
    </main>
    <script>
        let selectedItems = [];

        $(document).ready(function() {
            initSelect2('#kd_user', 'Pilih User');
            initSelect2('#kode_module', 'Pilih Module');

            let table = $('#tblAksesModule').DataTable({
                "responsive": true,
                "paging": true,
                "searching": false,
                "columnDefs": [{
                    "targets": [0, 2],
                    "visible": false
                }]
            });

            $.ajax({
                url: `<?= BASEURL; ?>/module/allDataModule`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let res = response.data
                        loadSelectModule(res)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            $.ajax({
                url: `<?= BASEURL; ?>/user/allDataUser`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let res = response.data.filter(it => it.id_usr_level !== '1');
                        loadSelectUser(res)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            $('#addAccessMenu').click(function() {
                let selectedUser = $('#kd_user').val();
                let selectedModule = $('#kode_module').val();

                if (selectedUser && selectedModule) {
                    let userText = $('#kd_user option:selected').text();
                    let moduleText = $('#kode_module option:selected').text();

                    let itemIdentifier = `${selectedUser}-${selectedModule}`;
                    if (selectedItems.includes(itemIdentifier)) {
                        showModalMessage('Error', 'User dan Module yang dipilih sudah ada', 'error');
                        return;
                    }

                    table.row.add([
                        selectedUser,
                        userText,
                        selectedModule,
                        moduleText,
                        '<button class="btn btn-danger btn-sm delete-btn"><i class="fa-solid fa-trash"></i></button>'
                    ]).draw();

                    selectedItems.push(itemIdentifier);

                    $('#addAcess').removeClass('d-none');
                    $('#btnSimpanAksesModule').removeClass('disabled');
                } else {
                    showModalMessage('Error', 'Pilih user dan module terlebih dahulu', 'error');
                }

                updateSaveButtonState();
            });

            $('#tblAksesModule tbody').on('click', '.delete-btn', function() {
                let row = table.row($(this).parents('tr'));
                let data = row.data();
                let itemIdentifier = `${data[0]}-${data[2]}`;

                selectedItems = selectedItems.filter(item => item !== itemIdentifier);

                row.remove().draw();
                updateSaveButtonState();
            });

            const updateSaveButtonState = () => {
                if (table.data().count() === 0) {
                    $('#addAcess').addClass('d-none');
                    $('#btnSimpanAksesModule').prop('disabled', true);
                } else {
                    $('#addAcess').removeClass('d-none');
                    $('#btnSimpanAksesModule').prop('disabled', false);
                }
            }

            $('#modalAlert').click(function() {
                if ($('#modalMessageTitle').text() === 'Success') {
                    $('#modalMessage').modal('hide');
                    location.reload();
                } else {
                    $('#modalMessage').modal('hide');
                }
            });

            $('#btnSimpanAksesModule').click(function() {
                let tableData = table.rows().data().toArray();
                const csrfToken = $('#_csrf_token').val();
                let userInput = $('#kd_asli_user').data('kd_asli_user');
                let dataToSend = {
                    csrf_token: csrfToken,
                    data: tableData.map((it, ix) => ({
                        kd_user: it[0],
                        kd_module: it[2],
                        user_input: userInput
                    }))
                };

                showModalNotificaton(
                    'Konfirmasi Simpan Datga Excel Kecamatan',
                    `Apakah Anda yakin ingin menyimpan data ini?`,
                    function() {
                        console.log('Ya simpan data Excel Kecamatan');
                        $.ajax({
                            url: "<?= BASEURL; ?>/module/validasiSimpanAksesModuleUser",
                            method: 'POST',
                            data: JSON.stringify(dataToSend),
                            contentType: 'application/json',
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    showModalMessage('Success', response.message, 'success');
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

            })
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

        const initSelect2 = (selector, placeholder) => {
            $(selector).select2({
                placeholder: placeholder,
                allowClear: true
            });
        }

        const loadSelectUser = (listUser) => {
            let $select = $('#kd_user');
            $select.empty().append('<option value="" disabled selected>-- Pilih --</option>');

            for (let i = 0; i < listUser.length; i++) {
                let item = listUser[i];
                $select.append(`<option value="${item.kd_asli_user}">${item.nama_user}</option>`);
            }
        };

        const loadSelectModule = (listModule) => {
            let $select = $('#kode_module');

            $select.empty().append('<option value="" disabled selected>-- Pilih --</option>');

            for (let i = 0; i < listModule.length; i++) {
                let item = listModule[i];
                $select.append(`<option value="${item.kd_module}">${item.nama_module}</option>`);
            }
        };
    </script>