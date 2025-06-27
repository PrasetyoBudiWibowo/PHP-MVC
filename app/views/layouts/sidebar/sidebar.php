<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
?>
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="<?= BASEURL; ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Interface</div>

                <!-- MODULE -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAksesMenu"
                    aria-expanded="false" aria-controls="collapseAksesMenu">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bars"></i></div>
                    Akses Menu
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAksesMenu" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav" id="modulesContainer"></nav>
                </div>

                <!-- User List -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                    aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Daftar User
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?= BASEURL; ?>/user/tambah_user">Tambah User</a>
                        <a class="nav-link" href="<?= BASEURL; ?>/bukutamu">Test Link</a>
                        <a class="nav-link" href="<?= BASEURL; ?>/user">List User</a>
                    </nav>
                </div>

                <!-- AKSES USER DAN LIST MODULE -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseModule"
                    aria-expanded="false" aria-controls="collapseModule">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bars"></i></div>
                    Tambah Menu
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseModule" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?= BASEURL; ?>/module/list_module">List Module</a>
                        <a class="nav-link" href="<?= BASEURL; ?>/module/list_akses_user">Akses Module</a>
                        <a class="nav-link" href="">List Akses User</a>
                    </nav>
                </div>

                <!-- WILAYAH -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseWilayah"
                    aria-expanded="false" aria-controls="collapseWilayah">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-earth-asia"></i></div>
                    Wilayah
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseWilayah" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">

                        <!-- Provinsi -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseProvinsi" aria-expanded="false" aria-controls="collapseProvinsi">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-map"></i></div>
                            Provinsi
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseProvinsi" aria-labelledby="headingProvinsi"
                            data-bs-parent="#collapseExcelProvinsi">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= BASEURL; ?>/wilayah/list_provinsi">List Provinsi</a>
                                <a class="nav-link" href="<?= BASEURL; ?>/wilayah/import_excel_provinsi">Import Excel
                                    Provinsi</a>
                            </nav>
                        </div>

                        <!-- Kota / Kabupaten -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseKotaKabupaten" aria-expanded="false"
                            aria-controls="collapseKotaKabupaten">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-map"></i></div>
                            Kota / Kabupaten
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseKotaKabupaten" aria-labelledby="headingKotaKabupaten"
                            data-bs-parent="#collapseExcelKotaKabupaten">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= BASEURL; ?>/wilayah/list_kota_kabupaten">List Kota
                                    Kabupaten</a>
                                <a class="nav-link" href="<?= BASEURL; ?>/wilayah/import_excel_kota_kabupaten">Import
                                    Excel Kota Kabupaten</a>
                            </nav>
                        </div>

                        <!-- Kecamatan -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseKecamatan" aria-expanded="false" aria-controls="collapseKecamatan">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-map"></i></div>
                            Kecamatan
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseKecamatan" aria-labelledby="headingKecamatan"
                            data-bs-parent="#collapseExcelKotaKecamatan">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= BASEURL; ?>/wilayah/list_kecamatan">List Kecamatan</a>
                                <a class="nav-link" href="<?= BASEURL; ?>/wilayah/import_excel_kecamatan">Import Excel
                                    Kecamatan</a>
                            </nav>
                        </div>

                    </nav>
                </div>

                <!-- Ubah Data By user -->
                <a class="nav-link" href="#" id="ubahDataByUser">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                    Ubah Data
                </a>
            </div>
        </div>
    </nav>
    <script>
        $(document).ready(function() {

            const loadModules = () => {
                $.ajax({
                    url: `<?= BASEURL ?>/module/allDataModule`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {

                            let dataModule = response.data

                            $('#modulesContainer').empty();
                            if (Array.isArray(dataModule) && dataModule.length) {
                                dataModule.forEach(module => {
                                    $('#modulesContainer').append(`
                                <a class="nav-link akses-module" href="#" data-url="${module.url_module}" data-kode-module="${module.kd_module}">
                                    <i class="fa-regular fa-circle m-1"></i>${module.nama_module}
                                </a>`);
                                });
                            } else {
                                $('#modulesContainer').append('<p>Tidak ada modul yang tersedia.</p>');
                            }
                        } else {
                            alert("Terjadi kesalahan saat mengambil modul.");
                        }
                    },
                    error: function(xhr) {
                        console.error("Kesalahan saat mengambil modul:", xhr.responseText);
                        alert("Terjadi kesalahan saat mengambil modul.");
                    }
                });
            }

            const checkModuleAccess = (data, user) => {
                $.ajax({
                    url: `<?= BASEURL ?>/module/validasiAksesModule`,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        kd_module: data,
                        kd_user: user
                    },
                    success: function(response) {
                        console.log('hasil akses', response)
                        if (response.status === 'success') {
                            const moduleUrl = response.url_module;
                            window.location.href = `<?= BASEURL ?>${moduleUrl}`;
                        } else {
                            alert('Anda tidak memiliki akses ke modul ini.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Kesalahan saat memeriksa akses modul:", xhr.responseText);
                        alert(`Kesalahan saat memeriksa akses modul. ${xhr.responseText}`);
                    }
                })
            }

            $('#ubahDataByUser').on('click', function(event) {
                event.preventDefault();
                $.ajax({
                    url: '<?= BASEURL ?>/user/getUserByLogin',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            console.error(data.error);
                        } else {
                            dataByUser(data)
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching user data:', textStatus, errorThrown);
                    }
                });
            });

            $('#collapseProvinsi, #collapseKotaKabupaten, #collapseKecamatan').on(
                'show.bs.collapse hide.bs.collapse',
                function(e) {
                    const isShow = e.type === 'show';
                    toggleIcon(this, isShow ? 'fa-map' : 'fa-map-location-dot', isShow ?
                        'fa-map-location-dot' : 'fa-map');
                });

            loadModules();

            $('#modulesContainer').on('click', '.akses-module', function(event) {
                event.preventDefault();
                const kodeModule = $(this).data('kode-module');
                let userLogin = $('#kd_asli_user').data('kd_asli_user');
                if (kodeModule) {
                    checkModuleAccess(kodeModule, userLogin);
                }
            });
        });

        const dataByUser = (data) => {
            let dataTemp = {
                kd_user: data.kd_asli_user,
                nama_user: data.username,
                id_usr_level: data.id_level_user,
                password: data.password_tampil,
                status_user: data.status_user,
                blokir: data.blokir,
                img_user: data.img_user,
                format_img_user: data.format_img_user,
                user_input: $('#kd_asli_user').data('kd_asli_user'),
            }
            $.ajax({
                url: `<?= BASEURL; ?>/user/dataTempEdit`,
                method: 'POST',
                data: dataTemp,
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        window.location.href = '<?= BASEURL; ?>/user/ubah';
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })
        }
        const toggleIcon = (target, showClass, hideClass) => {
            $(target).prev('.nav-link').find('.fa-map, .fa-map-location-dot').toggleClass(showClass + ' ' +
                hideClass);
        }
    </script>

</div>