<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahDepartement">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label for="filterDivisi" class="form-label">Pilih Divisi</label>
                            <select id="filterDivisi" class="filterDivisi form-select">
                                <option value="">-- Pilih Divisi --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button id="btnSearch" class="btn btn-success"><i class="fas fa-search"></i> Cari</button>
                            <button id="btnReset" class="btn btn-danger"><i class="fas fa-sync-alt"></i> Reset</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblDepartement" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Divisi</th>
                                            <th>Depatement</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahDepartement" tabindex="-1" aria-labelledby="modalTambahDepartementLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahDepartementLabel">Tambah Departement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="kd_divisi" class="form-label">Pilih Divisi</label>
                                    <select class="form-control" name="kd_divisi" id='kd_divisi' required></select>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_departement" class="form-label">Nama Departement</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nama_departement"
                                        placeholder="Masukkan Departement" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanDepartement">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalUbahDepartement" tabindex="-1" aria-labelledby="modalUbahDepartementLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalUbahDepartementLabel">Tambah Departement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <input type="hidden" name="kd_departement" id="kd_departement">
                                <div class="mb-3">
                                    <label for="ubah_kd_divisi" class="form-label">Pilih Divisi</label>
                                    <select class="form-control" name="ubah_kd_divisi" id='ubah_kd_divisi' required></select>
                                </div>
                                <div class="mb-3">
                                    <label for="ubah_nama_departement" class="form-label">Nama Departement</label>
                                    <input type="text" autocomplete="off" class="form-control" id="ubah_nama_departement"
                                        placeholder="Masukkan Departement" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnUbahDepartement">
                                    <i class="fa-solid fa-paper-plane"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <script>
        let allDivisi = [];
        let allDepartement = [];

        $(document).ready(function() {
            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataDivisi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        allDivisi = res

                        loadSelectDivisi(res)
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat mengambil data.',
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

            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataDepartement`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data

                        allDepartement = res
                        loadDataDepartement(res)
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat mengambil data.',
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

            $('.select-test').select2();

            defaultSelect2(".filterDivisi", "-- Pilih Divisi --");

            $('#btnSearch').click(function() {
                const kdDivisi = $('#filterDivisi').val();

                if (kdDivisi === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih divisi terlebih dahulu.',
                    });
                    return;
                }

                const filteredDepartement = allDepartement.filter(departement => departement.kd_divisi === kdDivisi);

                $('#tblDepartement').DataTable().clear().destroy();
                loadDataDepartement(filteredDepartement);
            });

            $('#btnReset').click(function() {
                $('#filterDivisi').val('');
                $('#tblDepartement').DataTable().clear().destroy();
                loadDataDepartement(allDepartement);
            });

            $('#modalTambahDepartement').on('shown.bs.modal', function() {
                $('#kd_divisi').select2({
                    dropdownParent: $('#modalTambahDepartement'),
                    placeholder: "Pilih Divisi",
                    allowClear: true
                });
            });

            $('#modalUbahDepartement').on('shown.bs.modal', function() {
                $('#ubah_kd_divisi').select2({
                    dropdownParent: $('#modalTambahDepartement'),
                    placeholder: "Pilih Divisi",
                    allowClear: true
                });
            });

            $('#modalTambahDepartement, #modalUbahDepartement').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalTambahDepartement, #modalUbahDepartement').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $(this).find('select').val(null).trigger('change');
            });

            $('#nama_departement, #ubah_nama_departement').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });


            $('#btnSimpanDepartement').on('click', () => {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menyimpan data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        simpanDataDepartement();
                    }
                });
            });

            $('#btnUbahDepartement').on('click', () => {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mengubah data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        ubahDataDepartement();
                    }
                });
            });
        });

        const tampilUbahDepartement = (data) => {
            $('#kd_departement').val(data.kd_departement);
            $('#ubah_kd_divisi').val(data.kd_divisi).trigger('change');
            $('#ubah_nama_departement').val(data.nama_departement);
            $('#modalUbahDepartement').modal('show');
        }

        const loadSelectDivisi = (data) => {
            const selectDivisi = $('#kd_divisi');
            const ubahSelectDivisi = $('#ubah_kd_divisi');

            [selectDivisi, ubahSelectDivisi].forEach(select => {
                select.empty().append('<option value="" disabled selected>-- Pilih --</option>');
                data.forEach(item => {
                    select.append(`<option value="${item.kd_divisi}">${item.nama_divisi}</option>`);
                });
            });

            $('#filterDivisi').empty().append('<option value="">-- Pilih Divisi --</option>');
            data.forEach(divisi => {
                $('#filterDivisi').append(`<option value="${divisi.kd_divisi}">${divisi.nama_divisi}</option>`);
            });
        };

        const loadDataDepartement = (data) => {
            $('#tblDepartement').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'divisi.nama_divisi',
                    },
                    {
                        data: 'nama_departement',
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbahDepartement(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahDepartement"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick='tampilTempHapusDepartement(${JSON.stringify(data)})'></i>
                            </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tblDepartement tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblDepartement tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        }

        const simpanDataDepartement = () => {
            const csrfToken = $('#_csrf_token').val();
            let kdDivisi = $('#kd_divisi').val();
            let namaDepartement = $('#nama_departement').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            if (!validateInput(kdDivisi, 'Divisi tidak boleh kosong!')) return;
            if (!validateInput(namaDepartement, 'Nama departement tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                kd_divisi: kdDivisi,
                nama_departement: namaDepartement,
                kd_user: user_input,
            }

            Swal.fire({
                title: 'Menyimpan data...',
                text: 'Harap tunggu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiSimpanDepartement",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan!',
                        }).then(() => {
                            $('#modalTambahDepartement').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menyimpan data.',
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

        const ubahDataDepartement = () => {
            const csrfToken = $('#_csrf_token').val();
            let kdDepartement = $('#kd_departement').val();
            let ubahKdDivisi = $('#ubah_kd_divisi').val();
            let ubahNamaDepartement = $('#ubah_nama_departement').val();

            if (!validateInput(ubahNamaDepartement, 'Nama departement tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                kd_departement: kdDepartement,
                kd_divisi: ubahKdDivisi,
                nama_departement: ubahNamaDepartement,
            }

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiUbahDepartement",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan!',
                        }).then(() => {
                            $('#modalUbahDepartement').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menyimpan data.',
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
    </script>