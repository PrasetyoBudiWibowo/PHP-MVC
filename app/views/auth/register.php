<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Register</title>
    <link href="../../public/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">
                                        Create Account
                                    </h3>
                                </div>
                                <?php if (isset($data['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php echo $data['error']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <form action="<?= BASEURL; ?>/auth/register" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                        <label for="nama_user">Nama User:</label>
                                        <input class="form-control" type="text" name="nama_user" id="nama_user" required autocomplete="off"><br>
                                        <label for="level_user">Level User:</label>
                                        <select class="form-control" name="id_usr_level" id='id_usr_level' required>
                                            <?php foreach ($levels as $level): ?>
                                                <option value="<?php echo $level['id']; ?>"><?php echo $level['level_user']; ?></option>
                                            <?php endforeach; ?>
                                        </select><br>
                                        <label for="password">Password:</label>
                                        <input class="form-control" type="text" name="password" required autocomplete="off"><br>
                                        <button class="btn btn-primary btn-block" type="submit">Register</button>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">
                                        <a href="<?php echo BASEURL; ?>/auth/login">Have an account? Go to login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- jQuery (dibutuhkan untuk Select2) -->
    <script src="<?= BASEURL; ?>/js/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../../public/js/scripts.js"></script>
    <!-- Inisialisasi Select2 -->
    <script>
        $(document).ready(function() {
            $('#nama_user').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });
        });
    </script>
</body>

</html>