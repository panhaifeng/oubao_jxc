<div class="col-xs-4">
  <div class="form-group">
    <label for="{$item.name|default:$key}" class="col-sm-3 control-label lableMain">{$item.title}:</label>
    <!--增加了登记界面选择框的isSearch，2015-09-28，by liuxin-->
    <div class="col-sm-9">{webcontrol type='BtSelect' model=$item.model condition=$item.condition options=$item.options value=$item.value itemName=$item.name|default:$key emptyText=$item.emptyText optionType=$item.optionType isSearch=$item.isSearch}      
    </div>
  </div>
</div>