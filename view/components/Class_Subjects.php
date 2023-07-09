<div class="pagetitle">
    <h1>Class Subjects</h1>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-title">
                                <h5>List Class Subjects</h5>
                            </div>

                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='true'>
                                    Add New Class Subject
                                </button>
                                <div class="modal fade" id="modalForm" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add New Class Subject</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" id="form" class="row g-3">
                                                    <input type="hidden" name="id">
                                                    <div class="col-12" id="msg"></div>
                                                    <div class="col-12">
                                                        <label for="class_selected" class="form-label">Full Name Class</label>
                                                        <select name="class_id" id="class_selected" class="form-select">
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="subject_selected" class="form-label">Subject</label>
                                                        <select name="subject_id" id="subject_selected" class="form-select">
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="faculty_selected" class="form-label">Faculty</label>
                                                        <select name="faculty_id" id="faculty_selected" class="form-select">
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="time_remaining" class="form-label">Time Remaining</label>
                                                        <input type="text" class="form-control disable" name="time_remaining" id="time_remaining" autocomplete="off" >
                                                    </div>
                                                    <div class="col-12 form-check form-switch" style="padding-left: 3em;">
                                                        <input class="form-check-input" type="checkbox" name="status_cs" id="flexSwitchCheckChecked" checked>
                                                        <label class="form-check-label" for="flexSwitchCheckChecked">Status Class Subject</label>
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
                            </div>
                        </div>

                        <table id="tablePaging" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Full Name Class</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Faculty</th>
                                    <th scope="col">Time Remaining</th>
                                    <th scope="col">Status Class Subject</th>
                                    <th scope="col" class="text-center">Action</th>
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
    <div class="modal fade" id="modalConfirm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    Are you sure to delete this Class Subject ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="confirmDelete">Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        getDataCboxAsync('get_class', 'id', 'class_name', '#class_selected')
        getDataCboxAsync('get_subject', 'id', 'subject', '#subject_selected')
        getDataCboxAsync('get_faculty', 'id', 'name', '#faculty_selected')

        $('#tablePaging').DataTable({
            ajax: 'controller/ajax.php?action=get_class_subject',
            columns: [{
                    data: 'id',
                    className: 'dt-body-left',
                    render: function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    data: 'class_name',
                    className: 'dt-body-left'
                },
                {
                    data: 'subject_name',
                    className: 'dt-body-left'
                },
                {
                    data: 'faculty_name',
                    className: 'dt-body-left'
                },
                {
                    data: 'time_remaining',
                    className: 'dt-body-right'
                },
                {
                    data: 'status_cs',
                    className: 'dt-body-right',
                    render: function(data, type, row) {
                        return data == 1 ? `<span class="badge bg-success">Active</span>` : `<span class="badge bg-secondary">Inactive</span>`
                    }
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

        $('#modalForm').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var recipient = !button.data('add') ? button.data('bind') : null
            var title = button.data('add') ? 'Add Class Subject' : 'Edit Class Subject'
            modal.find('.modal-title').text(title)
            modal.find('.modal-body select[name=class_id]').val(recipient?.class_id)
            modal.find('.modal-body select[name=subject_id]').val(recipient?.subject_id)
            modal.find('.modal-body select[name=faculty_id]').val(recipient?.faculty_id)
            modal.find('.modal-body input[name=time_remaining]').val(recipient?.time_remaining)
            modal.find('.modal-body input[name=status_cs]').attr('checked', recipient?.status_cs == 1 ? true : false)
            modal.find('.modal-body input[name=id]').val(recipient?.id)
        })

        $('#modalConfirm').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var recipient = button.data('id')
            modal.find('.modal-body input[name=id]').val(recipient)
        })

        $('#confirmDelete').on('click', function(event) {
            var id = $('#modalConfirm').find('.modal-body input[name=id]').val()
            $.ajax({
                url: 'controller/ajax.php?action=delete_class_subject',
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

        $('#subject_selected').on('change', function(event) {
            const id = event.target.value
            $.ajax({
                url: `controller/ajax.php?action=get_time_remaining_subject`,
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    const paserData = JSON.parse(data)
                    $('#modalForm').find('.modal-body input[name=time_remaining]').val(paserData?.time_subject)
                }
            })
        })

        //Save
        $('#form').submit(function(e) {
            e.preventDefault()
            $.ajax({
                url: 'controller/ajax.php?action=save_class_subject',
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
                        $('#msg').html('<div class="alert alert-danger mx-2">Class Subject already exist.</div>')
                        return
                    }
                    if (resp == 3 || resp == 4 || resp == 5) {
                        var msg = resp == 3 ? 'class' : resp == 4 ? 'subject' : 'faculty'
                        $('#msg').html(`<div class="alert alert-danger mx-2">Please select ${msg}.</div>`)
                        return
                    }
                }
            })
        })

    });
</script>