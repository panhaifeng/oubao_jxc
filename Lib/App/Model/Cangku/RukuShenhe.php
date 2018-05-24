<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_RukuShenhe extends TMIS_TableDataGateway {
    var $tableName = 'cangku_rukushenhe';
    var $primaryKey = 'id';
    var $primaryName = 'rukuCode';
    var $hasMany = array (
        array(
            'tableClass' => 'Model_Cangku_Rukushenhe2Product',
            'foreignKey' => 'rukuId',
            'mappingName' => 'Products',
        )
    );
    var $belongsTo = array (
        array(
            'tableClass' => 'Model_Jichu_Kuwei',
            'foreignKey' => 'kuweiId',
            'mappingName' => 'Kuwei'
        )
    );
        
}


?>