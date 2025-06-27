<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 mb-3">
            <h1 class="mt-4">ANDA SEDANG BERADA DI MODULE HRD</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">ANDA SEDANG BERADA DI MODULE HRD</li>
            </ol>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>KARYAWAN SAAT INI</h4>
                    <button class="btn btn-sm btn-primary toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#cardContent1" aria-expanded="false" aria-controls="cardContent1">
                        <i class="fas fa-plus toggle-icon"></i>
                    </button>
                </div>

                <div id="cardContent1" class="collapse">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6" id="pie-karyawan"></div>
                            <div class="col-6" id="thn-lahir-karyawan"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>KARYAWAN BERGABUNG</h4>
                    <button class="btn btn-sm btn-primary toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#cardContent2" aria-expanded="false" aria-controls="cardContent2">
                        <i class="fas fa-plus toggle-icon"></i>
                    </button>
                </div>

                <div id="cardContent2" class="collapse">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12" id="thn-gabung-karyawan"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <script>
        const url = "<?= BASEURL ?>";
        $(document).ready(function() {
            getAllDataKaryawanNew(url).then(data => {
                let jumlahKaryawan = data.length;

                const chartData = Object.entries(
                    data.reduce((acc, item) => {
                        const gender = (item.gender || 'TIDAK DIISI').toUpperCase();
                        acc[gender] = (acc[gender] || 0) + 1;
                        return acc;
                    }, {})
                ).map(([gender, count]) => ({
                    name: gender,
                    y: count
                }));

                Highcharts.chart('pie-karyawan', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Gender Karyawan'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'Gender',
                        colorByPoint: true,
                        data: chartData
                    }]
                });


                const yearCounts = data.reduce((acc, item) => {
                    const year = item.thn_lahir || 'Tidak Diketahui';
                    acc[year] = (acc[year] || 0) + 1;
                    return acc;
                }, {});

                const sortedYears = Object.keys(yearCounts).sort((a, b) => a - b);

                const categories = sortedYears;
                const counts = sortedYears.map(year => yearCounts[year]);

                Highcharts.chart('thn-lahir-karyawan', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Jumlah Karyawan Berdasarkan Tahun Lahir'
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: 'Tahun Lahir'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: `Jumlah Karyawan ${jumlahKaryawan}`
                        },
                        allowDecimals: false
                    },
                    series: [{
                        name: `Jumlah`,
                        data: counts
                    }]
                });


                const thnBergabung = data.reduce((acc, item) => {
                    const year = item.thn_bergabung || 'Tidak diketahui';
                    acc[year] = (acc[year] || 0) + 1;
                    return acc;
                }, {})

                const urutThnGabung = Object.keys(thnBergabung).sort((a, b) => a - b);

                const thngabung = urutThnGabung;
                const seriesData = urutThnGabung.map(year => thnBergabung[year]);

                Highcharts.chart('thn-gabung-karyawan', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Jumlah Karyawan Berdasarkan Tahun Bergabung'
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: 'Tahun Bergabung'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Jumlah Karyawan'
                        },
                        allowDecimals: false
                    },
                    series: [{
                        name: 'Karyawan',
                        data: seriesData
                    }],
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>'
                    },
                    credits: {
                        enabled: false
                    }
                });


            }).catch(err => {
                showAlert('error', 'Gagal', err.message);
            });

            $('.collapse').on('show.bs.collapse', function() {
                $(this).prev().find('.toggle-icon').removeClass('fa-plus').addClass('fa-minus');
            });

            $('.collapse').on('hide.bs.collapse', function() {
                $(this).prev().find('.toggle-icon').removeClass('fa-minus').addClass('fa-plus');
            });
        })
    </script>