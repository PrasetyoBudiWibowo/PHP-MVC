<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="row g-1 align-items-left">
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary btn-sm" id="generateFakePengunjung">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                            <div class="col-auto">
                                <input type="text" autocomplete="off" class="form-control form-control-sm"
                                    id="columns_fake_pengunjung" placeholder="Banyak data" required>
                            </div>

                            <div class="col-auto">
                                <select class="form-control" id="tahun_awal" name="tahun_awal">
                                    <option value="">-- PILIH TAHUN --</option>
                                </select>
                            </div>

                            <div class="col-auto">
                                <select class="form-control" id="tahun_akhir" name="tahun_akhir">
                                    <option value="">-- PILIH TAHUN --</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="tblFakePengunjung" class="table table-bordered table-striped display nowrap" style="width:100%" border="1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pengunjung</th>
                                                <th>Tanggal Kunjungan</th>
                                                <th>Waktu Kunjungan</th>
                                                <th>Provinsi</th>
                                                <th>Nama Provinsi</th>
                                                <th>Kota / Kabupaten</th>
                                                <th>Nama Kota / Kabupaten</th>
                                                <th>Kecamatan</th>
                                                <th>Nama Kecamatan</th>
                                                <th>Alasan Kunjungan</th>
                                                <th>Nama Alasan Kunjungan</th>
                                                <th>Sumber Informasi</th>
                                                <th>Nama Sumber Informasi</th>
                                                <th>Sumber Informasi Detail</th>
                                                <th>Nama Sumber Informasi Detail</th>
                                                <th>Sales</th>
                                                <th>Nama Sales</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-success btn-sm" id="btnSimpanFakeBukutamu">
                                    <i class="fas fa-anchor"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <script>
        let thnAwal = "2015";
        let thnAkhir = "2025";
        setLocaleIndonesia();

        const buatDataFakePengunjung = async () => {

            let jumlahData = $('#columns_fake_pengunjung').val().replace(/\./g, '');
            let tahunAwal = $('#tahun_awal').val();
            let tahunAkhir = $('#tahun_akhir').val();

            if (!jumlahData || isNaN(jumlahData) || jumlahData <= 0) {
                validateInput(jumlahData, 'Masukkan jumlah data yang valid!')
                return;
            }

            let dataSend = {
                jumlah_data: jumlahData,
                tahun_awal: tahunAwal,
                tahun_akhir: tahunAkhir
            }

            try {
                Swal.fire({
                    title: 'Sedang Membuat Data...',
                    text: 'Mohon tunggu.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                })

                const response = await fetch(`<?= BASEURL; ?>/Faker/generateFakePengunjungBukuTamu`, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(dataSend)
                });

                const result = await response.json();
                Swal.close();

                if (result.status === "success") {
                    $('#tblFakePengunjung').DataTable().clear().destroy();
                    loadDataFakeBukuTamu(result.data)
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                    });
                }
            } catch (error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan ${error.message}.`,
                });
            }
        }

        const simpanDataDummyBukuTamu = async() => {
            let userInput = $('#kd_asli_user').data('kd_asli_user');
            let tableFakeKunjunganBukuTamu = $('#tblFakePengunjung').DataTable().rows({
                filter: 'applied'
            }).data().toArray();

            let dataToSave = {
                data: tableFakeKunjunganBukuTamu.map((it, ix) => {
                    return {
                        nama_pengunjung: it.nama_pengunjung,
                        kd_master_sales: it.kd_master_sales,
                        kd_provinsi: it.kd_provinsi,
                        kd_kota_kabupaten: it.kd_kota_kabupaten,
                        kd_kecamatan: it.kd_kecamatan,
                        kd_alasan_kunjungan_buku_tamu: it.kd_alasan_kunjungan_buku_tamu,
                        kd_sumber_informasi_buku_tamu: it.kd_sumber_informasi_buku_tamu,
                        kd_sumber_informasi_detail_buku_tamu: it.kd_sumber_informasi_detail_buku_tamu,
                        tgl_kunjungan: it.tgl_kunjungan,
                        bln_kunjungan: moment(it.tgl_kunjungan).format("MM"),
                        thn_kunjungan: moment(it.tgl_kunjungan).format("YYYY"),
                        waktu_kunjungan: it.waktu_kunjungan,
                        kd_user: userInput,
                    }
                })
            }

            if (dataToSave.data.length === 0) {
                validateInput(dataToSend.data, 'Data tidak boleh kosong')
                return;
            }

            try {
                Swal.fire({
                    title: 'Menyimpan data...',
                    text: 'Mohon tunggu.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                })
                
                const response = await fetch(`<?= BASEURL; ?>/faker/simpanDataFakeKunjunganBukuTamu`, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(dataToSave)
                });

                const result = await response.json();
                Swal.close();
                if (result.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message || 'Data berhasil Disimpan!',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message,
                    });
                }
            } catch (error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan ${error.message}.`,
                });
            }
        }

        $(document).ready(function() {
            let dataOpsiTahun = getTahun(thnAwal, thnAkhir)

            loadSelectTahun(dataOpsiTahun)

            defaultSelect2("#tahun_awal", "-- PILIH TAHUN AWAL --");
            defaultSelect2("#tahun_akhir", "-- PILIH TAHUN AKHIR --");

            $('#columns_fake_pengunjung').on('input', function() {
                let raw = $(this).val();
                let formatted = formatNumber(raw);
                $(this).val(formatted);
            });

            $('#generateFakePengunjung').click(function() {
                let jumlahData = $('#columns_fake_pengunjung').val();

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda Yakin Membuat Data Dummy Sebanyak ${jumlahData}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        buatDataFakePengunjung()
                    }
                });
            })

            $('#btnSimpanFakeBukutamu').click(function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda Yakin Menyimpan Data Dummy Ini`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        simpanDataDummyBukuTamu()
                    }
                });
            })
        })

        const loadSelectTahun = (data) => {
            loadSelectOptions('#tahun_awal', data, 'tahun', 'tahun', '-- PILIH TAHUN AWAL --');
            loadSelectOptions('#tahun_akhir', data, 'tahun', 'tahun', '-- PILIH TAHUN AWAL --');
        }

        const loadDataFakeBukuTamu = (data) => {
            $('#tblFakePengunjung').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return makeUppercase(row.nama_pengunjung)
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return moment(row.tgl_kunjungan).format('DD-MMMM-YYYY');
                        }
                    },
                    {
                        data: 'waktu_kunjungan'
                    },
                    {
                        data: 'kd_provinsi'
                    },
                    {
                        data: 'nama_provinsi'
                    },
                    {
                        data: 'kd_kota_kabupaten'
                    },
                    {
                        data: 'nama_kota_kabupaten'
                    },
                    {
                        data: 'kd_kecamatan'
                    },
                    {
                        data: 'nama_kecamatan'
                    },
                    {
                        data: 'kd_alasan_kunjungan_buku_tamu'
                    },
                    {
                        data: 'nama_alasan_kunjungan'
                    },
                    {
                        data: 'kd_sumber_informasi_buku_tamu'
                    },
                    {
                        data: 'nm_sumber_informasi'
                    },
                    {
                        data: 'kd_sumber_informasi_detail_buku_tamu'
                    },
                    {
                        data: 'nm_sumber_informasi_detail'
                    },
                    {
                        data: 'kd_master_sales'
                    },
                    {
                        data: 'nama_sales',
                        render: function(data, type, row) {
                            return makeUppercase(row.nama_sales)
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            `;
                        }
                    }
                ],
                columnDefs: [{
                    targets: [4, 6, 8, 10, 12, 14, 16],
                    visible: false
                }],
                scrollX: true,
                scrollCollapse: true,
                scrollY: 400,
                fixedHeader: true,
                fixedColumns: true,
                fixedColumns: {
                    leftColumns: 2,
                },
                headerCallback: function(thead, data, start, end, display) {
                    $(thead).find('th').css({
                        'white-space': 'nowrap',
                        'overflow': 'hidden',
                        'text-overflow': 'ellipsis'
                    });
                },
                createdRow: function(row, data, dataIndex) {
                    $('td', row).css({
                        'white-space': 'nowrap',
                        'overflow': 'hidden',
                        'text-overflow': 'ellipsis'
                    });
                },
                initComplete: function() {
                    $('#tblFakePengunjung tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblFakePengunjung tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                },
                destroy: true,
            })
        }
    </script>