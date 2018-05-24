<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtSpan($name,$params){	
	$itemName 	= $params['itemName'];
    $value = $params['value']?$params['value']:'';
    $html= "<span style='line-height:30px;' name='{$itemName}'>{$value}</span>";
	return $html;	
}
?>