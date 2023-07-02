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
                                <div class="col-3">
                                    <label for="monthReport">Date Attendance</label>
                                    <input id="monthReport" type="month" class="form-control pl-0">
                                </div>
                                <div class="col-2">
                                    <label></label>
                                    <div class="d-grid gap-2">
                                        <button id="filterReport" class="btn btn-primary">Filter</button>
                                    </div>
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
                                    <th scope="col">#Id Number</th>
                                    <th scope="col">Student</th>
                                    <th scope="col">Present</th>
                                    <th scope="col">Late</th>
                                    <th scope="col">Absent</th>
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
        getDataCboxAsync('get_class_subject', 'id', 'class_subject_name', '#selectAttendance')
        $('#monthReport').prop('max', getCurrentMonth())
        $('#monthReport').val(getCurrentMonth())
        $('#filterReport').prop('disabled', true)
        $('#excelReport').prop('disabled', true)

        $('#monthReport').on('change', function(e) {
            console.log(e.target.value);
            if (e.target.value && e.target.value != '') {
                $('#filterReport').prop('disabled', false)
                $('#excelReport').prop('disabled', false)
                return
            }
            $('#filterReport').prop('disabled', true)
            $('#excelReport').prop('disabled', true)
        })
        $('#selectAttendance').on('change', function(e) {
            if (e.target.value != 0) {
                $('#filterReport').prop('disabled', false)
                $('#excelReport').prop('disabled', false)
                return
            }
            $('#filterReport').prop('disabled', true)
            $('#excelReport').prop('disabled', true)
        })

        $('#filterReport').on('click', async function(e) {
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
                        data: 'late',
                        className: 'dt-body-left'
                    },
                    {
                        data: 'absent',
                        className: 'dt-body-left'
                    },
                ]
            });
        })

        $('#excelReport').on('click', async function(e) {
            const listData = await getData()
            const class_subject_name = $('#selectAttendance').find(":selected").text();
            const mappingData = listData?.map(x => ({
                'Mã sinh viên': x.student_idno,
                'Họ tên sinh viên': x.student_name,
                'Có mặt': x.present,
                'Muộn': x.late,
                'Vắng mặt': x.absent,
                'Tháng': x.month,
                'Năm': x.year
            }))
            const filename = `Attendance_Report_${class_subject_name}_${$('#monthReport').val()}.xlsx`;
            const ws = XLSX.utils.json_to_sheet(mappingData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, $('#monthReport').val());
            XLSX.writeFile(wb, filename);
        })

        async function getData() {
            let listReport = []
            const monthYear = $('#monthReport').val()
            const month = monthYear.split('-')[1]
            const year = monthYear.split('-')[0]
            const subject_class_id = $('#selectAttendance').find(":selected").val();

            await $.ajax({
                url: `controller/ajax.php?action=get_att_report&month=${month}&year=${year}&subject_class_id=${subject_class_id}`,
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