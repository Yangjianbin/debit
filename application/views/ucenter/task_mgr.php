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

            <div class="cl pd-5 bg-1 bk-gray ">
                <span class="l pd-5">
                    <a href="javascript:;"
                       onclick="admin_add('添加','<?= site_url("ucenter/taskmgradd") ?>','','310')"
                       class="btn btn-primary radius">
                        <i class="Hui-iconfont">&#xe600;</i> Add</a>
<!--                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>Query</button>-->
<!--                    <button id="reset" onclick="reset();" class="btn btn-error">Reset</button>-->
                    <!--                    <a href="javascript:;" onclick="bookAdd()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>上架新书</a>-->
                </span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>AdminId</th>
                        <th>Username</th>
                        <th>Task Type</th>
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
        },
        "createdRow": function (row, data, dataIndex) {
            $(row).children('td').attr('style', 'text-align: center;')
        },
        "aoColumnDefs": [],
        ajax: "<?=site_url('ucenter/taskmgr')?>",
        columns: [
            {data: "adminId"},
            {data: "username"},
            {data: "taskType", render: function (val, type, full) {
                if(val == 1){
                    return 'Customer service-CM';
                }
                if(val == 2){
                    return 'Reminder-RM';
                }
                if(val == 3){
                    return 'Overdue-OD';
                }
                return '';
            }},
            {data: ''}
        ],
        "aoColumnDefs": [
            {
                "targets": -1,
                "data": 'id',
                "mRender": function (data, type, full) {
                    // var str =  '<a style="text-decoration:none" class="ml-5" onclick="check(' + full.id + ')" href="javascript:;" title=""><i class="Hui-iconfont">&#xe6df;Edit</i></a>' +
                      var str =  '<a title="删除" href="javascript:;" onclick="taskmgrdel('+full.id+')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;Remove</i></a>';
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
        layer_show('Audit', "<?=site_url('ucenter/taskmgredit/')?>" + id, null, null);
    }

    function admin_add(title, url, w, h) {
        layer_show(title, url, w, h);
    }

    function taskmgrdel(id) {
        layer.confirm('确认要删除吗？', function (index) {
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