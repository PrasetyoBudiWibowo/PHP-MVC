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
                            <label for="fileExcelKecamatan" class="form-label">Import Excel</label>
                            <input type="file" id="fileExcelKecamatan" class="form-control" accept=".xlsx, .xls, .csv">
                        </div>
                    </div>
                    <input type="hidden" name="_csrf_token" id="_csrf_token" value="<?= htmlspecialchars($data['csrf_token']); ?>">
                    <div class="row mt-4" id="addExcelKecamatan">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tblImportKecamatan" class="table table-striped table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th>No</th>
                                            <th>Id Kota</th>
                                            <th>Id Kecamatan</th>
                                            <th>Nama Kecamatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="btnSimpanExcelKecamatan" disabled>
                        Simpan Data Excel Kecamatan
                    </button>
                </div>
            </div>

            <?php require APPROOT . '/views/modalNotification/modalConfirmation.php'; ?>

            <?php require APPROOT . '/views/modalNotification/modalMessage.php'; ?>

            <?php require APPROOT . '/views/modalNotification/modalLoading.php'; ?>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            let table = $('#tblImportKecamatan').DataTable({
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
                ],
                initComplete: function() {
                    $('#tblImportKecamatan tbody').on('mouseenter', 'tr', function() {
                        $(this).css('background-color', 'Yellow');
                    });
                    $('#tblImportKecamatan tbody').on('mouseleave', 'tr', function() {
                        $(this).css('background-color', '');
                    });
                },
            });

            $('#modalAlert').click(function() {
                const title = $('#modalMessageTitle').text();
                $('#modalMessage').modal('hide');
                if (title === 'Success') {
                    location.reload();
                }
            });

            $('#fileExcelKecamatan').on('change', function(e) {
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

                showModalLoading('Proses Import File', 'Proses Import file sudah selesai. Terimakasih sudah menunggu...');

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
                                r[2].toUpperCase(),
                                null,
                            ]).draw();
                        }
                    });

                    hideModalLoading();

                    if (excelData.length > 1) {
                        $('#btnSimpanExcelKecamatan').prop('disabled', false);
                    }
                };

                reader.readAsArrayBuffer(file);
            });

            $('#tblImportKecamatan tbody').on('click', '.delete-row', function() {
                table.row($(this).parents('tr')).remove().draw();
            });

            $('#btnSimpanExcelKecamatan').on('click', SimpanExcelKecamatan)
        })

        const showModalLoading = (title, message) => {
            $('#modalLoadingTitle').text(title);
            $('#modalLoadingMessageBody').text(message);
            $('#modalLoading').modal('show');
        };

        const hideModalLoading = () => {
            $('#modalLoading').modal('hide');
        };

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

        const SimpanExcelKecamatan = () => {
            const csrfToken = $('#_csrf_token').val();
            let userInput = $('#kd_asli_user').data('kd_asli_user');
            let tableDataExcel = $('#tblImportKecamatan').DataTable().rows({
                filter: 'applied'
            }).data().toArray();

            let dataToSend = {
                csrf_token: csrfToken,
                data: tableDataExcel.map((it, ix) => ({
                    id_kota_kabupaten: it[1].toString(),
                    id_kecamatan: it[2].toString(),
                    nama_kecamatan: it[3],
                    kd_user: userInput,
                }))
            };

            showModalNotificaton(
                'Konfirmasi Simpan Datga Excel Kecamatan',
                `Apakah Anda yakin ingin menyimpan data ini?`,
                function() {
                    console.log('Ya simpan data Excel Kecamatan');
                    $.ajax({
                        url: "<?= BASEURL; ?>/wilayah/validasiExcelKecamatan",
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
                },
                function() {
                    console.log('Tutup Modal Notification');
                }
            )
        }
    </script>