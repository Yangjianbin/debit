<script type="text/javascript" src="<?=base_url()?>static/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?=base_url()?>static/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/h-ui.admin/js/H-ui.admin.page.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/common.js?v20180623"></script>
<script>
	var islogin = "<?=$this->session->islogin?>";
	$(function(){
		if(!islogin){
			layer.open({
				type: 1,
				closeBtn: 0,
				area: 'auto',
				shade:0.8,
				title: '登录',
				content: $('#login'),
				btnAlign: 'c',
				btn: ['登录'],
				yes: function(index, layero){
					var username = $('#login [name=username]').val();
					var password = $('#login [name=password]').val();
					if(!username || !password){
						layer.msg('用户名密码必填');
						return ;
					}
					var obj = {username:username,password:password};
					$.ajax({
						url:"<?=site_url('login')?>",
						type:'post',
						dataType:'json',
						data:obj
					}).done(function(res){
						if(!res || res.status == 500){
							layer.msg(res.msg);
							return ;
						}
						layer.msg('登录成功',{time:400},function(){
							location.reload();
						})
					}).fail(function(){
						layer.msg('请求失败，稍后再试');
					})
				}
			});
		}
		
		$('#logout').click(function(){
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
		})
		$('#changepwd').click(function(){
			layer.open({
				type: 1,
				shadeClose:true,
				area: 'auto',
				shade:0.8,
				title: '修改密码',
				content: $('#changepwdwrap'),
				btnAlign: 'c',
				btn: ['确认修改'],
				yes: function(index, layero){
					var newpassword = $('#changepwdwrap [name=newpassword]').val();
					var renewpassword = $('#changepwdwrap [name=renewpassword]').val();
					if(!newpassword || !renewpassword){
						layer.msg('密码必须填写');
						return ;
					}
					if(newpassword != renewpassword){
						layer.msg('新旧密码不同');
						return ;
					}
					var obj = {newpassword:newpassword};
					$.ajax({
						url:"<?=site_url('login/changepwd')?>",
						type:'post',
						dataType:'json',
						data:obj
					}).done(function(res){
						if(!res || res.status == 500){
							layer.msg(res.msg);
							return ;
						}
						layer.msg('修改成功',{time:400},function(){
							location.reload();
						})
					}).fail(function(){
						layer.msg('请求失败，稍后再试');
					})
				}
			});
			
			
		})
	})
	
</script>
<div id="login" style="display:none;">
	<article class="cl pd-20">
	<form method="post" class="form form-horizontal" novalidate="novalidate">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-4">用户名：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="text" class="input-text" value="" placeholder="用户名"  name="username">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-4">密码：</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="password" class="input-text valid" autocomplete="off" value="" placeholder="密码" name="password">
			</div>
		</div>

	</form>
</article>
</div>
<div id="changepwdwrap" style="display:none;">
	<article class="cl pd-20">
	<form method="post" class="form form-horizontal" novalidate="novalidate">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-4">新密码</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="password" class="input-text" value="" placeholder="新密码"  name="newpassword">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-4">重复密码</label>
			<div class="formControls col-xs-8 col-sm-8">
				<input type="password" class="input-text valid" autocomplete="off" value="" placeholder="重复密码" name="renewpassword">
			</div>
		</div>

	</form>
</article>
</div>
<script>
	$(function(){
		$('.menu-article li.current').closest('dd').prev('dt').click();
	});
</script>