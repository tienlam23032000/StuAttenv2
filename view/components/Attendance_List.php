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
                                <h6 id="viewTimeRemaining">Subject Session: 0</h6>
                            </div>

                            <div class="row card-header justify-content-end w-75 p-3">
                                <div class="col-6">
                                    <label for="selectAttendance">Class per Subjects</label>
                                    <select id="selectAttendance" class="form-select">
                                    </select>
                                    <label for="noteAttendance">Note</label>
                                    <input id="noteAttendance" type="text" class="form-control pl-0">
                                </div>
                                <div class="col-3">
                                    <label for="dateAttendance">Date Attendance</label>
                                    <input id="dateAttendance" type="date" class="form-control pl-0">

                                    <label for="timeAttendance">Time Attendance</label>
                                    <input id="timeAttendance" type="time" class="form-control pl-0" disabled>
                                </div>
                                <div class="col-2">
                                    <label></label>
                                    <div class="d-grid gap-2">
                                        <button id="saveAttendance" class="btn btn-primary">Save</button>
                                    </div>
                                    <label></label>
                                    <div class="d-grid gap-2">
                                        <button id="endSubject" class="btn btn-primary">End Subject</button>
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
        $('#dateAttendance').prop('max', getCurrentDate())
        getDataCboxAsync('get_class_subject', 'id', 'class_subject_name', '#selectAttendance')

        // let realTime = setInterval(function() {
        //     $('#timeAttendance').val(getCurrentTime())
        // }, 999);

        $('#selectAttendance').on('change', async function(e) {
            window.selectedId = e.target.value
            await $.ajax({
                url: 'controller/ajax.php?action=get_class_list',
                type: 'POST',
                data: {
                    class_subject_id: window.selectedId,
                },
                success: function(resp) {
                    window.listAttendance = JSON.parse(resp)?.data ?? {
                        data: []
                    }
                    const timeRemaining = JSON.parse(resp)?.time_remaining ?? 0
                    $('#viewTimeRemaining').html(`Subject Session: ${timeRemaining}`)
                    $('#tablePaging').DataTable().destroy()
                    $('#tablePaging').DataTable({
                        data: window.listAttendance,
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
                                data: 'id',
                                className: 'dt-body-center',
                                render: function(data, type, row) {
                                    return `
                                        <div class="d-flex justify-content-center" id="attendanceLst-${data}">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input present-inp" name="attendance-${data}" type="radio" value="1"/>
                                                <label class="form-check-label present-lbl">Present</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input absent-inp" name="attendance-${data}" type="radio" value="0" />
                                                <label class="form-check-label absent-lbl">Absent</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input late-inp" name="attendance-${data}" type="radio" value="2" />
                                                <label class="form-check-label late-lbl">Licensed</label>
                                            </div>
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
                }
            })
            await changeDate();
        })

        $('#saveAttendance').on('click', function(e) {
            const dateDOC = $('#dateAttendance').val()
            const jsonBody = {
                id: 0,
                doc: dateDOC,
                start_time: $('#timeAttendance').val(),
                end_time: null,
                note: $('#noteAttendance').val(),
                class_subject_id: window.selectedId,
                type: window.listAttendance.map(x => $(`#attendanceLst-${x.id} input[name=attendance-${x.id}]:checked`).val()),
                student_id: window.listAttendance.map(x => x.id)
            }

            $.ajax({
                url: 'controller/ajax.php?action=save_attendance',
                data: {
                    json: JSON.stringify(jsonBody)
                },
                method: 'POST',
                success: function(resp) {
                    var msg = resp == 1 ? 'updated' : 'inserted'
                    $("#timeAttendance").prop('disabled', true);
                    alert_toast(`Data successfully ${msg}`, 'success', 5000)
                },
                error: function(err) {
                    alert_toast(`Fail saved`, 'danger', 5000)
                    console.error(err)
                }
            })

        })

        $('#endSubject').on('click', function(e) {
            const startTime = $('#timeAttendance').val()
            const endTime = getCurrentTime()
            const timeRemaining = calcTimeToHour(startTime, endTime)

            $.ajax({
                url: 'controller/ajax.php?action=end_subject',
                type: 'POST',
                data: {
                    json: JSON.stringify({
                        endTime: endTime,
                        timeRemaining: timeRemaining,
                        class_subject_id: window.selectedId,
                        attendance_id: window.attendance_id
                    })
                },
                success: function(data) {
                    $("#endSubject").prop('disabled', true);
                    $("#saveAttendance").prop('disabled', true);
                    eventAttenList(true, false)
                    alert_toast(`End subject successfully`, 'success', 5000)
                }
            })

        })

        $('#dateAttendance').on('change', function(e) {
            changeDate()
        })


        async function changeDate() {
            await $.ajax({
                url: 'controller/ajax.php?action=get_edit_class_list',
                type: 'POST',
                data: {
                    class_subject_id: window.selectedId,
                    date_attendance: $('#dateAttendance').val()
                },
                success: function(data) {
                    const dataPaser = JSON.parse(data)
                    window.attendance_id = dataPaser?.attendance_id
                    if (!dataPaser.success) {
                        $('#timeAttendance').val(getCurrentTime())
                        $('#noteAttendance').val('')
                        // $("#timeAttendance").prop('disabled', false);
                        $('#endSubject').prop('disabled', false);
                        $("#saveAttendance").prop('disabled', false);
                        eventAttenList(false, false, true, false)
                        // realTime = setInterval(function() {
                        //     $('#timeAttendance').val(getCurrentTime())
                        // }, 999);
                        return
                    }
                    // clearInterval(realTime);
                    $('#endSubject').prop('disabled', false);
                    eventAttenList(false, 1)
                    if (dataPaser.endTime != '00:00:00') {
                        $('#endSubject').prop('disabled', true);
                        $("#saveAttendance").prop('disabled', true);
                        eventAttenList(true, 1)
                    } else {
                        $("#saveAttendance").prop('disabled', false);
                    }
                    $('#timeAttendance').val(dataPaser.startTime.slice(0,5))
                    // $("#timeAttendance").prop('disabled', true);
                    $('#noteAttendance').val(dataPaser.note)

                    dataPaser?.listType.forEach(itemType => {
                        $(`#attendanceLst-${itemType.student_id} input[name=attendance-${itemType.student_id}]`).each(
                            function(index) {
                                $(this).filter(`[value=${itemType.type}]`).prop('checked', true)
                            })
                    })

                }
            })
        }

        function eventAttenList(disable, checked, defaultValueOne = false, clean = false) {
            try {
                window.listAttendance.forEach(item => {
                    $(`#attendanceLst-${item.id} input[name=attendance-${item.id}]`).each(
                        function(index) {
                            $(this).prop('disabled', disable);
                            if (checked) {
                                $(this).prop('checked', checked);
                            }
                            if (defaultValueOne) {
                                $(this).filter(`[value=1]`).prop('checked', true)
                            }
                            if (clean) {
                                $(this).filter(`:checked`).prop('checked', false)
                            }
                        }
                    )
                })
            } catch (error) {

            }
        }
    });
</script>