<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <button id="exportToPdf" class="btn btn-primary">Export <i class="fa-solid fa-file-pdf"></i></button>
                    <table id="tableuser" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Nama User</td>
                                <td>Level User</td>
                                <td>Password</td>
                                <td>Tanggal Registrasi</td>
                                <td>Foto User</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <?php require APPROOT . '/views/modalNotification/modalMessage.php'; ?>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: `<?= BASEURL; ?>/user/allDataUser`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        let res = response.data
                        loadData(res)
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

            $('#exportToPdf').on('click', exportToPdf);

            $('#modalAlert').click(function() {
                if ($('#modalMessageTitle').text() === 'Success') {
                    $('#modalMessage').modal('hide');
                    window.reload();
                } else {
                    $('#modalMessage').modal('hide');
                }
            });
        })

        const showModalMessage = (title, message, type) => {
            $('#modalMessageTitle').text(title);
            $('#modalMessageBody').text(message);
            $('#modalMessage').modal('show');
        };

        const tampilUbah = (data) => {
            let dataTemp = {
                kd_user: data.kd_asli_user,
                nama_user: data.nama_user,
                id_usr_level: data.id_usr_level,
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

        const loadData = (data) => {
            $('#tableuser').DataTable({
                data: data,
                columns: [{
                        data: null,
                        width: '5%',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama_user',
                    },
                    {
                        data: 'nama_user',
                    },
                    {
                        data: 'password_tampil',
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return moment(row.tgl_input).format('DD MMMM YYYY');
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            if (row.img_user) {
                                return `<img src="<?= BASEURL; ?>/img/user/${row.img_user}.${row.format_img_user}" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%;">`;
                            } else {
                                return `<img src="<?= BASEURL; ?>/img/default/Default-Profile.png" alt="Default Image" style="width: 50px; height: 50px; border-radius: 50%;">`;
                            }
                        }
                    },
                    {
                        data: null,
                        title: "Aksi",
                        render: function(data, type, row, meta) {
                            return `
                        <div>
                            <button type="button" class="btn btn-warning btn-sm" btnUbah" onclick='tampilUbah(${JSON.stringify(data)})' data-bs-toggle="modal"
                                data-bs-target="#modalUbahUser">
                                <i class="fas fa-edit"></i> Ubah
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btnHapus" onclick='' data-toggle="modal" data-target="">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                        `
                        }
                    }
                ],
                initComplete: function() {
                    $('#tableuser tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tableuser tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                }
            })
        }

        const exportToPdf = () => {
            let tableData = $('#tableuser').DataTable().rows({
                filter: 'applied'
            }).data().toArray();

            $.ajax({
                url: `<?= BASEURL; ?>/user/exportUserToPdf`,
                method: 'POST',
                dataType: 'json',
                data: {
                    data: tableData
                },
            })
        }
    </script>