<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Cache-control" content="no-cache" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登陆易奇软件</title>
<link rel="stylesheet" type="text/css" href="Resource/Css/loginNew_phone.css">
</head>
<body>
    <div class="container-fluid">
        <div class="content" style="{$login.bigCss}">
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
                        <form action="{url controller=$smarty.get.controller action='LoginPhone'}" method="post" autocomplete='off' id="form_login">
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
                </div>
                <div class="footer">
                    <span class="gray">易奇科技 版权所有 © 2007-{$smarty.now|default:'2015'|date_format:'%Y'}</span>
                </div>
            </div>
        </div>
    </div>
</body>
<script language="javascript" type="text/javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" type="text/javascript" src="Resource/Script/jquery.form.js"></script>
<script type="text/javascript">
{literal}
    $(function(){
        //加载界面就判断
        $('.inputstyle').each(function(){
            if(this.value==''){
                $(this).parent().find('label').css({'display':'block'});
            }else{
                $(this).parent().find('label').css({'display':'none'});
            }
        });
        //placeholder效果模拟
        $('.inputstyle').keydown(function(event){
            $(this).parent().find('label').css({'display':'none'});
        });
        $('.inputstyle').keyup(function(event){
            if(this.value==''){
                $(this).parent().find('label').css({'display':'block'});
            }else{
                $(this).parent().find('label').css({'display':'none'});
            }
        });
        $('.inputstyle').blur(function(){
            //边框
            $(this).removeClass('inputstyle_focus');

            //判断是否要显示label placeholder
            if(this.value==''){
                $(this).parent().find('label').css({'display':'block'});
            }else{
                $(this).parent().find('label').css({'display':'none'});
            }
        });

        //边框聚焦问题
        $('.inputstyle').focus(function(){
            $(this).addClass('inputstyle_focus');
        });

        //切换登陆方式
        $('.login_btn').click(function(){
            var that = this;
            //按钮颜色改变
            $('.login_btn').removeClass('active');
            $(that).addClass('active');

            $('.active_bottom').css({'left':(that.offsetLeft+35)+'px'});

            //显示的登陆框改变
            $('.input').removeClass('input_block').addClass('input_hide');
            $('#'+$(that).attr('to')).removeClass('input_hide').addClass('input_block');
        });

        //聚焦用户名输入
        $('#username').focus();

        //确定按钮点击后效果
        $('#form_login').submit(function(){
            $('#submit').attr('disabled',true);
            $('#submit').text('登录中…');
            $(this).ajaxSubmit({
                'data':{'is_ajax':true},
                success:function(t,b,f){
                    var json = eval("("+t+")");
                    if(json.success==true){
                        showError('登陆成功');
                        setTimeout(function(){window.location.href=json.href;}, 500);
                    }else{
                        showError(json.msg);
                        setTimeout(function(){
                            $('#submit').attr('disabled',false);
                            $('#submit').text('登 录');
                        }, 500);
                    }
                }
            });
            
            return false;
        });
    });

    function showError(text) {
        $('#divError').text(text).fadeIn('slow');
        setTimeout(function(){$('#divError').fadeOut('normal');}, 3500);
    }
{/literal}
</script>
</html>
