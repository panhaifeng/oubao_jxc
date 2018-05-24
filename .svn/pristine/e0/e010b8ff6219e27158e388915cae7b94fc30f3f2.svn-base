<?php
/*
*2014-3-26 by jeff : select需要被bootstrap控件使用，所以这里改动为select1
*/
function _ctlBtStar($name,$params){	
	$itemName 	= $params['itemName'];
	$readonly = $params['readonly']?"readonly":'';
	$disabled = $params['disabled']?"disabled":'';
    $value = $params['value']?$params['value']:'';
    $style = $params['style']?$params['style']:'';
    $style!='' && $style="style='{$style}'";

    $step = $params['step'];
    $max = $params['max'];
    // dump($params);//exit;
    $html = "<input type='number' class='rating' step='{$step}' max='{$max}' data-size='xs' name='{$itemName}' id='{$itemName}' value=\"{$value}\" {$disabled} {$readonly} />";    
	return $html;	
}
?>