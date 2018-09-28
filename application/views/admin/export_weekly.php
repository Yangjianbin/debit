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
        <nav class="breadcrumb"><i class="Hui-iconfont"></i> Export Daily
            <span class="c-gray en">&gt;</span>
            Monthly
            <span class="c-gray en">&gt;</span>
            List  </nav>
        <article class="cl pd-20">

            <div class="cl pd-5 bg-1 bk-gray ">
                <!--<span class="l pd-5">

                    <input type="number" id="debitid" placeholder="Loan Reference No." style="width:250px" class="input-text">
                    <input type="number" id="userid" placeholder="User ID" style="width:250px" class="input-text">
                    <input type="number" id="idcard" placeholder="IdCard" style="width:250px" class="input-text">
                    <input type="number" id="phone" placeholder="mobile phone" style="width:250px" class="input-text">

                </span>

                <div class="l pd-5">
                    <input type="text" placeholder="Apply Time" id="start_time" style="width: 200px;" onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                    -
                    <input type="text" placeholder="Apply Time" id="end_time" style="width: 200px;" onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                </div>-->


                <!--<span class="l pd-5">


                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>Query</button>
                    <button id="reset" onclick="reset();" class="btn btn-error">Reset</button>
                </span>-->
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <!--<th>周</th>
                        <th>放款本金</th>
                        <th>回收本金</th>
                        <th>本金利息</th>
                        <th>延期利息</th>
                        <th>总回收</th>
                        <th>损益</th>
                        <th>延期本金</th>
                        <th>逾期本金</th>
                        <th>回款率</th>
                        <th>损益率</th>-->
                        <th>Week</th>
                        <th>Loan Capital</th>
                        <th>Received capital</th>
                        <th>Capital Interests</th>
                        <th>Extend Interests</th>
                        <th>Total Received</th>
                        <th>P&L</th>
                        <th>Extend Capital</th>
                        <th>Overdue Capital</th>
                        <th>Payback %</th>
                        <th>P&L %</th>
                    </tr>
                    </thead>
                </table>
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
<script type="text/javascript">
    var statusEnum = {
        "-2" : "Repayment has not been approved",
        "-1":"Loan application has not been approved",
        "0":"Apply for loan",
        "1":"Approved",
        "2":"Repayment has been applied",
        "3" : "Repayment has been approved",
        "4" : "overdue",
        "5" : "approved",
        "6" : "申请延期",
    }
    var colorEnum = {
        "-2" : "red",
        "-1":"red",
        "0":"#ffd126",
        "1":"green",
        "2":"#ffd126",
        "3" : "green",
        "4" : "red",
        "5" : "green",
        "6" : "orange",
    }
    var table = $('.table-sort').dataTable({
        "aaSorting": [[1, "desc"]],//默认第几个排序
        bSort: false,//是否允许排序
        bLengthChange: false,//是否显示每页大小的下拉框
        searching: false,//是否显示搜索框
        "bStateSave": false,//状态保存
        serverSide: true,
        fnServerParams: function (aoData) {
            aoData._rand = Math.random();
            // aoData.userid = $('#userid').val();
            // aoData.status = $('#status').val();
            // aoData.debitid = $('#debitid').val();
            // aoData.phone = $('#phone').val();
            // aoData.idcard = $('#idcard').val();
            // aoData.startTime = $('#start_time').val();
            // aoData.endTime = $('#end_time').val();
        },
        "createdRow": function (row, data, dataIndex) {
            $(row).children('td').attr('style', 'text-align: center;')
        },
        "aoColumnDefs": [],
        ajax: "<?=site_url('export/weekly')?>",
        columns: [
            {data: "dateId"},
            {data: "toDayDebitMoneys", "render": function ( data) {
                    return   number(parseInt(data) / 10000);
                }},
            {data: "returnDebitMoneys", "render": function ( data) {
                    return   number(parseInt(data) / 10000);
                }},
            {data: "returnInterests", "render": function ( data) {
                    return   number(parseInt(data) / 10000);
                }},
            {data: "todayExtendInterests", "render": function ( data) {
                    return   number(parseInt(data) / 10000);
                }},
            {data: "todayExtendInterests", "render": function ( data,type,all) {
                    return   number((parseInt(all.returnDebitMoneys) + parseInt(all.returnInterests) + parseInt(all.todayExtendInterests)) / 10000);
                }},
            {data: "todayExtendInterests", "render": function ( data,type,all) {
                    return   number((parseInt(all.returnDebitMoneys) + parseInt(all.returnInterests) + parseInt(all.todayExtendInterests) - parseInt(all.toDayDebitMoneys)) / 10000);
                }},
            {data: "todayExtendMoneys", "render": function ( data) {
                    return   number(parseInt(data) / 10000);
                }},
            {data: "toDayOverdueMoneys", "render": function ( data) {
                    return   number(parseInt(data) / 10000);
                }},
            {data: "toDayOverdueMoneys", "render": function ( data,type,all) {
                    var a = (parseInt(all.returnDebitMoneys) + parseInt(all.returnInterests) + parseInt(all.todayExtendInterests)) / 10000;
                    var b = parseInt(all.toDayDebitMoneys) / 10000;
                    if(!a || !b){
                        return '';
                    }
                    return  (100 * a / b).toFixed(2) + '%';
                }},
            {data: "toDayOverdueMoneys", "render": function ( data,type,all) {
                    var a= (parseInt(all.returnDebitMoneys) + parseInt(all.returnInterests) + parseInt(all.todayExtendInterests) - parseInt(all.toDayDebitMoneys)) / 10000;
                    var b = parseInt(all.toDayDebitMoneys) / 10000;
                    if(!a || !b){
                        return '';
                    }
                    return  (100 * a / b).toFixed(2) + '%';
                }}
        ],
        "sPaginationType": "full_numbers",
        iDisplayLength: 10,
        "oLanguage": {
            "sProcessing": "正在加载中......",
            "sLengthMenu": "每页显示 _MENU_ 条记录",
            "sZeroRecords": "对不起，查询不到相关数据！",
            "sEmptyTable": "表中无数据存在！",
            "sInfo": "current display lasts for _START_-_END_ records,in total there will be _MAX_ records",
            "sInfoFiltered": "数据表中共为 _MAX_ 条记录",
            "sSearch": "搜索",
            "oPaginate": {
                "sFirst": "First",
                "sPrevious": "Pre",
                "sNext": "Next",
                "sLast": "Last"
            }
        }
    });

    $(function () {
        $('#search').bind("click", function () { //按钮 触发table重新请求服务器
            table.fnDraw();
        });
    })

    function check(id) {
        layer_show('AUDIT', "<?=site_url('admin/extendCheck/')?>" + id, null, null);
    }


    function layer_show(title, url, w, h) {
        if (title == null || title == '') {
            title = false;
        }
        ;
        if (url == null || url == '') {
            url = "404.html";
        }
        ;
        if (w == null || w == '') {
            w = 1000;
        }
        ;
        if (h == null || h == '') {
            h = ($(window).height() - 100);
        }
        ;
        layer.open({
            type: 2,
            area: [w + 'px', h + 'px'],
            fix: false, //不固定
            maxmin: true,
            shade: 0.4,
            title: title,
            content: url
        });
    }

    function reset() {
        $(':input').val('');
    }
</script>
<!--/请在上方写此页面业务相关的脚本-->


</body>
</html>