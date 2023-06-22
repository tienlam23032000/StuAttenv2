<div class="pagetitle">
    <h1>Attendance</h1>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-title">
                                <h5>Attendance List</h5>
                            </div>

                            <div class="row d-flex card-header w-75 p-3">
                                <div class="col-6">
                                    <label for="selectAttendance">Class per Subjects</label>
                                    <select id="selectAttendance" class="form-select">
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="dateAttendance">Date Attendance</label>
                                    <input id="dateAttendance" type="date" class="form-control pl-0">
                                </div>
                                <div class="col-3">
                                    <label for="timeAttendance">Time Attendance</label>
                                    <input id="timeAttendance" type="time" class="form-control pl-0">
                                </div>
                                <div class="col-8">
                                    <!-- Blank Div -->
                                </div>
                                <div class="col-2">
                                    <label></label>
                                    <div class="d-grid gap-2">
                                        <button type="button" name="" id="" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label></label>
                                    <div class="d-grid gap-2">
                                        <button type="button" name="" id="" class="btn btn-primary">End Subject</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="tablePaging" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Student</th>
                                    <th scope="col" class="text-center">Attendance</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        // FillData 
        $('#dateAttendance').val(getCurrentDate())
        $('#timeAttendance').val(getCurrentTime())
        getDataCboxAsync('get_class_subject', 'id', 'class_subject_name', '#selectAttendance')

        $('#selectAttendance').on('change', function(e) {
            const selectedId = e.target.value
            const formData = new FormData()
            formData.append("class_subject_id", selectedId)
            $('#tablePaging').DataTable().destroy()
            $('#tablePaging').DataTable({
                ajax: {
                    url: 'controller/ajax.php?action=get_class_list',
                    type: 'POST',
                    data: {
                        class_subject_id: selectedId,
                    }
                },
                columns: [{
                        data: 'id',
                        className: 'dt-body-left',
                        render: function(data, type, row, meta) {
                            return meta.row + 1
                        }
                    },
                    {
                        data: 'name',
                        className: 'dt-body-left'
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
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id='${data}' type="button">
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
        })



        //Save
        $('#form').submit(function(e) {
            e.preventDefault()
            $.ajax({
                url: 'controller/ajax.php?action=save_course',
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
                    } else if (resp == 2) {
                        $('#msg').html('<div class="alert alert-danger mx-2">Course already exist.</div>')
                    }
                }
            })
        })
    });
</script>