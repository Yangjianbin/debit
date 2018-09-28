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

                    <input type="number" id="debitid" placeholder="Loan Reference No." style="width:250px"
                           class="input-text">
                    <input type="number" id="userid" placeholder="User ID" style="width:250px" class="input-text">
                    <input type="text" id="username" placeholder="Username" style="width:250px" class="input-text">
<!--                    <input type="number" id="phone" placeholder="mobile phone" style="width:250px" class="input-text">-->

                </span>

                <!--<div class="l pd-5">
                    <input type="text" placeholder="Apply Time" id="start_time" style="width: 200px;"
                           onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                    -
                    <input type="text" placeholder="Apply Time" id="end_time" style="width: 200px;"
                           onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                </div>-->


                <span class="l pd-5">
                    <span class="select-box inline">
                        <!--<select id="taskType" class="select">
                            <option value="1">Loan Apply</option>
                            <option value="2">Reminders Task</option>
                            <option value="3">Overdue Task</option>
                        </select>-->
                        <select id="status" class="select">
                            <option value="">Status</option>
                            <option value="0">UN DONE</option>
                            <option value="1">DONE</option>
                            <option value="2">PENDING</option>
                        </select>
                    </span>

                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>Query</button>
                    <button id="reset" onclick="reset();" class="btn btn-error">Reset</button>
                    <a href="javascript:;" onclick="export1()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>Export</a>
                    <!--                    <a href="javascript:;" onclick="bookAdd()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>上架新书</a>-->
                </span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>Loan Reference No.</th>
                        <th>User ID</th>
                        <th>facebookId</th>
                        <th>Star</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Overdue Days</th>
<!--                        <th>Overdue money amount</th>-->
                        <th>Apply Time</th>
                        <th>Remark</th>
                        <th>status</th>
                        <th>freezeStatus</th>
                        <th>Audit Status</th>

                        <th>Operation</th>
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
    /*var statusEnum = {
        "-2" : "还款申请不通通（重新还款）",
        "-1":"贷款申请不通过（重新申请）",
        "0":"申请贷款（待审核）",
        "1":"审核通过（已放款）",
        "2":"还款申请（申请还款）",
        "3" : "还款审核通过（已还款）",
        "4" : "已逾期（去还款）",
        "5" : "资料审核通过（未放款）"
    }*/
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
            aoData.taskType = $('#taskType').val();
            aoData.username = $('#username').val();
        },
        "createdRow": function (row, data, dataIndex) {
            $(row).children('td').attr('style', 'text-align: center;')
        },
        "aoColumnDefs": [],
        ajax: "<?=site_url('ucenter/task')?>",
        columns: [
            {data: "DebitId"},
            // {data: "DebitMoney"},
            // {data: "DebitPeroid"},
            {data: "UserId"},
            {data: "facebookId"},
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
            // {
            //     data: 'blackStar', render: function (val, type, full) {
            //         if (!val || val < 1) {
            //             return '';
            //         }
            //         return '<div style="width: 24px;height:24px;background:  black;float: left;margin-right: 10px;"></div>';
            //     }
            // },
            // {
            //     data: 'greenStar', render: function (val, type, full) {
            //         if (!val || val < 1) {
            //             return '';
            //         }
            //         return '<div style="width: 24px;height:24px;background:   green;float: left;margin-right: 10px;"></div>';
            //     }
            // },
            {data: "fullName",render: function (val, type, full) {
                    return '<div onclick="check(' + full.DebitId + ')">'+val+'</div>';
                }},
            // {data: "IdCard"},
            {data: "Phone"},
            {data: "overdueDay"},
            // {data: "overdueMoney"},
            {data: "CreateTime"},
            {data: "remark", render: function (val, type, full) {
                if (!val) {
                    return '';
                }
                var str = val.replace(/[<>&"]/g,' ');
                return '<p title='+str+' style="width: 100px;max-height: 50px;overflow: hidden;">'+val+'</p>';
            }},
            {
                data: 'Status', render: function (val, type, full) {
                    return '<div style="color:' + colorEnum[val] + '">' + statusEnum[val] + '</div>';
                }
            },
            {
                data: 'freeze', render: function (val, type, full) {
                    return val == 1 ? 'Freezed' : '';
                }
            },
            {
                data: 'status', render: function (val, type, full) {
                    return val == 1 ? 'DONE' : val == 2 ? 'PENDING' : 'UN DONE';
                }
            },

            {data: ''}
        ],
        "aoColumnDefs": [
            {
                "targets": -1,
                "data": 'id',
                "mRender": function (data, type, full) {
                    var taskType = full.taskType ? full.taskType : 1;
                    var str = '<a style="text-decoration:none" class="ml-5" onclick="record(' + full.UserId + ')" href="javascript:;" title="记录"><i class="Hui-iconfont">&#xe709;Record</i></a>';
                    str += '<a style="text-decoration:none" class="ml-5" onclick="remarkHis(' + full.taskId + ')" href="javascript:;" title="记录"><i class="Hui-iconfont">&#xe709;RemarkHis</i></a>';
                    if(taskType == 1 && full.status != 1){
                        str += '<a style="text-decoration:none" class="ml-5" onclick="check(' + full.DebitId + ')" href="javascript:;" title="审核"><i class="Hui-iconfont">&#xe6df;Audit</i></a>';
                    }
                    if(taskType != 1){
                        str += '<a style="text-decoration:none" class="ml-5" onclick="view(' + full.DebitId + ')" href="javascript:;" title="View"><i class="Hui-iconfont">&#xe6df;View</i></a>';
                    }
                    if (taskType == 3 && full.freeze != 1) {
                        str += '<a style="text-decoration:none" class="ml-5" onclick="freeze(' + full.DebitId + ')" href="javascript:;" title="Freeze"><i class="Hui-iconfont">&#xe6df;Freeze</i></a>';
                    }
                    if (taskType == 3 && full.freeze == 1) {
                        str += '<a style="text-decoration:none" class="ml-5" onclick="unfreeze(' + full.DebitId + ')" href="javascript:;" title="UNFreeze"><i class="Hui-iconfont">&#xe6df;UNFreeze</i></a>';
                    }
                    //remark 有才能done
                    if(full.status != 1 && full.remark){
                        str += '<a style="text-decoration:none" class="ml-5" onclick="done(' + full.taskId + ')" href="javascript:;" title="Bad"><i class="Hui-iconfont">&#xe60b;DONE</i></a>';
                    }
                    if(full.status != 1){
                        str += '<a style="text-decoration:none" class="ml-5" onclick="pending(' + full.taskId + ')" href="javascript:;" title="Bad"><i class="Hui-iconfont">&#xe60b;PENDING</i></a>';
                    }
                    str += '<a style="text-decoration:none" class="ml-5" onclick="remark(' + full.taskId + ',\''+full.remark+'\')" href="javascript:;" title="remark"><i class="Hui-iconfont">&#xe6df;AddRemark</i></a>';
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
            "sInfoEmpty":"No data",
            "sEmptyTable": "No data in the report",
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
        layer_show('Audit', "<?=site_url('admin/loanCheck/')?>" + id, null, null);
    }

    function view(id) {
        layer_show('View', "<?=site_url('admin/overdueCheck/')?>" + id, null, null);
    }

    function record(id) {
        layer_show('Record', "<?=site_url('admin/auditRecord/')?>" + id, null, null);
    }

    function remarkHis(id) {
        layer_show('Record', "<?=site_url('ucenter/remarkHis/')?>" + id, null, null);
    }

    function freeze(id) {
        layer.prompt({title: 'FREEZE How Long？', formType: 0, value:7, btn: ['Confirm', 'Cancel']}, function(pass, index){
            if(isNaN(pass)){
                return;
            }
            pass = parseInt(pass);
            if(!Number.isInteger(pass) || pass<1){
                return;
            }
            $.ajax({
                url: "<?=site_url('ucenter/addFreeze/')?>" + id + '/' + pass,
                type: 'get',
                dataType: 'json'
            }).done(function (res) {
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


        /*layer.confirm('Confirm FREEZE？', {
            btn: ['YES', 'NO'],
            title: 'Confirm',
            icon: 3
        }, function () {
            $.ajax({
                url: "<?=site_url('ucenter/addFreeze/')?>" + id,
                type: 'get',
                dataType: 'json'
            }).done(function (res) {
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
        });*/
    }

    function unfreeze(id) {
        layer.confirm('Confirm UNFREEZE？', {
            btn: ['YES', 'NO'],
            title: 'Confirm',
            icon: 3
        }, function () {
            $.ajax({
                url: "<?=site_url('ucenter/removeFreeze/')?>" + id,
                type: 'get',
                dataType: 'json'
            }).done(function (res) {
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

    function remark(taskId,str) {
        layer.prompt({title: 'input remark', formType: 2, value:'', btn: ['Confirm', 'Cancel']}, function(pass, index){
            if(!pass) {
                return;
            }
            $.ajax({
                url: "<?=site_url('ucenter/addremark/')?>" + taskId,
                type: 'post',
                dataType: 'json',
                data:{remark:pass}
            }).done(function (res) {
                console.log(res)
                if (!res || res.status == 500) {
                    layer.msg(res.msg);
                    return;
                }
                layer.msg('操作成功', {time: 400}, function () {
                    window.location.reload();
                })
            }).fail(function () {
                layer.msg('操作失败，稍后再试');
            });
            layer.close(index);
        });
        //layer_show('Audit', "<?//=site_url('admin/loanCheck/')?>//" + id, null, null);
    }

    function done(id) {
        layer.confirm('Confirm DONE？', {
            btn: ['YES', 'NO'],
            title: 'Confirm',
            icon: 3
        }, function () {
            $.ajax({
                url: "<?=site_url('ucenter/done/')?>" + id,
                type: 'get',
                dataType: 'json'
            }).done(function (res) {
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

    function pending(id) {
        layer.confirm('Confirm Pending？', {
            btn: ['YES', 'NO'],
            title: 'Confirm',
            icon: 3
        }, function () {
            $.ajax({
                url: "<?=site_url('ucenter/pending/')?>" + id,
                type: 'get',
                dataType: 'json'
            }).done(function (res) {
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

    function bookUpdate(id) {
        layer_show('Audit', "<?=site_url('admin/bookUpdate')?>/" + id, null, null);
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
        var status = $('#status').val();
        var debitid = $('#debitid').val();
        var username = $('#username').val();

        location.href = "<?=site_url('export/task')?>" + '?userid='
            + userid + '&status=' + status + '&debitid=' + debitid + '&username=' + username;
    }
</script>
<!--/请在上方写此页面业务相关的脚本-->

</body>
</html>
