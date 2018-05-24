<?php
load_class('TMIS_TableDataGateway');
class Model_Caigou_Shengou extends TMIS_TableDataGateway {
	var $tableName = 'caigou_shengou';
	var $primaryKey = 'id';
	var $primaryName = 'shengouCode';


	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Employ',
			'foreignKey' => 'employId',
			'mappingName' => 'Employ',
		),
		array(
			'tableClass' => 'Model_Jichu_Department',
			'foreignKey' => 'depId',
			'mappingName' => 'Dep',
		)
	);
	
	var $hasMany = array (
		array(
			'tableClass' => 'Model_Caigou_Shengou2Product',
			'foreignKey' => 'shengouId',
			'mappingName' => 'Products',
		)
	);

}


?>