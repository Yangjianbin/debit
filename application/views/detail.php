<?php
$this->load->view('common/header');
?>
<!-- 页头 end -->
<style>
    .classification_content .book_list a {
        width: 500px;
    }
    .home_text{width:1220px;height:40px;line-height: 40px;float:left}
    .home_text a{text-decoration: none;color:#666;font-weight: bold;}
    .home_text a:hover{color:#147223;}
    .home{font-size:16px}
    .home_next{font-size: 14px;}
    .second_left{width:890px;overflow: hidden;float:left;border-right:#eee 1px solid;padding-bottom:50px;color:#666}
    .second_right{width:300px;float:right;overflow: hidden;}
    .tb-booth{height:380px;margin-bottom: 50px;}
    .tb-booth_img{width:378px;height:378px;border:#eee 1px solid;text-align: center;display: table-cell;vertical-align:middle;}
    .tb-wrap{width:475px;overflow: hidden;float:right;}
    .tb-wrap h2{font-size: 24px;}
    .tb-wrap p{color:#666;font-size: 16px;}
    .download{width:855px;border:#eee 1px solid;font-size: 14px;margin-bottom:10px;border-top:#db2020 2px solid;}
    .download h4{font-weight: normal;border-bottom: #eee 1px solid;padding:5px;background:#f7f7f7;}
    .download_content{padding:22px}
    .download_content p{width:100%;overflow: hidden;;}
    .download_content p span{float:left;margin-right:5px}
    .download_content p a{display: block;float:left;padding:2px 10px;background:#db2020;text-decoration: none;color:#fff;border-radius: 2px;}
    .download_content p button{border:#3a3ae0 1px solid;border-radius:3px;color:#fff;padding:5px;cursor:pointer;background:-webkit-linear-gradient(#5858e5, #3535dc);background: -o-linear-gradient(#5858e5, #3535dc); background: -moz-linear-gradient(#5858e5, #3535dc); background: linear-gradient(#5858e5, #3535dc);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5858e5', endColorstr='#3535dc',GradientType=0 );
    }
    .download1 p{border-bottom: #147223 2px solid;}
    .download1 p span{padding:3px 10px;background:#147223;color:#fff;border-radius: 5px 5px 0px 0px;}
    .download1 div{padding:12px;line-height: 25px;}

</style>
<div class="center">
    <div class="navigation_module" dd_name="面包屑路径" style="padding: 0px;"></div>
    <div class="main classification_list" style="background: none">
        <div class="right" dd_name="">
            <div class="home_text">
                <a href="/index.php?s=/Home/Index/index.html" class="home">首页</a>&nbsp;<span> &gt; &nbsp;</span><a href="" class="home_next"></a>
            </div>

            <div class="second_left">
                <div class="tb-booth" id="book_list">
                    <!--<div style="float:left"><a href="/Uploads/Picture/2017-07-10/5962ecf243808.jpg"><div class="tb-booth_img">
                                <img src="/Uploads/Picture/2017-07-10/5962ecf243808.jpg" style="width: 272px;height: 339px"></div></a></div>
                    <div class="tb-wrap">
                        <input type="hidden" class="id" value="4351">
                        <h2 class="title" style="margin: initial;padding: initial;line-height: initial;font-weight: 900">摩根临床麻醉学-(第5版)_医学电子书</h2>
                        <p style="line-height: 50px;"><span>ISBN：</span>9787565911316</p>
                        <p style="line-height: 50px;"><span>出版社： </span></p>
                        <p style="line-height: 50px;"><span>出版时间：</span>2015-07-10</p>
                        <p style="line-height: 50px;"><span>版次：</span>0</p>
                        <p style="line-height: 50px;"><span>所属分类：</span>
                            | 麻醉疼痛科| 麻醉                    </p>
                    </div>-->
                </div>
                <div class="download" id="maoji" style="border-top:2px solid #214c90;">
                    <h4>支持正版，请购买正版图书</h4>
                    <div class="download_content" >
                        <div id="buy_links">

                        </div>
                    </div>
                </div>
                <div class="download" id="maoji" style="border-top:2px solid #214c90;">
                    <h4>免费电子书下载，下载源收集整理自网络，仅供个人学习使用</h4>
                    <div class="download_content">
                        <div id="down_links">
                            <?php if(isset($hasdownlinks) && !$hasdownlinks):?>
                                暂无下载地址.
                            <?php else:?>
                                <?php if(isset($this->session->islogin) && $this->session->islogin):?>
                                    电子书1积分/本，点击查看将消耗1积分(已下载过的用户不需要重复扣除积分) <a style="
    padding: 2px 10px;
    background: #db2020;
    text-decoration: none;
    color: #fff;
    border-radius: 2px;" target="_blank" href="javascript:;" onclick="buy()">查看</a>
                                    <br><br>

                                <?php else:?>
                                    您还未登录，不能查看。本网站不用注册，可以直接用微信登录查看。<a style="
    padding: 2px 10px;
    background: #db2020;
    text-decoration: none;
    color: #fff;
    border-radius: 2px;" target="_blank" href="<?=site_url('login/wx')?>" >用微信登录</a>
                                <?php endif?>
                            <?php endif;?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="second_right">
                <div class="Public_number">
                    <?php foreach ($ad as $k=>$v):?>
                        <?php
                            $v = (object)$v;
                        ?>
                        <a target="_blank" href="<?=$v->link ? $v->link:'javascript:;'?>">
                            <img src="<?=$v->image?>" style="max-width: 100%;"><br>
                        </a>
                        <?php if($v->desc):?>
                            <a style="font-size: 1.1em;" target="_blank" href="<?=$v->link ? $v->link:'javascript:;'?>">
                                <p style="text-indent: 2em;"><?=$v->desc?></p>
                            </a>
                        <?php endif;?>
                        <br>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="classification_content">
                <div class="book book_list clearfix" id="book_list">
                </div>
            </div>
            <div class="clickMore_wrap">
                <div class="loading" style="display: none;"></div>
            </div>
        </div>
    </div>

</div>
<?php $this->load->view('common/footer'); ?>
<script>
    $(function () {

        $('.searchbtn').click(function () {
            var q = $('.searchtext').val();
            location.href = "<?=site_url('welcome/search')?>/" + q;
        });
        $('.searchtext').bind('keypress', function (event) {
            if (event.keyCode == "13") {
                var q = $('.searchtext').val();
                location.href = "<?=site_url('welcome/search')?>/" + q;
            }
        });

        var data = [<?=$data?>];
        $('.home_next').html(data[0].name);
        if(data && data.length >0 && data[0].buy_links){
            var links = JSON.parse(data[0].buy_links);
            var str = '';
            for(var o in links){
                var arr = links[o].split('@');
//                console.log('sssssssss',arr)
                if(arr[0] && arr[1]){
                    str += arr[0] + '： <a style="text-decoration: underline;" target="_blank" href="'+arr[1]+'">'+arr[1]+'</a><br/>';
                }
            }
            $('#buy_links').html(str);
        }else{
            $('#buy_links').html('暂无购买地址.');
        }
        //是否下载过
        var hasdown = <?=(isset($hasdown) && $hasdown) ? 'true' : 'false'?>;
        if(hasdown){
            var str = '';
            $.each(JSON.parse(data[0].download_links),function (i, v) {
                var arr = v.split('@');
                if(arr[0] && arr[1]) {
                    str += '链接：' + '<a target="_blank" style="padding:3px;border-radius:3px;background-color:#db2020;color:white;" href="' + arr[0] + '">' + arr[0] + '</a>' + (arr[1] ? ' 密码：' + arr[1] : '') + '<br/><br/>';
                    str += '如果无下载地址或下载地址无效，请点击<a style="padding: 2px 10px;background: #6431e7;text-decoration: none;color: #fff;border-radius: 2px;" target="_blank" href="javascript:;" onclick="report()">举报已经失效</a>我们会在5个工作日内改进。';
                }
            });
            $('#down_links').html(str);
        }

        var template = _.template($('#classification_book_list').html());
        var html = template({data: data});
        $('#book_list').append(html);

    });

</script>
<script type="text/template" id="classification_book_list">
    <%if (data && data !=undefined){%>
    <%for(var i=0;i<data.length;i++){%>
        <%var item = data[i]%>
    <div style="float:left"><a href="<%=item.cover%>" target="_blank"><div class="tb-booth_img">
        <img src="<%=item.cover%>" style="width: 272px;height: 339px"></div></a></div>
        <div class="tb-wrap">
        <h2 class="title" style="margin: initial;padding: initial;line-height: initial;font-weight: 900"><%=item.name%></h2>
    <p style="line-height: 50px;"><span>ISBN：</span><%=item.isbn%></p>
    <p style="line-height: 50px;"><span>出版社： <%=item.publisher%></span></p>
    <p style="line-height: 50px;"><span>出版时间：</span><%=item.publish_time%></p>
    <p style="line-height: 50px;"><span>所属分类：</span><%=item.categoryStr%></p>
    </div>
            <%}%>
    <%}else{%>
    <div>亲，没有更多内容了</div>
        <%}%>
</script>
<script type="text/javascript" src="<?= base_url() ?>static/lib/layer/3/layer.js"></script>
<script>
    function buy() {
        layer.confirm('确定花费一积分查看？',function () {
            $.ajax({
                url:"<?=site_url('ucenter/buy/'.json_decode($data)->id)?>",
                type:'post',
                dataType:'json',
                success:function (res) {
                    if(res.status != 200) {
                        layer.msg(res.msg,{time:800},function () {});
                    }else{
                        var str = '';
                        if(res.data.download_links){
                            $.each(JSON.parse(res.data.download_links),function (i, v) {
                                var arr = v.split('@');
                                if(arr[0]){
                                    str += '链接：' + '<a target="_blank" style="padding:3px;border-radius:3px;background-color:#db2020;color:white;" href="'+arr[0]+'">' + arr[0] + '</a>' + (arr[1] ? ' 密码：' + arr[1] : '') + '<br/><br/>' ;
                                    str += '如果无下载地址或下载地址无效，请点击<a style="padding: 2px 10px;background: #6431e7;text-decoration: none;color: #fff;border-radius: 2px;" target="_blank" href="javascript:;" onclick="report()">举报已经失效</a>我们会在一个工作日改进。';
                                }
                            });
                            $('#down_links').html(str);
                        }

                        layer.closeAll();
//                        layer.alert('下载地址:' + str);
                    }
                }
            })
        })
    }

    function report() {
        layer.confirm('确定举报？',function () {
            $.ajax({
                url:"<?=site_url('ucenter/report/'.json_decode($data)->id)?>",
                type:'post',
                dataType:'json',
                success:function (res) {
                    if(res.status != 200) {
                        layer.msg(res.msg,{time:800},function () {});
                    }else{
                        layer.msg(res.msg,{time:800},function () {layer.closeAll();});
                    }
                }
            })
        });
    }
</script>
</html>
