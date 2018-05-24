<?php
/*
*2014-3-26 by jeff : checkbox
*/
function _ctlBtCheckbox($name,$params){ 
    $itemName   = $params['itemName'];
    $title  = $params['title'];
    $value = $params['value'];
    $checked = $params['checked'] ?'checked':'';
    
    //$selected = 1;
    // $model = $params['model'];
    
    // if($model!='') {
    //     if(count($opts)==0) { //根据model取得所有的基础档案数据
    //         $m = & FLEA::getSingleton($model);
    //         $rowset = $m->findAll();
    //         foreach($rowset as & $v) {
    //             $opts[] = array('text'=>$v[$m->primaryName],'value'=>$v[$m->primaryKey]);
    //         }
    //     }       
    // }
    // $html = "<select name='{$itemName}' id='{$itemName}' class='form-control'>";
    // $html .= "<option value=''>请选择</option>";
    // foreach($opts as &$v) {
    //  $html.= "<option value='{$v['value']}'";
    //  if($selected==$v['value']) $html.=" selected ";
    //  $html.=">{$v['text']}</option>";
    // }
    // $html .= "</select>";
    // $html = $value;

    $html = "<div class=\"checkbox\">
    <label>
      <input type=\"checkbox\" name=\"{$itemName}\" id=\"{$itemName}\" value=\"{$value}\" {$checked}>{$title}
    </label>
  </div>";
    return $html;   
}
?>