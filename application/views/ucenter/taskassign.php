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
        <nav class="breadcrumb"><i class="Hui-iconfont"></i> Mgr
            <span class="c-gray en">&gt;</span>
            Mgr
            <span class="c-gray en">&gt;</span>
            List  </nav>
        <article class="cl pd-20">
            <div class="cl pd-5 bg-1 bk-gray">
                <span class="l pd-5">

                    <input type="number" id="debitId" placeholder="Loan Reference No." style="width:250px" class="input-text">
                    <input type="number" id="adminId" placeholder="Admin ID" style="width:250px" class="input-text">
<!--                    <input type="number" id="idcard" placeholder="IdCard" style="width:250px" class="input-text">-->
<!--                    <input type="number" id="phone" placeholder="mobile phone" style="width:250px" class="input-text">-->

                </span>

                <div class="l pd-5">
                    <input type="text" placeholder="Assign Time" id="start_time" style="width: 200px;" onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                    -
                    <input type="text" placeholder="Assign Time" id="end_time" style="width: 200px;" onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                </div>

                <span class="l pd-5">
                    <span class="select-box inline">
                        <select id="status" class="select">
                            <option value="">status</option>
                            <option value="0">UNDONE</option>
                            <option value="1">DONE</option>
                            <option value="2">PENDING</option>
                        </select>
                    </span>
                    <span class="select-box inline">
                        <select id="type" class="select">
                            <option value="">type</option>
                            <option value="1">Loan</option>
                            <option value="2">Reminds</option>
                            <option value="3">Overdue</option>
                        </select>
                    </span>

                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>Query</button>
                    <button id="reset" onclick="reset();" class="btn btn-error">Reset</button>
                    <a href="javascript:;" onclick="batchcheck()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>Batch Assign</a>
                </span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th><input type="checkbox" id="selectall" value=""></th>
                        <th>taskId</th>
                        <th>DebitId</th>
                        <th>taskType</th>
                        <th>adminId</th>
<!--                        <th>debitId</th>-->
                        <th>status</th>
                        <th>CreateTime</th>
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
            aoData.adminId = $('#adminId').val();
            aoData.status = $('#status').val();
            aoData.debitId = $('#debitId').val();
            aoData.type = $('#type').val();
            aoData.startTime = $('#start_time').val();
            aoData.endTime = $('#end_time').val();
        },
        "createdRow": function (row, data, dataIndex) {
            $(row).children('td').attr('style', 'text-align: center;')
        },
        "aoColumnDefs": [],
        ajax: "<?=site_url('ucenter/taskassign')?>",
        columns: [
            {data: 'taskId', render: function (val, type, all) {
                    return '<input type="checkbox" class="selecttaskid" value="'+val+'">';
                }},
            {data: "taskId"},
            {data: "DebitId"},
            {data: "taskType", render: function (val, type, full) {
                    if(val == 1){
                        return 'Loan';
                    }
                    if(val == 2){
                        return 'Reminds';
                    }
                    if(val == 3){
                        return 'Overdue';
                    }
                    return '';
                }},
            {data: "adminId"},
            // {data: "debitId"},
            {
                data: 'status', render: function (val, type, full) {
                    return val == 1 ? 'DONE' : 'UN DONE';
                }
            },
            {data: 'createTime'},
            {data: ''}
        ],
        "aoColumnDefs": [
            {
                "targets": -1,
                "data": 'id',
                "mRender": function (data, type, full) {
                    var str = '<a style="text-decoration:none" class="ml-5" onclick="check(' + full.taskId + ')" href="javascript:;" title=""><i class="Hui-iconfont">&#xe6df;Assign</i></a>';
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
        layer_show('Audit', "<?=site_url('ucenter/taskassignedit/')?>" + id, null, null);
    }

    function batchcheck() {
        var ids = '';
        $('.selecttaskid:checked').each(function (i, v) {
            ids += ($(v).val() + '-');
        });
        if(!ids){
            return;
        }
        layer_show('Audit', "<?=site_url('ucenter/batchtaskassignedit/')?>" + ids, null, null);
    }

    function admin_add(title, url, w, h) {
        layer_show(title, url, w, h);
    }

    function taskmgrdel(id) {
        layer.confirm('Confirm Delete？', function (index) {
            var deleteUrl = "<?=site_url('ucenter/taskmgrdel')?>" + '/' + id;
            $.ajax({
                url: deleteUrl,
                type: 'post',
                dataType: 'json'
            }).done(function (res) {
                if (!res || res.status == 500) {
                    layer.msg(res.msg);
                    return;
                }
                layer.msg('已删除', {time: 400}, function () {
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
            w = 700;
        }
        ;
        if (h == null || h == '') {
            h = ($(window).height() - 400);
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