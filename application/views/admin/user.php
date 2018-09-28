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
			<span class="l">
			<!--<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius">
			<i class="Hui-iconfont"></i> 批量删除</a>
			-->
                <input type="text" name="" id="q" placeholder="用户编号" style="width:250px" class="input-text">
                <button id="search" class="btn btn-success" ><i class="Hui-iconfont"></i>查询</button>
<!--			<a href="javascript:;" id="addUser" class="btn btn-primary radius"><i class="Hui-iconfont"></i> 添加用户</a>-->

			</span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>UserId</th>
                        <th>name</th>
                        <th>IdCard</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>bankName</th>
                        <th>reason</th>
                        <th>status</th>
                        <th>yesNumber</th>
                        <th>loadId</th>
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
        },
        "aoColumnDefs": [
        ],
        "createdRow": function( row, data, dataIndex ) {
//            $(row).children('td').eq(0).attr('style', 'text-align: center;')
            $(row).children('td').attr('style', 'text-align: center;')
        },
        ajax:"<?=site_url('admin/user')?>",
        /**
         * <th>UserId</th>
         <th>name</th>
         <th>IdCard</th>
         <th>Address</th>
         <th>Phone</th>
         <th>bankName</th>
         <th>reason</th>
         <th>status</th>
         <th>yesNumber</th>
         <th>loadId</th>
         */
        columns : [
            {data : "UserId"},
            {data : "name"},
            {data : "IDCard"},
            {data : "Address"},
            {data : "phone"},
            {data : "bankName"},
            {data : "reson"},
            {data : "status"},
            {data : "yesNumber"},
            {data : "loanId"},
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
            "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
            "sInfoFiltered": "数据表中共为 _MAX_ 条记录",
            "sSearch": "搜索",
            "oPaginate":
                {
                    "sFirst": "首页",
                    "sPrevious": "上一页",
                    "sNext": "下一页",
                    "sLast": "末页"
                }
        }
    });
    $(function(){
        $('#search').bind("click", function () { //按钮 触发table重新请求服务器
            var q = $('#q').val();
            table.fnDraw();
        });


    })

    function scoreAdd(id) {
        var $scoreAddWrap = $('#scoreAddWrap');
        layer.open({
            type: 1,
            area: ['380px', '270px'],
            shade:0.8,
            title: '积分',
            btnAlign: 'c',
            btn: ['点击保存'],
            content:$scoreAddWrap,
            yes:function(){
                var data = getFormJson($scoreAddWrap.find('form'));
                data.score = data.do + data.score;
                data.user_id = id;
                $.ajax({
                    url:"<?=site_url('admin/scoreAdd')?>",
                    type:'post',
                    dataType:'json',
                    data:data
                }).done(function(res){
                    if(!res || res.status == 500){
                        layer.msg(res.msg);
                        return ;
                    }
                    layer.msg('保存成功',{time:400},function(){
                        location.reload();
                    })
                }).fail(function(){
                    layer.msg('请求失败，稍后再试');
                })
            }
        });
    }

    function scoreLog(id) {
        $.getJSON("<?=site_url('admin/scoreLog')?>/" + id,function (res) {
            var $scoreLogWrap = $('#scoreLogWrap');
            $scoreLogWrap.find('table').empty();
            var str = '<tr><td>积分</td><td>备注</td><td>时间</td></tr>';
            if(res.data){
                $.each(res.data,function (i, v) {
                    str += '<tr><td>'+v.score+'</td><td>'+v.remark+'</td><td>'+v.create_time+'</td></tr>';
                });
            }
            $scoreLogWrap.find('table').append(str);
            layer.open({
                type: 1,
                area: ['500px', '500px'],
                shade:0.8,
                shadeClose: true,
                title: '积分记录',
                content:$scoreLogWrap
            });
        })
    }

    function downloadLog(id) {
        $.getJSON("<?=site_url('admin/downloadLog')?>/" + id,function (res) {
            var $scoreLogWrap = $('#scoreLogWrap');
            $scoreLogWrap.find('table').empty();
            var str = '<tr><td>电子书名称</td><td>下载时间</td></tr>';
            if(res.data){
                $.each(res.data,function (i, v) {
                    str += '<tr><td>'+v.book_name+'</td>><td>'+v.create_time+'</td></tr>';
                });
            }
            $scoreLogWrap.find('table').append(str);
            layer.open({
                type: 1,
                area: ['500px', '500px'],
                shade:0.8,
                shadeClose: true,
                title: '下载记录',
                content:$scoreLogWrap
            });
        })
    }


</script>
<!--/请在上方写此页面业务相关的脚本-->

<div id="userAddWrap" style="display:none;">
    <article class="cl pd-20">
        <form method="post" class="form form-horizontal" novalidate="novalidate">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">用户名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="用户名"  name="username">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">姓名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="姓名"  name="realname">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">电话：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="姓名"  name="phone">
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
                <label class="form-label col-xs-4 col-sm-3">密码：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text valid"  value="123456" placeholder="密码" name="password">
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
<!--设置积分-->
<div id="scoreAddWrap" style="display:none;">
    <article class="cl pd-20">
        <form method="post" class="form form-horizontal" novalidate="novalidate">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">操作：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select class="select valid" size="1" name="do">
					<option value="+">添加</option>
					<option value="-">扣除</option>
				</select>
				</span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">积分：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder=""  name="score">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">理由：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder=""  name="remark">
                </div>
            </div>
        </form>
    </article>
</div>
<div id="scoreLogWrap" style="display: none">
    <article class="cl pd-20">
    <table class="table table-border table-bordered table-hover">

    </table>
    </article>
</div>
</body>
</html>