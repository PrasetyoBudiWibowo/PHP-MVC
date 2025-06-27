<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?> <strong id="tampil_nama_karyawan"></strong>
                </div>
                <div class="card-body">
                    <div id="accordion">
                        
                        <div class="card mb-3">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <i class="fas fa-edit btn btn-link collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="false"
                                        aria-controls="collapseOne"></i> Foto karyawan
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                data-bs-parent="#accordion">
                                <div class="card-body">
                                    <div class="mb-3 d-flex align-items-center gap-2">
                                        <img id="img_kry_preview" src="<?= BASEURL; ?>/img/default/Default-Profile.png"
                                            alt="Profile Image" class="img-thumbnail" width="200">
                                        <button class="btn btn-link p-0" onclick="ubahFotoKayawan()">
                                            <i class="fas fa-edit fs-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <i class="fas fa-edit btn btn-link collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo"></i> Data Personal Karyawan
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordion">
                                <div class="card-header">
                                    <button class="btn btn-link p-0" onclick="ubahIdenditasKaryawan()">
                                        <i class="fas fa-edit fs-4"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="d-flex align-items">
                                                Nama Karyawan: <strong id="nama_karyawan"></strong>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items">
                                                Nama Panggilan: <strong id="nama_panggilan_karyawan"></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="d-flex align-items">
                                                Jenis Kelamin: <strong id="gender"></strong>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items">
                                                Tempat & Tanggal Lahir:
                                                <strong id="tempat_lahir" class="mr-3"></strong>
                                                <span class="mx-2"> & </span>
                                                <strong id="tgl_lahir" class="ml-3"></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="d-flex align-items">
                                                Umur: <strong id="umur"></strong> Tahun
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items">
                                                Nama Panggilan: <strong id="nama_panggilan_karyawan"></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="d-flex align-items">
                                                Agama: <strong id="agama"></strong>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items">
                                                Kewarganegaraan: <strong id="kewarganegaraan"></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <i class="fas fa-edit btn btn-link collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo"></i> DOKUMEN KARYAWAN (KTP-KK-SIM)
                                </h5>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <i class="fas fa-edit btn btn-link collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo"></i> DATA KELUARGA
                                </h5>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <i class="fas fa-edit btn btn-link collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo"></i> DATA KARYAWAN
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const getKdkaryawan = urlParams.get('kd_karyawan');
        const getNamaKaryawan = urlParams.get('nama_karyawan');
        let dataKaryawan;


        $(document).ready(function() {
            setLocaleIndonesia();

            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataKaryawan`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        dataKaryawan = res.filter(it => it.kd_karyawan === getKdkaryawan)[0];
                        console.log('asijd==>', dataKaryawan)

                        let tlgLahir = moment(dataKaryawan.tgl_lahir).format('D MMMM YYYY');
                        let umur = moment().diff(moment(dataKaryawan.tgl_lahir), 'years');
                        console.log('ubn', umur)

                        $('#tampil_nama_karyawan').append(dataKaryawan.nama_karyawan)
                        $('#nama_karyawan').append(dataKaryawan.nama_karyawan)
                        $('#nama_panggilan_karyawan').append(dataKaryawan.nama_panggilan_karyawan)
                        $('#gender').append(dataKaryawan.gender)
                        $('#tgl_lahir').append(tlgLahir)
                        $('#tempat_lahir').append(dataKaryawan.KotaKabLahir.nama_kota_kabupaten)
                        $('#umur').append(umur)
                        $('#agama').append(dataKaryawan.agama)
                        $('#kewarganegaraan').append(dataKaryawan.negara.name)

                        if (dataKaryawan.foto_karyawan === "" || dataKaryawan.foto_karyawan === null) {
                            $('#img_kry_preview').attr('src',
                                `<?= BASEURL; ?>/img/default/Default-Profile.png`);
                        } else {
                            $('#img_kry_preview').attr('src',
                                `<?= BASEURL; ?>/img/karyawan/${dataKaryawan.foto_karyawan}.${dataKaryawan.format_gambar}`
                            );
                        }
                    } else {
                        window.location.href = `<?= BASEURL ?>/hrd/master_karyawan`;
                    }
                },
                error: function(xhr) {
                    window.location.href = `<?= BASEURL ?>/hrd/master_karyawan`;
                }
            });


            $("#accordion .btn-link").click(function() {
                let icon = $(this).find("i");
                $("#accordion .btn-link i").removeClass("bi-chevron-up").addClass("bi-chevron-down");
                if (!$(this).hasClass("collapsed")) {
                    icon.removeClass("bi-chevron-down").addClass("bi-chevron-up");
                }
            });
        });

        const ubahFotoKayawan = () => {
            Swal.fire({
                title: 'Konfirmasi',
                html: `Apakah Anda Ingin Ubah Foto Nama : <strong>${getNamaKaryawan}</strong> ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        `<?= BASEURL ?>/hrd/foto_karyawan?kd_karyawan=${getKdkaryawan}&nama_karyawan=${getNamaKaryawan}`;
                }
            });
        }

        const ubahIdenditasKaryawan = () => {
            Swal.fire({
                title: 'Konfirmasi',
                html: `Apakah Anda Ingin Ubah Data pribadi karyawan : <strong>${getNamaKaryawan}</strong> ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        `<?= BASEURL ?>/hrd/data_personal?kd_karyawan=${getKdkaryawan}&nama_karyawan=${getNamaKaryawan}`;
                }
            });
        }
    </script>