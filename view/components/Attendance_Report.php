<div class="pagetitle">
    <h1>Attendance Report</h1>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-title">
                                <h5>List Attendance Report</h5>
                            </div>

                            <div class="row card-header justify-content-end w-75 p-3">
                                <div class="col-6">
                                    <label for="selectAttendance">Class per Subjects</label>
                                    <select id="selectAttendance" class="form-select">
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label></label>
                                    <div class="d-grid gap-2">
                                        <button id="excelReport" class="btn btn-primary">Export Excel</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="tablePaging" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">#ID</th>
                                    <th scope="col">Student</th>
                                    <th scope="col">Present</th>
                                    <th scope="col">Absent</th>
                                    <th scope="col">Licensed</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Details Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="tableDetails" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Type</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const accountEmail = '<?php echo $global->emailUser; ?>';
        const accountType = '<?php echo $global->typeUser; ?>';

        const param = `email="${accountEmail}"&typeAccount=${accountType}&isActive=3`
        getDataCboxAsync('get_class_subject', 'id', 'class_subject_name', '#selectAttendance', param)

        $('#excelReport').prop('disabled', true)

        $('#selectAttendance').on('change', async function(e) {
            if (e.target.value == 0) {
                $('#excelReport').prop('disabled', true)
                return
            }
            $('#excelReport').prop('disabled', false)

            const listData = await getData()
            $('#tablePaging').DataTable().destroy()
            $('#tablePaging').DataTable({
                data: listData,
                columns: [{
                        data: 'student_idno',
                        className: 'dt-body-left',
                        render: function(data, type, row, meta) {
                            return meta.row + 1
                        }
                    },
                    {
                        data: 'student_idno',
                        className: 'dt-body-left'
                    },
                    {
                        data: 'student_name',
                        className: 'dt-body-left'
                    },
                    {
                        data: 'present',
                        className: 'dt-body-left'
                    },
                    {
                        data: 'absent',
                        className: 'dt-body-left'
                    },
                    {
                        data: 'licensed',
                        className: 'dt-body-left'
                    },
                    {
                        data: 'status',
                        className: 'dt-body-left',
                        render: function(data, type, row) {
                            return data == 0 ? `<span class="badge bg-success">Pass</span>` : `<span class="badge bg-secondary">Fail</span>`
                        }
                    },
                    {
                        data: 'id',
                        className: 'dt-body-center',
                        render: function(data, type, row) {
                            return `
                            <div>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalConfirm" data-add='false' data-bind='${JSON.stringify(row)}'>
                                    View
                                </button>
                            </div>
                        `
                        }
                    }
                ],
                columnDefs: [{
                    targets: -1,
                    className: 'dt-body-center'
                }],
                lengthMenu: [
                    [-1],
                    ['All']
                ]
            });
        })

        $('#modalConfirm').on('show.bs.modal', async function(event) {
            $('#tableDetails').DataTable().destroy()
            const subject_class_id = $('#selectAttendance').find(":selected").val();
            var button = $(event.relatedTarget)
            var modal = $(this)
            var data = button.data('bind')
            modal.find('.modal-title').text(`${data.student_idno} - ${data.student_name}`)
            await $.ajax({
                url: `controller/ajax.php?action=get_Details_Record&classSubjectId=${subject_class_id}&studentId=${data.student_id}`,
                type: 'GET',
                dataType: 'json',
                success: function(resp) {
                    $('#tableDetails').DataTable({
                        data: resp?.data,
                        columns: [{
                                data: 'id',
                                className: 'dt-body-left',
                                render: function(data, type, row, meta) {
                                    return meta.row + 1
                                }
                            },
                            {
                                data: 'type',
                                className: 'dt-body-left',
                                render: function(data, type, row) {
                                    return getStatusByType(data)
                                }
                            },
                            {
                                data: 'doc',
                                className: 'dt-body-left'
                            },
                        ],
                    });
                }
            })
        })

        function getStatusByType(type) {
            var html = ''
            switch (+type) {
                case 0:
                    html = `<span class="badge bg-warning">Absent</span>`
                    break;
                case 1:
                    html = `<span class="badge bg-success">Present</span>`
                    break;
                case 2:
                    html = `<span class="badge bg-info">Licensed</span>`
                    break;
                default:
                    html = `<span class="badge bg-success">Present</span>`
            }
            return html
        }

        $('#excelReport').on('click', async function(e) {
            const listData = await getData()
            const class_subject_name = $('#selectAttendance').find(":selected").text();
            const mappingData = listData?.map(x => ({
                'Mã sinh viên': x.student_idno,
                'Họ tên sinh viên': x.student_name,
                'Có mặt': x.present,
                'Có phép': x.licensed,
                'Vắng mặt': x.absent
            }))
            const filename = `Attendance_Report_${class_subject_name}_${$('#monthReport').val()}.xlsx`;
            const ws = XLSX.utils.json_to_sheet(mappingData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, $('#monthReport').val());
            XLSX.writeFile(wb, filename);
        })

        async function getData() {
            let listReport = []
            const subject_class_id = $('#selectAttendance').find(":selected").val();

            await $.ajax({
                url: `controller/ajax.php?action=get_att_report&subject_class_id=${subject_class_id}`,
                type: 'GET',
                success: function(resp) {
                    listReport = JSON.parse(resp)?.data ?? {
                        data: []
                    }
                }
            })
            return listReport
        }

    });
</script>