<?php
load_class('TMIS_TableDataGateway');
class Model_Caigou_Shengou2Product extends TMIS_TableDataGateway {
	var $tableName = 'caigou_shengou2product';
	var $primaryKey = 'id';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Product',
			'p_primaryKey' => 'proCode',
		),
		array(
			'tableClass' => 'Model_Caigou_Shengou',
			'foreignKey' => 'shengouId',
			'mappingName' => 'Shengou'
		),
		array(
			'tableClass' => 'Model_Jichu_Supplier',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier'
		)
	);
}
?>