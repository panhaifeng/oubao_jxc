<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Employ extends TMIS_TableDataGateway {
	var $tableName = 'jichu_employ';
	var $primaryKey = 'id';
	var $primaryName = 'employName';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Department',
			'foreignKey' => 'depId',
			'mappingName' => 'Dep'
		)
	);
	function getTrader(){
	    $str="select x.* from jichu_employ x
		left join jichu_department y on y.id=x.depId
		where depName='销售部' or depName='业务部'
		and isFire=0";
		$row = $this->findBySql($str);
	    return $row;
	}
	//形成下拉框选项
	function getSelect(){
		$row=$this->getTrader();
		foreach($row as & $v){
			$arr[]=array('value'=>$v[$this->primaryKey],'text'=>$v[$this->primaryName]);
		}
		return $arr;
	}


	function getEmp(){
	    $str="select x.* from jichu_employ x
		left join jichu_department y on y.id=x.depId
		where 1
		and isFire=0";
		$row = $this->findBySql($str);
	    return $row;
	}

	//所有员工
	function getEmploy(){
		$row=$this->getEmp();

		foreach($row as & $v){
			$arr[]=array('value'=>$v[$this->primaryKey],'text'=>$v[$this->primaryName]);
		}
		return $arr;
	}
	
}
?>