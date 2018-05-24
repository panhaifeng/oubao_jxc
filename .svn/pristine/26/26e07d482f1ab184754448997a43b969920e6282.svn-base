<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtBtnCommon($name,$params){	
	$itemName = $params['itemName']?$params['itemName']:'BtnCommon';
	$url = $params['url']?$params['url']:'';
    $textFld = $params['textFld']?$params['textFld']:'按钮';    
	$html = "<a name='{$itemName}' id='{$itemName}' class='btn btn-sm btn-default' href='javascript:;' url='{$url}'>{$textFld}</a><input type='hidden' id='hide_{$itemName}' name='hide_{$itemName}' value>";
	return $html;
}
?>