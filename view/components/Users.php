<div class="pagetitle">
    <h1>Courses</h1>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-title">
                                <h5>List User</h5>
                            </div>

                            <div class="card-header">
                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='true'>
                                    Add New User
                                </button> -->
                                <div class="modal fade" id="modalForm" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add New User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" id="form" class="row g-3">
                                                    <input type="hidden" name="id">
                                                    <div class="col-12" id="msg"></div>
                                                    <div class="col-12">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="name" id="name" autocomplete="off">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="username" class="form-label">Username</label>
                                                        <input type="text" class="form-control" name="username" id="username" autocomplete="off">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input type="password" class="form-control" name="password" id="password" autocomplete="off">
                                                        <small><i>Leave this blank if you dont want to change the password.</i></small>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="usertype_selected" class="form-label">User Type</label>
                                                        <select name="type" id="usertype_selected" class="form-select">
                                                            <option value="2" selected>Staff</option>
                                                            <option value="1">Admin</option>
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
                            </div>
                        </div>

                        <table id="tablePaging" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Type</th>
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
    <div class="modal fade" id="modalConfirm" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    Are you sure to delete this User?
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
        let dataCourse = {}

        $('#tablePaging').DataTable({
            ajax: 'controller/ajax.php?action=get_user',
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
                    data: 'username',
                    className: 'dt-body-left'
                },
                {
                    data: 'type',
                    className: 'dt-body-left',
                    render: function(data, type, row) {
                        return data == 1 ? `<span class="badge bg-info">Admin</span>` : `<span class="badge bg-warning">Staff</span>`
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
                                ${row?.type != 1 ?
                                ` <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-id='${data}' type="button">
                                    Delete
                                </button>` : ''
                                }
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
            var title = button.data('add') ? 'Add User' : 'Edit User'
            modal.find('.modal-title').text(title)
            modal.find('.modal-body input[name=id]').val(recipient?.id)
            modal.find('.modal-body input[name=name]').val(recipient?.name)
            modal.find('.modal-body input[name=username]').val(recipient?.username)
            modal.find('.modal-body select[name=type]').val(recipient?.type)

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
                url: 'controller/ajax.php?action=delete_user',
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
                url: 'controller/ajax.php?action=save_user',
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
                        $('#msg').html('<div class="alert alert-danger mx-2">User already exist.</div>')
                    }
                }
            })
        })
    });
</script>