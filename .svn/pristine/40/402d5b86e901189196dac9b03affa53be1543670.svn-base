<?php
FLEA::loadClass('Controller_Jichu_Supplier');
class Controller_Jichu_Jiagonghu extends Controller_Jichu_Supplier {
    
    function __construct() {
        $this->_modelExample = & FLEA::getSingleton('Model_Jichu_Jiagonghu');
        $this->fldMain = array(
            'id'=>array('title'=>'','type'=>'hidden','value'=>''),
            'isJiagong'=>array('title'=>'','type'=>'hidden','value'=>'1'),
            'compCode'=>array('title'=>'加工户编号','type'=>'text','value'=>$this->_autoCode('','JGH',$this->_modelExample->qtableName,'compCode')),
            'kind'=>array('title'=>'加工户分类','type'=>'combobox','value'=>'','options'=>$this->_modelExample->getKind()),
            'compName'=>array('title'=>'加工户名称','type'=>'text','value'=>''),
            'people'=>array('title'=>'联系人','type'=>'text','value'=>''),
            'isStop'=>array('title'=>'停止往来','type'=>'select','value'=>'','options'=>array(
                    array('text'=>'否','value'=>0),
                    array('text'=>'是','value'=>1)
                )),
            'address'=>array('title'=>'地址','type'=>'text','value'=>''),
            'tel'=>array('title'=>'电话','type'=>'text','value'=>''),
            // 'isSelf'=>array('title'=>'是否本厂','type'=>'radio','value'=>'0','radios'=>array(
            //      array('title'=>'否','value'=>0),
            //      array('title'=>'是','value'=>1)
            // )),
            'memo'=>array('title'=>'备注','type'=>'textarea','value'=>''),
        );

        $this->rules = array(
            'compCode'=>'required repeat',
            'compName'=>'required',
            'kind'=>'required'
        );
    }

    function actionRight() {
        $this->authCheck('15-10');
        // $title = '坯纱供应商档案编辑'; 
        // /////////////////////////////模板文件
        $tpl = 'TblList.tpl'; 
        // /////////////////////////////表头
        $arr_field_info = array(
            '_edit' => '操作',
        ); 
        foreach($this->fldMain as $k=>& $v) {
            if($v['type'] == 'hidden') continue;
            $arr_field_info[$k] = $v['title'];
        }
        // /////////////////////////////模块定义
        
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(
            array('key' => ''
        ));
        $condition[] = array('isJiagong',1,'=');
        if ($arr['key'] != '') {
            $condition[] = array('compCode', "%{$arr['key']}%", 'like', 'or');
            $condition[] = array('compName', "%{$arr['key']}%", 'like');
        } 
        $pager = &new TMIS_Pager($this->_modelExample, $condition);
        $rowset = $pager->findAll();
        if (count($rowset) > 0) foreach($rowset as &$v) {
            $v['_edit'] .= $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
            if($v['isStop']==1)$v['_bgColor']="#5BC0DE";
            $v['isStop']=$v['isStop']==0?'否':'是';
        }
        $smarty = &$this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr))."<font color='blue'>   蓝色表示停止往来</font>");
        $smarty->display($tpl);
    }
    //在模式对话框中显示待选择的客户，返回某个客户的json对象。
    function actionPopup() {
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'key' => '',
        ));
        $sql = "select * from jichu_supplier as z where 1 and isStop=0";
        if($arr['key']!='') $sql.=" and z.compName like '%{$arr['key']}%'
                                    or zhujiCode like '%$arr[key]%'
                                    or compCode like '%$arr[key]%'";
        
        $sql .=" order by z.compName desc";
        $pager =& new TMIS_Pager($sql);
        $rowset=$pager->findAll();
            $arr_field_info = array(                
                "compCode" =>"编码",
                "compName" =>"名称",
                "people" =>"联系人",
                "tel" =>"电话",
                "mobile" =>"手机",
                "taxId" =>"税号",
                "address" =>"地址",
                "comeFrom" =>"来源"
            );
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择加工户');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('add_display','none');
        $smarty->assign('pk', $pk);
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('s',$arr);
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('clean',true);
        $smarty-> display('Popup/CommonNew.tpl');
    }
}
?>