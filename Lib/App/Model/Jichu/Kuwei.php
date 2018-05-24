<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Kuwei extends TMIS_TableDataGateway {
	var $tableName = 'jichu_kuwei';
	var $primaryKey = 'id';
	var $primaryName = 'kuweiName';
    var $sortByKey = "letters";
    // var $optgroup = true;
    //获取仓库名称
	function typeOptions(){
		$sql="select distinct ckName from jichu_kuwei where 1 order by id";
		$temp=$this->findBySql($sql);
		// dump($temp);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['ckName'],'value'=>$v['ckName']);
		}
		return $arr;
	}
}
?>