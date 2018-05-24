<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Supplier extends TMIS_TableDataGateway {
	var $tableName = 'jichu_supplier';
	var $primaryKey = 'id';
	var $primaryName = 'compName';
	var $sortByKey = 'convert(trim(compName) USING gbk)';

    
	var $hasMany = array(
		array(
			'tableClass' => 'Model_Jichu_SupplierTaitou',
			'foreignKey' => 'supplierId',
			'mappingName' => 'Supplier'
		)

	);
 //    //形成下拉框选项
	// function getSelect(){
	// 	$sql="select * from jichu_supplier where 1";
	// 	$row = $this->findBySql($sql);
	// 	//$row=$this->getTrader();
	// 	foreach($row as & $v){
	// 		$arr[]=array('value'=>$v[$this->primaryKey],'text'=>$v[$this->primaryName]);
	// 	}
	// 	return $arr;
	// }

}
?>