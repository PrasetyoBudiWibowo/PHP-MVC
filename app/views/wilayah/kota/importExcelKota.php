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
                            <label for="fileExcelKotaKabupaten" class="form-label">Import Excel</label>
                            <input type="file" id="fileExcelKotaKabupaten" class="form-control" accept=".xlsx, .xls, .csv">
                        </div>
                    </div>
                    <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                    <div class="row mt-4" id="addExcelKotaKabupaten">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblImportKotaKabupaten" class="table table-striped table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th>No</th>
                                            <th>Id Provinsi</th>
                                            <th>Id Kota/Kabupaten</th>
                                            <th>Nama Kota / Kabupaten</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="btnSimpanExcelKota" disabled>
                        Simpan Data Excel Kota/Kabupaten
                    </button>
                </div>
            </div>

            <?php require APPROOT . '/views/modalNotification/modalConfirmation.php'; ?>

            <?php require APPROOT . '/views/modalNotification/modalMessage.php'; ?>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            let table = $('#tblImportKotaKabupaten').DataTable({
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
                        "targets": [2],
                        "visible": false
                    },
                    {
                        "targets": [4],
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

            $('#fileExcelKotaKabupaten').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    showModalMessage('Error', "Silakan Pilih File Excel.", 'error');
                    return;
                }
                const fileName = file.name;
                const fileExtension = fileName.split('.').pop().toLowerCase();
                const allowedExtensions = ['xls', 'xlsx', 'csv'];
                if (!allowedExtensions.includes(fileExtension)) {
                    showModalMessage('Error', "Hanya file Excel (xls, xlsx) dan CSV yang diijinkan.",
                        'error');
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
                    // console.log('Data Excel:', excelData);
                    table.clear().draw();
                    excelData.forEach(function(r, idx) {
                        if (idx > 0 && r.length > 0) {
                            table.row.add([
                                idx,
                                r[1],
                                r[0],
                                r[2],
                                null,
                            ]).draw();
                        }
                    });
                    if (excelData.length > 1) {
                        $('#btnSimpanExcelKota').prop('disabled', false);
                    }
                };
                reader.readAsArrayBuffer(file);
            });

            $('#modalAlert').click(function() {
                if ($('#modalMessageTitle').text() === 'Success') {
                    $('#modalMessage').modal('hide');
                    location.reload();
                } else {
                    $('#modalMessage').modal('hide');
                }
            });

            $('#tblImportKotaKabupaten tbody').on('click', '.delete-row', function() {
                table.row($(this).parents('tr')).remove().draw();
            });

            $('#btnSimpanExcelKota').on('click', simpanDataExcelKotaKabupaten)
        })

        const showModalMessage = (title, message, type) => {
            $('#modalMessageTitle').text(title);
            $('#modalMessageBody').text(message);
            $('#modalMessage').modal('show');
        };

        const showModalNotificaton = (title, message, onYesCallback, onNoCallback) => {
            $('#modalConfirmationTitle').text(title);
            $('#modalConfirmationBody').text(message);

            $('#modalConfirmationYesButton').off('click').on('click', function() {
                if (typeof onYesCallback === 'function') {
                    onYesCallback();
                }
                $('#modalConfirmation').modal('hide');
            });

            $('#modalConfirmationNoButton').off('click').on('click', function() {
                if (typeof onNoCallback === 'function') {
                    onNoCallback();
                }
                $('#modalMessage').modal('hide');
            });

            $('#modalConfirmation').modal('show');
        };

        const simpanDataExcelKotaKabupaten = () => {
            const csrfToken = $('#_csrf_token').val();
            let user_input = $('#kd_asli_user').data('kd_asli_user');
            let tableDataExcel = $('#tblImportKotaKabupaten').DataTable().rows({
                filter: 'applied'
            }).data().toArray();

            console.log('csrfToken', csrfToken)

            let dataToSend = {
                csrf_token: csrfToken,
                data: tableDataExcel.map((it, ix) => ({
                    id_provinsi: it[1].toString(),
                    id_kota_kabupaten: it[2].toString(),
                    nama_kota_kabupaten: it[3],
                    kd_user: user_input,
                }))
            };

            showModalNotificaton(
                'Konfirmasi Simpan Data Excel kota',
                `Apakah Anda yakin ingin menyimpan data ini?`,
                function() {
                    console.log('Ya simpan data Excel Provinsi');
                    $.ajax({
                        url: "<?= BASEURL; ?>/wilayah/validasiExcelKotaKabupaten",
                        method: 'POST',
                        data: JSON.stringify(dataToSend),
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                showModalMessage('Success', response.message, 'success');
                                $('#modalTambahProvinsi').modal('hide');
                            } else {
                                showModalMessage('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            showModalMessage('Error', xhr.responseText, 'error');
                        }
                    })
                },
                function() {
                    console.log('Tutup Modal Notification');
                }
            )
        }
    </script>