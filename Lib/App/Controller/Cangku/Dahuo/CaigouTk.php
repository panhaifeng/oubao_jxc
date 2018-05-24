<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Xianhuo_CaigouTk');
class Controller_Cangku_Dahuo_CaigouTk extends Controller_Cangku_Xianhuo_CaigouTk{
    function __construct() {
        parent::__construct();
        $this->_cangkuName = __CANGKU_3;
        $this->_head = 'CGTK';
        $this->_kind = '采购退库';
        $this->_check='3-1-2';
        $this->_mold = 'Dahuo';
        $this->_modelCaigou = &FLEA::getSingleton('Model_Caigou_Order');
        $this->_modelCaigou2pro = &FLEA::getSingleton('Model_Caigou_Order2Product');
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Tuiku');
        $this->_subModel = &FLEA::getSingleton('Model_Cangku_Tuiku2Product');
        $this->_modelruku = &FLEA::getSingleton('Model_Cangku_Ruku');

        $this->fldMain = array(
            'rukuCode' => array('title' => '入库单号', "type" => "text", 'readonly' => true,'value' =>'自动生成'),
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
            'cnt' => array('type' => 'BtText', "title" => '数量', 'name' => 'cnt[]',),
            'cntJian' => array('type' => 'BtText', "title" => '卷数', 'name' => 'cntJian[]'),
            // 'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
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
}


?>