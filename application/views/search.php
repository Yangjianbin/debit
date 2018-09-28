<?php
$this->load->view('common/header');
?>
<!-- 页头 end -->
<style>
    .classification_content .book_list a{
        width: 300px;
    }
</style>
<div class="center">
    <div class="navigation_module" dd_name="面包屑路径"></div>
    <div class="main classification_list" style="background: none">
        <div class="right" dd_name="书籍分类列表">
            <div class="classification_content">
                <div class="index_subnav_module">
                    <ul class="nav clearfix for_publish">
                        <li class="on" data-type="create_time">
                            <a data-type="create_time" href="javascript:;" dd_name="上架时间">上架时间
                                <i class="icon"> </i></a>
                        </li>
                        <li data-type="publish_time"><a data-type="publish_time" href="javascript:;" dd_name="出版时间">出版时间<i
                                    class="icon"> </i></a>
                        </li>
                    </ul>
                    <div class="bar" style="width: 83px;"></div>
                </div>
                <div class="book book_list clearfix" id="book_list">

                </div>
            </div>
            <div class="clickMore_wrap">
                <div class="loading" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('common/footer');?>
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
            init();
            category = $(this).data('category');
            getData(pageStart,pageSize);
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
                init();
                order = type;
                getData(pageStart,pageSize);
            }
        });
        /*初始化*/
        var counter = 0;
        /*计数器*/
        var pageStart = 0;
        /*offset*/
        var pageSize = 12;
        /*size*/
        var isEnd = false;
        var category = null;
        var order = 'create_time';
        var q = "<?=$q?>";
        /*结束标志*/

        var init = function () {
            pageStart = 0;
            $('#book_list').empty();
        };

        var getData = function(pageStart, pageSize) {
            $(".loading").show();
            isEnd = true;
            var query = {
                start: pageStart,
                pageSize: pageSize,
                category: category,
                order:order,
                q:q
            };

            $.ajax({
                url: "<?=site_url('welcome/bookLike')?>",
                type: 'post',
                dataType: 'json',
                data: query,
                complete:function () {
                    $(".loading").hide();
                }
            }).done(function (res) {
                var data = res.data.data;
                if(!data || !data.length){
                    $('#book_list').append("<div class='go_for_more' style='clear:both'><a href='javascript:void(0)'>亲，没有更多内容了</a></div>");
                }else{
                    var template = _.template($('#classification_book_list').html());
                    var html = template({data:data});
                    $('#book_list').append(html);
                    isEnd = false;
                }
            })
        }

        /*首次加载*/
        getData(pageStart, pageSize);

        /*监听加载更多*/
        $(window).scroll(function () {
            if (isEnd == true) {
                return;
            }
            // 当滚动到最底部以上100像素时， 加载新内容
            // 核心代码
            if ($(document).height() - $(this).scrollTop() - $(this).height() < 100) {
                $('.returntop').show();

                counter++;
                pageStart = counter * pageSize;

                getData(pageStart, pageSize);
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

</script>
<script type="text/template" id="classification_book_list">
    <%if (data && data !=undefined){%>
    <%for(var i=0;i<data.length;i++){%>
    <%var item = data[i]%>
    <a href="<?=site_url('welcome/detail')?>/<%=item.id%>" target="_blank" title="<%=item.name%>" dd_name="<%=item.name%>">
	                 <span class="bookcover">
	                <img src="<%=item.cover%>"
                         alt="<%=item.name%>"></span>
        <div class="bookinfo">
            <div class="title"><%=item.name%></div>
            <div class="author"><%=item.publisher%></div>
            <div class="startie"></div>
            <div class="price">
                <span class="now"><%=item.isbn%></span>
            </div>
            <div class="des">出版时间 <%=item.publish_time%>
            </div>
        </div>
    </a>
    <%}%>
    <%}else{%>
    <div>亲，没有更多内容了</div>
    <%}%>
</script>

</html>
