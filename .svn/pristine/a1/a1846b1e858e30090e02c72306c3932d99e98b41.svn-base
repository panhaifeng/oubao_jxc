<?php
load_class('TMIS_TableDataGateway');
class Model_Jichu_Jiagonghu extends TMIS_TableDataGateway {
    var $tableName = 'jichu_supplier';
    var $primaryKey = 'id';
    var $primaryName = 'compName';
    var $sortByKey = ' letters';
    var $optgroup = true;

    /**
     * 获取kind的接口
     * Time：2014/08/25 09:50:47
     * @author li
     * @return array
    */
    function getKind(){
        $sql="select distinct kind from ".$this->qtableName." where 1";
        $res = $this->findBySql($sql);
        $arr=array();
        foreach ($res as $key => & $v) {
            $arr[]=array('text'=>$v['kind'],'value'=>$v['kind']);
        }

        return $arr;
    }
}
?>