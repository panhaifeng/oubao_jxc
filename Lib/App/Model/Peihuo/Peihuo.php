<?php
load_class('TMIS_TableDataGateway');
class Model_Peihuo_Peihuo extends TMIS_TableDataGateway {
    var $tableName = 'ph_peihuo';
    var $primaryKey = 'id';

    var $hasMany = array (
        array(
            'tableClass' => 'Model_Peihuo_Peihuo2Madan',
            'foreignKey' => 'phId',
            'mappingName' => 'Peihuo',
        )
    );

    /**
     * 获取最新的单号
     * Time：2015/09/16 16:11:22
     * @author li
     * @return string
    */
    function getNewCode(){
        $head = 'PHD';
        $sql = "select peihuoCode from ph_peihuo 
            where peihuoCode like '{$head}_________' 
            order by peihuoCode desc 
            limit 0,1";

        $_r = $this->findBySql($sql);
        $row = $_r[0];

        $init = $head .date('ymd').'001';
        if(empty($row['peihuoCode'])) return $init;
        if($init>$row['peihuoCode']) return $init;

        //自增1
        $max = substr($row['peihuoCode'],-3);
        $pre = substr($row['peihuoCode'],0,-3);
        return $pre .substr(1001+$max,1);
    }
}


?>