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
        <input type="hidden" name="debitId" value="<?= $item->DebitId ?>"/>
        <input type="hidden" name="userId" value="<?= $item->user->UserId ?>"/>
        <input type="hidden" name="registrationId" value="<?= $item->user->registrationId ?>"/>

        <?php
        $this->load->view('admin/check_tab');
        ?>
        <div style="margin-top:20px" class="row cl">
            <label class="form-label col-xs-2 col-sm-2">Loan Status：</label>
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
            <!--<label class="form-label col-xs-2 col-sm-2">Loan Reference No.：</label>
            <div class="formControls col-xs-4 col-sm-4">
                <input type="text" class="input-text" value="<?/*= $item->DebitId */?>" placeholder="">
            </div>-->
            <label class="form-label col-xs-3 col-sm-3">Total Amount of Bill：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" value="<?= ($item->payBackMoney + $item->overdueMoney) ?>" placeholder="">
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
            <label class="form-label col-xs-3 col-sm-3">Total Amount of Paidback：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <input type="text" class="input-text" readonly value="<?= $item->alreadyReturnMoney ?>" placeholder="">
            </div>
        </div>

        <?php if ($item->payback): ?>
            <div class="row cl">
                <label class="form-label col-xs-2 col-sm-2">Proof of payback：</label>
                <div class="formControls col-xs-4 col-sm-4">
                    <div class="uploader-thum-container">
                        <img style="max-width: 200px;" src="<?=$item->paycert->certificateUrl?>" alt="" />

                    </div>
                </div>
                <label class="form-label col-xs-3 col-sm-3">Latest Paidback Amount：</label>
                <div class="formControls col-xs-3 col-sm-3">
                    <input type="number" style="width: 200px;" class="input-text" name="returnMoney" value="0" placeholder="">
                </div>
            </div>
        <?php endif; ?>

        <input type="hidden" name="url" id="url" value=""/>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>Status：</label>
            <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                <div class="radio-box">
                    <input name="status" type="radio" value="3" id="sex-1">
                    <label for="sex-1">Pass</label>
                </div>
                <div class="radio-box">
                    <input type="radio" id="sex-2" value="-2" name="status" checked>
                    <label for="sex-2">Unpass</label>
                </div>
            </div>
        </div>

        <div class="row cl" id="reason">
            <label class="form-label col-xs-2 col-sm-2">Unpass Reason：</label>
            <div class="formControls col-xs-3 col-sm-3">
                <span class="select-box inline">
                <select class="select check-item" style="width:200px;">
                    <option value="">Unpass Reason</option>
                    <option value="Repayment Information Incorrect">Repayment Information Incorrect</option>
                    <option value="Order not exisit">Order not exisit</option>
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
<script type="text/javascript" src="<?= base_url() ?>static/lib/webuploader/0.1.5/webuploader.min.js"></script>
<!--<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=QVYxRW2ZxRxLGHycLEcZUTtNfBK8rvwp"></script>-->
<script type='text/javascript'
        src='https://cn.bing.com/api/maps/mapcontrol?key=AsKibxR4UshSJ7Z6s4UYcgcJCkwFRh9l2cQtz3t3-StEcE9attEqAJeI5d38-8CX'></script>
<script src="http://gosspublic.alicdn.com/aliyun-oss-sdk-4.4.4.min.js"></script>


<script type="text/javascript">
    var categorySelect = "<?=site_url('welcome/category')?>";
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

        $('[name=status]').on('ifChecked', function (event) {
            var val = $(this).val();
            if (val != -2) {
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
                if (obj.status == -2 && !obj.description) {
                    layer.msg('备注必须填写');
                    return;
                }
                $.ajax({
                    url: "<?=site_url('admin/doRepayment')?>",
                    type: 'post',
                    dataType: 'json',
                    data: obj
                }).done(function (res) {
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

        var $wrap = $('.uploader-list-container'),

            // 图片容器
            $queue = $('<ul class="filelist"></ul>')
                .appendTo($wrap.find('.queueList')),

            // 状态栏，包括进度和控制按钮
            $statusBar = $wrap.find('.statusBar'),

            // 文件总体选择信息。
            $info = $statusBar.find('.info'),

            // 上传按钮
            $upload = $wrap.find('.uploadBtn'),

            // 没选择文件之前的内容。
            $placeHolder = $wrap.find('.placeholder'),

            $progress = $statusBar.find('.progress').hide(),

            // 添加的文件数量
            fileCount = 0,

            // 添加的文件总大小
            fileSize = 0,

            // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

            // 缩略图大小
            thumbnailWidth = 110 * ratio,
            thumbnailHeight = 110 * ratio,

            // 可能有pedding, ready, uploading, confirm, done.
            state = 'pedding',

            // 所有文件的进度信息，key为file id
            percentages = {},
            // 判断浏览器是否支持图片的base64
            isSupportBase64 = (function () {
                var data = new Image();
                var support = true;
                data.onload = data.onerror = function () {
                    if (this.width != 1 || this.height != 1) {
                        support = false;
                    }
                }
                data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                return support;
            })(),

            // 检测是否已经安装flash，检测flash的版本
            flashVersion = (function () {
                var version;

                try {
                    version = navigator.plugins['Shockwave Flash'];
                    version = version.description;
                } catch (ex) {
                    try {
                        version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                            .GetVariable('$version');
                    } catch (ex2) {
                        version = '0.0';
                    }
                }
                version = version.match(/\d+/g);
                return parseFloat(version[0] + '.' + version[1], 10);
            })(),

            supportTransition = (function () {
                var s = document.createElement('p').style,
                    r = 'transition' in s ||
                        'WebkitTransition' in s ||
                        'MozTransition' in s ||
                        'msTransition' in s ||
                        'OTransition' in s;
                s = null;
                return r;
            })(),


            // file upload
            $list = $("#fileList"),
            $btn = $("#btn-star"),
            state = "pending",
            uploader;

        var uploader = WebUploader.create({
            auto: true,
            swf: 'lib/webuploader/0.1.5/Uploader.swf',

            // 文件接收服务端。
            server: "<?=site_url('upload/upload')?>",

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker',

            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            resize: false,
            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
        a = uploader;
        uploader.on('fileQueued', function (file) {
            $list.empty();
            var $li = $(
                '<div id="' + file.id + '" class="item">' +
                '<div class="pic-box"><img></div>' +
                '<div class="info">' + file.name + '</div>' +
                '<p class="state">等待上传...</p>' +
                '</div>'
                ),
                $img = $li.find('img');
            $list.append($li);

            // 创建缩略图
            // 如果为非图片文件，可以不用调用此方法。
            // thumbnailWidth x thumbnailHeight 为 100 x 100
            uploader.makeThumb(file, function (error, src) {
                if (error) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $img.attr('src', src);
            }, thumbnailWidth, thumbnailHeight);
        });
        // 文件上传过程中创建进度条实时显示。
        uploader.on('uploadProgress', function (file, percentage) {
            var $li = $('#' + file.id),
                $percent = $li.find('.progress-box .sr-only');

            // 避免重复创建
            if (!$percent.length) {
                $percent = $('<div class="progress-box"><span class="progress-bar radius"><span class="sr-only" style="width:0%"></span></span></div>').appendTo($li).find('.sr-only');
            }
            $li.find(".state").text("上传中");
            $percent.css('width', percentage * 100 + '%');
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on('uploadSuccess', function (file, res) {
            $('#' + file.id).addClass('upload-state-success').find(".state").text("已上传");
            var url = res.data.url;
            $('#url').val(url);
        });

        // 文件上传失败，显示上传出错。
        uploader.on('uploadError', function (file) {
            $('#' + file.id).addClass('upload-state-error').find(".state").text("上传出错");
        });

        // 完成上传完了，成功或者失败，先删除进度条。
        uploader.on('uploadComplete', function (file) {
            $('#' + file.id).find('.progress-box').fadeOut();
        });
        uploader.on('all', function (type) {
            if (type === 'startUpload') {
                state = 'uploading';
            } else if (type === 'stopUpload') {
                state = 'paused';
            } else if (type === 'uploadFinished') {
                state = 'done';
            }

            if (state === 'uploading') {
                $btn.text('暂停上传');
            } else {
                $btn.text('开始上传');
            }
        });

        $btn.on('click', function () {
            if (state === 'uploading') {
                uploader.stop();
            } else {
                uploader.upload();
            }
        });


        // file uplaod

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