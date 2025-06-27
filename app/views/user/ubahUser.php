<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mb-4 mt-4">
                <div class="card-header">
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
                    <input type="text" name="kd_asli_user" id="kd_asli_user" class="form-control" hidden="hidden">
                    <div class="mb-3">
                        <label for="nama_user" class="form-label">User Name</label>
                        <input type="text" autocomplete="off" class="form-control" id="nama_user"
                            placeholder="Masukkan Nama" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_usr_level" class="form-label">Level User</label>
                        <select class="form-control" name="id_usr_level" id='id_usr_level' required></select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password User</label>
                        <input type="text" autocomplete="off" class="form-control" id="password" required>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label for="status_user" class="form-label">Status Active</label>
                            <select class="form-control" name="status_user" id='status_user' required>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="NON ACTIVE">NON ACTIVE</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="blokir" class="form-label">Status Blokir</label>
                            <select class="form-control" name="blokir" id='blokir' required>
                                <option value="YA">YA</option>
                                <option value="TIDAK">TIDAK</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger" id="batalUbah">
                        Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="simpanUbahData">
                        Simpan Ubah Data User
                    </button>
                </div>

                <?php require APPROOT . '/views/modalNotification/modalMessage.php'; ?>
            </div>
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

            $.ajax({
                url: `<?= BASEURL; ?>/TempTblUser/TempUser`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data.filter((it, ix) => it.user_input === $('#kd_asli_user').data('kd_asli_user'))
                        let value = res[0]
                        dataTempEdit(value)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

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

            $('#simpanUbahData').on('click', saveData);

            $('#modalAlert').click(function() {
                if ($('#modalMessageTitle').text() === 'Success') {
                    $('#modalMessage').modal('hide');
                    // window.location.href = '<?= BASEURL; ?>/user';
                    window.history.back();
                } else {
                    $('#modalMessage').modal('hide');
                }
            });

            $('#batalUbah').click(function() {
                // window.location.href = '<?= BASEURL; ?>/user';
                window.history.back();
            });
        })

        const showModalMessage = (title, message, type) => {
            $('#modalMessageTitle').text(title);
            $('#modalMessageBody').text(message);
            $('#modalMessage').modal('show');
        };

        const loadLevels = (data) => {
            let $select = $('#id_usr_level');
            $select.empty().append('<option value="" disabled selected>-- Pilih --</option>');
            for (let i = 0; i < data.length; i++) {
                let item = data[i];
                $select.append(`<option value="${item.id}">${item.level_user}</option>`);
            }
        };

        const dataTempEdit = (data) => {
            $('#kd_asli_user').val(data.kd_user)
            $('#nama_user').val(data.nama_user)
            $('#id_usr_level').val(data.id_usr_level).trigger('change');
            $('#password').val(data.password)
            if (data.img_user === "" || data.img_user === null) {
                $('#img_user_preview').attr('src', `<?= BASEURL; ?>/img/default/Default-Profile.png`);
            } else {
                $('#img_user_preview').attr('src', `<?= BASEURL; ?>/img/user/${data.img_user}.${data.format_img_user}`);
            }
            $('#status_user').val(data.status_user).trigger('change');
            $('#blokir').val(data.blokir).trigger('change');
        }

        const saveData = () => {
            const csrfToken = $('#_csrf_token').val()
            let kdUser = $('#kd_asli_user').val()
            let namaUser = $('#nama_user').val()
            let idLevel = $('#id_usr_level').val()
            let password = $('#password').val()
            let statusUser = $('#status_user').val()
            let blokir = $('#blokir').val()
            let userInput = $('#kd_asli_user').data('kd_asli_user')
            let file_gambar = $('#img_user')[0].files[0];

            let formData = new FormData();
            formData.append('_csrf_token', csrfToken)
            formData.append('kd_asli_user', kdUser)
            formData.append('nama_user', namaUser)
            formData.append('id_usr_level', idLevel)
            formData.append('password', password)
            formData.append('status_user', statusUser)
            formData.append('blokir', blokir)
            formData.append('user_input', userInput)
            formData.append('img_user', file_gambar)

            $.ajax({
                url: "<?= BASEURL; ?>/user/dataEdit",
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
        }
    </script>