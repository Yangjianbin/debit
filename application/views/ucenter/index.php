<?php
$this->load->view('common/header');
?>
<link href="/static/home/reading_center_page.css" rel="stylesheet" type="text/css">
<!-- 页头 end -->
<div class="center">
    <div class="navigation_module" dd_name="面包屑路径"></div>
    <div class="main classification_list">
        <div class="left" id="nav_left" dd_name="左侧导航">
            <div class="classification_left_nav">
                <div class="first_level publication publisher selected original_blank">
                    <h3>个人信息<i class="icon"> </i></h3>
                    <ul class="second_level">
                        <li></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="right" dd_name="书籍分类列表">
            <div class="personal_info">
                <div class="left_head">
                    <img src="http://img7x9.ddimg.cn/imghead/19/35/3647603521999-1_e.png?1498442951644" alt="">
                </div>
                <div class="right_info">
                    <h3 id="nickname_all"><?= $this->session->user->nickname ?></h3>
                    <div class="account">
                        账户余额：<span class="gold_bell"><i class="gold_icon"> </i>积分 <span
                                    id="goldNum"><?= $this->session->user->score ?></span> </span>
                        <span class="package">
                            <a href="javascript:;" onclick="redeem()" dd_name="兑换积分"><i class="package_icon"> </i><em>兑换积分</em></a>
                        </span>
                    </div>
                    <div class="person_detail">
                        <div class="publish_title">
                            <h2>积分记录</h2>
                        </div>
                        <div class="left_half" style="width:100%">
                            <?php if ($data): ?>
                                <?php foreach ($data as $k => $v): ?>
                                    <div class="info_detail">
                                        操作：<span id="myLevel"><?= $v['remark'] ?></span> &nbsp;&nbsp;&nbsp;
                                        积分变动：<span id="myScore"><?= $v['score'] ?></span>分&nbsp;&nbsp;&nbsp;
                                        操作时间：<span id="myScore"><?= $v['create_time'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('common/footer'); ?>
<script type="text/javascript" src="<?= base_url() ?>static/lib/layer/3/layer.js"></script>
<script>

    $(function () {
        $(".classification_left_nav .publisher h3").on("click", function () {
            var b = $(this).parent();
            b.addClass("selected").siblings().removeClass("selected");
            b.children("ul").slideToggle().end().siblings().children("ul").slideUp();
        });
        $(".original_blank .second_level li").on("click", function () {
            $(".classification_left_nav li").removeClass("current");
            $(this).addClass("current");
        });
        $('.index_subnav_module li').click(function (e) {
            if (!$(this).is('.on')) {
                $(this).siblings('li').removeClass('on').end().addClass('on');
                var type = $(this).data('type');
                var move = function (b) {
                    var c = $("li[data-type=" + b + "]");
                    c.addClass("on").siblings("li").removeClass("on");
                    var d = c.parent().find("li").eq(0).outerWidth(),
                        e = c.offset().left - c.parent().offset().left + (c.outerWidth() - d) / 2;
                    $(".index_subnav_module .bar").width(d).animate({left: e + "px"}, 100);
                };
                move(type)
            }
        });

        $('.searchbtn').click(function () {
            var q = $('.searchtext').val();
            location.href = "<?=site_url('welcome/search')?>/" + q;
        });
        $('.searchtext').bind('keypress',function(event){
            if(event.keyCode == "13") {
                var q = $('.searchtext').val();
                location.href = "<?=site_url('welcome/search')?>/" + q;
            }
        });
    });
    function redeem() {
        layer.prompt({title: '输入兑换码，并确认', formType: 0,btn: ['兑换','取消']}, function (code, index) {
            layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.ajax({
                url:"<?=site_url('ucenter/useRedeem')?>",
                type:'post',
                dataType:'json',
                data:{code:code},
                success:function (res) {
                    layer.closeAll('loading');
                    layer.msg(res.msg,{time:800},function (d) {
                        if(res.status == 200) {
                            location.reload();
                        }
                    })

                }
            });
        });
    }
</script>


</html>
