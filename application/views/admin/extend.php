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
        <nav class="breadcrumb"><i class="Hui-iconfont"></i> Extend Mgr
            <span class="c-gray en">&gt;</span>
            Extend Mgr
            <span class="c-gray en">&gt;</span>
            List  </nav>
        <article class="cl pd-20">

            <div class="cl pd-5 bg-1 bk-gray ">
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
                            <option value="6">申请延期</option>
                        </select>
                    </span>-->

                    <button id="search" class="btn btn-success"><i class="Hui-iconfont"></i>Query</button>
                    <button id="reset" onclick="reset();" class="btn btn-error">Reset</button>
                    <!--                    <a href="javascript:;" onclick="bookAdd()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>上架新书</a>-->
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
<!--                        <th>IDCard</th>-->
                        <th>Mobile Number</th>
                        <th>Apply Time</th>
                        <th>Overdue Money</th>
                        <th>status</th>
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
        "5" : "approved",
        "6" : "Extend",
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
        ajax: "<?=site_url('admin/extend')?>",
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
            // {data: "IdCard"},
            {data: "Phone"},
            {data: "CreateTime"},
            {data: "overdueMoney"},
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
                    return '<a style="text-decoration:none" class="ml-5" onclick="check(' + full.DebitId + ')" href="javascript:;" title="确认还款"><i class="Hui-iconfont">&#xe6df; EXTEND</i></a>';
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

<div id="bookAddWrap" style="display:none;">
    <article class="cl pd-20">
        <form method="post" class="form form-horizontal" novalidate="novalidate">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">用户名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="用户名" name="username">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">姓名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="姓名" name="realname">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">电话：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="姓名" name="phone">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">部门：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select class="select valid" size="1" name="dpart">
					<option value="办公室">办公室</option>
					<option value="设备科">设备科</option>
					<option value="安全科">安全科</option>
					<option value="收费科">收费科</option>
					<option value="养护科">养护科</option>
					<option value="监控分中心">监控分中心</option>
				</select>
				</span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">角色：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select class="select valid" size="1" name="role">
					<option value="" selected>职员</option>
					<option value="2">办公室管理员</option>
				</select>
				</span>
                </div>
            </div>

            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text valid" value="123456" placeholder="密码" name="password">
                </div>
            </div>

        </form>
    </article>
</div>

<div id="userSettingWrap" style="display:none;">
    <article class="cl pd-20">
        <form method="post" class="form form-horizontal" novalidate="novalidate">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">早餐</label>
                <div class="formControls col-xs-8 col-sm-9">
			<span class="select-box">
				<select class="select valid" size="1" name="breakfast">
					<option value="1">yes</option>
					<option value="0">no</option>
				</select></span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">午餐</label>
                <div class="formControls col-xs-8 col-sm-9">
			<span class="select-box">
				<select class="select valid" size="1" name="lunch">
					<option value="1">yes</option>
					<option value="0">no</option>
				</select></span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">晚餐</label>
                <div class="formControls col-xs-8 col-sm-9">
			<span class="select-box">
				<select class="select valid" size="1" name="dinner">
					<option value="1">yes</option>
					<option value="0">no</option>
				</select></span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">班车方向</label>
                <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box">
				<select class="select valid" size="1" name="busDir">
					<option value="1">杭州</option>
					<option value="2">湖州</option>
					<option value="3">嘉兴</option>
				</select>
				</span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">早班车</label>
                <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box">
				<select class="select valid" size="1" name="morning">
					<option value="1">yes</option>
					<option value="0">no</option>
				</select>
				</span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">晚班车</label>
                <div class="formControls col-xs-8 col-sm-9">
			<span class="select-box">
				<select class="select valid" size="1" name="evening">
					<option value="1">yes</option>
					<option value="0">no</option>
				</select></span>
                </div>
            </div>

        </form>
    </article>
</div>
</body>
</html>