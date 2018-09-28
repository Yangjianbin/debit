
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
			<a href="javascript:;" id="addUser" class="btn btn-primary radius"><i class="Hui-iconfont"></i> 添加用户</a>

			</span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">

                    <thead>
                    <tr class="text-c">
                        <th>ID</th>
                        <th>用户名</th>
                        <th>姓名</th>
                        <th>电话</th>
                        <th>部门</th>
                        <th>角色</th>
                        <th>默认设置</th>
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
<script type="text/javascript" src="<?=base_url()?>static/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">
    var userid = "<?=$this->session->user->id?>";
    var deleteUrl = "<?=site_url('welcome/userDelete')?>";
    var table = $('.table-sort').dataTable({
        "aaSorting": [[ 1, "desc" ]],//默认第几个排序
        bSort : false,//是否允许排序
        bLengthChange : false,//是否显示每页大小的下拉框
        searching : false,//是否显示搜索框
        "bStateSave": false,//状态保存
        serverSide : true,
        fnServerParams : function (aoData) {
            aoData._rand = Math.random();
        },
        "aoColumnDefs": [
        ],
        ajax:"<?=site_url('welcome/userList')?>",
        columns : [
            {data : "id"},
            {data : "username"},
            {data : "realname"},
            {data : "phone"},
            {data : "dpart"},
            {data : "role_name"},
            {data : "dpart","render":function(data, type, full){
                return '<a style="text-decoration:none" class="ml-5" onclick="user_setting('+full.id+')" href="javascript:;" title="设置"><i class="Hui-iconfont">&#xe6df;</i></a>';
            }},
            {data:''}
        ],
        "aoColumnDefs":[
            {
                "targets": -1,
                "data":'id',
                "mRender": function(data, type, full){
                    if(userid == full.id){
                        return null;
                    }
                    return '<a style="text-decoration:none" class="ml-5" onclick="user_del('+full.id+')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>';
                }
            }
        ],
        "sPaginationType": "full_numbers",
        iDisplayLength:5,
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
        $('#addUser').click(function(){
            var $userAddWrap = $('#userAddWrap');
            layer.open({
                type: 1,
                area: ['380px', '400px'],
                shade:0.8,
                title: '添加用户',
                btnAlign: 'c',
                btn: ['确认添加'],
                content:$userAddWrap,
                yes:function(){
                    var username = $userAddWrap.find('[name=username]').val();
                    var phone = $userAddWrap.find('[name=phone]').val();
                    var password = $userAddWrap.find('[name=password]').val();
                    var role = $userAddWrap.find('[name=role]').val();
                    var dpart = $userAddWrap.find('[name=dpart]').val();
                    var realname = $userAddWrap.find('[name=realname]').val();
                    if(!username || !realname || !password){
                        layer.msg('请输入必填项');return;
                    }
                    var obj = {username:username,phone:phone,password:password,role:role,dpart:dpart,realname:realname};
                    $.ajax({
                        url:"<?=site_url('welcome/userAdd')?>",
                        type:'post',
                        dataType:'json',
                        data:obj
                    }).done(function(res){
                        if(!res || res.status == 500){
                            layer.msg(res.msg);
                            return ;
                        }
                        layer.msg('添加成功',{time:400},function(){
                            location.reload();
                        })
                    }).fail(function(){
                        layer.msg('请求失败，稍后再试');
                    })
                }
            });
        });

        $('[name=dpart]').change(function(){
            var val = $(this).val();
            var str = val + '管理员';
            var options = '<option>职员</option><option value="'+($(this).get(0).selectedIndex + 2)+'">'+str+'</option>';
            $('[name=role]').html(options);
            console.log(options);
        });
    })

    /*资讯-添加*/
    function article_add(title,url,w,h){
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }
    /*资讯-编辑*/
    function article_edit(title,url,id,w,h){
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }
    /*资讯-删除*/
    function user_del(id){
        layer.confirm('确认要删除吗？',function(index){
            $.ajax({
                type: 'POST',
                url: deleteUrl,
                dataType: 'json',
                data:{id:id},
                success: function(data){
                    if(data.status == 200){
                        layer.msg('删除成功',{time:800},function(){
                            location.reload();
                        })
                    }else{
                        layer.msg('操作失败');
                    }
                },
                error:function(data) {
                    layer.msg(data.msg);
                },
            });
        });
    }

    function user_setting(id){
        var nTrs = table.fnGetNodes();//fnGetNodes获取表格所有行，nTrs[i]表示第i行tr对象
        var $userSettingWrap = $('#userSettingWrap');
        for(var i = 0; i < nTrs.length; i++){
            var d = table.fnGetData(nTrs[i]);
            if(d.id == id){
                $userSettingWrap.find('form [name=breakfast]').val(d.breakfast);
                $userSettingWrap.find('form [name=lunch]').val(d.lunch);
                $userSettingWrap.find('form [name=dinner]').val(d.dinner);
                $userSettingWrap.find('form [name=busDir]').val(d.bus_dir);
                $userSettingWrap.find('form [name=morning]').val(d.morning);
                $userSettingWrap.find('form [name=evening]').val(d.evening);
            }
        }

        layer.open({
            type: 1,
            area: ['380px', '450px'],
            shade:0.8,
            title: '用户默认信息设置',
            btnAlign: 'c',
            btn: ['点击保存'],
            content:$userSettingWrap,
            yes:function(){
                var data = $userSettingWrap.find('form').serialize();
                data = data + '&id=' + id;
                $.ajax({
                    url:"<?=site_url('welcome/userSetting')?>",
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


    /*资讯-审核*/
    function article_shenhe(obj,id){
        layer.confirm('审核文章？', {
                btn: ['通过','不通过','取消'],
                shade: false,
                closeBtn: 0
            },
            function(){
                $(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="article_start(this,id)" href="javascript:;" title="申请上线">申请上线</a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已发布</span>');
                $(obj).remove();
                layer.msg('已发布', {icon:6,time:1000});
            },
            function(){
                $(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="article_shenqing(this,id)" href="javascript:;" title="申请上线">申请上线</a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-danger radius">未通过</span>');
                $(obj).remove();
                layer.msg('未通过', {icon:5,time:1000});
            });
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
                <label class="form-label col-xs-4 col-sm-3">角色：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select class="select valid" size="1" name="role">
					<option value="" selected>职员</option>
					<option value="2">办公室管理员</option>
                    <!--<option value="" selected>职员</option>
                    <option value="1">系统管理员</option>
                    <option value="2">办公室管理员</option>
                    <option value="3">设备科管理员</option>
                    <option value="4">安全科管理员</option>
                    <option value="5">收费科管理员</option>
                    <option value="6">养护科管理员</option>
                    <option value="7">监控分中心管理员</option>
                    -->
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
</body>
</html>