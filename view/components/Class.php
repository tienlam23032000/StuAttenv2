<div class="pagetitle">
    <h1>Class</h1>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-title">
                                <h5>List Class</h5>
                            </div>

                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='true'>
                                    Add New Class
                                </button>
                                <div class="modal fade" id="modalForm" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add New Class</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" id="form" class="row g-3">
                                                    <input type="hidden" name="id">
                                                    <div class="col-12" id="msg"></div>
                                                    <div class="col-12">
                                                        <label for="course_selected" class="form-label">Course</label>
                                                        <select name="course_id" id="course_selected" class="form-select">
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="class" class="form-label">Class</label>
                                                        <input type="text" class="form-control" name="class" id="class" autocomplete="off">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="subclass" class="form-label">Subclass</label>
                                                        <input type="text" class="form-control" name="subclass" id="subclass" autocomplete="off">
                                                    </div>
                                                    <!-- <div class="col-12 form-check form-switch" style="padding-left: 3em;">
                                                        <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" checked>
                                                        <label class="form-check-label" for="flexSwitchCheckChecked">Status</label>
                                                    </div> -->
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
                                    <th scope="col">Class</th>
                                    <th scope="col">SubClass</th>
                                    <th scope="col">Full Name Class</th>
                                    <!-- <th scope="col">Status</th> -->
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
</div>

<script>
    $(document).ready(function() {

        // Get Data Select
        $.ajax({
            url: 'controller/ajax.php?action=get_course',
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
                        html += `<option value="${element.id}">${element.course}</option>`
                    });
                }
                $('#course_selected').html(html)
            }
        })

        $('#tablePaging').DataTable({
            ajax: 'controller/ajax.php?action=get_class',
            columns: [{
                    data: 'id',
                    className: 'dt-body-left',
                    render: function(data, type, row, meta) {
                        return meta.row + 1
                    }
                },
                {
                    data: 'course_name',
                    className: 'dt-body-left'
                },
                {
                    data: 'class',
                    className: 'dt-body-left',
                },
                {
                    data: 'subclass',
                    className: 'dt-body-left'
                },
                {
                    data: 'class',
                    className: 'dt-body-left',
                    render: function(data, type, row) {
                        return `${row.course_name} ${data}-${row.subclass}`
                    }
                },
                // {
                //     data: 'status',
                //     className: 'dt-body-left',
                //     render: function(data, type, row) {
                //         return data == 1 ? `<span class="badge bg-success">Active</span>` : `<span class="badge bg-secondary">Inactive</span>`
                //     }
                // },
                {
                    data: 'id',
                    className: 'dt-body-center',
                    render: function(data, type, row) {
                        return `
                            <div>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-add='false' data-bind='${JSON.stringify(row)}'>
                                    Edit
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
            var title = button.data('add') ? 'Add Class' : 'Edit Class'
            modal.find('.modal-title').text(title)
            modal.find('.modal-body select[name=course_id]').val(recipient?.course_id)
            modal.find('.modal-body input[name=class]').val(recipient?.class)
            modal.find('.modal-body input[name=subclass]').val(recipient?.subclass)
            // modal.find('.modal-body input[name=status]').attr('checked', recipient?.status == 1 ? true : false)
            modal.find('.modal-body input[name=id]').val(recipient?.id)
        })

        //Save
        $('#form').submit(function(e) {
            e.preventDefault()
            $.ajax({
                url: 'controller/ajax.php?action=save_class',
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
                        $('#msg').html('<div class="alert alert-danger mx-2">Class already exist.</div>')
                        return
                    }
                    if (resp == 3) {
                        $('#msg').html('<div class="alert alert-danger mx-2">Please select course.</div>')
                        return
                    }
                }
            })
        })
    });
</script>