<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Product extends TMIS_Controller {
  var $_modelExample;
  var $fldMain; 
  // /构造函数
  function Controller_Jichu_Product() {
    $this->_modelExample = &FLEA::getSingleton('Model_Jichu_Product'); 

    $this->fldMain = array(
      'wuliaoKind' => array('title' => '物料大类', 'type' => 'combobox', 'value' => '','options'=>$this->_modelExample->typeWuliaos()),
      'proCode' => array('title' => '花型六位号', "type" => "text", 'value' => '',),
      'proName' => array('title' => '品名', 'type' => 'text', 'value' => '',),
      'jingmi' => array('title' => '经密', 'type' => 'text', 'value' => ''),
      'weimi' => array('title' => '纬密', 'type' => 'text', 'value' => ''),
      'shazhi' => array('title' => '纱支', 'type' => 'text', 'value' => ''),
      'menfu' => array('title' => '门幅', 'type' => 'text', 'value' => ''),
      'color' => array('title' => '颜色', 'type' => 'text', 'value' => ''),
      'zhengli' => array('title' => '整理方式', 'type' => 'combobox', 'value' => '','options'=>$this->_modelExample->typeZhenglis()),
      'kezhong' => array('title' => '克重', 'type' => 'text', 'value' => ''),
      'chengfen' => array('title' => '成分', 'type' => 'text', 'value' => ''),
      'zuzhi' => array('title' => '组织大类', 'type' => 'combobox', 'value' => '','options'=>$this->_modelExample->typeZuzhis()),
      'danjia' => array('title' => '基准价格', 'type' => 'text', 'value' => ''),
      'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
      'id' => array('type' => 'hidden', 'value' => ''),
    );

    $this->rules = array(
      'proName'=>'required',
      'proCode'=>'required repeat'
    );
  }

  /**
   * ps ：进销存同步ec中的产品信息
   * Time：2015/09/14 16:03:32
   * @author jiang
  */
  function actionTongbu(){
    // sleep(10);
    //先查找客户档案的最新修改时间  将时间戳传递过去
    $sql="select dt from jichu_product where 1 order by dt desc limit 1";
    $ret=$this->_modelExample->findBySql($sql);

    $last_modify = strtotime($ret[0]['dt'])+0;
    
    //发出请求
    set_time_limit(0);

    $obj_api = FLEA::getSingleton('Api_Request');
    $r = $obj_api->callApi(array(
            'method'=>'apioubao.erp.response.get_product_data',
            'params'=>array(
              'last_modify'=>$last_modify
          )
        ));

        $ret = json_decode($r,true);
        
        // dump($ret);exit;
        //判断是否连接成功
        $_data=$ret;
        if($_data['rsp']=='fail'){
          js_alert(
            '连接失败！',
            "window.parent.location.href=window.parent.location.href;",
            $this->_url('right')
        );
        exit;
        } 
        // dump($_data['data']);exit;
        //开始将ec中的数据保存到进销存中
        foreach ($_data['data'] as $k => & $v) {
          $_arr=array(
              'proCode'=>$k,
              'proName'=>$v['name'],
              'huaxinghao'=>$v['props']['花型号'].'',
              'colornum'=>$v['props']['款号'].'',
              'colorsnum'=>$v['props']['颜色号'].'',
              'sFinishingMethod'=>$v['props']['sFinishingMethod'].'',
              'Content'=>$v['props']['Content'].'',
              'pinming'=>$v['props']['品名'].'',
              'menfu'=>$v['props']['门幅'].'',
              'kezhong'=>$v['props']['克重'].'',
              'color'=>$v['color'].'',
              'zhengli'=>$v['props']['整理方式'].'',
              'chengfen'=>$v['props']['成分'].'',
              'shazhi'=>$v['props']['纱支规格'].'',
              'jingmi'=>$v['props']['经密'].'',
              'weimi'=>$v['props']['纬密'].'',
              'wuliaoKind'=>$v['wuliaoKind'].'',
              'zuzhi'=>$v['zuzhi'].'',
              'danjia'=>$v['danjia']+0,
              'memo'=>$v['brief'],
        );
        $row=$this->_modelExample->find(array('proCode'=>$k));
        $row['id']>0 && $_arr['id'] = $row['id'];

        $arr[] = $_arr;
        }

        // dump($arr);exit;
        $this->_modelExample->saveRowset($arr);

        //删除ec中已经删除的数据   暂时不考虑
        // $bn=substr($_data['data']['bn'],0,strlen($_data['data']['bn'])-1);
        // $sqlmid="delete from jichu_product where proCode not in ({$bn})";
        // $this->_modelExample->execute($sqlmid);
        
        js_alert(
          null,
          "window.parent.showMsg('同步成功');window.location.href=window.location.href;",
          $this->_url('right')
      );
      exit;
  }

  function actionRight() {
    $this->authCheck('15-9');
    FLEA::loadClass('TMIS_Pager');
    $arr = TMIS_Pager::getParamArray(array(
        'key' => '',
        ));
    $str = "select * from jichu_product where 1";
    if ($arr['key'] != '') $str .= " and (proCode like '%{$arr['key']}%'
                        or proName like '%{$arr['key']}%')";

    $str .= " order by proCode asc,proName asc";
    $pager = &new TMIS_Pager($str);
    $rowset = $pager->findAll(); 
    // dump($rowset);exit;
    if (count($rowset) > 0) foreach($rowset as & $v) {
      $v['_edit'] = $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
    }
    
    
    $smarty = &$this->_getView();
    $smarty->assign('title', '选择产品');
    $arr_field_info = array(
      // "_edit" => '操作',
      'proCode' => '花型六位号',
      // 'huaxinghao' => '花型号',
      'colornum' => '款号',
      'colorsnum' => '颜色号',
      'sFinishingMethod' => array('width'=>'130','text'=>'sFinishingMethod'),
      'pinming' => '品名',
      'proName' => '产品名称',
      'jingmi' => '经密',
      'weimi' => '纬密',
      'shazhi' => '纱支',
      'menfu' => '门幅',
      'color' => '颜色',
      'zhengli' =>'整理方式',
      'kezhong' =>'克重',
      'chengfen' =>'成分',
      'Content' =>'成分(英文)',
      'wuliaoKind' =>'物料大类',
      'zuzhi' =>'组织大类',
      'danjia' =>'基准价格',
      'memo' =>'备注',
      );
    $smarty->assign('arr_field_info', $arr_field_info);
    $smarty->assign('arr_field_value', $rowset);
    $smarty->assign('arr_condition', $arr);
    $smarty->assign('add_display', 'none');
    $other_url="<a href='".$this->_url('Tongbu',array(
      'id'=>$v['id'],
      ))."' onclick='layer.msg(\"玩命同步中，不要操作，以免中断\",100)'>同步商城商品</a>";
    $smarty->assign('other_url', $other_url);
    $smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
    $smarty->display('TblList.tpl');
  } 
  // **************************弹出产品信息 begin***************************
  function actionPopup() {
    // dump($_GET);exit;
    FLEA::loadClass('TMIS_Pager');
    $arr = TMIS_Pager::getParamArray(array(
        'key' => '',
        )); 

    $str = "SELECT x.*,
          sum(y.cnt)as cnt
          from jichu_product x 
          left join cangku_kucun y on x.proCode=y.productId  
          where  1";
    if ($_GET['kuweiId']!='') $str .=" and y.kuweiId='{$_GET['kuweiId']}'";
    if ($arr['key'] != '') $str .= " and (proCode like '%{$arr['key']}%'
                        or proName like '%{$arr['key']}%')";
    //排序
    $str .= " group by proCode"; 
    $pager = &new TMIS_Pager($str);
    $rowset = $pager->findAll();
    // dump($str);exit;
    // if (count($rowset) > 0) foreach($rowset as &$v) {
     
    // }
    $smarty = &$this->_getView();
    $smarty->assign('title', '选择产品');
    $arr_field_info = array(
      'proCode' => '花型六位号',
      'proName' => '品名',
      'jingmi' => '经密',
      'weimi' => '纬密',
      'shazhi' => '纱支',
      'menfu' => '门幅',
      'zhengli' =>'整理方式',
      'kezhong' =>'克重',
      'chengfen' =>'成分',
      'cnt'=>'库存',
      'wuliaoKind' =>'物料大类',
      'zuzhi' =>'组织大类',
      'danjia' =>'基准价格',
      'memo' =>'备注',
    ); 
    if($_GET['kuweiId']=='') {
        unset($arr_field_info['cnt']);
    }
      
    $smarty->assign('arr_field_info',$arr_field_info);
    $smarty->assign('arr_field_value',$rowset);
    $smarty->assign('add_display','none');
    $smarty->assign('arr_condition',$arr);
    $smarty->assign('page_info',$pager->getNavBar($this->_url('Popup',$arr)));
    $smarty-> display('Popup/CommonNew.tpl');
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