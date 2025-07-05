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
                <a class="nav-link" href="<?= BASEURL; ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard Buku Tamu
                </a>
                <div class="sb-sidenav-menu-heading">MENU</div>

                <!-- Master Data -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseMasterDataBukutamu"
                    aria-expanded="false" aria-controls="collapseMasterDataBukutamu">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bars"></i></div>
                    MASTER DATA
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseMasterDataBukutamu" aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseSumberInformasiBukuTamu" aria-expanded="false" aria-controls="collapseSumberInformasiBukuTamu" id="posisiHeading">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                            Sumber Informasi
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseSumberInformasiBukuTamu" aria-labelledby="posisiHeading">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link m-1" href="<?= BASEURL; ?>/bukutamu/sumber_informasi_buku_tamu">
                                    Sumber Informasi
                                </a>
                                <a class="nav-link m-1" href="<?= BASEURL; ?>/bukutamu/sumber_informasi_detail_buku_tamu">
                                    Sumber Informasi Detail
                                </a>
                            </nav>
                        </div>
                        <a class="nav-link" href="<?= BASEURL; ?>/bukutamu/alasan_kunjungan_buku_tamu">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                            Alasan Kunjungan
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
                        <a class="nav-link" href="<?= BASEURL; ?>/bukutamu/fake_pengunjung">Data Dummy Buku Tamu</a>
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