    <div class="form-group">
        <label for="{$item.name|default:$key}" class="col-sm-3 control-label lableMain">{$item.title}:</label>
        <div class="col-sm-7">
          {webcontrol type='BtPopupEdit' itemName=$item.name|default:$key value=$item.value disabled=$item.disabled readonly=$item.readonly text=$item.text url=$item.url textFld=$item.textFld hiddenFld=$item.hiddenFld}
        </div>
    </div>