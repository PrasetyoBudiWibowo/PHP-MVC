<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mt-2">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    <?= $data['judul']; ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="fileExcelProvinsi" class="form-label">Import Excel</label>
                            <input type="file" id="fileExcelProvinsi" class="form-control" accept=".xlsx, .xls, .csv">
                        </div>
                    </div>
                    <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                    <div class="row mt-4" id="addExcelProvinsi">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblImportProvinsi" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Id</th>
                                            <th>Nama Provinsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="btnSimpanExcelProvinsi" disabled>
                        Simpan Data Excel Provinsi
                    </button>
                </div>
            </div>

            <?php require APPROOT . '/views/modalNotification/modalMessage.php'; ?>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            let table = $('#tblImportProvinsi').DataTable({
                "responsive": true,
                "paging": true,
                "searching": false,
                "scrollX": true,
                "columnDefs": [{
                        "targets": [0],
                        "width": "50px",
                    },
                    {
                        "targets": [1],
                        "visible": false
                    },
                    {
                        "targets": [3],
                        "orderable": false,
                        "searchable": false,
                        "width": "50px",
                        "className": "text-center",
                        "render": function() {
                            return '<i class="fa-solid fa-trash-can text-danger delete-row" style="cursor:pointer"></i>';
                        }
                    }
                ]
            });

            $('#modalAlert').click(function() {
                const title = $('#modalMessageTitle').text();
                $('#modalMessage').modal('hide');
                if (title === 'Success') {
                    location.reload();
                }
            });

            $('#fileExcelProvinsi').on('change', function(e) {
                const file = e.target.files[0];

                if (!file) {
                    showModalMessage('Error', "Silakan Pilih File Excel.", 'error');
                    return;
                }

                const fileName = file.name;
                const fileExtension = fileName.split('.').pop().toLowerCase();
                const allowedExtensions = ['xls', 'xlsx', 'csv'];

                if (!allowedExtensions.includes(fileExtension)) {
                    showModalMessage('Error', "Hanya file Excel (xls, xlsx) dan CSV yang diijinkan.", 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const data = new Uint8Array(event.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const excelData = XLSX.utils.sheet_to_json(firstSheet, {
                        header: 1
                    });

                    table.clear().draw();
                    excelData.forEach(function(r, idx) {
                        if (idx > 0 && r.length > 0) {
                            table.row.add([
                                idx,
                                r[0],
                                r[1],
                                null,
                            ]).draw();
                        }
                    });
                    if (excelData.length > 1) {
                        $('#btnSimpanExcelProvinsi').prop('disabled', false);
                    }
                };

                reader.readAsArrayBuffer(file);
            })

            $('#tblImportProvinsi tbody').on('click', '.delete-row', function() {
                table.row($(this).parents('tr')).remove().draw();
            });

            $('#btnSimpanExcelProvinsi').on('click', simpanDataExcelProvinsi)
        });

        const showModalMessage = (title, message, type) => {
            $('#modalMessageTitle').text(title);
            $('#modalMessageBody').text(message);
            $('#modalMessage').modal('show');
        };

        const simpanDataExcelProvinsi = () => {
            const csrfToken = $('#_csrf_token').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');
            let tableDataExcel = $('#tblImportProvinsi').DataTable().rows({
                filter: 'applied'
            }).data().toArray();


            let dataToSend = {
                csrf_token: csrfToken,
                data: tableDataExcel.map((it, ix) => ({
                    id_provinsi: it[1].toString(),
                    nama_provinsi: it[2],
                    kd_user: user_input,
                }))
            };

            // console.log('datase', dataToSend)

            $.ajax({
                url: "<?= BASEURL; ?>/wilayah/validasiExcelProvinsi",
                method: 'POST',
                data: JSON.stringify(dataToSend),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showModalMessage('Success', response.message, 'success');
                    } else {
                        showModalMessage('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    showModalMessage('Error', xhr.responseText, 'error');
                }
            })

        }
    </script>