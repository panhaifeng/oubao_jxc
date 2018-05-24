<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Product extends TMIS_TableDataGateway {
	var $tableName = 'jichu_product';
	var $primaryKey = 'id';
	var $primaryName = 'proCode';

	//获取物料大类
	function typeWuliaos(){
		$sql="select distinct wuliaoKind from jichu_product where 1 order by wuliaoKind";
		$temp=$this->findBySql($sql);
		// dump($temp);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['wuliaoKind'],'value'=>$v['wuliaoKind']);
		}
		return $arr;
	}
	//获取整理方式
	function typeZhenglis(){
		$sql="select distinct zhengli from jichu_product where 1 order by zhengli";
		$temp=$this->findBySql($sql);
		// dump($temp);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['zhengli'],'value'=>$v['zhengli']);
		}
		return $arr;
	}
	//获取组织大类
	function typeZuzhis(){
		$sql="select distinct zuzhi from jichu_product where 1 order by zuzhi";
		$temp=$this->findBySql($sql);
		// dump($temp);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['zuzhi'],'value'=>$v['zuzhi']);
		}
		return $arr;
	}

}
?>