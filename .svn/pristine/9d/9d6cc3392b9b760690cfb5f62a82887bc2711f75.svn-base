<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Xianhuo_Xsck');
class Controller_Cangku_Dahuo_Xsck extends Controller_Cangku_Xianhuo_Xsck{

    function __construct() {
        parent::__construct();
        
        $this->_cangkuName = __CANGKU_3;

        $this->_kind = "销售出库";

        $this->_head = "XSCK";

        //出库主信息
        $this->fldMain = array(
            'chukuCode' => array(
                'title' => '出库单号', 
                "type" => "text", 
                'readonly' => true,
                'value' => $this->_getNewCode($this->_head,'cangku_chuku','chukuCode')
            ),
            'chukuDate' => array('title' => '出库日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'kuweiId' => array(
                'title' => '库位',
                'type' => 'select', 
                'value' => '',
                'name'=>'kuweiId',
                'model'=>'Model_Jichu_Kuwei',
                'condition'=>'ckName="'.$this->_cangkuName.'"'
            ),
            'ship_name' => array('title'=>'收货人','type' => 'text', 'value' =>''),
            'ship_addr' => array('title'=>'收货地址','type' => 'text', 'value' =>''),
            'ship_tel' => array('title'=>'收货电话','type' => 'text', 'value' =>''),
            'ship_mobile' => array('title'=>'收货人手机','type' => 'text', 'value' =>''),
            'logi_no' => array('title'=>'物流单号','type' => 'text', 'value' =>''),
            'shipping' => array('title'=>'配送方式','type' => 'select', 'options' =>''),
            'corp_name' => array('title'=>'物流公司','type' => 'select', 'options' =>''),
            'clientName' => array('title'=>'客户名称','type' => 'text', 'value' =>'','readonly'=>true),
            'kind' => array('title'=>'出库类型','type' => 'text', 'value' => $this->_kind,'readonly'=>true),
            'id' => array('type' => 'hidden', 'value' => '','name'=>'mainId'),
            'clientId' => array('type' => 'hidden', 'value' => '','name'=>'clientId'),
            'isGuozhang' => array('type' => 'hidden', 'value' => '0','name'=>'isGuozhang'),
            'cangkuName' => array('type' => 'hidden', 'value' => $this->_cangkuName,'name'=>'cangkuName'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            'wuliuInfo' => array('type' => 'hidden', 'value' => ''),
        );

        //子信息
        $this->headSon = array(
            '_edit' => array('type' => 'BtBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'pihao'=>array('type'=>'BtText',"title"=>'批号','name'=>'pihao[]'),
            'productId'=>array('type'=>'BtText',"title"=>'花型六位号','name'=>'productId[]','readonly'=>true),
            'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
            'proXinxi'=>array('type'=>'BtText',"title"=>'产品信息','name'=>'proXinxi[]','readonly'=>true,'colmd'=>3),
            // 'Madan' => array('type' => 'btBtnMadan', "title" => '配货单', 'name' => 'Madan[]'),
            'cnt' => array('type' => 'BtText', "title" => '本次数量', 'name' => 'cnt[]'),
            'unit' => array('type' => 'BtText', "title" => '单位', 'name' => 'unit[]','readonly'=>true,'value'=>'M'),
            /*'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'M','value'=>'M'),
                    array('text'=>'Y','value'=>'Y')
                )),*/
            
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'orderId' => array('type' => 'BtHidden', 'name' => 'orderId[]'),
            'ord2proId' => array('type' => 'BtHidden', 'name' => 'ord2proId[]'),
            'peihuoId' => array('type' => 'BtHidden', 'name' => 'peihuoId[]'),
            'planId' => array('type' => 'BtHidden', 'name' => 'planId[]'),
        );

        //表单元素的验证规则定义
        $this->rules = array(
            'kuweiId'=>'required',
            // 'shipping'=>'required',
        );

        $this->sonTpl = array('Cangku/Xianhuo/xsck_madan.tpl');
    }

    function actionAdd(){
        //配货单id，如果传递过来的配货id为1,2,3这样的多条数据，可以支持多条数据一起出库，
        // 条件：客户相同，发货地址方式相同
        $planId = (int)$_GET['id'];
        
        //出库单的数据
        $_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');
        $_condotion['in()']=explode(',',$planId);
        $plan = $_modelPlan->findAll($_condotion);
        // dump($plan);exit;

        //查找订单明细信息与订单主信息
        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $_modelOrder->clearLinks();
        $_p = current($plan);
        $_order = $_modelOrder->find($_p['orderId']);
        // dump($_p);exit;

        //查找客户信息
        $sql = "select if(compName!='',compName,compCode) as compName from jichu_client where member_id = '{$_order['clientId']}'";
        $_client = $_modelOrder->findBySql($sql);
        $_client = $_client[0];
        // dump($_client);exit;


        //同步EC数据到进销存
        $_wuliu = $_modelPlan->getWuliuFromEc();
        // dump($_wuliu);exit;
        //配送方式
        $_dlytype = array();
        $_dlytype_info = array();
        foreach ($_wuliu['data']['dlytype'] as $key => & $v) {
            $_dlytype[] = array('text'=>$v['dt_name'],'value'=>$v['dt_name']);
            $_dlytype_info[$v['dt_name']]=$v['dt_id'];
        }

        //物流公司
        $_dlycorp = array();
        $_dlycorp_info = array();
        foreach ($_wuliu['data']['dlycorp'] as $key => & $v) {
            $_dlycorp[] = array('text'=>$v['name'],'value'=>$v['name']);
            $_dlycorp_info[$v['name']]=$v['corp_id'];
        }
        //给下拉框添加选项
        $this->fldMain['shipping']['options'] = $_dlytype;
        $this->fldMain['corp_name']['options'] = $_dlycorp;

        //本次同步的物流信息
        $wuliuInfo = array(
            'dlytype'=>$_dlytype_info,
            'dlycorp'=>$_dlycorp_info
        );
        // dump($wuliuInfo);
        // exit;

        //整理需要默认的数据信息
        $arr = array(
            'clientId'=>$_order['clientId'],
            'ship_name'=>$_order['ship_name'],
            'ship_addr'=>$_order['ship_addr'],
            'ship_tel'=>$_order['ship_tel'],
            'ship_mobile'=>$_order['ship_mobile'],
            'shipping'=>$_order['shipping'],
            'clientName'=>$_client['compName'],
            'wuliuInfo'=>serialize($wuliuInfo),
        );

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }


        //查找对应的配货单
        // dump($plan);exit;
        $arr_son = array();
        foreach ($plan as $key => & $v) {
            $arr_son[]=array(
                'planId'=>$v['id'],
                'productId'=>$v['productId'],
                'productId'=>$v['productId'],
                'orderId'=>$v['orderId'],
                'ord2proId'=>$v['ord2proId'],
                'peihuoId'=>$v['peihuoId'],
                'proName'=>$v['Product']['proName'],
                'cnt'=>$v['cnt'],
                'cntM'=>$v['cntM'],
                'unit'=>'M',
                'proXinxi'=>'成分:'.$v['Product']['chengfen'].',纱支:'.$v['Product']['shazhi'].',经纬密:'.$v['Product']['jingmi'].'*'.$v['Product']['weimi'].',门幅:'.$v['Product']['menfu'],
            );
        }

        //显示默认值
        foreach($arr_son as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $rowsSon[] = $temp;
        }

        $smarty = & $this->_getView();
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);
        $smarty->assign('sonTitle', "明细信息"); 
        $smarty->assign('areaMain', $areaMain);     
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);

        $_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
        $smarty->assign('fromAction', $_from);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $smarty->display('Main2Son/T1.tpl');
    }
}


?>