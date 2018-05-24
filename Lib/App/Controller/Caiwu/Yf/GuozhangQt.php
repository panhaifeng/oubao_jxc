<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Caiwu_Yf_GuozhangQt extends TMIS_Controller {

    function __construct() {

        $this->_kind = "其他过账";
        //model
        $this->_modelExample = &FLEA::getSingleton('Model_Caiwu_Yf_Guozhang');
        //搭建过账界面
        $this->fldMain  = array(
            'guozhangDate' => array('title' => '过账日期', "type" => "calendar", 'value' => date('Y-m-d')),
            'supplierId' => array(
                'title' => '对象',
                'type' => 'Popup',
                'name' => 'supplierId',
                'url'=>url('Jichu_Supplier','PopupAll'),
                'textFld'=>'compName',
                'hiddenFld'=>'id'
            ),
            'money' => array('title' => '入账金额', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
            'bizhong' => array('title' => '币种', 'type' => 'select', 'value' => '', 'optionType'=>'币种'),
            'huilv' => array('title' => '汇率', 'type' => 'text', 'value' => '1'),
            'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
            'id' => array('type' => 'hidden', 'value' => ''),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            'kind' => array('type' => 'hidden', 'value' => $this->_kind),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'guozhangDate' => 'required',
            'supplierId' => 'required',
            'money' => 'required number'
        );
    }

    /**
     * ps ：其他应付
     * Time：2015/10/22 15:26:09
     * @author liuxin
    */
    function actionAdd(){
        $this->authCheck('4-2-5');
        $smarty=& $this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('title', '过账信息编辑');
        $smarty->assign('sonTpl', 'Caiwu/Ys/OtherGuozhang.tpl');
        $smarty->display('Main/A1.tpl');
    }

    function actionRight(){
        $this->authCheck('4-2-6');
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'), 
            "dateTo" => date('Y-m-d'),
            'supplierIds'=>'',
        )); 
        $sql="select x.*,c.compName
                from caiwu_yf_guozhang x
                left join jichu_supplier c on c.id=x.supplierId
                where 1";
        $sql.=" and x.guozhangDate >= '{$arr['dateFrom']}' and x.guozhangDate <= '{$arr['dateTo']}' and x.kind='$this->_kind'";
        if($arr['supplierIds']!='') $sql .=" and x.supplierId='{$arr['supplierIds']}' ";
        $sql.=" order by guozhangDate";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as & $v) {
            $v['money']=round($v['money'],2);
            $v['moneyRMB']=round($v['money']*$v['huilv'],2);
            $v['_edit']=$this->getEditHtml($v['id']);
            $v['_edit'].=' '.$this->getRemoveHtml($v['id']);
            //显示币种
            $this->getBizhong($v['bizhong']);
        }
        $arrFieldInfo = array(
            "_edit"=>'操作',
            "guozhangDate"=>'过账日期',
            "kind" =>'类型',
            "compName" => '客户',
            "money" => '金额',
            "moneyRMB" => '金额(RMB)',
            "bizhong" => '币种',
            "huilv" => '汇率',
            "memo" => '备注',
            );

        $smarty = &$this->_getView();
        $smarty->assign('title', '收发存报表'); 
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }
    function actionEdit(){
        $row = $this->_modelExample->find($_GET['id']);
        //供应商
        $this->_modelSupplier = &FLEA::getSingleton('Model_Jichu_Supplier');
        $rowset = $this->_modelSupplier->find(array('id' => $row['supplierId']));
        $row['compName']= $rowset['compName'];

        $this->fldMain = $this->getValueFromRow($this->fldMain, $row);
        $this->fldMain['supplierId']['text']=$row['compName'];
        // dump($row);dump($this->fldMain);exit;
        $smarty = &$this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules',$this->rules);
        $smarty->assign('aRow', $row);
        $smarty->assign('title', '过账信息编辑');
        $smarty->assign('sonTpl', 'Caiwu/Ys/OtherGuozhang.tpl');
        $smarty->display('Main/A1.tpl');
    }
  
    function actionSave(){
        // dump($_POST);exit;
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $arr[$k] = $_POST[$name];
        }
        $this->_modelExample->save($arr);

        $from=$_POST['fromAction']!='' ? $_POST['fromAction'] : 'add';
        js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($from));
  }
}

?>