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


    <div class="Hui-article">
        <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Home
            <span class="c-gray en">&gt;</span>
            Authority Mgr
            <span class="c-gray en">&gt;</span>
            Role
        </nav>
        <article class="cl pd-20">

            <div class="cl pd-5 bg-1 bk-gray">
                <!--<span class="l pd-5">

                    <input type="number" id="debitid" placeholder="贷款编号" style="width:250px" class="input-text">
                    <input type="number" id="userid" placeholder="用户ID" style="width:250px" class="input-text">
                    <input type="number" id="idcard" placeholder="身份证号" style="width:250px" class="input-text">
                    <input type="number" id="phone" placeholder="电话" style="width:250px" class="input-text">

                </span>

                <div class="l pd-5">
                    <input type="text" placeholder="提交时间" id="start_time" style="width: 200px;" onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                    -
                    <input type="text" placeholder="提交时间" id="end_time" style="width: 200px;" onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                    <span class="select-box inline">
                        <select id="status" class="select">
                            <option value="">状态</option>
                            <option value="0">未审核</option>
                            <option value="6">通过待放款</option>
                            <option value="-1">未通过</option>
                        </select>
                    </span>
                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>查询</button>
                    <button id="reset" onclick="reset();" class="btn btn-error">重置</button>
                </div>-->
                <span class="l pd-5">
                    <a href="javascript:;"
                       onclick="admin_add('添加角色','<?= site_url("auth/roleAdd") ?>','','510')"
                       class="btn btn-primary radius">
                        <i class="Hui-iconfont">&#xe600;</i> Add Role</a>
                </span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Create Time</th>
                        <th>操作</th>
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
        },
        "createdRow": function (row, data, dataIndex) {
            $(row).children('td').attr('style', 'text-align: center;')
        },
        "aoColumnDefs": [],
        ajax: "<?=site_url('auth/role')?>",
        columns: [
            {data: "id"},
            {data: "rolename"},
            {data: "gmt_create"},
            {
                data: function (val, type, full) {
                    var id = val.id;
                    var editUrl = "<?=site_url('auth/roleEdit')?>" + '/' + id;
                    var str = '<a title="编辑" href="javascript:;" ' +
                    'onclick="role_edit(\'角色编辑\',\'' + editUrl + '\',\'800\',\'500\')" ' +
                    'class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> '
                    return str + '<a title="删除" href="javascript:;" onclick="role_del(this,' + id + ')" class="ml-5" style="text-decoration:none">' +
                        '<i class="Hui-iconfont">&#xe6e2;</i></a>';
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
    });

    /*编辑*/
    function role_edit(title, url, w, h) {
        layer_show(title, url, w, h);
    }

    /*管理员-删除*/
    function role_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            var deleteUrl = "<?=site_url('auth/roleDelete')?>" + '/' + id;
            console.log(deleteUrl)
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

    function admin_add(title, url, w, h) {
        layer_show(title, url, w, h);
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
            w = 900;
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