<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Wuliao extends TMIS_Controller {
  var $_modelExample;
  var $fldMain; 
  // /构造函数
  function Controller_Jichu_Wuliao() {
    $this->_modelExample = &FLEA::getSingleton('Model_Jichu_Wuliao'); 

    $this->fldMain = array(
      'wuCode' => array('title' => '编号', "type" => "text", 'value' => '',),
      'wuName' => array('title' => '名称', 'type' => 'text', 'value' => '',),
      'guige' => array('title' => '规格', 'type' => 'text', 'value' => ''),
      'chengfen' => array('title' => '成分', 'type' => 'text', 'value' => ''),
      'wuliaoKind' => array('title' => '物料大类', 'type' => 'combobox', 'value' => '','options'=>$this->_modelExample->typeWuliaos()),
      'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
      'id' => array('type' => 'hidden', 'value' => ''),
    );

    $this->rules = array(
      'wuname'=>'required',
      // 'proCode'=>'required repeat'
    );
  }

  function actionRight() {
    $this->authCheck('15-7');
    FLEA::loadClass('TMIS_Pager');
    $arr = TMIS_Pager::getParamArray(array(
        'key' => '',
        ));
    $str = "select * from jichu_wuliao where 1";
    if ($arr['key'] != '') $str .= " and (wuCode like '%{$arr['key']}%'
                        or wuName like '%{$arr['key']}%')";

    $str .= " order by wuCode asc,wuName asc";
    $pager = &new TMIS_Pager($str);
    $rowset = $pager->findAll(); 
    // dump($rowset);exit;
    if (count($rowset) > 0) foreach($rowset as & $v) {
      $v['_edit'] = $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
    }
    
    
    $smarty = &$this->_getView();
    $smarty->assign('title', '选择物料');
    $arr_field_info = array("_edit" => '操作',
      'wuCode' => '编号',
      'wuName' => '名称',
      'guige'=>'规格',
      'chengfen' =>'成分',
      'wuliaoKind' =>'物料大类',
      'memo' =>'备注',
      );
    $smarty->assign('arr_field_info', $arr_field_info);
    $smarty->assign('arr_field_value', $rowset);
    $smarty->assign('arr_condition', $arr);
    $smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
    $smarty->display('TblList.tpl');
  } 

  function actionPopup(){
    $smarty = &$this->_getView();
    $smarty->assign('TreeUrl', url('Jichu_ProKind','TreeToProduct'));
    $smarty->assign('CenterUrl', $this->_url('Popup2'));
    $smarty->display('Popup/TreeProduct.tpl');
  }
  // **************************弹出产品信息 begin***************************
  function actionPopup2() {
    // dump($_GET);exit;
    FLEA::loadClass('TMIS_Pager');
    $kindId=$_GET['kindId']+0;
    TMIS_Pager::clearCondition();
    $arr = TMIS_Pager::getParamArray(array('key' => '',
        'key' => '',
        'kindId'=>$kindId,
        )); 
    
    $str = "select * from jichu_product where 1" . $sql;

    if ($arr['key'] != '') $str .= " and (proCode like '%{$arr['key']}%'
                        or proName like '%{$arr['key']}%'
                        or guige like '%{$arr['key']}%')";
    //大类id
    $str .= " and kindId='{$arr['kindId']}'";
    //排序
    $str .= " order by proCode asc,proName asc,guige asc"; //dump($str);exit;
    $pager = &new TMIS_Pager($str);
    $rowset = $pager->findAll();
    // dump($str);exit;
    if (count($rowset) > 0) foreach($rowset as &$v) {
      //查找产品大类名称
      $sql="select * from Jichu_ProKind where id='{$v['kindId']}'";
      $temp = $this->_modelExample->findBySql($sql);
      $v['kindName']=$temp[0]['kindName'];
    }
    $smarty = &$this->_getView();
    $smarty->assign('title', '选择产品');
    $arr_field_info = array(
      // "kindName" => "分类",
      "proCode" => "产品编码",
      "proName" => "品名",
      "guige" => array('text'=>"规格",'width'=>100),
      'unit'=>'单位',
      'memo'=>'备注',
    ); 
    
    $smarty->assign('arr_field_info', $arr_field_info);
    $smarty->assign('arr_field_value', $rowset);
    $smarty->assign('add_display', 'none');
    // $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
    $smarty->assign('arr_condition', $arr);
    $smarty->assign('sonTpl', "Popup/FrameInit.tpl");
    $smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $arr)));
    $smarty->display('Popup/CommonNew.tpl');
  }
  // **************************弹出产品信息 end***************************
  function actionAdd() {
    $smarty = &$this->_getView();
    $smarty->assign('fldMain', $this->fldMain);
    $smarty->assign('title', '产品编辑信息');
    $smarty->assign('rules',$this->rules);
    $smarty->assign('sonTpl','Jichu/ProductEdit.tpl');
    $smarty->display('Main/A1.tpl');
  }

  function actionEdit() {
    $row = $this->_modelExample->find($_GET['id']);
    $this->fldMain = $this->getValueFromRow($this->fldMain, $row);
    // dump($row);dump($this->fldMain);exit;
    $smarty = &$this->_getView();
    $smarty->assign('fldMain', $this->fldMain);
    $smarty->assign('rules',$this->rules);
    $smarty->assign('title', '产品大类编辑信息');
    // $smarty->assign('sonTpl','Jichu/ProductEdit.tpl');
    $smarty->assign('aRow', $row);
    $smarty->display('Main/A1.tpl');
  }

  function actionSave() {
    // 确保产品编码,品名,规格,颜色都存在
    $row = array();
    foreach($this->fldMain as $k=>&$v) {
      $name = $v['name']?$v['name']:$k;
      $row[$k] = $_POST[$name];
    }
    // dump($row);exit;
    $this->_modelExample->save($row);
    js_alert(null, 'window.parent.showMsg("保存成功")', $this->_url($_POST['fromAction']));
    exit;
  }

  //获取对应的物料信息和编码
  function actionGetProInfo(){
    $str="SELECT * from jichu_prokind where id='{$_POST['kindId']}'";
      $row=$this->_modelExample->findBySql($str);
      if($row){
        echo json_encode(array(
          "success"=>true,
          "kindCode"=>$row[0]["kindCode"],
          "kindName"=>$row[0]["kindName"],
          ));
        exit;
      }else{
        echo json_encode(array(
          "success"=>false,
          "msg"=>"没有此物料信息，请确认!"
          ));
        exit;
      }
      
  }
}

?>