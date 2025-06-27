<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login</title>
    <link href="<?= BASEURL; ?>/css/styles.css" rel="stylesheet" />
    <link href="<?= BASEURL; ?>/select2-4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASEURL; ?>/DataTables/datatables.css" rel="stylesheet" />


    <script src="<?= BASEURL; ?>/js/jquery-3.6.0.min.js"></script>
    <script src="<?= BASEURL; ?>/DataTables/datatables.js"></script>
    <script src="<?= BASEURL; ?>/font-awesome-4.7.0/css/font-awesome.min.css" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Login</h3>
                                </div>
                                <?php if (isset($data['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php echo $data['error']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <form action="<?= BASEURL; ?>/auth/login" method="POST">
                                        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                        <label for="nama_user">Nama User:</label>
                                        <input class="form-control" type="text" name="nama_user" required
                                            autocomplete="off"><br>
                                        <label for="password">Password:</label>
                                        <input class="form-control" type="password" name="password" required autocomplete="off"><br>
                                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">
                                        <a href="<?= BASEURL; ?>/auth/register">Need an account? Sign up!</a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="../../public/js/scripts.js"></script>
</body>

</html>