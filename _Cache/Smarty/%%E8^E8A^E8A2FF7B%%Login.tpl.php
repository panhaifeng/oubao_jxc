<?php /* Smarty version 2.6.10, created on 2017-03-19 16:33:55
         compiled from Login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Login.tpl', 14, false),array('function', 'url', 'Login.tpl', 32, false),array('modifier', 'default', 'Login.tpl', 20, false),array('modifier', 'date_format', 'Login.tpl', 60, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%E8^E8A^E8A2FF7B%%Login.tpl.inc'] = 'e7dc6ff67ba0353312fdb77d33876c48'; ?><!DOCTYPE html>
<html>
<head>
<meta name="renderer" content="webkit"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>E7 Manager System V1.1</title>
<link rel="stylesheet" type="text/css" href="Resource/Css/loginNew.css">
</head>
<body>
	<div class="container">
		<div class="header">
			<a href='http://www.eqinfo.com.cn' target="_blank"><div class="logo" style="background-image:url(Resource/Image/LoginNew/logo.png);"></div></a>
			<div class="link">
				<span><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:e7dc6ff67ba0353312fdb77d33876c48#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemV'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:e7dc6ff67ba0353312fdb77d33876c48#0}';}?>
</span>
				&nbsp;|&nbsp;
				<span>免费服务热线:400-669-0297</span>
			</div>
		</div>

		<div class="content" style="background-image:url(<?php echo ((is_array($_tmp=@$this->_tpl_vars['login']['bg'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
);backgroud-size:100% 100%">
			<div class="mainInner">
				<div class="rightBox">
					<div class="header_login">
						<div id="common_login" to="kuaijie_form" class="login_btn col-6 text-center active">账号密码登陆</div>
						<!-- <div id="kuaijie_login" to="qrcode_form" class="login_btn col-6 text-center">快速登陆</div> -->
						<div class="active_bottom">&nbsp;</div>
					</div>
					
					<!-- 密码登陆 -->
			<div id="divError"></div>
	        <div class="input input_block" id="kuaijie_form">
	        	<form action="<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:e7dc6ff67ba0353312fdb77d33876c48#1}';}echo $this->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => 'login'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:e7dc6ff67ba0353312fdb77d33876c48#1}';}?>
" method="post" autocomplete='off' id="form_login">
	        		<div class="uinArea" id="uinArea">
	        			<label class="input_tips" id="uin_tips" for="username">用户名</label>
		        			<input type="text" class="inputstyle" id="username" name="username" tabindex="1">
	        		</div>
	        		<div class="pwdArea" id="pwdArea">
	        			<label class="input_tips" id="pwd_tips" for="password">密码</label>
	        				<input type="password" class="inputstyle password" id="password" name="password" tabindex="2">
	        		</div>
	        		<div class="verifyArea" id="verifyArea">
	        			<label class="input_tips" id="verify_tips" for="verify">验证码/可以为空</label>
	        				<input type="text" class="inputstyle verify" id="verify" name="verify" tabindex="3">
	        		</div>
	            <button type="submit" id="submit" tabindex="4">登 录</button>
	           </form>
	        </div>
					<!-- 二维码登陆 -->
					<!-- <div class="input input_hide" id="qrcode_form">
						<div class="login_text">请使用<span style='color:#3481D8'>微信扫一扫</span>登陆</div>
						<div class="br_20">&nbsp;</div>
						<div id="qrcode"><img src="Resource/Image/LoginNew/yiqi.png" /></div>
					</div> -->
				</div>
			</div>
		</div>
		<div class="footer">
			<a href="http://www.eqinfo.com.cn" target="_blank">关于易奇</a>
			&nbsp;|&nbsp;
			<span class="gray">© 2007-<?php echo ((is_array($_tmp=((is_array($_tmp=time())) ? $this->_run_mod_handler('default', true, $_tmp, '2015') : smarty_modifier_default($_tmp, '2015')))) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y') : smarty_modifier_date_format($_tmp, '%Y')); ?>
EQINFO Inc. All Rights Reserved.</span>
		</div>
	</div>
</body>
<script language="javascript" type="text/javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" type="text/javascript" src="Resource/Script/jquery.form.js"></script>
<script type="text/javascript">
<?php echo '
	$(function(){
		//加载界面就判断
		$(\'.inputstyle\').each(function(){
			if(this.value==\'\'){
				$(this).parent().find(\'label\').css({\'display\':\'block\'});
			}else{
				$(this).parent().find(\'label\').css({\'display\':\'none\'});
			}
		});
		//placeholder效果模拟
		$(\'.inputstyle\').keydown(function(event){
			$(this).parent().find(\'label\').css({\'display\':\'none\'});
		});
		$(\'.inputstyle\').keyup(function(event){
			if(this.value==\'\'){
				$(this).parent().find(\'label\').css({\'display\':\'block\'});
			}else{
				$(this).parent().find(\'label\').css({\'display\':\'none\'});
			}
		});
		$(\'.inputstyle\').blur(function(){
			//边框
			$(this).removeClass(\'inputstyle_focus\');

			//判断是否要显示label placeholder
			if(this.value==\'\'){
				$(this).parent().find(\'label\').css({\'display\':\'block\'});
			}else{
				$(this).parent().find(\'label\').css({\'display\':\'none\'});
			}
		});

		//边框聚焦问题
		$(\'.inputstyle\').focus(function(){
			$(this).addClass(\'inputstyle_focus\');
		});

		//切换登陆方式
		$(\'.login_btn\').click(function(){
			var that = this;
			//按钮颜色改变
			$(\'.login_btn\').removeClass(\'active\');
			$(that).addClass(\'active\');

			$(\'.active_bottom\').css({\'left\':(that.offsetLeft+35)+\'px\'});

			//显示的登陆框改变
			$(\'.input\').removeClass(\'input_block\').addClass(\'input_hide\');
			$(\'#\'+$(that).attr(\'to\')).removeClass(\'input_hide\').addClass(\'input_block\');
		});

		//聚焦用户名输入
		$(\'#username\').focus();

		//确定按钮点击后效果
		$(\'#form_login\').submit(function(){
			$(\'#submit\').attr(\'disabled\',true);
			$(\'#submit\').text(\'登录中…\');
			$(this).ajaxSubmit({
				\'data\':{\'is_ajax\':true},
				success:function(t,b,f){
					var json = eval("("+t+")");
					if(json.success==true){
						showMsg(\'登陆成功\');
						setTimeout(function(){window.location.href=json.href;}, 500);
					}else{
						showError(json.msg);
						setTimeout(function(){
							$(\'#submit\').attr(\'disabled\',false);
							$(\'#submit\').text(\'登 录\');
						}, 500);
					}
				}
			});
			
			return false;
		});
	});
	function showMsg(text){
		$(\'#divMsg\').text(text).fadeIn(\'slow\');
		setTimeout(function(){$(\'#divMsg\').fadeOut(\'normal\');}, 3500);
	}

	function showError(text) {
		$(\'#divError\').text(text).fadeIn(\'slow\');
		setTimeout(function(){$(\'#divError\').fadeOut(\'normal\');}, 3500);
	}
'; ?>

</script>
</html>