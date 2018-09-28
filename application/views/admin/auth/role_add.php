<?php
$this->load->view('admin/header');
?>
<body>
<article class="cl pd-20">
    <form action="<?= site_url('auth/roleAdd') ?>" method="post" class="form form-horizontal" id="form-admin-add">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>角色名：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="" id="adminName" name="rolename">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">功能菜单：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <dl class="permission-list">
                    <dt>
                        <label>
                            选择菜单</label>
                    </dt>
                    <dd>
                        <?php if ($menus): ?>
                            <?php foreach ($menus as $k => $v): ?>
                                <?php if ($v['pid'] == 0): ?>
                                    <dl class="cl permission-list2">
                                        <dt>
                                            <label class="">
                                                <input type="checkbox" value="<?= $v['id'] ?>" name="menu[]" />
                                                <?= $v['title'] ?></label>
                                        </dt>
                                        <dd>
                                            <?php foreach ($menus as $kk => $vv): ?>
                                                <?php if ($vv['pid'] == $v['id']): ?>
                                                    <label class="">
                                                        <input type="checkbox" value="<?= $vv['id'] ?>"
                                                               name="menu[]" >
                                                        <?= $vv['title'] ?></label>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </dd>
                                    </dl>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </dd>
                </dl>
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

        $(".permission-list dt input:checkbox").click(function () {
            $(this).closest("dl").find("dd input:checkbox").prop("checked", $(this).prop("checked"));
        });
        $(".permission-list2 dd input:checkbox").click(function () {
            var l = $(this).parent().parent().find("input:checked").length;
            var l2 = $(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
            if ($(this).prop("checked")) {
                $(this).closest("dl").find("dt input:checkbox").prop("checked", true);
                $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked", true);
            }
            else {
                if (l == 0) {
                    $(this).closest("dl").find("dt input:checkbox").prop("checked", false);
                }
                if (l2 == 0) {
                    $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked", false);
                }
            }
        });

        $("#form-admin-add").validate({
            rules: {
                rolename: {
                    required: true,
                    minlength: 4,
                    maxlength: 16
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