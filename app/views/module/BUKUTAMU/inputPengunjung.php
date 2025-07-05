<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center gap-3">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-header"><strong>CUSTOMER BARU</strong></div>
                                <div class="card-body d-flex justify-content-center">
                                    <img src="<?= BASEURL; ?>/img/tools/image-book4.png" alt="" id="new_customer_buku_tamu" style="cursor: pointer; width: 300px; height: 300px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card">
                                <div class="card-header"><strong>CUSTOMER SUDAH PERNAH BERKUNJUNG</strong></div>
                                <div class="card-body d-flex justify-content-center">
                                    <img src="<?= BASEURL; ?>/img/tools/image-book6.png" alt="" id="old_customer_buku_tamu" style="cursor: pointer; width: 300px; height: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalNewCustomerBukuTamu" tabindex="-1" aria-labelledby="modalNewCustomerBukuTamuLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalNewCustomerBukuTamuLabel">Input Pengunjung Buku Tamu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="nama_pengunjung" class="form-label">Nama Pengunjung</label>
                                    <input type="text" autocomplete="off" class="form-control" id="nama_pengunjung"
                                        placeholder="Masukkan Nama Pengunjung" required>
                                </div>

                                <div class="mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="kd_provinsi" class="form-label">Provinsi</label>
                                            <select class="form-control" id="kd_provinsi" name="kd_provinsi"></select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="kd_kota_kabupaten" class="form-label">Kota/Kabupaten</label>
                                            <select class="form-control" id="kd_kota_kabupaten" name="kd_kota_kabupaten" disabled></select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="kd_kecamatan" class="form-label">Kecamatan <strong>(Optional)</strong></label>
                                            <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" disabled></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_detail" class="form-label">
                                        Alamat Detail <strong>(Optional)</strong>
                                    </label>
                                    <textarea class="form-control" id="alamat_detail" rows="3"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="kd_alasan_kunjungan_buku_tamu" class="form-label">Alasan Kunjungan</label>
                                    <select class="form-control" id="kd_alasan_kunjungan_buku_tamu" name="kd_alasan_kunjungan_buku_tamu"></select>
                                </div>

                                <div class="mb-3" id="kunjungan_detail" style="display: none;">
                                    <label for="alasan_kunjungan_detail" class="form-label">Detail Alasan Kunjungan</label>
                                    <input type="text" autocomplete="off" class="form-control" name="alasan_kunjungan_detail" id="alasan_kunjungan_detail"
                                        placeholder="Masukkan Alasan Kunjungan" required>
                                </div>

                                <div id="group_alasan_kunjungan" style="display: none;">
                                    <div class="mb-3">
                                        <label for="kd_sumber_informasi_buku_tamu" class="form-label">Sumber Informasi</label>
                                        <select class="form-control" id="kd_sumber_informasi_buku_tamu" name="kd_sumber_informasi_buku_tamu"></select>
                                    </div>

                                    <div class="mb-3" id="sumber_informasi_buku_tamu" style="display: none;">
                                        <label for="detail_sumber_informasi" class="form-label">Sumber Informasi Detail</label>
                                        <input type="text" class="form-control" id="detail_sumber_informasi" name="detail_sumber_informasi"></input>
                                    </div>

                                    <div class="mb-3" id="sumber_informasi_detail_buku_tamu" style="display: none;">
                                        <label for="kd_sumber_informasi_detail_buku_tamu" class="form-label">Sumber Informasi Detail</label>
                                        <select class="form-control" id="kd_sumber_informasi_detail_buku_tamu" name="kd_sumber_informasi_detail_buku_tamu"></select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="kd_master_sales" class="form-label">Sales</label>
                                        <select class="form-control" id="kd_master_sales" name="kd_master_sales"></select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="note" class="form-label">
                                        Catatan / Kesan dan pesan <strong>(Optional)</strong>
                                    </label>
                                    <textarea class="form-control" id="note" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">
                                    <i class="fa-solid fa-xmark"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" id="btnSimpanNewCustomerBukuTamu">
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
        let dataProvinsi = [];
        let dataKotaKabupaten = [];
        let dataKecamatan = [];
        let dataAlasanKunjungan = [];
        let dataSumberInformasi = [];
        let dataSumberInformasiDetail = [];

        const simpanDataNewCustomerBukuTamu = async () => {
            let requireValue = [];

            const csrfToken = $('#csrf_token').val();
            let namaPengunjung = $('#nama_pengunjung').val().trim();
            let kdProvinsi = $('#kd_provinsi').val();
            let kdKabupaten = $('#kd_kota_kabupaten').val();
            let kdKecamatan = $('#kd_kecamatan').val();
            let kdAlsanKunjungan = $('#kd_alasan_kunjungan_buku_tamu').val();
            let kdSumberInfromasi = $('#kd_sumber_informasi_buku_tamu').val();
            let kdSumberInformasiDetail = $('#kd_sumber_informasi_detail_buku_tamu').val();
            let kdMasterSales = $('#kd_master_sales').val();
            let note = $('#note').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');

            let hariIniBulanTahun = moment();

            let hari = hariIniBulanTahun.format('YYYY-MM-DD');
            let bulan = hariIniBulanTahun.format('MM');
            let tahun = hariIniBulanTahun.format('YYYY');
            let jamMenit = hariIniBulanTahun.format('HH:mm');

            let alasanKunjungan = $('#alasan_kunjungan_detail').val().trim();
            let detailSumberInformasi = $('#detail_sumber_informasi').val().trim();

            let selectKunjungan = $('#kd_alasan_kunjungan_buku_tamu option:selected').text();
            let selectSumberInfromasi = $('#kd_sumber_informasi_buku_tamu option:selected').text();

            requireValue.push({
                value: namaPengunjung,
                message: 'Nama pengunjung tidak boleh kosong!'
            });
            requireValue.push({
                value: kdProvinsi,
                message: 'Provinsi harus dipilih!'
            });
            requireValue.push({
                value: kdKabupaten,
                message: 'Kabupaten/Kota harus dipilih!'
            });


            if (kdAlsanKunjungan === null || kdAlsanKunjungan === "") {
                requireValue.push({
                value: kdAlsanKunjungan,
                message: 'Alasan Kunjungan harus dipilih!'
                });
            } else {
                if (selectKunjungan === "LAINNYA") {
                    requireValue.push({
                        value: alasanKunjungan,
                        message: 'Alasan Kunjungan Detail tidak boleh kosong!'
                    });
                } else if (selectKunjungan !== "LAINNYA") {
                    requireValue.push({
                        value: kdSumberInfromasi,
                        message: 'Sumber Informasi harus dipilih!'
                    });

                    if (selectSumberInfromasi === "LAINNYA") {
                        requireValue.push({
                            value: detailSumberInformasi,
                            message: 'Detail Sumber Informasi tidak boleh kosong!'
                        });
                    } else {
                        requireValue.push({
                            value: kdSumberInformasiDetail,
                            message: 'Sumber Informasi Detail harus dipilih!'
                        });
                    }
                    requireValue.push({
                        value: kdMasterSales,
                        message: 'Sales harus dipilih!'
                    });
                }
            }


            if (!validasiBanyakInputan(requireValue)) return;

            let dataToSave = {
                csrf_token: csrfToken,
                nama_pengunjung: namaPengunjung,
                kd_master_sales: kdMasterSales,
                kd_provinsi: kdProvinsi,
                kd_kota_kabupaten: kdKabupaten,
                kd_kecamatan: kdKecamatan,
                kd_alasan_kunjungan_buku_tamu: kdAlsanKunjungan,
                alasan_kunjungan_detail: selectKunjungan === "LAINNYA" ? alasanKunjungan : null,
                kd_sumber_informasi_buku_tamu: kdSumberInfromasi,
                detail_sumber_informasi: selectSumberInfromasi === "LAINNYA" ? detailSumberInformasi : null,
                kd_sumber_informasi_detail_buku_tamu: selectSumberInfromasi !== "LAINNYA" ? kdSumberInformasiDetail : null,
                tgl_kunjungan: hari,
                bln_kunjungan: bulan,
                thn_kunjungan: tahun,
                waktu_kunjungan: jamMenit,
                note: note,
                kd_user: user_input,
            }

            // console.log('dask', dataToSave)

            try {
                Swal.fire({
                    title: 'Menyimpan data...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                })

                const response = await fetch(`<?= BASEURL; ?>/bukutamu/validasiSimpanPengunjungBaru`, {
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
                        $('#modalNewCustomerBukuTamu').modal('hide');
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
            getAllProvinsi(url).then(data => {
                loadSelectProvinsi(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllProvinsi: ${err.statusText || err}`,
                });
            });

            getAllKotaKabupaten(url).then(data => {
                dataKotaKabupaten = data
                loadSelectKotakabupaten(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllKotaKabupaten: ${err.statusText || err}`,
                });
            });

            getAllKecamatan(url).then(data => {
                dataKecamatan = data
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllKecamaten: ${err.statusText || err}`,
                });
            });

            getAllAlasanKunjunganBukuTamu(url).then(data => {
                dataAlasanKunjungan = data.filter((it) => it.tampil_buku_tamu === "YA")
                dataAlasanKunjungan.sort((a, b) => a.nama_alasan_kunjungan.localeCompare(b.nama_alasan_kunjungan))
                loadSelectAlasanKunjungan(dataAlasanKunjungan)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllKecamaten: ${err.statusText || err}`,
                });
            });

            getAllDataSumberInformasi(url).then(data => {
                dataSumberInformasi = data
                data.sort((a, b) => a.nm_sumber_informasi.localeCompare(b.nm_sumber_informasi))
                loadSelectedSumberInformasi(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllKecamaten: ${err.statusText || err}`,
                });
            });

            getAllDataSumberInformasiDetail(url).then(data => {
                dataSumberInformasiDetail = data
                data.sort((a, b) => a.nm_sumber_informasi_detail.localeCompare(b.nm_sumber_informasi_detail))
                loadSelectedSumberInformasiDetail(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllKecamaten: ${err.statusText || err}`,
                });
            });

            getAllSales(url).then(data => {
                loadSelectedSales(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllKecamaten: ${err.statusText || err}`,
                });
            });

            $('#new_customer_buku_tamu').css('cursor', 'pointer').on('click', function() {
                $('#modalNewCustomerBukuTamu').modal('show');
            });

            $('#modalNewCustomerBukuTamu').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#modalNewCustomerBukuTamu').on('shown.bs.modal', function() {
                defaultSelect2("#kd_provinsi", "-- PILIH PROVINSI --", '#modalNewCustomerBukuTamu');
                defaultSelect2("#kd_kota_kabupaten", "-- PILIH KOTA/KABUPATEN --", '#modalNewCustomerBukuTamu');
                defaultSelect2("#kd_kecamatan", "-- PILIH KECAMATAN --", '#modalNewCustomerBukuTamu');
                defaultSelect2("#kd_alasan_kunjungan_buku_tamu", "-- PILIH ALASAN KUNJUNGAN --", '#modalNewCustomerBukuTamu');
                defaultSelect2("#kd_sumber_informasi_buku_tamu", "-- PILIH SUMBER INFORMASI --", '#modalNewCustomerBukuTamu');
                defaultSelect2("#kd_sumber_informasi_detail_buku_tamu", "-- PILIH SUMBER INFORMASI DETAIL --", '#modalNewCustomerBukuTamu');
                defaultSelect2("#kd_master_sales", "-- PILIH SALES --", '#modalNewCustomerBukuTamu');
            });

            $('#modalNewCustomerBukuTamu').on('hidden.bs.modal', function() {
                $(this).find('input').val('');
                $(this).find('select').val('');
                $(this).find('textarea').val('');
                $('#group_alasan_kunjungan').hide();
                $('#kunjungan_detail').hide();
                $(this).find(':focus').blur();
            });

            $('#nama_pengunjung, #alamat_detail, #note, #alasan_kunjungan_detail, #detail_sumber_informasi').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#kd_provinsi').on('change', function() {
                const selectedProvinsi = $(this).val();

                if (selectedProvinsi) {
                    let filterKobakabupaten = dataKotaKabupaten.filter((it) => it.kd_provinsi === selectedProvinsi)
                    loadSelectKotakabupaten(filterKobakabupaten)
                    $('#kd_kota_kabupaten').prop('disabled', false)
                } else {
                    $('#kd_kota_kabupaten')
                        .empty()
                        .append('<option value="" disabled selected>-- Pilih Kota/Kabupaten --</option>')
                        .prop('disabled', true);
                    $('#kd_kecamatan')
                        .empty()
                        .append('<option value="" disabled selected>-- Pilih Kecamatan --</option>')
                        .prop('disabled', true);
                }
            });

            $('#kd_kota_kabupaten').on('change', function() {
                const selectedkotakabupaten = $(this).val();

                if (selectedkotakabupaten) {
                    let filterkecamatan = dataKecamatan.filter((it) => it.kd_kota_kabupaten === selectedkotakabupaten)
                    loadSelectKecamatan(filterkecamatan)
                    $('#kd_kecamatan').prop('disabled', false)
                } else {
                    $('#kd_kecamatan')
                        .empty()
                        .append('<option value="" disabled selected>-- Pilih Kecamatan --</option>')
                        .prop('disabled', true);
                }
            });

            $('#kd_alasan_kunjungan_buku_tamu').on('change', function() {
                const selectAlasanKunjungan = $(this).val();
                let filterAlasanKunjungan = dataAlasanKunjungan.filter((it) => it.kd_alasan_kunjungan_buku_tamu === selectAlasanKunjungan)

                if (selectAlasanKunjungan) {
                    if (filterAlasanKunjungan[0].nama_alasan_kunjungan === "LAINNYA") {
                        $('#kunjungan_detail').show();
                        $('#group_alasan_kunjungan').hide();
                    } else {
                        $('#kunjungan_detail').hide();
                        $('#group_alasan_kunjungan').show();
                    }
                } else {
                    $('#kunjungan_detail').hide();
                    $('#group_alasan_kunjungan').hide();
                }
            });

            $('#kd_sumber_informasi_buku_tamu').on('change', function() {
                const selectSumberinformasi = $(this).val();
                let filterSumberInformasi = dataSumberInformasi.filter((it) => it.kd_sumber_informasi_buku_tamu === selectSumberinformasi)
                let filterSumberInformasiDetail = dataSumberInformasiDetail.filter((it) => it.kd_sumber_informasi_buku_tamu === selectSumberinformasi)

                if (selectSumberinformasi) {
                    if (filterSumberInformasi[0].nm_sumber_informasi === "LAINNYA") {
                        $('#kd_sumber_informasi_detail_buku_tamu').val('');
                        $('#sumber_informasi_buku_tamu').show();
                        $('#sumber_informasi_detail_buku_tamu').hide();
                    } else {
                        loadSelectedSumberInformasiDetail(filterSumberInformasiDetail)
                        $('#sumber_informasi_buku_tamu').hide();
                        $('#detail_sumber_informasi').val('');
                        $('#sumber_informasi_detail_buku_tamu').show();
                    }
                }
            });

            $('#btnSimpanNewCustomerBukuTamu').on('click', () => {
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
                        simpanDataNewCustomerBukuTamu()
                    }
                });
            });
        });

        const loadSelectProvinsi = (data) => {
            // let optionProvinsi = $('#kd_provinsi');

            // optionProvinsi.empty();
            // optionProvinsi.append('<option value="" disabled selected>-- PILIH PROVINSI --</option>');

            // data.forEach(item => {
            //     optionProvinsi.append(`<option value="${item.kd_provinsi}">${item.nama_provinsi}</option>`);
            // })

            loadSelectOptions('#kd_provinsi', data, 'kd_provinsi', 'nama_provinsi', '-- PILIH PROVINSI --');
        }

        const loadSelectKotakabupaten = (data) => {
            loadSelectOptions('#kd_kota_kabupaten', data, 'kd_kota_kabupaten', 'nama_kota_kabupaten', '-- PILIH KOTA/KABUPATEN --');
        }

        const loadSelectKecamatan = (data) => {
            loadSelectOptions('#kd_kecamatan', data, 'kd_kecamatan', 'nama_kecamatan', '-- PILIH KECAMATAN --');
        }

        const loadSelectAlasanKunjungan = (data) => {
            loadSelectOptions('#kd_alasan_kunjungan_buku_tamu', data, 'kd_alasan_kunjungan_buku_tamu', 'nama_alasan_kunjungan', '-- PILIH ALASAN KUNJUNGAN --');
        }

        const loadSelectedSumberInformasi = (data) => {
            loadSelectOptions('#kd_sumber_informasi_buku_tamu', data, 'kd_sumber_informasi_buku_tamu', 'nm_sumber_informasi', '-- PILIH SUMBER INFORMASI --');
        }

        const loadSelectedSumberInformasiDetail = (data) => {
            loadSelectOptions('#kd_sumber_informasi_detail_buku_tamu', data, 'kd_sumber_informasi_detail_buku_tamu', 'nm_sumber_informasi_detail', '-- PILIH SUMBER INFORMASI DETAIL --');
        }

        const loadSelectedSales = (data) => {
            // let optionSales = $('#kd_master_sales');
            // console.log('as', data)

            // optionSales.empty();
            // optionSales.append('<option value="" disabled selected>-- PILIH SALES --</option>');

            // data.forEach(item => {
            //     optionSales.append(`<option value="${item.kd_master_sales}">${item.karyawan.nama_karyawan}</option>`);
            // })

            loadSelectOptions('#kd_master_sales', data, 'kd_master_sales', 'karyawan.nama_karyawan', '-- PILIH SALES --');
        }
    </script>