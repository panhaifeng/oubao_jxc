<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_kuqu extends TMIS_Controller {
    var $_modelExample;
    var $fldMain; 
    // /构造函数
    function __construct() {
        $this->_modelExample = &FLEA::getSingleton('Model_Jichu_Kuqu'); 
        $this->fldMain = array(
            'kuquName'=>array('title' => '库位名称', "type" => "text", 'value' => ''),
            'memo' => array('title' => '备注', "type" => "textarea", 'value' => ''),
            'id' => array('type' => 'hidden', 'value' => ''), 
        );
        $this->rules = array(

        );
    }

    function actionRight() {
        $this->authCheck('15-5');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'key' => '',
        ));
        $str = "select * from jichu_kuqu where 1";
        if ($arr['key'] != '') $str .= " and kuquName like '%{$arr['key']}%'";
        $str.=" order by id desc";
        $pager = &new TMIS_Pager($str);
        $rowset = $pager->findAll(); 
        if (count($rowset) > 0) foreach($rowset as &$v) {
            $v['_edit'] = $this->getEditHtml($v['id']);
            $v['_edit'] .= "&nbsp;&nbsp;<a href='".$this->_url('Remove',array(
                            'id'=>$v['id'],
                            'kuquName'=>$v['kuquName'],
                            'fromAction'=>$_GET['action'],
                            'fromController'=>$_GET['controller'],
                ))."' onclick='return confirm(\"您确认要删除吗?\")'><span class='glyphicon glyphicon-trash text-danger' ext:qtip='删除库位'></span></a>";
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', '库位');
        $arr_field_info = array(
            "_edit" => '操作',
            // 'kuweiName'=>array('text'=>'库位','width'=>180),
        );
        foreach($this->fldMain as $k=>& $v) {
            if($v['type'] == 'hidden') continue;
            $arr_field_info[$k] = $v['title'];
        }
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset); 
        // $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    } 

    function actionAdd() {
        // dump($this->getCangku());exit;
        $kuquName=array();
        // $temp=$this->getCangku();
        foreach ($temp as & $value) {
            $kuquName[]=array('text'=>$value,'value'=>$value);
        }
        $this->fldMain['kuquName']['options']=$kuquName;
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules',$this->rules);
        $smarty->assign('title', '库位信息');
        $smarty->display('Main/A1.tpl');
    }

    function actionEdit() {
        $kuquName=array();
        $temp=$this->getCangku();
        foreach ($temp as & $value) {
            $kuquName[]=array('text'=>$value,'value'=>$value);
        }
        $this->fldMain['kuquName']['options']=$kuquName;
        $row = $this->_modelExample->find(array('id' => $_GET['id']));
        $this->fldMain = $this->getValueFromRow($this->fldMain, $row); 
        // dump($row);dump($this->fldMain);exit;
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('title', '库位信息');
        $smarty->assign('aRow', $row);
        $smarty->display('Main/A1.tpl');
    }

    function actionSave() {
        
        $_POST['kuquName']=$_POST['kuquName'].$_POST['kwName'];
        if (!$_POST['kuquName']) {
            js_alert('请输入库位名!', 'window.history.go(-1)');
            exit;
        }


        // 产品编码不重复
        $sql = "select count(*) cnt from jichu_kuqu where kuquName='{$_POST['kuquName']}' and id<>'{$_POST['id']}'";
        $_rows = $this->_modelExample->findBySql($sql);
        if ($_rows[0]['cnt'] > 0) {
            js_alert('库位重复!',  'window.history.go(-1)');
            exit;
        }

        $this->_modelExample->save($_POST);
        js_alert(null, 'window.parent.showMsg("保存成功")', $this->_url($_POST['fromAction']));
        exit;
    }

    function actionRemove(){
        $sql="SELECT count(*)as cnt 
            from madan_db
            where kuquId={$_GET['id']} and status<>'finish' ";
        $Num=$this->_modelExample->findBySql($sql);
        if($Num[0]['cnt']>0){
            js_alert('该库位已有入库记录!',null,$this->_url('right'));
            exit;
        }
        parent::actionRemove();
    }

}

?>