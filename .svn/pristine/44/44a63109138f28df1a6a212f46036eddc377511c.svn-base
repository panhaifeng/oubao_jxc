<?php
load_class('TMIS_TableDataGateway');
class Model_Trade_Order extends TMIS_TableDataGateway {
	var $tableName = 'trade_order';
	var $primaryKey = 'id';
	var $primaryName = 'orderCode';
	var $hasMany = array (
		array(
			'tableClass' => 'Model_Trade_Order2Product',
			'foreignKey' => 'orderId',
			'mappingName' => 'Products',
		)
	);
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Client',
			'foreignKey' => 'clientId',
			'mappingName' => 'Client',
			'p_primaryKey' => 'member_id',
		)
	);
	//获取支付方式
	function typePayments(){
		$sql="select distinct payment from trade_order where 1 order by payment";
		$temp=$this->findBySql($sql);
		// dump($temp);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['payment'],'value'=>$v['payment']);
		}
		return $arr;
	}	
	//获取币种
	function typeCurrencys(){
		$sql="select distinct currency from trade_order where 1 order by currency";
		$temp=$this->findBySql($sql);
		// dump($temp);
		$arr=array();
		foreach($temp as & $v){
			$arr[]=array('text'=>$v['currency'],'value'=>$v['currency']);
		}
		return $arr;
	}

	/**
	 * 获得订单号
	 * Time：2015/10/29 17:36:37
	 * @author li
	 * @return 订单号，bigint
	*/
	public function getOrderCode()
    {
        $i = rand(0,99999);
        do{
            if(99999==$i){
                $i=0;
            }
            $i++;
            //保证订单号后面的位数为5位，不到5位用0填充
            $order_id = date('ymdHi').str_pad($i,5,'0',STR_PAD_LEFT);
            $row = $this->findCount("orderCode = '{$order_id}'");
            // dump($row);exit;
        }while($row);
        return $order_id;
    }
}


?>