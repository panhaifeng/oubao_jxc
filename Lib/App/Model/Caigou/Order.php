<?php
load_class('TMIS_TableDataGateway');
class Model_Caigou_Order extends TMIS_TableDataGateway {
	var $tableName = 'caigou_order';
	var $primaryKey = 'id';
	var $primaryName = 'orderCode';
	var $hasMany = array (
		array(
			'tableClass' => 'Model_Caigou_Order2Product',
			'foreignKey' => 'caigouId',
			'mappingName' => 'Products',
		)
	);
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Supplier',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier'
		)
	);
		
}


?>