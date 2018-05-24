<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Shenhe_ShenheDb extends TMIS_TableDataGateway {
	var $tableName = 'shenhe_db';
	var $primaryKey = 'id';
    
    var $belongsTo = array(
        array(
            'tableClass' => 'Model_Acm_User',
            'foreignKey' => 'userId',
            'mappingName' => 'User'
        )
    );
    
    /**
     * 判断某一个审核节点是否审核完成
     * Time：2015/09/09 16:05:08
     * @author li
     * @param nodeName String
     * @param node Array
     * @param pkv 对应shenhe_db 表中的tableId
     * @return string
    */
    function _shenhe_status($nodeName,$node,$pkv){

        //判断是否为最后一个节点
        $_isLast = $node['isLast'];

        //审核节点id
        $_nodeId = $node['id'];

        
       //该节点的实际审核状态
        $_node_status = $this->getNodeStatus($_nodeId,$pkv);

        //该条审核的总状态
        $_status = $this->getStatus($nodeName,$pkv);

        //开始判断该子节点应该显示的状态
        if($_status == 'yes'){
            return '通过';
        }

        //如果是最后一个节点且已审核，则必须和总状态一致
        if($_isLast && $_node_status!=''){
            return $_status == '' ? '未审核' : '未通过';
        }

        //保持自己审核的原状态
        $_status_str = '';
        if($_node_status=='yes'){
            $_status_str = '通过';
        }elseif($_node_status=='no'){
            $_status_str = '未通过';
        }else{
            $_status_str = '未审核';
        }
        return $_status_str;
        
    }

    /**
     * 查找审核总状态是否为审核通过
     * Time：2015/09/10 15:52:41
     * @author li
     * @param nodeName string 审核名称
     * @param pkv int 审核表中的tableId值
     * @return string
    */
    function getStatus($nodeName,$pkv){
        $_shenhe2Node = & FLEA::getSingleton('Model_Shenhe_Shenhe2Node');
        //数据表名字
        $_tbl = $_shenhe2Node->getDataTable($nodeName);

        //查找总状态
        $sql="select shenhe from {$_tbl} where id = '{$pkv}'";
        $_row = $this->findBySql($sql);
        $_status = $_row[0]['shenhe'];

        return $_status;
    }

    /**
     * 查找某个节点的审核状态
     * Time：2015/09/10 15:56:48
     * @author li
     * @param nodeId string 审核节点id
     * @param pkv int 审核表中的tableId值
     * @return string
    */
    function getNodeStatus($_nodeId,$pkv){
        //查找是否在shenhe_db中已经存在
         $_db_shenhe = $this->find(array(
            'nodeId'=>$_nodeId,
            'tableId'=>$pkv
        ));        
        $_node_status = $_db_shenhe['status'];//该子节点的实际状态

        return $_node_status;
    }
}
?>