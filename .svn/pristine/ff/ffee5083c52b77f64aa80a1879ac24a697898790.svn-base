<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Trade_Order extends TMIS_Controller {
	// /构造函数
	function __construct() {
		$this->_modelExample = &FLEA::getSingleton('Model_Trade_Order');
		$this->_subModel = &FLEA::getSingleton('Model_Trade_Order2Product');
		// 定义模板中的主表字段
		$this->fldMain = array(
			'orderCode' => array('title' => '订单号', "type" => "text", 'readonly' => true,'value' =>'自动生成'),
			'clientId' => array(
				'title' => '客户',
				'type' => 'popup',
				'name' => 'clientId',
				'url'=>url('Jichu_Client','Popup'),
				'textFld'=>'compName',
				'hiddenFld'=>'id',
				// 'inTable'=>true,
			),
			'employId'=>array('title'=>'业务员','type'=>'select','model' => 'Model_Jichu_Employ','isSearch'=>'true','condition'=>'isFire=0'),
			'shipping' => array('title'=>'配送方式','type' => 'select', 'options' =>''),
			'payment'=> array('title' => '支付方式', "type" => "select",'value' => '','options'=>$this->_modelExample->typePayments()),
			'ship_name'=> array('title' => '收货人', "type" => "text",'value'=>''),
			'ship_addr'=> array('title' => '收货地址', "type" => "text",'value'=>''),
			'cost_freight'=> array('title' => '配送费用', "type" => "text",'value'=>''),
			'orderTime'=> array('title' => '下单日期', "type" => "calendarTime",'value'=>date('Y-m-d H:i:s')),
			'currency'=> array('title' => '支付货币', "type" => "select",'value' => '','optionType'=>'币种'),
			'area' => array('title' => '地区', "type" => "text", 'value' =>'','name'=>'area'),
			'money' => array('title' => '总金额', "type" => "text", 'value' =>'','name'=>'sumMoney','readonly'=>true),
			// 'is_tax'=> array('title' => '是否开票', "value"=>'false',"type" => "select",'options'=>array(
			// 		array('text'=>'是','value'=>'true'),
			// 		array('text'=>'否','value'=>'false'),
			// 	)),
			'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memoView'),
			// 下面为隐藏字段
			'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
			'id' => array('type' => 'hidden', 'value' =>'','name'=>'ordid'),
			'is_tax' => array('type' => 'hidden', 'value' =>'true','name'=>'is_tax'),
			'tax_type' => array('type' => 'hidden', 'value' =>'true','name'=>'company'),
		);
		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		$this->headSon = array(
			'_edit' => array('type' => 'BtBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			'productId' => array(
				'title' => '花型六位号',
				'type' => 'BtPopup',
				'name' => 'productId[]',
				'url'=>url('Jichu_Product','Popup'),
				'textFld'=>'proCode',
				'hiddenFld'=>'id',
				'inTable'=>true,
				'dialogWidth'=>900
			),
			'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
			'chengfen'=>array('type'=>'BtText',"title"=>'成分','name'=>'chengfen[]','readonly'=>true),
			'shazhi' => array('type' => 'BtText', "title" => '纱支', 'name' => 'shazhi[]','readonly'=>true),
			'jwmi' => array('type' => 'BtText', "title" => '经纬密', 'name' => 'jwmi[]','readonly'=>true),
			'menfu' => array('type' => 'BtText', "title" => '门幅', 'name' => 'menfu[]','readonly'=>true),
			// 'color'=>array('type'=>'BtText',"title"=>'颜色','name'=>'color[]','readonly'=>true),
			'kind' => array('type' => 'BtText', "title" => '类型', 'name' => 'kind[]','value'=>'大货','readonly'=>true),
			// 'kind'=> array('title' => '类型', "type" => "BtSelect",'name'=>'kind[]','options'=>array(
			// 		array('text'=>'现货','value'=>'现货'),
			// 		array('text'=>'样品','value'=>'样品'),
			// 		array('text'=>'大货','value'=>'大货'),
			// 	)),
			'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
        			array('text'=>'M','value'=>'M'),
        			array('text'=>'Y','value'=>'Y')
        		)),
			'cnt' => array('type' => 'BtText', "title" => '要货数', 'name' => 'cnt[]'),
			'danjia' => array('type' => 'BtText', "title" => '单价', 'name' => 'danjia[]'),
			'money' => array('type' => 'BtText', "title" => '金额', 'name' => 'money[]'),
			'memo' => array('type' => 'BtText', "title" => '备注', 'name' => 'memo[]'),
			// ***************如何处理hidden?
			'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
		);
		// 表单元素的验证规则定义
		$this->rules = array(
			'clientId' => 'required',
			'currency' => 'required',
			'employId' => 'required',
		);
	}

	function actionRight(){
		//权限判断
		$this->authCheck('2-3');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo'=>date('Y-m-d'),
			'orderCode'=>'',
			'clientId'=>'',
			'proCode' => '',
			'order_kind' => '',
			'employId' => '',
			'is_delivery' => '',
		));
		$sql="SELECT x.*,p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,
				p.menfu,p.color,y.id as sId,y.kind,y.unit,y.cnt,y.danjia,y.shenhe,
				y.peihuoId,c.compName,c.traderId as cId,m.employName
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
				left join jichu_client c on c.member_id=x.clientId
				left join jichu_employ m on m.id=x.traderId
			where 1 ";
		// dump($arr);exit;
		if($arr['clientId']!='') $sql .=" and x.clientId='{$arr['clientId']}' ";
		if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
		if($arr['orderCode']!='') $sql .=" and x.orderCode like '%{$arr['orderCode']}%' ";
		if($arr['order_kind']!='') $sql .=" and y.kind = '{$arr['order_kind']}' ";
		if($arr['employId']!='') $sql .=" and x.traderId = '{$arr['employId']}'  ";
		if($arr['is_delivery']!='') $sql .=" and x.is_delivery = '{$arr['is_delivery']}'  ";
		if($arr['dateFrom'] !=''){
			$sql.=" and x.orderTime>='{$arr['dateFrom']} 00:00:00' and x.orderTime<='{$arr['dateTo']}  23:59:59'";
		}

		$sql.=" order by x.orderTime desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		// dump($rowset);exit;

		if (count($rowset) > 0) foreach($rowset as &$v) {
		    $v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];
		    $v['cost_freight']=round($v['cost_freight'],3);
		    $v['cnt']=round($v['cnt'],2);
		    //如果发货状态为取消发货则绿色显示
		    if($v['is_delivery']=='N'){
					$v['_bgColor'] = 'lightgreen';

		    }
			//审核后的不能修改删除
			$statesh=$this->stateShenhe('销售合同',$v['id']);
			$over=$v['is_setover']==0?1:0;

			if($statesh=='未审核'){
				if($v['kind']=='大货'){
					$v['_edit']=$this->getEditHtml($v['id']).' '.$this->getRemoveHtml($v['id']);

				}else{
					$v['_edit'].="<span class='glyphicon glyphicon-pencil' ext:qtip='电商下单，禁止操作'></span>";
					$v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='电商下单，禁止操作'></span>";
				}
			}else {
				$v['_edit'].="<span class='glyphicon glyphicon-pencil' ext:qtip='已审核不能修改'></span>";
				$v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已审核不能删除'></span>";
			}

			if($v['kind']=='大货'){
				if($v['is_setover']!='1'){
					$v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('SetOver',array(
						id=>$v['id'],
						'over'=>$over,
						'fromAction'=>$_GET['action']
					))."'><span class='glyphicon glyphicon-ok' ext:qtip='完成'></span></a>";
				}else{
					$v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='合同完成，禁止操作'></span>";
					$v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='合同完成，禁止操作'></span>";
					$v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('SetOver',array(
						id=>$v['id'],
						'over'=>$over,
						'fromAction'=>$_GET['action']
					))."'><span class='glyphicon glyphicon-share-alt' ext:qtip='取消完成'></span></a>";
				}
			}

			// if($statesh=='拒绝'){
			// 	$statesh="<p class='bg-danger'>{$statesh}</p>";
			// }elseif($statesh=='已通过'){
			// 	$statesh="<p class='bg-success'>{$statesh}</p>";
			// }

			//显示审核信息
			$_detial=$this->showShenhe('销售合同',$v['id']);
			if($statesh=='拒绝'){
				$v['state'] = "<a href='javascript:;' class='bg-danger' ext:qtip=\"{$_detial}\">{$statesh}</a>";
			}elseif($statesh=='已通过'){
				$v['state'] = "<a href='javascript:;' class='bg-success' ext:qtip=\"{$_detial}\">{$statesh}</a>";
			}else{
				$v['state'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$statesh}</a>";
			}

			$v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('Print',array(id=>$v['id']))."' target='_blank'><span class='glyphicon glyphicon-print' title='打印'></span></a>";
			$v['is_tax']=$v['is_tax']=='true'?'是':'否';

			//支付方式
			$v['payment']=$this->getPayment($v['payment']);

			//是否发货
			if($v['is_delivery']=='N'){
				$v['is_delivery']="<p class='bg-danger' title='{$v['isdelivery_desc']}'>{$v['is_delivery']}</p>";
			}

			$v['status'] = $this->getStatusOrder($v['status'] ,true);
			//显示币种
			$this->getBizhong($v['currency']);
			//税率
			// $v['tax'] = round(($v['cost_tax']/($v['money']-$v['cost_freight']-$v['cost_tax'])*100),2).' %';
			$v['tax'] = round($v['cost_tax'],2);
			//数量
			$v['cnt'] = round($v['cnt'],2);
			//整单折扣
			$v['pmt_money'] = round($v['pmt_money'],2);
			//成本价
			if($v['kind']=='现货'){
				$v['chengben'] = "<a href='".$this->_url('chengben',array(
					'productId'=>$v['proCode'],
					'peihuoId'=>$v['peihuoId']
				))."' title='成本价' target='_blank'>成本价详细</a>";
			}

  		}
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			"_edit" => '操作',
			'orderCode'=>array('width'=>'130','text'=>'订单号'),
			'orderTime'=>array('width'=>'130','text'=>'下单时间'),
			'state'=>array('width'=>'70','text'=>'审核状态'),
			'status'=>array('width'=>'70','text'=>'订单状态'),
			"compName" => "客户",
			"employName"=>"业务员",
			'payment'=>'支付方式',
			'is_delivery'=>array('width'=>'70','text'=>'是否发货'),
			'shipping'=>'配送方式',
			'ship_name'=>array('width'=>'70','text'=>'收货人'),
			'ship_addr'=>'收货地址',
			'pmt_order'=>array('width'=>'70','text'=>'订单优惠'),
			'cost_freight'=>array('width'=>'70','text'=>'配送费用'),
			'currency'=>array('width'=>'70','text'=>'支付货币'),
			'money' =>array('width'=>'70','text'=>'总金额'),
			// 'is_tax'=>array('width'=>'70','text'=>'是否开票'),
			'proCode'=>array('width'=>'70','text'=>'花型六位号'),
			'proName'=>array('width'=>'70','text'=>'产品名称'),
			'kind'=>array('width'=>'70','text'=>'类型'),
			// "color" => "颜色",
			'cnt'=>array('width'=>'70','text'=>'数量'),
			'unit'=>array('width'=>'70','text'=>'单位'),
			'danjia'=>array('width'=>'70','text'=>'单价'),
			// 'tax'=>array('width'=>'70','text'=>'税率'),
			//订单税率改为订单税金，by张艳 2015-11-10 根据蒋会蒋会提示
			'tax' =>array('width'=>'70','text'=>'税金'),
			"memo" => '备注',
			"chengben" => '成本价',
		);
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr))."&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>绿色表示该订单已经取消发货</font>"."&nbsp;<span class='text-danger'> </span>");
		$smarty->display('TblList.tpl');
	}
	/**
	 * ps ：修改
	 * Time：2015-08-21 14:07:34
	 * @author jiang
	*/
	function actionEdit() {
		$temp=$this->getZhifu();
		$this->fldMain['payment']['options']=$temp;
		$_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');
		//同步EC数据到进销存
        $_wuliu = $_modelPlan->getWuliuFromEc();
		//配送方式
        $_dlytype = array();
        foreach ($_wuliu['data']['dlytype'] as $key => & $v) {
            $_dlytype[] = array('text'=>$v['dt_name'],'value'=>$v['dt_name']);
        }
        //给下拉框添加选项
        $this->fldMain['shipping']['options'] = $_dlytype;
		$arr = $this->_modelExample->find(array('id' => $_GET['id']));
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value'];
		}
		$this->fldMain['clientId']['text']=$arr['Client']['compName'];
		$this->fldMain['employId']['value']=$arr['traderId'];
		$this->fldMain['cost_freight']['value']=round($arr['cost_freight'],3);

		foreach($arr['Products'] as &$v) {
			$_temp = $this->_subModel->find(array('id' => $v['id']));
			// dump($_temp);exit;
			$v['proCode'] = $_temp['Product']['proCode'];
			$v['proName'] = $_temp['Product']['proName'];
			$v['chengfen'] = $_temp['Product']['chengfen'];
			$v['shazhi'] = $_temp['Product']['shazhi'];
			$v['jwmi'] = $_temp['Product']['jingmi'].'*'.$_temp['Product']['weimi'];
			$v['menfu'] = $_temp['Product']['menfu'];
		}
		foreach($arr['Products'] as &$v) {
			$temp = array();
			foreach($this->headSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $v[$kk]);
			}
			$temp['productId']['text'] = $v['proCode'];
			$rowsSon[] = $temp;
		}
		// dump($rowsSon);exit;
		//补齐5行
		$cnt = count($rowsSon);
		for($i=5;$i>$cnt;$i--) {
			$rowsSon[] = array();
		}
		$smarty = &$this->_getView();
		$smarty->assign('areaMain',array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain));
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', 'Trade/OrderSontpl.tpl');
		$smarty->display('Main2Son/T1.tpl');
	}

	function actionAdd() {
		$this->authCheck('2-2');
		$temp=$this->getZhifu();
		$this->fldMain['payment']['options']=$temp;
		$_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');
		//同步EC数据到进销存
		// dump($this->fldMain);exit;
        $_wuliu = $_modelPlan->getWuliuFromEc();
		//配送方式
        $_dlytype = array();
        foreach ($_wuliu['data']['dlytype'] as $key => & $v) {
            $_dlytype[] = array('text'=>$v['dt_name'],'value'=>$v['dt_name']);
        }
        //给下拉框添加选项
        $this->fldMain['shipping']['options'] = $_dlytype;
		while (count($rowsSon) < 5) {
			$rowsSon[]=array(
				'kind'=>array('value'=>'大货')
			);
		}
		$smarty = &$this->_getView();
		$areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);
		$smarty->assign('areaMain', $areaMain);
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('fromAction', $_GET['fromAction']==''?'add':$_GET['fromAction']);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', 'Trade/OrderSontpl.tpl');
		$smarty->display('Main2Son/T1.tpl');
	}

	/**
	 * ps ：保存
	 * Time：2015-08-21 14:07:34
	 * @author shen
	*/
	function actionSave(){
		//有效性验证,没有明细信息禁止保存
		//开始保存
		$pros = array();
		$_POST['traderId']=$_POST['employId'];
		foreach($_POST['productId'] as $key=>&$v) {
			if($v=='' || $_POST['cnt'][$key]=='') continue;
			$temp = array();
			foreach($this->headSon as $k=>&$vv) {
				$temp[$k] = $_POST[$k][$key];
			}
			//将单位转换为米保存
			$temp['unit']=='' && $temp['unit']='M';//默认不能为空
			if($temp['unit']=='Y') $temp['cntM']=0.9144*$temp['cnt'];
			else $temp['cntM']=$temp['cnt'];
			$pros[]=$temp;
		}
		if(count($pros)==0) {
			js_alert('请选择有效的产品明细信息!','window.history.go(-1)');
			exit;
		}
		$row = array();
		foreach($this->fldMain as $k=>&$vv) {
			$name = $vv['name']?$vv['name']:$k;
			$row[$k] = $_POST[$name];
		}
		//生成编号
		if($row['orderCode']=='自动生成' || $row['orderCode']==''){
			$row['orderCode']=$this->_modelExample->getOrderCode();
		}
		$row['Products'] = $pros;
		$row['traderId'] = $row['employId'];
		if(!$this->_modelExample->save($row)) {
			js_alert('保存失败','window.history.go(-1)');
			exit;
		}

		//跳转
		js_alert(null,'window.parent.showMsg("保存成功!")',url($_POST['fromController'],$_POST['fromAction'],array()));
		exit;
	}
	/**
	 * ps ：订单合同打印
	 * Time：2015/08/25 14:14:04
	 * @author jiang
	*/
	function actionPrint(){
		$sql="select x.*,p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,
				p.menfu,p.color,y.kind,y.unit,y.cnt,y.danjia,y.money as ymoney,c.compName,c.mobile
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
				left join jichu_client c on c.member_id=x.clientId
				where 1 and x.id='{$_GET['id']}' and y.kind<>'未知'";
		$row=$this->_modelExample->findBySql($sql);
		foreach ($row as  &$value) {
			$value['cnt']=round($value['cnt'],3);
			$value['danjia']=round($value['danjia'],3);
			$value['ymoney']=round($value['ymoney'],3);
			$value['cost_freight']=round($value['cost_freight'],3);
			$value['money']=round($value['money'],3);
			//支付方式
			$value['payment']=$this->getPayment($value['payment']);
			//显示币种
			$this->getBizhong($value['currency']);
		}
		$hj=$this->getHeji($row,array('cnt'));
		$smarty = &$this->_getView();
		$smarty->assign('row',$row);
		$smarty->assign('hj',$hj);
		$smarty->display('Trade/HetongPrint.tpl');
	}

	/**
	 * 审核订单明细，生成出库单
	 * Time：2015/09/15 19:19:00
	 * @author li
	*/
	function actionShenhe(){
		$this->authCheck('2-4');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' =>date('Y-m-d '),
			'orderCode'=>'',
			'compCode'=>'',
			'clientId'=>'',
			'isShenhe'=>'',
			'dateShenhe'=>'',
		));
		$sql="SELECT
			x.*,
			y.id as proId,
			y.money as m_pro,
			y.item_type,
			sum(y.cnt)as cnt,
			y.unit,
			y.kind,
			y.peihuoId,
			y.danjia,
			if(c.compName='',c.compCode,c.compName) as compName,
			c.compCode
			from trade_order x
			left join trade_order2product y on x.id=y.orderId
			left join jichu_client c on c.member_id=x.clientId
			where 1 and x.is_delivery='Y' ";
		if($arr['dateFrom'] != '') {
            $sql .=" and x.orderTime >= '{$arr['dateFrom']} 00:00:00' and x.orderTime <= '{$arr['dateTo']} 23:59:59'";
        }
        if($arr['compCode']!='') $sql .=" and c.compCode like '%{$arr['compCode']}%' ";
		if($arr['clientId']!='') $sql .=" and x.clientId='{$arr['clientId']}' ";
		if($arr['orderCode']!='') $sql .=" and x.orderCode like '%{$arr['orderCode']}%' ";
		if($arr['isShenhe']!='all') $sql .=" and x.shenhe = '{$arr['isShenhe']}' ";
		if($arr['dateShenhe'] != '') {
            $sql .=" and x.ShenheTime >= '{$arr['dateShenhe']} 00:00:00' and x.ShenheTime <= '{$arr['dateShenhe']} 23:59:59'";
        }
        // dump($arr);die;
		$sql.="group by x.orderCode order by x.orderTime desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		// dump($sql);die;
		foreach($rowset as & $v) {
			$v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];
			//明细显示
			// dump($v['orderTime']);die;
			$sql="SELECT y.*,
				p.proCode,
				p.proName,
				p.menfu,
				p.kezhong,
				p.zhengli,
				p.wuliaoKind,
				p.zuzhi from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
				where x.id='{$v['id']}'";
			$ret = $this->_modelExample->findBySql($sql);
			// dump($ret);die;
			foreach ($ret as &$value) {
				// $value['item_type'] = $this->getTypePro($value['item_type']);
				$ret['peihuoIds'][] = $value['peihuoId'];
 			}
			// dump($ret);die;
			//审核
			if($v['status']=='active'){
				//只有未发货状态下才允许反审
				if($v['ship_status']==0) $v['_edit']=$this->getShenheHtml('销售合同',$v['id']);
				else $v['_edit']='已发货,禁止反审';
			}else{
				$v['_edit']="订单状态禁止操作";
			}

			$v['money'] = round($v['money'],2);
			$v['status'] = $this->getStatusOrder($v['status'] ,true);

			//配货详细
			$ret['peihuoIds'] = array_filter($ret['peihuoIds']);
			// dump($ret['peihuoIds']);die;
			$peihuoIds =implode(',',$ret['peihuoIds']);
			$peihuoIds>0 && $v['peihuo'] = "<a href='".url('Peihuo_Peihuo','View2',array(
				'peihuoId'=>$peihuoIds
			))."' title='配货单' target='_blank'>配货单</a>";

			//数量
			$v['cnt'] = round($v['cnt'],3).' '.$v['unit'];
			//支付方式
			$v['payment'] = $this->getPayment($v['payment']);

			//其他信息
			// $_detial=array();
			// $_detial[] = "<span>支付方式</span>：".$this->getPayment($v['payment']);
			// $_detial[] = "<span class='text-primary'>配送方式</span>：".$v['shipping'];
			// // $_detial[] = "<span class='text-success'>商品类型</span>：".$v['item_type'];
			// $_detial[] = "<span class='text-success'>商品金额</span>：".$v['m_pro'];
			// $_detial[] = "<span class='text-success'>运费</span>：".$v['cost_freight'];
			// // $_detial[] = "<span class='text-success'>订单优惠</span>：".$v['pmt_order'];
			// $_detial[] = "<span class='text-success'>会员优惠总额</span>：".$v['youhui'];
			// $_detial[] = "<span class='text-success'>订单减免</span>：".$v['discount'];
			// $_detial[] = "<span class='text-danger'>应收金额</span>：".$v['money'];
			// $_detial[] = "<span>是否要发货</span>：".$v['is_delivery'];

			// $_detial = join('<br>',$_detial);
			// $v['orderCode'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$v['orderCode']}</a>";
			$v['Products']=$ret;
		}
		// dump($rowset);exit;
		$smarty = & $this->_getView();
		$arrField = array(
			"_edit" => array('text'=>'操作','width'=>146),
			'orderTime' =>array('text'=>'订单时间','width'=>130),
			'status'=>array('text'=>'订单状态','width'=>70),
			'compName'=>array('text'=>'客户','width'=>150),
			'money' =>array('text'=>'商品金额','width'=>70),
			'cnt' =>array('text'=>'数量','width'=>70),
			'orderCode'=>array('text'=>'订单编号(more)','width'=>130),
			'compCode' =>array('text'=>'用户名','width'=>70),
			'payment' =>array('text'=>'支付方式','width'=>80),
			'shipping' =>array('text'=>'配送方式','width'=>70),
			'cost_freight' =>array('text'=>'运费','width'=>70),
			// 'pmt_order' =>array('text'=>'促销优惠金额','width'=>100),
			'youhui' =>array('text'=>'会员优惠金额','width'=>100),
			'discount' =>array('text'=>'订单处理金额','width'=>70),
			'pmt_order' =>array('text'=>'订单促销优惠','width'=>70),
			'memo' =>array('text'=>'备注','width'=>170),
			'tag_name' =>array('text'=>'客户订单标签','width'=>90),
			'peihuo' =>array('text'=>'配货详细','width'=>70),
			'ShenheTime' =>array('text'=>'审核时间','width'=>150),
		);
		$arrFieldinfo = array(
			'proCode'=>array('text'=>'花型六位号','width'=>100),
			'kind'=>array('text'=>'现货/样品','width'=>70),
			'proName'=>array('text'=>'品名','width'=>70),
			'wuliaoKind'=>array('text'=>'物料大类','width'=>80),
			'zuzhi' =>array('text'=>'组织大类','width'=>80),
			'zhengli' =>array('text'=>'整理方式','width'=>80),
			'menfu' =>array('text'=>'门幅','width'=>70),
			'kezhong' =>array('text'=>'克重','width'=>70),
			'danjia'=>array('text'=>'单价','width'=>80),
			'money' =>array('text'=>'商品金额','width'=>70),
			'cnt' =>array('text'=>'数量','width'=>70),
			'rollNo' =>array('text'=>'','width'=>70),
		);
		// dump($rowset);exit;
		$smarty->assign('title', '采购合同审核');
		$smarty->assign('arr_field_info', $arrField);
		$smarty->assign('arr_field_info2', $arrFieldinfo);
        $smarty->assign('sub_field', 'Products');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr)."&nbsp;<span class='text-danger'>不需要发货不需要审核</span>");
		$smarty->display('TblListMore.tpl');
	}
	function actionPopup() {
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		TMIS_Pager::clearCondition();
		$arr = TMIS_Pager::getParamArray(array(
				'key' => '',
		));
         $str = "select
				x.orderCode,
				x.clientId,
				x.traderId,
				x.memo as orderMemo,
				x.id,
				x.id as orderId,
				y.compName,
				group_concat(z.productId) as productId,
				m.employName,
				n.proName,
				n.proCode
				from trade_order x
				inner join trade_order2product z on z.orderId=x.id
				left join jichu_client y on x.clientId = y.member_id
				left join jichu_employ m on m.id=x.traderId
				left join jichu_product n on n.proCode=z.productId
                where 1 ";
        if ($arr['key']!='') $str .= " and orderCode like '%$arr[key]%'
                                        or compName like '%$arr[key]%'";
        if($_GET['clientId']!='') $str.=" and x.clientId='{$_GET['clientId']}'";
		$str .= " group by x.id order by  substring(orderCode,3) desc";
		// dump($str);exit;
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll();

		if (count($rowset) > 0) foreach($rowset as $i => &$v) {

		}
		$arrFieldInfo = array(
			"orderCode" => "订单编号",
			"compName" => "客户名称",
			"proCode" => "产品编码",
			"proName" => "品名",
			"orderMemo" => "备注",
		);

		$smarty = &$this->_getView();
		$smarty->assign('title', '选择客户');
		$pk = $this->_modelExample->primaryKey;
		$smarty->assign('pk', $pk);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('Popup/CommonNew.tpl');
	}

	/**
	 * ps ：大货/现货/样品销售统计通用方法
	 * Time：2015/10/22 18:08:03
	 * @author liuxin
	*/
	function ReportXiaoshou($kind){
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'proCode' => '',
			"dateFrom" => date('Y-m-01'),
            "dateTo" => date('Y-m-d'),
			'clientId'=>'',
			'employId' => '',
		));
		if(is_array($kind)){
			$kind = implode("','",$kind);
		}
		$sql="SELECT p.proCode,p.jingmi,p.weimi,
				d.employName,p.proName,p.menfu,
				p.chengfen,p.shazhi,p.pinming,p.colornum,p.colorsnum,
				y.kind,sum(y.cntM)as cnt,c.compName
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
				left join jichu_client c on c.member_id=x.clientId
				left join jichu_employ d on x.traderId=d.id
				where y.kind in ('{$kind}') and x.status != 'dead'
				and x.orderTime<='{$arr['dateTo']} 24:00:00'
				and x.orderTime>='{$arr['dateFrom']}'";
		if($arr['clientId']!='') $sql .=" and x.clientId='{$arr['clientId']}' ";
		if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
		if($arr['employId']!='') $sql .=" and x.traderId like '%{$arr['employId']}%' ";
		$sql.=" group by x.traderId,p.proCode,c.compName order by p.proCode,c.compName";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		foreach ($rowset as &$v) {
			$v['cnt'] = round($v['cnt'],2);
		}
		$rowset['heji']=$this->getHeji($rowset,array('cnt'),'proCode');
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			'proCode' => '花型六位号',
            "compName" => "客户",
            "employName" => "业务员",
			'proName' => '产品名称',
			"pinming" => "品名",
			"colornum" => "款号",
			"colorsnum" => "颜色号",
			'chengfen' => '成份',
			'shazhi' => '纱支',
			'jingmi' => '经密',
			'weimi' => '纬密',
			'menfu' => '门幅',
			'cnt'=>'数量(M)',
		);
		$smarty->assign('title', '销售统计');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	}
	/**
	 * ps ：大货合同设置完成
	 * Time：2015-10-22 18:14:03
	 * @author shen
	*/
	function actionSetOver(){
		$sql = "update trade_order set is_setover='{$_GET['over']}' where id='{$_GET['id']}'";
		$this->_modelExample->execute($sql);
		$msg=$_GET['over']==1?"设置成功!":"取消设置成功!";
		js_alert(null,"window.parent.showMsg('{$msg}')",$this->_url($_GET['fromAction']));
	}

	/**
	 * ps ：销售合同统计报表
	 * Time：2015/10/22 18:31:21
	 * @author liuxin
	*/
	function actionReportOrder(){
		$this->authCheck('14-5');
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			"dateFrom" => date('Y-m-01'),
            "dateTo" => date('Y-m-d'),
			'clientId'=>'',
			'employId' => '',
		));
		//样品
		$sql="SELECT sum(y.cnt) as cntYp,
				0 as cntXh,sum(y.money) as moneyYp,
				0 as moneyXh,p.proCode,
				d.employName,p.proName,
				y.kind,c.compName,x.clientId,
				x.traderId
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
				left join jichu_client c on c.member_id=x.clientId
				-- left join jichu_employ d on x.traderId=d.
				left join jichu_employ d on c.traderId=d.id
				where y.kind = '样品' and x.status != 'dead'
				and x.orderTime<='{$arr['dateTo']} 24:00:00'
				and x.orderTime>='{$arr['dateFrom']}'
				group by x.traderId,c.compName";
		//现货
		$str="SELECT 0 as cntYp,
				sum(y.cnt) as cntXh,0 as moneyYp,
				sum(y.money) as moneyXh,
				p.proCode,d.employName,p.proName,
				y.kind,c.compName ,x.clientId,
				x.traderId
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
				left join jichu_client c on c.member_id=x.clientId
				-- left join jichu_employ d on x.traderId=d.id
				left join jichu_employ d on c.traderId=d.id
				where y.kind = '现货' and x.status != 'dead'
				and x.orderTime<='{$arr['dateTo']} 24:00:00'
				and x.orderTime>='{$arr['dateFrom']}'
				group by x.traderId,c.compName";
		$sqlstr = $sql." union ".$str;
		$str2 = "select sum(cntYp) as cntYp,
					sum(cntXh) as cntXh,
					sum(moneyYp) as moneyYp,
					sum(moneyXh) as moneyXh,
					employName,kind,compName,
					clientId,traderId
					from
					({$sqlstr}) as total
					where 1";
		if($arr['clientId']!='') $str2 .=" and clientId='{$arr['clientId']}' ";
		if($arr['employId']!='') $str2 .=" and traderId like '%{$arr['employId']}%' ";
		$str2.=" group by compName,employName order by compName";
		// dump($str2);die;
		$pager = &new TMIS_Pager($str2);
		$rowset = $pager->findAll();
		foreach ($rowset as &$v) {
			$v['cntYp'] = round($v['cntYp'],3);
			$v['cntXh'] = round($v['cntXh'],3);
			$v['moneyYp'] = round($v['moneyYp'],3);
			$v['moneyXh'] = round($v['moneyXh'],3);
			$v['cnt'] = round($v['cntYp'] + $v['cntXh'],3);
			$v['money'] = round($v['moneyYp'] + $v['moneyXh'],3);
		}
		$rowset['heji']=$this->getHeji($rowset,array('cntYp','cntXh','cnt','moneyYp','moneyXh','money'),'compName');
		$smarty = &$this->_getView();
		// dump($rowset);exit;
		$arrFieldInfo = array(
			"compName" => "客户",
			"employName" => "业务员",
			'cntYp' => '样品M数',
			'cntXh' => '现货M数',
			'cnt'=>'总数量',
			'moneyYp' => '样品金额',
			'moneyXh' => '现货金额',
			'money' => '总金额',
		);
		$smarty->assign('title', '销售合同统计');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
    		if($_GET['export']==1){
        $this->_exportList(array('title'=>$title),$smarty);
    		}
		$smarty->display('TblList.tpl');
	}
	/**
	* ps ：花型销售统计报表
	* Time：2016年4月14日16:31:02
	* @author jiangxu
	*/
	function actionReportViewOrder(){
		//权限判断
		$this->authCheck('14-3');
		FLEA::loadClass('TMIS_Pager');

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'years'=>'2016',
			'productId' => '',
		));

		//全部的花型六位号
		$proAll = "select proCode as productId,pinming,colornum,colorsnum from jichu_product where 1 and LENGTH(proCode) < 7";
        if($arr['productId']!='') $proAll.=" and proCode='{$arr['productId']}'";
		// $proAll.=" GROUP BY y.productId";
		$yue13=$this->_modelExample->findBySql($proAll);

		// dump($yue13);exit;
		$Jan = "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Jan
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and  x.chukuDate>='{$arr['years']}-01-01  00:00:00' and x.chukuDate<='{$arr['years']}-01-31  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Jan.=" and y.productId='{$arr['productId']}'";
		$Jan.=" GROUP BY y.productId ";
		$yue1=$this->_modelExample->findBySql($Jan);

		$Feb= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Feb
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-02-01  00:00:00' and x.chukuDate<='{$arr['years']}-02-29  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Feb.=" and y.productId='{$arr['productId']}'";
        $Feb.=" GROUP BY y.productId";
		$yue2=$this->_modelExample->findBySql($Feb);


		$Mar= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Mar
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-03-01  00:00:00' and x.chukuDate<='{$arr['years']}-03-31  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Mar.=" and y.productId='{$arr['productId']}'";
        $Mar.=" group by y.productId";
		$yue3=$this->_modelExample->findBySql($Mar);

		// dump($yue3);exit;

		$Apr= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Apr
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-04-01  00:00:00' and x.chukuDate<='{$arr['years']}-04-30  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Apr.=" and y.productId='{$arr['productId']}'";
        $Apr.=" group by y.productId";
		$yue4=$this->_modelExample->findBySql($Apr);

		$May= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as May
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-05-01  00:00:00' and x.chukuDate<='{$arr['years']}-05-31  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $May.=" and y.productId='{$arr['productId']}'";
        $May.=" group by y.productId";
		$yue5=$this->_modelExample->findBySql($May);

		$Jun= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Jun
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-06-01  00:00:00' and x.chukuDate<='{$arr['years']}-06-30  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Jun.=" and y.productId='{$arr['productId']}'";
        $Jun.=" group by y.productId";
		$yue6=$this->_modelExample->findBySql($Jun);

		$Jul= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Jul
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-07-01  00:00:00' and x.chukuDate<='{$arr['years']}-07-31  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Jul.=" and y.productId='{$arr['productId']}'";
        $Jul.=" group by y.productId";
		$yue7=$this->_modelExample->findBySql($Jul);

		$Aug= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Aug
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-08-01  00:00:00' and x.chukuDate<='{$arr['years']}-08-31  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Aug.=" and y.productId='{$arr['productId']}'";
        $Aug.=" group by y.productId";
		$yue8=$this->_modelExample->findBySql($Aug);

		$Sep= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Sep
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-09-01  00:00:00' and x.chukuDate<='{$arr['years']}-09-30  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Sep.=" and y.productId='{$arr['productId']}'";
        $Sep.=" group by y.productId";
		$yue9=$this->_modelExample->findBySql($Sep);

		$Oct= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Oct
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-10-01  00:00:00' and x.chukuDate<='{$arr['years']}-10-31  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Oct.=" and y.productId='{$arr['productId']}'";
        $Oct.=" group by y.productId";
		$yue10=$this->_modelExample->findBySql($Oct);

		$Nov= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Nov
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-11-01  00:00:00' and x.chukuDate<='{$arr['years']}-11-30  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Nov.=" and y.productId='{$arr['productId']}'";
        $Nov.=" group by y.productId";
		$yue11=$this->_modelExample->findBySql($Nov);

		$Decm= "SELECT x.chukuDate,y.cnt,y.productId, sum(y.cnt) as Decm
		from cangku_chuku x
		left join cangku_chuku2product y on  x.id = y.chukuId
	    where x.kind='销售出库' and x.chukuDate>='{$arr['years']}-12-01  00:00:00' and x.chukuDate<='{$arr['years']}-12-30  24:00:00' and LENGTH(productId) < 7
		";
        if($arr['productId']!='') $Decm.=" and y.productId='{$arr['productId']}'";
        $Decm.=" group by y.productId";
		$yue12=$this->_modelExample->findBySql($Decm);


		//库存数量
		$sql5 = "SELECT
		sum(cntM)as rowKucun,productId
		from cangku_kucun
		where 1 and LENGTH(productId) < 7";
		if($arr['productId']!=''){
			$sql5.=" and productId like '%{$arr['productId']}%'";
		}
		$sql5.=' GROUP BY productId';
		$rowKucun=$this->_modelExample->findBySql($sql5);

		//预留数量
		$sql6 = "SELECT
		sum(cntM)as kucun_lock,productId
		from madan_db
		where 1 and status='lock' and LENGTH(productId) < 7";
		if($arr['productId']!=''){
			$sql6.=" and productId like '%{$arr['productId']}%'";
		}
		$sql6.=' GROUP BY productId';
		$rowKucun_lock=$this->_modelExample->findBySql($sql6);

		//不含预留数量库存 现货
		$sql7 = "SELECT
		sum(cntM)as xhkucun_else,productId
		from madan_db
		where 1 and status='active' and LENGTH(productId) < 7";
		if($arr['productId']!=''){
			$sql7.=" and productId like '%{$arr['productId']}%'";
		}
		$sql7.=' GROUP BY productId';
		$xhKucun_else=$this->_modelExample->findBySql($sql7);

		//不含预留数量库存 样品
		$sql8 = "SELECT
		sum(cntM)as ypkucun_else,productId
		from cangku_kucun
		where 1 and cangkuName='样品仓库' and LENGTH(productId) < 7";
		if($arr['productId']!=''){
			$sql8.=" and productId like '%{$arr['productId']}%'";
		}
		$sql8.=' GROUP BY productId';
		$ypKucun_else=$this->_modelExample->findBySql($sql8);

		//补单数量
		$str = "SELECT
		sum(cntM)as budan,
		productId,
		group_concat(jiaoqi,'交',cntM,'M') as jiaoqi
		from caigou_order2product
		where 1 and rukuOver=0 and LENGTH(productId) < 7";
		if($arr['productId']!=''){
			$str.=" and productId like '%{$arr['productId']}%'";
		}
		$str.=' GROUP BY productId';
		$rowBudan=$this->_modelExample->findBySql($str);
		$rowBudan[0]['jiaoqi'] = str_replace(',','<br />', $rowBudan[0]['jiaoqi']);

		$result=array_merge_recursive($yue1,$yue2,$yue3,$yue4,$yue5,$yue6,$yue7,$yue8,$yue9,$yue10,$yue11,$yue12,$yue13,$rowKucun,$rowKucun_lock,$xhKucun_else,$ypKucun_else,$rowBudan);
		// dump($result);exit;
		foreach ($result as &$v) {
			// dump($v);die;
       		 $rowset[$v["productId"]][] = $v;
       		 //查询库存
       		 $sql = "select sum(cnt) as kuCun from madan_db where status<>'finish'  and productId = '{$v['productId']}'";
       		 $row = $this->_modelExample->findBySql($sql);
       		 $rowset[$v["productId"]][0]['kuCun'] =$row[0]['kuCun'];

		}
		foreach ($rowset as $key=>&$value) {
			$strJiaoqi='';
			foreach ($value as &$vv) {
				$value['productId'][] = $vv['productId'];
				$value['pinming'][]=$vv['pinming'];
				$value['colornum'][]=$vv['colornum'];
				$value['colorsnum'][]=$vv['colorsnum'];
				$value['Jan'][] = $vv['Jan'];
				$value['Feb'][] = $vv['Feb'];
				$value['Mar'][] = $vv['Mar'];
				$value['Apr'][] = $vv['Apr'];
				$value['May'][] = $vv['May'];
				$value['Jun'][] = $vv['Jun'];
				$value['Jul'][] = $vv['Jul'];
				$value['Aug'][] = $vv['Aug'];
				$value['Sep'][] = $vv['Sep'];
				$value['Oct'][] = $vv['Oct'];
				$value['Nov'][] = $vv['Nov'];
				$value['Decm'][] = $vv['Decm'];
				$value['rowKucun'][] = $vv['rowKucun'];
				$value['kucun_lock'][] = $vv['kucun_lock'];
				$value['xhkucun_else'][] = $vv['xhkucun_else'];
				$value['ypkucun_else'][] = $vv['ypkucun_else'];
				$value['budan'][] = $vv['budan'];
				if($vv['jiaoqi']) {
					$strJiaoqi = $vv['jiaoqi'];
				}
				// $value['hxHj'] = $vv['hxHj'];
			}
			// dump($strJiaoqi);exit;
			$value['productId']=$value[0]['productId'];
			$value['pinming']=implode("",$value['pinming']);
			$value['colornum']=implode("",$value['colornum']);
			$value['colorsnum']=implode("",$value['colorsnum']);
			$value['Jan']=implode("",$value['Jan']);
			$value['Feb']=implode("",$value['Feb']);
			$value['Mar']=implode("",$value['Mar']);
			$value['Apr']=implode("",$value['Apr']);
			$value['May']=implode("",$value['May']);
			$value['Jun']=implode("",$value['Jun']);
			$value['Jul']=implode("",$value['Jul']);
			$value['Aug']=implode("",$value['Aug']);
			$value['Sep']=implode("",$value['Sep']);
			$value['Oct']=implode("",$value['Oct']);
			$value['Nov']=implode("",$value['Nov']);
			$value['Decm']=implode("",$value['Decm']);
			$value['kucun_lock']=implode("",$value['kucun_lock']);
			$value['xhkucun_else']=implode("",$value['xhkucun_else']);
			$value['ypkucun_else']=implode("",$value['ypkucun_else']);
			$value['rowKucun']=implode("",$value['rowKucun']);
			$value['budan']=implode("",$value['budan']);
			// $value['heji']=implode("",$value['heji']);
			$value['hxHj']=$value['Jan']+$value['Feb']+$value['Mar']+$value['Apr']+$value['May']+$value['Jun']+$value['Jul']+$value['Aug']+$value['Sep']+$value['Oct']+$value['Nov']+$value['Decm'];
			//判断查询到的库存如果为空则显示为0
			if($value[0]['kuCun']!='')
				{$value['kuCun'] = $value[0]['kuCun'];
			}else{
				$value['kuCun'] =0;
			}
			if($value['budan']) {
				$txtBudan = '补单:'.$value['budan'];
				// dump('aaaa');exit;dump($value);exit;
				$value['budan']="<a href='javascript:;' class='bg-danger' ext:qtip=\"{$strJiaoqi}\">{$txtBudan}</a>";
			}
			//"<a href='javascript:;' class='bg-danger' ext:qtip=\"{$_detial}\">{$statesh}</a>"

		}
		ksort($rowset);
		// dump($rowset);exit;
		$hj=$this->getHeji($rowset,array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Decm','kucun_lock','xhkucun_else','ypkucun_else','rowKucun','hxHj','kuCun'),'productId');
		$rowset['yfHj']=$hj;
			// array_splice($productIds,0);
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			'productId'=>'花型六位号',
			"pinming" => "品名",
			"colornum" => "款号",
			"colorsnum" => "颜色号",
			"Jan" => "一月份",
			'Feb' => '二月份',
			'Mar' => '三月份',
			'Apr' => '四月份',
			'May' => '五月份',
			'Jun' => '六月份',
			'Jul' => '七月份',
			'Aug' => '八月份',
			"Sep" => '九月份',
			"Oct" => "十月份",
			"Nov" => "十一月份",
			"Decm" => "十二月份",
			'hxHj' => '销售合计',
			"kucun_lock" => "预留数量",
			"xhkucun_else" => "现货不含预留库存数量",
			'kuCun' => '现货库存',
			"ypkucun_else" => "样品库存",
			"rowKucun" => "总库存数量",
			'budan' => '补单信息',
		);
		$smarty->assign('title', '采购合同分析报表');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
            if($_GET['export']==1){
                $this->_exportList(array('title'=>$title),$smarty);
            }
		$smarty->display('TblList.tpl');
	}

	/**
	 * ps ：大货销售统计
	 * Time：2015/10/23 08:53:28
	 * @author liuxin
	*/
	function actionReportDhOrder(){
		$this->authCheck('14-8');
		$this->ReportXiaoshou('大货');
	}

	/**
	 * ps ：现货样品销售统计
	 * Time：2015/10/23 08:53:41
	 * @author liuxin
	*/
	function actionReportXhypOrder(){
		$this->authCheck('14-6');
		$this->ReportXiaoshou(array('现货','样品'));
	}

	/**
	* ps ：订单明细弹框
	* Time：2015/10/23 12:48:33
	* @author jiang
	*/
	function actionPopupMingxi() {
		FLEA::loadClass('TMIS_Pager');
		// /构造搜索区域的搜索类型
		TMIS_Pager::clearCondition();
		$arr = TMIS_Pager::getParamArray(array(
			'orderCode'=>'',
			'clientId'=>'',
			'proCode' => '',
		));
         $str = "select
				x.orderCode,
				x.orderTime,
				z.memo,
				z.id,
				y.compName,
				m.employName,
				n.proName,
				n.proCode,
				n.chengfen,
				n.jingmi,
				n.weimi,
				n.menfu
				from trade_order x
				inner join trade_order2product z on z.orderId=x.id
				left join jichu_client y on x.clientId = y.member_id
				left join jichu_employ m on m.id=x.traderId
				left join jichu_product n on n.proCode=z.productId
                where 1 and z.kind='大货' and x.shenhe='yes'";
        if($arr['orderCode']!='') $str.=" and x.orderCode='{$arr['orderCode']}'";
        if($arr['proCode']!='') $str.=" and n.proCode='{$arr['proCode']}'";
        if($arr['clientId']!='') $str.=" and x.clientId='{$arr['clientId']}'";
		$str .= "order by  substring(orderCode,3) desc";
		// dump($str);exit;
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll();

		if (count($rowset) > 0) foreach($rowset as $i => &$v) {
			$v['jingmi']=$v['weimi']?$v['jingmi'].'*'.$v['weimi']:$v['jingmi'];
		}
		$arrFieldInfo = array(
			// "id" => "id",
			"orderCode" => "订单编号",
			"orderTime" => "下单时间",
			"compName" => "客户名称",
			"proCode" => "产品编码",
			"proName" => "品名",
			"chengfen" => "成分",
			"jingmi" => "经纬密",
			"menfu" => "门幅",
			"memo" => "备注",
		);

		$smarty = &$this->_getView();
		$smarty->assign('title', '选择大货订单明细');
		$pk = $this->_modelExample->primaryKey;
		$smarty->assign('pk', $pk);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('Popup/CommonNew.tpl');
	}


	/**
	* ps ：手机审核查询界面
	* Time：2015/10/26 16:30:34
	* @author jiang
	*/
	function actionShenheByPhone(){
		if(!$_SESSION['USERID']){
			redirect(url('Login','LoginPhone'));exit;
		}
		$sql="select
			x.*,
			y.id as proId,
			y.money as m_pro,
			y.item_type,
			y.cnt,
			y.unit,
			y.kind,
			y.peihuoId,
			if(c.compName='',c.compCode,c.compName) as compName,
			p.proCode,
			p.proName,
			p.menfu,
			p.kezhong,
			p.zhengli,
			p.wuliaoKind,
			p.zuzhi
			from trade_order x
			left join trade_order2product y on x.id=y.orderId
			left join jichu_client c on c.member_id=x.clientId
			left join jichu_product p on p.proCode=y.productId
			left join shenhe_db s on y.id=s.tableId
			where 1 and y.shenhe='' and x.status='active'
				and s.last<>1 and s.status='yes' and s.nodeName='销售合同'";

		$sql.=" order by x.orderTime limit 0,50";
		$rowset=$this->_modelExample->findBySql($sql);
		// dump($rowset);die;
		foreach($rowset as & $v) {
			$v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];
			//审核
			if($v['status']=='active'){
				$v['_edit']=$this->getShenheHtml('销售合同',$v['proId']);
			}else{
				$v['_edit']="订单状态禁止操作";
			}


			$v['item_type'] = $this->getTypePro($v['item_type']);
			$v['status'] = $this->getStatusOrder($v['status'] ,true);

			//配货详细
			$v['peihuoId']>0 && $v['peihuo'] = "<a href='".url('Peihuo_Peihuo','view',array(
				'peihuoId'=>$v['peihuoId']
			))."' title='配货单' target='_blank'>配货单</a>";

			//数量
			$v['cnt'] = round($v['cnt'],3).' '.$v['unit'];
			//金额
			$v['m_pro'] = round($v['m_pro'],3);

		}
		$smarty = & $this->_getView();
		$smarty->assign('arr_field_value', $rowset);
		$smarty->display('Trade/ShowShenhe.tpl');
	}

	/**
	 * 订单新增
	 * Time：2015-10-27 13:37:41
	 * @author shen
	*/
	function actionNewAdd(){
		$this->authCheck('2-3');
		FLEA::loadClass('TMIS_Pager');
		$today = date('w')>0 ? date('w')-1 : 6;//星期几：0（星期7）~ 6（星期六）
		$dateFrom = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$today,date('Y')));
		$dateTo=date('Y-m-d H:i:s');
		$sql="select
			x.*,
			y.id as proId,
			y.money as m_pro,
			y.item_type,
			y.cnt,
			y.unit,
			y.kind,
			y.peihuoId,
			if(c.compName='',c.compCode,c.compName) as compName,
			p.proCode,
			p.proName,
			p.menfu,
			p.kezhong,
			p.zhengli,
			p.wuliaoKind,
			p.zuzhi
			from trade_order x
			left join trade_order2product y on x.id=y.orderId
			left join jichu_client c on c.member_id=x.clientId
			left join jichu_product p on p.proCode=y.productId
			where x.orderTime>='{$dateFrom}' and x.orderTime <= '{$dateTo}' and x.status='active' ";
		$sql.=" order by x.orderTime desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		//dump($rowset);die;
		foreach($rowset as & $v) {
			$v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];

			$v['item_type'] = $this->getTypePro($v['item_type']);
			$v['status'] = $this->getStatusOrder($v['status'] ,true);

			//配货详细
			$v['peihuoId']>0 && $v['peihuo'] = "<a href='".url('Peihuo_Peihuo','view',array(
				'peihuoId'=>$v['peihuoId']
			))."' title='配货单' target='_blank'>配货单</a>";

			//数量
			$v['cnt'] = round($v['cnt'],3).' '.$v['unit'];

			//其他信息
			$_detial=array();
			$_detial[] = "<span>支付方式</span>：".$this->getPayment($v['payment']);
			$_detial[] = "<span class='text-danger'>现货/样品</span>：".$v['kind'];
			$_detial[] = "<span class='text-primary'>配送方式</span>：".$v['shipping'];
			$_detial[] = "<span class='text-success'>商品类型</span>：".$v['item_type'];
			$_detial[] = "<span class='text-success'>商品金额</span>：".$v['money_order'];
			$_detial[] = "<span class='text-success'>运费</span>：".$v['cost_freight'];
			$_detial[] = "<span class='text-success'>整单优惠</span>：".$v['pmt_money'];
			$_detial[] = "<span class='text-danger'>应收金额</span>：".$v['money'];
			$_detial[] = "<span>是否要发货</span>：".$v['is_delivery'];

			$_detial = join('<br>',$_detial);
			$v['orderCode'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$v['orderCode']}</a>";
		}

		$smarty = & $this->_getView();
		$arrField = array(
			'orderTime' =>array('text'=>'订单时间','width'=>130),
			'status'=>array('text'=>'订单状态','width'=>70),
			'compName'=>array('text'=>'客户','width'=>70),
			'proCode'=>array('text'=>'花型六位号','width'=>100),
			'kind'=>array('text'=>'现货/样品','width'=>70),
			'proName'=>array('text'=>'品名','width'=>70),
			'wuliaoKind'=>array('text'=>'物料大类','width'=>80),
			'zuzhi' =>array('text'=>'组织大类','width'=>80),
			'zhengli' =>array('text'=>'整理方式','width'=>80),
			'menfu' =>array('text'=>'门幅','width'=>70),
			'kezhong' =>array('text'=>'克重','width'=>70),
			// 'money'=>array('text'=>'订单总金额','width'=>80),
			'm_pro' =>array('text'=>'商品金额','width'=>70),
			'cnt' =>array('text'=>'数量','width'=>70),
			// 'payment'=>array('text'=>'支付方式','width'=>80),
			'orderCode'=>array('text'=>'订单编号(more)','width'=>130),
			'peihuo' =>array('text'=>'配货详细','width'=>70),
		);
		$smarty->assign('arr_field_info', $arrField);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr)."&nbsp;<span class='text-danger'>不需要发货不需要审核</span>");
		$smarty->display('TblList.tpl');
	}
	/**
	 * 未出库销售合同
	 * Time：2015-10-27 13:37:41
	 * @author shen
	*/
	function actionHtWaiting(){
		$this->authCheck('2-3');
		FLEA::loadClass('TMIS_Pager');
		$sql="SELECT x.*,p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,
				p.menfu,p.color,y.id as sId,y.kind,y.unit,y.cnt,y.danjia,y.shenhe,c.compName
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
				left join jichu_client c on c.member_id=x.clientId
				left join cangku_chuku2product a on a.ord2proId=y.id
				where 1 and a.id is null and x.is_delivery='Y' and x.status='active'";
		$sql.=" order by x.orderTime desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		// dump($rowset);exit;
		if (count($rowset) > 0) foreach($rowset as &$v) {
		    $v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];
		    $v['cost_freight']=round($v['cost_freight'],3);



			$v['is_tax']=$v['is_tax']=='true'?'是':'否';

			//支付方式
			$v['payment']=$this->getPayment($v['payment']);

			//是否发货
			if($v['is_delivery']=='N'){
				$v['is_delivery']="<p class='bg-danger' title='{$v['isdelivery_desc']}'>{$v['is_delivery']}</p>";
			}

			$v['status'] = $this->getStatusOrder($v['status'] ,true);
		}
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			'orderCode'=>array('width'=>'130','text'=>'订单号'),
			'orderTime'=>array('width'=>'130','text'=>'下单时间'),
			// 'state'=>array('width'=>'70','text'=>'审核状态'),
			'status'=>array('width'=>'70','text'=>'订单状态'),
			"compName" => "客户",
			'payment'=>'支付方式',
			'is_delivery'=>array('width'=>'70','text'=>'是否发货'),
			'shipping'=>'配送方式',
			'ship_name'=>array('width'=>'70','text'=>'收货人'),
			'ship_addr'=>'收货地址',
			'cost_freight'=>array('width'=>'70','text'=>'配送费用'),
			'currency'=>array('width'=>'70','text'=>'支付货币'),
			'money' =>array('width'=>'70','text'=>'总金额'),
			'proCode'=>array('width'=>'70','text'=>'花型六位号'),
			'proName'=>array('width'=>'70','text'=>'产品名称'),
			'kind'=>array('width'=>'70','text'=>'类型'),
			'cnt'=>array('width'=>'70','text'=>'数量'),
			'unit'=>array('width'=>'70','text'=>'单位'),
			'danjia'=>array('width'=>'70','text'=>'单价'),
			"memo" => '备注',
		);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr))."&nbsp;<span class='text-danger'>是否发货：红色表示不用发货</span>");
		$smarty->display('TblList.tpl');
	}
	/**
	 * 待确认发货单
	 * Time：2015-10-27 13:37:41
	 * @author shen
	*/
	function actionFahuoWaiting(){
		$this->authCheck('3-2-6') and $this->authCheck('3-1-6');
		FLEA::loadClass('TMIS_Pager');
		$sql="SELECT x.*,p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,
				p.menfu,p.color,y.id as sId,y.kind,y.unit,y.cnt,y.danjia,y.shenhe,c.compName
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				left join jichu_product p on p.proCode=y.productId
				left join jichu_client c on c.member_id=x.clientId
				left join chuku_plan a on a.ord2proId=y.id
				where 1 and a.id is null and y.shenhe='yes' and x.is_delivery='Y' and x.status='active'";
		$sql.=" order by x.orderTime desc";
		// dump($sql);exit;
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		// dump($rowset);exit;
		if (count($rowset) > 0) foreach($rowset as &$v) {
		    $v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];
		    $v['cost_freight']=round($v['cost_freight'],3);


			$v['is_tax']=$v['is_tax']=='true'?'是':'否';

			//支付方式
			$v['payment']=$this->getPayment($v['payment']);

			//是否发货
			if($v['is_delivery']=='N'){
				$v['is_delivery']="<p class='bg-danger' title='{$v['isdelivery_desc']}'>{$v['is_delivery']}</p>";
			}

			$v['status'] = $this->getStatusOrder($v['status'] ,true);
		}
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			'orderCode'=>array('width'=>'130','text'=>'订单号'),
			'orderTime'=>array('width'=>'130','text'=>'下单时间'),
			'status'=>array('width'=>'70','text'=>'订单状态'),
			"compName" => "客户",
			'payment'=>'支付方式',
			'is_delivery'=>array('width'=>'70','text'=>'是否发货'),
			'shipping'=>'配送方式',
			'ship_name'=>array('width'=>'70','text'=>'收货人'),
			'ship_addr'=>'收货地址',
			'cost_freight'=>array('width'=>'70','text'=>'配送费用'),
			'currency'=>array('width'=>'70','text'=>'支付货币'),
			'money' =>array('width'=>'70','text'=>'总金额'),
			'proCode'=>array('width'=>'70','text'=>'花型六位号'),
			'proName'=>array('width'=>'70','text'=>'产品名称'),
			'kind'=>array('width'=>'70','text'=>'类型'),
			'cnt'=>array('width'=>'70','text'=>'数量'),
			'unit'=>array('width'=>'70','text'=>'单位'),
			'danjia'=>array('width'=>'70','text'=>'单价'),
			"memo" => '备注',
		);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr))."&nbsp;<span class='text-danger'>是否发货：红色表示不用发货</span>");
		$smarty->display('TblList.tpl');
	}
	/**
	 * ps:销售合同待审核
	 * Time:2015-10-27 13:56:30
	 * @author shen
	 */
	function actionHetongSh(){
		$this->authCheck('2-4');
		FLEA::loadClass('TMIS_Pager');
		$sql="select
			x.*,
			y.id as proId,
			y.money as m_pro,
			y.item_type,
			y.cnt,
			y.unit,
			y.kind,
			y.peihuoId,
			if(c.compName='',c.compCode,c.compName) as compName,
			p.proCode,
			p.proName,
			p.menfu,
			p.kezhong,
			p.zhengli,
			p.wuliaoKind,
			p.zuzhi
			from trade_order x
			left join trade_order2product y on x.id=y.orderId
			left join jichu_client c on c.member_id=x.clientId
			left join jichu_product p on p.proCode=y.productId
			where 1 and y.shenhe='' and x.status='active' and is_delivery='Y'";

		$sql.=" order by x.orderTime desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		//dump($rowset);die;
		foreach($rowset as & $v) {
			$v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];

			$v['item_type'] = $this->getTypePro($v['item_type']);
			$v['status'] = $this->getStatusOrder($v['status'] ,true);

			//配货详细
			$v['peihuoId']>0 && $v['peihuo'] = "<a href='".url('Peihuo_Peihuo','view',array(
				'peihuoId'=>$v['peihuoId']
			))."' title='配货单' target='_blank'>配货单</a>";

			//数量
			$v['cnt'] = round($v['cnt'],3).' '.$v['unit'];

			//其他信息
			$_detial=array();
			$_detial[] = "<span>支付方式</span>：".$this->getPayment($v['payment']);
			$_detial[] = "<span class='text-danger'>现货/样品</span>：".$v['kind'];
			$_detial[] = "<span class='text-primary'>配送方式</span>：".$v['shipping'];
			$_detial[] = "<span class='text-success'>商品类型</span>：".$v['item_type'];
			$_detial[] = "<span class='text-success'>商品金额</span>：".$v['money_order'];
			$_detial[] = "<span class='text-success'>运费</span>：".$v['cost_freight'];
			$_detial[] = "<span class='text-success'>整单优惠</span>：".$v['pmt_money'];
			$_detial[] = "<span class='text-danger'>应收金额</span>：".$v['money'];
			$_detial[] = "<span>是否要发货</span>：".$v['is_delivery'];

			$_detial = join('<br>',$_detial);
			$v['orderCode'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$v['orderCode']}</a>";
		}

		$smarty = & $this->_getView();
		$arrField = array(
			'orderTime' =>array('text'=>'订单时间','width'=>130),
			'status'=>array('text'=>'订单状态','width'=>70),
			'compName'=>array('text'=>'客户','width'=>70),
			'proCode'=>array('text'=>'花型六位号','width'=>100),
			'kind'=>array('text'=>'现货/样品','width'=>70),
			'proName'=>array('text'=>'品名','width'=>70),
			'wuliaoKind'=>array('text'=>'物料大类','width'=>80),
			'zuzhi' =>array('text'=>'组织大类','width'=>80),
			'zhengli' =>array('text'=>'整理方式','width'=>80),
			'menfu' =>array('text'=>'门幅','width'=>70),
			'kezhong' =>array('text'=>'克重','width'=>70),
			// 'money'=>array('text'=>'订单总金额','width'=>80),
			'm_pro' =>array('text'=>'商品金额','width'=>70),
			'cnt' =>array('text'=>'数量','width'=>70),
			// 'payment'=>array('text'=>'支付方式','width'=>80),
			'orderCode'=>array('text'=>'订单编号(more)','width'=>130),
			'peihuo' =>array('text'=>'配货详细','width'=>70),
		);
		$smarty->assign('arr_field_info', $arrField);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr)."&nbsp;<span class='text-danger'>不需要发货不需要审核</span>");
		$smarty->display('TblList.tpl');
	}
	/**
	 * ps:成本价详细
	 * Time:2016年3月10日 08:54:05
	 * @author shen
	 */
	function actionChengben(){

		if($_GET['peihuoId']!='0'){
			// dump($_GET);die;
			$sql="SELECT * from ph_peihuo2madan where phId='{$_GET['peihuoId']}'";
			$rowset=$this->_modelExample->findBySql($sql);
			// dump($rowset);die;
		}
        foreach ($rowset as $k=> & $v) {
        	$sql="SELECT * from madan_db where id = '{$v['madanId']}'";
        	$row = $this->_modelExample->findBySql($sql);

        	$v['productId'] = $_GET['productId'];
        	$v['millNo'] = $row['0']['millNo'];
        	$v['cntM'] = $row['0']['cntM'];

        	$sql = "SELECT y.danjia from caigou_order x
	        	left join caigou_order2product y on x.id=y.caigouId
	        	left join cangku_ruku z on z.caigouId=x.id
	        	left join cangku_ruku2product w on z.id=w.rukuId
	        	where w.productId='{$_GET['productId']}' and w.pihao='{$v['millNo']}'";
        	$pro_d = $this->_modelExample->findBySql($sql);
        	$v['danjia'] = $pro_d['0']['danjia'];
        	$v['money'] = $v['danjia'] * $v['cntM'];
        }
		$hj=$this->getHeji($rowset,array('money'));

        $smarty = & $this->_getView();
        $smarty->assign('row', $rowset);
        $smarty->assign('hj', $hj);
        $smarty->display('Trade/Chengben.tpl');
	}

	/**
	 * ps:销量查询
	 * Time:2016年3月28日 12:25:00
	 * @author shen
	 */
	function actionXiaoshouReport(){
		set_time_limit(0);
		FLEA::loadClass('TMIS_Pager');
		FLEA::loadClass('TMIS_Common');
		$arr = TMIS_Pager::getParamArray(array(
			'proName' => '',
		));

		//全部的花型六位号
		$proAll = "select proCode as productId,pinming,colornum,colorsnum from jichu_product where 1";
        if($arr['proName']!='') $proAll.=" and proCode='{$arr['proName']}'";
		// $proAll.=" GROUP BY y.productId";
		$All=$this->_modelExample->findBySql($proAll);

		$sql = "SELECT
		dateFasheng,
		sum(cntM)as threeCnt,productId
		from cangku_kucun
		where   kind='销售出库'
		and dateFasheng BETWEEN SUBDATE(CURDATE(),INTERVAL 3 month) and NOW()
		";

		if($arr['proName']!=''){
			$sql.=" and productId like '%{$arr['proName']}%'";
		}
		// dump($sql);die;
		$sql.=' GROUP BY productId';
		$rowthree=$this->_modelExample->findBySql($sql);


        $sql2 = "SELECT
		dateFasheng,
		sum(cntM)as sixCnt,productId
		from cangku_kucun
		where  kind='销售出库'
		and dateFasheng BETWEEN SUBDATE(CURDATE(),INTERVAL 6 month) and NOW()
		";

		if($arr['proName']!=''){
			$sql2.=" and productId like '%{$arr['proName']}%'";
		}
		$sql2.=' GROUP BY productId';
		$rowsix=$this->_modelExample->findBySql($sql2);


	 	$sql3 = "SELECT
		dateFasheng,
		sum(cntM)as twelveCnt,productId
		from cangku_kucun
		where  kind='销售出库'
		and dateFasheng BETWEEN SUBDATE(CURDATE(),INTERVAL 12 month) and NOW()
		";

		if($arr['proName']!=''){
			$sql3.=" and productId like '%{$arr['proName']}%'";
		}
		$sql3.=' GROUP BY productId';
		$rowtwelve=$this->_modelExample->findBySql($sql3);

		//历史
		$sql4 = "SELECT
		dateFasheng,
		sum(cntM)as AllCnt,productId
		from cangku_kucun
		where  kind='销售出库'";
		if($arr['proName']!=''){
			$sql4.=" and productId like '%{$arr['proName']}%'";
		}
		$sql4.=' GROUP BY productId';
		$rowAll=$this->_modelExample->findBySql($sql4);

		//库存数量
		$sql5 = "SELECT
		dateFasheng,
		sum(cntM)as kucun,productId
		from cangku_kucun
		where 1 ";
		if($arr['proName']!=''){
			$sql5.=" and productId like '%{$arr['proName']}%'";
		}
		$sql5.=' GROUP BY productId';
		$rowKucun=$this->_modelExample->findBySql($sql5);

		//预留数量
		$sql6 = "SELECT
		sum(cntM)as kucun_lock,productId
		from madan_db
		where 1 and status='lock'";
		if($arr['proName']!=''){
			$sql6.=" and productId like '%{$arr['proName']}%'";
		}
		$sql6.=' GROUP BY productId';
		$rowKucun_lock=$this->_modelExample->findBySql($sql6);

		//不含预留数量库存 现货
		$sql7 = "SELECT
		sum(cntM)as xhkucun_else,productId
		from madan_db
		where 1 and status='active'";
		if($arr['proName']!=''){
			$sql7.=" and productId like '%{$arr['proName']}%'";
		}
		$sql7.=' GROUP BY productId';
		$xhKucun_else=$this->_modelExample->findBySql($sql7);

		//不含预留数量库存 样品
		$sql8 = "SELECT
		sum(cntM)as ypkucun_else,productId
		from cangku_kucun
		where 1 and cangkuName='样品仓库'";
		if($arr['proName']!=''){
			$sql8.=" and productId like '%{$arr['proName']}%'";
		}
		$sql8.=' GROUP BY productId';
		$ypKucun_else=$this->_modelExample->findBySql($sql8);



		//补单数量
		$str = "SELECT sum(cntM)as budan,productId from caigou_order2product
		where 1 and rukuOver=0";
		if($arr['proName']!=''){
			$str.=" and productId like '%{$arr['proName']}%'";
		}
		$str.=' GROUP BY productId';
		$rowBudan=$this->_modelExample->findBySql($str);

		$result=array_merge_recursive($rowthree,$rowsix,$rowtwelve,$rowAll,$rowKucun,$rowKucun_lock,$xhKucun_else,$ypKucun_else,$rowBudan,$All);
		// dump($result);exit;
		foreach($result as $k=>&$v) {
       		 $row[$v["productId"]][] = $v;
		}

		// dump($row);die;
		foreach ($row as $key => &$value) {
			foreach ($value as &$vv) {
				$value['threeCnt'][] = $vv['threeCnt'];
				$value['sixCnt'][] = $vv['sixCnt'];
				$value['twelveCnt'][] = $vv['twelveCnt'];
				$value['AllCnt'][] = $vv['AllCnt'];
				$value['kucun'][] = $vv['kucun'];
				$value['xhkucun_else'][] = $vv['xhkucun_else'];
				$value['ypkucun_else'][] = $vv['ypkucun_else'];
				$value['kucun_lock'][] = $vv['kucun_lock'];
				$value['budan'][] = $vv['budan'];
				$value['pinming'][] = $vv['pinming'];
				$value['colornum'][] = $vv['colornum'];
				$value['colorsnum'][] = $vv['colorsnum'];
			}
			$value['threeCnt']=implode("",$value['threeCnt']);
			$value['sixCnt']=implode("",$value['sixCnt']);
			$value['twelveCnt']=implode("",$value['twelveCnt']);
			$value['AllCnt']=implode("",$value['AllCnt']);
			$value['kucun']=implode("",$value['kucun']);
			$value['kucun_lock']=implode("",$value['kucun_lock']);
			$value['xhkucun_else']=implode("",$value['xhkucun_else']);
			$value['ypkucun_else']=implode("",$value['ypkucun_else']);
			$value['budan']=implode("",$value['budan']);
			$value['pinming']=implode("",$value['pinming']);
			$value['colornum']=implode("",$value['colornum']);
			$value['colorsnum']=implode("",$value['colorsnum']);

			$value['threeCnt']=abs($value['threeCnt']);
			$value['sixCnt']=abs($value['sixCnt']);
			$value['twelveCnt']=abs($value['twelveCnt']);
			$value['twelveCnt']=abs($value['twelveCnt']);
			$value['AllCnt']=abs($value['AllCnt']);
			$value['productId']=$value[0]['productId'];

			$value['threeAverage'] =round($value['threeCnt']/3,3);
			$value['sixAverage'] =round($value['sixCnt']/6,3);
			$value['twelveAverage'] =round($value['twelveCnt']/12,3);

			if($value['kucun']){
				$value['threePro'] = round($value['threeCnt']/$value['kucun'],3);
				$value['sixPro'] = round($value['sixCnt']/$value['kucun'],3);
				$value['twelvePro'] = round($value['twelveCnt']/$value['kucun'],3);
			}
		}

		$hj=$this->getHeji($row,array('threeCnt','sixCnt','twelveCnt','AllCnt','threeAverage','sixAverage','twelveAverage','kucun'),'productId');
		$row[]=$hj;

		foreach ($row as &$vv) {
			//进度条
			$vv['threePro'] = TMIS_Common::progressBar($vv['threePro'],array('title'=>'ProgressBar：'.
				($vv['threePro']*100).'%'));
			$vv['sixPro'] = TMIS_Common::progressBar($vv['sixPro'],array('title'=>'ProgressBar：'.
				($vv['sixPro']*100).'%'));
			$vv['twelvePro'] = TMIS_Common::progressBar($vv['twelvePro'],array('title'=>'ProgressBar：'.
				($vv['twelvePro']*100).'%'));

			//提醒
			// 当库存数量-前三个月销售量>=前三个月销售量，提示安全库存范围。
	 		// 当库存数量-前三个月销售量>=前三个月销售量一半，提示基本满足满足未来三个月销售量。
	 		// 当库存数量<前三个月销售量的一半，且库存大于0，提示库存不足，无法满足未来三个月销售量。
	 		// 当库存=0，前三个月销售量=0，提示无库存，无前三个月销售量
			if($vv['kucun']-$vv['threeCnt']>=$vv['threeCnt']){
				$vv['notice'] = '安全库存范围';
			}
			if($vv['kucun']-$vv['threeCnt']>=($vv['threeCnt']/2)){
				$vv['notice'] = "<span style='color:green;'>基本满足满足未来三个月销售量 </span>";
			}
			if($vv['kucun']<($vv['threeCnt']/2) && $vv['kucun']>0){
				$vv['notice'] = "<span style='color:#e53333;'>库存不足，无法满足未来三个月销售量 </span>";
			}
			if($vv['threeCnt']==0 && $vv['kucun']==0){
				$vv['notice'] = '无库存，无前三个月销售量';
			}

			//字体颜色
			$vv['threeCnt']='<span style="color:green;">'.$vv['threeCnt']."</span>";
			$vv['sixCnt']='<span style="color:purple;">'.$vv['sixCnt'].'</span>';
			$vv['twelveCnt']='<span style="color:blue;">'.$vv['twelveCnt'].'</span>';

			$vv['threeAverage']='<span style="color:green;">'.$vv['threeAverage']."</span>";
			$vv['sixAverage']='<span style="color:purple;">'.$vv['sixAverage'].'</span>';
			$vv['twelveAverage']='<span style="color:blue;">'.$vv['twelveAverage'].'</span>';

			if($vv['budan']) $vv['budan']='补单信息:'.$vv['budan'];

			if($vv['mark']=='heji'){
				unset($vv['threePro']);
				unset($vv['sixPro']);
				unset($vv['twelvePro']);
			}
		}
        $row=array_column_sort($row,'productId',SORT_ASC);
 		$smarty = & $this->_getView();
		$arrField = array(
			'productId' =>array('text'=>'花型六位号','width'=>100),
			"pinming" => "品名",
			"colornum" => "款号",
			"colorsnum" => "颜色号",
			'threeCnt'=>array('text'=>'前三月','width'=>65),
			'sixCnt'=>array('text'=>'前六月','width'=>65),
			'twelveCnt'=>array('text'=>'前十二月','width'=>65),
			'AllCnt'=>array('text'=>'历史','width'=>70),
			'threeAverage'=>array('text'=>'前三月  /平均','width'=>100),
			'sixAverage'=>array('text'=>'前六月  /平均','width'=>100),
			'twelveAverage' =>array('text'=>'前十二月  /平均','width'=>100),
			'threePro' =>array('text'=>'前三月  /Pro','width'=>100),
			'sixPro' =>array('text'=>'前六月  /Pro','width'=>100),
			'twelvePro' =>array('text'=>'前十二月  /Pro','width'=>100),
			'kucun_lock' =>array('text'=>'预留数量','width'=>70),
			'xhkucun_else' =>array('text'=>'现货不含预留库存数量','width'=>100),
			'ypkucun_else' =>array('text'=>'样品库存','width'=>100),
			'kucun' =>array('text'=>'库存数量','width'=>70),
			'budan' =>array('text'=>'补单信息','width'=>100),
			'notice'=>array('text'=>'友情提醒','width'=>200),
		);
		//处理数据
        if($_GET['export']==1){
        	foreach ($row as & $value) {
				$value['threeCnt'] = preg_replace("/<span[^>]*>/i", "", $value['threeCnt']);
				$value['threeCnt'] = preg_replace("/<\/span>/i", "", $value['threeCnt']);
				$value['threePro'] = round($value['threeCnt']/$value['kucun'],3);
				$value['threePro'] = ($value['threePro']*100).'%';

				$value['sixCnt'] = preg_replace("/<span[^>]*>/i", "", $value['sixCnt']);
				$value['sixCnt'] = preg_replace("/<\/span>/i", "", $value['sixCnt']);
				$value['sixPro'] = round($value['sixCnt']/$value['kucun'],3);
				$value['sixPro'] = ($value['sixPro']*100).'%';

				$value['twelveCnt'] = preg_replace("/<span[^>]*>/i", "", $value['twelveCnt']);
				$value['twelveCnt'] = preg_replace("/<\/span>/i", "", $value['twelveCnt']);
				$value['twelvePro'] = round($value['twelveCnt']/$value['kucun'],3);
				$value['twelvePro'] = ($value['twelvePro']*100).'%';
				$value = preg_replace("/<span[^>]*>/i", "", $value);
				$value = preg_replace("/<\/span>/i", "", $value);
            }
        }
		$smarty->assign('arr_field_info', $arrField);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_value', $row);
		$smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
            if($_GET['export']==1){
                $this->_exportList(array('title'=>$title),$smarty);
            }
		$smarty->display('TblList.tpl');
	}


	/**
	 * ps ：现货利润报表
	 * Time：2016年4月22日16:10:26
	 * @author jiangxu
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionXhLirunReport(){
		$this->authCheck('14-10');
		FLEA::loadClass('TMIS_Pager');

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'proName' => '',
		));

		$sql = "select x.orderCode,p.madanId,y.productId,m.cntM,m.millNo,rk.danjia as cMoney,y.danjia as xMoney
				From trade_order x
				left join trade_order2product y on y.orderId = x.id
				left join caiwu_ar_guozhang g on g.orderId = x.id
				left join ph_peihuo2madan p on p.phId = y.peihuoId
				left join madan_db m on m.id = p.madanId
				left join madan_rc2madan md on m.id = md.madanId
				left join cangku_ruku2product rk on rk.id = md.rukuId
				left join cangku_ruku k on k.id = rk.rukuId
				where m.millNo = rk.pihao ";
		if($arr['proName']!=''){
			$sql.=" and y.productId like '%{$arr['proName']}%'";
		}
		$sql.=' order BY y.productId';
		// $sql.='order by y.productId';
		$rowset=$this->_modelExample->findBySql($sql);
		// dump($sql);exit;
		foreach ($rowset as &$value) {
			$productId = $value['productId'];
			$millNo = $value['millNo'];
			$row[$productId][$millNo][]=array(
				'orderCode'=>$value['orderCode'],
				'madanId'=>$value['madanId'],
				'cMoney'=>$value['cMoney'],
				'xMoney'=>$value['xMoney'],
				'millNo'=>$value['millNo'],
				'cntM'=>$value['cntM'],
				);
		}
		foreach ($row as &$v) {
			foreach ($v as &$vv) {
				foreach ($vv as &$vvv) {
			$vvv['lirun'] = $vvv['cntM']*($vvv['xMoney']-$vvv['cMoney']);
			$vv[0]['pLirun'] +=$vvv['lirun'];
				}
			$v['zLirun'] +=$vv[0]['pLirun'];
			}
		}
		// dump($v['zLirun']);exit;
		foreach ($row as $key=>$v1) {
			foreach ($v1 as $b => $a) {
					$Xianshi[$key]=array(
					'productId'=>$key,
					'zLirun'=>$v1['zLirun'],
					'Mingxi'=> "<a href='".url('Trade_Order','Mingxi',array(
					'proName'=>$key
			))."' title='利润明细' target='_blank'>利润明细</a>"
					);

				}
			}
		$arrFieldinfo = array(
			'productId' =>array('text'=>'花型六位号','width'=>100),
			'zLirun'=>array('text'=>'总利润','width'=>70),
			'Mingxi' =>array('text'=>'利润明细','width'=>70),
		);
		// dump($row);exit;
		$smarty = &$this->_getView();
		$smarty->assign('title', '采购合同审核');
		$smarty->assign('arr_field_info', $arrFieldinfo);
        $smarty->assign('sub_field', 'Products');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_value', $Xianshi);
		$smarty->display('TblList.tpl');
	}

	/**
	 * ps ：现货报表明细
	 * Time：2016年4月22日16:09:02
	 * @author jiangxu
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionMingxi(){
		$this->authCheck('14-10');
		FLEA::loadClass('TMIS_Pager');
		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'proName' => '',
		));

		$sql = "select x.orderCode,p.madanId,y.productId,m.cntM,m.millNo,rk.danjia as cMoney,y.danjia as xMoney
				From trade_order x
				left join trade_order2product y on y.orderId = x.id
				left join caiwu_ar_guozhang g on g.orderId = x.id
				left join ph_peihuo2madan p on p.phId = y.peihuoId
				left join madan_db m on m.id = p.madanId
				left join madan_rc2madan md on m.id = md.madanId
				left join cangku_ruku2product rk on rk.id = md.rukuId
				left join cangku_ruku k on k.id = rk.rukuId
				where m.millNo = rk.pihao ";
		if($arr['proName']!=''){
			$sql.=" and y.productId like '%{$arr['proName']}%'";
		}
		$sql.=' order BY y.productId';
		$rowset=$this->_modelExample->findBySql($sql);
		foreach ($rowset as &$value) {
			$productId = $value['productId'];
			$millNo = $value['millNo'];
			$row[$productId][$millNo][]=array(
				'orderCode'=>$value['orderCode'],
				'madanId'=>$value['madanId'],
				'cMoney'=>$value['cMoney'],
				'xMoney'=>$value['xMoney'],
				'millNo'=>$value['millNo'],
				'cntM'=>$value['cntM'],
				);
		}
		foreach ($row as &$v) {
			foreach ($v as &$vv) {
				foreach ($vv as &$vvv) {
			$vvv['lirun'] = $vvv['cntM']*($vvv['xMoney']-$vvv['cMoney']);
			$vv[0]['pLirun'] +=$vvv['lirun'];
				}
			$v['zLirun'] +=$vv[0]['pLirun'];
			}
		}
		foreach ($row as &$v1) {
			foreach ($v1 as $v2) {
				foreach ($v2 as &$v3) {
					$pihao = $v3['millNo'];
					$ret[$pihao][]=array(
						'cMoney'=>$v3['cMoney'],
						'xMoney'=>$v3['xMoney'],
						'cntM'=>$v3['cntM'],
						);
					$ret[$pihao][0]['cntSum']+=$v3['cntM'];
					$ret[$pihao][0]['pLirun'] = $v2[0]['pLirun'];
					// dump($ret[$pihao][0]['cntSum']);
				}
			}
		}
		// dump($ret);exit;
        $smarty = & $this->_getView();
        $smarty->assign('row', $ret);
        $smarty->display('Trade/Lirun.tpl');
	}

	/**
	 * ps:样品利润报表
	 * Time:2016年4月5日 17:02:11
	 * @author shen
	 */
	function actionYpLirunReport(){
		set_time_limit(0);
		FLEA::loadClass('TMIS_Pager');
		FLEA::loadClass('TMIS_Common');
		$arr = TMIS_Pager::getParamArray(array(
			'proCode' => '',
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' =>date('Y-m-d '),
		));

		$sql="SELECT y.productId,y.cntM,y.danjia,sum(y.cntM*y.danjia)as cbmoney from cangku_ruku x
			left join cangku_ruku2product y on x.id=y.rukuId
			where x.kind in('采购入库','采购退库','调货') and x.cangkuName='样品仓库'";

	 	// if($arr['dateFrom'] != '') {
   //          $sql .=" and x.rukuDate >= '{$arr['dateFrom']} 00:00:00' and x.rukuDate <= '{$arr['dateTo']} 23:59:59'";
   //      }
        if($arr['proCode']!=''){
        	$sql .=" and y.productId like '%{$arr['proCode']}%'";
        }
		$sql.=' GROUP BY y.productId';

		$rowset=$this->_modelExample->findBySql($sql);
		// dump($rowset);die;

		foreach($rowset as $k=>&$v) {
       		 $cb_money[$v["productId"]][] = $v;
		}
		$_temp=array();
		foreach ($cb_money as  &$vv) {
			foreach ($vv as  &$vvv) {
				$aa['cb_cnt'] += $vvv['cntM'];
				$aa['cb_money'] += $vvv['cbmoney'];
				$aa['productId'] = $vvv['productId'];
			}
			$aa['productId'] = $aa['productId'];
			$aa['averageDanjia'] = round($aa['cb_money']/$aa['cb_cnt'],2);
			$_temp[] = $aa;
			unset($aa);
		}

		// dump($_temp);die;

		$str="SELECT y.productId,sum(y.cntM*y.danjia)as xsmoney,(y.cntM)as xscnt
			from cangku_chuku x
			left join cangku_chuku2product y on x.id=y.chukuId
			where x.kind='销售出库' and x.cangkuName='样品仓库' and y.peihuoId='0'";

		if($arr['dateFrom'] != '') {
            $str .=" and x.chukuDate >= '{$arr['dateFrom']} 00:00:00' and x.chukuDate <= '{$arr['dateTo']} 23:59:59'";
        }
        if($arr['proCode']!=''){
        	$str .=" and y.productId like '%{$arr['proCode']}%'";
        }
		$str.=' GROUP BY y.productId';
		$row=$this->_modelExample->findBySql($str);
		// dump($row);die;
		foreach ($row as &$v1) {
			$v1['xsmoney'] =round($v1['xsmoney'],2);
		}

		$rowsSon=array_merge_recursive($row,$_temp);
        $rowsSon=array_column_sort($rowsSon,'productId',SORT_ASC);
		// dump($rowsSon);exit;

		foreach($rowsSon as $k=>&$v) {
       		$result[$v["productId"]][] = $v;
		}

		$ret=array();
		foreach ($result as $key => $value) {
			$temp=array();
			foreach ($value as $a => $b) {
				$temp=array_merge($temp,$b);
			}
			$ret[$key] = $temp;
		}
		// dump($ret);die;
		foreach ($ret as &$c) {
			$c['lirun'] = ($c['xscnt']*$c['averageDanjia']) -$c['cb_money'];
			$c['jqmoney'] = $c['xscnt']*$c['averageDanjia'];
		}



		$smarty = & $this->_getView();
		$arrField = array(
			'productId' =>array('text'=>'花型六位号','width'=>100),
			'cb_money' =>array('text'=>'成本','width'=>70),
			'xsmoney' =>array('text'=>'实际销售价','width'=>100),
			// 'jqmoney' =>array('text'=>'加权销售价','width'=>100),
			'lirun'=>array('text'=>'利润','width'=>200),
		);
		$smarty->assign('arr_field_info', $arrField);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_value', $ret);
		$smarty->display('TblList.tpl');
	}
}

?>