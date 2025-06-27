<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= $data['judul']; ?></title>

    <!-- Link -->
    <link href="<?= BASEURL; ?>/css/styles.css" rel="stylesheet" />
    <!-- <link href="<?= BASEURL; ?>/css/select2.min.css" rel="stylesheet" /> -->
    <link href="<?= BASEURL; ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASEURL; ?>/js/DataTables/dataTables.min.css" rel="stylesheet" />
    <link href="<?= BASEURL; ?>/js/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?= BASEURL; ?>/css/sweetalert2.min.css" rel="stylesheet" />
    <link href="<?= BASEURL; ?>/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />

    <!-- Stylesheets -->
    <script src="<?= BASEURL; ?>/js/jquery-3.6.0.min.js"></script>
    <script src="<?= BASEURL; ?>/js/all.js" crossorigin="anonymous"></script>
    <link href="<?= BASEURL; ?>/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js" rel="stylesheet" />
    <!-- <script src="<?= BASEURL; ?>/js/jquery-3.7.0.min.js"></script> -->
    <script src="<?= BASEURL; ?>/js/sweetalert2.all.js"></script>
    <script src="<?= BASEURL; ?>/js/sweetalert2.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/id.min.js"></script> -->
    
    <script src="<?= BASEURL; ?>/js/dataTables.js"></script>
    <script src="<?= BASEURL; ?>/js/moment.js"></script>
    <!-- <script src="<?= BASEURL; ?>/js/id.min.js"></script> -->
    <script src="<?= BASEURL; ?>/js/xlsx.full.min.js"></script>
    <script type="text/javascript" src="<?= BASEURL; ?>/js/helper/helper.js"></script>
    <script type="text/javascript" src="<?= BASEURL; ?>/js/helper/API.js"></script>
    <!-- <script src="<?= BASEURL; ?>/js/select2.min.js"></script> -->
    <script src="<?= BASEURL; ?>/js/select2/dist/js/select2.min.js"></script>
    <script src="<?= BASEURL; ?>/js/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= BASEURL; ?>/js/Highcharts-11.4.8/code/highcharts.js"></script>
</head>

<body class="sb-nav-fixed">
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    ?>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="">Start Bootstrap</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 d-flex align-items-center">
            <li class="nav-item dropdown d-flex align-items-center">
                <?php if ($user): ?>
                    <?php
                    if (!empty($user['img_user'])) {
                        $userImage = '/img/user/' . htmlspecialchars($user['img_user']) . '.' . htmlspecialchars($user['format_img_user']);
                    } else {
                        $userImage = '/img/default/Default-Profile.png';
                    }
                    ?>
                    <img src="<?= BASEURL . $userImage; ?>" alt="Profile Image"
                        class="nav-link dropdown-toggle d-flex align-items-center rounded-circle me-2" width="50"
                        height="50" style="object-fit: cover;" id="navbarDropdown" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                <?php else: ?>
                    <div class="small">Guest</div>
                <?php endif; ?>

                <ul class="dropdown-menu dropdown-menu-end text-center" aria-labelledby="navbarDropdown">
                    <li class="text-center">
                        <?php
                        if (!empty($user['img_user'])) {
                            $userImage = '/img/user/' . htmlspecialchars($user['img_user']) . '.' . htmlspecialchars($user['format_img_user']);
                        } else {
                            $userImage = '/img/default/Default-Profile.png';
                        }
                        ?>
                        <img src="<?= BASEURL . $userImage; ?>" alt="Profile Image" class="rounded-circle" width="80"
                            height="80" style="object-fit: cover;">
                    </li>
                    <li class="text-center">
                        <?php if ($user): ?>
                            <div class="small" id="nama_login"
                                data-nama_login="<?= htmlspecialchars($user['nama_user']); ?>">
                                <?= htmlspecialchars($user['nama_user']); ?>
                            </div>
                            <div class="small" id="kd_asli_user"
                                data-kd_asli_user="<?= htmlspecialchars($user['kd_asli_user']); ?>" style="display: none;">
                                <?= htmlspecialchars($user['kd_asli_user']); ?>
                            </div>
                            <div class="small"><?= htmlspecialchars($user['level_user']); ?></div>
                        <?php else: ?>
                            <div class="small">Guest</div>
                        <?php endif; ?>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="<?= BASEURL; ?>/auth/logout">Logout</a></li>
                </ul>

            </li>
            <li class="nav-item">
                <span id="realTimeClock" class="navbar-text text-white me-3"></span>
            </li>
        </ul>

    </nav>
    <script>
        $(document).ready(function() {
            $('#sidebarToggle').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('sb-sidenav-toggled');
            });

            function updateClock() {
                $('#realTimeClock').text(moment().format('HH:mm:ss'));
            }
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
    <div id="layoutSidenav">