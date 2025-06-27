<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="d-flex gap-2">Nama Karyawan : <strong id='namaKaryawan'></strong></div>
                            <div class="d-flex gap-2">Divisi : <strong id='divisi'></strong></div>
                            <div class="d-flex gap-2">Departement : <strong id='departement'></strong></div>
                            <div class="d-flex gap-2">Posisi : <strong id='posisi'></strong></div>
                        </div>
                    </div>
                    <h3>Kontrak Sebelumnya</h3>
                    <div class="row mb-2">
                        <input type="hidden" name="_csrf_token" id="_csrf_token"
                            value="<?= htmlspecialchars($data['csrf_token']); ?>">
                        <div class="col-6">
                            <label for="" class="form-label">Tanggal Awal Kontrak</label>
                            <input type="text" class="form-control" id="tgl_awal_kontrak" disabled>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">Tanggal Akhir Kontrak</label>
                            <input type="text" class="form-control" id="tgl_akhir_kontrak" disabled>
                        </div>
                    </div>
                    <h3>Kontrak Baru</h3>
                    <div class="row mb-2">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Awal Kontrak</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tglAwalKontrak"><span
                                        class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal Akhir</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" id="tglAkhirKontrak"><span
                                        class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Note</label>
                                <select name="status_kontrak" id="status_kontrak" class="form-control">
                                    <option value="">-- STATUS KONTRAK --</option>
                                    <option value="PKHL">PKHL</option>
                                    <option value="PKWT">PKWT</option>
                                    <option value="PKWTT">PKWTT</option>
                                    <option value="PROBATION / MAGANG">PROBATION / MAGANG</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <label for="" class="form-label">Catatan</label>
                            <textarea class="form-control" name="note" id="note" rows="6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-danger me-2" id="batal">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="btnSimpanKontrakKaryawan">
                        <i class="fa-solid fa-paper-plane"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </main>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const getKdkaryawan = urlParams.get('kd_karyawan');
        let dataKaryawan;

        $(document).ready(function() {
            $.ajax({
                url: `<?= BASEURL; ?>/hrd/allDataKaryawan`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        dataKaryawan = res.filter(it => it.kd_karyawan === getKdkaryawan)[0];
                        if (dataKaryawan) {
                            $('#namaKaryawan').text(dataKaryawan.nama_karyawan);
                            $('#divisi').text(dataKaryawan.divisi.nama_divisi);
                            $('#departement').text(dataKaryawan.departement.nama_departement);
                            $('#posisi').text(dataKaryawan.posisi.nama_position);
                            $('#tgl_awal_kontrak').val(dataKaryawan.tgl_awal_kontrak);
                            $('#tgl_akhir_kontrak').val(dataKaryawan.tgl_akhir_kontrak);
                        } else {
                            window.location.href = `<?= BASEURL ?>/hrd/master_karyawan`;
                        }
                    } else {
                        window.location.href = `<?= BASEURL ?>/hrd/master_karyawan`;
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Gagal', `Terjadi kesalahan ${xhr.responseText}.`);
                }
            })
            $('#tglAwalKontrak').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
            });
            $('#tglAkhirKontrak').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
            });
            $('#note').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });
            $('#batal').click(function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    html: `Apakah Membatalkan Proses ini ?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa-solid fa-check"></i> Ya',
                    cancelButtonText: '<i class="fa-solid fa-xmark"></i> Tidak',
                    allowHtml: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `<?= BASEURL ?>/hrd/master_karyawan`;
                    }
                });
            })
            $('#btnSimpanKontrakKaryawan').on('click', () => {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menyimpan data ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                }).then((result) => {
                    if (result.isConfirmed) {
                        SimpanKontrakKaryawan();
                    }
                });
            });
        });

        const SimpanKontrakKaryawan = () => {
            const csrfToken = $('#_csrf_token').val();
            let kdKaryawan = getKdkaryawan;
            let tglAwal = $('#tglAwalKontrak').val();
            let tglAkhir = $('#tglAkhirKontrak').val();
            let statusKontrak = $('#status_kontrak').val();
            let note = $('#note').val();

            if (!validateInput(tglAwal, 'Tanggal Awal Kontrak Di Kontrak Baru')) return;
            if (!validateInput(tglAkhir, 'Tanggal Akhir Kontrak Di Kontrak Baru')) return;
            if (!validateInput(statusKontrak, 'Status Kontrak')) return;

            let dataToSave = {
                csrf_token: csrfToken,
                kd_karyawan: kdKaryawan,
                tgl_awal_kontrak: tglAwal,
                tgl_akhir_kontrak: tglAkhir,
                status_kontrak: statusKontrak,
                note: note,
            }

            showLoadingAlert({
                title: 'Sedang Menyimpan Data...',
                text: 'Harap tunggu hingga proses selesai.'
            });

            $.ajax({
                url: "<?= BASEURL; ?>/hrd/validasiSimpanKontrakKaryawan",
                method: 'POST',
                data: dataToSave,
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status === 'success') {
                        showAlert('success', 'Berhasil', response.message || 'Data berhasil disimpan!',
                            () => {
                                window.location.href = `<?= BASEURL ?>/hrd/master_karyawan`;
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