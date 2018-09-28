<!--_meta 作为公共模版分离出去-->
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="Bookmark" href="favicon.ico" >
<link rel="Shortcut Icon" href="favicon.ico" />
<?php
$this->load->view('header');
?>
<!--/meta 作为公共模版分离出去-->

<title>编辑文章</title>
</head>
<body>
<article class="page-container">
	<form class="form form-horizontal" id="form-article-add">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-1">标题：</label>
			<div class="formControls col-xs-8 col-sm-11">
				<input type="text" class="input-text" value="" placeholder="" id="title" name="title">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-1">小图：</label>
			<div class="formControls col-xs-8 col-sm-11">
				<div id="container">
					<a href="javascript:;" id="pickfiles" class="btn btn-default radius"><i class="Hui-iconfont"></i>上传图片</a>
					<img alt="" id="pickfiles_image" style="max-width: 100px;max-height:100px;">
				</div>
			</div>
			<input type="hidden" name="image" />
		</div>
		
		
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-1">内容：</label>
			<div class="formControls col-xs-8 col-sm-11"> 
				<script id="editor" type="text/plain" style="width:100%;height:550px;"></script> 
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-1">
				<button id="addArticle" type="button" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe632;</i>添加</button>
			</div>
		</div>
	</form>
</article>

<!--_footer 作为公共模版分离出去-->
<?php
$this->load->view('footer');
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

<script src="https://cdn.staticfile.org/plupload/2.1.2/moxie.min.js"></script>
<script src="https://cdn.staticfile.org/plupload/2.1.2/plupload.full.min.js"></script>
<script src="https://cdn.staticfile.org/plupload/2.1.2/i18n/zh_CN.js"></script>
<script src="http://haitao3.isudoo.com/Public/static/qiniu.min.js"></script>


<script type="text/javascript">
$(function(){
	
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});

	$list = $("#fileList"),
	$btn = $("#btn-star"),
	state = "pending",
	uploader;

	var uploader = WebUploader.create({
		auto: true,
		swf: '<?=base_url()?>/static/lib/webuploader/0.1.5/Uploader.swf',
	
		// 文件接收服务端。
		server: 'fileupload.php',
	
		// 选择文件的按钮。可选。
		// 内部根据当前运行是创建，可能是input元素，也可能是flash.
		pick: '#filePicker',
	
		// 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
		resize: false,
		// 只允许选择图片文件。
		accept: {
			title: 'Images',
			extensions: 'gif,jpg,jpeg,bmp,png',
			mimeTypes: 'image/*'
		}
	});
	uploader.on( 'fileQueued', function( file ) {
		var $li = $(
			'<div id="' + file.id + '" class="item">' +
				'<div class="pic-box"><img></div>'+
				'<div class="info">' + file.name + '</div>' +
				'<p class="state">等待上传...</p>'+
			'</div>'
		),
		$img = $li.find('img');
		$list.append( $li );
	
		// 创建缩略图
		// 如果为非图片文件，可以不用调用此方法。
		// thumbnailWidth x thumbnailHeight 为 100 x 100
		uploader.makeThumb( file, function( error, src ) {
			if ( error ) {
				$img.replaceWith('<span>不能预览</span>');
				return;
			}
	
			$img.attr( 'src', src );
		}, thumbnailWidth, thumbnailHeight );
	});
	// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {
		var $li = $( '#'+file.id ),
			$percent = $li.find('.progress-box .sr-only');
	
		// 避免重复创建
		if ( !$percent.length ) {
			$percent = $('<div class="progress-box"><span class="progress-bar radius"><span class="sr-only" style="width:0%"></span></span></div>').appendTo( $li ).find('.sr-only');
		}
		$li.find(".state").text("上传中");
		$percent.css( 'width', percentage * 100 + '%' );
	});
	
	// 文件上传成功，给item添加成功class, 用样式标记上传成功。
	uploader.on( 'uploadSuccess', function( file ) {
		$( '#'+file.id ).addClass('upload-state-success').find(".state").text("已上传");
	});
	
	// 文件上传失败，显示上传出错。
	uploader.on( 'uploadError', function( file ) {
		$( '#'+file.id ).addClass('upload-state-error').find(".state").text("上传出错");
	});
	
	// 完成上传完了，成功或者失败，先删除进度条。
	uploader.on( 'uploadComplete', function( file ) {
		$( '#'+file.id ).find('.progress-box').fadeOut();
	});
	uploader.on('all', function (type) {
        if (type === 'startUpload') {
            state = 'uploading';
        } else if (type === 'stopUpload') {
            state = 'paused';
        } else if (type === 'uploadFinished') {
            state = 'done';
        }

        if (state === 'uploading') {
            $btn.text('暂停上传');
        } else {
            $btn.text('开始上传');
        }
    });

    $btn.on('click', function () {
        if (state === 'uploading') {
            uploader.stop();
        } else {
            uploader.upload();
        }
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

	$('#addArticle').click(function(){
		var title = $('#title').val();
		var content = ue.getContent();
		var image = $('[name=image]').val();
		if(!title || !content){
			layer.msg('请输入标题和内容');return;
		}
		var obj = {title:title,image:image,content:content,catId:"<?=$catId?>"};
		$.ajax({
			url:"<?=site_url('welcome/articleAdd')?>",
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
	
	var uploader = Qiniu.uploader({
		runtimes: 'html5',      // 上传模式，依次退化
		browse_button: 'pickfiles',         // 上传选择的点选按钮，必需
		uptoken_url: "<?=site_url('welcome/uptoken')?>",         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
		get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
		 unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
		domain: 'http://static.isudoo.com',     // bucket域名，下载资源时用到，必需
		container: 'container',             // 上传区域DOM ID，默认是browser_button的父元素
		max_file_size: '200mb',             // 最大文件体积限制
		flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
		max_retries: 3,                     // 上传失败最大重试次数
		dragdrop: true,                     // 开启可拖曳上传
		drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
		chunk_size: '4mb',                  // 分块上传时，每块的体积
		auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
		x_vars:{
		},
		init: {
			'BeforeUpload':function (up,file) {
			},
			'FileUploaded': function(up, file, info) {
				 var domain = up.getOption('domain');
				 var res = JSON.parse(info);
				 var sourceLink = domain +"/"+ res.key;
				$('#pickfiles_image').attr('src',sourceLink);
				$('[name=image]').val(sourceLink);
			}
		}
	});
	
});
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>