<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Caiwu_Ys_GuozhangQt extends TMIS_Controller {

    function __construct() {

        $this->_kind = "其他过账";
        //model
        $this->_modelExample = &FLEA::getSingleton('Model_Caiwu_Ar_Guozhang');
        //搭建过账界面
        $this->fldMain  = array(
            'guozhangDate' => array('title' => '过账日期', "type" => "calendar", 'value' => date('Y-m-d')),
            'clientId' => array(
                'title' => '客户',
                'type' => 'Popup',
                'name' => 'clientId',
                'url'=>url('Jichu_Client','Popup'),
                'textFld'=>'compName',
                'hiddenFld'=>'id'
            ),
            'orderId' => array(
                'title' => '相关订单', 
                "type" => "Popup",
                'url'=>url('Trade_Order','Popup'),
                'textFld'=>'orderCode',
                'hiddenFld'=>'id',
                'dialogWidth'=>880
            ),
            // 'compName' => array('title' => '客户', 'type' => 'text', 'value' => ''),
            'money' => array('title' => '入账金额', 'type' => 'text', 'value' => '','addonEnd'=>'元'),
            'bizhong' => array('title' => '币种', 'type' => 'select', 'value' => '', 'optionType'=>'币种'),
            'huilv' => array('title' => '汇率', 'type' => 'text', 'value' => '1'),
            'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
            'id' => array('type' => 'hidden', 'value' => ''),
            // 'clientId' => array('type' => 'hidden', 'value' => ''),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            'kind' => array('type' => 'hidden', 'value' => $this->_kind),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'guozhangDate' => 'required',
            'clientId' => 'required',
            'money' => 'required number'
        );
    }

    /**
     * ps ：其他应收过账
     * Time：2015-10-13 09:11:03
     * @author shen
    */
    function actionAdd(){
        $this->authCheck('4-1-3');
        $smarty=& $this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('title', '过账信息编辑');
        $smarty->assign('sonTpl', 'Caiwu/Ys/OtherGuozhang.tpl');
        $smarty->display('Main/A1.tpl');
    }

    function actionRight(){
        $this->authCheck('4-1-4');
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            'orderCode'=>'',
            "dateFrom" => date('Y-m-01'), 
            "dateTo" => date('Y-m-d'),
            'clientId'=>'',
        )); 
        $sql="select x.*,o.orderCode,c.compName,k.chukuCode,
                p.proCode,p.proName,p.shazhi,p.chengfen,p.menfu,p.jingmi,p.weimi 
                from caiwu_ar_guozhang x
                left join trade_order o on o.id=x.orderId
                left join jichu_client c on c.member_id=x.clientId
                left join jichu_product p on p.proCode=x.productId
                left join cangku_chuku k on k.id=x.chukuId
                where 1";
        $sql.=" and x.guozhangDate >= '{$arr['dateFrom']}' and x.guozhangDate <= '{$arr['dateTo']}' and x.kind='$this->_kind'";
        if($arr['clientId']!='') $sql .=" and x.clientId='{$arr['clientId']}' ";
        if($arr['orderCode']!='') $sql .=" and o.orderCode like '%{$arr['orderCode']}%'";
        $sql.=" order by guozhangDate";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as & $v) {
            $v['_edit']=$this->getEditHtml($v['id']);
            $v['_edit'].=' '.$this->getRemoveHtml($v['id']);
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            $v['cnt']=round($v['cnt'],3);
            $v['danjia']=round($v['danjia'],3);
            $v['money']=round($v['money'],2);
            $v['moneyRMB']=round($v['money']*$v['huilv'],2);
            $v['huilv']=round($v['huilv'],2);
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];
            //显示币种
            $this->getBizhong($v['bizhong']);
        }
        $arrFieldInfo = array(
            "_edit"=>'操作',
            "orderCode" => '订单号',
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
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
            if($_GET['export']==1){
                $this->_exportList(array('title'=>$title),$smarty);
            }
        $smarty->display('TblList.tpl');
    }
    function actionEdit(){
        $row = $this->_modelExample->find($_GET['id']);
        //订单编号
        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $aRow = $_modelOrder->find($row['orderId']);
        $row['orderCode']=$aRow['orderCode'];
        //客户
        $this->_modelClient = &FLEA::getSingleton('Model_Jichu_Client');
        $rowset = $this->_modelClient->find(array('member_id' => $row['clientId']));
        $row['compName']= $rowset['compName'];

        $this->fldMain = $this->getValueFromRow($this->fldMain, $row);
        $this->fldMain['orderId']['text']=$row['orderCode'];
        $this->fldMain['clientId']['text']=$row['compName'];
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