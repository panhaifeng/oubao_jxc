<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Wuliao extends TMIS_TableDataGateway {
	var $tableName = 'jichu_wuliao';
	var $primaryKey = 'id';
	var $primaryName = 'wuCode';
	//获取物料大类
	function typeWuliaos(){
		$sql="select distinct wuliaoKind from jichu_wuliao where 1 order by id";
		$temp=$this->findBySql($sql);
		// dump($temp);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['wuliaoKind'],'value'=>$v['wuliaoKind']);
		}
		return $arr;
	}
}
?>