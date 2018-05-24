<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Xianhuo_Shrk extends Controller_Cangku_Ruku{
    /**
     * 添加登记界面的权限
     * @var array
    */
    var $_check;
    function __construct() {
        parent::__construct();
        // $this->_rukushenhe = &FLEA::getSingleton('Model_Cangku_RukuShenhe');
        $this->_cangkuName = __CANGKU_1;
        $this->_mold = 'Xianhuo';
        // $this->_check='3-1-5';
    }


    function actionShenheList(){
        $this->authCheck('3-1-13');
        FLEA::loadClass('TMIS_Pager'); 

        //构造搜索区域的搜索类型
        $arr = TMIS_Pager::getParamArray(array(
            'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
            'dateTo' => date("Y-m-d"),
            'danhao'=>'',
            'hetongCode'=>'',
            'kuweiId_name'=>'',
            'kuweiName'=>$this->_cangkuName,
            'kind'=>'',
            'proCode'=>'',
            'jiagonghuId'=>'',
            'isCheck'=>'',
        )); 
        // $sql="SELECT x.*,y.id as rukuId,y.rukuDate,y.rukuCode,y.kind,y.isCheck,k.kuweiName,c.orderCode,
        //         p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,p.menfu,p.kezhong,s.compName as jiagonghu
        //         from cangku_rukushenhe2product x
        //         left join cangku_rukushenhe y on x.rukuId=y.id
        //         left join jichu_kuwei k on k.id=y.kuweiId
        //         left join caigou_order c on c.id=y.caigouId
        //         left join jichu_product p on p.proCode=x.productId
        //         left join jichu_supplier s on s.id=y.jiagonghuId
        //         where 1 and cangkuName='{$this->_cangkuName}' ";
        $sql="SELECT x.id as rukuId,x.rukuDate,x.rukuCode,x.kind,x.isCheck,k.kuweiName,
            c.orderCode,s.compName as jiagonghu
             from cangku_rukushenhe x 
            left join  jichu_kuwei k on k.id=x.kuweiId
            left join caigou_order c on c.id=x.caigouId
            left join jichu_supplier s on s.id=x.jiagonghuId
            where 1 and cangkuName='{$this->_cangkuName}' ";
        if($arr['dateFrom'] != '') {
            $sql .=" and x.rukuDate >= '{$arr['dateFrom']}' and x.rukuDate <= '{$arr['dateTo']}'";
        }
        if($arr['danhao']!='') $sql .=" and x.rukuCode like '%{$arr['danhao']}%' ";
        if($arr['hetongCode']!='') $sql .=" and c.orderCode like '%{$arr['hetongCode']}%' ";
        if($arr['kuweiId_name']!='') $sql .=" and x.kuweiId = '{$arr['kuweiId_name']}' ";
        if($arr['kind']!='') $sql .=" and x.kind = '{$arr['kind']}' ";
        if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
        if($arr['jiagonghuId']!='') $sql .=" and x.jiagonghuId = '{$arr['jiagonghuId']}' ";
        if($arr['isCheck']!='') $sql .=" and x.isCheck = '{$arr['isCheck']}' ";
        $sql.="order by rukuDate desc,rukuCode desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach($rowset as & $v) {
            $str="SELECT y.id,z.color,y.cnt,y.unit,y.danjia,y.cntJian,z.proCode,z.proName,z.chengfen,z.shazhi,z.menfu,CONCAT(z.jingmi,'*',z.weimi) as jwmi
                from cangku_rukushenhe2product y
                left join jichu_product z on z.proCode=y.productId
                where 1 and y.rukuId='{$v['rukuId']}'";
            $ret=$this->_modelExample->findBySql($str);
            $v['Products']=$ret;

            $v['rukuDate']=$v['rukuDate']=='0000-00-00'?'':$v['rukuDate'];

            //2015-10-29 by jiang 已过账禁止修改和删除
            $sql="select * from caiwu_yf_guozhang where ruku2ProId='{$v['id']}'";
            $ret=$this->_modelExample->findBySql($sql);
            if(count($ret)>0){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已过账禁止修改和删除'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已过账禁止修改和删除'></span>";
            }else{
                if($v['kind']=='采购入库'){ 
                    $v['_edit']="<a href='".url('Cangku_'.$this->_mold.'_CaigouRk','EditShenhe',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
                }
                else if($v['kind']=='加工入库'){
                    $v['_edit']="<a href='".url('Cangku_'.$this->_mold.'_JiagongRk','EditShenhe',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
                }
                else if($v['kind']=='采购退库'){
                    $v['_edit']="<a href='".url('Cangku_'.$this->_mold.'_CaigouTk','EditShenhe',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
                }
                else if($v['kind']=='其他入库'){
                    $v['_edit']="<a href='".url('Cangku_'.$this->_mold.'_QtRk','EditShenhe',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
                }
                else{
                    $v['_edit'] = $this->getEditHtml($v['rukuId']);
                }

                
                $v['_edit'].=' ' .$this->getRemoveHtml($v['rukuId']);
                if($v['isCheck']==0){
                    $v['_edit'].=' ' ."<a href='".$this->_url('Shenhe',
                    array(
                        'id'=>$v['rukuId'],
                        'fromAction'=>$_GET['action']
                        ))."'><span class='glyphicon glyphicon-ok' title='审核'></span></a>";
                }else{
                    $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已审核禁止修改和删除'></span>";
                    $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已审核禁止修改和删除'></span>";
                    $v['_edit'].=' ' ."<span class='glyphicon glyphicon-ok' ext:qtip='已审核'></span>";
                }
                
            }

            //采购退货
            // if($v['kind']=='采购入库'){
            //      $v['_edit'].="&nbsp;<a href='".url('Cangku_'.$this->_mold.'_CaigouTk','AddShenhe',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-log-out' title='采购退库登记'></span></a>";
            // }
            //调货
            if($v['kind']=='调货' ){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='调货数据不能修改'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash' ext:qtip='调货数据不能删除'></span>";
            }
            //退换入库
            if($v['kind']=='退换入库' ){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='退换入库数据不能在此页面修改'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash' ext:qtip='退换入库数据不能在此页面删除'></span>";
            }
            //只有现货仓库需要查看码单和打印入库标签
            if($this->_cangkuName==__CANGKU_1){
                $v['_edit'].="&nbsp;<a href='".$this->_url('MadanCheck',array(
                    'id'=>$v['rukuId'],
                    'fromAction'=>$_GET['action'],
                    'width'=>'700',
                    'height'=>'400',
                ))."' class='thickbox'><span class='glyphicon glyphicon-zoom-in' title='查看码单'></span></a>";
            }
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            
        } 
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'_edit'); 
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'].' M';
        $rowset[]=$hj;
        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            "_edit" => array('text'=>'操作','width'=>95),
            "rukuCode" => '单号',
            "rukuDate" => '入库日期',
            "kuweiName" => '库位',
            "kind" => '类型',
            "orderCode" => '采购合同',
            "jiagonghu" => '加工户',
            // "proCode" => '花型六位号',
            // "proName" => '品名',
            // "chengfen" => '成分',
            // "jwmi" => '经纬密',
            // "menfu" => '门幅',
            // "cnt" => '数量',
            // "cntJian" => '卷数',
            // "unit" => '单位'
        );
        $arrField = array(
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
        $smarty->assign('arr_field_info2', $arrField);
        $smarty->assign('sub_field', 'Products');
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblListMore.tpl');
    }



    function actionRemove() {
        $this->authCheck('3-1-13-2');
        $from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
        if ($this->_rukushenhe->removeByPkv($_GET['id'])) {
            if($from=='') redirect($this->_url("right"));
            else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
        }
        else js_alert('出错，不允许删除!',null,$this->_url($from));

    }
    
    /**
     * 查看码单
     * Time：2015-12-14 14:24:38
     * @author shen
    */
    function actionMadanCheck(){
        $this->authCheck('3-1-13-4');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
        )); 

        $sql="SELECT y.* from  cangku_rukushenhe x
            left join cangku_rukushenhe2product y on y.rukuId=x.id
            where x.id='{$_GET['id']}' ";

        $rowset = $this->_modelExample->findBySql($sql);

        // dump($rowset);die;
        $_temp=array();
        foreach ($rowset as & $v) {
            $v['Madan'] = json_decode($v['MadanJson'],true);
            foreach ($v['Madan'] as &$vv) {
                $_temp[] = $vv;
            }
        }
        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            "productId" => '花型六位号',
            "millNo" => '批次号',
            "cntM" => '米数',
            "rollNo" => '卷号',
            "qrcode" => '条码',
            "kuqu" => '库区',
        );
        $smarty->assign('title', '查看码单');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $_temp);
        // $smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }

    /**
     * 审核入库
     * Time：2015-12-14 14:24:38
     * @author shen
    */
    function actionShenhe(){
        $this->authCheck('3-1-13-3');
        $arr = $this->_rukushenhe->find(array('id' => $_GET['id']));
        $sql = "update cangku_rukushenhe set isCheck=1 where id = '{$_GET['id']}'";
        // $this->_rukushenhe->execute($sql);
        foreach($arr['Products'] as & $v){
            unset($v['id']);
            $madan = json_decode($v['MadanJson']);
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
                $m['rukuDate']=$row['rukuDate'];
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
        }
        dump($arr);die;
        unset($arr['id']);
        unset($arr['isCheck']);
        if(!$this->_modelExample->save($arr)) {
            js_alert('审核失败','window.history.go(-1)');
            exit;
        }
        //跳转
        js_alert(null,'window.parent.showMsg("审核成功!")',$this->_url('ShenheList'));
    }
}


?>