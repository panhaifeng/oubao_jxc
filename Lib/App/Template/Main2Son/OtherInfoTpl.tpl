<!--其他费用信息-->
<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">{$otherInfo|default:'其他信息编辑'}</h3></div>
  <div class="panel-body" style="overflow:auto;max-height:320px;">
    <div class="table-responsive" style="margin-top:-15px;">
      <table class="table table-condensed table-striped" id='table_else'>
        <thead>
          <tr>
            {foreach from=$qitaSon item=item key=key}
            {if $item.type!='BtHidden'}
              {if $item.type=='BtBtnRemove' || $item.type=='btBtnCopy'}
                <th>{webcontrol type='btBtnAdd'}</th>
              {else}
              <th style='white-space:nowrap;'>{$item.title}</th>
              {/if}
            {/if}
            {/foreach}
          </tr>   
        </thead>
        <tbody>
          {foreach from=$row4sSon item=item1 key=key1}
          <tr class='trRow'>
            {foreach from=$qitaSon item=item key=key}
              {if $item.type!='BtHidden'}
              <td>{webcontrol 
                      type=$item.type
                      value=$item1[$key].value
                      kind=$item.kind
                      itemName=$item.name
                      readonly=$item.readonly
                      disabled=$item.disabled
                      model=$item.model
                      options=$item.options
                      checked=$item1[$key].checked
                      url=$item.url 
                      textFld=$item.textFld
                      hiddenFld=$item.hiddenFld
                      text=$item1[$key].text
                      inTable=$item.inTable 
                      condition=$item.condition
                      dialogWidth=$item.dialogWidth 
                      width=$item.width
                      style=$item.style
              }</td>
              {else}
                {webcontrol type=$item.type value=$item1[$key].value itemName=$item.name readonly=$item.readonly disabled=$item.disabled}
              {/if}
            {/foreach}
          </tr>  
          {/foreach}    
        </tbody>
      </table>
    </div>
  </div>
  </div>
</div>