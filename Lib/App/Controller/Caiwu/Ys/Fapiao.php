
<?php
FLEA::loadClass('TMIS_Controller');
////////////////////////////////////////控制器名称
class Controller_Caiwu_Ys_Fapiao extends TMIS_Controller {
	var $_modelExample;

	function __construct() {
		$this->_modelExample = & FLEA::getSingleton('Model_Caiwu_Ar_Fapiao');

		//搭建过账界面
        $this->fldMain = array(
        	'creater' => array('type' => 'text', 'value' => $_SESSION['REALNAME'],'title'=>'制单人','readonly'=>true),
        	'fapiaoCode' => array('type' => 'text', 'value' => '','title'=>'发票编码'),
			'fapiaoDate' => array('title' => '发票日期', "type" => "calendar", 'value' => date('Y-m-d')),
			'clientId' => array(
				'title' => '客户',
				'type' => 'Popup',
				'name' => 'clientId',
				'url'=>url('Jichu_Client','Popup'),
				'textFld'=>'compName',
				'hiddenFld'=>'id',
				// 'inTable'=>true,
			),
			'tax_compName' => array('title' => '发票抬头', 'type' => 'text', 'value' => ''),
			'money' => array('title' => '发票金额', 'type' => 'text', 'value' => ''),
			'bizhong' => array('title' => '币种', 'type' => 'select', 'value' => '', 'optionType'=>'币种'),
			'huilv' => array('title' => '汇率', 'type' => 'text', 'value' => '1'),
			'memo' => array('title' => '备注', 'type' => 'textarea', 'value' => ''),
			'id' => array('type' => 'hidden', 'value' => ''),
		);

		// 表单元素的验证规则定义
		$this->rules = array(
			'fapiaoCode' => 'required repeat',
			'fapiaoDate' => 'required',
			'clientId' => 'required',
			'money' => 'required number'
		);
	}

	function actionRight() {
		$this->authCheck('4-1-6');
		FLEA::loadclass('TMIS_Pager');
		$arr=TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'))),
			'dateTo'=>date('Y-m-d'),
			'clientId'=>'',
			'key'=>'',
			'no_edit'=>'',
		));
		$sql="select x.*,a.compName from caiwu_ar_fapiao x
			left join jichu_client a on a.member_id=x.clientId
			where 1";
		if($arr['clientId']!='')$sql.=" and x.clientId='{$arr['clientId']}'";
		if($arr['dateFrom']!=''){
			$sql.=" and x.fapiaoDate >= '{$arr['dateFrom']}'";
		}
		if($arr['dateTo']!=''){
			$sql.=" and x.fapiaoDate <='{$arr['dateTo']}'";
		}
		
		if($arr['key']!=''){
			$sql.=" and x.fapiaoCode like '%{$arr['key']}%' or x.memo like '%{$arr['key']}%'";
		}
		$sql.=" order by x.id desc";
		$page=& new TMIS_Pager($sql);
		$rowset=$page->findAll();
		if(count($rowset)>0)foreach($rowset as & $v) {
			$v['_edit'].=$this->getEditHtml($v['id']).'&nbsp;&nbsp;'.$this->getRemoveHtml($v['id']);

			//折合人民币
			$v['moneyRmb']=round($v['money']*$v['huilv'],2);
			//显示币种
			$this->getBizhong($v['bizhong']);
		}

		$rowset[] = $this->getHeji($rowset, array('money','moneyRmb'), $_GET['no_edit']==1?'fapiaoCode':'_edit');
		
		$arr_field_info=array(
			'_edit'=>array('text'=>'操作','width'=>50),
			'fapiaoCode'=>array('text'=>'发票编码','width'=>100),
			'fapiaoDate'=>'收票日期',
			'tax_compName'=>array('text'=>'发票抬头','width'=>200),
			'compName'=>array('text'=>'客户','width'=>200),
			'money'=>'金额',
			'moneyRmb'=>'金额(RMB)',
			'bizhong'=>'币种',
			'huilv'=>'汇率',
			'memo'=>array('text'=>'备注','width'=>300),
		);
		$smarty=& $this->_getView();
		$smarty->assign('arr_condition',$arr);
		$smarty->assign('add_display','none');
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('page_info',$page->getNavBar($this->_url($_GET['action'],$arr)).$note);
		$smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
            if($_GET['export']==1){
                $this->_exportList(array('title'=>$title),$smarty);
            }
		$smarty->display('TblList.tpl');
	}
	
	
	function actionSave() {
		//所有开票金额
        $sql="SELECT sum(money*huilv) as faPiaoMoneyAll,clientId FROM `caiwu_ar_fapiao` where 1 and clientId='{$_POST['clientId']}'";
        $kaipiaoAll=$this->_modelExample->findBySql($sql);

        //开票额度 按应收
        $str="select sum(money*huilv) as kpMoney,clientId from caiwu_ar_guozhang where 1 and clientId='{$_POST['clientId']}'";
        $rowset = $this->_modelExample->findBySql($str);

        $kp_limit=$rowset[0]['kpMoney']-$kaipiaoAll[0]['faPiaoMoneyAll'];
        // dump($kp_limit);die;
        if($_POST['id']>0){
        	$sql2="SELECT sum(money*huilv) as smoney,clientId FROM `caiwu_ar_fapiao` where 1 and clientId='{$_POST['clientId']}' and id not in({$_POST['id']})";
       		$kp_else=$this->_modelExample->findBySql($sql2);
        	if($_POST['money']+$kp_else[0]['smoney']-$rowset[0]['kpMoney']>0){
        		js_alert("开票金额已经超出开票额度，请重新输入开票金额！",null,$this->_url('add'));
        	}
        }else{
        	if(intval($_POST['money']-$kp_limit)>0 ){
        		js_alert("开票金额已经超出开票额度，请重新输入开票金额！",null,$this->_url('add'));
       		}
        }
        

		$arr=array();
		foreach($this->fldMain as $key=>&$v) {
			$arr[$key] = $_POST[$key];
		}

		$arr['huilv']=empty($arr['huilv'])?1:$arr['huilv'];
		$id=$this->_modelExample->save($arr);
		js_alert(null,'window.parent.showMsg("操作成功");',$this->_url($_POST['fromAction']==''?'Add':$_POST['fromAction']));

	}

	/**
	 * 按照订单登记发票
	 * Time：2015/10/28 17:32:52
	 * @author li
	*/
	function actionListOrder(){
		$this->authCheck('4-1-11');
		FLEA::loadClass('TMIS_Pager'); 
		$arr = TMIS_Pager::getParamArray(array(
			'orderCode'=>'',
			'clientId'=>'',
			'isOver'=>'N',
			'key'=>''
		));

		$sql = "SELECT x.*,if(c.compName<>'',c.compName,c.compCode) as compName
			from trade_order x
			left join jichu_client c on c.member_id=x.clientId
			left join (select orderId,kind from trade_order2product group by orderId) s on x.id=s.orderId
			where x.status<>'dead'  and s.kind<>'大货'";
		if($arr['orderCode']!=''){
			$sql.=" and x.orderCode like '%{$arr['orderCode']}%'";
		}
		if($arr['clientId']!=''){
			$sql.=" and x.clientId = '{$arr['clientId']}'";
		}
		if($arr['isOver']=='N'){
			$sql.=" and x.is_tax_over=0";
		}elseif($arr['isOver']=='Y'){
			$sql.=" and x.is_tax_over=1";
		}
		if($arr['key']!=''){
			$sql.=" and x.tax_company like '%{$arr['key']}%'";
		}
		$sql.=" order by x.orderTime ";
		// echo $sql;exit;
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();

		foreach($rowset as &$v) {
		    $v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];
		    $v['cost_freight']=round($v['cost_freight'],3);
			//支付方式
			$v['payment']=$this->getPayment($v['payment']);

			$v['status'] = $this->getStatusOrder($v['status'] ,true);


			//edit
			$v['_edit']="<input type='checkbox' name='check[]' value='{$v['id']}' clientId='{$v['clientId']}' taitou='{$v['tax_company']}'>";

			//是否开票
			$v['is_tax'] = $v['is_tax']=='false' ? '不开' : '开票';
			//开票类型
			$this->getKaipiaoType($v['tax_type']);

			$v['is_tax_over'] = $v['is_tax_over']==0?'否':'是';

			//付款状态
			$v['pay_status'] = $this->getPayStatus($v['pay_status'], true);

			//发货状态
			$v['ship_status'] = $this->getShipStatus($v['ship_status'], true);

			//税率
			// $v['tax'] = round(($v['cost_tax']/($v['money']-$v['cost_freight']-$v['cost_tax'])*100),2).' %';
			$v['tax'] = round($v['cost_tax'],2);
		}

		
		$smarty = &$this->_getView(); 
		$arrFieldInfo = array(
			"_edit" => "<input type='checkbox' id='checkall' title='全选/反选'>",
			'orderCode'=>array('width'=>'130','text'=>'订单号'),
			'orderTime'=>array('width'=>'130','text'=>'下单时间'),
			'status'=>array('width'=>'70','text'=>'订单状态'),
			'is_tax_over'=>array('width'=>'70','text'=>'开票完成'),
			"compName" => "客户",
			'payment'=>'支付方式',
			'ship_status'=>'发货状态',
			'pay_status'=>'付款状态',
			'cost_freight'=>array('width'=>'70','text'=>'配送费用'),
			'currency'=>array('width'=>'70','text'=>'支付货币'),
			'tax_company' =>array('width'=>'100','text'=>'发票抬头'),
			// 'tax' =>array('width'=>'70','text'=>'订单税率'),
			//订单税率改为订单税金，by张艳 2015-11-10 根据蒋会蒋会提示
			'tax' =>array('width'=>'70','text'=>'订单税金'),
			// 'is_tax'=>array('width'=>'70','text'=>'是否开票'),
			// 'tax_type'=>array('width'=>'70','text'=>'开票类型'),
			'tax_content'=>array('width'=>'70','text'=>'发票内容'),
			'money' =>array('width'=>'70','text'=>'总金额'),
			'cur_rate'=>array('width'=>'70','text'=>'货币汇率'),
			// 'fpMoney'=>array('width'=>'100','text'=>'已开票金额'),
			// 'wpMoney'=>array('width'=>'100','text'=>'未开票金额'),
		); 
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$other_url="<button type='button' class='btn btn-info btn-sm' id='kaipiao'>开始开票</button>";
		$other_url.="&nbsp;<button type='button' class='btn btn-success btn-sm' id='overKp' over='1'>标记完成</button>";
		$other_url.="&nbsp;<button type='button' class='btn btn-danger btn-sm' id='canneloverKp' over='0'>取消完成</button>";
        $smarty->assign('other_url', $other_url);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('sonTpl', "Caiwu/Ys/FapiaoEdit.tpl");
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	}

	function actionAdd() {
		$this->authCheck('4-1-5');
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '发票信息编辑');
		$smarty->assign('sonTpl', 'Trade/OrderSontpl.tpl');
		$smarty->display('Main/A1.tpl');
	}

	function actionEdit() {
		$row = $this->_modelExample->find(array('id' => $_GET['id']));
		$this->fldMain = $this->getValueFromRow($this->fldMain, $row); 
		$this->fldMain['clientId']['text']=$row['Client']['compName'];
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '发票信息编辑');
		$smarty->assign('aRow', $row);
		$smarty->assign('sonTpl', 'Trade/OrderSontpl.tpl');
		$smarty->display('Main/A1.tpl');
	}
	
	/**
	 * 开票完成
	 * Time：2015/10/28 20:58:13
	 * @author li
	*/
	function actionSetOrderKpOver(){
		$this->authCheck('4-1-11');
		$_orderId = $_GET['orderId'];
		$is_tax_over = (int)$_GET['over'];
		(!$_orderId) && $_orderId = "''";

		$sql="update trade_order set is_tax_over = '{$is_tax_over}' where id in ({$_orderId})";
		$this->_modelExample->execute($sql);

		echo json_encode(array('success'=>true));
	}

	/**
	 * 按照订单开票
	 * Time：2015/10/28 20:58:13
	 * @author li
	*/
	function actionAddOrder(){
		$this->authCheck('4-1-11');

		$_orderId = $_GET['orderIds'];
		(!$_orderId) && $_orderId = "''";
		//有效性验证
		// 客户必须一致，开票抬头不一致给予提醒，但是不限制抬头不一致的时候不能开票
		$sql="SELECT clientId,tax_company,cur_rate,currency from trade_order where id in({$_orderId})";
		$tmp_order = $this->_modelExample->findBySql($sql);
		$_clientId = array_col_values($tmp_order,'clientId');
		$tax_company = array_col_values($tmp_order,'tax_company');

		$_clientId = array_filter(array_unique($_clientId));
		$tax_company = array_filter(array_unique($tax_company));
		
		//判断是否一致
		if(count($_clientId)>1){
			js_alert('客户必须一致',null,$this->_url('ListOrder'));exit;
		}

		//查找订单金额
		$sql="SELECT sum(money) as money from trade_order where id in({$_orderId})";
		$tmp_money = $this->_modelExample->findBySql($sql);

		//查找客户信息
		$sql="SELECT compName from jichu_client where member_id='{$_clientId[0]}'";
		$tmp_client = $this->_modelExample->findBySql($sql);
		// dump($sql);exit;

		$_tmp_arr = array(
			'clientId'=>$_clientId[0],
			'orderId'=>$_orderId,
			'fapiaoDate'=>date('Y-m-d'),
			'creater'=>$_SESSION['REALNAME'].'',
			'tax_compName'=>$tax_company[0],
			'huilv'=>$tmp_order[0]['cur_rate'],
			'bizhong'=>$tmp_order[0]['currency'],
			'money'=>round($tmp_money[0]['money'],3),
		);

		$this->fldMain = $this->getValueFromRow($this->fldMain, $_tmp_arr); 
		$this->fldMain['clientId']['text']=$tmp_client[0]['compName'];

		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('title', '发票信息编辑');
		$smarty->assign('sonTpl', 'Trade/OrderSontpl.tpl');
		$smarty->display('Main/A1.tpl');
	}
}
?>