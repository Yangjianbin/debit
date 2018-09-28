<?php
$this->load->view('admin/header');
?>
<body>
<!--_header 作为公共模版分离出去-->
<?php
$this->load->view('admin/navbar');
?>
<!--/_header 作为公共模版分离出去-->

<!--_menu 作为公共模版分离出去-->
<?php
$this->load->view('admin/side_menu');
?>
<!--/_menu 作为公共模版分离出去-->

<section class="Hui-article-box">
    <!--<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span>
        表
        <span class="c-gray en">&gt;</span>
        表original
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    -->
    <div class="Hui-article">
        <article class="cl pd-20">
            <div class="cl pd-5 bg-1 bk-gray mt-20">

                <div class="l pd-5">
                    <input type="text" placeholder="Apply Time" id="start_time" style="width: 200px;"
                           onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                    -
                    <input type="text" placeholder="Apply Time" id="end_time" style="width: 200px;"
                           onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                </div>

                <span class="l pd-5">
                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>Query</button>
                    <button id="reset" onclick="reset();" class="btn btn-error">Reset</button>
                    <!--                    <a href="javascript:;" onclick="bookAdd()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>上架新书</a>-->
                </span>
            </div>

            <div class="mt-20">
                <div id="container" style="min-width:700px;height:400px"></div>
                <div id="container2" style="min-width:700px;height:300px"></div>
                <div id="container3" style="min-width:700px;height:300px"></div>
            </div>
        </article>
    </div>
</section>

<!--_footer 作为公共模版分离出去-->
<?php
$this->load->view('admin/footer');
?>
<!--/_footer /作为公共模版分离出去-->


<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="<?= base_url() ?>static/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/hcharts/Highcharts/5.0.6/js/highcharts.js"></script>
<script type="text/javascript"
        src="<?= base_url() ?>static/lib/hcharts/Highcharts/5.0.6/js/modules/exporting.js"></script>

<script type="text/javascript">
    var statusEnum = {
        "-2": "Repayment has not been approved",
        "-1": "Loan application has not been approved",
        "0": "Apply for loan",
        "1": "Approved",
        "2": "Repayment has been applied",
        "3": "Repayment has been approved",
        "4": "overdue",
        "5": "approved"
    }
    var colorEnum = {
        "-2": "red",
        "-1": "red",
        "0": "#ffd126",
        "1": "green",
        "2": "#ffd126",
        "3": "green",
        "4": "red",
        "5": "green"
    };
    var chart,chart2,chart3;
    $(function () {
        $('#search').bind("click", function () { //按钮 触发table重新请求服务器
            var startTime = $('#start_time').val();
            var endTime = $('#end_time').val();

            $.ajax({
                url: "<?=site_url('admin/chart')?>",
                type: 'post',
                dataType: 'json',
                data:{startTime: startTime, endTime: endTime},
                success: function (d) {
                    chart.series[0].setData(d.res);

                    var data2 = {}, cate = [], data22 = [];
                    $.each(d.res2,function (i, v) {
                        cate.push(v.date);
                        if(data2[v.name] && data2[v.name].length > 0){
                            data2[v.name].push(v.y);
                        } else{
                            data2[v.name] = [v.y];
                        }
                    });
                    $.each(data2, function (i, v) {
                        if(v) {
                            data22.push({
                                name: i,
                                data: v
                            });
                        }
                    });
                    drawChart2(cate, data22);

                    var data3 = {}, cate3 = [], data33 = [];
                    $.each(d.res3,function (i, v) {
                        cate3.push(v.date);
                        if(data3[v.name] && data3[v.name].length > 0){
                            data3[v.name].push(v.y);
                        } else{
                            data3[v.name] = [v.y];
                        }
                    });
                    $.each(data3, function (i, v) {
                        if(v) {
                            data33.push({
                                name: i,
                                data: v
                            });
                        }
                    });


                    drawChart3(cate3, data33);
                }
            })
        });
        // var chart = $('#container').highcharts({
        chart = Highcharts.chart('container', {
            credits: {
                enabled: false
            },
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'All Status Ratio'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Ratio: ',
                data: [

                    /*['Firefox',   45.0],
                    ['IE',       26.8],
                    {
                        name: 'Chrome',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['Safari',    8.5],
                    ['Opera',     6.2],
                    ['Others',   0.7]*/
                ]
            }]
        });


        $('#search').click();

    })

    function drawChart2(categories, series) {
        if(chart2 != null) {
            chart2.destroy();
        }
        chart2 = Highcharts.chart('container2', {
            credits: {
                enabled: false
            },
            title: {
                text: '贷款状态曲线图',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: 'Status Count '
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '.'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: series

        });
    }

    function drawChart3(categories, series) {
        if(chart3 != null) {
            chart3.destroy();
        }
        chart3 = Highcharts.chart('container3', {
            credits: {
                enabled: false
            },
            title: {
                text: '贷款金额曲线图',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: 'DebitMoney '
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '.'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: series

        });
    }

    function reset() {
        $(':input').val('');
    }

</script>
<!--/请在上方写此页面业务相关的脚本-->

</body>
</html>