<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtBtnSel($name,$params){	
	$itemName 	= $params['itemName'];	
    $value = $params['value']?$params['value']:'';
    $url = $params['url']?$params['url']:'';
    $title = $params['title']?$params['title']:'获取信息'; 
	$html = '
<button type="button" class="btn btn-default btn-sm" id="btnSel" url="'.$url.'">
  <span class="glyphicon glyphicon-plus-sign"></span> '.$title.'
</button>';
	return $html;	
}
?>