<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :jiang
*  FName  :Diaohuo.php
*  Time   :2015/10/08 13:57:24
*  Remark :调货出库：处理调货库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Xianhuo_Diaohuo');
class Controller_Cangku_Yangpin_Diaohuo extends Controller_Cangku_Xianhuo_Diaohuo{
    
    function __construct() {
        parent::__construct();
        $this->_cangkuName = __CANGKU_2;
        $this->_head = 'YPDH';
        $this->_kind = '调货';
        $this->_mold = 'Yangpin';
        $this->_check='3-2-9';
        $this->_subModel = FLEA::getSingleton('Model_Cangku_Chuku2Product');
        $this->fldMain = array(
            'chukuCode' => array('title' => '单号', "type" => "text", 'readonly' => true,'value' =>'自动生成'),
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
            'kuweiru' => array('type' => 'hidden', 'value' =>''),

        );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'BtBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
            'pihao'=>array('type'=>'BtText',"title"=>'批号','name'=>'pihao[]'),
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
            'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
            'chengfen'=>array('type'=>'BtText',"title"=>'成分','name'=>'chengfen[]','readonly'=>true),
            'shazhi' => array('type' => 'BtText', "title" => '纱支', 'name' => 'shazhi[]','readonly'=>true),
            'jwmi' => array('type' => 'BtText', "title" => '经纬密', 'name' => 'jwmi[]','readonly'=>true),
            'menfu' => array('type' => 'BtText', "title" => '门幅', 'name' => 'menfu[]','readonly'=>true),
            'cnt' => array('type' => 'BtText', "title" => '数量', 'name' => 'cnt[]'),
            'cntJian' => array('type' => 'BtText', "title" => '卷数', 'name' => 'cntJian[]'),
            'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'M','value'=>'M'),
                    array('text'=>'Y','value'=>'Y')
                )),
            'memoView' => array('type' => 'BtText', "title" => '备注', 'name' => 'memoView[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'kuweiId' => 'required',
            'kind' => 'required',
            'kuweiIdru' => 'required',
        );

        $this->sonTpl = array(
            'Cangku/Diaohuo.tpl',
            'Cangku/dhkw.tpl',
        );
    }

    /**
     * ps ：调货修改
     * Time：2015/10/09 20:01:09
     * @author jiang
    */
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

            //在入库中查找码单
            $sql="select y.* from madan_rc2madan x
                    left join madan_db y on x.madanId=y.id
                    where x.rukuId='{$v['dbId']}'";
            $retMadan = $this->_modelExample->findBySql($sql);
            $_temp=array();
            if(count($retMadan)>0) {
                foreach($retMadan as & $m){
                    //当码单被锁定或者已出库则不能修改码单
                    if ($m['status'] !='active') {
                        $m['readonly']=true;
                    }
                    $_temp[$m['rollNo']-1]=$m;
                }
                $_temp['isCheck']=1;
                $v['Madan'] = json_encode($_temp);
            }else{
                $v['Madan'] ='';
            }
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

    /**
     * ps ：调货出库保存
     * Time：2015/10/09 12:42:41
     * @author jiang
    */
    function actionSave(){
        // dump($_POST);exit;
        $modelMadan2ruku = FLEA::getSingleton('Model_Cangku_Rc2Madan');
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
        foreach($_POST['productId'] as $key=> & $v) {
            if($v=='' || empty($_POST['cnt'][$key])|| $_POST['pihao'][$key]=='') continue;
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            $temp['unit']=='' && $temp['unit']='M';//默认不能为空
            $temp['cntM'] = $temp['unit'] == 'M' ? $temp['cnt'] : $temp['cnt']*0.9144;
            //2015-10-30 by jiang 保存码单json
            //入库的码单
            if($_POST['kuweiru']!='现货'){
                $this->_madanDelete($temp);
            }
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
        $chuId=$this->_modelExample->save($row);
        $chuId=$_POST['cgckId']==''?$chuId:$_POST['cgckId'];
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
                $m['rukuDate']=$row['chukuDate'];
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
                    'cntJian' => $v['cntJian'],
                    'cnt' => $v['cnt'],
                    'cntM' => $v['cntM'],
                    'unit' => $v['unit'],
                    'dbId' => $v['id'],
                    'memoView' => $v['memoView'].'',
                    'Madan' => $v['Madan'],
                    'madan_clear' => $v['madan_clear']
            );
        }
        $ckName=$this->_modelKw->find(array('id'=>$_POST['kuweiIdru']));
        $rowruku=array(
                'id'            =>$ru2pro[0]['id'],
                'rukuDate'     =>$_POST['chukuDate'],
                'rukuCode'     =>$row['chukuCode'],
                'kuweiId' => $_POST['kuweiIdru'],
                'cangkuName' => $ckName['ckName'],
                'isGuozhang' => $_POST['isGuozhang'],
                'creater' => $_POST['creater'].'',
                'memo'          =>$_POST['memo'].'',
                'kind'          =>$_POST['kind'].'',
                'Products'      => $arrRu
        );
        
        // dump($rowruku);exit;  
        $rukuId=$this->_modelRuk->save($rowruku);
        $rukuId=$ru2pro[0]['id']?$ru2pro[0]['id']:$rukuId;

        //2015-10-30 by jiang 保存玩入库后在将入库的Id保存到出库表的dbId中
        $retRuku=$this->_modelRuk->find(array('id'=>$rukuId));
        $retChuku=array();
        foreach ($retRuku['Products'] as & $r) {
            //查找码单信息
            $_temp=$modelMadan2ruku->findAll(array('rukuId'=>$r['id']));
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

        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('Right'));

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
        $temp['Madan']=json_decode($temp['Madan'],true);
        if(count($temp['Madan'])>0){
            foreach ($temp['Madan'] as & $t) {
                $t['cntFormat']='';;
            }
            $temp['Madan']=json_encode($temp['Madan']);
        }
    } 

    /**
     * 显示码单入库信息
     * Time：2015/09/07 15:16:43
     * @author jiang
    */
    function actionViewMadan(){
        $smarty = & $this->_getView();
        $smarty->assign('title', "码单编辑");
        $smarty->display("Cangku/MadanEdit.tpl");
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

}


?>