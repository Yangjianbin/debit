<!DOCTYPE html>
<html>
<head>
<!--    <script src="https://img1.km.com/bookimg/public/javascripts/sea/module/m/jump.js" type="text/javascript"></script>-->
    <meta charset="gbk" />
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
    <meta name="wap-font-scale" content="no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta http-equiv="Cache-Control" content="no-transform" />
    <title></title>
<!--    <link rel="stylesheet" href="https://img1.km.com//bookimg/public/styles/mobile/3_0_0/mobile-global.css?20170501">-->
    <link rel="stylesheet" href="/static/h5/mobile-global.css">
    <style>
        .dropload-up,.dropload-down{
            position: relative;
            height: 0;
            overflow: hidden;
            font-size: 12px;
            /* 开启硬件加速 */
            -webkit-transform:translateZ(0);
            transform:translateZ(0);
        }
        .dropload-down{
            height: 50px;
        }
        .dropload-refresh,.dropload-update,.dropload-load,.dropload-noData{
            height: 50px;
            line-height: 50px;
            text-align: center;
        }
         .img-horizontal-list li{
             margin-bottom: 8px;
         }
        .dropload-load .loading{
            display: inline-block;
            height: 15px;
            width: 15px;
            border-radius: 100%;
            margin: 6px;
            border: 2px solid #666;
            border-bottom-color: transparent;
            vertical-align: middle;
            -webkit-animation: rotate 0.75s linear infinite;
            animation: rotate 0.75s linear infinite;
        }
        @-webkit-keyframes rotate {
            0% {
                -webkit-transform: rotate(0deg);
            }
            50% {
                -webkit-transform: rotate(180deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }
        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            50% {
                transform: rotate(180deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body id="xtopjsinfo" ontouchstart=''>
<!--wrapper begin-->
<div class="wrapper">
    <!-- header begin -->
    <header class="header inner-header">
        <a href="javascript:history.back();" id="go-back-button" class="back"></a>
        <h1 class="sub-title"><?=urldecode($name)?></h1>
        <a href="/" class="home iconfont icon-home" ></a>
    </header>

    <div id="list" class="rank-bd" style="padding-top: 15px;">
        <ul class="img-horizontal-list lazyload_box">
<!--            --><?php //foreach ($books['data'] as $k=>$v):?>
<!--                <li><a href="--><?//=site_url('welcome/h5_detail/' . $v['id'])?><!--">-->
<!--                        <img alt="" _src="--><?//=$v['cover']?><!--"><span>--><?//=$v['name']?><!--</span>-->
<!--                    </a>-->
<!--                </li>-->
<!--            --><?php //endforeach;?>
        </ul>
    </div>


</div>
<footer style="text-align: center;height: 80px;line-height: 80px;">
    <!--    <p class="links"><a href="/">小说首页</a><i>|</i><a href="http://book.km.com/app/index.html">手机客户端</a><i>|</i><a href="/index.php/c/feedback/">问题反馈</a><i>|</i><a href="#xtopjsinfo" class="go_top">返回顶部</a></p>-->
    <p>版权所有 &copy;www.yidian114.com All Rights Reserved</p>
</footer>
<!--footer end-->
<!--<script src="https://img1.km.com/bookimg/public/javascripts/jquery1.8.3.min.js" type="text/javascript"></script>-->
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<!--<script src="https://img2.km.com/bookimg/public/javascripts/jquery.cookie.js" type="text/javascript"></script>-->
<!--<script src="https://img1.km.com/bookimg/public/javascripts/sea/sea.js" id="seajsnode"></script>-->
<script src="<?= base_url() ?>/static/dropload.min.js"></script>

</body>
</html>
<!--<script src="https://img1.km.com/bookimg/public/javascripts/jquery.lazyload.js"></script>-->
<script>
//    $('.lazyload_box img').lazyload({
//        original: '_src',
//        placeholder: 'https://img2.km.com/bookimg/public/pic/common/default.png',
//        threshold: 0,
//        effect: 'fadeIn',
//        effectspeed: '200',
//        onerror: 'https://img3.km.com/bookimg/public/pic/common/default.png'
//    });
    $(function () {
        // 页数
//        var page = 0;
        // 每页展示5个
//        var size = 5;
        var start = 0;
        // dropload
        $('#list').dropload({
            scrollArea : window,
            loadDownFn : function(me){

                // 拼接HTML
                var result = '';
                $.ajax({
                    type: 'POST',
                    data:{start:start},
                    url: "<?=site_url('welcome/h5_list')?>",
                    dataType: 'json',
                    success: function(res){
                        var bool = res.data && res.data.data.length;
                        if(bool){
                            $.each(res.data.data,function (i, v) {
                                var url = "<?=site_url('welcome/h5_detail/')?>" + v.id;
                                var cover = v.cover;
                                result += '<li><a href="'+url+'"><img alt="" src="'+cover+'"><span>'+v.name+'</span></a></li>';
                            })
                            start += 10;
                            // 如果没有数据
                        }else{
                            // 锁定
                            me.lock();
                            // 无数据
                            me.noData();
                        }
                        // 为了测试，延迟1秒加载
                        setTimeout(function(){
                            // 插入数据到页面，放到最后面
                            $('#list>ul').append(result);
                            // 每次数据插入，必须重置
                            me.resetload();
                        },10);
                    },
                    error: function(xhr, type){
                        alert('Ajax error!');
                        // 即使加载出错，也得重置
                        me.resetload();
                    }
                });
            }
        });
    })
</script>
