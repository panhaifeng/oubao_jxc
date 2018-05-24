<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
</head>
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap_eqinfo.css">
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/bootstrap.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}

{literal}
<style type="text/css">

body{margin-left:5px; margin-top:5px; margin-right: 8px;}
.btns { position:absolute; right:16px; top:1px; height:28px;}
.relative { position:relative;}
.frbtn {position:absolute; top:1px; right:0px; height:28px;z-index:1000;}
.pd5{ padding-left:5px;}
#heji { padding-left:20px; height:20px; line-height:20px; margin-bottom:5px;}
label.error {
  color: #FF0000;
  font-style: normal;
  position:absolute;
  right:-50px;
  top:5px;
}
.lableMain {
  padding-left: 2px !important;
  padding-right: 2px !important;
}
.trRow select{width:auto;}
.trRow input{min-width:75px;}
.form-horizontal{overflow: hidden;}
</style>

<script language="javascript">
   
$(function(){
  $('.glyphicon-plus','#table_main').bind('click',function(){
        var Trmain = $(this).parents('.trRow').clone(true);
        $('[name="sgFile[]"]',Trmain).val('');
        // $('[name="pbId[]"]',Trmain).val('');
        addRow(Trmain,'#table_main');
    });
    $('#table_main').on('click','.glyphicon-minus',function(){
        var elm = $(this).parents('.trRow');
        // var id=$('[name="pbId[]"]',elm).val();
        deleteId(elm);
    });
});


</script>
{/literal}
<body>
<div class='container-min'>
<form name="form1" id="form1" class="form-horizontal" action="{url controller=$smarty.get.controller action=$action_save|default:'saveShengou'}" method="post" enctype="multipart/form-data">

<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">导入申购单</h3></div>
    <div class="panel-body">
      <div class="row">
        <div class="form-group">
          <label for="" class="col-sm-3 control-label lableMain">
            上传文档
          </label>
          <div class="col-sm-7">
          <div class="input-group">
            <input id="sgFile[]" class="form-control" type="file" name="sgFile[]">
            <span class="input-group-btn">
              <input id="Submit" class="btn btn-info" type="submit" value=" 确 定 " name="Submit">
            </span>
          </div>
          <label for="" class="col-sm-12 control-label lableMain">
            (只能上传Excel文件)
          </label>
          </div>
        </div>
      </div>
  </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">删除已上传的缓存文件</h3></div>
    <div class="panel-body">
      <div class="row">
        <div class="form-group">
        <label for="" class="col-sm-3 control-label lableMain"></label>
          <div class="col-sm-7">
            <input id="Remove" class="btn btn-danger" type="button" name="Remove" value="我要删除缓存文件" onClick="javascript:window.location.href='{url Controller=Caigou_Shengou action='DelFile'}'"/>
          </div>
        </div>
      </div>
  </div>
</div>

</form>
</div>
</body>
</html>