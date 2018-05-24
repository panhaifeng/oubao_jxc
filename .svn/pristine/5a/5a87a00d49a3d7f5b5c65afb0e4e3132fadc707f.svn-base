<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Shenhe_Shenhe extends TMIS_Controller {
    function __construct(){
        $this->_modelShenheDb = &FLEA::getSingleton('Model_Shenhe_ShenheDb');
        $this->_modelShenhe2Node = &FLEA::getSingleton('Model_Shenhe_Shenhe2Node');
    }
    /**
     * ps ：审核登记界面
     * Time：2015/09/04 13:49:03
     * @author jiang
    */
    function actionEditShenhe(){
        //先判读是否有权限
        if(!$this->authShenhe($_GET['nodeId'])){
            js_alert('没有该审核节点的权限，无法操作','window.history.go(-1)');
            exit;
        }

        //审核名称
        $_nodeName = $_GET['nodeName'];

        if($_nodeName!=''){
            //修复补丁，一般只有改了审核几点数量的时候才会有
            $this->actionBuild($_nodeName,$_GET['id']);
        }


        //查找已经审核的数据信息：修改的时候展示
        $row=$this->_modelShenheDb->find(array(
            'nodeId'=>$_GET['nodeId'],
            'tableId'=>$_GET['id']
        ));

        !$row && $row=array('status'=>'yes');

        //特殊情况下改变审核状态，一般只有改变了审核节点数量的情况下才会出现，这里面考虑的不严谨
        //如果有问题，注释if中的所有代码，不会影响其他地方，注意测试
        if($_nodeName!=''){
            //根据nodeID获取node
            $node = $this->_modelShenhe2Node->getNodeById($_GET['nodeId']);
            // dump($row);exit;

            //依据总状态判断这个节点的状态
            $_status = $this->_modelShenheDb->_shenhe_status($_nodeName,$node,$_GET['id']);

            if($_status=='未审核'){
                unset($row['User']);
                unset($row['memo']);
            }
        }
        

        // dump($row);exit;
        $smarty = &$this->_getView();
        $smarty->assign('row', $row);
        $smarty->assign('Arr', $_GET);
        $smarty->display('Shenhe/Shenhe.tpl');
    }
    /**
     * ps ：保存审核
     * Time：2015/09/04 14:29:14
     * @author jiang
    */
    function actionSaveShenhe(){
        // dump($_POST);exit;
        $_p = $_POST;
        //取消审核删除已经审核的数据
        if($_p['status'] == 'remove'){
            $_id = (int)$_p['id'];
            $_id > 0 && $this->_modelShenheDb->removeByPkv($_id );
        }else{
            $arr=array(
                'id'=>$_p['id'],
                'status'=>$_p['status'],
                'userId'=>$_SESSION['USERID']+0,
                'nodeId'=>$_p['nodeId'],
                'tableId'=>$_p['tableId'],
                'last'=>$_p['isLast'],
                'nodeName'=>$_p['nodeName'],
                'memo'=>$_p['memo'],
            );
            $this->_modelShenheDb->save($arr);
        }

        //更新主表的状态
        if($_p['status'] == 'remove'){
            $status='';
        }elseif($_p['status']==no || $_p['isLast']==1){
            $status = $_p['status'];
        }else{
            $status='';
        }

        //更新最终审核的状态
        require "Config/Config_Shenhe.php";
        $_tbl = $_node_table[$_p['nodeName']];
        $sql="update {$_tbl} set shenhe='{$status}' where id = '{$_p['tableId']}'";
        $this->_modelShenheDb->execute($sql);

        //更新订单表的审核时间
        if($_p['status'] == 'remove'){
            $ShenheTime='';
        }elseif($_p['status']=='yes' && $_p['isLast']==1){
            $ShenheTime = date('Y-m-d H:i:s');
        }else{
            $ShenheTime='';
        }
        if($_p['status']=='yes' && $_p['isLast']==1 && $_tbl=='trade_order'){
            $sql="update trade_order set ShenheTime='{$ShenheTime}' where id = '{$_p['tableId']}'";
            $this->_modelShenheDb->execute($sql);
        }

        //如果本次节点是最后一次节点，修改之前的保存的最后节点变成非最后一次节点
        if($_p['isLast'] ==1){
            $sql="update shenhe_db set last=0 where 1
                        and nodeName='{$_p['nodeName']}'
                        and tableId='{$_p['tableId']}'
                        and last=1
                        and nodeId<>'{$_p['nodeId']}'";
            $this->_modelShenheDb->execute($sql);
        }
        

        js_alert(
            null,
            'if(window.top==window.self){alert("操作成功");}
            else {window.parent.parent.showMsg("操作成功");window.parent.location.href=window.parent.location.href;}',
            url('Trade_Order','ShenheByPhone')
        );
    }


    /**
     * 问题数据修复
     * 判断总状态是否为通过，通过的情况查看是否有部分节点没有数据的情况，如果有表示审核节点有变化
     * Time：2015/09/10 15:12:01
     * @author li
     * @param shenheName string 审核名称 
     * @param tableId int 审核表中tableId 的值 
     * @return int 新曾数量
    */
    function actionBuild($shenheName,$tableId){
        $_field_cnt = 0;
        $count = $this->_modelShenheDb->findCount(array('tableId'=>$tableId,'nodeName'=>$shenheName));

        //查找总状态是否为审核通过
        $_status = $this->_modelShenheDb->getStatus($shenheName,$tableId);

        //如果不是审核成功状态，直接返回，不进行任何处理
        if($_status!='yes')return $_field_cnt;

        //查找有效节点
        $_nodes = $this->_modelShenhe2Node->_config_shenhe();
        $_nodes = $_nodes[$shenheName];

        if(count($_nodes)>$count){
            foreach($_nodes as & $_node){
                //查找该节点是否存
                $_res = $this->_modelShenheDb->findCount(array('tableId'=>$tableId,'nodeName'=>$shenheName,'nodeId'=>$_node['id']));
                if(!$_res>0){
                    $_arr = array(
                        'status'=>'yes',
                        'userId'=>$_SESSION['USERID']+0,
                        'nodeId'=>$_node['id'],
                        'tableId'=>$tableId,
                        'last'=>$_node['isLast']+0,
                        'nodeName'=>$shenheName,
                    );

                    $this->_modelShenheDb->save($_arr);
                    $_field_cnt++;
                }
            }
        }

        return $_field_cnt;
    }
}
?>