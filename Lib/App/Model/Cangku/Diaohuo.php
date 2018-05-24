<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Diaohuo extends TMIS_TableDataGateway {
    var $tableName = 'cangku_chuku';
    var $primaryKey = 'id';
    var $primaryName = 'chukuCode';
    var $hasMany = array (
        array(
            'tableClass' => 'Model_Cangku_Diaohuo2Product',
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