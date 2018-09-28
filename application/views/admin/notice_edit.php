<?php
$this->load->view('admin/header');
?>
<body>
<article class="page-container">
    <form class="form form-horizontal" id="form-article-add">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">标题：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" class="input-text" value="<?=$item->title?>" placeholder="" id="title" name="title">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">开始时间：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" id="start_time" value="<?=$item->startTime?>" name="start_time" style="width: 200px;" onfocus="WdatePicker()"
                       class="input-text Wdate"/>
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">结束时间：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <input type="text" id="end_time" name="end_time" value="<?=$item->endTime?>" style="width: 200px;" onfocus="WdatePicker()"
                       class="input-text Wdate"/>
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">内容：</label>
            <div class="formControls col-xs-8 col-sm-10">
                <script id="editor" type="text/plain" style="width:100%;height:550px;"></script>
                </div>
                </div>
                <div class="row cl">
                    <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <button id="addArticle" type="button" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe632;</i>添加</button>
                </div>
                </div>
                </form>
                </article>


                <!--_footer 作为公共模版分离出去-->
                <?php
                $this->load->view('admin/footer');
                ?>
                <!--/_footer /作为公共模版分离出去-->

                <!--请在下方写此页面业务相关的脚本-->
                <script type="text/javascript" src="<?=base_url()?>static/lib/My97DatePicker/4.8/WdatePicker.js"></script>
                <script type="text/javascript" src="<?=base_url()?>static/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
                <script type="text/javascript" src="<?=base_url()?>static/lib/jquery.validation/1.14.0/validate-methods.js"></script>
                <script type="text/javascript" src="<?=base_url()?>static/lib/jquery.validation/1.14.0/messages_zh.js"></script>
                <script type="text/javascript" src="<?=base_url()?>static/lib/webuploader/0.1.5/webuploader.min.js"></script>
                <script type="text/javascript" src="<?=base_url()?>static/lib/ueditor/1.4.3/ueditor.config.js"></script>
                <script type="text/javascript" src="<?=base_url()?>static/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
                <script type="text/javascript" src="<?=base_url()?>static/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>

                <!--<script src="https://cdn.staticfile.org/plupload/2.1.2/moxie.min.js"></script>
                <script src="https://cdn.staticfile.org/plupload/2.1.2/plupload.full.min.js"></script>
                <script src="https://cdn.staticfile.org/plupload/2.1.2/i18n/zh_CN.js"></script>
                <script src="http://haitao3.isudoo.com/Public/static/qiniu.min.js"></script>-->


                <script type="text/javascript">
                    $(function(){

                        $('.skin-minimal input').iCheck({
                            checkboxClass: 'icheckbox-blue',
                            radioClass: 'iradio-blue',
                            increaseArea: '20%'
                        });

                        var ue = UE.getEditor('editor',{
                            toolbars: [[
                                'fullscreen', 'source', '|', 'undo', 'redo', '|',
                                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                                'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                                'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                                'directionalityltr', 'directionalityrtl', 'indent', '|',
                                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                                'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                                'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
                                'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
                                'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                                // 'print', 'preview', 'searchreplace', 'help', 'drafts'
                            ]]
                        });

                        ue.addListener("ready", function () {
                            ue.setContent($('#content').html());
                        });

                        $('#addArticle').click(function(){
                            var title = $('#title').val();
                            var startTime = $('#start_time').val();
                            var endTime = $('#end_time').val();
                            var content = ue.getContent();
                            if(!title || !content){
                                layer.msg('请输入标题和内容');return;
                            }
                            var obj = {id:"<?=$item->Id?>",title:title,start_time:startTime,end_time:endTime,content:content};
                            $.ajax({
                                url:"<?=site_url('admin/doNoticeEdit')?>",
                                type:'post',
                                dataType:'json',
                                data:obj
                            }).done(function(res){
                                if(!res || res.status == 500){
                                    layer.msg(res.msg);
                                    return ;
                                }
                                layer.msg('添加成功',{time:400},function(){
                                    window.parent.location.reload();
                                })
                            }).fail(function(){
                                layer.msg('请求失败，稍后再试');
                            })

                        });


                    });
                </script>
                <!--/请在上方写此页面业务相关的脚本-->
</body>
<div id="content" style="display: none"><?=$item->content?></div>
</html>