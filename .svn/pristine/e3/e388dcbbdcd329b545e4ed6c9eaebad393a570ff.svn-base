<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Client extends TMIS_TableDataGateway {
	var $tableName = 'jichu_client';
	var $primaryKey = 'id';
	var $primaryName = 'compName';
	var $sortByKey = ' convert(trim(compName) USING gbk)';
	// var $optgroup = true;
	
	var $belongsTo = array(
		array(
			'tableClass' => 'Model_Jichu_Employ',
			'foreignKey' => 'traderId',
			'mappingName' => 'Trader'
		),
		array(
            'tableClass' => 'Model_Jichu_Lidan',
            'foreignKey' => 'lidanId',
            'mappingName' => 'Lidan'
        )
	);
}
?>