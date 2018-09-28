<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="<?=site_url()?>">DEBIT</a>
		<a class="logo navbar-logo-m f-l mr-10 visible-xs" href="#">H-ui</a>
			<nav id="Hui-userbar" style="right: 31px;" class="nav navbar-nav navbar-userbar hidden-xs">
				<ul class="cl">
					<?php if($this->session->islogin):?>
					<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?=$this->session->admin->username?><i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" id="changepwd">Change Pin</a></li>
							<li><a href="javascript:;" id="logout">Log Out</a></li>
						</ul>
					</li>
					<?php endif;?>
				</ul>
			</nav> 
		</div>
	</div>
</header>