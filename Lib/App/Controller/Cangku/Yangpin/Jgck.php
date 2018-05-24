<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Xianhuo_Jgck');
class Controller_Cangku_Yangpin_Jgck extends Controller_Cangku_Xianhuo_Jgck{

    function __construct() {
        $this->_subModel = FLEA::getSingleton('Model_Cangku_Chuku2Product');


        parent::__construct();
        
        $this->_cangkuName = __CANGKU_2;

        $this->_kind = "外协出库";

        $this->_head = "WXCK";
        
        $this->_check='3-2-8';
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
            'cnt' => array('type' => 'BtText', "title" => '出库数量', 'name' => 'cnt[]'),
            'cntJian' => array('type' => 'BtText', "title" => '出库卷数', 'name' => 'cntJian[]'),
            // 'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'M','value'=>'M'),
                    array('text'=>'Y','value'=>'Y')
                )),
            // 'unit' => array('type' => 'BtText', "title" => '单位', 'name' => 'unit[]','value' => 'M','readonly'=>true),
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