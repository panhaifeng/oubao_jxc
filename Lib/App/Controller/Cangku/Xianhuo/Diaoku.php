<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :shen
*  FName  :Diaoku.php
*  Time   :2016年4月14日15:35:31
*  Remark :调整库位
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Chuku');
class Controller_Cangku_Xianhuo_Diaoku extends Controller_Cangku_Chuku{
    /**
     * 权限设置
     * @var string
    */
    var $_check;
    function __construct() {
        parent::__construct();
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Madan');
        $this->_cangkuName = __CANGKU_1;
        $this->_check='3-1-17';
        $this->fldMain = array(
            'drukuwei' => array('title' => '调入库位','type' => 'text', 'value' => '','name'=>'drukuwei','model'=>'Model_Jichu_Kuqu','isSearch'=>true),
            // 下面为隐藏字段
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
            'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            'cnt' => array('type' => 'BtText', "title" => '数量', 'name' => 'cnt[]','readonly'=>true),
            'cntJian' => array('type' => 'BtText', "title" => '卷数', 'name' => 'cntJian[]','readonly'=>true),
            // ***************如何处理hidden?
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
            'danjia' => array('type' => 'BtHidden', 'name' => 'danjia[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'kuweiId' => 'required',
            'drukuwei' => 'required',
        );

        $this->sonTpl = array(
            'Cangku/drkuwei.tpl',
        );
    }

    function actionRight(){
        //权限判断
        $this->authCheck($this->_check);
        FLEA::loadClass('TMIS_Pager'); 
        $arr = TMIS_Pager::getParamArray(array(
            'proCode'=>'',
        )); 
        $sql="select *
                from madan_db 
                where 1 and status='active'";

        if($arr['proCode']!='') $sql .=" and productId like '%{$arr['proCode']}%' ";
        $sql.="order by productId asc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();

        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'productId'); 
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'].' M';
        $rowset[]=$hj;
        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            // "_edit" => array('text'=>'操作','width'=>80),
            "productId" => '花型六位号',
            "millNo" => array('text'=>'批次号','width'=>120),
            "cntM" => '米数',
            "rollNo" => '卷号',
            "qrcode" => '条码',
            "kuqu" => '库位',
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
     * 调库位保存
     * Time：2016年4月15日09:00:55
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
        $kuqu = &FLEA::getSingleton('Model_Jichu_Kuqu');
        $kuqu_obj=$kuqu->find(array('kuquName'=>$_POST['drukuwei']));
        // dump($kuqu_obj);die;
        if(!$kuqu_obj){
            js_alert('保存失败,请确认调入库位','window.history.go(-1)');
            exit;
        }

        $maId=array();
        foreach($_POST['productId'] as $key=> & $v) {
            $temp = array();
            foreach($this->headSon as $k=>&$vv) {
                $temp[$k] = $_POST[$k][$key];
            }
            // dump($temp);die;
            $maId[]=$_POST['Madan'][$key];
        }
        if($maId) {
            $maId=array_filter($maId); 
            $maId=implode(",",$maId);
        }
        if($maId){
            $m_sql="update madan_db set kuquId={$kuqu_obj['id']},kuqu={$kuqu_obj['kuquName']} 
            where id in({$maId})";
            // dump($m_sql);die;
            $this->_modelExample->execute($m_sql);
        }else{
            js_alert('保存失败，没有码单明细数据','window.history.go(-1)');
            exit;
        }
        //跳转
        js_alert(null,'window.parent.showMsg("更新成功!")',$this->_url('Right'));

    }

    /**
     * ps ：码单显示
     * Time：2016年4月14日17:17:03
     * @author shen
    */
    function actionViewMadan(){
        // dump($_GET);die;
        $ma=&FLEA::getSingleton('Model_Cangku_Madan');

        $sql="SELECT * from madan_db 
                   where productId='{$_GET['productId']}' and status='active'
                   group by id order by rollNo";
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
           

            $ret['Peihuo'][$v['millNo']][]=$v;
        }
        // dump($ret);die;
        $smarty = &$this->_getView(); 
        $smarty->assign('Peihuo', $ret);
        $smarty->assign('Product', $product);
        $smarty->display('Cangku/Xianhuo/Tzkuwei.tpl');
    }
}


?>