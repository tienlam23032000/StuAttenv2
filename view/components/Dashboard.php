<div class="pagetitle">
    <h1>Dashboard</h1>
    <br>
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card" style="box-shadow: 0px 0 10px rgba(1, 41, 112, 0.1) !important;">
                    <div class="card-body">
                        <h5 class="card-title">Time Remaining of Class Subject</h5>
                        <div id="barChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card" style="box-shadow: 0px 0 10px rgba(1, 41, 112, 0.1) !important;">
                    <div class="card-body">
                        <h5 class="card-title">Attendance Statistics</h5>
                        <div id="pieChart"></div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>

</div>

<script>
    $(document).ready(async function() {
        await $.ajax({
            url: 'controller/ajax.php?action=get_Dashboard_BarChart',
            method: 'GET',
            dataType: 'json',
            success: function(resp) {
                window.categoryBarChart = resp?.data?.sort(s => s.class_id)?.map(m => `${m.class_name} ${m.subject_name}`)
                window.dataBarChart = resp?.data?.sort(s => s.class_id)?.map(m => +m.time_remaining)
            }
        })
        await $.ajax({
            url: 'controller/ajax.php?action=get_Dashboard_PieChart',
            method: 'GET',
            dataType: 'json',
            success: function(resp) {
                window.dataPieChart = resp?.data?.reduce((acc, cur) => ([...acc, +cur.present, +cur.late, +cur.absent]), [])
            }
        })
        var optionBarChart = {
            series: [{
                data: window.dataBarChart
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            tooltip: {
                y: {
                    formatter: undefined,
                    title: {
                        formatter: (seriesName) => 'Time remaining: ',
                    },
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opt) {
                    return val + ' h'
                },
            },
            xaxis: {
                categories: window.categoryBarChart,
            }
        }

        var optionPieChart = {
            series: window.dataPieChart,
            chart: {
                height: 350,
                type: 'pie',
                toolbar: {
                    show: true
                }
            },
            labels: ['Present', 'Late', 'Absent']
        }

        new ApexCharts(document.querySelector("#barChart"), optionBarChart).render();
        new ApexCharts(document.querySelector("#pieChart"), optionPieChart).render();
    });
</script>