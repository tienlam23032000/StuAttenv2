<div class="pagetitle">
    <h1>Faculty</h1>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-title">
                                <h5>List Faculty</h5>
                            </div>

                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='true'>
                                    Add New Faculty
                                </button>
                                <div class="modal fade" id="modalForm" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add New Faculty</h5>
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
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="name" id="name" autocomplete="off">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="text" class="form-control" name="email" id="email" autocomplete="off">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="contact" class="form-label">Contact</label>
                                                        <input type="text" class="form-control" name="contact" id="contact" autocomplete="off">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="address" class="form-label">Address</label>
                                                        <textarea class="form-control" name="address" id="address" cols="30" rows="4" autocomplete="off"></textarea>
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
                                    <th scope="col">ID #</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Address</th>
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
                    Are you sure to delete this faculty?
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
            ajax: 'controller/ajax.php?action=get_faculty',
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
                    data: 'email',
                    className: 'dt-body-left'
                },
                {
                    data: 'contact',
                    className: 'dt-body-left'
                },
                {
                    data: 'address',
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
            var title = button.data('add') ? 'Add Faculty' : 'Edit Faculty'
            modal.find('.modal-title').text(title)

            modal.find('.modal-body input[name=id_no]').val(recipient?.id_no)
            modal.find('.modal-body input[name=name]').val(recipient?.name)
            modal.find('.modal-body input[name=email]').val(recipient?.email)
            modal.find('.modal-body input[name=contact]').val(recipient?.contact)
            modal.find('.modal-body textarea[name=address]').val(recipient?.address)
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
                url: 'controller/ajax.php?action=delete_faculty',
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
                url: 'controller/ajax.php?action=save_faculty',
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
                        $('#msg').html('<div class="alert alert-danger mx-2">Faculty already exist.</div>')
                    }
                }
            })
        })
    });
</script>