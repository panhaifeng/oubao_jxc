<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Xianhuo_Jgck extends Controller_Cangku_Chuku{
    /**
     * 权限设置
     * @var string
    */
    var $_check;
    function __construct() {
        parent::__construct();
        
        $this->_cangkuName = __CANGKU_1;

        $this->_kind = "外协出库";

        $this->_head = "WXCK";
        
        $this->_check='3-1-8';
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
            'jiagonghuId' => array('title' => '加工户','type' => 'select', 'value' => '','name'=>'jiagonghuId','model'=>'Model_Jichu_Jiagonghu','condition'=>'isJiagong=1 and isStop=0','isSearch'=>true),
            'kind' => array('title'=>'出库类型','type' => 'text', 'value' => $this->_kind,'readonly'=>true),
            'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memo'),
            'isGuozhang' => array('type' => 'hidden', 'value' => '1','name'=>'isGuozhang'),
            'cangkuName' => array('type' => 'hidden', 'value' => $this->_cangkuName,'name'=>'cangkuName'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
            'id' => array('type' => 'hidden', 'value' => '','name'=>'mainId'),
        );

        //子信息
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
            'cnt' => array('type' => 'BtText', "title" => '出库数量', 'name' => 'cnt[]','readonly'=>true),
            'cntJian' => array('type' => 'BtText', "title" => '出库卷数', 'name' => 'cntJian[]','readonly'=>true),
            'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            // 'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
            //         array('text'=>'M','value'=>'M'),
            //         array('text'=>'Y','value'=>'Y')
            //     )),
            'unit' => array('type' => 'BtText', "title" => '单位', 'name' => 'unit[]','value' => 'M','readonly'=>true),
            'memoView' => array('type' => 'BtText', "title" => '备注', 'name' => 'memoView[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
        );

        //表单元素的验证规则定义
        $this->rules = array(
            'kuweiId'=>'required',
            'jiagonghuId'=>'required',
        );

        $this->sonTpl = array('Cangku/cksonTpl.tpl');
    }

    /**
     * 修改
     * Time：2015/09/19 23:05:37
     * @author li
    */
    function actionEdit() {

        $arr = $this->_modelExample->find(array('id' => $_GET['id']));

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
     * 销售出库保存
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
            if($temp['id']>0 && $delmadan[0]){
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
            // $temp['cntJian'] = count($madan_ck);
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
        //锁定码单  只要是出库就得锁定码单 暂时只有现货需要这个功能
        if($this->_cangkuName == __CANGKU_1){
            $maId=join(',',$maId);
            $sql="update madan_db set status='finish' where id in({$maId})";
            $this->_modelExample->findBySql($sql);
        }
        if($row['chukuCode']=='自动生成') $row['chukuCode']=$this->_getNewCode($this->_head,'cangku_chuku','chukuCode');
        $row['Products'] = $pros;
        // dump($row);exit;
        if(!$this->_modelExample->save($row)) {
            js_alert('保存失败','window.history.go(-1)');
            exit;
        }   
        //跳转
        js_alert(null,'window.parent.showMsg("保存成功!")',$this->_url('Right'));

    }

    function actionRight(){
        //权限判断
        $this->authCheck($this->_check);
        FLEA::loadClass('TMIS_Pager'); 
        $arr = TMIS_Pager::getParamArray(array(
            'danhao'=>'',
            'jiagonghuId'=>'',
            'kuweiId_name'=>'',
            'kuweiName'=>$this->_cangkuName,
            'proCode'=>'',
        )); 
        $sql="select x.*,y.id as chukuId,y.chukuDate,y.chukuCode,y.kind,k.kuweiName,
                p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,p.menfu,p.kezhong,s.compName as jiagonghu
                from cangku_chuku2product x
                left join cangku_chuku y on x.chukuId=y.id
                left join jichu_kuwei k on k.id=y.kuweiId
                left join jichu_product p on p.proCode=x.productId
                left join jichu_supplier s on s.id=y.jiagonghuId
                where 1 and cangkuName='{$this->_cangkuName}' and y.kind='{$this->_kind}'";

        if($arr['danhao']!='') $sql .=" and y.chukuCode like '%{$arr['danhao']}%' ";
        if($arr['kuweiId_name']!='') $sql .=" and y.kuweiId = '{$arr['kuweiId_name']}' ";
        if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
        if($arr['jiagonghuId']!='') $sql .=" and y.jiagonghuId = '{$arr['jiagonghuId']}' ";
        $sql.="order by chukuCode desc,chukuDate desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($this->_mold);die;
        foreach($rowset as & $v) {
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];
            $v['_edit'] = $this->getEditHtml($v['chukuId']);

            $v['_edit'].=' ' .$this->getRemoveHtml($v['chukuId']);
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
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
            "_edit" => '操作',
            "chukuCode" => '单号',
            "chukuDate" => '出库日期',
            "kuweiName" => '库位',
            "kind" => '类型',
            "jiagonghu" => '加工户',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "chengfen" => '成分',
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
        // $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
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
}


?>