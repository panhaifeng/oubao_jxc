<?php
/*
*2014-3-26 by jeff : 表格中的日期控件会显示异常，故需要独立出来一个控件
*/
function _ctlBtCalendarInTbl($name,$params){	
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';
    $html = '<input type="text" style="width:100px;" name="'.$itemName.'" class="form-control input-sm" id="'.$itemName.'" value="'.$value.'"  onClick="calendar()">';
 //    $html = '<div class="input-group input-group-sm">';
	// $html .= "<input type='text' class='form-control' name='{$itemName}' id='{$itemName}' value='{$value}' {$disabled} {$readonly}  onClick='calendar()' />";
	// //加上日期图标
	// $html .= '<span class="input-group-btn">
 //        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
 //                <span class="caret"></span>
 //                <span class="sr-only">Toggle Dropdown</span>
 //              </button>
 //      </span>';
	// $html .= "</div>";
	return $html;	
}
?>