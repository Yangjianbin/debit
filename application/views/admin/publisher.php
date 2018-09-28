<?php
$this->load->view('admin/header');
?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css"/>
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
        <article class="cl pd-20">
            <div class="cl pd-5 bg-1 bk-gray mt-20">
			    <span class="l">
<!--			        <a href="javascript:;" onclick="categoryAdd()" class="btn btn-primary radius"><i class="Hui-iconfont"></i>添加分类</a>-->
			    </span>
            </div>
            <table class="table">
                <tr>
                    <td width="200" class="va-t">
                        <ul id="tree" class="ztree"></ul>
                    </td>
                    <td class="va-t">
                    </td>
                </tr>
            </table>

        </article>
    </div>
</section>

<!--_footer 作为公共模版分离出去-->
<?php
$this->load->view('admin/footer');
?>
<!--/_footer /作为公共模版分离出去-->


<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="<?= base_url() ?>static/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script>
<script>
    var zTree;
    var setting = {
        view:{
            selectedMulti:false
        },
        edit: {
            enable: true,
            editNameSelectAll:true,
            removeTitle:'删除',
//            renameTitle:'重命名',
            showRemoveBtn: true,
            showRenameBtn: false
        },
        data: {
            keep:{
                parent:true,
                leaf:true
            },
            simpleData: {
                enable: true,
                pIdKey: "pid",
            }
        },
        callback:{
            beforeRemove:beforeRemove,//点击删除时触发，用来提示用户是否确定删除
            beforeEditName: beforeEditName,//点击编辑时触发，用来判断该节点是否能编辑
            beforeRename:beforeRename,//编辑结束时触发，用来验证输入的数据是否符合要求
            onRemove:onRemove,//删除节点后触发，用户后台操作
            onRename:onRename,//编辑后触发，用于操作后台
            beforeDrag:beforeDrag,//用户禁止拖动节点
            onClick:clickNode//点击节点触发的事件
        }
    };

    $(document).ready(function(){
        $.getJSON(categorySelect,function (res) {
            var zNodes = res.data;
            zTree = $.fn.zTree.init($("#tree"), setting, zNodes);
        });
    });

    function beforeRemove(e,treeId,treeNode){
        return confirm("你确定要删除吗？");
    }
    function onRemove(e,treeId,treeNode){
        var id = treeNode.id;
        $.ajax({
            url:"<?=site_url('base/publisherDel')?>",
            type:'post',
            dataType:'json',
            data:{id:id}
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
    function beforeEditName(treeId,treeNode){
        if(treeNode.isParent){
            alert("不准编辑非叶子节点！");
            return false;
        }
        return true;
    }
    function beforeRename(treeId,treeNode,newName,isCancel){
        if(newName.length < 3){
            alert("名称不能少于3个字符！");
            return false;
        }
        return true;
    }
    function onRename(e,treeId,treeNode,isCancel){
        alert("修改节点的id为："+treeNode.id+"\n修改后的名称为："+treeNode.name);
    }
    function clickNode(e,treeId,treeNode){
        if(treeNode.id == 11){
            location.href=treeNode.url;
        }
    }
    function beforeDrag(treeId,treeNodes){
        return false;
    }
</script>
<script type="text/javascript">
    var deleteUrl = "<?=site_url('Admin/publisherDel')?>";
    var categorySelect = "<?=site_url('welcome/publisher')?>";

</script>
<!--/请在上方写此页面业务相关的脚本-->

</body>
</html>