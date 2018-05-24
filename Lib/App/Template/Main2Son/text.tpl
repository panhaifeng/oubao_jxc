<div class="col-xs-4">
    <div class="form-group">
        <label for="{$item.name|default:$key}" class="col-sm-3 control-label lableMain lableMain">{$item.title}:</label>
        <div class="col-sm-9">
        	{webcontrol type='BtText' itemName=$item.name|default:$key value=$item.value disabled=$item.disabled readonly=$item.readonly addonPre=$item.addonPre addonEnd=$item.addonEnd width=$item.width}
        </div>
    </div>
</div>