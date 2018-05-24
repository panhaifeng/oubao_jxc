<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Chart_Order extends TMIS_Controller {
	// /构造函数
	function __construct() {
		$this->_modelExample = &FLEA::getSingleton('Model_Trade_Order');
    }
	/**
	 * 花型大类分析
	 * Time：2015/10/22 17:26:08
	 * @author zhnagyan
	*/
	function actionProcodeChart(){
		$this->authCheck('14-4');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-01')
			,'dateTo'=>date('Y-m-d')
			,'flowerType'=>''
		));
		$sql="SELECT sum(y.money) as money,p.wuliaoKind,p.zuzhi
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
			where x.status !='dead' and y.kind != '大货'";

		if($arr['dateFrom'] != '') {
			$sql .=" and x.orderTime >= '{$arr['dateFrom']}' and x.orderTime <= '{$arr['dateTo']} 24:00:00'";
		}
		if($arr['flowerType'] != ''){
			$sql.=" group by p."."{$arr['flowerType']}";
		}	
		$rowset = $this->_modelExample->findBySql($sql);
		//重组数组
		if ($arr['flowerType']=='zuzhi') {
			foreach ($rowset as $k=> $vo){
				//当花型大类和数量为空时过滤掉
				if (isset($vo['zuzhi'])) {
	    			$name[] = $vo['zuzhi'];
	    			$money[$k]['name']= $vo['zuzhi'];
	    			$money[$k]['value']= $vo['money'];
				}
    		}
		}else{
			foreach ($rowset as $k=> $vo){
				//当花型大类和数量为空时过滤掉
				if (isset($vo['wuliaoKind'])) {
	    			$name[] = $vo['wuliaoKind'];
	    			$money[$k]['name']= $vo['wuliaoKind'];
	    			$money[$k]['value']= $vo['money'];
				}
    		}
		}
		sort($money);
		$title = '销售饼状图';
		$tpl = "Chart/ProcodeChart.tpl";
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('name', json_encode($name));
		$smarty->assign('money', json_encode($money));
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
    	$smarty->assign("_controllerName",$_GET['controller']);
		$smarty->display($tpl);
	}
}

?>