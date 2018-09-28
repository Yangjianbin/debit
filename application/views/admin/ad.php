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
<!--                <input type="text" name="" id="q" placeholder="用户编号" style="width:250px" class="input-text">-->
<!--                <button id="search" class="btn btn-success" ><i class="Hui-iconfont"></i>查询</button>-->
                <!--			<a href="javascript:;" id="addUser" class="btn btn-primary radius"><i class="Hui-iconfont"></i> 添加用户</a>-->

			</span>
            </div>

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th>编号</th>
                        <th>图片地址</th>
                        <th>链接地址</th>
                        <th>描述文字</th>
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
//            aoData.id = $('#q').val();
        },
        "aoColumnDefs": [
        ],
        "createdRow": function( row, data, dataIndex ) {
//            $(row).children('td').eq(0).attr('style', 'text-align: center;')
            $(row).children('td').attr('style', 'text-align: center;')
        },
        ajax:"<?=site_url('base/ad')?>",
        columns : [
            {data : "id"},
            {data : "image"},
            {data : "link"},
            {data : "desc"},
            {data:''}
        ],
        "aoColumnDefs":[
            {
                "targets": -1,
                "data":'id',
                "mRender": function(data, type, full){
                    var str = '';
//                    str += '<a style="text-decoration:none" class="ml-5" onclick="downloadLog('+full.id+')" href="javascript:;" title="下载记录"><i class="Hui-iconfont">&#xe640;</i></a>';
//                    str += '<a style="text-decoration:none" class="ml-5" onclick="scoreLog('+full.id+')" href="javascript:;" title="积分记录"><i class="Hui-iconfont">&#xe709;</i></a>';
                    str += '<a data-desc="'+full.desc+'" data-link="'+full.link+'" data-image="'+full.image+'" style="text-decoration:none" class="ml-5" onclick="update('+full.id+',this)" href="javascript:;" title="修改"><i class="Hui-iconfont">&#xe6df;</i></a>';
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


    function update(id,e) {
        var $scoreAddWrap = $('#scoreAddWrap');
        var image = $(e).data('image'),link = $(e).data('link'),desc = $(e).data('desc');
        $scoreAddWrap.find('[name=image]').val(image);
        $scoreAddWrap.find('[name=link]').val(link);
        $scoreAddWrap.find('[name=desc]').val(desc);

        layer.open({
            type: 1,
            area: ['580px', '370px'],
            shade:0.8,
            title: '编辑',
            btnAlign: 'c',
            btn: ['点击保存'],
            content:$scoreAddWrap,
            yes:function(){
                var data = getFormJson($scoreAddWrap.find('form'));
                data.id = id;
                $.ajax({
                    url:"<?=site_url('base/adUpdate')?>",
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



</script>
<!--/请在上方写此页面业务相关的脚本-->

<!--设置积分-->
<div id="scoreAddWrap" style="display:none;">
    <article class="cl pd-20">
        <form method="post" class="form form-horizontal" novalidate="novalidate">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">图片：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder=""  name="image">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">链接：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder=""  name="link">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">描述：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea class="textarea" name="desc" ></textarea>
                </div>
            </div>
        </form>
    </article>
</div>

</body>
</html>