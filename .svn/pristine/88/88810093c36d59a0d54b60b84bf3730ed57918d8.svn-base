<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Madan extends TMIS_TableDataGateway {
    var $tableName = 'madan_db';
    var $primaryKey = 'id';

    var $belongsTo = array (
        array(
            'tableClass' => 'Model_Jichu_Product',
            'foreignKey' => 'productId',
            'mappingName' => 'Product',
            'p_primaryKey' => 'proCode',
        ),
    );
    var $hasMany = array(
        array(
            'tableClass' => 'Model_Cangku_Rc2Madan',
            'foreignKey' => 'madanId',
            'mappingName' => 'Madan'
        )
    );

}


?>