<?php
load_class('TMIS_Model_Cangku_Son');
class Model_Cangku_Chukushenhe2Product extends TMIS_Model_Cangku_Son {
    var $tableName = 'cangku_chukushenhe2product';
    var $primaryKey = 'id';
    var $belongsTo = array (
        array(
            'tableClass' => 'Model_Cangku_Chukushenhe',
            'foreignKey' => 'chukuId',
            'mappingName' => 'Ck',
        ),

    );

}
?>