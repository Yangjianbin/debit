<?php
$this->load->view('admin/header');
?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css"/>
<style>
    #menuContent{z-index: 9999;}
    ul.ztree {z-index:9999;margin-top: 10px;border: 1px solid #617775;background: #f0f6e4;width:220px;height:360px;overflow-y:scroll;overflow-x:auto;}
    .autocomplete-suggestions{
        background-color: #ddd;
    }
</style>
<body>
<article class="page-container">
    <form class="form form-horizontal" id="form-article-add">
        <input type="hidden" name="id" value="<?=$item->id?>">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">书名：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" class="input-text" value="<?=$item->name?>" placeholder=""  name="name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">出版社：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" class="input-text" value="<?=$item->publisher?>" placeholder="可模糊查询"  name="publisher">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">出版时间：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" name="publish_time" value="<?=$item->publish_time?>" style="width: 200px;" onfocus="WdatePicker()"
                       class="input-text Wdate"/>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">ISBN：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" class="input-text" value="<?=$item->isbn?>" placeholder=""  name="isbn">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">分类：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input id="citySel" value="<?=$item->categoryStr?>" type="text" class="input-text" readonly onclick="showMenu();"  style="width: 90%">
                <input type="hidden" name="category" value="<?=$item->category?>">
                <a id="menuBtn" href="#" onclick="showMenu(); return false;">选择分类</a>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">图片封面：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <div id="container">
                    <a href="javascript:;" id="pickfiles" class="btn btn-default radius"><i class="Hui-iconfont"></i>上传图片</a>
                    <img <?=$item->cover ? 'src="' . $item->cover.'"' :''?> alt="" id="pickfiles_image" style="max-width: 100px;max-height:100px;">
                </div>
            </div>
            <input type="hidden" name="cover" value="<?=$item->cover?>"/>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">上传电子书：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <div id="container2">
                    <a href="javascript:;" id="pickfiles2" class="btn btn-default radius"><i class="Hui-iconfont"></i>点击上传</a>
                    <input type="text" value="<?=$item->save_path?>" class="input-text" placeholder="" style="width: 80%;" name="save_path">
                </div>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">下载地址：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" class="input-text" value="<?=(isset($item->download_links[0]) ? $item->download_links[0] : '')?>" placeholder="" style="width: 100%;margin-bottom: 5px;"
                       name="download_links[]">
                <input type="text" class="input-text" value="<?=(isset($item->download_links[1]) ? $item->download_links[1] : '')?>" placeholder="" style="width: 100%;margin-bottom: 5px;"
                       name="download_links[]">
                <input type="text" class="input-text" value="<?=(isset($item->download_links[2]) ? $item->download_links[2] : '')?>" placeholder="" style="width: 100%;margin-bottom: 5px;"
                       name="download_links[]">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">纸质书购买地址：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" style="width:100%;margin-bottom: 5px;" class="input-text" value="<?=(isset($item->buy_links[0]) ? $item->buy_links[0] : '')?>" placeholder="" name="buy_links[]">
                <input type="text" style="width:100%;margin-bottom: 5px;" class="input-text" value="<?=(isset($item->buy_links[1]) ? $item->buy_links[1] : '')?>" placeholder="" name="buy_links[]">
                <input type="text" style="width:100%;margin-bottom: 5px;" class="input-text" value="<?=(isset($item->buy_links[2]) ? $item->buy_links[2] : '')?>" placeholder="" name="buy_links[]">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">已下架：</label>
            <div class="formControls col-xs-2 col-sm-2 skin-minimal">
                <div class="check-box">
                    <input name="status" type="checkbox" <?=$item->status ? 'checked' : ''?> value="1" id="checkbox-pinglun">
                    <label for="checkbox-pinglun">&nbsp;</label>
                </div>
            </div>
            <label class="form-label col-xs-5 col-sm-3">下载链接已过期：</label>
            <div class="formControls col-xs-2 col-sm-2 skin-minimal">
                <div class="check-box">
                    <input name="invalid" type="checkbox" <?=$item->invalid ? 'checked' : ''?> value="1" id="checkbox-pinglun">
                    <label for="checkbox-pinglun">&nbsp;</label>
                </div>
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button id="bookSave" type="button" class="btn btn-primary radius"><i class="Hui-iconfont">
                        &#xe632;</i> 点击保存
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
<script type="text/javascript" src="<?= base_url() ?>static/lib/jquery.autocomplete/jquery.autocomplete.js"></script>

<script src="https://cdn.staticfile.org/plupload/2.1.2/moxie.min.js"></script>
<script src="https://cdn.staticfile.org/plupload/2.1.2/plupload.full.min.js"></script>
<script src="https://cdn.staticfile.org/plupload/2.1.2/i18n/zh_CN.js"></script>
<script src="http://haitao3.isudoo.com/Public/static/qiniu.min.js"></script>

<script type="text/javascript" src="<?= base_url() ?>static/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script>


<script type="text/javascript">
    var categorySelect = "<?=site_url('welcome/category')?>";
    var category = "<?=$item->category?>";
    $(function () {
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });

        $('[name=publisher]').autocomplete({
            serviceUrl: "<?=site_url('welcome/publisher')?>",
            transformResult: function(response) {
                response = JSON.parse(response);
                var map = [];
                $.each(response.data,function (i, dataItem) {
                    map.push({ value: dataItem.name, data: dataItem.name })
                });
                return {
                    suggestions: map
                };
            },
            onSelect: function (suggestion) {
//                alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
            }
        });

        $('#bookSave').click(function () {
            var obj = getFormJson('.form');
            $.ajax({
                url: "<?=site_url('admin/bookUpdate')?>",
                type: 'post',
                dataType: 'json',
                data: obj
            }).done(function (res) {
                if (!res || res.status == 500) {
                    layer.msg(res.msg);
                    return;
                }
                layer.msg('添加成功', {time: 400}, function () {
                    window.parent.location.reload();
                })
            }).fail(function () {
                layer.msg('请求失败，稍后再试');
            })
        });

        var uploader = Qiniu.uploader({
            runtimes: 'html5',      // 上传模式，依次退化
            browse_button: 'pickfiles',         // 上传选择的点选按钮，必需
            uptoken_url: "<?=site_url('welcome/uptoken')?>",         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
            get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
            unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
            domain: 'http://static.isudoo.com',     // bucket域名，下载资源时用到，必需
            container: 'container',             // 上传区域DOM ID，默认是browser_button的父元素
            max_file_size: '200mb',             // 最大文件体积限制
            flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
            max_retries: 3,                     // 上传失败最大重试次数
            dragdrop: true,                     // 开启可拖曳上传
            drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
            chunk_size: '4mb',                  // 分块上传时，每块的体积
            auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
            x_vars: {},
            filters: {
                mime_types : [ //只允许上传图片
                    { title : "Image files", extensions : "jpg,jpeg,gif,png" },
                ],
                prevent_duplicates : false //不允许选取重复文件
            },
            init: {
                'BeforeUpload': function (up, file) {
                },
                'FileUploaded': function (up, file, info) {
                    var domain = up.getOption('domain');
                    var res = JSON.parse(info);
                    var sourceLink = domain + "/" + res.key;
                    $('#pickfiles_image').attr('src', sourceLink);
                    $('[name=cover]').val(sourceLink);
                }
            }
        });
        var uploader2 = Qiniu.uploader({
            runtimes: 'html5',      // 上传模式，依次退化
            browse_button: 'pickfiles2',         // 上传选择的点选按钮，必需
            uptoken_url: "<?=site_url('welcome/uptoken')?>",         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
            get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
            unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
            domain: 'http://static.isudoo.com',     // bucket域名，下载资源时用到，必需
            container: 'container',             // 上传区域DOM ID，默认是browser_button的父元素
            max_file_size: '200mb',             // 最大文件体积限制
            flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
            max_retries: 3,                     // 上传失败最大重试次数
            dragdrop: true,                     // 开启可拖曳上传
            drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
            chunk_size: '4mb',                  // 分块上传时，每块的体积
            auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
            x_vars: {},
            init: {
                'BeforeUpload': function (up, file) {
                },
                'FileUploaded': function (up, file, info) {
                    var domain = up.getOption('domain');
                    var res = JSON.parse(info);
                    var sourceLink = domain + "/" + res.key;
                    $('[name=save_path]').val(sourceLink);
                }
            }
        });
        var setting = {
            check: {
                enable: true,
                chkboxType: {"Y":"", "N":""}
            },
            view: {
                dblClickExpand: false
            },
            data: {
                simpleData: {
                    enable: true,
                    pIdKey: "pid"
                }
            },
            callback: {
                beforeClick: beforeClick,
                onCheck: onCheck
            }
        };
        var zNodes = [];
//        [
//            {id:1, pId:0, name:"北京"},
//            {id:6, pId:0, name:"重庆"},
//            {id:4, pId:0, name:"河北省", open:true, nocheck:true},
//            {id:41, pId:4, name:"石家庄"},
//            {id:42, pId:4, name:"保定"},
//            {id:43, pId:4, name:"邯郸"},
//        ];
        $.getJSON(categorySelect,function (res) {
            var categoryArr = category.split(',');//4,8,9
            $.each(res.data,function (i, v) {
                if($.inArray(v.id, categoryArr) >= 0){
                    v.checked = true;
                }
//                v.checked = true;
//                if(v.open == true){
//                    v.nocheck = true;
//                }
                zNodes.push(v);
            })
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
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
        for (var i=0, l=nodes.length; i<l; i++) {
            v += nodes[i].name + ",";
            categorys.push(nodes[i].id);
        }
        if (v.length > 0 ) v = v.substring(0, v.length-1);
        var cityObj = $("#citySel");
        cityObj.attr("value", v);
        $('[name=category]').val(categorys.join(','));
    }
    function showMenu() {
        var cityObj = $("#citySel");
        var cityOffset = $("#citySel").offset();
        $("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");
        $("body").bind("mousedown", onBodyDown);
    }
    function hideMenu() {
        $("#menuContent").fadeOut("fast");
        $("body").unbind("mousedown", onBodyDown);
    }
    function onBodyDown(event) {
        if (!(event.target.id == "menuBtn" || event.target.id == "citySel" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
            hideMenu();
        }
    }
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>