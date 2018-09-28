<?php
$this->load->view('admin/header');
?>
<body>
<article class="cl pd-20">
    <form action="<?= site_url('auth/menuAdd') ?>" method="post" class="form form-horizontal" id="form-admin-add">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>标题：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="" id="adminName" name="title">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>url：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="" id="realname" name="url">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>是否显示：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box" style="width:150px;">
                    <select class="select" name="display" size="1">
                        <option value="0">不显示</option>
                        <option value="1">显示</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>上级菜单：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box" style="width:150px;">
                    <select class="select" name="pid" size="1">
                        <option value="0"></option>
                        <?php if($menus):?>
                        <?php foreach ($menus as $k=>$v):?>
                                <?php if(!$v['pid']):?>
                                <option value="<?=$v['id']?>"><?=$v['pid'] != 0 ? ' -- ' : ''?><?=$v['title']?></option>
                                <?php /*foreach ($menus as $kk=>$vv):*/?><!--
                                    <?php /*if($vv['pid'] == $v['id']):*/?>
                                    <option value="<?/*=$vv['id']*/?>"><?/*=$vv['pid'] != 0 ? ' -- ' : ''*/?><?/*=$vv['title']*/?></option>
                                    <?php /*endif;*/?>
                                --><?php /*endforeach;*/?>
                                <?php endif;?>
                        <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>类型：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box" style="width:150px;">
                    <select class="select" name="type" size="1">
                        <option value="1">菜单</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>排序：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" placeholder="" name="order_num" id="email">
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
            </div>
        </div>
    </form>
</article>

<?php
$this->load->view('admin/footer');
?>
<!--/_footer /作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="<?= base_url() ?>static/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/jquery.validation/1.14.0/messages_zh.js"></script>
<script type="text/javascript">
    $(function () {
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });

        $("#form-admin-add").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 4,
                    maxlength: 16
                },
                password: {
                    required: true,
                }
            },
            onkeyup: false,
            focusCleanup: true,
            success: "valid",
            submitHandler: function (form) {
                $(form).ajaxSubmit(function (res) {
                    res = JSON.parse(res);
                    if (res && res.status == 200) {
                        layer.msg(res.msg, {type: 1, time: 500}, function () {
                            window.parent.location.reload();
                        })
                    } else {
                        layer.msg(res.msg ? res.msg : '操作失败');
                    }
                });
            }
        });
    });
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>