<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Chukushenhe extends TMIS_TableDataGateway {
    var $tableName = 'cangku_chukushenhe';
    var $primaryKey = 'id';
    var $primaryName = 'chukuCode';
    var $hasMany = array (
        array(
            'tableClass' => 'Model_Cangku_Chukushenhe2Product',
            'foreignKey' => 'chukuId',
            'mappingName' => 'Products',
        )
    );
}


?>