<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>

                <div class="card-body">
                    <div class="row g-1 align-items-left">
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary btn-sm" id="generateData">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                        <div class="col-auto">
                            <input type="number" autocomplete="off" class="form-control form-control-sm"
                                id="columns_fake" placeholder="Banyak data" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="tblFakeKaryawan" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan</th>
                                            <th>Panggilan</th>
                                            <th>Kelamin</th>
                                            <th>Kode Provinsi</th>
                                            <th>Provinsi</th>
                                            <th>Kode Kota Kabupaten</th>
                                            <th>Kota Kabupaten</th>
                                            <th>Kode Kecamatan</th>
                                            <th>Kecamatan</th>
                                            <th>Alamat</th>
                                            <th>Provinsi Lahir</th>
                                            <th>Kode Kota Kabupaten Lahir</th>
                                            <th>Kota Kabupaten Lahir</th>
                                            <th>Kode Kecamatan Lahir</th>
                                            <th>Kecamatan lahir</th>
                                            <th>Alamat</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Umur</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Tanggal Akhir Kontrak</th>
                                            <th>Kode Divisi</th>
                                            <th>Divisi</th>
                                            <th>Kode Departement</th>
                                            <th>Departement</th>
                                            <th>Kode Posisi</th>
                                            <th>Posisi</th>
                                            <th>Gaji</th>
                                            <th>No Telp/HP</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-success btn-sm" id="btnSimpanFakeKaryawan">
                                <i class="fas fa-anchor"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $('#generateData').click(function() {
                const jumlahData = $('#columns_fake').val();

                if (!jumlahData || isNaN(jumlahData) || jumlahData <= 0) {
                    validateInput(jumlahData, 'Masukkan jumlah data yang valid!')
                    return;
                }

                showLoadingAlert({
                    title: 'Sedang Membuat Data Fake...',
                    text: 'Harap tunggu hingga proses selesai.'
                });

                $.ajax({
                    url: "<?= BASEURL; ?>/Faker/generateFakeData",
                    method: 'POST',
                    data: {
                        jumlah_data: jumlahData
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        if (response.status === 'success') {
                            $('#tblFakeKaryawan').DataTable().clear().destroy();
                            loadDataFake(response.data)
                        } else {
                            showAlert('error', 'Gagal', response.message ||
                                'Terjadi kesalahan ahhahahaha.');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close();
                        showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
                    }
                })

            });

            $('#btnSimpanFakeKaryawan').on('click', simpanDataFakekaryawan);
        });

        const loadDataFake = (data) => {
            $('#tblFakeKaryawan').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama',
                    },
                    {
                        data: 'panggilan',
                    },
                    {
                        data: 'kelamin',
                    },
                    {
                        data: 'kd_provinsi',
                    },
                    {
                        data: 'nama_provinsi',
                    },
                    {
                        data: 'kd_kota_kabupaten',
                    },
                    {
                        data: 'nama_kota_kabupaten',
                    },
                    {
                        data: 'kd_kecamatan',
                    },
                    {
                        data: 'kecamatan',
                    },
                    {
                        data: 'kd_provinsi_lahir',
                    },
                    {
                        data: 'nama_provinsi_lahir',
                    },
                    {
                        data: 'kd_kota_kabupaten_lahir',
                    },
                    {
                        data: 'nama_kota_kabupaten_lahir',
                    },
                    {
                        data: 'kd_kecamatan_lahir',
                    },
                    {
                        data: 'kecamatan_lahir',
                    },
                    {
                        data: 'detail_alamat',
                    },
                    {
                        data: 'tgl_lahir',
                        render: function(data, type, row) {
                            return moment(data).format('DD-MMMM-YYYY');
                        }
                    },
                    {
                        data: 'tgl_lahir',
                        render: function(data, type, row) {
                            const umur = moment().diff(moment(data), 'years');
                            return umur + ' Tahun';
                        }
                    },
                    {
                        data: 'tgl_masuk',
                        render: function(data, type, row) {
                            return moment(data).format('DD-MMMM-YYYY');
                        }
                    },
                    {
                        data: 'tgl_akhir_kontrak',
                        render: function(data, type, row) {
                            return moment(data).format('DD-MMMM-YYYY');
                        }
                    },
                    {
                        data: 'kd_divisi',
                    },
                    {
                        data: 'nama_divisi',
                    },
                    {
                        data: 'kd_departement',
                    },
                    {
                        data: 'nama_departement',
                    },
                    {
                        data: 'kd_position',
                    },
                    {
                        data: 'nama_posisi',
                    },
                    {
                        data: 'gaji',
                        render: function(data, type, row) {
                            return formatStringNumber(data)
                        }
                    },
                    {
                        data: 'no_telp1'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="btn btn-danger btn-sm delete-row fas fa-trash-alt"></i>
                            </div>
                            `;
                        }
                    }
                ],
                columnDefs: [{
                    targets: [4, 6, 8, 10, 12, 14, 20, 22, 24],
                    visible: false
                }],
                scrollX: true,
                scrollCollapse: true,
                scrollY: 300,
                fixedHeader: true,
                fixedColumns: {
                    start: 3,
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
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100, "All"]
                ],
                initComplete: function() {
                    $('#tblFakeKaryawan tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblFakeKaryawan tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                },
            });
        };

        const simpanDataFakekaryawan = () => {
            let userInput = $('#kd_asli_user').data('kd_asli_user');
            let tableFakeKaryawan = $('#tblFakeKaryawan').DataTable().rows({
                filter: 'applied'
            }).data().toArray();

            let dataToSend = {
                data: tableFakeKaryawan.map((it, ix) => {
                    return {
                        nama_karyawan: it.nama,
                        nama_panggilan_karyawan: it.panggilan,
                        gender: it.kelamin,
                        tgl_lahir: it.tgl_lahir,
                        bln_lahir: moment(it.tgl_lahir).format("MM"),
                        thn_lahir: moment(it.tgl_lahir).format("YYYY"),
                        tgl_awal_kontrak: it.tgl_masuk,
                        tgl_akhir_kontrak: it.tgl_akhir_kontrak,
                        tgl_bergabung: it.tgl_masuk,
                        bln_bergabung: moment(it.tgl_masuk).format("MM"),
                        thn_bergabung: moment(it.tgl_masuk).format("YYYY"),
                        gaji_angka: it.gaji.toString(),
                        provinsi_lahir: it.kd_provinsi_lahir,
                        kota_kab_lahir: it.kd_kota_kabupaten_lahir,
                        kecamatan_lahir: it.kd_kecamatan_lahir,
                        provinsi_tinggal: it.kd_provinsi,
                        kota_kab_tinggal: it.kd_kota_kabupaten,
                        kecamatan_tinggal: it.kd_kecamatan,
                        alamat_tinggal: it.detail_alamat,
                        no_telp1: it.no_telp1,
                        kd_divisi: it.kd_divisi,
                        kd_departement: it.kd_departement,
                        kd_position: it.kd_position,
                        kd_user: userInput,
                    }
                })
            }

            if (dataToSend.data.length === 0) {
                validateInput(dataToSend.data, 'Data tidak boleh kosong')
                return;
            }

            showLoadingAlert({
                    title: 'Sedang Proses Input Data Mohon Menunggu',
                    text: 'Harap tunggu hingga proses selesai.'
                });

            $.ajax({
                url: "<?= BASEURL; ?>/faker/simpanDataFakeKaryawan",
                method: 'POST',
                data: JSON.stringify(dataToSend),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        showAlert('success', 'Berhasil', response.message || 'Data berhasil disimpan!',
                            () => {
                                location.reload();
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