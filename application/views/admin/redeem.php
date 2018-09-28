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
                <input type="text" name="" id="q" placeholder="兑换码" style="width:250px" class="input-text">
                <button id="search" class="btn btn-success" ><i class="Hui-iconfont"></i>查询</button>
                			<a href="javascript:;" onclick="redeemAdd()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>生成兑换码</a>

			</span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>兑换码</th>
                        <th>积分值</th>
                        <th>生成时间</th>
                        <th>过期时间</th>
                        <th>状态</th>
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
    var deleteUrl = "<?=site_url('Admin/redeemDel')?>";
    var table = $('.table-sort').dataTable({
        "aaSorting": [[ 1, "desc" ]],//默认第几个排序
        bSort : false,//是否允许排序
        bLengthChange : false,//是否显示每页大小的下拉框
        searching : false,//是否显示搜索框
        "bStateSave": false,//状态保存
        serverSide : true,
        fnServerParams : function (aoData) {
            aoData._rand = Math.random();
            aoData.code = $('#q').val();
        },
        "aoColumnDefs": [
        ],
        "createdRow": function( row, data, dataIndex ) {
//            $(row).children('td').eq(0).attr('style', 'text-align: center;')
            $(row).children('td').attr('style', 'text-align: center;')
        },
        ajax:"<?=site_url('admin/redeem')?>",
        columns : [
            {data : "code",render:function (data, type, full) {
                if(full.type == 2){
                    return full.prefix +'-'+ full.code;
                }
                return data;
            }},
            {data : "score"},
            {data : "create_time"},
            {data : "expire_time"},
            {data : "status"},
            {data:''}
        ],
        "aoColumnDefs":[
            {
                "targets": -1,
                "data":'id',
                "mRender": function(data, type, full){
                    var str = '';
                    //str += '<a style="text-decoration:none" class="ml-5" onclick="downloadLog('+full.id+')" href="javascript:;" title="修改积分"><i class="Hui-iconfont">&#xe640;</i></a>';
                    str += '<a style="text-decoration:none" class="ml-5" onclick="redeemLog('+full.id+')" href="javascript:;" title="兑换记录"><i class="Hui-iconfont">&#xe709;</i></a>';
                    //str += '<a style="text-decoration:none" class="ml-5" onclick="scoreAdd('+full.id+')" href="javascript:;" title="修改积分"><i class="Hui-iconfont">&#xe6df;</i></a>';
                    return str;
                }
            }
        ],
        "sPaginationType": "full_numbers",
        iDisplayLength:10,
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

    function redeemAdd() {
        var $redeemAddWrap = $('#redeemAddWrap');
        layer.open({
            type: 1,
            area: ['380px', '370px'],
            shade:0.8,
            title: '兑换码',
            btnAlign: 'c',
            btn: ['点击生成'],
            content:$redeemAddWrap,
            yes:function(){
                var data = getFormJson($redeemAddWrap.find('form'));
                $.ajax({
                    url:"<?=site_url('admin/redeemAdd')?>",
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

    function redeemLog(id) {
        $.getJSON("<?=site_url('admin/redeemLog')?>/" + id,function (res) {
            var $scoreLogWrap = $('#scoreLogWrap');
            $scoreLogWrap.find('table').empty();
            var str = '<tr><td>用户名</td><td>兑换时间</td></tr>';
            if(res.data){
                $.each(res.data,function (i, v) {
                    str += '<tr><td>'+v.username+'</td><td>'+v.create_time+'</td></tr>';
                });
            }
            $scoreLogWrap.find('table').append(str);
            layer.open({
                type: 1,
                area: ['500px', '500px'],
                shade:0.8,
                shadeClose: true,
                title: '兑换记录',
                content:$scoreLogWrap
            });
        })
    }

</script>
<!--/请在上方写此页面业务相关的脚本-->
选择类型，填入积分值、需生成的兑换码的数量、有效时长（小时数），由系统自动生成兑换码（如果选择的是前缀类型，则需填入前缀）

<div id="redeemAddWrap" style="display:none;">
    <article class="cl pd-20">
        <form method="post" class="form form-horizontal" novalidate="novalidate">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">类型：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select class="select valid" size="1" name="type">
					<option value="1">一次性</option>
					<option value="2">前缀</option>
				</select>
				</span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">前缀：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text valid"  value="" placeholder="前缀型需要填写" name="prefix">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">积分值：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="积分值"  name="score">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">数量：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="数量"  name="num">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">有效期：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="有效时间（小时数）"  name="hour">
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