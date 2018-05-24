<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Xianhuo_Xsck extends Controller_Cangku_Chuku{

    function __construct() {
        parent::__construct();
        // $this->_chukushenhe = &FLEA::getSingleton('Model_Cangku_ChukuShenhe');
        $this->_cangkuName = __CANGKU_1;

        $this->_kind = "销售出库";

        $this->_head = "XSCK";

        $this->_check='3-1-7';
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
            'ship_name' => array('title'=>'收货人','type' => 'text', 'value' =>''),
            'ship_addr' => array('title'=>'收货地址','type' => 'text', 'value' =>''),
            'ship_tel' => array('title'=>'收货电话','type' => 'text', 'value' =>''),
            'ship_mobile' => array('title'=>'收货人手机','type' => 'text', 'value' =>''),
            'logi_no' => array('title'=>'物流单号','type' => 'text', 'value' =>''),
            'shipping' => array('title'=>'配送方式','type' => 'select', 'options' =>''),
            'corp_name' => array('title'=>'物流公司','type' => 'select', 'options' =>''),
            //添加运费结算方式，by liu，2017-1-10
            'pay_type' => array('title'=>'运费结算','type' => 'select', 'options' =>array(
                array('text'=>'现付','value'=>'1'),
                array('text'=>'到付','value'=>'2'),
                array('text'=>'月结','value'=>'3'),
                array('text'=>'第三方支付','value'=>'4'),
            )),
            'clientName' => array('title'=>'客户名称','type' => 'text', 'value' =>'','readonly'=>true),
            'kind' => array('title'=>'出库类型','type' => 'text', 'value' => $this->_kind,'readonly'=>true),
            'chuhuo_all' =>array(
                'type' => 'btnCommon',
                "title" => '高级功能',
                'textFld'=>'全部出货',
                // 'url'=>url('Caigou_Shengou','Popup')
                // 'dialogWidth'=>800
            ),
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
            'Madan' => array('type' => 'btBtnMadan', "title" => '配货单', 'name' => 'Madan[]'),
            'cnt' => array('type' => 'BtText', "title" => '本次数量', 'name' => 'cnt[]','readonly'=>true),
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
            'danjia' => array('type' => 'BtHidden', 'name' => 'danjia[]'),
            'money' => array('type' => 'BtHidden', 'name' => 'money[]'),
        );

        //表单元素的验证规则定义
        $this->rules = array(
            'kuweiId'=>'required',
            // 'shipping'=>'required',
        );

        $this->sonTpl = array('Cangku/Xianhuo/xsck_madan.tpl');
    }

    /**
     * 发货出库：现货发货和其他仓库发货不同，比较个性
     * Time：2015/09/18 16:40:03
     * @author li
    */
    function actionAdd(){
        //配货单id，如果传递过来的配货id为1,2,3这样的多条数据，可以支持多条数据一起出库，
        // 条件：客户相同，发货地址方式相同
        // $planId = (int)$_GET['id'];
        $planId = $_GET['id'];

        //出库单的数据
        $_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');
        $_condotion['in()']=explode(',',$planId);

        // 判断是否允许出库
        foreach ($_condotion['in()'] as $id) {
            // 出库前验证- 出库单校验
            $str="SELECT jiaoyan from chuku_plan where orderId='{$id}' and kind='现货仓库'";
            $_res = $_modelPlan->findBySql($str);

            //出库前验证状态是否为active
            $sql="SELECT y.status from chuku_plan x
                left join trade_order y on y.id=x.orderId
                where x.orderId='{$id}'";
            $re = $_modelPlan->findBySql($sql);
            if($_res[0]['jiaoyan']!='yes' || $re[0]['status']!='active'){
                echo "请检查订单是否能出库校验";exit;
            }
        };
        $sql="SELECT x.*,y.chengfen,y.shazhi,y.jingmi,y.weimi,y.menfu,y.proName
            from chuku_plan x
            left join jichu_product y on x.productId=y.proCode
            where x.orderId='{$planId}' and x.kind='现货仓库'";
        $plan = $_modelPlan->findBySql($sql);
        // dump($plan);die;

        //查找订单明细信息与订单主信息
        $_modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $_modelOrder->clearLinks();
        $_p = current($plan);
        $_order = $_modelOrder->find($_p['orderId']);
        // dump($_order);die;

        //查找客户信息
        $sql = "SELECT if(compName!='',compName,compCode) as compName from jichu_client where member_id = '{$_order['clientId']}'";
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
            //将物流公司缩写拼接在名称后面，by liu，2017-1-12
            if($v['corp_code']){
                $_tmp_corp_name = $v['name'].'-'.$v['corp_code'];
            }
            else{
                $_tmp_corp_name = $v['name'];
            }
            $_dlycorp[] = array('text'=>$_tmp_corp_name,'value'=>$_tmp_corp_name);
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

        //处理地址
        $_order['first_addr']=mb_substr($_order['ship_addr'],0,2,'utf-8');
        $arr = array('北京','上海','重庆','天津');

        if(!in_array($_order['first_addr'],$arr)){
            $aa=substr($_order['ship_addr'],6);
            $_order['address'] = $_order['first_addr'] .'省'.$aa;
        }

        //整理需要默认的数据信息
        $arr = array(
            'clientId'=>$_order['clientId'],
            'ship_name'=>$_order['ship_name'],
            'ship_addr'=>$_order['address'],
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
                'proName'=>$v['proName'],
                'danjia'=>$v['danjia'],
                'unit'=>'M',
                'proXinxi'=>'成分:'.$v['chengfen'].',纱支:'.$v['shazhi'].',经纬密:'.$v['jingmi'].'*'.$v['weimi'].',门幅:'.$v['menfu'],
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
        $smarty->assign('sonTitle', "选择配货单并出库(未校验不能够出库)");
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);

        $_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
        $smarty->assign('fromAction', $_from);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $smarty->display('Main2Son/T1.tpl');
    }

    /**
     * 修改
     * Time：2015/09/19 23:05:37
     * @author li
    */
    function actionEdit() {
        //出库单的数据
        $_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');

        $arr = $this->_modelExample->find(array('id' => $_GET['id']));

        $sql = "select if(compName!='',compName,compCode) as compName from jichu_client where member_id = '{$arr['clientId']}'";
        // dump($sql);exit;
        $_client = $this->_modelExample->findBySql($sql);
        $_client = $_client[0];
        $arr['clientName'] = $_client['compName'];

        //同步EC数据到进销存
        $_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');
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

        // dump($arr);exit;

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
            //将物流公司缩写拼接在名称后面，by liu，2017-1-12
            if($v['corp_code']){
                $_tmp_corp_name = $v['name'].'-'.$v['corp_code'];
            }
            else{
                $_tmp_corp_name = $v['name'];
            }
            $_dlycorp[] = array('text'=>$_tmp_corp_name,'value'=>$_tmp_corp_name);
            $_dlycorp_info[$v['name']]=$v['corp_id'];
        }
        //给下拉框添加选项
        $this->fldMain['shipping']['options'] = $_dlytype;
        $this->fldMain['corp_name']['options'] = $_dlycorp;

        // dump($arr);exit;
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        // dump($arr['Products']);exit;
        foreach($arr['Products'] as & $v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $_t = $_temp[0];
            $v['proName'] = $_t['proName'];
            $v['proXinxi'] = '成分:'.$_t['chengfen'].',纱支:'.$_t['shazhi'].',经纬密:'.$_t['jingmi'].'*'.$_t['weimi'].',门幅:'.$_t['menfu'];

            //获取码单
            $sql="select group_concat(madanId) as madanId from madan_rc2madan where chukuId = '{$v['id']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $_t = $_temp[0];
            // dump($_t);exit;
            $v['Madan']=$_t['madanId'];

        }

        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $rowsSon[] = $temp;
        }

        //补齐5行
        // dump($rowsSon);exit;
        $cnt = count($rowsSon);
        for($i=1;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }

        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('fromAction', $_GET['fromAction']);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $this->_beforeDisplayEdit($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }

    /**
     * 选择配货单并出库
     * Time：2015/09/19 15:24:29
     * @author li
    */
    function actionViewPeihuo(){
        $_phId = (int)$_GET['peihuoId'];
        $_planId = (int)$_GET['planId'];
        $_chukuId = (int)$_GET['chukuId'];
        if(!$_phId>0){
            echo "配货单不能为空，请返回重新操作";exit;
        }

        //查找配货单是否已经验证
        $_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');
        $plan = $_modelPlan->find($_planId);
        $product = $plan['Product'];
        // dump($plan);exit;
        if($plan['jiaoyan']!='yes'){
            echo "未校验成功，不能出库";exit;
        }

        //显示配货单的数据
        $_modelPeihuo = FLEA::getSingleton('Model_Peihuo_Peihuo');
        $_peihuo = $_modelPeihuo->find($_phId);
        // dump($_peihuo);exit;
        $_madanId = join(',',array_col_values($_peihuo['Peihuo'],'madanId'));
        if($_madanId!=''){
            $sql="select * from madan_db where id in ({$_madanId}) order by millNo,rollNo";
            $res = $_modelPeihuo->findBySql($sql);

            foreach ($res as $key => & $v) {
                //查找码单的情况
                $sql="select x.id,x.chukuId,x.madanId,y.status from madan_rc2madan x
                   left join madan_db y on x.madanId=y.id
                    where madanId='{$v['id']}' and chukuId<>0";
                $temp = $_modelPeihuo->findBySql($sql);
                if($temp[0]['status']=='finish' && $temp[0]['chukuId']!=$_chukuId){
                    $v['checked'] = true;
                    $v['disabled'] = true;
                    $v['title']="已经出库了，不能重复出库";
                }

                $v['cnt'] = round($v['cnt'],3);
                $v['cntM'] = round($v['cntM'],3);
                if($v['unit']=='Y'){
                    $v['cntMi']=$v['cnt']*0.9144;
                }else{
                    $v['cntMi']=$v['cntM'];
                }
            }

            //处理res数据集，按照批次号汇总
            $res = array_group_by($res,'millNo');
        }


        $_peihuo['Peihuo'] = $res;

        // dump($_peihuo);exit;
        // dump($_GET);exit;
        $smarty = & $this->_getView();
        $smarty->assign('Peihuo', $_peihuo);
        $smarty->assign('Product', $product);
        $smarty->assign('ispei', 'true');
        $smarty->display("Cangku/Xianhuo/PeihuoSelect.tpl");
    }

    /**
     * 销售出库保存
     * Time：2015/09/19 21:11:28
     * @author li
    */
    function actionSave(){
        // dump($_POST);exit;

        //获取ec上同步过来的数据
        $_wuliu = unserialize($_POST['wuliuInfo']);
        // dump($_wuliu);exit;

        //主表信息
        $row = array();
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
        }
        // dump($row);exit;

        //明细信息
        $pros = array();
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

            //清空所有的码单数据 修改保存的时候进行的
            if($delmadan[0]!=''&&$temp['id']>0){
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
                $_delsql = "delete from madan_rc2madan where chukuId='{$temp['id']}'";
                $this->_modelExample->execute($_delsql);
            }
            $temp['Madan'] = $madan_ck;
            // $temp['MadanJson'] =json_encode($madan_ck);
            $temp['cntJian'] = count($madan_ck);
            //将单位转换为米保存
            $temp['unit']=='' && $temp['unit']='M';//默认不能为空
            $temp['cntM'] = $temp['unit'] == 'M' ? $temp['cnt'] : $temp['cnt']*0.9144;
            //获取订单id
            //($temp['orderId']>0 && !$temp['id']>0) && $_orderId[]=$temp;
            $pros[]=$temp;
        }
        $maId = array_filter($maId);

        //查询订单的总数
        // $sql_r="select sum(cnt)as sumcnt from trade_order2product where orderId='{$_POST['orderId'][0]}' and peihuoId='0'";
        // $res_r=$this->_modelExample->findBySql($sql_r);

        // $sql_c="select sum(cnt)as sumcnt from cangku_chuku2product where orderId='{$_POST['orderId'][0]}' and peihuoId='0'";
        // $res_c=$this->_modelExample->findBySql($sql_c);
        // foreach ($pros as &$value) {
        //     $rows['cnt']+=$value['cnt'];
        // }

        // if($rows['cnt']+$res_c[0]['sumcnt']>$res_r[0]['sumcnt']){
        //     js_alert('请确认是否重复发货','window.history.go(-1)');
        //     exit;
        // }

        if(!$pros){
            js_alert('保存失败，没有有效明细数据','window.history.go(-1)');
            exit;
        }
        //锁定码单  只要是出库就得锁定码单 暂时只有现货需要这个功能
        if($maId[0]!=''&&$this->_cangkuName == __CANGKU_1){
            $maId=join(',',$maId);
            $sql="update madan_db set status='finish' where id in({$maId})";
            $this->_modelExample->findBySql($sql);
        }
        if($row['chukuCode']=='自动生成') $row['chukuCode']=$this->_getNewCode($this->_head,'cangku_chuku','chukuCode');
        // dump($pros);exit;
        $row['Products'] = $pros;
        // dump($row);exit;
        //添加送货人电话号码字段
        $sql = "select phone from acm_userdb where id = {$_SESSION['USERID']}";
        $row['creater_mobile']=$this->_modelExample->findBySql($sql);
        $row['creater_mobile']=implode("",$row['creater_mobile'][0]);
        $chukuId=$this->_modelExample->save($row);
        // if(!$this->_chukushenhe->save($row)) {
        //     js_alert('保存失败','window.history.go(-1)');
        //     exit;
        // }
        //销售出库的自动过账  只有新增的时候保存过账记录  修改不管
        $ret=$this->_modelExample->find(array('id'=>$chukuId));

        $this->_modelGuozhang = &FLEA::getSingleton('Model_Caiwu_Ar_Guozhang');
        $this->_modelOrder = &FLEA::getSingleton('Model_Trade_Order');
        foreach ($ret['Products'] as $key => &$v) {
            $arr['id']='';
            $arr['orderId']=$v['orderId'];
            $arr['clientId']=$ret['clientId'];
            $arr['chukuDate']=$ret['chukuDate'];
            $arr['ord2proId']=$v['ord2proId'];
            $arr['chuku2proId']=$v['id'];
            $arr['chukuId']=$v['chukuId'];
            $arr['productId']=$v['productId'];
            $arr['cnt']=$v['cnt'];
            $arr['unit']=$v['unit'];
            $arr['kind']='销售出库';
            $arr['guozhangDate']=date('Y-m-d');
            $arr['creater']=$_SESSION['REALNAME'].'';
            $arr['danjia']=$v['danjia'];
            $arr['_money']=$v['cntM']*$v['danjia'];
            $arr['money']=$v['cntM']*$v['danjia'];
            if(!$this->_modelGuozhang->save($arr)) {
                js_alert('保存失败','window.history.go(-1)');
            }
        }
        //运费自动过账
        $res_freight=$this->_modelOrder->find(array('id'=>$pros[0]['orderId']));
        $guo_freight=$this->_modelGuozhang->find(array('orderId'=>$pros[0]['orderId'],'kind'=>'运费过账'));
        // dump($guo_freight);die;
        if($res_freight['cost_freight'] != '0.000' && !$guo_freight){
            $order_freight['id']='';
            $order_freight['orderId']=$pros[0]['orderId'];
            $order_freight['chukuId']=$chukuId;
            $order_freight['chukuDate']=$ret['chukuDate'];
            $order_freight['kind']='运费过账';
            $order_freight['guozhangDate']=date('Y-m-d');
            $order_freight['clientId']=$row['clientId'];
            $order_freight['_money']=$res_freight['cost_freight'];
            $order_freight['money']=$res_freight['cost_freight'];
            if(!$this->_modelGuozhang->save($order_freight)) {
                js_alert('运费过账失败','window.history.go(-1)');
                exit;
            }
        }
        //订单减免自动过账
        // dump($guo_freight);die;
        $guo_derate=$this->_modelGuozhang->find(array('orderId'=>$pros[0]['orderId'],'kind'=>'订单减免'));
        if($res_freight['discount'] != '0.000' && !$guo_derate){
            $order_discount['id']='';
            $order_discount['orderId']=$pros[0]['orderId'];
            $order_discount['chukuId']=$chukuId;
            $order_discount['chukuDate']=$ret['chukuDate'];
            $order_discount['kind']='订单减免';
            $order_discount['guozhangDate']=date('Y-m-d');
            $order_discount['clientId']=$row['clientId'];
            $order_discount['_money']=$res_freight['discount'];
            $order_discount['money']=$res_freight['discount'];
            if(!$this->_modelGuozhang->save($order_discount)) {
                js_alert('订单减免过账失败','window.history.go(-1)');
                exit;
            }
        }
        //订单优惠自动过账
        // dump($guo_freight);die;
        $guo_discount=$this->_modelGuozhang->find(array('orderId'=>$pros[0]['orderId'],'kind'=>'订单优惠'));
        if($res_freight['pmt_order'] != '0.000' && !$guo_discount){
            $order_discount['id']='';
            $order_discount['orderId']=$pros[0]['orderId'];
            $order_discount['chukuId']=$chukuId;
            $order_discount['chukuDate']=$ret['chukuDate'];
            $order_discount['kind']='订单优惠';
            $order_discount['guozhangDate']=date('Y-m-d');
            $order_discount['clientId']=$row['clientId'];
            $order_discount['_money']=$res_freight['pmt_order'];
            $order_discount['money']= -($res_freight['pmt_order']);
            if(!$this->_modelGuozhang->save($order_discount)) {
                js_alert('订单优惠过账失败','window.history.go(-1)');
                exit;
            }
        }
        //同步发货状态
        //查找订单的发货总状态
        $pros_order = array_col_values($pros,'orderId');
        $pros_order = array_unique($pros_order);
        $pros_order = array_filter($pros_order);
        count($pros_order)>0 && $this->ship_status_toec($pros_order);
        //跳转
        // js_alert('保存成功','window.history.go(-1)');
        js_alert(null,'window.parent.showMsg("保存成功!")',url('Cangku_Xianhuo_Plan',$_POST['fromAction']));

    }

    function actionRight(){
        //权限判断
        $this->authCheck($this->_check);
        FLEA::loadClass('TMIS_Pager');

        //构造搜索区域的搜索类型
        $arr = TMIS_Pager::getParamArray(array(
            'orderCode' =>'',
            'danhao' => '',
            'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
            'dateTo' => date("Y-m-d"),
            'proCode'=>'',
            'clientId'=>'',
            'kuweiId_name'=>'',
            'kuweiName'=>$this->_cangkuName,
        ));

        $condition=array();
        $condition[]=array('Ck.cangkuName',$this->_cangkuName,'=');
        $condition[]=array('Ck.kind',$this->_kind,'=');

        $conSql = " and b.cangkuName='{$this->_cangkuName}'";
        $conSql .= " and b.kind='{$this->_kind}'";

        //添加按订单号查询，2015-10-20，by liuxin
        if($arr['orderCode']!=''){
            $sqlOrder = "select id
                        from trade_order
                        where orderCode like '%{$arr['orderCode']}%'";
            $orderCode = $this->_modelExample->findBySql($sqlOrder);
            $condition[]=array('orderId',"%{$orderCode['0']['id']}%",'like');

            $conSql .= " and a.orderId like '%{$orderCode['0']['id']}%'";
        }
        if($arr['danhao']!=''){
            $condition[]=array('Ck.chukuCode',"%{$arr['danhao']}%",'like');

            $conSql .= " and b.chukuCode like '%{$arr['danhao']}%'";
        }
        if($arr['proCode']!=''){
            $condition[]=array('productId',"%{$arr['proCode']}%",'like');

            $conSql .= " and a.productId like '%{$arr['proCode']}%'";
        }
        if($arr['kuweiId_name']!=''){
            $condition[]=array('Ck.kuweiId',"{$arr['kuweiId_name']}",'=');

            $conSql .= " and b.kuweiId='{$arr['kuweiId_name']}'";
        }
        if($arr['clientId']!=''){
            $condition[]=array('Ck.clientId',"{$arr['clientId']}",'=');
            $conSql .= " and b.clientId='{$arr['clientId']}'";
        }
        //增加日期搜索功能
        if($arr['dateFrom'] != '') {
            $condition[]=array('Ck.chukuDate',"{$arr['dateFrom']}",'>=');
            $conSql .= " and b.chukuDate>='{$arr['dateFrom']}'";

        }
        if($arr['dateTo'] != '') {
            $condition[]=array('Ck.chukuDate',"{$arr['dateTo']}",'<=');
            $conSql .= " and b.chukuDate<='{$arr['dateTo']}'";
        }

        //查找计划
        if ($_GET['export']==2) {
            // dump($_POST);dump($conSql);
            // dump($this->_subModel);exit;
            // $rowset = $this->_subModel->findAll($condition,'chukuCode desc');
            // $rowset = $this->_subModel->findAll();
            $sql = "select
            count(*) as cnt
            from cangku_chuku2product a
            left join cangku_chuku b on a.chukuId=b.id
            where 1 {$conSql}";
            $rows = $this->_modelExample->findBySql($sql);
            if($rows[0]['cnt']>5000) {
                js_alert('结果集超过5000条，为防止拖死服务器，禁止导出，请缩小搜索范围后重新导出',null,url($_GET['controller'],'right'));
                exit;
            }
            $sql = "select
            a.*,b.chukuCode,b.chukuDate,b.cangkuName,b.logi_no,b.kuweiId
            from cangku_chuku2product a
            left join cangku_chuku b on a.chukuId=b.id
            where 1 {$conSql}";
            $rowset = $this->_modelExample->findBySql($sql);
            foreach($rowset as & $v) {
                $v['Ck']['chukuCode'] = $v['chukuCode'];
                $v['Ck']['chukuDate'] = $v['chukuDate'];
                $v['Ck']['cangkuName'] = $v['cangkuName'];
                $v['Ck']['logi_no'] = $v['logi_no'];
                $v['Ck']['kuweiId'] = $v['kuweiId'];
            }
            // dump($sql);dump($rowset);exit;
        } else {
            // dump($condition);dump($conSql);exit;
            $pager = &new TMIS_Pager($this->_subModel,$condition,'chukuCode desc');
            $rowset = $pager->findAll();
        }
        // dump($rowset);exit;
        foreach($rowset as & $v) {
            $v['Ck']['chukuDate']=$v['Ck']['chukuDate']=='0000-00-00'?'':$v['Ck']['chukuDate'];

            //2015-10-29 by jiang 已过账禁止修改和删除
            $sql="select * from caiwu_ar_guozhang where chuku2proId='{$v['id']}'";
            $ret=$this->_modelExample->findBySql($sql);
            if(count($ret)>0){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已过账禁止修改和删除'></span>";
                $v['_edit'].="&nbsp;&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已过账禁止修改和删除'></span>";
            }else{
                $v['_edit'] = $this->getEditHtml($v['Ck']['id']);
                //删除操作
                $v['_edit'] .= '&nbsp;&nbsp;' .$this->getRemoveHtml($v['Ck']['id']);
            }
            if($v['Ck']['cangkuName']=='现货仓库'){
                //打印出库单标签
                $v['_edit'].= "&nbsp;&nbsp;<a href='".$this->_url('Printlab',array(
                                'id'=>$v['Ck']['id'],
                                'fromAction'=>$_GET['action'],
                                'printkind'=>0,
                                'fromController'=>$_GET['controller'],
                        ))."'><span class='glyphicon glyphicon-comment' ext:qtip='出库单常规标签'></span></a>";
                $v['_edit'].= "&nbsp;&nbsp;<a href='".$this->_url('Printlab',array(
                                'id'=>$v['Ck']['id'],
                                'fromAction'=>$_GET['action'],
                                'printkind'=>1,
                                'fromController'=>$_GET['controller'],
                        ))."'><span class='glyphicon glyphicon-duplicate' ext:qtip='出库单内容标签'></span></a>";
            }
            if($v['Ck']['cangkuName']=='现货仓库'){
                $v['isChecked'] = "<input type='checkbox' name='chk[]' id='chk[]' value='{$v['chukuId']}'/>";
            }
            if(($v['Ck']['logi_no'] && $v['Ck']['print_tpl']) || !$v['Ck']['logi_no']){
                $v['_edit'].= "&nbsp;&nbsp;<a href='".$this->_url('Express',array(
                                'id'=>$v['Ck']['id'],
                                'fromAction'=>$_GET['action'],
                                'printkind'=>0,
                                'fromController'=>$_GET['controller'],
                        ))."'><span class='glyphicon glyphicon-bed' ext:qtip='电子面单打印'></span></a>";
            }
            else{
                $v['_edit'].="&nbsp;&nbsp;<span class='glyphicon glyphicon-bed' ext:qtip='该出库单不可打印电子面单'></span>";
            }
            //添加同步按钮
           /* $v['_edit'] .= '&nbsp;&nbsp;'."<a href='".$this->_url('ApiToEcChuku',array(
                    'id'=>$v['Ck']['id'],
            ))."' onclick='return confirm(\"同步之后客户将看到发货信息，不能随便更改！\");layer.msg(\"玩命同步中，不要操作，以免中断\",100);' ext:qtip='同步到EC、同步发货信息'><span class='glyphicon glyphicon-retweet'></span></a>";*/

            //查找对应的产品明细信息
            $sql="select proCode,proName,zhengli,menfu,kezhong,chengfen,wuliaoKind,zuzhi
                from jichu_product where proCode='{$v['productId']}'";
            $res = $this->_modelExample->findBySql($sql);
            // dump($sql);exit;
            $res[0] && $v+=$res[0];
            //查询对应的物流单号和物流公司
            $str="SELECT x.corp_name,x.logi_no
                  from cangku_chuku x
                  where x.id = '{$v['chukuId']}' group by x.chukuCode ";
            $res = $this->_modelExample->findBySql($str);
            $v['corp_name'] = $res[0]['corp_name'];
            $v['logi_no'] = $res[0]['logi_no'];
            //查找库位
            $sql="select * from jichu_kuwei where id='{$v['Ck']['kuweiId']}'";
            $res = $this->_modelExample->findBySql($sql);
            $v['cangkuName'] = $res[0]['kuweiName'];
            $v['cnt']=round($v['cnt'],2);
            //查找订单与客户信息
            $sql="select x.orderCode ,if(c.compName='',c.compCode,compName) as compName
            from trade_order x
            left join jichu_client c on c.member_id = x.clientId
            where x.id = '{$v['orderId']}'";
            $res = $this->_modelExample->findBySql($sql);
            $v['orderCode'] = $res[0]['orderCode'];
            $v['compName'] = $res[0]['compName'];
            if($v['Ck']['cangkuName']=='现货仓库'){
                $v['isChecked'] = "<input type='checkbox' name='chk[]' id='chk[]' value='{$v['id']}'/>";
            }

            $v['cntJian'] =count($v['Madan']);
        }
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'_edit');
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'];

        //导出去掉合计标签
        if($_GET['export']==1){
            $hj['orderCode'] = strip_tags($hj['_edit']);
            $hj['cnt']=$hj['cntM'];
            unset($hj['_edit']);
        }


        $rowset[]=$hj;
        $smarty = &$this->_getView();
        // 左侧信息
        $arr_field_info = array(
            "isChecked"=>array("text"=>"<input type='checkbox' id='checkedAll' title='全选/反选'/>","width"=>"30",),
            "_edit" => '操作',
            "orderCode" => array('text'=>'订单编号','width'=>130),
            "Ck.chukuCode" => '出库单号',
            "Ck.chukuDate" => '出库日期',
            "corp_name" => '物流公司名称',
            "logi_no" => '物流单号',
            "compName" => '客户',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "chengfen" => '成分',
            "menfu" => '门幅',
            "kezhong" => '克重',
            "wuliaoKind" => '物料大类',
            "zhengli" => '整理方式',
            "zuzhi" => '组织大类',
            "cangkuName" => '库位',
            "cnt" => '数量',
            "cntJian" => '卷数',
            "unit" => '单位',

        );
        if($this->_cangkuName=='样品仓库') unset($arr_field_info['isChecked']);
        if($this->_cangkuName=='现货仓库'){
        $other_url='<button type="button" class="btn btn-info btn-sm" id="print" name="print">常规标签打印';
        $other_url.='<button type="button" class="btn btn-info btn-sm" id="print2" name="print2">内容标签打印';
        $smarty->assign('other_url', $other_url);
        }

        $smarty->assign('title', '入库列表');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        if($pager) $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->assign('sonTpl', "Cangku/Xianhuo/xsck_printlab.tpl");
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
        $smarty->assign('fn_export1',$this->_url($_GET['action'],array('export'=>2)));
        if($_GET['export']==1){
            foreach ($rowset as $key => & $value) {
                foreach ($value as $key => & $v) {
                    $v = strip_tags($v);
                }
                unset($value['isChecked']);
                unset($value['_edit']);
            }
            unset($arr_field_info['isChecked']);
            unset($arr_field_info['_edit']);
            $this->_exportList(array('title'=>$title),$smarty);
        } elseif ($_GET['export']==2) {
            unset($arr_field_info['_edit']);
            unset($arr_field_info['isChecked']);
            $arr_field_info = array_merge(array('num'=>'序号') ,$arr_field_info);
            $i=1;
            foreach($rowset as & $v) {
                $v['num'] = $i++;
            }
            // dump($rowset);exit;
            $smarty->assign('arr_field_info', $arr_field_info);
            $smarty->assign('arr_field_value', $rowset);
            $this->_exportList(array('title'=>$title),$smarty);
        }
        $smarty->display('TblList.tpl');
    }

    function actionRemoveByAjax() {
        //将码单取消锁定  暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $sql="update madan_db set status='active' where id in(
                    select madanId from madan_rc2madan where chukuId='{$_POST['id']}'
                    )";
            $this->_modelExample->findBySql($sql);
        }
        parent::actionRemoveByAjax();
    }

    function actionRemove(){
        //将码单取消锁定 暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $sql="update madan_db set status='active' where id in(
                    select madanId from madan_rc2madan x
                    left join cangku_chuku2product y on x.chukuId=y.id
                     where y.chukuId='{$_GET['id']}'
                    )";
            $this->_modelExample->findBySql($sql);
        }
        parent::actionRemove();
    }
    /**
     * ps ：现货标签打印
     * Time：2016年2月29日16:23:48
     * @author jiangxu
     * @param 参数类型
     * @return 返回值类型
    */
    function actionPrintlab(){
        $res_order=$this->_subModel->find(array('chukuId'=>$_GET['id']));
        $arr=$this->_subModel->findAll(array('orderId'=>$res_order['orderId']));
        // dump($arr);exit;
        foreach ($arr as $key => &$value) {
            $rows="SELECT orderCode
                from trade_order
                where id='{$value['orderId']}'";
            $rowsets=$this->_modelExample->findBySql($rows);
            // dump($rowsets);exit;
            //查找门幅，克重，纱织，整理
            $sql="SELECT menfu,kezhong,chengfen,shazhi,zhengli
                from jichu_product
                where proCode='{$value['productId']}'";
            $temp=$this->_modelExample->findBySql($sql);
            //查找公司名称
            $str="SELECT compName,
                if(compName='',compCode,compName) as compName
                from jichu_client
                where member_id='{$value['Ck']['clientId']}' ";
            $aRow=$this->_modelExample->findBySql($str);
            // dump($aRow);exit;

            $brr['chukuCode']=$value['Ck']['chukuCode'];
            $brr['ship_name']=$value['Ck']['ship_name'];
            $brr['ship_addr']=$value['Ck']['ship_addr'];
            $brr['dt']=$value['Ck']['dt'];

            if($value['Ck']['ship_mobile']=='') $brr['Ck']['ship_mobile']=$value['Ck']['ship_tel'];
            $brr['ship_mobile']=$value['Ck']['ship_mobile'];
            $brr['productId']=$value['productId'];
            $brr['menfu']=$temp[0]['menfu'];
            $brr['orderCode']=$rowsets[0]['orderCode'];
            $brr['kezhong']=$temp[0]['kezhong'];
            $brr['chengfen']=$temp[0]['chengfen'];
            $brr['shazhi']=$temp[0]['shazhi'];
            $brr['zhengli']=$temp[0]['zhengli'];
            $brr['compName']=$aRow[0]['compName'];
            $brr['cntM']=round($value['cntM'],2);
            $this->_modelMadan = FLEA::getSingleton('Model_Cangku_Madan');
            foreach ($value['Madan'] as $key => &$v) {
                $row=$this->_modelMadan->find(array('id'=>$v['madanId']));
                // $arr['totalM']+=$row['cntM'];
                $brr['buLength']=$row['cntM'];
                $brr['qrcode']=$row['qrcode'];
                $brr['rollNo']=$row['rollNo'];
                $brr['cntJian']=count($value['Madan']);
                $brr['yema']=$key+1;
                $brr['buLength']=round($row['cntM'],2);
                $rowset[]=$brr;
            }
        }
        // dump($rowset);exit;
        $smarty = & $this->_getView();
        $smarty->assign('row',$rowset);
        if($_GET['printkind']=='0'){
             $smarty->display('Cangku/Printlab.tpl');
        }else{
             $smarty->display('Cangku/PrintNormal.tpl');
        }
    }

   /**
     * ps ：样品标签打印
     * Time：2016年2月29日16:08:23
     * @author jiangxu
     * @param 参数类型
     * @return 返回值类型
    */
    function actionPrintYplab(){
        $arr=$this->_subModel->find(array('id'=>$_GET['id']));
        $sql="select * from jichu_product where proCode='{$arr['productId']}'";
        $temp=$this->_modelExample->findBySql($sql);
        $arr['menfu']=$temp[0]['menfu'];
        $arr['kezhong']=$temp[0]['kezhong'];
        $arr['chengfen']=$temp[0]['chengfen'];
        $arr['zhengli']=$temp[0]['zhengli'];
        $arr['jingmi']=$temp[0]['jingmi'];
        $arr['weimi']=$temp[0]['weimi'];
        $arr['shazhi']=$temp[0]['shazhi'];
        $arr['dt']=$arr['Ck']['dt'];
        $rowset[]=$arr;
        // dump($rowset);exit;
        $smarty = & $this->_getView();
        $smarty->assign('row',$rowset);
        if($_GET['printkind']=='0')
             $smarty->display('Cangku/PrintYplab.tpl');

    }

    /**
     * ps ：现货标签选择打印
     * Time：2016年5月4日 13:48:13
     * @author jiangxu
     * @param 参数类型
     * @return 返回值类型
    */
    function actionPrintSelect(){
        $id = $_GET['id'];
        $rowset = explode(",",$id);
        foreach ($rowset as $k => $v) {
        $res_order = $this->_subModel->find(array('id'=>$v));
                $rows="SELECT orderCode
                    from trade_order
                    where id ='{$res_order['orderId']}'";
                $rowsets=$this->_modelExample->findBySql($rows);
                // dump($rows);
                //查找门幅，克重，纱织，整理
                $sql="SELECT menfu,kezhong,chengfen,shazhi,zhengli
                    from jichu_product
                    where proCode='{$res_order['productId']}'";
                $temp=$this->_modelExample->findBySql($sql);
                //查找公司名称
                $str="SELECT compName,
                    if(compName='',compCode,compName) as compName
                    from jichu_client
                    where member_id='{$res_order['Ck']['clientId']}' ";
                $aRow=$this->_modelExample->findBySql($str);

                $brr['chukuCode']=$res_order['Ck']['chukuCode'];
                $brr['ship_name']=$res_order['Ck']['ship_name'];
                $brr['ship_addr']=$res_order['Ck']['ship_addr'];
                $brr['dt']=$res_order['Ck']['dt'];

                if($res_order['Ck']['ship_mobile']=='') $brr['Ck']['ship_mobile']=$res_order['Ck']['ship_tel'];
                $brr['ship_mobile']=$res_order['Ck']['ship_mobile'];
                $brr['productId']=$res_order['productId'];
                $brr['menfu']=$temp[0]['menfu'];
                $brr['orderCode']=$rowsets[0]['orderCode'];
                $brr['kezhong']=$temp[0]['kezhong'];
                $brr['chengfen']=$temp[0]['chengfen'];
                $brr['shazhi']=$temp[0]['shazhi'];
                $brr['zhengli']=$temp[0]['zhengli'];
                $brr['compName']=$aRow[0]['compName'];
                $brr['cntM']=round($res_order['cntM'],2);
                $this->_modelMadan = FLEA::getSingleton('Model_Cangku_Madan');
                foreach ($res_order['Madan'] as  &$vv) {
                    $row=$this->_modelMadan->find(array('id'=>$vv['madanId']));
                    // $arr['totalM']+=$row['cntM'];
                    $brr['buLength']=$row['cntM'];
                    $brr['qrcode']=$row['qrcode'];
                    $brr['rollNo']=$row['rollNo'];
                    $brr['cntJian']=count($res_order['Madan']);
                    $brr['yema']=$key+1;
                    $brr['buLength']=round($row['cntM'],2);
                    $print[]=$brr;
                }

        }
        // dump($res_order);exit;
        // dump($print);exit;

        $smarty = & $this->_getView();
        $smarty->assign('row',$print);
        $smarty->display('Cangku/Printlab.tpl');

    }
    /**
     * ps ：现货标签选择打印
     * Time：2016年5月4日 13:48:13
     * @author jiangxu
     * @param 参数类型
     * @return 返回值类型
    */
    function actionPrintSelect2(){
        $id = $_GET['id'];
        $rowset = explode(",",$id);
        foreach ($rowset as $k => $v) {
        $res_order = $this->_subModel->find(array('id'=>$v));
                $rows="SELECT orderCode
                    from trade_order
                    where id ='{$res_order['orderId']}'";
                $rowsets=$this->_modelExample->findBySql($rows);
                // dump($rows);
                //查找门幅，克重，纱织，整理
                $sql="SELECT menfu,kezhong,chengfen,shazhi,zhengli
                    from jichu_product
                    where proCode='{$res_order['productId']}'";
                $temp=$this->_modelExample->findBySql($sql);
                //查找公司名称
                $str="SELECT compName,
                    if(compName='',compCode,compName) as compName
                    from jichu_client
                    where member_id='{$res_order['Ck']['clientId']}' ";
                $aRow=$this->_modelExample->findBySql($str);

                $brr['chukuCode']=$res_order['Ck']['chukuCode'];
                $brr['ship_name']=$res_order['Ck']['ship_name'];
                $brr['ship_addr']=$res_order['Ck']['ship_addr'];
                $brr['dt']=$res_order['Ck']['dt'];

                if($res_order['Ck']['ship_mobile']=='') $brr['Ck']['ship_mobile']=$res_order['Ck']['ship_tel'];
                $brr['ship_mobile']=$res_order['Ck']['ship_mobile'];
                $brr['productId']=$res_order['productId'];
                $brr['menfu']=$temp[0]['menfu'];
                $brr['orderCode']=$rowsets[0]['orderCode'];
                $brr['kezhong']=$temp[0]['kezhong'];
                $brr['chengfen']=$temp[0]['chengfen'];
                $brr['shazhi']=$temp[0]['shazhi'];
                $brr['zhengli']=$temp[0]['zhengli'];
                $brr['compName']=$aRow[0]['compName'];
                $brr['cntM']=round($res_order['cntM'],2);
                $this->_modelMadan = FLEA::getSingleton('Model_Cangku_Madan');
                foreach ($res_order['Madan'] as  &$vv) {
                    $row=$this->_modelMadan->find(array('id'=>$vv['madanId']));
                    // $arr['totalM']+=$row['cntM'];
                    $brr['buLength']=$row['cntM'];
                    $brr['qrcode']=$row['qrcode'];
                    $brr['rollNo']=$row['rollNo'];
                    $brr['cntJian']=count($res_order['Madan']);
                    $brr['yema']=$key+1;
                    $brr['buLength']=round($row['cntM'],2);
                    $print[]=$brr;
                }

        }
        // dump($res_order);exit;
        // dump($print);exit;

        $smarty = & $this->_getView();
        $smarty->assign('row',$print);
        $smarty->display('Cangku/PrintNormal.tpl');

    }


    function actionEditShenhe(){

        $_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');

        $arr = $this->_chukushenhe->find(array('id' => $_GET['id']));

        $sql = "select if(compName!='',compName,compCode) as compName from jichu_client where member_id = '{$arr['clientId']}'";
        // dump($sql);exit;
        $_client = $this->_modelExample->findBySql($sql);
        $_client = $_client[0];
        $arr['clientName'] = $_client['compName'];

        //同步EC数据到进销存
        $_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');
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

        // dump($arr);exit;

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

        // dump($arr);exit;
        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        // dump($arr['Products']);exit;
        foreach($arr['Products'] as & $v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $_t = $_temp[0];
            $v['proName'] = $_t['proName'];
            $v['proXinxi'] = '成分:'.$_t['chengfen'].',纱支:'.$_t['shazhi'].',经纬密:'.$_t['jingmi'].'*'.$_t['weimi'].',门幅:'.$_t['menfu'];

            //获取码单
            $sql="select group_concat(madanId) as madanId from madan_rc2madan where chukuId = '{$v['id']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $_t = $_temp[0];
            // dump($_t);exit;
            $v['Madan']=$_t['madanId'];

        }

        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $rowsSon[] = $temp;
        }

        //补齐5行
        // dump($rowsSon);exit;
        $cnt = count($rowsSon);
        for($i=1;$i>$cnt;$i--) {
            $rowsSon[] = array();
        }

        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('fromAction', $_GET['fromAction']);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $this->_beforeDisplayEdit($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }

    /**
     * ps ：自动出货
     * Time：2016年3月16日 15:09:38
     * @author shen
    */

    function actionAutoPeihuo(){

        $sql="SELECT x.id,group_concat(y.madanId) as madanId,sum(z.cntM) as cntM,z.productId
            from ph_peihuo x
            left join ph_peihuo2madan y on x.id=y.phId
            left join madan_db z on y.madanId=z.id
            where x.id in ({$_POST['peihuoId']}) and z.status<>'finish' group by x.id";
        $rowset=$this->_modelExample->findBySql($sql);

        $result=array_values($rowset);
        $jsonData=json_encode($result);
        echo json_encode(array('success'=>true,'data'=>$rowset,'dataMadan'=>$result));exit;
    }
        /**
     * ps ：电子面单
     * Time：2016年5月30日 08:29:24
     * @author jiangxu
    */
    function actionExpress(){
        $res_order=$this->_subModel->find(array('chukuId'=>$_GET['id']));
        // dump($res_order);die;
        if($res_order['Ck']['logi_no'] && !$res_order['Ck']['print_tpl']){
            echo "该出库单不可打印电子面单";exit();
        }
        // dump($res_order);die;
        if(!$res_order['Ck']['print_tpl']){
            // dump(1);die;
            // $arr=$this->_subModel->findAll(array('orderId'=>$res_order['orderId']));
            $sql = "select orderCode,money,payment from trade_order where id = {$res_order['orderId']}";
            $orderCode = $this->_subModel->findBySql($sql);
            // dump($res_order);die;

            $corp = explode('-', $res_order['Ck']['corp_name']);
            $corp_code = $corp[1];
            $obj_api = FLEA::getSingleton('Api_Lib_Express');
            $kdbird_config = $obj_api->_get_config();
            if(isset($kdbird_config['Corp'][$corp_code])){
                $corp_info = $kdbird_config['Corp'][$corp_code];
                $corp_info['ShipperCode'] = $corp_code;
            }
            else{
                echo "该物流不支持电子面单，或尚未设置物流信息！";exit();
            }


            //截取地址获得所需要的省份和市名称
            $res_order['Ck']['ProvinceName'] = explode('省',$res_order['Ck']['ship_addr']);
            if($res_order['Ck']['ProvinceName'][1]){
               $res_order['Ck']['CityName'] = explode('市',$res_order['Ck']['ProvinceName'][1]);
            }else{
               $res_order['Ck']['CityName'] = explode('市',$res_order['Ck']['ProvinceName'][0]);
            }
            if(mb_strlen($res_order['Ck']['ProvinceName'][0],'UTF8')>3)$res_order['Ck']['ProvinceName'][0] = $res_order['Ck']['CityName'][0];
            $rowset = array(
                'OrderCode'=> $orderCode['0']['orderCode'],
                // 'LogisticCode' => $res_order['Ck']['logi_no'],
                'PayType' => $res_order['Ck']['pay_type']?:'3',
                'ExpType' => 1,
                'Remark' => $res_order['Ck']['memo'],
                'Receiver' =>array(
                    'Name' => $res_order['Ck']['ship_name'],
                    'Tel' => $res_order['Ck']['ship_tel'],
                    'Mobile' => $res_order['Ck']['ship_mobile'],
                    'ProvinceName' => $res_order['Ck']['ProvinceName'][0].'省',
                    'CityName' => $res_order['Ck']['CityName'][0].'市',
                    'Address' => $res_order['Ck']['ship_addr'],
                    ),
                'Commodity' =>array(
                    array(
                    'GoodsName' => $res_order['productId'],
                    )),
                'IsReturnPrintTemplate' =>1,
            );

            $rowset = array_merge($corp_info,$rowset);
            $result = $obj_api->payOrder($rowset);
            if($result['Order']['LogisticCode']){
                $sql="update cangku_chuku set logi_no='{$result['Order']['LogisticCode']}',print_tpl='{$result['PrintTemplate']}' where id = '{$_GET['id']}'";
                $this->_modelExample->findBySql($sql);
            }
            $PrintTemplate = $result['PrintTemplate'];
        }
        else{
            $PrintTemplate = $res_order['Ck']['print_tpl'];
        }

        // echo $PrintTemplate ;exit();
        $smarty = &$this->_getView();
        $smarty->assign('result', $PrintTemplate);
        $smarty->display('Cangku/PrintExpress.tpl');
    }

}
?>