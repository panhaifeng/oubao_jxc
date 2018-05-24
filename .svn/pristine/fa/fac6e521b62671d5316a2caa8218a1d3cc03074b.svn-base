<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>

{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}

{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
<script charset="utf-8" src="Resource/Script/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="Resource/Script/kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="Resource/Script/kindeditor/plugins/code/prettify.js"></script>
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">
{literal}
<script language="javascript">
$(function(){
	$.validator.addMethod("oneUser", function(value, element) {		
		var o = $('[name="userId[]"]');
		if(o.length>0)return true;
		else{
			alert('至少填写一个收件人');
			return false;
		}
	}, "");
	$('#form1').validate({
		rules:{		
			'title':'required',
			'buildDate':'required',
			// 'content':'required',
			'accepter':'required',
			'id':'oneUser'
		}
		,submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});
	KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="content"]', {
				cssPath : 'Resource/Script/kindeditor/plugins/code/prettify.css',
				uploadJson : 'Resource/Script/kindeditor/php/upload_json.php',
				fileManagerJson : 'Resource/Script/kindeditor/php/file_manager_json.php',
				allowFileManager : true,
				items:[
					'undo', 'redo','|', 'justifyleft', 'justifycenter', 'justifyright',
					'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
					'superscript',
					'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
					'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '/', 'table', 'hr', 'emoticons', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'paste',
					'plainpaste', 'wordpaste', '|', 'clearhtml', 'quickformat', 'selectall', 'fullscreen', '|', 'preview'
				],
				afterCreate : function() {
					var self = this;
					this.focus();
				}
			});
			prettyPrint();
		});
	
	$('.colDiv').live('mouseover',function(){
		$(this).css({color:'red','background-color':'#FC9'});
		$(this).attr('title','点击删除该用户名');
	});
	$('.colDiv').live('mouseout',function(){
		$(this).css({color:'blue','background-color':''});
	});
	$('.colDiv').live('click',function(){
		var pos=$('.colDiv').index(this);
		$('.parDiv').eq(pos).remove();
		$('[name="userId[]"]').eq(pos).remove();
	});
});

function addText(sender) {
    var idx = sender.selectedIndex;
    if(idx < 0) return;
    var opt = sender.options[idx];

    if($('[name="userId[]"][value="'+opt.value+'"]').length>0)return;
	var div="<div class='parDiv'><div class='userDiv'>"+opt.text+"</div><div class='colDiv'></div></div>";
	var html=$('#accepter').html();
	html+=div;
	$('#accepter').html(html);
   // var ipt = sender.form.accepter;
    //ipt.value += (ipt.value ? "，" : "") + opt.text;
	
	//增加隐藏域
	var html = '<input type="hidden" name="userId[]" id="userId[]" value="'+opt.value+'">';
	document.getElementById('spanHidden').innerHTML += html;
	
}
</script>
<style type="text/css">
body{
	background: #F0F0EE;
}

.mainTableStyle100 .title{width:100px;}
.mainTableStyle100 {width:400px;}
.parDiv{border:0px solid; white-space:nowrap; float:left; padding:2px 6px 2px 6px; border-radius: 3px; margin-left: 3px; margin-top: -3px;}
.parDiv:hover{
	border: 1px solid #efefef;
}
.userDiv{border:0px solid; float:left; font-size:12px;}
.colDiv{ height:16px;width:16px;border:0px;float:left;cursor:pointer;background-image:url('Resource/Image/error.png')}
#accepter1{
	min-height: 260px;
	/*overflow: auto;*/
}
</style>
{/literal}
</head>
<body>

<form id='form1' name="form1" class="form-horizontal" method="post" action="{url controller=$smarty.get.controller action='save'}"  enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="1" cellspacing="1">
	<tr>
	  <td width="130" rowspan="3" align="right" valign="top">
	  <div align="left">
         <select name="accepter1" id="accepter1" multiple class="form-control" ondblclick='addText(this)'>
     		 {webcontrol type='TmisOptions' model='acm_user' emptyText='双击选择收件人'}
    	</select>
	    </div></td>
	  <td height="30" align="right">收件人&nbsp;</td>
	  <td height="30" bgcolor="#efefef">
      <div id='accepter'  name="accepter" class="form-control" title="双击选择收件人" style="width:80%;">{$aRow.accepter}</div>
      <!--input name="accepter" readonly type="hidden" id="accepter" size="80" value="{$aRow.accepter}"-->
	    <span id='spanHidden'>{$hidden}</span></td>
	  </tr>
	  <tr    bgcolor="#efefef">
	    <td width="80" height="30" align="right">标&nbsp;&nbsp;题&nbsp;</td>
        <td height="30"><input name="title" type="text" class="form-control" style="width:80%;" check="^(\s|\S)+$" warning="请输入主题！" value="{$aRow.title}"></td>
      </tr>
	  <tr bgcolor="#efefef">
	    <td height="300" align="right" valign="top">内&nbsp;&nbsp;容&nbsp;</td>
        <td><textarea name="content" id="content" style="width:80%;height:400px;">{$aRow.content}</textarea></td>
      </tr>
    </table>
    <!-- 按钮 -->
    <br>
    <div class="form-group col-xs-12">
  		<div class="text-center btnSubmit">
	      <input class="btn btn-primary" type="submit" id="Submit" name="Submit" value=" 确认并发送 " onclick="$('#submitValue').val('保存')">
	      {*其他一些功能按钮,*}
	      {$other_button}
	      <input class="btn btn-danger" type="reset" id="Reset" name="Reset" value=" 重 填 ">
	      <input type='hidden' name='submitValue' id='submitValue' value=''/>
	      <input type='hidden' name='fromController' id='fromController' value='{$fromController|default:$smarty.get.controller}'/>
	      <input type='hidden' name='fromAction' id='fromAction' value='{$fromAction|default:$smarty.get.action}'/>
	      <input type="hidden" id='id' name="id">
	  </div>
	</div>
  </form>

</body>
</html>
