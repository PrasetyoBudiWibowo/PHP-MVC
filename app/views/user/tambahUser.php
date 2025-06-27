<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <img id="img_user_preview" src="<?= BASEURL; ?>/img/default/Default-Profile.png"
                            alt="Profile Image" class="img-thumbnail" width="250">
                    </div>

                    <div class="mb-3">
                        <label for="img_user" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" id="img_user" name="img_user" accept="image/*"
                            required>
                    </div>
                    <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="nama_user" class="form-label">User Name</label>
                            <input type="text" autocomplete="off" class="form-control" id="nama_user"
                                placeholder="Masukkan Nama" required>
                        </div>
                        <div class="col-6">
                            <label for="id_usr_level" class="form-label">Level User</label>
                            <select class="form-control" name="id_usr_level" id='id_usr_level' required></select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password User</label>
                        <input type="text" autocomplete="off" class="form-control" id="password" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="btnSimpanTambahUser">
                        Simpan Ubah Data User
                    </button>
                </div>
            </div>

            <?php require APPROOT . '/views/modalNotification/modalConfirmation.php'; ?>

            <?php require APPROOT . '/views/modalNotification/modalMessage.php'; ?>

        </div>
    </main>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: `<?= BASEURL; ?>/LevelUser/level`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        loadLevels(res)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            $('#nama_user').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#id_usr_level').select2({
                placeholder: "Pilih Level User",
                allowClear: true,
                width: '100%'
            });

            $('#img_user').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#img_user_preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#modalAlert').click(function() {
                if ($('#modalMessageTitle').text() === 'Success') {
                    $('#modalMessage').modal('hide');
                    window.location.href = '<?= BASEURL; ?>/user';
                } else {
                    $('#modalMessage').modal('hide');
                }
            });

            $('#btnSimpanTambahUser').on('click', simpanTambahUser);
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

        const loadLevels = (data) => {
            let $select = $('#id_usr_level');
            $select.empty().append('<option value="" disabled selected>-- Pilih --</option>');
            for (let i = 0; i < data.length; i++) {
                let item = data[i];
                $select.append(`<option value="${item.id}">${item.level_user}</option>`);
            }
        };

        const simpanTambahUser = () => {
            const csrfToken = $('#_csrf_token').val()
            let kdUser = null
            let namaUser = $('#nama_user').val()
            let idLevel = $('#id_usr_level').val()
            let password = $('#password').val()
            let userInput = $('#kd_asli_user').data('kd_asli_user')
            let file_gambar = $('#img_user')[0].files[0];

            let formData = new FormData();
            formData.append('_csrf_token', csrfToken)
            formData.append('kd_asli_user', kdUser)
            formData.append('nama_user', namaUser)
            formData.append('id_usr_level', idLevel)
            formData.append('password', password)
            formData.append('user_input', userInput)
            formData.append('img_user', file_gambar)

            showModalNotificaton(
                'Konfirmasi Tambah User',
                'Apakah Anda yakin ingin Menambah User',
                function() {
                    console.log('ya simpan data');
                    $.ajax({
                        url: "<?= BASEURL; ?>/user/validasiTambahUser",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
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
        }
    </script>