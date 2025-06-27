<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mb-4 mt-4">
                <div class="card-header">
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <img id="img_kry_preview" src="<?= BASEURL; ?>/img/default/Default-Profile.png"
                            alt="Profile Image" class="img-thumbnail" width="250">
                    </div>

                    <div class="mb-3">
                        <label for="img_kry" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" id="img_kry" name="img_kry" accept="image/*"
                            required>
                    </div>
                    <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger" id="batalUbahFotoKaryawan">
                        Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="simpanUbahFotoKaryawan">
                        Simpan Foto Karyawan
                    </button>
                </div>

            </div>
        </div>
    </main>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const getKdkaryawan = urlParams.get('kd_karyawan');
        const getNamaKaryawan = urlParams.get('nama_karyawan');

        $(document).ready(function() {
            $("#batalUbahFotoKaryawan").click(function() {
                window.history.go(-1);
            });

            $('#img_kry').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#img_kry_preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#simpanUbahFotoKaryawan').on('click', simpanFotoKaryawan)
        });

        const simpanFotoKaryawan = () => {
            const csrfToken = $('#_csrf_token').val();
            let kdKaryawan = getKdkaryawan;
            let namaKaryawan = getNamaKaryawan;
            let type = 'FOTO';
            let file_gambar = $('#img_kry')[0].files[0];

            let formData = new FormData();
            formData.append('_csrf_token', csrfToken)
            formData.append('kd_karyawan', kdKaryawan)
            formData.append('type', type)
            formData.append('img_kry', file_gambar)

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiUbahDataKaryawan",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        showAlert('success', 'Berhasil', response.message || 'Data berhasil disimpan!',
                            () => {
                                window.location.href = `<?= BASEURL ?>/hrd/edit_data_personal_karyawan?kd_karyawan=${getKdkaryawan}&nama_karyawan=${namaKaryawan}`;
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
            })
        }
    </script>