<div class="col-xs-4">
  <div class="form-group">
    <label for="{$item.name|default:$key}" class="col-sm-3 control-label lableMain">{$item.title}:</label>
    <div class="col-sm-7">
	<div class='checkbox-inline'>
	<label>
	<input type="checkbox" name="{$item.name|default:$key}" value="{$item.value}" {if $item.checked==true} checked {/if}>
	</label>
	</div>
	</div>
  </div>
</div>
