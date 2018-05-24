<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Plan extends TMIS_TableDataGateway {
    var $tableName = 'chuku_plan';
    var $primaryKey = 'id';
    
    var $belongsTo = array (
        array(
            'tableClass' => 'Model_Jichu_Product',
            'foreignKey' => 'productId',
            'mappingName' => 'Product',
            'p_primaryKey' => 'proCode',
        )
    );


    /**
     * 添加获取配送方式
     * 添加获取物流方式
     * API 获取 EC 的数据
     * Time：2015/09/18 13:44:41
     * @author li
     * @param delivery=true需要获取配送方式
     * @param logi_id=true需要获取物流方式
     * @return array
    */
    function getWuliuFromEc($params = array('delivery'=>true,'logi_id'=>true),$time = 10){
        $obj_api = FLEA::getSingleton('Api_Request');
        $r = $obj_api->callApi(array(
            'method'=>'apioubao.erp.response.get_delivery',
            'params'=>$params
        ));

        $ret = json_decode($r,true);

        return $ret;
    }
        
}


?>