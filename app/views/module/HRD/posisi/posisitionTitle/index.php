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
                            data-bs-target="#modalTambahPosisitonTitle">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label for="filterDivisi" class="form-label">Pilih Divisi</label>
                            <select id="filterDivisi" class="form-select">
                                <option value="">-- Pilih Divisi --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterDepartement" class="form-label">Pilih Departement</label>
                            <select id="filterDepartement" class="form-select">
                                <option value="">-- Pilih Departement --</option>
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
                                <table id="tblPisitionTitle" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Divisi</th>
                                            <th>Depatement</th>
                                            <th>Job Title</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahPosisitonTitle" tabindex="-1"
                    aria-labelledby="modalTambahPosisitonTitleLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahPosisitonTitleLabel">Tambah Job Title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="_csrf_token" id="_csrf_token"
                                    value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="kd_divisi" class="form-label">Divisi</label>
                                    <select class="form-control" name="kd_divisi" id='kd_divisi' required></select>
                                </div>
                                <div class="mb-3">
                                    <label for="kd_departement" class="form-label">Departement</label>
                                    <select class="form-control" name="kd_departement" id='kd_departement' required
                                        disabled></select>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_posisi" class="form-label">Posisi Title</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nama_posisi"
                                        placeholder="Job Title" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanPosisitonTitle">
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
        let filterDivisi = [];
        let filterDepartement = [];
        let dataFilterPosisition = []

        $(document).ready(function() {
            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataDivisi`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        allDivisi = res
                        filterDivisi = res
                        loadSelectDivisi(res)
                    } else {
                        showAlert('error', 'Gagal', response.message ||
                            'Terjadi kesalahan saat menyimpan data.');
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
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
                        filterDepartement = res
                        loadSelectDepartement(res)
                    } else {
                        showAlert('error', 'Gagal', response.message ||
                            'Terjadi kesalahan saat menyimpan data.');
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
                }
            });

            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataPosisiton`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data

                        res.sort((a, b) => {
                            const divisiA = a.departement.divisi.nama_divisi.toUpperCase();
                            const divisiB = b.departement.divisi.nama_divisi.toUpperCase();
                            if (divisiA < divisiB) return -1;
                            if (divisiA > divisiB) return 1;
                            return 0;
                        })

                        dataFilterPosisition = res
                        loadDataPosisiton(res)
                    } else {
                        showAlert('error', 'Gagal', response.message ||
                            'Terjadi kesalahan saat menyimpan data.');
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
                }
            });

            defaultSelect2("#filterDivisi", "-- Pilih Divisi --");
            defaultSelect2("#filterDepartement", "-- Pilih Departement --");
            defaultSelect2("#kd_divisi", "Pilih Divisi", "#modalTambahPosisitonTitle");
            defaultSelect2("#kd_departement", "Pilih Departement", "#modalTambahPosisitonTitle");

            $('#modalTambahPosisitonTitle').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $(this).find('select').val(null).trigger('change');
            });

            $('#nama_posisi').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#kd_divisi').on('change', function() {
                let selectedDivisi = $(this).val();
                let selectDivisiName = $('#kd_divisi').find('option:selected').text();
                $('#kd_departement').html('<option value="">-- Pilih Departement --</option>').prop(
                    'disabled', true);
                if (selectedDivisi) {
                    let filterDivisi = allDepartement.filter(item =>
                        item.kd_divisi === selectedDivisi
                    );
                    if (filterDivisi.length > 0) {
                        for (let dept of filterDivisi) {
                            $('#kd_departement').append(
                                `<option value="${dept.kd_departement}">${dept.nama_departement}</option>`
                            );
                            $('#kd_departement').prop('disabled', false);
                        }
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: `Tidak Ada Departement yang berada di bawah divisi ${selectDivisiName}.`,
                        });
                        return;
                        $('#kd_departement').prop('disabled', true);
                    }
                }
            });

            $('#filterDivisi').on('change', function() {
                let selectedFilterDivisi = $(this).val();
                let selectedFilterDivisiName = $('#filterDivisi').find('option:selected').text();
                $('#filterDepartement').html('<option value="">-- Pilih Departement --</option>').prop(
                    'disabled', true);
                if (selectedFilterDivisi) {
                    let selectDivisi = filterDepartement.filter(item =>
                        item.kd_divisi === selectedFilterDivisi
                    );
                    for (let dept of selectDivisi) {
                        $('#filterDepartement').append(
                            `<option value="${dept.kd_departement}">${dept.nama_departement}</option>`
                        );
                        $('#filterDepartement').prop('disabled', false);
                    }
                }
            });

            $('#btnSearch').click(function() {
                const kdDivisi = $('#filterDivisi').val();
                const kdDepartement = $('#filterDepartement').val();
                let filteredData = dataFilterPosisition.filter(item => {
                    let matchDivisi = kdDivisi ? item.departement.divisi.kd_divisi ===
                        kdDivisi : true;
                    let matchDepartement = kdDepartement ? item.departement.kd_departement ===
                        kdDepartement : true;
                    return matchDivisi && matchDepartement;
                });
                $('#tblPisitionTitle').DataTable().clear().destroy();
                loadDataPosisiton(filteredData);
            });

            $('#btnReset').on('click', function() {
                $("#filterDivisi, #filterDepartement").val("").trigger("change");
                $('#tblPisitionTitle').DataTable().clear().destroy();
                loadDataPosisiton(dataFilterPosisition);
            });

            $('#btnSimpanPosisitonTitle').on('click', () => {
                showConfirmationDialog(
                    'Konfirmasi',
                    'Apakah Anda yakin ingin menyimpan data ini?',
                    'Ya, Simpan!',
                    'Batal',
                    function() {
                        simpanDataPosisitionTitle();
                    }
                );
            });
        });

        const loadSelectDivisi = (data) => {
            const selectDivisi = $('#kd_divisi');
            // const ubahSelectDivisi = $('#ubah_kd_divisi');
            [selectDivisi].forEach(select => {
                select.empty().append('<option value="" disabled selected>-- Pilih --</option>');
                data.forEach(item => {
                    select.append(`<option value="${item.kd_divisi}">${item.nama_divisi}</option>`);
                });
            });
            $('#filterDivisi').empty().append('<option value="">-- Pilih Divisi --</option>');
            data.forEach(divisi => {
                $('#filterDivisi').append(
                    `<option value="${divisi.kd_divisi}">${divisi.nama_divisi}</option>`);
            });
        };

        const loadSelectDepartement = (data) => {
            const selectDepartement = $('#kd_departement');
            // const ubahselectDepartement = $('#ubah_kd_departement');
            [selectDepartement].forEach(select => {
                select.empty().append('<option value="" disabled selected>-- Pilih --</option>');
                data.forEach(item => {
                    select.append(
                        `<option value="${item.kd_departement}">${item.nama_departement}</option>`
                    );
                });
            });
        };

        const loadDataPosisiton = (data) => {
            $('#tblPisitionTitle').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'departement.divisi.nama_divisi',
                    },
                    {
                        data: 'departement.nama_departement',
                    },
                    {
                        data: 'nama_position',
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return `
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit btn btn-warning btn-sm" onclick='tampilUbahPosisition(${JSON.stringify(data)})' data-bs-toggle="modal" data-bs-target="#modalUbahPosisition"></i>
                                <div class="mx-2" style="border-left: 1px solid #000000; height: 24px;"></div>
                                <i class="fas fa-trash btn btn-danger btn-sm" onclick='tampilTempHapusPosisition(${JSON.stringify(data)})'></i>
                            </div>
                        `
                        }
                    }
                ],
                pageLength: 50,
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100, "All"]
                ],
                initComplete: function() {
                    $('#tblPisitionTitle tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblPisitionTitle tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        };

        const simpanDataPosisitionTitle = () => {
            const csrfToken = $('#_csrf_token').val();
            let kdDivisi = $('#kd_divisi').val();
            let kdDepartement = $('#kd_departement').val();
            let jobTitle = $('#nama_posisi').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            if (!validateInput(kdDivisi, 'Divisi tidak boleh kosong!')) return;
            if (!validateInput(kdDepartement, 'Departement tidak boleh kosong!')) return;
            if (!validateInput(jobTitle, 'Job Title tidak boleh kosong!')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                kd_divisi: kdDivisi,
                kd_departement: kdDepartement,
                nama_position: jobTitle,
                kd_user: user_input,
            }

            showLoadingAlert({
                title: 'Sedang Menyimpan Data...',
                text: 'Harap tunggu hingga proses selesai.'
            });

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiSimpanPosisitionTitle",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        showAlert('success', 'Berhasil', response.message || 'Data berhasil disimpan!',
                            () => {
                                $('#modalTambahPosisitonTitle').modal('hide');
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
            });
        }
    </script>