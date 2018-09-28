<!-- 页尾begin -->
<div class="public_footermes_module">
    <div class="footer_copyright"><span>Copyright (C) 医典114 2016-2017, All Rights Reserved</span></div>
</div>
<!-- 页尾end -->
<!-- 页尾end -->
<div class="returntop" style="display: none;">
    <div style="display:block;" class="public_totop_module" dd_name="右侧返回顶部"></div>
</div>
</body>
<script src="<?= base_url() ?>/static/lib/jquery/1.9.1/jquery.min.js"></script>
<script src="<?= base_url() ?>/static/home/underscore.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/lib/layer/2.4/layer.js"></script>
<script>
    
    function logout() {
        $.ajax({
            url:"<?=site_url('login/logout')?>",
            type:'post',
            dataType:'json'
        }).done(function(res){
            if(!res || res.status == 500){
                layer.msg(res.msg);
                return ;
            }
            layer.msg('退出成功',{time:400},function(){
                location.reload();
            })
        }).fail(function(){
            layer.msg('请求失败，稍后再试');
        })
    }
    $(function () {
        $('.returntop').click(function () {
            $("html,body").animate({scrollTop:0}, 300);
        });
    })
</script>
<script src="https://s13.cnzz.com/z_stat.php?id=1264336854&web_id=1264336854" language="JavaScript"></script>