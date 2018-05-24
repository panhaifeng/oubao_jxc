<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Xianhuo_Shck extends Controller_Cangku_Chuku{

    function __construct() {
        parent::__construct();
        
        $this->_chukushenhe = &FLEA::getSingleton('Model_Cangku_ChukuShenhe');
        $this->_chukushenhe2product = &FLEA::getSingleton('Model_Cangku_ChukuShenhe2Product');
        $this->_cangkuName = __CANGKU_1;
        $this->_kind = "销售出库";

        $this->_check='3-1-7';


        //表单元素的验证规则定义
        $this->rules = array(
            'kuweiId'=>'required',
            // 'shipping'=>'required',
        );

        $this->sonTpl = array('Cangku/Xianhuo/xsck_madan.tpl');
    }

    function actionShenheList(){
        
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
        
        //添加按订单号查询，2015-10-20，by liuxin
        if($arr['orderCode']!=''){
            $sqlOrder = "select id
                        from trade_order
                        where orderCode like '%{$arr['orderCode']}%'";
            $orderCode = $this->_chukushenhe->findBySql($sqlOrder);
            $condition[]=array('orderId',"%{$orderCode['0']['id']}%",'like');
        }
        if($arr['danhao']!=''){
            $condition[]=array('Ck.chukuCode',"%{$arr['danhao']}%",'like');
        }
        if($arr['proCode']!=''){
            $condition[]=array('productId',"%{$arr['proCode']}%",'like');
        }
        // if($arr['kuweiId_name']!=''){
        //     $condition[]=array('Ck.kuweiId',"{$arr['kuweiId_name']}",'=');
        // }
        if($arr['clientId']!=''){
            $condition[]=array('Ck.clientId',"{$arr['clientId']}",'=');
        }
        //增加日期搜索功能
        if($arr['dateFrom'] != '') {
             $condition[]=array('Ck.chukuDate',"{$arr['dateFrom']}",'>=');
        }
        if($arr['dateTo'] != '') {
             $condition[]=array('Ck.chukuDate',"{$arr['dateTo']}",'<=');
        }
        //查找计划
        $pager = &new TMIS_Pager($this->_chukushenhe2product,$condition,'chukuCode desc');
        $rowset = $pager->findAll();
        // dump($rowset);die;
        foreach($rowset as & $v) {
            $v['Ck']['chukuDate']=$v['Ck']['chukuDate']=='0000-00-00'?'':$v['Ck']['chukuDate'];

            //2015-10-29 by jiang 已过账禁止修改和删除
            $sql="select * from caiwu_ar_guozhang where chuku2proId='{$v['id']}'";
            $ret=$this->_modelExample->findBySql($sql);
            if(count($ret)>0){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已过账禁止修改和删除'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已过账禁止修改和删除'></span>";
            }else{
                if($v['Ck']['isCheck']==1){
                    $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已审核禁止修改和删除'></span>";
                    $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已审核禁止修改和删除'></span>";
                    $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-ok ' ext:qtip='已审核'></span>";
                }else{
                     if($v['Ck']['kind']=='销售出库'){ 
                        $v['_edit']="<a href='".url('Cangku_Xianhuo_Xsck','EditShenhe',array('id'=>$v['Ck']['id'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
                    }else{
                        $v['_edit'] .= $this->getEditHtml($v['Ck']['id']);
                    }
                    //删除操作
                    $v['_edit'] .= '&nbsp;&nbsp;' .$this->getRemoveHtml($v['Ck']['id']);
                    //审核操作
                    $v['_edit'] .='&nbsp;&nbsp;'. "<a href='".$this->_url('SetShenhe',
                        array('id'=>$v['Ck']['id'],
                            'fromAction'=>$_GET['action']
                            ))."'><span class='glyphicon glyphicon-ok' title='审核'></span></a>";
                    }
               
            }

           
           

            //查找对应的产品明细信息
            $sql="select proCode,proName,zhengli,menfu,kezhong,chengfen,wuliaoKind,zuzhi 
                from jichu_product where proCode='{$v['productId']}'";
            $res = $this->_modelExample->findBySql($sql);
            // dump($sql);exit;
            $res[0] && $v+=$res[0];

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

        }
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'_edit'); 
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'].' M';
        $rowset[]=$hj;

        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            "_edit" => '操作',
            "Ck.chukuCode" => '出库单号',
            "Ck.chukuDate" => '出库日期',
            "orderCode" => '订单号',
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
            "unit" => '单位',
        );

        $smarty->assign('title', '入库列表');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
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

            //清空所有的码单数据
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
            $temp['cntJian'] = count($madan_ck);
            //将单位转换为米保存
            $temp['unit']=='' && $temp['unit']='M';//默认不能为空
            $temp['cntM'] = $temp['unit'] == 'M' ? $temp['cnt'] : $temp['cnt']*0.9144;
            //获取订单id
            //($temp['orderId']>0 && !$temp['id']>0) && $_orderId[]=$temp;
            $pros[]=$temp;
        }
        // dump($pros);exit;
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
        $chukuId=$this->_modelExample->save($row);

        // if(!$this->_modelExample->save($row)) {
        //     js_alert('保存失败','window.history.go(-1)');
        //     exit;
        // }
        //销售出库的自动过账  只有新增的时候保存过账记录  修改不管
        $ret=$this->_modelExample->find(array('id'=>$chukuId));
        // dump($ret);
        $this->_modelGuozhang = &FLEA::getSingleton('Model_Caiwu_Ar_Guozhang');
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
        js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('Right'));

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
        
        //添加按订单号查询，2015-10-20，by liuxin
        if($arr['orderCode']!=''){
            $sqlOrder = "select id
                        from trade_order
                        where orderCode like '%{$arr['orderCode']}%'";
            $orderCode = $this->_modelExample->findBySql($sqlOrder);
            $condition[]=array('orderId',"%{$orderCode['0']['id']}%",'like');
        }
        if($arr['danhao']!=''){
            $condition[]=array('Ck.chukuCode',"%{$arr['danhao']}%",'like');
        }
        if($arr['proCode']!=''){
            $condition[]=array('productId',"%{$arr['proCode']}%",'like');
        }
        if($arr['kuweiId_name']!=''){
            $condition[]=array('Ck.kuweiId',"{$arr['kuweiId_name']}",'=');
        }
        if($arr['clientId']!=''){
            $condition[]=array('Ck.clientId',"{$arr['clientId']}",'=');
        }
        //增加日期搜索功能
        if($arr['dateFrom'] != '') {
             $condition[]=array('Ck.chukuDate',"{$arr['dateFrom']}",'>=');
        }
        if($arr['dateTo'] != '') {
             $condition[]=array('Ck.chukuDate',"{$arr['dateTo']}",'<=');
        }

        //查找计划
        $pager = &new TMIS_Pager($this->_subModel,$condition,'chukuCode desc');
        $rowset = $pager->findAll();
        foreach($rowset as & $v) {
            $v['Ck']['chukuDate']=$v['Ck']['chukuDate']=='0000-00-00'?'':$v['Ck']['chukuDate'];

            //2015-10-29 by jiang 已过账禁止修改和删除
            $sql="select * from caiwu_ar_guozhang where chuku2proId='{$v['id']}'";
            $ret=$this->_modelExample->findBySql($sql);
            if(count($ret)>0){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已过账禁止修改和删除'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已过账禁止修改和删除'></span>";
            }else{
                $v['_edit'] = $this->getEditHtml($v['Ck']['id']);
                //删除操作
                $v['_edit'] .= '&nbsp;&nbsp;' .$this->getRemoveHtml($v['Ck']['id']);
            }

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

        }
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'_edit'); 
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'].' M';
        $rowset[]=$hj;

        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            "_edit" => '操作',
            "Ck.chukuCode" => '出库单号',
            "Ck.chukuDate" => '出库日期',
            "orderCode" => '订单号',
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
            "unit" => '单位',
        );

        $smarty->assign('title', '入库列表');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }
    /*
    * ps: 销售出库审核 
    * Time:2015-12-17 13:38:26
    */
    function actionSetShenhe(){
        $arr = $this->_chukushenhe->find(array('id' => $_GET['id']));
        $sql = "update cangku_chukushenhe set isCheck=1 where id = '{$_GET['id']}'";
        $this->_chukushenhe->execute($sql);
        foreach($arr['Products'] as $key=> & $v) {
            $maId=json_decode($v['MadanJson'],true);
            $v['Madan']=$maId;
            unset($v['id']);
        }
        $madanId = join(',',array_col_values($maId,'madanId'));
        //锁定码单  只要是出库就得锁定码单 暂时只有现货需要这个功能
        if($maId[0]!=''&&$this->_cangkuName == __CANGKU_1){
            $sql="update madan_db set status='finish' where id in({$madanId})";
            $this->_modelExample->findBySql($sql);
        }
        unset($arr['id']);
        // dump($arr);die;
        $chukuId=$this->_modelExample->save($arr);
        
        //销售出库的自动过账  只有新增的时候保存过账记录  修改不管
        $ret=$this->_modelExample->find(array('id'=>$chukuId));
        $this->_modelGuozhang = &FLEA::getSingleton('Model_Caiwu_Ar_Guozhang');
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
               exit;
            }
        }
        //同步发货状态
        //查找订单的发货总状态
        $pros_order = array_col_values($arr['Products'],'orderId');
        $pros_order = array_unique($pros_order);
        $pros_order = array_filter($pros_order);
        count($pros_order)>0 && $this->ship_status_toec($pros_order);
        js_alert(null,'window.parent.showMsg("审核成功!")',$this->_url('ShenheList'));
    }

    function actionRemove() {
        $this->authCheck('3-1-13-2');
        $from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
        if ($this->_chukushenhe->removeByPkv($_GET['id'])) {
            if($from=='') redirect($this->_url("right"));
            else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
        }
        else js_alert('出错，不允许删除!',null,$this->_url($from));

    }
}


?>