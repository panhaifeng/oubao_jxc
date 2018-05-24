<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_Rc2Madan extends TMIS_TableDataGateway {
    var $tableName = 'madan_rc2madan';
    var $primaryKey = 'id';
    var $belongsTo = array(
        array(
            'tableClass' => 'Model_Cangku_Ruku2Product',
            'foreignKey' => 'rukuId',
            'mappingName' => 'Ruku'
        ),
        array(
            'tableClass' => 'Model_Cangku_Madan',
            'foreignKey' => 'madanId',
            'mappingName' => 'Madan'
        )
    );
}


?>