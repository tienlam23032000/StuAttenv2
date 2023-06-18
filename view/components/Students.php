<div class="pagetitle">
    <h1>Student</h1>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-title">
                                <h5>List Student</h5>
                            </div>

                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalConfirmImportExcel">
                                    <i class="bi bi-file-earmark-excel"></i> Import Excel
                                </button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='true'>
                                    Add New Student
                                </button>
                            </div>
                        </div>

                        <table id="tablePaging" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID #</th>
                                    <th scope="col">Student</th>
                                    <th scope="col">Full Name Class</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="modalForm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form" class="row g-3">
                        <input type="hidden" name="id">
                        <div class="col-12" id="msg"></div>
                        <div class="col-12">
                            <label for="id_no" class="form-label">ID #</label>
                            <input type="text" class="form-control" name="id_no" id="id_no" autocomplete="off">
                        </div>

                        <div class="col-12">
                            <label for="student" class="form-label">Student</label>
                            <input type="text" class="form-control" name="name" id="student" autocomplete="off">
                        </div>
                        <div class="col-12">
                            <label for="class_selected" class="form-label">Class</label>
                            <select name="class_id" id="class_selected" class="form-select">
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="form">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalConfirm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    Are you sure to delete this student?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="confirmDelete">Continue</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalConfirmImportExcel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-primary" id="btnDownTempExcel">
                        <i class="bi bi-file-earmark-excel"></i> Download example excel
                    </button>
                    <input class="pt-3" type="file" id="excelFile" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="confirmImportExcel">Import</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</div>

<script>
    $(document).ready(function() {

        // Get Data Select
        $.ajax({
            url: 'controller/ajax.php?action=get_class',
            cache: false,
            contentType: false,
            processData: false,
            method: 'GET',
            type: 'GET',
            success: function(resp) {
                let data = JSON.parse(resp)?.data
                let html = '<option value="">Please select ...</option>'
                if (data && data?.length > 0) {
                    data.forEach(element => {
                        html += `<option value="${element.id}">${element.course_name} ${element.class}-${element.subclass}</option>`
                    });
                }
                $('#class_selected').html(html)
            }
        })

        $('#tablePaging').DataTable({
            ajax: 'controller/ajax.php?action=get_student',
            columns: [{
                    data: 'id',
                    className: 'dt-body-left',
                    render: function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    data: 'id_no',
                    className: 'dt-body-left'
                },
                {
                    data: 'name',
                    className: 'dt-body-left'
                },
                {
                    data: 'class_name',
                    className: 'dt-body-left',
                },
                {
                    data: 'id',
                    className: 'dt-body-center',
                    render: function(data, type, row) {
                        return `
                            <div>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='false' data-bind='${JSON.stringify(row)}'>
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete_course" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id='${data}' type="button">
                                    Delete
                                </button>
                            </div>
                        `
                    }
                }
            ],
            columnDefs: [{
                targets: -1,
                className: 'dt-body-center'
            }]
        });

        $('#modalForm').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var recipient = !button.data('add') ? button.data('bind') : null
            var title = button.data('add') ? 'Add Student' : 'Edit Student'
            modal.find('.modal-title').text(title)
            modal.find('.modal-body select[name=class_id]').val(recipient?.class_id)
            modal.find('.modal-body input[name=name]').val(recipient?.name)
            modal.find('.modal-body input[name=id_no]').val(recipient?.id_no)
            modal.find('.modal-body input[name=id]').val(recipient?.id)
        })

        // Excel 

        $('#btnDownTempExcel').on('click', function(event) {
            let locationURL = window.location
            let patch = `${locationURL.origin}/${locationURL.pathname.split('/')[1]}/template/FileStudents.xlsx`
            window.open(patch)
        })

        $('#modalConfirmImportExcel').on('show.bs.modal', function(event) {
            // This show popup clean old file
        })

        $('#modalConfirmImportExcel #confirmImportExcel').on('click', function(event) {
            //Get file blob
            var file = $('#modalConfirmImportExcel #excelFile')[0].files[0];
            var lstTypeFile = [{
                uid: 1,
                type: 'application/vnd.ms-excel'
            }, {
                uid: 2,
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }];

            //Check type file equals lstTypeFile
            var fileType = lstTypeFile.find(x => x.type == file.type)
            if (!file) {
                alert_toast('Chose file please !', 'danger')
                return;
            }
            if (!fileType) {
                alert_toast('File is not format !', 'danger')
                return;
            }
            if (typeof(FileReader) == "undefined") {
                alert_toast('Browser does not support!', 'danger')
                return;
            }
            excel_to_json(file, fileType.uid);
        })

        function excel_to_json(file, type) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = e.target.result;
                var workbook = type == 2 ?
                    XLSX.read(data, {
                        type: 'binary'
                    }) :
                    XLS.read(data, {
                        type: 'binary'
                    });
                //Get first sheet in wookbook
                var firstSheet = workbook.SheetNames[0];
                //convert sheet to Json
                var excelJson = type == 2 ?
                    XLSX.utils.sheet_to_json(workbook.Sheets[firstSheet], {
                        raw: true
                    }) :
                    XLS.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet], {
                        raw: true
                    });
                if (excelJson?.length < 0) {
                    alert_toast('File is empty !', 'danger')
                    return;
                }
                var mappingObj = excelJson?.map(x => ({
                    id_no: x['Mã sinh viên'],
                    class_id: x['Mã lớp'],
                    name: x['Tên sinh viên']
                }))
                console.log(mappingObj);
                import_excel(mappingObj)
            }
            //check error
            reader.onerror = function(ex) {
                console.log(ex);
            };
            reader.readAsBinaryString(file);
        }


        function import_excel(json) {
            $.ajax({
                url: "controller/ajax.php?action=import_excel",
                method: "POST",
                data: {
                    json: JSON.stringify(json)
                },
                success: function(resp) {
                    $('#modalConfirmImportExcel').modal('toggle')
                    const respPaser = JSON.parse(resp)
                    alert_toast(`<p>${respPaser.countSuccess} record update, ${respPaser.countVaild} already exist</p>`, 'success', 5000)
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }

        // Delete
        $('#modalConfirm').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var recipient = button.data('id')
            modal.find('.modal-body input[name=id]').val(recipient)
        })

        $('#confirmDelete').on('click', function(event) {
            var id = $('#modalConfirm').find('.modal-body input[name=id]').val()
            $.ajax({
                url: 'controller/ajax.php?action=delete_student',
                method: 'POST',
                data: {
                    id
                },
                success: function(resp) {
                    if (resp == 1) {
                        $('#modalConfirm').modal('toggle')
                        alert_toast("Data successfully deleted", 'success', 5000)
                        setTimeout(function() {
                            location.reload()
                        }, 1500)
                    }
                }
            })
        })

        //Save
        $('#form').submit(function(e) {
            e.preventDefault()
            $.ajax({
                url: 'controller/ajax.php?action=save_student',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    if (resp == 1) {
                        $('#msg').html('')
                        $('#modalForm').modal('toggle')
                        alert_toast("Data successfully saved", 'success', 5000)
                        setTimeout(function() {
                            location.reload()
                        }, 1500)
                        return
                    }
                    if (resp == 2) {
                        $('#msg').html('<div class="alert alert-danger mx-2">Student already exist.</div>')
                        return
                    }
                    if (resp == 3) {
                        $('#msg').html('<div class="alert alert-danger mx-2">Please select class.</div>')
                        return
                    }
                }
            })
        })
    });
</script>