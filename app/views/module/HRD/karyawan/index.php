<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-12">
                            <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                            <div class="table-responsive">
                                <table id="tblKaryawan" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            <th>Nama Karyawan</th>
                                            <th>Gender</th>
                                            <th>Penempatan</th>
                                            <th>Lama Bekerja & kontrak</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        let dataKaryawan = [];
        $(document).ready(function() {
            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataKaryawan`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        console.log('data', response.data)
                        let res = response.data
                        dataKaryawan = res

                        $('#tblKaryawan').DataTable().clear().destroy();
                        loadDataKaryawan(dataKaryawan)
                    } else {
                        showAlert('error', 'Gagal', response.message ||
                            'Terjadi kesalahan saat menyimpan data.');
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
                }
            })
        });

        const tampilEditKontrakKerja = (data) => {
            Swal.fire({
                title: 'Konfirmasi',
                html: `Apakah Anda Ingin Mengubah Kontrak Kerja Atas Nama : <strong>${data.nama_karyawan}</strong> ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    editkontrakKerja(data);
                }
            });
        };

        const tampilPersonalKaryawan = (data) => {
            Swal.fire({
                title: 'Konfirmasi',
                html: `Apakah Anda Ingin Edit Data Personal Atas Nama : <strong>${data.nama_karyawan}</strong> ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    editPersonalKaryawan(data);
                }
            });
        };

        const editkontrakKerja = (data) => {
            const csrfToken = $('#_csrf_token').val();
            let kdKaryawan = data.kd_karyawan;
            let namaKaryawan = data.nama_karyawan;

            let dataToSend = {
                csrf_token: csrfToken,
                kd_karyawan: kdKaryawan,
            }

            Swal.fire({
                title: 'Sedang Pengecakan Data Harap Tunggu',
                text: 'Harap tunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiDataKontrakKaryawan",
                method: 'POST',
                data: dataToSend,
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        window.location.href = `<?= BASEURL ?>/hrd/edit_kontrak_karyawan?kd_karyawan=${kdKaryawan}&nama_karyawan=${namaKaryawan}`;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: `Terjadi kesalahan ${xhr.responseText}.`,
                    });
                }
            });
        };

        const editPersonalKaryawan = (data) => {
            const csrfToken = $('#_csrf_token').val();
            let kdKaryawan = data.kd_karyawan;
            let namaKaryawan = data.nama_karyawan;

            let dataToSend = {
                csrf_token: csrfToken,
                kd_karyawan: kdKaryawan,
            }

            Swal.fire({
                title: 'Sedang Pengecakan Data Harap Tunggu',
                text: 'Harap tunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiDataPersonalKaryawan",
                method: 'POST',
                data: dataToSend,
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        window.location.href = `<?= BASEURL ?>/hrd/edit_data_personal_karyawan?kd_karyawan=${kdKaryawan}&nama_karyawan=${namaKaryawan}`;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: `Terjadi kesalahan ${xhr.responseText}.`,
                    });
                }
            });
        }

        const loadDataKaryawan = (data) => {
            $('#tblKaryawan').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            if (row.foto_karyawan) {
                                return `<img src="<?= BASEURL; ?>/img/karyawan/${row.foto_karyawan}.${row.format_gambar}" alt="" style="width: 90px; height: 90px; border-radius: 50%;">`;
                            } else {
                                return `<img src="<?= BASEURL; ?>/img/default/Default-Profile.png" alt="Default Image" style="width: 90px; height: 90px; border-radius: 50%;">`;
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                                <div>
                                    <strong>Nama Lengkap :</strong><br>
                                    ${row.nama_karyawan}<br>
                                    <strong>Nama Panggilan :</strong><br>
                                    ${row.nama_panggilan_karyawan}
                                </div>
                            `
                        }
                    },
                    {
                        data: 'gender'
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                                <div>
                                    <strong>Nama Divisi :</strong><br>
                                    ${row.divisi.nama_divisi}<br>
                                    <strong>Nama Departement :</strong><br>
                                    ${row.departement.nama_departement}<br>
                                    <strong>Posisi :</strong><br>
                                    ${row.posisi.nama_position}<br>
                                </div>
                            `
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            let lamaBekerja = moment().diff(moment(row.tgl_bergabung), 'years');
                            let awalKontrak = moment(row.tgl_awal_kontrak).format('DD-MMMM-YYYY');
                            let akhirKontrak = moment(row.tgl_akhir_kontrak).format('DD-MMMM-YYYY');
                            return `
                                <div>
                                    <strong>Lama Bekerja :</strong><br>
                                    ${lamaBekerja} Tahun<br>
                                    <strong>Tanggal Awal Kontrak :</strong><br>
                                    ${awalKontrak}<br>
                                    <strong>Tanggal Akhir Kontrak :</strong><br>
                                    ${akhirKontrak}<br>
                                </div>
                            `
                        }
                    },
                    {
                        data: null,
                        title: "Aksi",
                        render: function(data, type, row, meta) {
                            return `
                                <div class="d-flex flex-wrap gap-2" style="width: 100px;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-edit btn btn-warning btn-sm" onclick='tampilPersonalKaryawan(${JSON.stringify(data)})'></i>
                                        <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                        <i class="fas fa-trash btn btn-danger btn-sm" onclick=''></i>
                                    </div>
                                    <div class="d-flex align-items-center">

                                        <!-- dasknk edit kontrak kerja -->
                                        <a class="btn btn-success btn-sm d-flex align-items-center" onclick='tampilEditKontrakKerja(${JSON.stringify(data)})'>
                                            <i class="fas fa-file-signature"></i>
                                        </a>
                                        
                                        <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                        <i class="fas fa-suitcase btn btn-secondary btn-sm"></i>
                                    </div>
                                </div>
                            `
                        }
                    }
                ],
                scrollX: true,
                scrollCollapse: true,
                scrollY: 400,
                fixedHeader: true,
                fixedColumns: {
                    start: 2,
                },
                headerCallback: function(thead, data, start, end, display) {
                    $(thead).find('th').css({
                        'white-space': 'nowrap',
                        'overflow': 'hidden',
                        'text-overflow': 'ellipsis'
                    });
                },
                initComplete: function() {
                    $('#tblKaryawan tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblKaryawan tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                },
            })
        };
    </script>