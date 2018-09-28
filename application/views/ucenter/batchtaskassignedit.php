<?php
$this->load->view('admin/header');
?>
<body>
<article class="cl pd-20">
    <form action="<?= site_url('ucenter/batchtaskassignedit') ?>" method="post" class="form form-horizontal" id="form-admin-add">
        <input type="hidden" name="ids" value="<?=$ids?>">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>用户名：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box" style="width:150px;">
                    <select class="select" name="adminId" size="1">
                        <?php foreach ($users as $k=>$v):?>
                            <option value="<?= $v->adminId ?>"><?=$v->username?></option>
                        <?php endforeach;?>
                    </select>
                </span>
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