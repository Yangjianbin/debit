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
                <span class="l pd-5">

                    <input type="number" id="debitid" placeholder="Loan Reference No." style="width:250px" class="input-text">
                    <input type="number" id="userid" placeholder="User ID" style="width:250px" class="input-text">
                    <input type="number" id="idcard" placeholder="IdCard" style="width:250px" class="input-text">
                    <input type="number" id="phone" placeholder="mobile phone" style="width:250px" class="input-text">

                </span>

                <div class="l pd-5">
                    <input type="text" placeholder="Release Time" id="start_time" style="width: 200px;" onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                    -
                    <input type="text" placeholder="Release Time" id="end_time" style="width: 200px;" onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                </div>

                <span class="l pd-5">
                    <!--<span class="select-box inline">
                        <select id="status" class="select">
                            <option value="">Status</option>
                            <option selected value="4">Overdue</option>
                        </select>
                    </span>-->
                    <span class="select-box inline">
                        <select id="days" class="select">
                            <option value="">Overdue Days</option>
                            <?php for($i=1;$i<=15;$i++):?>
                                <option value="<?=$i?>"><?=$i?></option>
                            <?php endfor;?>
                            <option value="16">Moren Than 15</option>
                        </select>
                    </span>
                    <span class="select-box inline">
                        <select id="paid" class="select">
                            <option value="0">Has Paid</option>
                            <option value="1">YES</option>
                            <option value="0">NO</option>
                        </select>
                    </span>

                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>Query</button>
                    <button id="reset" onclick="reset();" class="btn btn-error">Reset</button>
                    <a href="javascript:;" onclick="export1()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>Export</a>
                </span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>Loan Reference No.</th>
                        <!--                        <th>Amount</th>-->
                        <!--                        <th>Period</th>-->
                        <th>User ID</th>
                        <th>Star</th>
                        <th>Name</th>
                        <th>PayPack Day Time</th>
                        <th>Overdue Days</th>
                        <th>OverdueMoney</th>
                        <th>AlreadyReturnMoney</th>
                        <!--                        <th>IDCard</th>-->
                        <th>Mobile Number</th>
                        <th>Release Time</th>
                        <th>Overdue Detail</th>
                        <th style="min-width: 50px;">操作</th>
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
    var deleteUrl = "<?=site_url('Admin/bookDel')?>";
    var statusEnum = {
        "-2" : "Repayment has not been approved",
        "-1":"Loan application has not been approved",
        "0":"Apply for loan",
        "1":"Approved",
        "2":"Repayment has been applied",
        "3" : "Repayment has been approved",
        "4" : "overdue",
        "5" : "approved"
    }
    var colorEnum = {
        "-2" : "red",
        "-1":"red",
        "0":"#ffd126",
        "1":"green",
        "2":"#ffd126",
        "3" : "green",
        "4" : "red",
        "5" : "green"
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
            aoData.userid = $('#userid').val();
            aoData.status = $('#status').val();
            aoData.debitid = $('#debitid').val();
            aoData.phone = $('#phone').val();
            aoData.idcard = $('#idcard').val();
            aoData.startTime = $('#start_time').val();
            aoData.endTime = $('#end_time').val();
            aoData.days = $('#days').val();
            aoData.paid = $('#paid').val();
        },
        "createdRow": function (row, data, dataIndex) {
            $(row).children('td').attr('style', 'text-align: center;')
        },
        "aoColumnDefs": [],
        ajax: "<?=site_url('admin/overdue0')?>",
        columns: [
            {data: "DebitId"},
            // {data: "DebitMoney"},
            // {data: "DebitPeroid"},
            {data: "UserId"},
            {
                data: 'redStar', render: function (val, type, full) {
                    var str = '';
                    if (!val || val < 1) {
                        str += '';
                    } else {
                        str += '<div style="width: 24px;height:24px;background:  red;float: left;margin-right: 10px;"></div>';
                    }
                    if(!full.blackStar || full.blackStar < 1){
                        str += '';
                    } else {
                        str += '<div style="width: 24px;height:24px;background:  black;float: left;margin-right: 10px;"></div>';
                    }

                    if(!full.greenStar || full.greenStar < 1){
                        str += '';
                    } else {
                        str += '<div style="width: 24px;height:24px;background:   green;float: left;margin-right: 10px;"></div>';
                    }
                    return str;
                }
            },
            {data: "fullName"},
            {data: "payBackDayTime"},
            {data: 'overdueDay'},
            {data: "overdueMoney"},
            {data: "alreadyReturnMoney"},
            // {data: "IdCard"},
            {data: "Phone"},
            {data: "releaseLoanTime"},
            {data: 'Status',render:function (val,type,full) {
                    return '<div style="color:'+colorEnum[val]+'">' + statusEnum[val] + '</div>';
                }},
            {data: ''}
        ],
        "aoColumnDefs": [
            {
                "targets": -1,
                "data": 'id',
                "mRender": function (data, type, full) {
                    //addBlack
                    str =  '<a style="text-decoration:none;" class="ml-5" onclick="check(' + full.DebitId + ')" href="javascript:;" title="审核"><i class="Hui-iconfont">&#xe6df;View</i></a>';
                    str += '<a style="text-decoration:none;" class="ml-5" onclick="addBlack(' + full.UserId + ')" href="javascript:;" title="Black"><i class="Hui-iconfont">&#xe6e2;Black</i></a>';
                    str += '<a style="text-decoration:none" class="ml-5" onclick="addBad(' + full.DebitId + ')" href="javascript:;" title="Bad"><i class="Hui-iconfont">&#xe60b;Bad</i></a>';
                    return str;
                }
            }
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
        layer_show('Audit', "<?=site_url('admin/overdueCheck/')?>" + id, null, null);
    }

    function bookUpdate(id) {
        layer_show('新书上架', "<?=site_url('admin/bookUpdate')?>/" + id, null, null);
    }

    function addBlack(userId) {
        layer.confirm('Confirm of Add？', {
            btn: ['是', '否'],
            title: '确认',
            icon: 3
        }, function () {
            $.ajax({
                url: "<?=site_url('admin/addBlacklist/')?>" + userId,
                type: 'get',
                dataType: 'json'
            }).done(function (res) {
                console.log(res)
                if (!res || res.status == 500) {
                    layer.msg(res.msg);
                    return;
                }
                layer.msg('操作成功', {time: 400}, function () {
                    window.parent.location.reload();
                })
            }).fail(function () {
                layer.msg('操作失败，稍后再试');
            })
        });
    }

    function addBad(debitId) {
        layer.confirm('Confirm of Add？', {
            btn: ['是', '否'],
            title: '确认',
            icon: 3
        }, function () {
            $.ajax({
                url: "<?=site_url('admin/addBad/')?>" + debitId,
                type: 'get',
                dataType: 'json'
            }).done(function (res) {
                console.log(res)
                if (!res || res.status == 500) {
                    layer.msg(res.msg);
                    return;
                }
                layer.msg('操作成功', {time: 400}, function () {
                    window.parent.location.reload();
                })
            }).fail(function () {
                layer.msg('操作失败，稍后再试');
            })
        });
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
            w = 1100;
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
    function export1() {
        var userid = $('#userid').val();
        var debitid = $('#debitid').val();
        var phone = $('#phone').val();
        var idcard = $('#idcard').val();
        var days = $('#days').val();
        var paid = $('#paid').val();
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();
        location.href = "<?=site_url('export/overdue')?>" + '?start_time='
            + startTime + '&end_time=' + endTime + '&userid=' + userid + '&debitid=' + debitid
            + '&phone='+phone + '&idcard='+idcard + '&days='+days+'&paid='+paid;
    }
</script>
<!--/请在上方写此页面业务相关的脚本-->

</body>
</html>
