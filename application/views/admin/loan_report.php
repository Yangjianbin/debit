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
                    <input type="text" placeholder="Start Time" id="start_time" style="width: 200px;"
                           onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                    -
                    <input type="text" placeholder="End Time" id="end_time" style="width: 200px;"
                           onfocus="WdatePicker()"
                           class="input-text Wdate"/>
                </div>

                <span class="l pd-5">
                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>Query</button>
                    <button id="reset" onclick="reset();" class="btn btn-error">Reset</button>
                    <a href="javascript:;" onclick="export1()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>Export</a>
                </span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>Date</th>
                        <th>Loan Reference ID</th>
                        <th>User ID</th>
                        <th>ID card</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Residential Address</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Phone Number</th>
                        <th>Bank Name</th>
                        <th>Bank Account Number</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Yes No.</th>
                        <th>No. of Loan</th>
                        <th>Loan Process</th>
                        <th>Action</th>
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
<script type="text/javascript" src="<?=base_url()?>static/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/h-ui.admin/css/style.css"/>
<script type="text/javascript" src="<?=base_url()?>static/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">
    var deleteUrl = "<?=site_url('Admin/userDel')?>";
    var table = $('.table-sort').dataTable({
        "aaSorting": [[ 1, "desc" ]],//默认第几个排序
        bSort : false,//是否允许排序
        bLengthChange : false,//是否显示每页大小的下拉框
        searching : false,//是否显示搜索框
        "bStateSave": false,//状态保存
        serverSide : true,
        fnServerParams : function (aoData) {
            aoData._rand = Math.random();
            aoData.id = $('#q').val();
            aoData.start_time = $('#start_time').val();
            aoData.end_time = $('#end_time').val();
        },
        "aoColumnDefs": [
        ],
        "createdRow": function( row, data, dataIndex ) {
//            $(row).children('td').eq(0).attr('style', 'text-align: center;')
            $(row).children('td').attr('style', 'text-align: center;')
        },
        ajax:"<?=site_url('admin/loanReport')?>",
        columns : [
            {data : "CreateTime"},
            {data : "DebitId"},
            {data : "UserId"},
            {data : "IdCard"},
            {data : "fullName"},
            {data : "age"},
            {data : "residentialAddress"},
            {data : "residentialCity"},
            {data : "residentialProvince"},
            {data : "Phone"},
            {data : "BankName"},
            {data : "BankCode"},
            {data : "age", render: function (val) {
                    return '';
                }},
            {data : "Status"},
            {data : "yes"},
            {data : "age", render: function (val) {
                    return '';
                }},
            {data : "age", render: function (val) {
                    return '';
                }},
            {data:''}
        ],
        "aoColumnDefs":[
            {
                "targets": -1,
                "data":'id',
                "mRender": function(data, type, full){
                    var str = '';
                    // str += '<a style="text-decoration:none" class="ml-5" onclick="downloadLog('+full.id+')" href="javascript:;" title="下载记录"><i class="Hui-iconfont">&#xe640;</i></a>';
                    // str += '<a style="text-decoration:none" class="ml-5" onclick="scoreLog('+full.id+')" href="javascript:;" title="积分记录"><i class="Hui-iconfont">&#xe709;</i></a>';
                    // str += '<a style="text-decoration:none" class="ml-5" onclick="scoreAdd('+full.id+')" href="javascript:;" title="修改积分"><i class="Hui-iconfont">&#xe6df;</i></a>';
                    return str;
                }
            }
        ],
        "sPaginationType": "full_numbers",
        iDisplayLength:50,
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
    $(function(){
        $('#search').bind("click", function () { //按钮 触发table重新请求服务器
            var q = $('#q').val();
            table.fnDraw();
        });


    })
    function reset() {
        $(':input').val('');
    }

    function export1() {
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();
        location.href = "<?=site_url('admin/export3')?>" + '?start_time=' + startTime + '&end_time=' + endTime;
    }

</script>
<!--/请在上方写此页面业务相关的脚本-->

<!--设置积分-->
</body>
</html>