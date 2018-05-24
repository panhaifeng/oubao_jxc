<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Caiwu_Ys_Income extends TMIS_Controller {

    function __construct() {

        //model
        $this->_modelExample = &FLEA::getSingleton('Model_Caiwu_Ar_Income');
        //搭建过账界面
        $this->fldMain = array(
            'runningNumber' => array('title' => '收款流水号', "type" => "text", 'readonly' => true,'value' =>'自动生成'),
            'incomeType' => array('title' => '收款类型', 'type' => 'select', 'value' => '', 'optionType'=>'收款类型','name'=>'incomeType'),
            'shouhuiDate' => array('title' => '收款日期', "type" => "calendar", 'value' => date('Y-m-d')),
            'clientId' => array(
                'title' => '客户',
                'type' => 'Popup',
                'name' => 'clientId',
                'url'=>url('Jichu_Client','Popup'),
                'textFld'=>'compName',
                'hiddenFld'=>'id'
            ),
            //先隐藏订单
            // 'orderId' => array(
            //     'title' => '相关订单', 
            //     "type" => "Popup",
            //     'url'=>url('Trade_Order','Popup'),
            //     'textFld'=>'orderCode',
            //     'hiddenFld'=>'orderId',
            //     'dialogWidth'=>880
            // ),
            'money' => array('title' => '收款金额', 'type' => 'text', 'value' => '','name'=>'money'),
            'bizhong' => array('title' => '币种', 'type' => 'select', 'value' => '', 'optionType'=>'币种','name'=>'skbizhong'),
            'huilv' => array('title' => '汇率', 'type' => 'text', 'value' => '1'),
            'bankId' => array('title' => '银行账户', 'type' => 'select', 'optionType' => 'bankId'),
            'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
            'id' => array('type' => 'hidden', 'value' => '','name'=>'incomeId'),
            'type' => array('title' => '收款方式', 'type' => 'hidden', 'value' => '其它收款'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME'],'title'=>'制单人','readonly'=>true),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'shouhuiDate' => 'required',
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
        $this->authCheck('4-1-7');
        $smarty=& $this->_getView();
        $smarty->assign('fldMain', $this->fldMain);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('title', '收款信息编辑');
        $smarty->assign('sonTpl', 'Caiwu/Ys/OtherGuozhang.tpl');
        $smarty->display('Main/A1.tpl');
    }

    function actionRight(){
        $this->authCheck('4-1-8');
       FLEA::loadclass('TMIS_Pager');
        $arr=TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
            'dateTo'=>date('Y-m-d'),
            'clientId'=>'',
            'zftype'=>'',
            'bankId'=>'',
            'runningNumber'=>'',
            'incomeType'=>'',
            // 'orderCode'=>'',
            'no_edit'=>'',
        ));
        // dump($_POST);dump($arr);
        $sql="select x.*,a.compName,j.itemName as bankName
            from caiwu_ar_income x
            left join jichu_client a on a.member_id=x.clientId
            left join trade_order q on q.id=x.orderId
            left join jichu_bank j on x.bankId = j.id
            where 1";
        if($arr['clientId']!='')$sql.=" and x.clientId='{$arr['clientId']}'";
        // if($arr['clientName']!='')$sql.=" and a.compName like '%{$arr['clientName']}%'";
        if($arr['bankId']!='')$sql.=" and j.id = '{$arr['bankId']}'";
        if($arr['dateFrom']!=''){
            $sql.=" and x.shouhuiDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.shouhuiDate <='{$arr['dateTo']}'";
        }
        if($arr['runningNumber']!=''){
            $sql.=" and x.runningNumber like '%{$arr['runningNumber']}%'";
        }
        if($arr['zftype']!=''){
            //2017-3-6 by jeff,支付可能以alipay被保存(预存款)
            if($arr['zftype']=='支付宝') {
                $sql.=" and (x.type like '%{$arr['zftype']}%' or x.type='alipay')";
            } else {
                $sql.=" and x.type like '%{$arr['zftype']}%'";
            }
        }
        if($arr['incomeType']!=''){
            $sql.=" and x.incomeType='{$arr['incomeType']}'";
        }
        // if($arr['orderCode']!=''){
        //     $sql.=" and q.orderCode like '%{$arr['orderCode']}%'";
        // }
        $sql.=" order by x.shouhuiDate desc";
        // dump($sql);exit;
        if($_GET['export']==1){
            $page=& new TMIS_Pager($sql);
            $rowset=$page->findAll();
        }elseif ($_GET['export']==2) {
            $rowset = $this->_modelExample->findBySql($sql);
        }elseif (empty($_GET['export'])) {
            $page=& new TMIS_Pager($sql);
            $rowset=$page->findAll();
        }
        // dump($rowset);exit;
        //dump($rowset);exit;
        if(count($rowset)>0)foreach($rowset as & $v) {
            if($v['type']=='其它收款') {                
                $v['_edit'].=$this->getEditHtml($v['id']).'&nbsp;&nbsp;'.$this->getRemoveHtml($v['id']);
                //增加审核按钮
                $v['shenhe']=$this->getShenheHtml('其他收款',$v['id']);
                // $strChk = $v['isChecked'] ? ' checked=true':'';
                // $v['shenhe'] .= "<input type='checkbox' class='chkShenhe' {$strChk} value='{$v['id']}'/>";
            }
            else $v['_edit']=" ";
            $v['money']=round($v['money'],3);
            //折合人民币
            $v['moneyRmb']=round($v['money']*$v['huilv'],3);
            //显示币种
            $this->getBizhong($v['bizhong']);
        }
        // dump($rowset);exit;
        foreach ($rowset as $key => $value) {
            $rowset[$key]['number'] = $key+1;
        }
        $rowset[] = $this->getHeji($rowset, array('money','moneyRmb'), $_GET['no_edit']==1?'shouhuiDate':'_edit');
        
        $arr_field_info=array(
            'number' => array('text'=>'编号','width'=>40),
            '_edit'=>array('text'=>'操作','width'=>60),
            'shenhe'=>array('text'=>'审核','width'=>60),
            'runningNumber'=>'收款流水号',
            // 'orderCode'=>'订单号',
            'shouhuiDate'=>'收款日期',
            'compName'=>array('text'=>'客户','width'=>160),
            'money'=>'金额',
            'moneyRmb'=>'金额(RMB)',
            'bizhong'=>'币种',
            'huilv'=>'汇率',
            'type'=>'收款方式',
            'incomeType'=>'收款类型',
            'bankName'=>'收款账户',
            'memo'=>array('text'=>'备注','width'=>160),
        );
        

        $smarty=& $this->_getView();
        //2016-12-19 by jeff,增加审核按钮
        $smarty->assign('sonTpl', 'Caiwu/Ys/_income.tpl');
        $smarty->assign('sub_field',"Fapiao");
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        if($_GET['export']==1){
            $smarty->assign('page_info',$page->getNavBar($this->_url($_GET['action'],$arr)).$note);
        }elseif ($_GET['export']==2) {
            unset($arr_field_info['shenhe']);
            $smarty->assign('arr_field_info',$arr_field_info);
        }elseif (empty($_GET['export'])) {
            $smarty->assign('page_info',$page->getNavBar($this->_url($_GET['action'],$arr)).$note);
        }
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
        $smarty->assign('fn_export1',$this->_url($_GET['action'],array('export'=>2)));
        if($_GET['export']==1){
            $this->_exportList(array('title'=>$title),$smarty);
        }elseif ($_GET['export']==2) {
            $this->_exportList(array('title'=>$title),$smarty);
        }
        $smarty->display('TblList.tpl');
    }
      function actionEdit(){
            $row = $this->_modelExample->find($_GET['id']);
            //订单编号
            $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
            $aRow = $_modelOrder->find($row['orderId']);
            //客户
            $this->_modelClient = &FLEA::getSingleton('Model_Jichu_Client');
            $rowset = $this->_modelClient->find(array('member_id' => $row['clientId']));

            $this->fldMain = $this->getValueFromRow($this->fldMain, $row);
            // $this->fldMain['orderId']['text']=$aRow['orderCode'];
            $this->fldMain['clientId']['text']=$rowset['compName'];
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
        // dump($arr);exit;
        $this->_modelExample->save($arr);

        $from=$_POST['fromAction']!='' ? $_POST['fromAction'] : 'add';
        js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($from));
  }

  /**
   * @desc ：收款查询中点击checkbox，改变审核标记
   * @author jeff #TM_TH_DTTM
   * @param 参数类型
   * @return 返回值类型
  */
  function actionAjaxShenhe() {
    if(!$_POST['id'] || !isset($_POST['todo'])) {
        echo json_encode(array(
            'succ'=>false,
            'msg'=>'参数出错'
        ));
        exit;
    }

    $row = array('id'=>$_POST['id'],'isChecked'=>$_POST['todo']=='true'?1:0);
    $ret = $this->_modelExample->update($row);
    echo json_encode(array(
        'succ'=>true,
        'msg'=>$_POST
    ));
    exit;
  }
}

?>