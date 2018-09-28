<?php
$this->load->view('admin/header');
?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css"/>
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
        width: 100% ;
        height: 280px ;
    }
</style>
<body onload='loadMapScenario();'>
<article class="page-container">
    <!--        tab-->

    <!--        tab-->
    <form class="form form-horizontal" id="form">
        <input type="hidden" name="debitId" value="<?= $item->DebitId ?>"/>
        <input type="hidden" name="userId" value="<?= $item->user->UserId ?>"/>
        <input type="hidden" name="registrationId" value="<?= $item->user->registrationId ?>"/>

        <?php
        $this->load->view('admin/check_tab');
        ?>
        <div style="margin-top:20px" class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Loan Info：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $this->config->item('statusEnum')[$item->Status] ?>"
                       placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Audit：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $this->session->admin->username ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Loan Reference No.：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->DebitId ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Amount：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->DebitMoney ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Period：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->DebitPeroid ?>" placeholder="">
            </div>
            <label class="form-label col-xs-2 col-sm-2">Phone Number：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?= $item->user->Phone ?>">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">UserId：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?=$item->UserId?>" placeholder="">
            </div>
        </div>

        <!--<div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">贷款审核结果：</label>
            <div class="formControls col-xs-8 col-sm-3 skin-minimal">
                <div class="check-box">
                    <input id="realname_flag" name="checkStatus" value="-1" type="checkbox">
                    <label for="realname_flag">审核不通过</label>
                </div>
            </div>
        </div>-->

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>Loan Result：</label>
            <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                <div class="radio-box">
                    <input name="checkStatus" type="radio" value="5" id="sex-1">
                    <label for="sex-1">Pass</label>
                </div>
                <div class="radio-box">
                    <input type="radio" id="sex-2" value="-1" name="checkStatus" checked>
                    <label for="sex-2">Unpass</label>
                </div>
            </div>
        </div>

        <div class="row cl" id="reason">
            <label class="form-label col-xs-2 col-sm-2">Unpass Reason：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <span class="select-box inline">
                <select class="select check-item" style="width:200px;">
                    <option value="">Unpass Reason</option>
                    <option value="Info pengguna salah">Info pengguna salah</option>
                    <option value="nfo Bank salah">Info Bank salah</option>
                    <option value="Info pekerjaan salah">Info pekerjaan salah</option>
                    <option value="Info ID salah">Info ID salah</option>
                    <option value="Info Kontak Salah">Info Kontak Salah</option>
                </select>
                </span>
            </div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Note：If upass,must be filled in reason for not pass：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea name="description" class="textarea" rows="3"></textarea>
            </div>
            <label class="form-label col-xs-2 col-sm-2">Audit Describe：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <textarea name="describe" class="textarea" rows="3"><?= $item->describe ?></textarea>
            </div>
        </div>

        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button id="submit" type="button" class="btn btn-primary radius"><i class="Hui-iconfont">
                        &#xe632;</i> Submit
                </button>
            </div>
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
<!--<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=QVYxRW2ZxRxLGHycLEcZUTtNfBK8rvwp"></script>-->
<script type='text/javascript'
        src='https://cn.bing.com/api/maps/mapcontrol?key=AsKibxR4UshSJ7Z6s4UYcgcJCkwFRh9l2cQtz3t3-StEcE9attEqAJeI5d38-8CX'></script>

<script type="text/javascript">
    var categorySelect = "<?=site_url('welcome/category')?>";
    $(function () {
        $("#tab-system").Huitab({
            index: 0
        });
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });


        $('[name=checkStatus]').on('ifChecked', function (event) {
            var val = $(this).val();
            if (val != -1) {
                $('#reason').hide();
            } else {
                $('#reason').show();
            }
        });

        $('#reason select').change(function () {
            var val = $(this).val();
            if (val) {
                $('[name=description]').val(val)
            }
        })

        $('#submit').click(function () {
            layer.confirm('Confirm of Approval？', {
                btn: ['YES', 'NO'],
                title: '确认',
                icon: 3
            }, function () {
                var obj = getFormJson('#form');
                console.log(obj)
                if (obj.checkStatus == -1 && !obj.description) {
                    layer.msg('备注必须填写');
                    return;
                }
                $.ajax({
                    url: "<?=site_url('admin/doPreloan')?>",
                    type: 'post',
                    dataType: 'json',
                    data: obj
                }).done(function (res) {
                    console.log(res)
                    if (!res || res.status == 500) {
                        layer.msg(res.msg);
                        return;
                    }
                    layer.msg('操作成功', {time: 400}, function () {
                        window.parent.location.reload();
                    })
                }).fail(function () {
                    layer.msg('操作失败，稍后再试');
                })
            });
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

    /*var map = new BMap.Map("map_canvas");          // 创建地图实例
    var point = new BMap.Point("<?=$item->user->locationY?>" , "<?=$item->user->locationX?>");  // 创建点坐标
    map.centerAndZoom(point, 15);// 初始化地图，设置中心点坐标和地图级别
    var marker = new BMap.Marker(point);
    map.addOverlay(marker);

    $('.map').click(function () {
        setTimeout(function () {
            map.setCenter(point)
        },100);
    })*/

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