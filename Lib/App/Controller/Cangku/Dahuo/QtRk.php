<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :chen
*  FName  :QtRk.php
*  Time   :2015/09/28 11:19:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Dahuo_QtRk extends Controller_Cangku_Ruku{
    function __construct() {
        parent::__construct();
        $this->_cangkuName = __CANGKU_3;
        $this->_head = 'DHQT';
        $this->_kind = '其他入库';  
        $this->_mold = 'Dahuo';
        $this->_check='3-3-4';
        $this->fldMain = array(
            'rukuCode' => array('title' => '单号', "type" => "text", 'readonly' => true,'value' =>'自动生成' ),
            'rukuDate' => array('title' => '入库日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'kuweiId' => array('title' => '库位','type' => 'select', 'value' => '','name'=>'kuweiId','model'=>'Model_Jichu_Kuwei','condition'=>'ckName="'.$this->_cangkuName.'"'),
           'kind'=>array('title'=>'类型','type'=>'text','name'=>'kind','value'=>'其他入库','readonly'=>true),
            'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memo'),
            // 下面为隐藏字段
            'id' => array('type' => 'hidden', 'value' => '','name'=>'cgrkId'),
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
            // 'productId' => array('type' => 'btproductpopup', "title" => '产品选择', 'name' => 'productId[]'),
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
            'cnt' => array('type' => 'BtText', "title" => '入库数量', 'name' => 'cnt[]'),
            'cntJian' => array('type' => 'BtText', "title" => '入库卷数', 'name' => 'cntJian[]'),
            // 'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'M','value'=>'M'),
                    array('text'=>'Y','value'=>'Y')
                )),
            'memoView' => array('type' => 'BtText', "title" => '备注', 'name' => 'memoView[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
        );
        $this->sonTpl = array(
            'Cangku/sonTpl.tpl',
        );
    }
    function actionRight(){
        //权限判断
        $this->authCheck('3-3-5');
        parent::actionRight();
    }
}


?>