<?php
load_class('TMIS_TableDataGateway');
class Model_Caiwu_Ar_Income extends TMIS_TableDataGateway {
    var $tableName = 'caiwu_ar_income';
    var $primaryKey = 'id';

    var $belongsTo = array (
        array(
            'tableClass' => 'Model_Jichu_Client',
            'foreignKey' => 'clientId',
            'mappingName' => 'Client',
        )
    );


    /**
     * 保存前处理
     * Time：2016/04/07 16:45:14
     * @author zhuli
     * @param 参数类型
     * @return 返回值类型
    */
    public function _beforeCreateDb(& $row){
    	//生成编号
        if($row['runningNumber']=='自动生成' || $row['runningNumber']==''){
            $row['runningNumber']=$this->getRunningNumber();
        }
    	return true;
    }

	/**
	 * 获得收款流水号
	 * Time：2015/10/29 17:36:37
	 * @author zhuli
	 * @return 流水号，bigint
	*/
	public function getRunningNumber()
    {
        $i = rand(0,999);
        do{
            if(999==$i){
                $i=0;
            }
            $i++;
            //保证订单号后面的位数为3位，不到3位用0填充
            $runningNumber = date('ymdHi').str_pad($i,3,'0',STR_PAD_LEFT);
            $row = $this->findCount("runningNumber = '{$runningNumber}'");
        }while($row);
        return $runningNumber;
    }
}
?>