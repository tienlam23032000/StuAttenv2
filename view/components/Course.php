<div class="pagetitle">
    <h1>Courses</h1>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-title">
                                <h5>List Courses</h5>
                            </div>

                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='true'>
                                    Add New Course
                                </button>
                                <div class="modal fade" id="modalForm" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add New Course</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" id="form" class="row g-3">
                                                    <input type="hidden" name="id">
                                                    <div class="col-12" id="msg"></div>
                                                    <div class="col-12">
                                                        <label for="course" class="form-label">Course</label>
                                                        <input type="text" class="form-control" name="course" id="course" autocomplete="off">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="description" class="form-label">Description</label>
                                                        <textarea class="form-control" name="description" id="description" cols="30" rows="4" placeholder="Enter school year" autocomplete="off"></textarea>
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
                                    <th scope="col">Course</th>
                                    <th scope="col">Description</th>
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
                    Are you sure to delete this course?
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
            ajax: 'controller/ajax.php?action=get_course',
            columns: [{
                    data: 'id',
                    className: 'dt-body-left',
                    render: function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    data: 'course',
                    className: 'dt-body-left'
                },
                {
                    data: 'description',
                    className: 'dt-body-left'
                },
                {
                    data: 'id',
                    className: 'dt-body-center',
                    render: function(data, type, row) {
                        return `
                            <div>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='false' data-course='${JSON.stringify(row)}'>
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
            if (!button.data('add')) {
                var recipient = button.data('course')
                modal.find('.modal-title').text('Edit Course')
                modal.find('.modal-body input[name=course]').val(recipient.course)
                modal.find('.modal-body textarea[name=description]').val(recipient.description)
                modal.find('.modal-body input[name=id]').val(recipient.id)
                return
            }
            modal.find('.modal-title').text('Add Course')
        })

        $('#modalConfirm').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var recipient = button.data('id')
            modal.find('.modal-body input[name=id]').val(recipient)
        })

        $('#confirmDelete').on('click', function(event) {
            
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