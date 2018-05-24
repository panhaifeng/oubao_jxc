<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Tuihuo extends TMIS_TableDataGateway {
    var $tableName = 'cangku_chuku';
    var $primaryKey = 'id';
    var $primaryName = 'chukuCode';
    var $hasMany = array (
        array(
            'tableClass' => 'Model_Cangku_Tuihuo2Product',
            'foreignKey' => 'chukuId',
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