<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :BackPlan.php
*  Time   :2015/10/08 13:30:33
*  Remark :退换货申请
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_BackPlan');
class Controller_Cangku_Xianhuo_BackPlan extends Controller_Cangku_BackPlan {

    function __construct() {
        parent::__construct();
        $this->_type_order = '现货';

        $this->_cangkuName = __CANGKU_1;

        $this->_check='3-1-10';

        $this->_check2='3-1-11';

        $this->_kind = "换货出库";

        $this->_kind2 = "退换入库";

        $this->_head = "HHCK";

        $this->_head2 = "THRK";

        $this->_modelChuku = &FLEA::getSingleton('Model_Cangku_Chuku');
        $this->_modelChu2Product = &FLEA::getSingleton('Model_Cangku_Chuku2Product');

        $this->_modelTuihuo = &FLEA::getSingleton('Model_Cangku_Tuihuo');
        $this->_modelTuihuo2Product = &FLEA::getSingleton('Model_Cangku_Tuihuo2Product');
        //出库主信息
        $this->fldMain = array(
            'chukuCode' => array(
                'title' => '出库单号', 
                "type" => "text", 
                'readonly' => true,
                'value' => '自动生成'
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
            // 'jiagonghuId' => array('title' => '加工户','type' => 'select', 'value' => '','name'=>'jiagonghuId','model'=>'Model_Jichu_Jiagonghu','condition'=>'isJiagong=1','isSearch'=>true),
            'clientName' => array('title'=>'客户名称','type' => 'text', 'value' =>'','readonly'=>true),
            'kind' => array('title'=>'出库类型','type' => 'text', 'value' => $this->_kind,'readonly'=>true),
            'orderCode' => array('title'=>'订单编号','type' => 'text', 'value' => '','readonly'=>true),
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
                'value' =>'自动生成'
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
            'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
            'productId'=>array('type'=>'BtHidden','name'=>'productId[]','readonly'=>true),
            'chengfen'=>array('type'=>'BtText',"title"=>'成分','name'=>'chengfen[]','readonly'=>true),
            'shazhi' => array('type' => 'BtText', "title" => '纱支', 'name' => 'shazhi[]','readonly'=>true),
            'jwmi' => array('type' => 'BtText', "title" => '经纬密', 'name' => 'jwmi[]','readonly'=>true),
            'menfu' => array('type' => 'BtText', "title" => '门幅', 'name' => 'menfu[]','readonly'=>true),
            'cnt' => array('type' => 'BtText', "title" => '数量', 'name' => 'cnt[]','readonly'=>true),
            'cntJian' => array('type' => 'BtText', "title" => '卷数', 'name' => 'cntJian[]','readonly'=>true),
            'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'M','value'=>'M'),
                    array('text'=>'Y','value'=>'Y')
                )),
            'isBaofei' => array("title" => '是否报废', 'name' => 'isBaofei[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'是','value'=>'0'),
                    array('text'=>'否','value'=>'1')
                )),
            // 'unit' => array('type' => 'BtText', "title" => '单位', 'name' => 'unit[]','value' => 'M','readonly'=>true),
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
            'jiagonghuId'=>'required',
        );

        $this->sonTpl = array('Cangku/cksonTpl.tpl');
    }

    /**
     * 出库
     * Time：2015/10/08 19:04:57
     * @author li
    */
    function actionChukuAdd(){
        // dump($_GET);exit;
        $id = (int)$_GET['planId'];
        $arr = $this->_modelExample->find($id);
        // dump($arr);exit;

        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $_modelOrder->clearLinks();
        $_order = $_modelOrder->find($arr['orderId']);
        // dump($_order);exit;

        //查找码单Id
        $str="SELECT a.*
            from trade_order2product x 
            left join cangku_back_plan y on y.ord2proId=x.id
            left join ph_peihuo2madan z on z.phId=x.peihuoId
            left join madan_db a on a.id=z.madanId
            where y.id='{$id}' group by x.productId,millNo";
        $aRow=$this->_modelExample->findBySql($str);
        // dump($aRow);exit;

        //查找客户名称
        $sql="SELECT x.*,c.compName from cangku_back_plan x 
            left join trade_order y on x.orderId = y.id
            left join jichu_client c on c.member_id=y.clientId
            where x.id={$arr['id']} ";
        $rowset=$this->_modelChuku->findBySql($sql);
        $arr['clientName']=$rowset[0]['compName'];
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
        }
        $this->fldMain['id']['value'] = '';
        $this->fldMain['clientId']['value'] = $_order['clientId'];
        $this->fldMain['memo']['value'] = $arr['title'];

        foreach ($aRow as $k=> & $v) {
            $temp[$k]['pihao'] = $v['millNo'];
            $temp[$k]['backId'] = $arr['id'];
            $temp[$k]['orderId'] = $arr['orderId'];
            $temp[$k]['ord2proId'] = $arr['ord2proId'];
            $temp[$k]['proCode'] = $arr['Product']['proCode'];
            $temp[$k]['productId'] = $arr['Product']['proCode'];
            $temp[$k]['proName'] = $arr['Product']['proName'];
            $temp[$k]['chengfen'] = $arr['Product']['chengfen'];
            $temp[$k]['shazhi'] = $arr['Product']['shazhi'];
            $temp[$k]['jwmi'] = $arr['Product']['jingmi'].'*'.$arr['Product']['weimi'];
            $temp[$k]['menfu'] = $arr['Product']['menfu'];
            $temp[$k]['danjia'] = $arr['price'];
        }
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
        $sql.="order by chukuDate desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach($rowset as & $v) {
            $v['isBaofei']=$v['isBaofei']=='0'?'是':'否';
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];
            $v['cnt']=round($v['cnt'],2);

            //2015-10-29 by jiang 已过账禁止修改和删除
            $sql="select * from caiwu_ar_guozhang where chuku2proId='{$v['chu2proId']}'";
            $ret=$this->_modelExample->findBySql($sql);
            if(count($ret)>0){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已过账禁止修改和删除'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已过账禁止修改和删除'></span>";
            }else{
                if($v['kind']==$this->_kind2){
                    $v['_edit']="<a href='".url('Cangku_Xianhuo_BackPlan','EditRuku',array(
                        id=>$v['id'],
                        'fromAction'=>$_GET['action']
                        ))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
                    $v['_edit'].=" <a href='".url('Cangku_Xianhuo_BackPlan','RemoveRuku',array(
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
        if($this->_cangkuName != __CANGKU_1) unset($arr_field_info['cntJian']);
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

        //订单编号
        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $_order = $_modelOrder->find($arr['Products'][0]['orderId']);
        $arr['orderCode']=$_order['orderCode'];
        // dump($_order);exit;

        //查找客户名称
        $this->_modelClient = &FLEA::getSingleton('Model_Jichu_Client');
        $rowset = $this->_modelClient->find(array('member_id' => $arr['clientId']));
        $arr['compName']= $rowset['compName'];

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
        }
        $this->fldMain['clientId']['value'] = $arr['clientId'];
        $this->fldMain['orderCode']['value'] = $arr['orderCode'];
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

             //获取码单
            $sql="select group_concat(madanId) as madanId from madan_rc2madan where chukuId = '{$v['id']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $_t = $_temp[0];
            $v['Madan']=$_t['madanId'];
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
        // dump($_GET);
        $arr = $this->_modelTuihuo->find(array('id' => $_GET['id']));
        // dump($arr);exit;

         // 订单编号
        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $_order = $_modelOrder->find($arr['Products'][0]['orderId']);
        $arr['orderCode']=$_order['orderCode'];

        //查找客户名称
        $this->_modelClient = &FLEA::getSingleton('Model_Jichu_Client');
        $rowset = $this->_modelClient->find(array('member_id' => $arr['clientId']));
        $arr['compName']= $rowset['compName'];

        foreach ($cai['Products'] as & $c) {
            $retCai[$c['id']]=$c;
        }

        foreach ($this->fldMain2 as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        $this->fldMain2['clientName']['value'] = $arr['compName'];
        $this->fldMain2['orderCode']['value'] = $arr['orderCode'];

        //仓库信息
        if($arr['kuweiId']>0){
            $sql="select kuweiName from jichu_kuwei where id='{$arr['kuweiId']}'";
            $temp=$this->_modelTuihuo2Product->findBySql($sql);
            $this->fldMain2['kuweiId'] && $this->fldMain2['kuweiId']['text']=$temp[0]['kuweiName'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain2);

        // 入库明细处理
        $rowsSon = array();
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $v['productId'] = $_temp[0]['proCode'];
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['chengfen'] = $_temp[0]['chengfen'];
            $v['shazhi'] = $_temp[0]['shazhi'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['jwmi'] = $_temp[0]['jingmi'].'*'.$_temp[0]['weimi'];

            $v['danjia'] = round($v['danjia'],6);

            //查找码单信息，并json_encode
            $sql="SELECT y.* from madan_rc2madan x
                left join madan_db y on x.madanId=y.id
                where x.chukuId='{$v['id']}' order by id asc";
            $retMadan = $this->_modelExample->findBySql($sql);
            $_temp=array();
            foreach($retMadan as $k=> & $m){
                if($m['status'] !='active') {
                    $m['readonly']=true;
                }
                $m['rollNo']=$k;
                $_temp[$m['rollNo']]=$m;
            }
            $_temp['isCheck']=1;
            $v['Madan'] = json_encode($_temp);
        }

        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $temp['productId']['text']=$v['proCode'];
            $temp['cntCg']['value']=$retCai[$v['cai2proId']]['cnt'];
            $rowsSon[] = $temp;
        }
        // dump($rowsSon);die;
        $smarty = &$this->_getView();
        $smarty->assign('areaMain',array('title' => $this->_kind2.'基本信息', 'fld' => $this->fldMain2));
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('fromAction', $_GET['fromAction']);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Xianhuo/xhrksonTpl.tpl');
        // $smarty->assign('sonTpl', $this->sonTpl);
        $smarty->assign('action_save', 'SaveRuku');
        $smarty->display('Main2Son/T1.tpl');
    }
    function actionViewMadan(){
        $ma=&FLEA::getSingleton('Model_Cangku_Madan');
        $_modelPro=&FLEA::getSingleton('Model_Jichu_Product');
        $product = $_modelPro->find(array('proCode'=>$_GET['productId']));
        if($_GET['chukuId']) $stat=" and (y.status='active' or x.chukuId='{$_GET['chukuId']}')";
        else $stat=" and y.status='active'";
        $sql="select y.* from madan_db y
                   left join madan_rc2madan x on x.madanId=y.id 
                   where y.productId='{$_GET['productId']}' and y.millNo='{$_GET['pihao']}'
                    {$stat}
                   group by y.id order by y.rollNo";
        $row = $ma->findBySql($sql);
        $ret=array();
        foreach ($row as & $v) {
            $v['cntM']=round($v['cntM'],3);
            $v['cnt']=round($v['cnt'],3);
            if($v['unit']=='Y'){
                $v['cntMi'] = $v['cnt']*0.9144;
            }else{
                $v['cntMi'] =$v['cntM'];
            }
            $ret['Peihuo'][$v['millNo']][]=$v;
            // $product=$v['Product'];
        }
        // dump($ret);exit;
        $smarty = &$this->_getView(); 
        $smarty->assign('Peihuo', $ret);
        $smarty->assign('Product', $product);
        $smarty->display('Cangku/Xianhuo/PeihuoSelect.tpl');
    }

    function actionViewMadan2(){
        $smarty = & $this->_getView();
        $smarty->assign('title', "码单编辑");
        $smarty->display("Cangku/MadanEdit.tpl");
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

        //验证出库的花型六位号是否有库存
        // $_modelKucuen = &FLEA::getSingleton('Model_Cangku_Kucun');
       
        // foreach ($_POST['productId'] as $key => $v) {
        //     $sql="SELECT sum(cnt)as cntChu from cangku_kucun
        //          where kuweiId={$_POST['kuweiId']} 
        //          and cangkuName='{$_POST['cangkuName']}' 
        //          and productId ='{$v}'";
        //     $aRow=$_modelKucuen->findBySql($sql);
            
        //     $aRow[0]['cntChu']=$aRow[0]['cntChu']==''?0:$aRow[0]['cntChu'];
        //     if($aRow[0]['cntChu']<$_POST['cnt'][$key]){
        //         js_alert('花型六位号'.' '.$v.' '.'在该库位库存为:'.$aRow[0]['cntChu'],'window.history.go(-1);');
        //     }
        // }
        
        //明细信息
        $pros = array();
        $maId=array();
        foreach($_POST['productId'] as $key=> & $v) {
            $delmadan=array();
            if($v=='' || empty($_POST['cnt'][$key])) continue;
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            $maId[]=$_POST['Madan'][$key];
            $delmadan[]=$_POST['Madan'][$key];
            $_madan = explode(',',$_POST['Madan'][$key]);

            $madan_ck = array();
            foreach ($_madan as & $m) {
                $madan_ck[] = array('madanId'=>$m);
            }

            //清空所有的码单数据
            if($temp['id']>0){
                //先查找本次修改去掉的码单
                $delmadan=join(',',$delmadan);
                $m_sql="update madan_db set `status`='active' where id in(
                            select madanId from (
                                select x.madanId from madan_rc2madan x
                                left join madan_db y on x.madanId=y.id
                                where x.chukuId='{$temp['id']}' and madanId not in({$delmadan})
                            ) as a
                        )";
                $this->_modelExample->findBySql($m_sql);
                //清空关联表
                $_delsql = "delete from madan_rc2madan where chukuId='{$temp['id']}'";
                $this->_modelExample->execute($_delsql);
            }

            $temp['Madan'] = $madan_ck;
            // $temp['cntJian'] = count($madan_ck);
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
        //锁定码单  
        if($this->_cangkuName == __CANGKU_1){
            $maId=join(',',$maId);
            $sql="update madan_db set status='finish' where id in({$maId})";
            $this->_modelExample->findBySql($sql);
        }
        if($row['chukuCode']=='自动生成') $row['chukuCode']=$this->_getNewCode($this->_head,'cangku_chuku','chukuCode');
        $row['Products'] = $pros;
        // dump($row);exit;
        if(!$this->_modelChuku->save($row)) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        }   
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('Right'));
    }

    function actionRemoveByAjax() {
        // dump($_POST);exit;
        //2015-11-11  已锁定的入库码单禁止删除
        $sqlm="select x.* from madan_db x 
                left join madan_rc2madan y on x.id=y.madanId
                left join cangku_chuku2product c on c.id=y.chukuId
                where c.id='{$_POST['id']}' and x.status <> 'active'";
        $retm=$this->_modelExample->findBySql($sqlm);
        if(count($retm)>0){
            echo json_encode(array('success'=>false,'msg'=>'码单已锁定，禁止删除!'));exit;
        }

        //将码单取消锁定  暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $sql="update madan_db set status='active' where id in(
                    select madanId from madan_rc2madan where chukuId='{$_POST['id']}'
                    )";
            $this->_modelExample->findBySql($sql);
        }
        // parent::actionRemoveByAjax();
        $success=$this->_modelExample->execute($sql);
        echo json_encode(array('success'=>$success,'msg'=>'删除失败'));exit;
    }

    function actionRemove(){
        // dump($_GET);exit;
        //将码单取消锁定 暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $sql="update madan_db set status='active' where id in(
                    select madanId from madan_rc2madan x
                    left join cangku_chuku2product y on x.chukuId=y.id
                     where y.chukuId='{$_GET['id']}'
                    )";
            $this->_modelExample->findBySql($sql);
        }
        $this->_modelChuku->removeByPkv($_GET[id]);
        redirect($this->_url('right'));
    }
    function actionRemoveRuku(){
         //2015-11-11 已锁定的入库码单禁止删除
        $sqlm="select x.* from madan_db x 
                left join madan_rc2madan y on x.id=y.madanId
                left join cangku_chuku2product c on c.id=y.chukuId
                where c.chukuId='{$_GET['id']}' and x.status <> 'active'";
        $retm=$this->_modelExample->findBySql($sqlm);
        if(count($retm)>0){
            $from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
            js_alert('码单已锁定，禁止删除!!',null,$this->_url($from));exit;
        }
        $this->_modelTuihuo->removeByPkv($_GET[id]);
        redirect($this->_url('right'));
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

        //查找同一个花型六位号同一个批号的数量和件数
        $str="SELECT a.id,x.productId,x.millNo,sum(x.cnt) as cnt,sum(x.cntFormat) as cntFormat,
            sum(x.cntM) as cntM ,count(*) as cntJian,x.unit
            from madan_db x 
            left join ph_peihuo2madan y on x.id=y.madanId
            left join trade_order2product z on z.peihuoId=y.phId
            left join cangku_back_plan a on a.ord2proId=z.id
            where a.id='{$id}' group by x.productId,millNo,unit";
        $aRow=$this->_modelExample->findBySql($str);

        //查找码单信息
        $sqlMadan="SELECT x.*
            from madan_db x 
            left join ph_peihuo2madan y on x.id=y.madanId
            left join trade_order2product z on z.peihuoId=y.phId
            left join cangku_back_plan a on a.ord2proId=z.id
            where a.id='{$id}'";
        $retMadan=$this->_modelExample->findBySql($sqlMadan);
        // dump($retMadan);
        $arrMadan=array();
        foreach ($retMadan as & $r) {
            $r['cntFormat']=$r['cnt'];
            $r['cnt']=$r['cnt'];
            $_tempMillo=$r['productId'].','.$r['millNo'].','.$r['unit'];
            $r['id']='';
            $arrMadan[$_tempMillo]['Madan'][$r['rollNo']-1]=$r;
        }
        // dump($arrMadan);exit;
        foreach ($aRow as & $a) {
            $_tempMillo = $a['productId'].','.$a['millNo'].','.$a['unit'];
            $a['Madan'] = $arrMadan[$_tempMillo]['Madan'];
            $a['Madan'] = json_encode($a['Madan']);
        }
        // dump($aRow);exit;
      

        //查找客户名称
        $sql="SELECT x.*,c.compName,y.clientId 
            from cangku_back_plan x 
            left join trade_order y on x.orderId = y.id
            left join jichu_client c on c.member_id=y.clientId
            where x.id={$arr['id']} ";
        $rowset=$this->_modelExample->findBySql($sql);
        $arr['clientName']=$rowset[0]['compName'];
        $arr['clientId']=$rowset[0]['clientId'];

        foreach ($this->fldMain2 as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
        }
        $this->fldMain2['id']['value'] = '';
        $this->fldMain2['memo']['value'] = $arr['title'];

        foreach ($aRow as $k=> & $v) {
            // dump($k);dump($v['unit']);
            $temp[$k]['pihao'] = $v['millNo'];
            $temp[$k]['backId'] = $v['id'];
            $temp[$k]['orderId'] = $arr['orderId'];
            $temp[$k]['ord2proId'] = $arr['ord2proId'];
            $temp[$k]['proCode'] = $arr['Product']['proCode'];
            $temp[$k]['productId'] = $arr['Product']['proCode'];
            $temp[$k]['proName'] = $arr['Product']['proName'];
            $temp[$k]['chengfen'] = $arr['Product']['chengfen'];
            $temp[$k]['shazhi'] = $arr['Product']['shazhi'];
            $temp[$k]['jwmi'] = $arr['Product']['jingmi'].'*'.$arr['Product']['weimi'];
            $temp[$k]['menfu'] = $arr['Product']['menfu'];

            // $temp[$k]['rollNo'] = $v['rollNo'];
            $temp[$k]['cntJian'] = $v['cntJian'];
            $temp[$k]['Madan'] = $v['Madan'];
            $temp[$k]['danjia'] = $arr['price'];
            $temp[$k]['isBaofei'] = 1;
            $temp[$k]['unit'] = $v['unit'];
            if($v['unit']='Y'){
                $temp[$k]['cnt'] = $v['cnt'];
            }
            else{
                $temp[$k]['cnt'] = $v['cntM'];
            }
        }
        // dump($temp);exit;
        foreach($temp as &$v) {
            $_temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $_temp[$kk] = array('value' => $v[$kk]);
            }
            $_temp['productId']['text'] = $v['proCode'];
            $rowsSon[] = $_temp;
        }
        // dump($rowsSon);exit;
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
        $_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', 'Cangku/Xianhuo/xhrksonTpl.tpl');
        // $smarty->assign('sonTpl', $this->sonTpl);
        $smarty->assign('action_save', 'SaveRuku');
        $smarty->display('Main2Son/T1.tpl');
    }
     /**
     * 入库保存
     * Time：2015-10-12 10:40:25
     * @author shen
    */
    function actionSaveRuku(){
        $pros = array();
        foreach($_POST['productId'] as $key=> & $v) {
            if($v=='' || empty($_POST['cnt'][$key]) || $_POST['pihao'][$key]=='') continue; 
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            $temp['cnt'] = round($temp['cnt'],2);
            $temp['cnt']=abs($temp['cnt'])*-1;
            $temp['cntJian']=abs($temp['cntJian'])*-1;
            //将单位转换为米保存
            $temp['unit']=='' && $temp['unit']='M';//默认不能为空
            if($temp['unit']=='Y') $temp['cntM']=0.9144*$temp['cnt'];
            else $temp['cntM']=$temp['cnt'];
            //设置码单状态，2015-10-23，by liuxin
            $madan = array();
            $madan = json_decode($temp['Madan']);
            if ($temp['isBaofei']==0) {
                foreach ($madan as &$value) {
                    $value = (Array)$value;
                    $value['status'] = 'finish';
                }
            }
            else{
                foreach ($madan as &$value) {
                    $value = (Array)$value;
                    $value['status'] = 'active';
                }
            }
            $temp['Madan'] = json_encode($madan);
            $pros[]=$temp;

        }
        // dump($pros);die;
        // if(count($pros)==0) {
        //     js_alert('批号、产品、数量为必填信息!','window.history.go(-1)');
        //     exit;
        // }
       
        //采购入库时，入库卷数要是必填项
        foreach($pros as $key=> & $v){
            if($v['cntJian']==''){
                js_alert('入库卷数为必填项!','window.history.go(-1)');
            }
        }
        $row = array();
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
        }

        $row['Products'] = $pros;
        /*
        * 处理码单信息
        */
        foreach($row['Products'] as & $v){
            $madan = json_decode($v['Madan'],true);
            // dump($madan);exit;
            $_temp=array();
            foreach($madan as & $m){
                $m = (Array)$m;
                //数量不存在，说明该码单不需要保存
                if(empty($m['cntFormat'])){
                    //如果id存在，则说明该码单需要在数据表中删除
                    if($m['id']>0){
                        $v['madan_clear'][]=$m['id'];
                    }
                    continue;
                }
                $m['rukuDate']=$row['chukuDate'];
                $m['productId']=$v['productId'];
                $m['millNo']=$v['pihao'];
                $m['unit']=$v['unit'];
                //将单位转换为米保存
                if($m['unit']=='Y') $m['cntM']=0.9144*$m['cnt'];
                else $m['cntM']=$m['cnt'];

                if($m) $_temp[]=$m;
            }
            $v['Madan'] = $_temp;
            //退货入库时码单取对应批号的入库时间,2015-10-30,by liuxin
            foreach ($v['Madan'] as &$value) {
                $d_sql = "select a.rukuDate from cangku_ruku a
                            inner join cangku_ruku2product b on a.id=b.rukuId
                            where b.pihao = '{$value['millNo']}'";
                $_tempDate = $this->_modelExample->findBySql($d_sql);
                $value['rukuDate'] = $_tempDate[0]['rukuDate'] == ''?$_POST['chukuDate']:$_tempDate[0]['rukuDate'];
            }
        }
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