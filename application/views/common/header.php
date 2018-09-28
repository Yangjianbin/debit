<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title><?=(isset($title) && $title) ? $title : ''?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta content="telephone=no" name="format-detection"/>
    <link type="text/css" rel="stylesheet" href="/static/home/common.min.css"/>
    <link type="text/css" rel="stylesheet" href="/static/home/classification_list_page.min.css"/>
    <meta name="keywords" content="<?=(isset($keywords) && $keywords) ? $keywords : '医典114'?>">
    <meta name="description" content="<?=(isset($description) && $description) ? $description : '医典114'?>
">
</head>
<body class="original_index">
<link href="/static/home/header.css" rel="stylesheet" type="text/css">
<div id="hd">
    <div id="tools">
        <div class="tools">
            <div class="ddnewhead_operate" dd_name="顶链接">
                <?php if($this->session->islogin && $this->session->user):?>
                    <div class="ddnewhead_welcome" display="none;">
                        <span id="nickname">
                            <span class="hi">Hi，<a href="<?=site_url('ucenter')?>" class="login_link" target="_blank"><b><?=$this->session->user->nickname?></b></a>
                                <a href="javascript:logout();" target="_self">[退出]</a>
                            </span>
                        </span>
                    </div>
                <?php else:?>
                    <div class="ddnewhead_welcome" >
                    <span id="nickname"><span class="hi hi_none">欢迎光临医典114，请</span>
                        <a href="<?=site_url('login/wx')?>" class="login_link">登录</a>
                    </span>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div id="header_end"></div>
</div>
<!-- 头部开始 -->
<div class="public_headersearch_module" dd_name="头部搜索">
    <div class="clearfix">
        <a href="<?=base_url()?>" class="logo">
            <img src="/static/img/logo.jpg"/>
        </a>
        <div class="search">
            <input type="text" placeholder="书名、ISBN" value="<?=isset($q) ? $q : ''?>" class="searchtext"/>
            <span type="button" value="提交" class="searchbtn"></span>
        </div>

    </div>
</div>
<!-- 头部结束 -->
<!--<script type="text/javascript">var nick_num = 1; initHeaderOperate();</script>-->

<!-- 导航 -->
<div class="public_headernav_module padding_top_30" dd_name="头部导航">
    <div class="public_headernav_module">
        <div class="nav">
            <ul style="height: 6px;">

            </ul>
        </div>
    </div>
</div>