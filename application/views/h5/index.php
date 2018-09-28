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
    <title></title>
    <link rel="stylesheet" href="/static/h5/mobile-global.css">
    <link rel="stylesheet" href="/static/h5/mobile-rank.css">
    <style>
        .sort-li-detail{font-size:.75rem;line-height:0;padding:0 1rem 1rem;text-align:justify;text-align:left}
        .sort-li-detail .btn-line-gray {
            margin-top:10px;
        }
        .btn-line-gray, .btn-line-gray~i:empty, .btn-tag {
            color: #969ba3;
        }
        .btn-line, .btn-line-gray, .btn-line-gray~i:empty, .btn-tag {
            font-size: 15px;
            line-height: 15px;
            display: inline-block;
            padding: 8px;
            text-align: center;
            border: 1px solid;
            border-radius: 99px;
        }
    </style>
</head>
<body ontouchstart='' id="xtopjsinfo">
<!--wrapper begin-->
<div class="wrapper">
    <!-- header begin -->
    <header class="header">
        <a href="/" style="display: block;margin: 10px auto auto 10px;"  class="logo1">
            <img style="float:left;max-height: 3.5rem;" src="/static/img/logo.jpg" alt="">
        </a>
    </header>

    <div style="border-top:1px solid #ebebeb;padding:5px;">
        <form action="<?=site_url('welcome/h5_search')?>" method="get">
            <div class="search-input" style="margin-bottom: 3px;">
                <input type="search" placeholder="请输入书名" value="" autocomplete="off" autocorrect="off" maxlength="64" name="key" class="inp">
                <button class="search-btn"><i class="iconfont icon-search"></i></button>
                <b class="iconfont icon-clean" style="display: none"></b>
            </div>
        </form>
    </div>

    <!--header end-->
    <!--nav begin-->
    <!--<nav class="navbar no-cut-line">

        <a href="/">推荐</a>
        <a href="/shuku.html">分类</a>
        <a href="/rank.html" class="current">排行</a>
        <a href="/search.html">搜索</a>
        <a href="javascript:;"class="new" id="top_app_download">客户端</a>
    </nav>-->
    <!--nav end-->
    <!--container begin-->
    <div class="container">

        <div class="rank-content">
            <div class="rank-hd"><h3 class="title r-tushu">最新上架</h3></div>
            <div class="rank-bd" style="padding-bottom: 0px !important;">
                <ul class="img-horizontal-list lazyload_box">
                    <?php foreach ($book as $k=>$v):?>
                        <li><a href="<?=site_url('welcome/h5_detail/' . $v['id'])?>">
                                <img alt="" _src="<?=$v['cover']?>"><span><?=$v['name']?></span>
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>


            <?php foreach ($category as $k=>$v):?>
                <div class="rank-hd"><h3 class="title r-click"><?=$v['name']?></h3><a href="<?=site_url('welcome/h5_list?category=' . $v['id'] . '&pageSize=20&name=' . $v['name'])?>" class="more">更多</a></div>
                <div class="">
                    <div class="sort-li-detail">
                        <?php foreach ($v['child'] as $kk=>$vv):?>
                        <a href="<?=site_url('welcome/h5_list?category=' . $vv['id'] . '&pageSize=20&name=' . $vv['name'])?>" class="btn-line-gray"><?=$vv['name']?></a>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<!--footer begin-->
<!--footer begin-->
<footer style="text-align: center;height: 80px;line-height: 80px;">
<!--    <p class="links"><a href="/">小说首页</a><i>|</i><a href="http://book.km.com/app/index.html">手机客户端</a><i>|</i><a href="/index.php/c/feedback/">问题反馈</a><i>|</i><a href="#xtopjsinfo" class="go_top">返回顶部</a></p>-->
    <p>版权所有 &copy;www.yidian114.com All Rights Reserved</p>
</footer>
<!--footer end-->
<!--<script src="https://img1.km.com/bookimg/public/javascripts/jquery1.8.3.min.js" type="text/javascript"></script>-->
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<!--<script src="https://img2.km.com/bookimg/public/javascripts/jquery.cookie.js" type="text/javascript"></script>-->
</body>
</html>
<script src="https://img1.km.com/bookimg/public/javascripts/jquery.lazyload.js"></script>
<script>
    $('.lazyload_box img').lazyload({
        original: '_src',
        placeholder: 'https://img2.km.com/bookimg/public/pic/common/default.png',
        threshold: 0,
        effect: 'fadeIn',
        effectspeed: '200',
        onerror: 'https://img3.km.com/bookimg/public/pic/common/default.png'
    });
</script>
<script src="https://s13.cnzz.com/z_stat.php?id=1264336854&web_id=1264336854" language="JavaScript"></script>
<!--footer end-->
<!-- Cached copy by 74278407498b2171698fb4b82ce73969.html , generated 2017-07-11 07:42:19 -->
