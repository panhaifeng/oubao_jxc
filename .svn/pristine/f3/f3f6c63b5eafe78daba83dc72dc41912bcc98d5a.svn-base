<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtCalendar($name,$params){	
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';
    $inTable =  $params['inTable']?$params['inTable']:0;
    // <input type="text" name="{$item.name|default:$key}" class="form-control" id="{$item.name|default:$key}" value="{$item.value}"  onClick="calendar()">
    $html = '<div class="input-group input-group-sm">';
	$html .= "<input type='text' class='form-control input-sm' name='{$itemName}' id='{$itemName}' value='{$value}' {$disabled} {$readonly}  onClick='calendar()' />";
	//加上日期图标
	$html .= '<span class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1" id="btnCalendar">
                <span class="glyphicon glyphicon-calendar"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
      </span>';
	$html .= "</div>";
	if($inTable) $html= "<div style='width:120px;'>
    {$html}
    </div>";
	return $html;	
}
?>