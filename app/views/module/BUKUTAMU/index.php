<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 mb-3">
            <h1 class="mt-4">ANDA SEDANG BERADA DI MODULE BUKUTAMU</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">ANDA SEDANG BERADA DI MODULE BUKUTAMU</li>
            </ol>

            <div class="row">
                <div class="col-4">
                    <div class="card" id="new-customer">
                        <div class="card-header position-relative d-flex justify-content-center align-items-center">
                            <span class="fw-bold text-center">BUKU TAMU</span>
                            <button class="btn btn-sm btn-primary toggle-btn position-absolute end-0 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#cardContent2" aria-expanded="false" aria-controls="cardContent2">
                                <i class="fas fa-plus toggle-icon"></i>
                            </button>
                        </div>

                        <div id="cardContent2" class="collapse">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <img src="<?= BASEURL; ?>/img/tools/image-book1.png" alt="" id="input-customer" style="cursor: pointer; width: 300px; height: 300px;">
                                <!-- <i class="fa-solid fa-book-open fa-8x" id="input-customer" style="cursor: pointer;"></i> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <strong>SEMUA KUNJUNGAN</strong>
                        </div>
                        <div class="card-body">
                            <div class="col-12" id="kunjungan_per_tahun"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <strong>KUNJUNGAN PER BULAN PER</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <select class="form-control" id="tahun_kunjungan" name="tahun_kunjungan">
                                        <option value="">-- Pilih Tahun --</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12" id="kunjungan_per_bln_tahun"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <strong>ALASAN KUNJUNGAN</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <select class="form-control" id="filter_alasan_kunjungan" name="filter_alasan_kunjungan">
                                        <option value="">-- Pilih Tahun --</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12" id="kunjungan_by_alasan_per_bln_tahun"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <strong>WILAYAH PENGUNJUNG</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <select class="form-control" id="filter_by_wilayah" name="filter_by_wilayah">
                                        <option value="">-- Pilih Tahun --</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="col-12" id="kunjungan_by_provinsi_per_bln_tahun"></div>
                                </div>

                                <div class="col-6">
                                    <div class="col-12" id="kunjungan_by_kota_kabupaten_per_bln_tahun"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6" id="kunjungan_by_kecamatan_per_bln_tahun"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <script>
        const url = "<?= BASEURL ?>";
        let dataKunjunaganBukuTamuPerTahun = [];
        let dataBulan = getSemuaBulan();

        $(document).ready(function() {
            getAllKunjunganBukutamu(url).then(data => {
                dataKunjunaganBukuTamuPerTahun = data
                let tanggalKunjungan = data.map(item => moment(item.tgl_kunjungan, 'YYYY-MM-DD'));

                let tanggalPalingAwal = moment.min(tanggalKunjungan).format('YYYY');
                let tanggalPalingAkhir = moment.max(tanggalKunjungan).format('YYYY');

                let dataTahun = getTahun(tanggalPalingAwal, tanggalPalingAkhir)

                loadAllKunjungan(data)
                loadKunjunganPerTahun(data)
                loadAlasanKunjungan(data)
                loadFilterTahun(dataTahun)
                loadProvinsiKunjungan(data)
                loadKotaKabupatenKunjungan(data)
                loadKecamatanKunjungan(data)
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: `Terjadi kesalahan getAllKunjunganBukutamu: ${err.statusText || err}`,
                });
            });

            $('#input-customer').css('cursor', 'pointer').on('click', function() {
                window.location.href = `<?= BASEURL; ?>/bukutamu/input_pengunjung`;
            });

            defaultSelect2('#tahun_kunjungan', '-- Pilih Tahun --')
            defaultSelect2('#filter_alasan_kunjungan', '-- Pilih Tahun --')
            defaultSelect2('#filter_by_wilayah', '-- Pilih Tahun --')

            $('#tahun_kunjungan').on('change', function() {
                let tahun = $('#tahun_kunjungan').val();

                if (tahun) {
                    let filterKunjungan = dataKunjunaganBukuTamuPerTahun.filter((it) => it.thn_kunjungan === tahun)
                    loadKunjunganPerTahun(filterKunjungan)
                } else {
                    let data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    loadKunjunganPerTahun(data)
                }
            })

            $('#filter_alasan_kunjungan').on('change', function() {
                let tahun = $('#filter_alasan_kunjungan').val();

                if (tahun) {
                    let filterKunjungan = dataKunjunaganBukuTamuPerTahun.filter((it) => it.thn_kunjungan === tahun)
                    loadAlasanKunjungan(filterKunjungan)
                } else {
                    let data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    loadAlasanKunjungan(data)
                }
            })

            $('#filter_by_wilayah').on('change', function() {
                let tahun = $('#filter_by_wilayah').val();

                if (tahun) {
                    let filterKunjungan = dataKunjunaganBukuTamuPerTahun.filter((it) => it.thn_kunjungan === tahun)
                    loadProvinsiKunjungan(filterKunjungan)
                    loadKotaKabupatenKunjungan(filterKunjungan)
                    loadKecamatanKunjungan(filterKunjungan)
                } else {
                    let data = []
                    loadProvinsiKunjungan(data)
                    loadKotaKabupatenKunjungan(data)
                    loadKecamatanKunjungan(data)
                }
            })
        })

        const loadFilterTahun = (data) => {
            loadSelectOptions('#tahun_kunjungan', data, 'tahun', 'tahun', '-- Pilih Tahun --')
            loadSelectOptions('#filter_alasan_kunjungan', data, 'tahun', 'tahun', '-- Pilih Tahun --')
            loadSelectOptions('#filter_by_wilayah', data, 'tahun', 'tahun', '-- Pilih Tahun --')
        }

        const loadAllKunjungan = (data) => {
            const kunjunganPerTahun = {};

            data.forEach(item => {
                const tahun = item.thn_kunjungan;
                if (!kunjunganPerTahun[tahun]) {
                    kunjunganPerTahun[tahun] = 0;
                }
                kunjunganPerTahun[tahun]++;
            });

            const categories = Object.keys(kunjunganPerTahun);
            const dataSeries = Object.values(kunjunganPerTahun);

            Highcharts.chart('kunjungan_per_tahun', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Jumlah Kunjungan per Tahun'
                },
                xAxis: {
                    categories: categories,
                    title: {
                        text: 'Tahun'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Kunjungan'
                    },
                    allowDecimals: false
                },
                series: [{
                    name: 'Total Kunjungan',
                    data: dataSeries
                }],
            });
        }

        const loadKunjunganPerTahun = (data) => {
            let tahun = $('#tahun_kunjungan').val();
            let jumlahPerBulan = new Array(12).fill(0);

            if (tahun) {
                data.filter(item => item.thn_kunjungan === tahun).forEach(item => {
                    let bulanIndex = dataBulan.findIndex(b => b.bln_dlm_angka === item.bln_kunjungan);
                    if (bulanIndex >= 0) {
                        jumlahPerBulan[bulanIndex]++;
                    }
                });
            }

            let totalKunjungan = jumlahPerBulan.reduce((a, b) => a + b, 0);

            Highcharts.chart('kunjungan_per_bln_tahun', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: tahun ?
                        `Jumlah Kunjungan per Bulan - Tahun ${tahun} (Total: ${totalKunjungan})` : 'Pilih Tahun untuk Menampilkan Data'
                },
                xAxis: {
                    categories: dataBulan.map(b => b.nama_bulan),
                    title: {
                        text: 'Bulan'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Kunjungan'
                    },
                    allowDecimals: false
                },
                series: [{
                    name: 'Kunjungan',
                    data: jumlahPerBulan
                }],
                tooltip: {
                    valueSuffix: ' kunjungan'
                }
            });
        }

        const loadAlasanKunjungan = (data) => {
            let tahun = $('#filter_alasan_kunjungan').val();

            const alasanUnik = [...new Set(data.map(item => item.alasan_kunjungan?.nama_alasan_kunjungan || 'Tidak Diketahui'))];

            const dataTahun = tahun ?
                data.filter(item => item.thn_kunjungan === tahun) : [];

            const seriesData = alasanUnik.map(namaAlasan => {
                const jumlahPerBulan = new Array(12).fill(0);

                dataTahun.forEach(item => {
                    const alasan = item.alasan_kunjungan?.nama_alasan_kunjungan || 'Tidak Diketahui';
                    if (alasan === namaAlasan) {
                        const bulanIndex = dataBulan.findIndex(b => b.bln_dlm_angka === item.bln_kunjungan);
                        if (bulanIndex >= 0) {
                            jumlahPerBulan[bulanIndex]++;
                        }
                    }
                });

                const totalAlasan = jumlahPerBulan.reduce((a, b) => a + b, 0);

                return {
                    name: `${namaAlasan} (${totalAlasan})`,
                    data: jumlahPerBulan,
                    showInLegend: true
                };
            });



            const totalKunjungan = dataTahun.length;

            Highcharts.chart('kunjungan_by_alasan_per_bln_tahun', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: tahun ?
                        `Kunjungan Berdasarkan Alasan Kunjungan ${tahun} (Total: ${totalKunjungan})` : `Pilih Tahun untuk Menampilkan Data`
                },
                xAxis: {
                    categories: dataBulan.map(b => b.nama_bulan),
                    title: {
                        text: 'Bulan'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Kunjungan'
                    },
                    allowDecimals: false
                },
                tooltip: {
                    shared: true,
                    crosshairs: true,
                    valueSuffix: ' kunjungan'
                },
                legend: {
                    enabled: true,
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    itemStyle: {
                        fontSize: '13px'
                    }
                },
                series: seriesData
            });
        }

        const loadProvinsiKunjungan = (data) => {
            const tahun = $('#filter_by_wilayah').val();

            if (!tahun) {
                Highcharts.chart('kunjungan_by_provinsi_per_bln_tahun', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Pilih Tahun untuk Menampilkan Data'
                    },
                    series: [{
                        name: 'Kunjungan',
                        data: []
                    }],
                    xAxis: {
                        categories: []
                    },
                    yAxis: {
                        title: {
                            text: ''
                        }
                    }
                });
                return;
            }

            const dataTahun = data.filter(item => item.thn_kunjungan === tahun);

            const kunjunganProvinsi = {};
            dataTahun.forEach(item => {
                const namaProvinsi = item.provinsi?.nama_provinsi || 'Tidak Diketahui';
                kunjunganProvinsi[namaProvinsi] = (kunjunganProvinsi[namaProvinsi] || 0) + 1;
            });

            const seriesData = Object.entries(kunjunganProvinsi).map(([nama, total]) => ({
                name: `${nama} (${total})`,
                y: total
            }));

            Highcharts.chart('kunjungan_by_provinsi_per_bln_tahun', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: `Distribusi Kunjungan Berdasarkan Provinsi - Tahun ${tahun}`
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y} kunjungan</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: ' kunjungan'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Kunjungan',
                    colorByPoint: true,
                    data: seriesData
                }]
            });
        }

        const loadKotaKabupatenKunjungan = (data) => {
            const tahun = $('#filter_by_wilayah').val();

            if (!tahun) {
                Highcharts.chart('kunjungan_by_kota_kabupaten_per_bln_tahun', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Pilih Tahun untuk Menampilkan Data'
                    },
                    series: [{
                        name: 'Kunjungan',
                        data: []
                    }],
                    xAxis: {
                        categories: []
                    },
                    yAxis: {
                        title: {
                            text: ''
                        }
                    }
                });
                return;
            }

            const dataTahun = data.filter(item => item.thn_kunjungan === tahun);

            const kunjunganKotaKabupaten = {};
            dataTahun.forEach(item => {
                const namaKotaKabupaten = item.kota_kabupaten?.nama_kota_kabupaten || 'Tidak Diketahui';
                kunjunganKotaKabupaten[namaKotaKabupaten] = (kunjunganKotaKabupaten[namaKotaKabupaten] || 0) + 1;
            });

            const seriesData = Object.entries(kunjunganKotaKabupaten).map(([nama, total]) => ({
                name: `${nama} (${total})`,
                y: total
            }));

            Highcharts.chart('kunjungan_by_kota_kabupaten_per_bln_tahun', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: `Distribusi Kunjungan Berdasarkan Kota/Kabupaten - Tahun ${tahun}`
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y} kunjungan</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: ' kunjungan'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Kunjungan',
                    colorByPoint: true,
                    data: seriesData
                }]
            });
        }

        const loadKecamatanKunjungan = (data) => {
            const tahun = $('#filter_by_wilayah').val();

            if (!tahun) {
                Highcharts.chart('kunjungan_by_kecamatan_per_bln_tahun', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Pilih Tahun untuk Menampilkan Data'
                    },
                    series: [{
                        name: 'Kunjungan',
                        data: []
                    }],
                    xAxis: {
                        categories: []
                    },
                    yAxis: {
                        title: {
                            text: ''
                        }
                    }
                });
                return;
            }

            const dataTahun = data.filter(item => item.thn_kunjungan === tahun);

            const kunjunganByKecamatan = {};
            dataTahun.forEach(item => {
                const namaKotaKabupaten = item.kecamatan?.nama_kecamatan || 'Tidak Diketahui';
                kunjunganByKecamatan[namaKotaKabupaten] = (kunjunganByKecamatan[namaKotaKabupaten] || 0) + 1;
            });

            const seriesData = Object.entries(kunjunganByKecamatan).map(([nama, total]) => ({
                name: `${nama} (${total})`,
                y: total
            }));

            Highcharts.chart('kunjungan_by_kecamatan_per_bln_tahun', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: `Distribusi Kunjungan Berdasarkan Kecamatan - Tahun ${tahun}`
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y} kunjungan</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: ' kunjungan'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Kunjungan',
                    colorByPoint: true,
                    data: seriesData
                }]
            });
        }
    </script>