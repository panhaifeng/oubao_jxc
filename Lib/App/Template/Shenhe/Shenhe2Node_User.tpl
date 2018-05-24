<!DOCTYPE html>
<html>
<head>
  <title>{webcontrol type='GetAppInf' varName='systemName'}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="Resource/Css/scrollbar.css">
  {webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
  {webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/bootstrap.js"}
</head>
{literal}
<script type="text/javascript">
  $(function(){
   
  });
</script>
<style type="text/css">

</style>
{/literal}
<body>
<div class="container">
  <form name="form1" id="form1" action="{url controller=$smarty.get.controller action=$form.action|default:'SaveUser'}" method="post">
  <h3>选择允许审核该节点的人员</h3>
  <!-- 审核菜单 -->
  <div id="users" class="col-md-12">
      <div class="row" role="group">
      {foreach from=$User item=user key=key}
        <div class="col-xs-3">
          <div class="form-group form-control">
            <label for="user_{$user.id}" class="control-label lableMain">{$user.realName}</label>
            <input type="checkbox" name="users[]" id='user_{$user.id}' value="{$user.id}" {if $user.checked==true}checked{/if}>
          </div>
        </div>
      {/foreach}
    </div>
  </div>
  <!-- 操作按钮 -->
  <div class="form-group col-md-12">
    <div class="text-center btnSubmit">
        <input type="hidden" value="{$nodeId}" name="nodeId">
        <input class="btn btn-info" type="submit" id="Submit" name="Submit" value=" 保存(Alt+S) " accesskey="S">
    </div>
  </div>
</form>
</div>
</body>
</html>