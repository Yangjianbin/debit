<?php
$this->load->view('admin/header');
?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css"/>
<link href="<?= base_url() ?>static/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css"/>
<style>
    #menuContent {
        z-index: 9999;
    }

    ul.ztree {
        overflow: scroll;
        z-index: 9999;
        margin-top: 10px;
        border: 1px solid #617775;
        background: #f0f6e4;
        width: 220px;
        height: 360px;
        overflow-y: scroll;
        overflow-x: auto;
    }

    .autocomplete-suggestions {
        background-color: #ddd;
        border: 1px solid #999;
        overflow: auto;
    }

    .map_wrap {
        width: 100%;
        height: 280px;
    }
</style>
<body onload='loadMapScenario();'>
<article class="page-container">
    <!--        tab-->

    <!--        tab-->
    <form class="form form-horizontal" id="form">

        <div style="margin-top:20px" class="row cl">
            <table class="table table-border table-bordered table-striped">
                <thead>
                <tr>
                    <th>DebitId</th>
<!--                    <td>申请时间</td>-->
                    <th>Release Time</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Remark</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $k=>$v):?>
                        <tr>
                            <td><?=$v->DebitId?></td>
                            <td><?=$v->auditTime?></td>
                            <td><?=$v->Description?></td>
                            <td><?=$v->Status == 1 ? 'PASS' : 'UNPASS'?></td>
                            <th><?=$v->Remark?></th>
                        </tr>
                    <?php endforeach;?>
                </tbody>

            </table>
        </div>

    </form>
</article>

<div id="menuContent" class="menuContent" style="display:none; position: absolute;">
    <ul id="treeDemo" class="ztree" style="margin-top:0; width:180px; height: 300px;"></ul>
</div>
<!--_footer 作为公共模版分离出去-->
<?php
$this->load->view('admin/footer');
?>
<script type="text/javascript" src="<?= base_url() ?>static/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/jquery.validation/1.14.0/messages_zh.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/webuploader/0.1.5/webuploader.min.js"></script>
<!--<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=QVYxRW2ZxRxLGHycLEcZUTtNfBK8rvwp"></script>-->
<script type='text/javascript'
        src='https://cn.bing.com/api/maps/mapcontrol?key=AsKibxR4UshSJ7Z6s4UYcgcJCkwFRh9l2cQtz3t3-StEcE9attEqAJeI5d38-8CX'></script>

<script src="http://gosspublic.alicdn.com/aliyun-oss-sdk-4.4.4.min.js"></script>


<script type="text/javascript">
    var a = null;
    $(function () {
        $("#tab-system").Huitab({
            index: 0
        });
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });

    });

    function beforeClick(treeId, treeNode) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        zTree.checkNode(treeNode, !treeNode.checked, null, true);
        return false;
    }

    function onCheck(e, treeId, treeNode) {
        var categorys = [];
        var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
            nodes = zTree.getCheckedNodes(true),
            v = "";
        for (var i = 0, l = nodes.length; i < l; i++) {
            v += nodes[i].name + ",";
            categorys.push(nodes[i].id);
        }
        if (v.length > 0) v = v.substring(0, v.length - 1);
        var cityObj = $("#citySel");
        cityObj.attr("value", v);
        $('[name=category]').val(categorys.join(','));
    }

    function showMenu() {
        var cityObj = $("#citySel");
        var cityOffset = $("#citySel").offset();
        $("#menuContent").css({
            left: cityOffset.left + "px",
            top: cityOffset.top + cityObj.outerHeight() + "px"
        }).slideDown("fast");
        $("body").bind("mousedown", onBodyDown);
    }

    function hideMenu() {
        $("#menuContent").fadeOut("fast");
        $("body").unbind("mousedown", onBodyDown);
    }

    function onBodyDown(event) {
        if (!(event.target.id == "menuBtn" || event.target.id == "citySel" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length > 0)) {
            hideMenu();
        }
    }

    var map;

    function loadMapScenario() {
        map = new Microsoft.Maps.Map(document.getElementById('map_canvas'), {
            credentials: 'AsKibxR4UshSJ7Z6s4UYcgcJCkwFRh9l2cQtz3t3-StEcE9attEqAJeI5d38-8CX',
            center: new Microsoft.Maps.Location("<?=$item->user->locationX?>", "<?=$item->user->locationY?>"),
            mapTypeId: Microsoft.Maps.MapTypeId.birdseye,
            zoom: 16
        });
        map.entities.clear();
        var pushpin = new Microsoft.Maps.Pushpin(map.getCenter(), null);
        map.entities.push(pushpin);
    }
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>