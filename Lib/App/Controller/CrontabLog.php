<?php
FLEA::loadClass('TMIS_Controller');
class Controller_CrontabLog extends TMIS_Controller{
    function __construct() {
        $this->_modelExample = FLEA::getSingleton('Model_CrontabLog');
    }

    /**
     * 日志查看工具
     * Time：2015/10/27 09:44:25
     * @author li
     * @param 参数类型
     * @return 返回值类型
    */
    function actionRight(){
        $this->authCheck('20-10');
        FLEA::loadClass('TMIS_Pager'); 
        $arr = TMIS_Pager::getParamArray(array(
            'key'=>''
        )); 
        $condition[]=array('desc',"%{$arr['key']}%",'like');

        $pager = & new TMIS_Pager($this->_modelExample,$condition,'createtime desc');
        $rowset = $pager->findAll();
        // dump($rowset);exit;

        foreach($rowset as &$v) {
            $v['runtime'] = date('Y-m-d H:i:s',$v['runtime']);
            $v['createtime'] = date('Y-m-d H:i:s',$v['createtime']);

            $v['isActive'] = $v['isActive']==1?'是':'否';
        }

        $smarty = & $this->_getView(); 
        $arrFieldInfo = array(
            // "_edit" => '操作',
            "result" => "执行结果",
            "desc" => array('text'=>"任务描述",'width'=>200),
            "address" => array('text'=>"执行地址",'width'=>160),
            "isActive" => "是否启用",
            "runtime" => array('text'=>"计划执行时间",'width'=>160),
            'createtime'=>array('text'=>"任务创建时间",'width'=>160),
        ); 
        $smarty->assign('title', '计划查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }
}
?>