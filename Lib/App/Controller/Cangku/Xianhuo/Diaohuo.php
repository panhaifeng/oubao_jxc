<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :jiang
*  FName  :Diaohuo.php
*  Time   :2015/10/08 13:57:24
*  Remark :调货出库：处理调货库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Xianhuo_Diaohuo extends Controller_Cangku_Chuku{
    /**
     * 权限设置
     * @var string
    */
    var $_check;
    function __construct() {
        parent::__construct();
        $this->_modelRuk = &FLEA::getSingleton('Model_Cangku_Ruku');
        $this->_modelRuk2pro = &FLEA::getSingleton('Model_Cangku_Ruku2Product');
        $this->_modelKw = &FLEA::getSingleton('Model_Jichu_Kuwei');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Diaohuo');
        $this->_subModel = &FLEA::getSingleton('Model_Cangku_Diaohuo2Product');
        $this->_modelRukshen = &FLEA::getSingleton('Model_Cangku_RukuShenhe');
        $this->_modelChu = &FLEA::getSingleton('Model_Cangku_Chuku');
        $this->_cangkuName = __CANGKU_1;
        $this->_head = 'XHDH';
        $this->_kind = '调货';
        $this->_mold = 'Xianhuo';
        $this->_check='3-1-9';
        $this->fldMain = array(
            'chukuCode' => array('title' => '单号', "type" => "text", 'readonly' => true,'value' =>'自动生成' ),
            'chukuDate' => array('title' => '出库日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'kuweiId' => array('title' => '调出库位','type' => 'select', 'value' => '','name'=>'kuweiId','model'=>'Model_Jichu_Kuwei','condition'=>'ckName="'.$this->_cangkuName.'"','isSearch'=>true),
            'kuweiIdru' => array('title' => '调入库位','type' => 'select', 'value' => '','name'=>'kuweiIdru','model'=>'Model_Jichu_Kuwei','isSearch'=>true),
            'kind'=>array('title'=>'类型','type'=>'text','name'=>'kind','value'=>$this->_kind,'readonly'=>true),
            'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memo'),
            // 下面为隐藏字段
            'id' => array('type' => 'hidden', 'value' => '','name'=>'cgckId'),
            'cangkuName' => array('type' => 'hidden', 'value' => $this->_cangkuName,'name'=>'cangkuName'),
            'isGuozhang' => array('type' => 'hidden', 'value' => '1','name'=>'isGuozhang'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),

        );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'BtBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'productId' => array(
                'title' => '花型六位号',
                'type' => 'BtPopup',
                'name' => 'productId[]',
                'url'=>url('Jichu_Product','Popup'),
                'textFld'=>'proCode',
                'hiddenFld'=>'id',
                'inTable'=>true,
                'dialogWidth'=>900
            ),
              'pihao' => array(
                'title' => '批号',
                'type' => 'BtPopup',
                'name'=>'pihao[]',
                'url'=>url('Cangku_Xianhuo_Diaohuo','Popup'),
                'textFld'=>'millNo',
                'hiddenFld'=>'id',
                'inTable'=>true,
                'dialogWidth'=>900
            ),

            'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
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
            'memoView' => array('type' => 'BtText', "title" => '备注', 'name' => 'memoView[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
            'danjia' => array('type' => 'BtHidden', 'name' => 'danjia[]'),
            'rumadan' => array('type' => 'BtHidden', 'name' => 'rumadan[]','value'=>''),
            'cmadan' => array('type' => 'BtHidden', 'name' => 'cmadan[]','value'=>''),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'kuweiId' => 'required',
            'kind' => 'required',
            'kuweiIdru' => 'required',
        );

        $this->sonTpl = array(
            'Cangku/cksonTpl.tpl',
            'Cangku/dhkw.tpl',
        );
    }

    function actionRight(){
        //权限判断
        $this->authCheck($this->_check);
        FLEA::loadClass('TMIS_Pager'); 
        $arr = TMIS_Pager::getParamArray(array(
            'danhao'=>'',
            'kuweiId_chu'=>'',
            'kuweiId_ru'=>'',
            'kuweiName'=>$this->_cangkuName,
            'proCode'=>'',
        )); 
        $sql="select x.*,y.id as chukuId,y.chukuDate,y.chukuCode,y.kind,k.kuweiName,
                p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,p.menfu,p.kezhong,
                j.kuweiName as rukuweiName,r.id as rukuId,rp.id as ru2proId
                from cangku_chuku2product x
                left join cangku_chuku y on x.chukuId=y.id
                left join jichu_kuwei k on k.id=y.kuweiId
                left join jichu_product p on p.proCode=x.productId
                left join cangku_ruku2product rp on rp.dbId=x.id
                left join cangku_ruku r on r.id=rp.rukuId
                left join jichu_kuwei j on j.id=r.kuweiId
                where 1 and y.cangkuName='{$this->_cangkuName}' and y.kind='{$this->_kind}'";

        if($arr['danhao']!='') $sql .=" and y.chukuCode like '%{$arr['danhao']}%' ";
        if($arr['kuweiId_chu']!='') $sql .=" and y.kuweiId = '{$arr['kuweiId_chu']}' ";
        if($arr['kuweiId_ru']!='') $sql .=" and r.kuweiId = '{$arr['kuweiId_ru']}' ";
        if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
        $sql.="order by chukuDate desc,chukuCode desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($rowset);die;
        foreach($rowset as & $v) {
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];

            //2015-11-5 by jiang 生成配货单的不允许修改和删除
            if($v['dahuo2proId']>0){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已生成过配货单禁止修改和删除'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已生成过配货单禁止修改和删除'></span>";
            }else{
                $v['_edit'] = $this->getEditHtml($v['chukuId']);
                $v['_edit'].=' ' .$this->getRemoveHtml($v['chukuId']);
            }
            
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            //查看码单
            if(substr($v['rukuweiName'],0,6)=='现货'){
                 $v['_edit'].="&nbsp;<a href='".url('Cangku_Ruku','MadanCheck',array(
                'id'=>$v['ru2proId'],
                'fromAction'=>$_GET['action'],
                'width'=>'700',
                'height'=>'400',
                ))."' class='thickbox'><span class='glyphicon glyphicon-zoom-in' title='查看码单'></span></a>";
            }
            //如果库位名为样品则添加标签打印功能
             if(substr($v['kuweiName'],0,6)=='样品'){
                 $v['_edit'].= "&nbsp;&nbsp;<a href='".$this->_url('PrintYplab',array(
                            'id'=>$v['id'],
                            'fromAction'=>$_GET['action'],
                            'printkind'=>0,
                            'fromController'=>$_GET['controller'],
                    ))."'><span class='glyphicon glyphicon-comment' ext:qtip='出库单常规标签'></span></a>";
            }
            
        } 
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'_edit'); 
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'].' M';
        $rowset[]=$hj;
        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            "_edit" => array('text'=>'操作','width'=>80),
            "chukuCode" => '单号',
            "chukuDate" => '出库日期',
            "kuweiName" => '调出库位',
            "rukuweiName" => '调入库位',
            "kind" => '类型',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "chengfen" => '成分',
            "jwmi" => '经纬密',
            "menfu" => '门幅',
            "cnt" => '数量',
            "cntJian" => '卷数',
            "unit" => '单位'
        );

        $smarty->assign('title', '入库列表');
        $smarty->assign('arr_field_info', $arr_field_info);
        // $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }
        /**
     * 调货出库保存
     * Time：2015/09/19 21:11:28
     * @author jiang
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
            if($v=='' || empty($_POST['cnt'][$key])|| $_POST['pihao'][$key]=='') continue;
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

            if($delmadan) $delmadan=join(',',$delmadan);
            //清空所有的码单数据
            if($temp['id']>0 && $delmadan){
                //先查找本次修改去掉的码单
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
            
            $temp['ChuMadan'] = $madan_ck;
            // $temp['cntJian'] = count($madan_ck);
            //将单位转换为米保存
            $temp['unit']=='' && $temp['unit']='M';//默认不能为空
            $temp['cntM'] = $temp['unit'] == 'M' ? $temp['cnt'] : $temp['cnt']*0.9144;

            //2015-10-30 by jiang 保存码单json
            $ckName=$this->_modelKw->find(array('id'=>$_POST['kuweiIdru']));
            //出库入库的码单
            if($ckName['ckName']=='现货仓库'){
                //必须保存码单
                $this->_madanChuli($temp);
            }else{
                $temp['Madan']='';
                $this->_madanDelete($temp);
            }
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
        if($row['chukuCode']=='自动生成') $row['chukuCode']=$this->_getNewCode($this->_head,'cangku_chuku','chukuCode');
        $row['Products'] = $pros;
        // dump($row);exit;
        $chuId=$this->_modelExample->save($row);
        // $chuId=$this->_modelChu->save($row);
        // $this->_modelRukshen->save($row);
        // dump($chuId);die;

        $chuId=$_POST['cgckId']==''?$chuId:$_POST['cgckId'];
        //保存入库信息
        $this->_saveRuku($chuId,$_POST['kuweiIdru']);
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('Right'));

    }
    function actionPopup(){
    FLEA::loadClass('TMIS_Pager');
    $arr = TMIS_Pager::getParamArray(array(
        'key' => '',
    )); 

    $str = "SELECT distinct(millNo) From madan_db  
           where productId = '{$_GET['productId']}'";

    if ($arr['key'] != '') $str .= " and millNo like '%{$arr['key']}%'";
    $pager = &new TMIS_Pager($str);
    $rowset = $pager->findAll();

    $smarty = &$this->_getView();
    $smarty->assign('title', '选择产品');
    $arr_field_info = array(
      "millNo"=>array('text'=>"批号",'width'=>1000),
    ); 
    $smarty->assign('arr_field_info',$arr_field_info);
    $smarty->assign('arr_field_value',$rowset);
    $smarty->assign('add_display','none');
    $smarty->assign('arr_condition',$arr);
    $smarty->assign('page_info',$pager->getNavBar($this->_url('Popup',$arr)));
    $smarty-> display('Popup/CommonNew.tpl');

    }
    /**
    * ps ：删除码单
    *如果这个单子一开始是现货调入现货生成码单
    *这次修改将改成现货调入大货 则需要删除原来保存的码单
    *只要将码单的数量变为空便可以删除
    * Time：2015/11/03 19:58:02
    * @author jiang
    */
    function _madanDelete(& $temp){
        $temp['rumadan']=json_decode($temp['rumadan'],true);
        if(count($temp['rumadan'])>0){
            foreach ($temp['rumadan'] as & $t) {
                $t['cntFormat']='';;
            }
            $temp['Madan']=json_encode($temp['rumadan']);
        }
    }   
    /**
    * ps ：码单入库的处理
    * Time：2015/10/31 11:16:41
    * @author jiang
    */
     function _madanChuli(& $temp){
        if($temp['Madan']) $temp['Madan']=explode(',',$temp['Madan']);
        if($temp['cmadan']) $temp['cmadan']=explode(',',$temp['cmadan']);
        $temp['rumadan']=json_decode($temp['rumadan'],true);
        $_rumadan=array();
        foreach ($temp['rumadan'] as & $r) {
            $_rumadan[$r['parentId']]=$r;
        }
        //合并数组
        $_hbMadan=$temp['cmadan']?array_unique(array_merge($temp['Madan'],$temp['cmadan'])):$temp['Madan'];
        $_hbMadan=join(',',$_hbMadan);
        //查找码单信息
        $sqlma="select * from madan_db where id in ({$_hbMadan})";
        $retma=$this->_modelExample->findBySql($sqlma);

        //判断那些码单Id是修改的  那些是删除的
        //删除的
        $_del = array_diff($temp['cmadan'],$temp['Madan']);
        $arr_del=array();
        foreach ($_del as & $d) {
            $arr_del[$d]=$d;
        }
        //需要新增的
        $_add = array_diff($temp['Madan'],$temp['cmadan']);
        $arr_add=array();
        foreach ($_add as & $a) {
            $arr_add[$a]=$a;
        }
        if(count($retma)>0){
            foreach($retma as & $r){
                if($arr_del[$r['id']]){//修改时删除的
                    $r=$_rumadan[$r['id']];
                    $r['cntFormat']='';
                }else if($arr_add[$r['id']]){//修改时新增的
                    $r['parentId']=$r['id'];
                    $r['id']='';
                    $r['cnt']=$r['cntM'];
                }else{
                    if($_rumadan[$r['id']]){
                        $r=$_rumadan[$r['id']];
                    }else{//新增的时候  上面都是修改需要判断的  
                        $r['parentId']=$r['id'];
                        $r['status']='active';
                        $r['id']='';
                        $r['cnt']=$r['cntM'];
                    }
                }
            }
            $temp['Madan']=json_encode($retma);
        }
     }   

    /**
    * ps ：保存入库
    * Time：2015/10/30 18:02:21
    * @author jiang
    */
    function _saveRuku($chuId,$kuweiIdru){ 
        $madan2ruku = &FLEA::getSingleton('Model_Cangku_Rc2Madan');
        $ret=$this->_modelExample->find(array('id'=>$chuId));
        //查找入库信息
        $sql="select x.id from cangku_ruku x
              left join cangku_ruku2product y on x.id=y.rukuId
              where y.dbId='{$ret['Products'][0]['id']}'";
        $ru2pro=$this->_modelExample->findBySql($sql);
         //保存入库信息
        foreach ($ret['Products'] as & $v){
            //处理码单信息
            $madan = json_decode($v['Madan'],true);
            $_temp=array();
            foreach($madan as & $m){
                //数量不存在，说明该码单不需要保存
                if(empty($m['cntFormat'])){
                    //如果id存在，则说明该码单需要在数据表中删除
                    if($m['id']>0){
                        $v['madan_clear'][]=$m['id'];
                    }
                    continue;
                }
                $m['productId']=$v['productId'];
                $m['millNo']=$v['pihao'];
                $m['unit']=$v['unit'];
                //将单位转换为米保存
                $m['unit']=='' && $m['unit']='M';//默认不能为空
                if($m['unit']=='Y') $m['cntM']=0.9144*$m['cnt'];
                else $m['cntM']=$m['cnt'];

                if($m) $_temp[]=$m;
            }
            $v['Madan'] = $_temp;

            $arrRu[] = array(
                    'id' => $v['dbId'],
                    'productId' => $v['productId'],
                    'pihao' => $v['pihao'],
                    'cntJian' => $v['cntJian'],
                    'cnt' => $v['cnt'],
                    'cntM' => $v['cntM'],
                    'danjia' => $v['danjia'],
                    'unit' => $v['unit'],
                    'dbId' => $v['id'],
                    'memoView' => $v['memoView'].'',
                    'Madan' => $v['Madan'],
                    'madan_clear' => $v['madan_clear']
            );
        }
        $ckName=$this->_modelKw->find(array('id'=>$kuweiIdru));
        $rowruku=array(
                'id'            =>$ru2pro[0]['id'],
                'rukuDate'     =>$ret['chukuDate'],
                'rukuCode'     =>$ret['chukuCode'],
                'kuweiId' =>    $kuweiIdru,
                'cangkuName'    => $ckName['ckName'],
                'isGuozhang' => $ret['isGuozhang'],
                'creater' => $ret['creater'].'',
                'memo'          =>$ret['memo'].'',
                'kind'          =>$ret['kind'].'',
                'Products'      => $arrRu
        );
        $rukuId=$this->_modelRuk->save($rowruku);
        $rukuId=$ru2pro[0]['id']?$ru2pro[0]['id']:$rukuId;
        //2015-10-30 by jiang 保存玩入库后在将入库的Id保存到出库表的dbId中
        $retRuku=$this->_modelRuk->find(array('id'=>$rukuId));
        $retChuku=array();
        foreach ($retRuku['Products'] as & $r) {
            //查找码单信息
            $_temp=$madan2ruku->findAll(array('rukuId'=>$r['id']));
            $_temp=array_col_values($_temp,'Madan');
            //将码单数据转换为字符串并保存到出库表中
            $r['Madan']=json_encode($_temp);
            $retChuku['Products'][]=array(
                    'id'=>$r['dbId'],
                    'dbId'=>$r['id'],
                    'Madan'=>$r['Madan']
                );
        }
        $retChuku['id']=$chuId;
        $this->_modelExample->save($retChuku);
    }

    function actionEdit() {
        $arr = $this->_modelExample->find(array('id' => $_GET['id']));

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }

        //查找调入库位
        $tempru=$this->_modelRuk2pro->find(array('dbId'=>$arr['Products'][0]['id']));
        $this->fldMain['kuweiIdru']['value']=$tempru['Rk']['kuweiId'];
        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        // dump($arr['Products']);exit;
        foreach($arr['Products'] as & $v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $v['productId'] = $_temp[0]['proCode'];
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['chengfen'] = $_temp[0]['chengfen'];
            $v['shazhi'] = $_temp[0]['shazhi'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['jwmi'] = $_temp[0]['jingmi'].'*'.$_temp[0]['weimi'];


            //获取码单
            $sql="select group_concat(madanId) as madanId from madan_rc2madan where chukuId = '{$v['id']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $_t = $_temp[0];
            // dump($_t);exit;
            $v['Madan']=$_t['madanId'];

            //在入库中查找码单
            $sql="select y.* from madan_rc2madan x
                    left join madan_db y on x.madanId=y.id
                    where x.rukuId='{$v['dbId']}'";
            $retMadan = $this->_modelExample->findBySql($sql);
            if(count($retMadan)>0){
                $v['rumadan'] = json_encode($retMadan);
            }else{
                $v['rumadan']='';
            }
            //出库码单Id  保存这个id 是为了保存的时候对比  那些是删除  那些是修改的
            $v['cmadan']=$v['Madan'];
        }

        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $temp['productId']['text']=$v['proCode'];
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

    function actionRemoveByAjax() {
        //2015-10-30 已锁定的入库码单禁止删除
        $sqlm="select x.* from madan_db x 
                left join madan_rc2madan y on x.id=y.madanId
                left join cangku_chuku2product c on c.dbId=y.rukuId
                where c.id='{$_POST['id']}' and x.status <> 'active'";
        $retm=$this->_modelExample->findBySql($sqlm);
        if(count($retm)>0){
            echo json_encode(array('success'=>false,'msg'=>'码单已锁定，禁止删除!'));exit;
        }

        //将码单取消锁定 暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $sql="update madan_db set status='active' where id in(
                    select madanId from madan_rc2madan where chukuId='{$_POST['id']}'
                    )";
            $this->_modelExample->findBySql($sql);
        }
        //删除入库信息
        $sql="select y.id from cangku_ruku2product y 
                left join cangku_chuku2product c on c.id=y.dbId
                where c.id='{$_POST['id']}'";
        $ret=$this->_modelRuk2pro->findBySql($sql);
        $this->_modelRuk2pro->removeByPkv($ret[0]['id']);
        parent::actionRemoveByAjax();
    }

    function actionRemove(){
        //2015-10-30 已锁定的入库码单禁止删除
        $sqlm="select x.* from madan_db x 
                left join madan_rc2madan y on x.id=y.madanId
                left join cangku_chuku2product c on c.dbId=y.rukuId
                where c.chukuId='{$_GET['id']}' and x.status <> 'active'";
        $retm=$this->_modelExample->findBySql($sqlm);
        if(count($retm)>0){
            $from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
            js_alert('码单已锁定，禁止删除!!',null,$this->_url($from));exit;
        }
        //将码单取消锁定  暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $sql="update madan_db set status='active' where id in(
                    select madanId from madan_rc2madan x
                    left join cangku_chuku2product y on x.chukuId=y.id
                     where y.chukuId='{$_GET['id']}'
                    )";
            $this->_modelExample->findBySql($sql);
        }
        
        //删除入库信息
        $sql="select x.id from cangku_ruku x
                left join cangku_ruku2product y on x.id=y.rukuId
                left join cangku_chuku2product c on c.id=y.dbId
                where c.chukuId='{$_GET['id']}'";
        $ret=$this->_modelRuk->findBySql($sql);
        $this->_modelRuk->removeByPkv($ret[0]['id']);
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
        if($_GET['chukuId']) $stat=" and (y.status='active' or x.chukuId='{$_GET['chukuId']}')";
        else $stat=" and y.status='active'";

        //现货掉入现货时需要的判断
       if($_GET['chukuId']){
        $xian=" and x.madanId not in(
                select madanId from madan_rc2madan x
                left join cangku_ruku2product y on x.rukuId=y.id
                where y.dbId='{$_GET['chukuId']}')";
        }

        $sql="SELECT y.* from madan_db y
                   left join madan_rc2madan x on x.madanId=y.id 
                   where y.productId='{$_GET['productId']}' and y.millNo='{$_GET['pihao']}'
                    {$stat} {$xian}
                   group by y.id order by y.rollNo";
        // dump($sql);exit;
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
            //现货调入现货时 判断 码单是否锁定
            $sql="select x.* from madan_db x
                left join madan_rc2madan y on x.id=y.madanId
                left join cangku_ruku2product r on r.id=y.rukuId
                 where parentId='{$v['id']}' and r.dbId='{$_GET['chukuId']}' 
                 and x.status <> 'active'";
            $maret=$this->_modelExample->findBySql($sql);
            if(count($maret)>0){
                $v['checked'] = true;
                $v['disabled'] = true;
                $v['title']="码单已锁定禁止修改";
            }
            //调货时取现货的单价
            $str="SELECT y.danjia 
                from cangku_ruku x 
                left join cangku_ruku2product y on x.id=y.rukuId
                left join caigou_order2product z on x.caigouId=z.caigouId
                where y.pihao='{$v['millNo']}' 
                and y.productId='{$v['productId']}' 
                and x.kind='采购入库'";
            $arr=$this->_modelExample->findBySql($str);
            $v['danjia']=$arr[0]['danjia'];

            $ret['Peihuo'][$v['millNo']][]=$v;
        }
        // dump($ret);die;
        $smarty = &$this->_getView(); 
        $smarty->assign('Peihuo', $ret);
        $smarty->assign('Product', $product);
        $smarty->display('Cangku/Xianhuo/PeihuoDiaohuo.tpl');
    }
    /**
    * ps ：查找是否有锁定的码单
    * Time：2015/11/06 10:45:25
    * @author jiang
    */
    function actionlockMandan(){
        $sql="select * from madan_db x
        left join madan_rc2madan y on x.id=y.madanId
        left join cangku_ruku2product r on r.id=y.rukuId
        left join cangku_chuku2product c on r.dbId=c.id
        where x.status<>'active' and c.chukuId='{$_POST['id']}' limit 1";
        $ret=$this->_modelExample->findBySql($sql);
        if(count($ret)>0){
            echo json_encode(array('success'=>true));exit;
        }else{
            echo json_encode(array('success'=>false));exit;
        }
    }    
}


?>