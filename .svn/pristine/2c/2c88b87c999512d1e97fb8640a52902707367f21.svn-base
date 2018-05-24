<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Shenhe_Shenhe2Node extends TMIS_Controller {
    function __construct(){
        $this->_modelShenhe = &FLEA::getSingleton('Model_Shenhe_Shenhe2Node');
    }

    /**
     * 审核设置的编辑界面
     * Time：2015/08/28 13:50:17
     * @author li
    */
    function actionEdit(){
        $this->authCheck('20-3');
        $shenheNode = $this->_modelShenhe->_config_shenhe();

        //查找是否已经设置审核人员
        foreach ($shenheNode as $key => & $node) {
            foreach ($node as $k => & $v) {
                $_res = $this->_modelShenhe->findCount(array('nodeId'=>$v['id']));

                if($_res>0){
                    $v['haveSet']=true;
                }
            }
        }
        // dump($shenheNode);exit;
        $smarty = & $this->_getView();
        $smarty->assign("Node", $shenheNode);
        $smarty->display('Shenhe/Shenhe2Node.tpl');
    }

    /**
     * 设置用户信息
     * Time：2015/08/28 17:42:51
     * @author li
    */
    function actionSetUser(){
        $this->authCheck('20-3');

        $_id = $_GET['id'];
        
        //查找所有的user信息，除了管理员除外
        $_modelUser = &FLEA::getSingleton('Model_Acm_User');
        $users = $_modelUser->getUser();
        // dump($users);

        //查找所有已经设置的用户信息
        $_node_user = $this->_modelShenhe->findAll(array('nodeId'=>$_id));
        $_node_user = array_col_values($_node_user,'userId');
        // dump($_node_user);

        //把已设置的user信息标记下 haveset = true
        foreach ($users as $key => & $v) {
            if(in_array($v['id'],$_node_user)){
                $v['checked'] = true;
            }
        }
        // dump($users);

        $smarty = & $this->_getView();
        $smarty->assign("User", $users);
        $smarty->assign("nodeId", $_id);
        $smarty->display('Shenhe/Shenhe2Node_User.tpl');
    }


    /**
     * 保存设置的USER信息
     * Time：2015/08/29 13:18:43
     * @author li
    */
    function actionSaveUser(){
        // dump($_POST);
        $_nodeId = $_POST['nodeId'];
        $_user = array();
        foreach ($_POST['users'] as $key => & $v) {
            $_user[] = array(
                'userId'=>$v,
                'nodeId'=>$_nodeId,
            );
        }
        // dump($_user);

        //先清空所有的已保存信息：符合nodeId为当前nodeId的数据
        $this->_modelShenhe->removeByConditions(array('nodeId'=>$_nodeId));

        //保存动作
        $this->_modelShenhe->saveRowset($_user);

        //返回操作信息
        js_alert(null,
            "window.parent.parent.showMsg('操作完成');window.parent.location.href=window.parent.location.href",
            $this->_url('Edit')
        );
    }
}
?>