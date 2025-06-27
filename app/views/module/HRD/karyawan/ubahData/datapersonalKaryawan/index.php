<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mb-4 mt-4">
                <div class="card-header">
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                    <input type="hidden" name="kd_karyawan" id="kd_karyawan">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                            <input type="text" autocomplete="off" class="form-control" id="nama_karyawan"
                                placeholder="Masukkan Nama Karyawan">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="nama_panggilan_karyawan" class="form-label">Nama Panggilan Karyawan</label>
                            <input type="text" autocomplete="off" class="form-control" id="nama_panggilan_karyawan"
                                placeholder="Masukkan Nama Panggilan Karyawan" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" name="gender" id="gender" class="form-label">
                                <option value="Pria">PRIA</option>
                                <option value="Wanita">WANITA</option>
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="text" autocomplete="off" class="form-control datepicker" id="tgl_lahir" name="tgl_lahir">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="kd_negara" class="form-label">Kewarganegaraan</label>
                            <select class="form-control" name="kd_negara" id='kd_negara' required>
                                <option value="">-- Pilih Negara --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="provinsi_lahir" class="form-label">Provinsi Lahir</label>
                            <select name="provinsi_lahir" id="provinsi_lahir" class="form-select"></select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kota_kab_lahir" class="form-label">Kota/Kabupaten Lahir</label>
                            <select name="kota_kab_lahir" id="kota_kab_lahir" class="form-select" disabled></select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kecamatan_lahir" class="form-label">Kecamatan Lahir</label>
                            <select name="kecamatan_lahir" id="kecamatan_lahir" class="form-select" disabled></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="alamat_lahir" class="form-label">Alamat Detail</label>
                            <textarea class="form-control" id="alamat_lahir" rows="3"></textarea>
                        </div>
                    </div>
                    <hr style="border: 5px solid black;">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="tinggi_karyawan" class="form-label">Tinggi</label>
                            <input type="number" autocomplete="off" class="form-control" id="tinggi_karyawan"
                                placeholder="Masukkan Nama Karyawan">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="bb_karyawan" class="form-label">Berat Badan</label>
                            <input type="number" autocomplete="off" class="form-control" id="bb_karyawan"
                                placeholder="Masukkan Nama Panggilan Karyawan" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="email_pribadi" class="form-label">Email Pribadi</label>
                            <input type="text" autocomplete="off" class="form-control" id="email_pribadi"
                                placeholder="Masukkan Nama Karyawan">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="email_kantor" class="form-label">Email kantor</label>
                            <input type="text" autocomplete="off" class="form-control" id="email_kantor"
                                placeholder="Masukkan Nama Karyawan">
                        </div>
                    </div>

                    <hr style="border: 5px solid black;">
                    <h4>Keluarga atau teman yang dapat di hubungi saat keadaan darurat</h4>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger" id="">
                        Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="btnUbahDataPersonalKaryawan">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </main>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const getKdkaryawan = urlParams.get('kd_karyawan');

        let karyawan = null;
        let allProvinsi = [];
        let allKotaKab = [];
        let allKecamatan = [];

        $(document).ready(function() {
            defaultSelect2("#kd_negara", "-- Pilih Negara --");
            defaultSelect2("#provinsi_lahir", "-- Pilih Provinsi --");
            defaultSelect2("#kota_kab_lahir", "-- Pilih Kota/Kabupaten --");
            defaultSelect2("#kecamatan_lahir", "-- Pilih Kecamatan --");

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom auto"
            });

            // data karyawan
            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataKaryawan`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        const res = response.data;
                        if (getKdkaryawan) {
                            karyawan = res.find(it => it.kd_karyawan === getKdkaryawan);
                            if (karyawan) {
                                $('#kd_karyawan').val(karyawan.kd_karyawan);
                                $('#nama_karyawan').val(karyawan.nama_karyawan);
                                $('#nama_panggilan_karyawan').val(karyawan.nama_panggilan_karyawan);
                                $('#tgl_lahir').val(karyawan.tgl_lahir);
                                $('#gender').val(karyawan.gender);
                                $('#gender').val(karyawan.gender);
                                $('#gender').val(karyawan.gender);

                                $('#kd_negara').val(karyawan.kd_negara).trigger('change');

                                $('#provinsi_lahir').val(karyawan.provinsi_lahir).trigger('change');

                                if (karyawan.provinsi_lahir) {
                                    $('#kota_kab_lahir').prop('disabled', false);
                                } else {
                                    $('#kota_kab_lahir').prop('disabled', true);
                                }


                                $('#kota_kab_lahir').val(karyawan.kota_kab_lahir).trigger('change');

                                if (karyawan.kota_kab_lahir) {
                                    $('#kecamatan_lahir').prop('disabled', false);
                                } else {
                                    $('#kecamatan_lahir').prop('disabled', true);
                                }
                            }
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'KARYAWAN TIDAK DITEMUKAN',
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: `Terjadi kesalahan ${xhr.responseText}.`,
                    });
                }
            });

            // Negara
            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataCountry`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        const res = response.data;
                        loadSelectCountry(res);

                        if (karyawan && karyawan.kd_negara) {
                            $('#kd_negara').val(karyawan.kd_negara).trigger('change');
                        }
                    }
                }
            });

            // Provinsi
            $.ajax({
                url: `<?= BASEURL; ?>/wilayah/allDataProvinsi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        allProvinsi = response.data;
                        optionProvinsi(allProvinsi);

                        if (karyawan && karyawan.provinsi_lahir) {
                            $('#provinsi_lahir').val(karyawan.provinsi_lahir).trigger('change');
                        }
                    }
                }
            });

            // Kota/Kabupaten
            $.ajax({
                url: `<?= BASEURL; ?>/wilayah/allDataKotaKabupaten`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        allKotaKab = response.data;

                        $('#provinsi_lahir').on('change', function() {
                            const selectedProvinsi = $(this).val();
                            const filtered = allKotaKab.filter(item => item.kd_provinsi === selectedProvinsi);
                            optionKotaKabupaten(filtered);

                            if (karyawan && karyawan.kota_kab_lahir) {
                                $('#kota_kab_lahir').val(karyawan.kota_kab_lahir).trigger('change');
                            }
                        });

                        if (karyawan && karyawan.provinsi_lahir) {
                            $('#provinsi_lahir').trigger('change');
                        }
                    }
                }
            });

            // Kecamatan
            $.ajax({
                url: `<?= BASEURL; ?>/wilayah/allDataKecamatan`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        allKecamatan = response.data;

                        $('#kota_kab_lahir').on('change', function() {
                            const selectedKotaKab = $(this).val();
                            const filtered = allKecamatan.filter(item => item.kd_kota_kabupaten === selectedKotaKab);
                            optionKecamatan(filtered);

                            if (karyawan && karyawan.kecamatan_lahir) {
                                $('#kecamatan_lahir').val(karyawan.kecamatan_lahir).trigger('change');
                            }
                        });

                        if (karyawan && karyawan.kota_kab_lahir) {
                            $('#kota_kab_lahir').trigger('change');
                        }
                    }
                }
            })

            $('#provinsi_lahir').on('change', function() {
                let selectedProvinsi = $(this).val();

                if (selectedProvinsi) {
                    $('#kota_kab_lahir').prop('disabled', false);
                    $('#kecamatan_lahir').prop('disabled', false);
                } else {
                    $('#kota_kab_lahir').prop('disabled', true);
                    $('#kecamatan_lahir').prop('disabled', true);
                }
            });

            $('#kota_kab_lahir').on('change', function() {
                let selectedKota = $(this).val();

                if (selectedKota) {
                    $('#kecamatan_lahir').prop('disabled', false);
                } else {
                    $('#kecamatan_lahir').prop('disabled', true);
                }
            });

            $('#btnUbahDataPersonalKaryawan').on('click', UbahDataPersonalKaryawan);


        });

        const loadSelectCountry = (data) => {
            const select = $('#kd_negara');
            select.empty().append('<option value="" disabled selected>-- Pilih --</option>');
            data.forEach(item => {
                select.append(`<option value="${item.kd_negara}">${item.name}</option>`);
            });
        };

        const optionProvinsi = (data) => {
            const select = $('#provinsi_lahir');
            select.empty().append('<option value="" disabled selected>-- Pilih Provinsi --</option>');
            data.forEach(item => {
                select.append(`<option value="${item.kd_provinsi}">${item.nama_provinsi}</option>`);
            });
        };

        const optionKotaKabupaten = (data) => {
            const select = $('#kota_kab_lahir');
            select.empty().append('<option value="" disabled selected>-- Pilih Kota/Kabupaten --</option>');
            data.forEach(item => {
                select.append(`<option value="${item.kd_kota_kabupaten}">${item.nama_kota_kabupaten}</option>`);
            });
        };

        const optionKecamatan = (data) => {
            const select = $('#kecamatan_lahir');
            select.empty().append('<option value="" disabled selected>-- Pilih Kecamatan --</option>');
            data.forEach(item => {
                select.append(`<option value="${item.kd_kecamatan}">${item.nama_kecamatan}</option>`);
            });
        };

        const UbahDataPersonalKaryawan = () => {
            // console.log("tglLahir:", $('#tgl_lahir').val());

            const csrfToken = $('#_csrf_token').val();
            let kdKaryawan = getKdkaryawan;
            let namaKaryawan = $('#nama_karyawan').val();
            let namaPanggilan = $('#nama_panggilan_karyawan').val();
            let gender = $('#gender').val();
            let tglLahir = $('#tgl_lahir').val()
            let negara = $('#kd_negara').val();
            let provinsiLahir = $('#provinsi_lahir').val();
            let kotaLahir = $('#kota_kab_lahir').val();
            let kecamatan = $('#kecamatan_lahir').val();
            let alamatLahir = $('#alamat_lahir').val();

            let blnLahir = moment(tglLahir, "YYYY-MM-DD").format("MM");
            let thnLahir = moment(tglLahir, "YYYY-MM-DD").format("YYYY");

            let tinggiKaryawan = $('#tinggi_karyawan').val();
            let beratKaryawan = $('#bb_karyawan').val();

            let type = 'PERSONAL KARYAWAN';

            let dataToSave = {
                csrf_token: csrfToken,
                kd_karyawan: kdKaryawan,
                nama_karyawan: namaKaryawan,
                nama_panggilan_karyawan: namaPanggilan,
                gender: gender,
                tgl_lahir: $('#tgl_lahir').val(),
                bln_lahir: blnLahir,
                thn_lahir: thnLahir,
                kd_negara: negara,
                provinsi_lahir: provinsiLahir,
                kota_kab_lahir: kotaLahir,
                kecamatan_lahir: kecamatan,
                alamat_lahir: alamatLahir,
                tinggi_karyawan: tinggiKaryawan,
                berat_karyawan: beratKaryawan,
                type: type
            }

            // console.log('sdbu', dataToSave)

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiUbahDataKaryawan",
                method: 'POST',
                data: dataToSave,
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