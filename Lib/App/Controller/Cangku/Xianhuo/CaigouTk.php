<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Xianhuo_CaigouTk extends Controller_Cangku_Ruku{
    function __construct() {
        parent::__construct();
        $this->_cangkuName = __CANGKU_1;
        $this->_head = 'CGTK';
        $this->_kind = '采购退库';
        $this->_check='3-1-2';
        $this->_mold = 'Xianhuo';
        $this->_modelCaigou = &FLEA::getSingleton('Model_Caigou_Order');
        $this->_modelCaigou2pro = &FLEA::getSingleton('Model_Caigou_Order2Product');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Tuiku');
        $this->_subModel = &FLEA::getSingleton('Model_Cangku_Tuiku2Product');
        $this->_modelruku = &FLEA::getSingleton('Model_Cangku_Ruku');

        $this->fldMain = array(
            'rukuCode' => array('title' => '入库单号', "type" => "text", 'readonly' => true,'value' => $this->_getNewCode($this->_head,'cangku_ruku','rukuCode')),
            'rukuDate' => array('title' => '入库日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'kuweiId' => array('title' => '库位','type' => 'select', 'value' => '','name'=>'kuweiId','model'=>'Model_Jichu_Kuwei','condition'=>'ckName="'.$this->_cangkuName.'"'),
            'caigouCode' => array('title'=>'采购合同','type' => 'text', 'value' =>'','name'=>'caigouCode','readonly'=>true),
            'caigouId' => array('type' => 'hidden', 'value' =>''),
            'kind' => array('title'=>'入库类型','type' => 'text', 'value' => $this->_kind,'readonly'=>true),
            'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memo'),
            // 下面为隐藏字段
            'id' => array('type' => 'hidden', 'value' => '','name'=>'cgrkId'),
            'cangkuName' => array('type' => 'hidden', 'value' => $this->_cangkuName,'name'=>'cangkuName'),
            'isGuozhang' => array('type' => 'hidden', 'value' => '0','name'=>'isGuozhang'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),

        );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnCopy', "title" => '操作', 'name' => '_edit[]'),
            'pihao'=>array('type'=>'BtText',"title"=>'批号','name'=>'pihao[]'),
            // 'productId' => array(
            //     'title' => '花型六位号',
            //     'type' => 'BtPopup',
            //     'name' => 'productId[]',
            //     'url'=>url('Jichu_Product','Popup'),
            //     'textFld'=>'proCode',
            //     'hiddenFld'=>'id',
            //     'inTable'=>true,
            //     'dialogWidth'=>900
            // ),
            'proCode'=>array('type'=>'BtText',"title"=>'花型六位号','name'=>'proCode[]','readonly'=>true),
            'productId'=>array('type'=>'BtHidden','name'=>'productId[]','readonly'=>true),
            'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
            'proXinxi'=>array('type'=>'BtText',"title"=>'产品信息','name'=>'proXinxi[]','readonly'=>true,'width'=>200),
            'cntCg' => array('type' => 'BtText', "title" => '采购数量', 'name' => 'cntCg[]','readonly'=>true),
            'cntYr' => array('type' => 'BtText', "title" => '已入库数量', 'name' => 'cntYr[]','readonly'=>true),
            'cnt' => array('type' => 'BtText', "title" => '数量', 'name' => 'cnt[]','readonly'=>true),
            'cntJian' => array('type' => 'BtText', "title" => '卷数', 'name' => 'cntJian[]','readonly'=>true),
            'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'M','value'=>'M'),
                    array('text'=>'Y','value'=>'Y')
                )),
            'memoView' => array('type' => 'BtText', "title" => '备注', 'name' => 'memoView[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
            'danjia' => array('type' => 'BtHidden', 'name' => 'danjia[]'),
            'cairu2proId' => array('type' => 'BtHidden', 'name' => 'cairu2proId[]'),
            'cai2proId' => array('type' => 'BtHidden', 'name' => 'cai2proId[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'kuweiId' => 'required',
        );

        $this->sonTpl = array(
            'Cangku/cksonTpl.tpl',
        );
    }
    
    function actionAdd(){
        $arr = $this->_modelExample->find(array('id' => $_GET['id']));
        //采购信息
        $caigou = &FLEA::getSingleton('Model_Caigou_Order');
        $cai=$caigou->find(array('id'=>$arr['caigouId']));
        foreach ($cai['Products'] as & $c) {
            $retCai[$c['id']]=$c;
        }
        $this->fldMain['caigouCode']['value']=$cai['orderCode'];
        $this->fldMain['caigouId']['value']=$cai['id'];
        $this->fldMain['kuweiId']['value']=$arr['kuweiId'];
        //仓库信息
        if($arr['kuweiId']>0){
            $sql="select kuweiName from jichu_kuwei where id='{$arr['kuweiId']}'";
            $temp=$this->_subModel->findBySql($sql);
            $this->fldMain['kuweiId'] && $this->fldMain['kuweiId']['text']=$temp[0]['kuweiName'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        foreach($arr['Products'] as &$v) {
            $temp = array();
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $temp['productId']['text'] = $_temp[0]['proCode'];
            $temp['productId']['value'] = $_temp[0]['proCode'];
            $temp['proCode']['value'] = $_temp[0]['proCode'];
            $temp['proName']['value'] = $_temp[0]['proName'];
            $temp['proXinxi']['value'] = '成分:'.$_temp[0]['chengfen'].',纱支:'.$_temp[0]['shazhi'].',经纬密:'.$_temp[0]['jingmi'].'*'.$_temp[0]['weimi'].',门幅:'.$_temp[0]['menfu'];

            $temp['danjia']['value'] = round($v['danjia'],6);

            //获取已入库数量
            $sql="select sum(cnt) as cnt from cangku_ruku2product where cai2proId='{$v['cai2proId']}' group by cai2proId";
            $retcnt=$this->_modelExample->findBySql($sql);
            $temp['cntYr']['value']=$retcnt[0]['cnt'];
            //采购数量
            $temp['cntCg']['value']=$retCai[$v['cai2proId']]['cnt'];
            $temp['pihao']['value']=$v['pihao'];
            $temp['cairu2proId']['value']=$v['id'];
            $temp['cai2proId']['value']=$v['cai2proId'];
            $rowsSon[] = $temp;
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
    function actionEdit(){
        $arr = $this->_modelruku->find(array('id' => $_GET['id']));
        //采购信息
        $caigou = &FLEA::getSingleton('Model_Caigou_Order');
        $cai=$caigou->find(array('id'=>$arr['caigouId']));
        foreach ($cai['Products'] as & $c) {
            $retCai[$c['id']]=$c;
        }

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        $this->fldMain['caigouCode']['value']=$cai['orderCode'];
        //仓库信息
        if($arr['kuweiId']>0){
            $sql="select kuweiName from jichu_kuwei where id='{$arr['kuweiId']}'";
            $temp=$this->_subModel->findBySql($sql);
            $this->fldMain['kuweiId'] && $this->fldMain['kuweiId']['text']=$temp[0]['kuweiName'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $v['productId'] = $_temp[0]['proCode'];
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['proXinxi'] = '成分:'.$_temp[0]['chengfen'].',纱支:'.$_temp[0]['shazhi'].',经纬密:'.$_temp[0]['jingmi'].'*'.$_temp[0]['weimi'].',门幅:'.$_temp[0]['menfu'];

            $v['danjia'] = round($v['danjia'],6);

            //获取码单
            $sql="select group_concat(madanId) as madanId from madan_rc2madan where rukuId = '{$v['id']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $_t = $_temp[0];
            // dump($_t);exit;
            $v['Madan']=$_t['madanId'];

            //获取已入库数量
            $sql="select sum(cnt) as cnt from cangku_ruku2product where cai2proId='{$v['cai2proId']}' group by cai2proId";
            $retcnt=$this->_modelExample->findBySql($sql);
            $v['cntYr']=$retcnt[0]['cnt'];
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
     * ps ：退库保存
     * Time：2015/10/10 13:25:35
     * @author jiang
    */
    function actionSave(){
       //主表信息
        $row = array();
        foreach($this->fldMain as $k=>&$vv) {
            $name = $vv['name']?$vv['name']:$k;
            $row[$k] = $_POST[$name];
        }

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
                                where x.rukuId='{$temp['id']}' and madanId not in({$delmadan})
                            ) as a
                        )";
                $this->_modelExample->findBySql($m_sql);
                //清空关联表
                $_delsql = "delete from madan_rc2madan where rukuId='{$temp['id']}'";
                $this->_modelExample->execute($_delsql);
            }

            $temp['Madan'] = $madan_ck;
            // $temp['cntJian'] = count($madan_ck);
            //将单位转换为米保存
            $temp['unit']=='' && $temp['unit']='M';//默认不能为空
            $temp['cntM'] = $temp['unit'] == 'M' ? $temp['cnt'] : $temp['cnt']*0.9144;
            //退库取绝对值乘-1
            $temp['cntM']=abs($temp['cntM']) *(-1);
            $temp['cnt']=abs($temp['cnt']) *(-1);
            $temp['cntJian']=abs($temp['cntJian']) *(-1);
            $pros[]=$temp;
        }
        if(!$pros){
            js_alert('保存失败，没有有效明细数据','window.history.go(-1)');
            exit;
        }
        //锁定码单  只要是出库就得锁定码单 暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $maId=join(',',$maId);
            $sql="update madan_db set status='finish' where id in({$maId})";
            $this->_modelExample->findBySql($sql);
        }

        $row['Products'] = $pros;
        if(!$this->_modelExample->save($row)) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        } else {
         $sql = "insert into oa_message(
               id,
               title,
               kindName,
               content
             ) values(
                '',
                '{$row['caigouCode']}',
                '采购退库',
                '您有一笔新的采购退库交易,请尽快确认！'
            )";
            mysql_query($sql) or die(mysql_error());
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',url('Cangku_'.$this->_mold.'_QtRk','Right',array()));
            }

    }
    function actionRemoveByAjax() {
        //将码单取消锁定  暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $sql="update madan_db set status='active' where id in(
                    select madanId from madan_rc2madan where rukuId='{$_POST['id']}'
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
                    left join cangku_ruku2product y on x.rukuId=y.id
                     where y.rukuId='{$_GET['id']}'
                    )";
            $this->_modelExample->findBySql($sql);
        }
        parent::actionRemove();
    }
    /**
     * ps ：码单显示
     * Time：2015/09/28 16:28:51
     * @author jiang
    */
    function actionViewMadan(){
        $ma=&FLEA::getSingleton('Model_Cangku_Madan');
        $_modelPro=&FLEA::getSingleton('Model_Jichu_Product');
        $product = $_modelPro->find(array('proCode'=>$_GET['productId']));
        if($_GET['chukuId']) $stat=" and (y.status='active' or x.rukuId='{$_GET['chukuId']}')";
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
                $v['cntMi']=$v['cnt']*0.9144;
            }else{
                $v['cntMi']=$v['cntM'];
            }
            $ret['Peihuo'][$v['millNo']][]=$v;
            // $product=$v['Product'];
        }
        $smarty = &$this->_getView(); 
        $smarty->assign('Peihuo', $ret);
        $smarty->assign('Product', $product);
        $smarty->display('Cangku/Xianhuo/PeihuoSelect.tpl');
    }

     function actionEditShenhe(){
        $this->authCheck('3-1-13-1');
        $arr = $this->_rukushenhe->find(array('id' => $_GET['id']));
        //采购信息
        $caigou = &FLEA::getSingleton('Model_Caigou_Order');
        $cai=$caigou->find(array('id'=>$arr['caigouId']));
        foreach ($cai['Products'] as & $c) {
            $retCai[$c['id']]=$c;
        }

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        $this->fldMain['caigouCode']['value']=$cai['orderCode'];
        //仓库信息
        if($arr['kuweiId']>0){
            $sql="select kuweiName from jichu_kuwei where id='{$arr['kuweiId']}'";
            $temp=$this->_subModel->findBySql($sql);
            $this->fldMain['kuweiId'] && $this->fldMain['kuweiId']['text']=$temp[0]['kuweiName'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $v['productId'] = $_temp[0]['proCode'];
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['proXinxi'] = '成分:'.$_temp[0]['chengfen'].',纱支:'.$_temp[0]['shazhi'].',经纬密:'.$_temp[0]['jingmi'].'*'.$_temp[0]['weimi'].',门幅:'.$_temp[0]['menfu'];

            $v['danjia'] = round($v['danjia'],6);

            //查找码单信息，并json_encode
            $retMadan=json_decode($v['MadanJson'],true);
            $_temp=array();
            foreach($retMadan as & $m){
                $_temp[$m['rollNo']-1]=$m;
            }
            $v['Madan'] = json_encode($_temp,true);

            //获取已入库数量
            $sql="select sum(cnt) as cnt from cangku_ruku2product where cai2proId='{$v['cai2proId']}' group by cai2proId";
            $retcnt=$this->_modelExample->findBySql($sql);
            $v['cntYr']=$retcnt[0]['cnt'];
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
}


?>