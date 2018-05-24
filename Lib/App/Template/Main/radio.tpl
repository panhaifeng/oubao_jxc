  <div class="form-group">
    <label for="{$item.name|default:$key}" class="col-sm-3 control-label lableMain">{$item.title}:</label>
    <div class="col-sm-7">
	{foreach from=$item.radios item=rdi}
	<div class='radio'>
	<label>
	<input type="radio" name="{$item.name|default:$key}" value="{$rdi.value}" {if $rdi.value==$item.value} checked {/if}>
	{$rdi.title}
	</label>
	</div>
	{/foreach}
	</div>
  </div>
