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
                            data-bs-target="#modalTambahSales">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblSpvSales" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama SPV</th>
                                            <th>Status SPV</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTambahSales" tabindex="-1" aria-labelledby="modalTambahSalesLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahSalesLabel">Tambah SPV Sales</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="kd_spv_sales" class="form-label">Pilih SPV</label>
                                    <select class="form-control" name="kd_spv_sales" id="kd_spv_sales"></select>
                                </div>
                                <div class="mb-3">
                                    <label for="kd_sales" class="form-label">Pilih SPV</label>
                                    <select class="form-control" name="kd_sales" id="kd_sales"></select>
                                    <input type="hidden" autocomplete="off" class="form-control" id="nama_sales" placeholder="Masukkan Nama Sales" disabled>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanSpvSales">
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
        const url = "<?= BASEURL ?>";
        let spv = [];
        let filterSpv = [];

        $(document).ready(function() {
            getAllSpvSales(url).then(data => {
                loadSelectSpv(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllPosisiton: ${err.statusText || err}`,
                });
            });

            getAllDataKaryawanNew(url).then(data => {
                let filterData = data.filter((it) => it.kd_position === 'PST-202501-0003');
                filterSpv = data
                loadSelectSales(filterData)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllDataKaryawanNew: ${err.statusText || err}`,
                });
            });

            $('#modalTambahSales').on('shown.bs.modal', function() {
                defaultSelect2("#kd_spv_sales", "-- Pilih SPV --", '#modalTambahSales');
                defaultSelect2("#kd_sales", "-- Pilih SALES --", '#modalTambahSales');
            });

            $('#kd_spv_sales').on('change', function() {
                const selectedValue = $(this).val();

                if (selectedValue) {
                    $('#kd_sales').prop('disabled', false);
                } else {
                    $('#kd_sales').prop('disabled', true);
                }
            });

            $('#kd_sales').prop('disabled', true);

            $('#kd_sales').on('change', function() {
                const selectedKd = $(this).val();
                const selectedSpv = filterSpv.find(item => item.kd_karyawan === selectedKd);
                if (selectedSpv) {
                    $('#nama_sales').val(selectedSpv.nama_karyawan);
                } else {
                    $('#nama_sales').val('');
                }
            });
        });

        const loadSelectSpv = (data) => {
            let spvActive = data.filter((it) => it.status_spv_sales === 'ACTIVE')
            let selectedSpv = $('#kd_spv_sales');

            console.log('sko', spvActive)

            selectedSpv.empty()
            selectedSpv.append('<option value="" disabled selected>-- PILIH SPV --</option>');

            spvActive.forEach(item => {
                selectedSpv.append(`<option value="${item.kd_spv_sales}">${item.karyawan.nama_karyawan}</option>`);
            })
        }

        const loadSelectSales = (data) => {
            let selectSales = $('#kd_sales');

            selectSales.empty()
            selectSales.append('<option value="" disabled selected>-- PILIH SALES --</option>');

            data.forEach(item => {
                selectSales.append(`<option value="${item.kd_karyawan}">${item.nama_karyawan}</option>`);
            })
        }
    </script>