<?php
load_class('TMIS_TableDataGateway');
class Model_Cangku_BackPlan extends TMIS_TableDataGateway {
    var $tableName = 'cangku_back_plan';
    var $primaryKey = 'id';
    
    var $belongsTo = array (
        array(
            'tableClass' => 'Model_Jichu_Product',
            'foreignKey' => 'productId',
            'mappingName' => 'Product',
            'p_primaryKey' => 'proCode',
        )
    );
}


?>