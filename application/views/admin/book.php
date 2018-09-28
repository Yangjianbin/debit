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
                <span class="select-box inline">
                    <select id="category" class="select">
                        <option value="">全部分类</option>
                        <?php foreach ($category as $k=>$v):?>
                            <option value="<?=$v['id'] ?>"><?=$v['pid'] ? '--' : ''?><?=$v['name']?></option>
                        <?php endforeach;?>
                    </select>
		        </span>
                <span class="select-box inline">
                    <select class="select" id="status">
                        <option value="">全部</option>
                        <option value="0">上架</option>
                        <option value="1">下架</option>
                    </select>
		        </span>
                <span class="select-box inline">
                    <select class="select" id="invalid">
                        <option value="">全部</option>
                        <option value="0">有效</option>
                        <option value="1">过期</option>
                    </select>
		        </span>

                <input type="text" id="q" placeholder="ISBN、书名" style="width:250px" class="input-text">
                <button id="search" class="btn btn-success" ><i class="Hui-iconfont"></i>查询</button>
			    <a href="javascript:;" onclick="bookAdd()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>上架新书</a>

			</span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>书名</th>
                        <th>图片封面</th>
                        <th>出版社</th>
                        <th>出版时间</th>
                        <th>ISBN</th>
                        <th>分类</th>
                        <th>状态</th>
                        <th>下载链接</th>
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
    var deleteUrl = "<?=site_url('Admin/bookDel')?>";
    var table = $('.table-sort').dataTable({
        "aaSorting": [[1, "desc"]],//默认第几个排序
        bSort: false,//是否允许排序
        bLengthChange: false,//是否显示每页大小的下拉框
        searching: false,//是否显示搜索框
        "bStateSave": false,//状态保存
        serverSide: true,
        fnServerParams: function (aoData) {
            aoData._rand = Math.random();
            aoData.category = $('#category').val();
            aoData.status = $('#status').val();
            aoData.invalid = $('#invalid').val();
            aoData.q = $('#q').val();
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).children('td').attr('style', 'text-align: center;')
        },
        "aoColumnDefs": [],
        ajax: "<?=site_url('admin/book')?>",
        columns: [
            {data: "name"},
            {data: "cover",render:function (data, type, full) {
                var str = '';
                if(data){
                    str += '<img style="max-width:100px;max-height: 40px;" alt="" src="'+data+'">';
                }
                return str;
            }},
            {data: "publisher"},
            {data: "publish_time"},
            {data: "isbn"},
            {data: "categoryStr"},
            {data: "status",render:function (data, type, full) {
                if(data == 1){
                    return '<span style="color:red">下架</span>';
                }
                return '上架';
            }},
            {data: "invalid",render:function (data, type, full) {
                if(data == 1){
                    return '<span style="color:red">过期</span>';
                }
                return '有效';
            }},
            {data: ''}
        ],
        "aoColumnDefs": [
            {
                "targets": -1,
                "data": 'id',
                "mRender": function (data, type, full) {
                    return '<a style="text-decoration:none" class="ml-5" onclick="bookUpdate(' + full.id + ')" href="javascript:;" title="查看或编辑"><i class="Hui-iconfont">&#xe6df;</i></a>';
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
            "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
            "sInfoFiltered": "数据表中共为 _MAX_ 条记录",
            "sSearch": "搜索",
            "oPaginate": {
                "sFirst": "首页",
                "sPrevious": "上一页",
                "sNext": "下一页",
                "sLast": "末页"
            }
        }
    });

    $(function () {
        $('#search').bind("click", function () { //按钮 触发table重新请求服务器
            table.fnDraw();
        });
    })

    function bookAdd() {
        layer_show('新书上架', "<?=site_url('admin/bookAdd')?>", null, null);
    }
    function bookUpdate(id) {
        layer_show('新书上架', "<?=site_url('admin/bookUpdate')?>/" + id, null, null);
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