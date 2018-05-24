<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Shenhe_Shenhe2Node extends TMIS_TableDataGateway {
    var $tableName = 'shenhe_user2node';
    var $primaryKey = 'id';

    /**
     * 获取审核配置文件里的信息
     * Time：2015/08/28 14:02:44
     * @author li
     * @param  active = true 表示只返回status为true的节点信息
     * @param  status为false表示关闭状态
     * @return Array
    */
    function _config_shenhe($active = true){
        //加载配置文件
        require "Config/Config_Shenhe.php";

        //有效的审核节点
        $_config = array();

        //所有的审核节点
        if($active==false){
            return $_config_shenhe;
        }
        
        //去掉关闭的节点信息
        foreach ($_config_shenhe as $k => & $node) {
            $_node = $this->_setNodeNull($node);
            if(!$_node) continue;

            //最后一个节点标记
            $this->_isLastNode($_node);

            $_config[$k] = $_node;
        }
        return $_config;
    }

    /**
     * 去掉空节点
     * Time：2015/08/28 14:21:30
     * @author li
     * @param array
     * @return Array
    */
    function _setNodeNull(& $node){
        // 方法一
        foreach ($node as $k => & $v) {
            if($v['status']==false){
                unset($node[$k]);
            }
        }

        return $node;

        //方法二
        // $_node =array();
        // if(!$node['id']){
        //     foreach ($node as $k => & $v) {
        //         $_n = $this->_setNodeNull($v);
        //         if($_n)$_node[$k] = $_n;
        //     }
        // }else{
        //     if($node['status']==false){
        //         return false;
        //     }else{
        //         return $node;
        //     }
        // }

        // return $_node;
    }

    /**
     * 给最后一个节点进行标记
     * Time：2015/08/28 15:05:06
     * @author li
    */
    function _isLastNode(& $arr){
        foreach ($arr as $key => &$v) {
            if($v == end($arr))$v['isLast']=true;
        }
    }


    /**
     * 是否需要审核
     * Time：2015/09/08 21:00:07
     * @author li
     * @param string 审核名声
     * @return Boolean 是否需要审核 false表示不需要审核  true表示需要审核
    */
    function _isNeedShenhe($node_key){
        //获取所有有效节点
        $_node = $this->_config_shenhe();

        //不存在想要的节点，则表示该节点不需要审核
        if(!$_node[$node_key]) return false;
        else return true;
    }

    /**
     * 获取最后一个子节点信息
     * Time：2015/09/09 14:19:49
     * @author li
     * @param string
     * @return Array
    */
    function _LastNode($nodeName){
        //获取所有有效节点
        $_node = $this->_config_shenhe();
        $_node = $_node[$nodeName];

        $node = end($_node);
        return $node;
    }

    /**
     * 根据nodeID获取node信息
     * Time：2015/09/09 18:09:13
     * @author li
     * @param id string
     * @return array
    */
    function getNodeById($nodeId){
        $_node_info = $this->_config_shenhe();

        $_node=array();
        foreach($_node_info as & $v){
            foreach ($v as $key => & $n) {
                if($n['id'] == $nodeId){
                    $_node = $n;
                }
            }
        }

        return $_node;
    }

    /**
     * 根据名字查找数据表
     * Time：2015/09/10 15:49:09
     * @author li
    */
    function getDataTable($nodeName){
        require "Config/Config_Shenhe.php";
        //数据表名字
        $_tbl = $_node_table[$nodeName];
        return $_tbl;
    }
}
?>