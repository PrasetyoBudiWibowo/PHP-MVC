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
                <div class="sb-sidenav-menu-heading">MENU UTAMA</div>
                <a class="nav-link" href="<?= BASEURL; ?>/hrd">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard HRD
                </a>
                <div class="sb-sidenav-menu-heading">MENU</div>

                <!-- Master Data -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMasterDataHrd"
                    aria-expanded="false" aria-controls="collapseMasterDataHrd">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bars"></i></div>
                    MASTER DATA
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseMasterDataHrd" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <!-- Divisi -->
                        <a class="nav-link" href="<?= BASEURL ?>/hrd/divisi">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                            Divisi
                        </a>

                        <!-- Departement -->
                        <a class="nav-link" href="<?= BASEURL ?>/hrd/departement">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                            Departement
                        </a>

                        <!-- Position -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapsePosition" aria-expanded="false" aria-controls="collapsePosition" id="posisiHeading">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                            Posisi
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePosition" aria-labelledby="posisiHeading">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= BASEURL ?>/hrd/posisition_title">Nama Posisi</a>
                            </nav>
                        </div>

                        <!-- Karyawan -->
                        <a class="nav-link" href="<?= BASEURL ?>/hrd/master_karyawan">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                            Karyawan
                        </a>
                    </nav>
                </div>

                <!-- MENU FAKER -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTestMenuHrd"
                    aria-expanded="false" aria-controls="collapseTestMenuHrd">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bars"></i></div>
                    Test
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseTestMenuHrd" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?= BASEURL ?>/hrd/dummy_karyawan ">Fake Karyawan</a>
                    </nav>
                </div>

                <a class="nav-link" href="#" id="ubahDataByUser">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                    Ubah Data
                </a>
            </div>
        </div>
    </nav>
</div>
<script>
    $(document).ready(function() {
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
    })

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
</script>