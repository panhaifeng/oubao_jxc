<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :BackPlan.php
*  Time   :2015/10/08 13:30:33
*  Remark :退换货申请
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_BackPlan');
class Controller_Cangku_Yangpin_BackPlan extends Controller_Cangku_BackPlan {

    function __construct() {
        parent::__construct();
        $this->_type_order = '样品';

        $this->_cangkuName = __CANGKU_2;

        $this->_check='3-2-10';

        $this->_check2='3-2-11';

        $this->_kind = "换货出库";

        $this->_kind2 = "退换入库";

        $this->_head = "HHCK";

        $this->_head2 = "THRK";
        $this->_modelChuku = FLEA::getSingleton('Model_Cangku_Chuku');
        $this->_modelChu2Product = FLEA::getSingleton('Model_Cangku_Chuku2Product');

        $this->_modelTuihuo = &FLEA::getSingleton('Model_Cangku_Tuihuo');
        $this->_modelTuihuo2Product = &FLEA::getSingleton('Model_Cangku_Tuihuo2Product');
        //出库主信息
        $this->fldMain = array(
            'chukuCode' => array(
                'title' => '出库单号', 
                "type" => "text", 
                'readonly' => true,
                'value' =>'自动生成' 
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
            'clientName' => array('title'=>'客户名称','type' => 'text', 'value' =>'','readonly'=>true),
            'kind' => array('title'=>'出库类型','type' => 'text', 'value' => $this->_kind,'readonly'=>true),
            'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memo'),
            'isGuozhang' => array('type' => 'hidden', 'value' => '0','name'=>'isGuozhang'),
            'cangkuName' => array('type' => 'hidden', 'value' => $this->_cangkuName,'name'=>'cangkuName'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            'id' => array('type' => 'hidden', 'value' => '','name'=>'mainId'),
            'clientId' => array('type' => 'hidden', 'value' => '','name'=>'clientId'),
            'isChuRuku' => array('type' => 'hidden', 'value' => '1','name'=>'isChuRuku'),
        );
        
         //入库主信息
        $this->fldMain2 = array(
            'chukuCode' => array(
                'title' => '入库单号', 
                "type" => "text", 
                'readonly' => true,
                'value' => '自动生成'
            ),
            'chukuDate' => array('title' => '入库日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'kuweiId' => array(
                'title' => '库位',
                'type' => 'select', 
                'value' => '',
                'name'=>'kuweiId',
                'model'=>'Model_Jichu_Kuwei',
                'condition'=>'ckName="'.$this->_cangkuName.'"'
            ),
            // 'jiagonghuId' => array('title' => '加工户','type' => 'select', 'value' => '','name'=>'jiagonghuId','model'=>'Model_Jichu_Jiagonghu','condition'=>'isJiagong=1','isSearch'=>true),
            'clientName' => array('title'=>'客户名称','type' => 'text', 'value' =>'','readonly'=>true),
            'kind' => array('title'=>'入库类型','type' => 'text', 'value' => $this->_kind2,'readonly'=>true),
            'orderCode' => array('title'=>'订单编号','type' => 'text', 'value' => '','readonly'=>true),
            'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memo'),
            'isGuozhang' => array('type' => 'hidden', 'value' => '0','name'=>'isGuozhang'),
            'cangkuName' => array('type' => 'hidden', 'value' => $this->_cangkuName,'name'=>'cangkuName'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            'id' => array('type' => 'hidden', 'value' => '','name'=>'mainId'),
            'clientId' => array('type' => 'hidden', 'value' => '','name'=>'clientId'),
            'isChuRuku' => array('type' => 'hidden', 'value' => '0','name'=>'isChuRuku'),
        );
        //子信息
        $this->headSon = array(
            '_edit' => array('type' => 'BtBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'pihao'=>array('type'=>'BtText',"title"=>'批号','name'=>'pihao[]'),
           // 'productId' => array(
           //      'title' => '花型六位号',
           //      'type' => 'BtPopup',
           //      'name' => 'productId[]',
           //      'url'=>url('Jichu_Product','Popup'),
           //      'textFld'=>'proCode',
           //      'hiddenFld'=>'id',
           //      'inTable'=>true,
           //      'dialogWidth'=>900
           //  ),
           'proCode'=>array('type'=>'BtText',"title"=>'花型六位号','name'=>'proCode[]','readonly'=>true),
           'productId'=>array('type'=>'BtHidden','name'=>'productId[]','readonly'=>true),
            'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
            'chengfen'=>array('type'=>'BtText',"title"=>'成分','name'=>'chengfen[]','readonly'=>true),
            'shazhi' => array('type' => 'BtText', "title" => '纱支', 'name' => 'shazhi[]','readonly'=>true),
            'jwmi' => array('type' => 'BtText', "title" => '经纬密', 'name' => 'jwmi[]','readonly'=>true),
            'menfu' => array('type' => 'BtText', "title" => '门幅', 'name' => 'menfu[]','readonly'=>true),
            'cnt' => array('type' => 'BtText', "title" => '数量', 'name' => 'cnt[]'),
            'cntJian' => array('type' => 'BtText', "title" => '卷数', 'name' => 'cntJian[]'),
            // 'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'M','value'=>'M'),
                    array('text'=>'Y','value'=>'Y')
                )),
            'isBaofei' => array("title" => '是否报废', 'name' => 'isBaofei[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'是','value'=>'0'),
                    array('text'=>'否','value'=>'1')
                )),
            // 'unit' => array('type' => 'BtText', "title" => '单位', 'name' => 'unit[]','value' => 'M'),
            'memoView' => array('type' => 'BtText', "title" => '备注', 'name' => 'memoView[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
            'orderId' => array('type' => 'BtHidden', 'value' => '','name'=>'orderId[]'),
            'ord2proId' => array('type' => 'BtHidden', 'value' => '','name'=>'ord2proId[]'),
            'backId' => array('type' => 'BtHidden', 'value' => '','name'=>'backId[]'),
            'danjia' => array('type' => 'BtHidden', 'value' => '','name'=>'danjia[]'),
        );

        //表单元素的验证规则定义
        $this->rules = array(
            'kuweiId'=>'required',
            'clientId'=>'required',
        );

        $this->sonTpl = array('Cangku/cksonTpl.tpl');
    }

    /**
     * 出库
     * Time：2015/10/08 19:04:57
     * @author li
    */
    function actionChukuAdd(){
        $id = (int)$_GET['planId'];
        $arr = $this->_modelExample->find($id);
        // dump($arr);exit;

        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $_modelOrder->clearLinks();
        $_order = $_modelOrder->find($arr['orderId']);
        // dump($_order);exit;

        //查找客户名称
        $sql="SELECT x.*,c.compName from cangku_back_plan x 
            left join trade_order y on x.orderId = y.id
            left join jichu_client c on c.member_id=y.clientId
            where x.id={$arr['id']} ";
        $rowset=$this->_modelExample->findBySql($sql);
        $arr['clientName']=$rowset[0]['compName'];
        // dump($arr);exit;
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
        }
        $this->fldMain['id']['value'] = '';
        $this->fldMain['clientId']['value'] = $_order['clientId'];
        $this->fldMain['memo']['value'] = $arr['title'];

        $temp[0]['backId'] = $arr['id'];
        $temp[0]['orderId'] = $arr['orderId'];
        $temp[0]['ord2proId'] = $arr['ord2proId'];
        $temp[0]['proCode'] = $arr['Product']['proCode'];
        $temp[0]['productId'] = $arr['Product']['proCode'];
        $temp[0]['proName'] = $arr['Product']['proName'];
        $temp[0]['chengfen'] = $arr['Product']['chengfen'];
        $temp[0]['shazhi'] = $arr['Product']['shazhi'];
        $temp[0]['jwmi'] = $arr['Product']['jingmi'].'*'.$arr['Product']['weimi'];
        $temp[0]['menfu'] = $arr['Product']['menfu'];
        $temp[0]['cnt'] = $arr['num'] ;
        $temp[0]['danjia'] = $arr['price'];
        
        foreach($temp as &$v) {
            $_temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $_temp[$kk] = array('value' => $v[$kk]);
            }
            $_temp['productId']['text'] = $v['proCode'];
            $rowsSon[] = $_temp;
        }
        //换货出库不需要是否报废选项，by liu
        unset($this->headSon['isBaofei']);
        // $rowsSon = array_fill(0,5,array());
        //补齐5行
        $cnt = count($rowsSon);
        for($i=5;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }
		$smarty = &$this->_getView();
		$areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);
		$smarty->assign('areaMain', $areaMain);		
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		//
		$_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
		$smarty->assign('fromAction', $_from);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', $this->sonTpl);
		$smarty->display('Main2Son/T1.tpl');
    }
    function actionRight(){
        //权限判断
        $this->authCheck($this->_check2);
        FLEA::loadClass('TMIS_Pager');

        //构造搜索区域的搜索类型
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
            'dateTo' => date("Y-m-d"),
            'danhao'=>'',
            'orderCode'=>'',
            'clientId'=>'',
            'kuweiId_name'=>'',
            'kuweiName'=>$this->_cangkuName,
            'proCode'=>'',
            'isBaofei'=>'',
        )); 
        $sql="SELECT x.cnt,x.cntJian,x.cntM,x.unit,x.isBaofei,y.id,y.chukuDate, 
                y.chukuCode,y.kind,k.kuweiName,x.id as chu2proId,
                p.proCode,p.proName,p.chengfen,p.jingmi,p.weimi,p.menfu,s.compName,z.orderCode
                from cangku_chuku2product x
                left join cangku_chuku y on x.chukuId=y.id
                left join jichu_kuwei k on k.id=y.kuweiId
                left join jichu_product p on p.proCode=x.productId
                left join jichu_client s on s.member_id=y.clientId
                left join trade_order z on z.id=x.orderId
                where 1 and cangkuName='{$this->_cangkuName}' and y.kind in ('{$this->_kind}','{$this->_kind2}')";

        if($arr['dateFrom'] != '') {
            $sql .=" and y.chukuDate >= '{$arr['dateFrom']}' and y.chukuDate <= '{$arr['dateTo']}'";
        }
        if($arr['danhao']!='') $sql .=" and y.chukuCode like '%{$arr['danhao']}%' ";
        if($arr['orderCode']!='') $sql .=" and z.orderCode like '%{$arr['orderCode']}%' ";
        if($arr['kuweiId_name']!='') $sql .=" and y.kuweiId = '{$arr['kuweiId_name']}' ";
        if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
        if($arr['clientId']!='') $sql .=" and y.clientId = '{$arr['clientId']}' ";
        if($arr['isBaofei']!='') $sql .=" and x.isBaofei = '{$arr['isBaofei']}'";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($sql);die;
        foreach($rowset as & $v) {
            $v['isBaofei']=$v['isBaofei']=='0'?'是':'否';
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];

            //2015-10-29 by jiang 已过账禁止修改和删除
            $sql="select * from caiwu_ar_guozhang where chuku2proId='{$v['chu2proId']}'";
            $ret=$this->_modelExample->findBySql($sql);
            if(count($ret)>0){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已过账禁止修改和删除'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已过账禁止修改和删除'></span>";
            }else{
                 if($v['kind']==$this->_kind2){
                    $v['_edit']="<a href='".url('Cangku_Yangpin_BackPlan','EditRuku',array(
                        id=>$v['id'],
                        'fromAction'=>$_GET['action']
                        ))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
                    $v['_edit'].=" <a href='".url('Cangku_Yangpin_BackPlan','RemoveRuku',array(
                        id=>$v['id'],
                        'fromAction'=>$_GET['action']
                        ))."' onclick='return confirm(\"您确认要删除吗?\")'><span class='glyphicon glyphicon-trash text-danger' title='删除'></span></a>";
                }else{
                  $v['_edit'] = $this->getEditHtml($v['id']);
                  $v['_edit'].=' ' .$this->getRemoveHtml($v['id']);
                }
            }

            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            
        } 
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'_edit'); 
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'].' M';

        //导出去掉合计标签
        if($_GET['export']==1){
            $hj['rukuCode'] = strip_tags($hj['_edit']);
            $hj['cnt']=$hj['cntM'];
            unset($hj['_edit']);
        }

        $rowset[]=$hj;
        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            "_edit" => '操作',
            "chukuCode" => '单号',
            "orderCode" => '订单号',
            "chukuDate" => '日期',
            "kuweiName" => '库位',
            "kind" => '类型',
            "compName" => '客户',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "chengfen" => '成分',
            "isBaofei" => '是否报废',
            "jwmi" => '经纬密',
            "menfu" => '门幅',
            "cnt" => '数量',
            "cntJian" => '卷数',
            "unit" => '单位'
        );
        //如果不是现货仓库  去除卷数
        // if($this->_cangkuName != __CANGKU_1) unset($arr_field_info['cntJian']);
        $smarty->assign('title', '入库列表');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
            if($_GET['export']==1){
                foreach ($rowset as $key => & $value) {
                    foreach ($value as $key => & $v) {     
                        $v = strip_tags($v);
                    }
                    unset($value['_edit']);
                }
                unset($arr_field_info['_edit']);
                $this->_exportList(array('title'=>$title),$smarty);
            }
        $smarty->display('TblList.tpl');
    }
    function actionEdit() {
        // dump($_GET);exit;
        $id = (int)$_GET['id'];
        $arr = $this->_modelChuku->find($id);
        // dump($arr);exit;
        //查找客户名称
        $this->_modelClient = &FLEA::getSingleton('Model_Jichu_Client');
        $rowset = $this->_modelClient->find(array('member_id' => $arr['clientId']));
        $arr['compName']= $rowset['compName'];

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
        }
        // $this->fldMain['id']['value'] = '';
        $this->fldMain['clientId']['value'] = $arr['clientId'];
        $this->fldMain['clientName']['value'] = $arr['compName'];
        $this->fldMain['memo']['value'] = $arr['memo'];

        foreach($arr['Products'] as &$v) {
            $this->_modelProduct = &FLEA::getSingleton('Model_Jichu_Product');
            $_temp = $this->_modelChu2Product->find(array('id' => $v['id']));
            $_t = $this->_modelProduct->find(array('proCode' => $v['productId']));

            $v['proCode'] = $_t['proCode'];
            $v['productId'] = $_temp['productId'];
            $v['proName'] = $_t['proName'];
            $v['chengfen'] = $_t['chengfen'];
            $v['shazhi'] = $_t['shazhi'];
            $v['jwmi'] = $_t['jingmi'].'*'.$_t['weimi'];
            $v['menfu'] = $_t['menfu'];

        }
        foreach($arr['Products'] as &$v) {
            $_temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $_temp[$kk] = array('value' => $v[$kk]);
            }
            $_temp['productId']['text'] = $v['productId'];
            $rowsSon[] = $_temp;
        }
        // $rowsSon = array_fill(0,5,array());
        //补齐5行
        $cnt = count($rowsSon);
        for($i=5;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }

        unset($this->headSon['isBaofei']);
        // dump($rowsSon);exit;
        $smarty = &$this->_getView();
        $smarty->assign('areaMain',array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain));
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $smarty->display('Main2Son/T1.tpl');
    }
    /**
     * 入库修改
     * Time：2015-10-12 14:12:54
     * @author shen
    */
    function actionEditRuku(){
        // dump($_GET);exit;
        $id = (int)$_GET['id'];
        $arr = $this->_modelTuihuo->find($id);
        // dump($arr);exit;

        //订单编号
        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $_order = $_modelOrder->find($arr['Products'][0]['orderId']);
        $arr['orderCode']=$_order['orderCode'];

        //查找客户名称
        $this->_modelClient = &FLEA::getSingleton('Model_Jichu_Client');
        $rowset = $this->_modelClient->find(array('member_id' => $arr['clientId']));
        $arr['compName']= $rowset['compName'];
        // dump($arr);exit;
        foreach ($this->fldMain2 as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
        }
        // $this->fldMain['id']['value'] = '';
        // $this->fldMain2['rukuCode']['value'] = $arr['rukuCode'];
        // $this->fldMain2['rukuDate']['value'] = $arr['rukuDate'];
        $this->fldMain2['clientId']['value'] = $arr['clientId'];
        $this->fldMain2['orderCode']['value'] = $arr['orderCode'];
        $this->fldMain2['clientName']['value'] = $arr['compName'];
        $this->fldMain2['memo']['value'] = $arr['memo'];

        foreach($arr['Products'] as &$v) {
            $this->_modelProduct = &FLEA::getSingleton('Model_Jichu_Product');
            $_temp = $this->_modelTuihuo2Product->find(array('id' => $v['id']));
            $_t = $this->_modelProduct->find(array('proCode' => $v['productId']));

            $v['proCode'] = $_t['proCode'];
            $v['productId'] = $_temp['productId'];
            $v['proName'] = $_t['proName'];
            $v['chengfen'] = $_t['chengfen'];
            $v['shazhi'] = $_t['shazhi'];
            $v['jwmi'] = $_t['jingmi'].'*'.$_t['weimi'];
            $v['menfu'] = $_t['menfu'];
        }
        foreach($arr['Products'] as &$v) {
            $_temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $_temp[$kk] = array('value' => $v[$kk]);
            }
            $_temp['productId']['text'] = $v['productId'];
            $rowsSon[] = $_temp;
        }
        // $rowsSon = array_fill(0,5,array());
        //补齐5行
        $cnt = count($rowsSon);
        for($i=5;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }
        // dump($rowsSon);exit;
        $smarty = &$this->_getView();
        $smarty->assign('areaMain',array('title' => $this->_kind2.'基本信息', 'fld' => $this->fldMain2));
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Xianhuo/xhrksonTpl.tpl');
        $smarty->assign('action_save', 'SaveRuku');
        $smarty->display('Main2Son/T1.tpl');
    }
    /**
     * 出库保存
     * Time：2015-10-09 19:28:52
     * @author shen
    */
    function actionSave(){
      // dump($_POST);exit;

        //主表信息
        $row = array();
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
        }
        // dump($row);exit;

        //明细信息
        $pros = array();
        $maId=array();
        foreach($_POST['productId'] as $key=> & $v) {
            if($v=='' || empty($_POST['cnt'][$key])) continue;
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            //将单位转换为米保存
            $temp['unit']=='' && $temp['unit']='M';//默认不能为空
            $temp['cntM'] = $temp['unit'] == 'M' ? $temp['cnt'] : $temp['cnt']*0.9144;
            $temp['isBaofei'] = 1;
            $pros[]=$temp;
        }
        // dump($pros);exit;
        if(!$pros){
            js_alert('保存失败，没有有效明细数据','window.history.go(-1)');
            exit;
        }
        $row['Products'] = $pros;
        if($row['chukuCode']=='自动生成') $row['chukuCode']=$this->_getNewCode($this->_head,'cangku_chuku','chukuCode');
        // dump($row);exit;
        if(!$this->_modelChuku->save($row)) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        }   
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('Right'));
    }
    function actionRemove(){
        $this->_modelChuku->removeByPkv($_GET[id]);
        js_alert(null, "window.parent.showMsg('成功删除')", $this->_url('right')); 
        
    }
    function actionRemoveRuku(){
        $this->_modelTuihuo->removeByPkv($_GET[id]);
        js_alert(null, "window.parent.showMsg('成功删除')", $this->_url('right')); 
    }
     /**
     * 入库
     * Time：2015-10-11 15:09:54
     * @author shen
    */
    function actionRukuAdd(){
        // dump($_GET);exit;
        $id = (int)$_GET['planId'];
        $arr = $this->_modelExample->find($id);
        // dump($arr);exit;

        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        // $_modelOrder->clearLinks();
        $_order = $_modelOrder->find($arr['orderId']);
        // dump($_order);exit;

        //查找客户名称
        $sql="SELECT x.*,c.compName from cangku_back_plan x 
            left join trade_order y on x.orderId = y.id
            left join jichu_client c on c.member_id=y.clientId
            where x.id={$arr['id']} ";
        $rowset=$this->_modelExample->findBySql($sql);
        $arr['clientName']=$rowset[0]['compName'];

        foreach ($this->fldMain2 as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
        }
        $this->fldMain2['id']['value'] = '';
        $this->fldMain2['clientId']['value'] = $_order['clientId'];
        $this->fldMain2['memo']['value'] = $arr['title'];

        $temp[0]['backId'] = $arr['id'];
        $temp[0]['orderId'] = $arr['orderId'];
        $temp[0]['ord2proId'] = $arr['ord2proId'];
        $temp[0]['pihao'] = $arr['pihao'];
        $temp[0]['proCode'] = $arr['Product']['proCode'];
        $temp[0]['productId'] = $arr['Product']['proCode'];
        $temp[0]['proName'] = $arr['Product']['proName'];
        $temp[0]['chengfen'] = $arr['Product']['chengfen'];
        $temp[0]['shazhi'] = $arr['Product']['shazhi'];
        $temp[0]['jwmi'] = $arr['Product']['jingmi'].'*'.$arr['Product']['weimi'];
        $temp[0]['menfu'] = $arr['Product']['menfu'];
        $temp[0]['danjia'] = $arr['price'];
        $temp[0]['cnt'] = $arr['num'];
        $temp[0]['isBaofei'] = 1;
        $temp[0]['unit'] = 'M';
        
        foreach($temp as &$v) {
            $_temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $_temp[$kk] = array('value' => $v[$kk]);
            }
            $_temp['productId']['text'] = $v['proCode'];
            $rowsSon[] = $_temp;
        }
        // $rowsSon = array_fill(0,5,array());
        //补齐5行
        $cnt = count($rowsSon);
        for($i=5;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }
        $smarty = &$this->_getView();
        $areaMain = array('title' => $this->_kind2.'基本信息', 'fld' => $this->fldMain2);
        $smarty->assign('areaMain', $areaMain);     
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        //
        $_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
        $smarty->assign('fromAction', $_from);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Xianhuo/xhrksonTpl.tpl');
        $smarty->assign('action_save', 'SaveRuku');
        $smarty->display('Main2Son/T1.tpl');
    }
     /**
     * 入库保存
     * Time：2015-10-12 10:40:25
     * @author shen
    */
    function actionSaveRuku(){
        //主表信息
        $row = array();
        foreach($this->fldMain2 as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
        }
        // dump($row);exit;

        //明细信息
        $pros = array();
        $maId=array();
        foreach($_POST['productId'] as $key=> & $v) {
            if($v=='' || empty($_POST['cnt'][$key])) continue;
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            $temp['cnt']=abs($temp['cnt'])*-1;
            $temp['cntJian']=abs($temp['cntJian'])*-1;
            //将单位转换为米保存
            $temp['unit']=='' && $temp['unit']='M';//默认不能为空
            $temp['cntM'] = $temp['unit'] == 'M' ? $temp['cnt'] : $temp['cnt']*0.9144;
            $pros[]=$temp;
        }
        // dump($pros);exit;
        if(!$pros){
            js_alert('保存失败，没有有效明细数据','window.history.go(-1)');
            exit;
        }
        $row['Products'] = $pros;
        if($row['chukuCode']=='自动生成') $row['chukuCode']=$this->_getNewCode($this->_head2,'cangku_chuku','chukuCode');
        // dump($row);exit;    
        if(!$this->_modelTuihuo->save($row)) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        }   
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('Right'));
    }
}
?>