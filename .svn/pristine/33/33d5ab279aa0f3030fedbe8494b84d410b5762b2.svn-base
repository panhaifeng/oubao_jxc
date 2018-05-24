<?php
load_class('TMIS_TableDataGateway');
class Model_Trade_Order2Product extends TMIS_TableDataGateway {
	var $tableName = 'trade_order2product';
	var $primaryKey = 'id';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Product',
			'p_primaryKey' => 'proCode',
		),
		array(
			'tableClass' => 'Model_Trade_Order',
			'foreignKey' => 'orderId',
			'mappingName' => 'Order'
		)
	);
	
}
?>