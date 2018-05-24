<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Api_Log extends TMIS_Controller {
    var $_modelExample;
    var $funcId = 35;
    function __construct() {
        $this->_modelExample = & FLEA::getSingleton('Model_Api_Log');

        $this->fldMain = array(
            'id'=>array('title'=>'','type'=>'hidden','value'=>''),
            'success'=>array('title'=>'是否成功','type'=>'text','value'=>''),
            'apiName'=>array('title'=>'api名称','type'=>'text','value'=>''),
            'url'=>array('title'=>'相应url','type'=>'text','value'=>''),
            'params'=>array('title'=>'传入参数','type'=>'text','value'=>''),
            'msg'=>array('title'=>'返货错误信息','type'=>'text','value'=>''),
            'calltime'=>array('title'=>'调用时间','type'=>'text','value'=>''),
            'retrytime'=>array('title'=>'最近重试时间','type'=>'text','value'=>''),
        );
    }

    /**
     * @desc ：api响应日志
     * @author jeff 2015/09/21 09:52:41
     * @param 
     * @return 返回值类型
    */
    function actionRight() {
        $this->authCheck('20-4');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'key'=>''
        ));
        $sql="select * from api_log where type=0 order by calltime desc";
        // if($arr['key']!='') $sql .=" and itemName like '%{$arr['key']}%'";
        $pager = new TMIS_Pager($sql);
        $rowset =$pager->findAll();
        // dump($rowset);exit;
        if(count($rowset)>0) foreach($rowset as & $v) {
            // $v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
            //TODO
            // $v['_edit'] = $v['success']?'':"<a href='".$this->_url()."'>重新发起</a>";
        }
        //dump($rowset);
        $smarty = & $this->_getView();
        $smarty->assign('title', '银行帐户');
        #对操作栏进行赋值
        
        $arr_field_info = array(
            '_edit'=>'操作',
            "success" =>"是否成功",
            "apiName"=>"api名称",
            "url"=>"相应url",
            "params"=>"传入参数",
            "msg"=>"返回错误信息",
            "calltime"=>array('text'=>"调用时间",'width'=>200),
            "retrytime"=>array('text'=>"最近重试时间",'width'=>200),
        );

        // $smarty->assign('arr_edit_info',$arr_edit_info);
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('add_display','none');
        $smarty->assign('page_info',$pager->getNavBar($this->_url('right')));
        // $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('grid')));
        $smarty-> display('TblList.tpl');
    }

    /**
     * @desc ：api调用日志
     * @author jeff 2015/09/21 09:52:41
     * @param 参数类型
     * @return 返回值类型
    */
    function actionCallRight() {
        $this->authCheck('20-4-2');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'key'=>''
        ));
        $sql="select * from api_log where type=1";
        // if($arr['key']!='') $sql .=" and itemName like '%{$arr['key']}%'";
        $pager = new TMIS_Pager($sql);
        $rowset =$pager->findAll();
        // dump($rowset);exit;
        if(count($rowset)>0) foreach($rowset as & $v) {
            // $v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
            //TODO
            // $v['_edit'] = $v['success']?'':"<a href='".$this->_url()."'>重新发起</a>";
        }
        //dump($rowset);
        $smarty = & $this->_getView();
        $smarty->assign('title', '银行帐户');
        #对操作栏进行赋值
        
        $arr_field_info = array(
            '_edit'=>'操作',
            "success" =>"是否成功",
            "apiName"=>"api名称",
            "url"=>"相应url",
            "params"=>"传入参数",
            "msg"=>"返回错误信息",
            "calltime"=>array('text'=>"调用时间",'width'=>200),
            "retrytime"=>array('text'=>"最近重试时间",'width'=>200),
        );

        // $smarty->assign('arr_edit_info',$arr_edit_info);
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('add_display','none');
        $smarty->assign('page_info',$pager->getNavBar($this->_url('right')));
        // $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('grid')));
        $smarty-> display('TblList.tpl');
    }

    /**
     * @desc ：重新调用日志
     * @author jeff 2015/09/21 09:52:41
     * @param 参数类型
     * @return 返回值类型
    */
    function actionRetryCall() {
        echo '开发中';
    }
}
?>