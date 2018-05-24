<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Xianhuo_JiagongRk extends Controller_Cangku_Ruku{
    /**
     * 添加登记界面的权限
     * @var array
    */
    var $_check;
    function __construct() {
        parent::__construct();
        $this->_cangkuName = __CANGKU_1;
        $this->_head = 'XHJG';
        $this->_kind = '加工入库';
        $this->_mold = 'Xianhuo';
        $this->_check='3-1-3';
        $this->fldMain = array(
            'rukuCode' => array('title' => '单号', "type" => "text", 'readonly' => true,'value' =>'自动生成'),
            'rukuDate' => array('title' => '入库日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'kuweiId' => array('title' => '仓库','type' => 'select', 'value' => '','name'=>'kuweiId','model'=>'Model_Jichu_Kuwei','condition'=>'ckName="'.$this->_cangkuName.'"','isSearch'=>true),
            'kind'=>array('title'=>'类型','type'=>'text','name'=>'kind','value'=>'加工入库','readonly'=>true),
            'jiagonghuId' => array('title' => '加工户','type' => 'select', 'value' => '','name'=>'jiagonghuId','model'=>'Model_Jichu_Jiagonghu','condition'=>'isJiagong=1 and isStop=0','isSearch'=>true),
            'madanExport'=>array('title'=>'码单导入','type'=>'file','name'=>'madanExport2'),
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
            'cnt' => array('type' => 'BtText', "title" => '入库数量', 'name' => 'cnt[]','readonly'=>true),
            'cntJian' => array('type' => 'BtText', "title" => '入库卷数', 'name' => 'cntJian[]','readonly'=>true),
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
            'jiagonghuId' => 'required',
        );

        $this->sonTpl = array(
            'Cangku/sonTpl.tpl',
        );
    }

    function actionEditShenhe(){
        $this->authCheck('3-1-13-1');
        $arr = $this->_rukushenhe->find(array('id' => $_GET['id']));
        foreach ($cai['Products'] as & $c) {
            $retCai[$c['id']]=$c;
        }

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
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
            $v['chengfen'] = $_temp[0]['chengfen'];
            $v['shazhi'] = $_temp[0]['shazhi'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['jwmi'] = $_temp[0]['jingmi'].'*'.$_temp[0]['weimi'];

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