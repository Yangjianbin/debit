
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
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="小说大全" name="apple-mobile-web-app-title">
    <title><?=$one->name?></title>
<!--    <link rel="stylesheet" href="https://img1.km.com//bookimg/public/styles/mobile/3_0_0/mobile-global.css?20170501">-->
    <link rel="stylesheet" href="/static/h5/mobile-global.css">
    <link rel="stylesheet" href="/static/h5/mobile-detail.css">
    <link rel="stylesheet" href="/static/h5/mobile-comment.css">
<!--    <link rel="stylesheet" href="https://img2.km.com/bookimg/public/styles/mobile/3_0_0/mobile-detail.css?0307">-->
<!--    <link rel="stylesheet" href="https://img2.km.com/bookimg/public/styles/mobile/3_0_0/mobile-comment.css?0307">-->

</head>
<body id="xtopjsinfo" ontouchstart=''>
<!--wrapper begin-->
<div class="wrapper">
    <!-- header begin -->
    <header class="header inner-header">
        <a href="javascript:history.back();" id="go-back-button" class="back"></a>
        <h1 class="sub-title"><?=$one->name?></h1>
        <a href="/" class="home iconfont icon-home" ></a>
    </header>
    <!--header end--><!--container begin-->
    <div class="container">
        <!-- detail -->
        <section class="mod-detail">
            <div class="book-cover">
                <div class="clearfix">
                    <div class="img"><img src="<?=$one->cover?>"  onerror="this.src='https://img6.km.com/bookimg/public/pic/common/default_big.jpg'"><span class="fd-mark">原创</span>	</div>
                    <dl class="info">
                        <dt><?=$one->name?></dt>
                        <dd class="five-star star-small">
                            <ul class="five-star-choose starlevel-chosen-container">
                                <li class="starlevel-value-1"></li>
                                <li class="starlevel-value-2"></li>
                                <li class="starlevel-value-3"></li>
                                <li class="starlevel-value-4"></li>
                                <li class="starlevel-value-5"></li>
                            </ul>
                            <span class="point">10分</span>
                        </dd>
                        <dd>
                            <p>ISBN：<?=$one->isbn?></p>
                            <p>出版社：<?=$one->publisher?></p>
                            <p>分类：<?=$one->categoryStr?></p>
                        </dd>
                    </dl>
                </div>
                <div class="btns-op clearfix">
                    <a class="btn-primary" btn-size="large" href="javascript:;" target="_blank" onclick="buy()" >开始阅读</a>
                    <a class="btn-default app-download" btn-size="large" href="javascript:;" onclick="buy();">免费下载本书</a>

                </div>
            </div>
            <div class="book-intro">


            </div>
            <div class="dis-state">
                <h3>购买地址</h3>
                <div id="buy_links"></div>
            </div>

            <!-- 神马搜索 end -->
        </section>
    </div>
    <!-- detail end-->
    <!--wrapper end-->
</div>

<!--footer begin-->
<footer style="text-align: center;height: 80px;line-height: 80px;">
    <!--    <p class="links"><a href="/">小说首页</a><i>|</i><a href="http://book.km.com/app/index.html">手机客户端</a><i>|</i><a href="/index.php/c/feedback/">问题反馈</a><i>|</i><a href="#xtopjsinfo" class="go_top">返回顶部</a></p>-->
    <p>版权所有 &copy;www.yidian114.com All Rights Reserved</p>
</footer>
<!--footer end-->
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<!--<script src="https://img1.km.com/bookimg/public/javascripts/jquery1.8.3.min.js" type="text/javascript"></script>-->
<!--<script src="https://img2.km.com/bookimg/public/javascripts/jquery.cookie.js" type="text/javascript"></script>-->
<script>
    function buy() {
        alert('请前往pc端打开')
    }
    $(function () {
        var data = <?=json_encode($one)?>;
        if(data && data.buy_links){
            var links = JSON.parse(data.buy_links);
            var str = '';
            for(var o in links){
                var arr = links[o].split('@');
                str += '<a style="text-decoration: underline;" target="_blank" href="'+arr[1]+'">'+arr[0]+'</a>&nbsp;&nbsp;&nbsp;';
            }
            $('#buy_links').html(str);
        }
    })
</script>
