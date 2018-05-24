<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Plan extends TMIS_Controller {

	/**
	 * 仓库主表的model实例化
	 * @var object
	*/
	var $_modelExample;

	/**
	 * 出库单对应的仓库名字
	 * 仓库大类：现货仓库/样品仓库/大货仓库
	 * @var string
	*/
	var $_kind;

	/**
	 * 订单类型
	 * 现货/样品/大货
	 * @var string
	*/
	var $_type_order;

	/**
	 * 是否需要校验
	 * @var string
	*/
	var $_is_jiaoyan = false;

	/**
     * 查询界面为了区分销售出库是那个仓库出库的
     * @var string
    */
    var $_mold;

	/**
	 * 构造函数
	 * Time：2015/09/10 13:43:51
	 * @author li
	*/
	function __construct() {
		//model
		$this->_modelJh = &FLEA::getSingleton('Model_Peihuo_Peihuo');
		$this->_modelExample = FLEA::getSingleton('Model_Cangku_Plan');
		$this->_modeChuku = FLEA::getSingleton('Model_Cangku_Chuku');
		$this->_subModel = FLEA::getSingleton('Model_Cangku_Chuku2Product');

	}


	function actionRight(){
		$this->authCheck($this->_check);
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom'=>date('Y-m-d',mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo'=>date('Y-m-d'),
			'orderCode'=>'',
			'clientId'=>'',
			'isOk'=>'',
		));
		$sql="select
			x.*,
			x.id as orderId,
			y.id as yid,
			if(c.compName='',c.compCode,c.compName) as compName
			from trade_order x
			left join trade_order2product y on x.id=y.orderId
			left join jichu_client c on c.member_id=x.clientId
			where 1 and x.is_delivery='Y'";

		$sql .=" and x.shenhe = 'yes'";
		$sql .=" and y.kind = '".$this->_type_order."'";
		if($arr['dateFrom'] !=''){
			$sql.=" and x.orderTime>='{$arr['dateFrom']} 00:00:00' and x.orderTime<='{$arr['dateTo']}  23:59:59'";
		}
		// dump($sql);exit;
		if($arr['isOk']=='y') $sql .=" and y.id in(select ord2proId from chuku_plan)";
		if($arr['isOk']=='n') $sql .=" and y.id not in(select ord2proId from chuku_plan)";
		if($arr['clientId']>0) $sql .=" and x.clientId='{$arr['clientId']}' ";
		if($arr['orderCode']!='') $sql .=" and x.orderCode like '%{$arr['orderCode']}%' ";

		$sql.="group by orderCode order by x.orderTime desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		// dump($rowset);die;
		foreach($rowset as & $v) {
			$jianhuo='';
			$bqPrint='';

			$v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];

			//---------明细显示---------\\
			$sql = "SELECT x.*,y.productId,y.cntM,y.money,y.kind,y.danjia,y.unit,y.peihuoId
				from  trade_order x
				left join trade_order2product y on x.id=y.orderId
				where x.id='{$v['orderId']}'and y.kind='$this->_type_order'";
			$rowSon=$this->_modelExample->findBySql($sql);
			// dump($rowSon);die;
			foreach ($rowSon as & $vv) {
				$v['cnt'] += $vv['cntM'];
				$v['peihuoIds'][] = $vv['peihuoId'];
			}
			$v['peihuoIds'] = join(',',$v['peihuoIds']);
			// dump($v['peihuoIds']);die;
			$v['Products'] =$rowSon;

			//---------留言显示---------\\
			$sql2 = "SELECT ms.title,ms.content
				from  trade_order_message ms
				where ms.orderId='{$v['orderCode']}'";
			$messages=$this->_modelExample->findBySql($sql2);
			foreach ($messages as  $mk=>$message) {
				$_message[$mk][] = "<span class='text-primary'>标题</span>：".$message['title'];
				$_message[$mk][] = "<span class='text-success'>内容</span>：".$message['content'];
			}
			foreach ($_message as $value1) {
				$_message1 .= implode('<br>',$value1);
				$_message1 .='<br>';
			}
			$v['messageInfo'] = "<a href='javascript:;' ext:qtip=\"{$_message1}\">留言</a>";
			//---------订单信息的显示处理---------\\
			//商品类型ss
			$v['item_type'] = $this->getTypePro($v['item_type']);

			//配货详细
			$v['peihuoIds']>0 && $v['peihuo'] = "<a href='".url('Peihuo_Peihuo','view2',array(
				'peihuoId'=>$v['peihuoIds']
			))."' title='配货单' target='_blank'>配货单</a>";
			$v['compName']="<span name='compName'>".$v['compName']."</span>";

			//其他信息
			$_detial=array();
			$_detial[] = "<span class='text-primary'>配送方式</span>：".$v['shipping'];
			$_detial[] = "<span class='text-success'>商品类型</span>：".$v['item_type'];
			$_detial[] = "<span class='text-primary'>收货地址</span>：".$v['ship_addr'];
			$_detial[] = "<span class='text-success'>收货地区</span>：".$v['area'];

			$_detial = join('<br>',$_detial);
			$v['orderCode'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$v['orderCode']}</a>";


			//查找是否已经存在出库单信息
			// $_temp = $this->_modelExample->findAll(array('orderId'=>$v['orderId']),null,null,'id,jiaoyan,print,orderId');
			$sql="SELECT * from chuku_plan where orderId='{$v['orderId']}' and kind='{$this->_kind}'";
			$_temp=$this->_modelExample->findBySql($sql);
			foreach ($_temp as &$v1) {
				$v['planId'][] =$v1['id'];
			}
			$v['planId'] = implode(',',$v['planId']);
			// $v['planId'] = $_temp['id'];

			//处理出库数量
			$v['cnt'] = round($v['cnt'],3);
			$_cnt = $v['cnt'];

			//查找已出库数量
			$_cntCk =0;
			if($v['planId']!=''){
				$sql="select sum(cntM) as cntM from cangku_chuku2product where planId in({$v['planId']})";
				$res = $this->_modelExample->findBySql($sql);
				// dump($res);
				$v['cntCk'] = round($res[0]['cntM'],2);
				$_cntCk = $v['cntCk'];
				if($v['cntCk']!=0){
					$v['cntCk']="<span class='text-danger'>{$v['cntCk']}</span>";
					$v['_bgColor']="#CDCDC1";
				}else{
					$v['cntCk']='';
				}
			}

			//---------------操作信息--------------\\
			//保存出库单或删除出库单
			if(count($_temp) > 0){
				//删除出库单
				$_isOk = "<a href='".$this->_url('Remove',array(
							'id'=>$_temp[0]['orderId'],
							'fromAction'=>$_GET['action'],
							'fromController'=>$_GET['controller'],
				))."' onclick='return confirm(\"您确认要删除吗?\")'><span class='glyphicon glyphicon-trash text-danger' ext:qtip='删除出库单'></span></a>";

				//出库单校验，判断校验的状态
				$_msg='';
				if($_temp['jiaoyan']=='yes'){
					$_msg = '已通过';
				}elseif($_temp['jiaoyan']=='no'){
					$_msg = '未通过';
				}
				$_jiaoyan = "<a href='".$this->_url('Jianyan',array(
							'id'=>$_temp[0]['orderId'],
							'jiaoyan'=>$_temp['jiaoyan'],
							'fromAction'=>$_GET['action'],
							'fromController'=>$_GET['controller'],
							'width'=>'700',
							'height'=>'200',
				))."' class='thickbox'><span class='glyphicon glyphicon-flag' ext:qtip='校验出库单'></span>{$_msg}</a>";

				//出库单打印
				$_msg='';//显示打印次数信息
				if($_temp['print']>0){
					$_msg = "({$_temp['print']})";
				}
				//校验成功或不需要校验的情况下可以打印
				if($_temp[0]['jiaoyan']=='yes' || !$v['peihuoId']>0 || $this->_is_jiaoyan == false){
					$_print = "<a href='".$this->_url('Print',array(
							'id'=>$_temp[0]['orderId'],
							'fromAction'=>$_GET['action'],
							'fromController'=>$_GET['controller'],
					))."'><span class='glyphicon glyphicon-print' ext:qtip='打印出库单'></span>{$_msg}</a>";
				}else{
					//不能打印
					$_print = "<span class='glyphicon glyphicon-print' ext:qtip='打印出库单:先校验成功才能打印出库单'></span>";
				}
				//校验成功或者不需要校验的情况下可以出库
				if($_temp[0]['jiaoyan']=='yes'  || $this->_is_jiaoyan == false){
					// dump($_cnt);dump('_cntCk is:'.$_cntCk);
					//如果是样品，增加confirm提示
					$_confirm='';
					if($_cntCk>=$_cnt) {
						$msg = "已出库数量{$_cntCk}已达到订单要求{$_cnt},禁止出库!";
						$_confirm = " onclick=\"alert('{$msg}');return false;\"";
					}
					//允许出库
					$_chuku = "<a href='".url('Cangku_'.$this->_mold.'_Xsck','Add',array(
							'id'=>$_temp[0]['orderId'],
							'fromAction'=>$_GET['action'],
							'fromController'=>$_GET['controller'],
					))."'{$_confirm}><span class='glyphicon glyphicon-log-out' ext:qtip='发货出库'></span></a>";
				}else{
					//不允许出库
					$_chuku = "<span class='glyphicon glyphicon-log-out' ext:qtip='发货出库:先校验成功才能出库'></span>";
				}
			}else{
				//保存出库单
				$_isOk = "<a href='".$this->_url('Save',array(
							'orderId'=>$v['orderId'],
							'fromAction'=>$_GET['action'],
							'fromController'=>$_GET['controller'],
				))."' ><span class='glyphicon glyphicon-ok' ext:qtip='确认并保存出库单'></span></a>";

				//出库单校验：不可操作
				$this->_is_jiaoyan && $_jiaoyan = "<span class='glyphicon glyphicon-flag' ext:qtip='出库单校验:先确认出库单'></span>";

				//出库单打印：不可操作
				$_print = "<span class='glyphicon glyphicon-print' ext:qtip='打印出库单:先确认出库单'></span>";

				//出库
				$_chuku = "<span class='glyphicon glyphicon-log-out' ext:qtip='发货出库:先确认出库单'></span>";
			}

			// 2016-3-25 by jiangxu 打印捡货单标签
		    if($this->_type_order=='现货' ){
				$jianhuo = "<a href='".$this->_url('PrintJh',array(
		                'id'=>$v['orderId'],
		                'fromAction'=>$_GET['action'],
		                'fromController'=>$_GET['controller'],
		        ))."'><span class='glyphicon glyphicon-inbox' ext:qtip='拣货单打印'></span></a>";
		    }elseif($this->_type_order=='样品' ){
		    	$jianhuo = "<a href='".$this->_url('PrintJhyp',array(
		                'id'=>$v['orderId'],
		                'fromAction'=>$_GET['action'],
		                'fromController'=>$_GET['controller'],
		        ))."'><span class='glyphicon glyphicon-inbox' ext:qtip='拣货单打印'></span></a>";
		         //打印出库单标签
		        $bqPrint = "<a href='".$this->_url('PrintYplab',array(
		                        'id'=>$v['orderId'],
		                        'fromAction'=>$_GET['action'],
		                        'fromController'=>$_GET['controller'],
		                ))."'><span class='glyphicon glyphicon-duplicate' ext:qtip='出库单常规标签'></span></a>";

		        // $v['_edit'].= "&nbsp;&nbsp;<a href='".$this->_url('PrintYplab',array(
		        //                 'id'=>$v['Ck']['id'],
		        //                 'fromAction'=>$_GET['action'],
		        //                 'printkind'=>1,
		        //                 'fromController'=>$_GET['controller'],
		        //         ))."'><span class='glyphicon glyphicon-duplicate' ext:qtip='出库单内容标签'></span></a>";
		    }

			//操作按钮处理
			$this->_is_jiaoyan == false && $_jiaoyan='';
			$v['isChecked'] = "<input type='checkbox' name='chk[]' id='chk[]' value='{$_temp['id']}'/>";
		    // dump($v['isChecked']);die;
			$v['_edit'] = $_isOk;
			if($v['status']=='active'){
				$_jiaoyan!='' && $v['_edit'] .= "&nbsp;&nbsp;".$_jiaoyan;
				$_print!='' && $v['_edit'] .= "&nbsp;&nbsp;".$_print;
				$_chuku!='' && $v['_edit'] .= "&nbsp;&nbsp;".$_chuku;
				$jianhuo!='' && $v['_edit'] .= "&nbsp;&nbsp;".$jianhuo;
				$bqPrint!='' && $v['_edit'] .= "&nbsp;&nbsp;".$bqPrint;
			}else{
				$v['_edit'] .= "&nbsp;&nbsp;订单禁止操作";
				$_print!='' && $v['_edit'] .= "&nbsp;&nbsp;".$_print;
				$jianhuo!='' && $v['_edit'] .= "&nbsp;&nbsp;".$jianhuo;
			}




			//显示订单状态
			$v['status'] = $this->getStatusOrder($v['status'] ,true);
			//支付方式
			$v['payment']=$this->getPayment($v['payment']);
			$v['payment']="<span name='payment'>".$v['payment']."</span>";

		}
		$smarty = & $this->_getView();
		$arrField = array(
			// "isChecked"=>array("text"=>"<input type='checkbox' id='checkedAll' title='全选/反选'/>","width"=>"30"),
			"_edit" => array('text'=>'操作','width'=>150),
			'orderTime' =>array('text'=>'订单时间','width'=>130),
			'orderCode'=>array('text'=>'订单编号(more)','width'=>130),
			'status'=>array('text'=>'订单状态','width'=>70),
			'cnt' =>array('text'=>'数量','width'=>70),
			'cntCk' =>array('text'=>'已出库','width'=>70),
			// 'unit' =>array('text'=>'单位','width'=>50),
			'ship_name' =>array('text'=>'收货人','width'=>70),
			'compName'=>array('text'=>'客户','width'=>170),
			'payment'=>array('text'=>'付款方式','width'=>90),
			'ship_mobile' =>array('text'=>'联系电话','width'=>100),
			'memo'=>array('text'=>'订单备注','width'=>150),
			'peihuo' =>array('text'=>'配货详细','width'=>70),
			'messageInfo'=>array('text'=>'留言','width'=>130),
		);

		$arrFieldinfo = array(
			'productId'=>array('text'=>'花型六位号','width'=>100),
			'kind'=>array('text'=>'现货/样品','width'=>70),
			// 'danjia'=>array('text'=>'单价','width'=>80),
			// 'money' =>array('text'=>'商品金额','width'=>70),
			'cntM' =>array('text'=>'数量','width'=>70),
		);

		if($this->_kind!=__CANGKU_1)  unset($arrField['peihuo']);
		$smarty->assign('title', '采购合同审核');
		$smarty->assign('arr_field_info', $arrField);
		$smarty->assign('arr_field_info2', $arrFieldinfo);
		$smarty->assign('sub_field', 'Products');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		// $smarty->assign('print_href', 'print_href');
		$smarty->assign('arr_field_value', $rowset);
		// $other_url='<button type="button" class="btn btn-info btn-sm" id="chu" name="chu">出库</button>
		// 			<button type="button" class="btn btn-info btn-sm" id="print" name="print">打印
		// 			<input type="hidden" name="mode" id="mode" value="'.$this->_mold.'"/>
		// 			<input type="hidden" name="jiaoyan" id="jiaoyan" value="'.$this->_is_jiaoyan.'"/></button>';
		// $other_url="<input type='button' id='save2' name='save2' value='打印'/>";
		$smarty->assign('other_url', $other_url);
		// $_chuku = "<a href='".url('Cangku_Xianhuo_Xsck','Add',array(
		// 					'id'=>$_temp['id'],
		// 					'fromAction'=>$_GET['action'],
		// 					'fromController'=>$_GET['controller'],
		// 			))."'><span class='glyphicon glyphicon-log-out' ext:qtip='发货出库'></span></a>";
		// $smarty->assign('_chuku', $_chuku);
		$smarty->assign('sonTpl', "Cangku/Xianhuo/xsckdan.tpl");
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr).'&nbsp;&nbsp;&nbsp;灰色背景代表已经出过货,请不要重复出货哦！');
		$smarty->display('TblListMore.tpl');
	}


	/**
	 *
	 * Time：2015/09/10 13:59:22
	 * @author li
	 * @param POST 数据表单提交post
	*/
	function actionSave(){
		// dump($_GET);exit;
		$_orderId = (int)$_GET['orderId'];
		if(!$_orderId>0){
			js_alert(
				null,
				'window.parent.showMsg("确认失败，请刷新后重新操作!")',
				$url($_POST['fromController'],$_POST['fromAction'])
			);exit;
		}

		//查找订单明细与主表信息
		// $_od2pro_model = FLEA::getSingleton('Model_Trade_Order2Product');
		// $_order2 = $_od2pro_model->find($_orderId);
		$_order_model = FLEA::getSingleton('Model_Trade_Order');
		$sql="SELECT x.status,x.shenhe,
			y.productId,y.id,
			y.orderId,y.peihuoId,
			y.cnt,y.unit,y.cntM,
			y.danjia
			from trade_order x
			left join  trade_order2product y on x.id=y.orderId
			where x.id='{$_orderId}' and y.kind='{$this->_type_order}'";
		$_order = $_order_model->findBySql($sql);
		// $_order = $_order_model->find($_orderId);

		//判断数据是否已经被保存过 2016年5月30日11:16:47 by shen
		// dump($_order);exit;
		if($_order){
			foreach ($_order as &$value) {
			$str="SELECT * from chuku_plan where ord2proId='{$value['id']}' ";
			$is_plan = $this->_modelExample->findBySql($str);
				if($is_plan){
					js_alert(null,"window.parent.showMsg('保存失败，请刷新重新操作')",$this->_url('right'));exit;
				}
			}
		}

		//判断状态，如果审核没有通过，或订单已作废，不允许保存出库单
		$_msg = '保存成功';
		$_save = true;

		if($_order[0]['shenhe']!='yes'){
			$_msg = "未审核通过，不能生成出库单";
			$_save = false;
		}
		if($_order[0]['status']!='active'){
			$_msg = "订单状态不允许生成出库单，确认后再操作";
			$_save = false;
		}

		//组织数据开始保存
		if($_save){
			foreach ($_order as  &$v) {
				$arr = array(
					'kind'=>$this->_kind,
					'productId'=>$v['productId'],
					'ord2proId'=>$v['id'],
					'orderId'=>$v['orderId'],
					'peihuoId'=>$v['peihuoId'],
					'cnt'=>$v['cnt'],
					'unit'=>$v['unit'],
					'cntM'=>$v['cntM'],
					'danjia'=>$v['danjia'],
					'planDate'=>date('Y-m-d'),
					'creater'=>$_SESSION['REALNAME'].'',
				);
				$this->_modelExample->save($arr);
			}
		}

		//跳转
		js_alert(null,"window.parent.showMsg('{$_msg}')",url($_GET['fromController'],$_GET['fromAction']));
	}

	/**
	 * 删除出库单
	 * Time：2015/09/16 16:55:43
	 * @author li
	*/
	function actionRemove(){
		$id = (int)$_GET['id'];
		//判断是否允许删除
		$sql="select count(*) as cnt from cangku_chuku2product where orderId='{$id}'";
		$res = $this->_modelExample->findBySql($sql);
		//已出库的出库单删除时提示改为弹窗提示，2015-10-16，by liuxin
		if($res[0]['cnt']>0){
			js_alert('该出库单已出库，不能删除','window.history.go(-1)');exit;
		}
		/*$_res = $this->_modelExample->find($id);
		if($_res['print']>0){
			echo "该出库单已打印出库单，表示已经出库，不能删除";exit;
		}*/

		$from = url($_GET['fromController'],$_GET['fromAction']);

		if ($this->_modelExample->removeByConditions(array('orderId'=>$id))) {
			js_alert(null,"window.parent.showMsg('成功删除')",$from);
		}

		// if ($this->_modelExample->removeByPkv($id)) {
		// 	js_alert(null,"window.parent.showMsg('成功删除')",$from);
		// }
		else js_alert('出错，不允许删除!',$from);
	}

	/**
	 * 校验：实际校验有通过，失败，未校验3中状态，模拟的时候只模拟两种状态
	 * 临时，校验需要优化，用到盘点器
	 * Time：2015/09/16 17:17:23
	 * @author li
	*/
	function actionJianyan(){
		// dump($_GET);die;
		$id = (int)$_GET['id'];
		$sql="select * from chuku_plan where orderId='{$id}' and kind='现货仓库'";
		$_info_plan = $this->_modelExample->findBySql($sql);
		// dump($_info_plan);die;


		$str="select * from chuku_plan where orderId='{$id}' and jiaoyan<>'yes'";
		$cnt = $this->_modelExample->findBySql($sql);
		// dump($cnt);die;

		// if(empty($cnt)){
		// 	$_info_plan['jiaoyan_res'] = '成功';
		// }elseif(count($cnt)>0){
		// 	$_info_plan['jiaoyan_res'] = '失败';
		// }

		// if($_info_plan['jiaoyan']=='yes'){
		// 	$_info_plan['jiaoyan_res'] = '成功';
		// }elseif($_info_plan['jiaoyan']=='no'){
		// 	$_info_plan['jiaoyan_res'] = '失败';
		// }

		// dump($_info_plan);exit;
		$smarty = & $this->_getView();
		$smarty->assign('Plan', $_info_plan);
		$smarty->display('Cangku/ChukuPlan.tpl');
	}

	/**
	 * ps ：读取用户上传文件
	 * Time：2015/10/27 14:34:40
	 * @author liuxin
	 * @param $_FILES 文件
	 * @return json
	*/
	function actionExportJianyan()
	{
	 	$filename = $_FILES['myFile']['tmp_name'];
		$fp = fopen($filename, "r");
		if (!$fp) {
			echo json_encode(array('success'=>false,'msg'=>'打开文件失败！'));
			fclose($fp);exit;
		}
		$data = array();$k = 0;
		while (!feof($fp)) {
			$arr = explode(" ",fgets($fp));
			if(!$arr[0]) continue;
			$data[] = $arr[0];
			unset($arr);
		}
		if ($data[0] == "") {
			echo json_encode(array('success'=>false,'msg'=>'未找到正确的条码'));
			fclose($fp);exit;
		}
		fclose($fp);
		$jsonData[0] = implode(",",$data);
		echo json_encode(array('success'=>true,'data'=>$jsonData));exit;
	}

	/**
	 * 处理
	 * Time：2015/09/17 19:23:31
	 * @author li
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionDoJianyan(){
		// dump($_POST);exit;
		$id = (int)$_POST['id'];
		//处理条码信息
		$_qrcodes = explode(',',$_POST['qrcodes']);
		$_qrcodes = array_filter($_qrcodes);//去除空数据
		foreach ($_qrcodes as $key => & $v) {
			$v = "'{$v}'";
		}
		$qrcode = join(',',$_qrcodes);
		if($qrcode==''){
			echo json_encode(array('success'=>false,'msg'=>'没有有效条码数据，不能校验'));exit;
		}

		//查找出库单信息
		// $aRow = $this->_modelExample->find($id);
		$sql="select * from chuku_plan where orderId='{$id}' and kind='现货仓库'";
		$aRow = $this->_modelExample->findBySql($sql);
		// dump($aRow);exit;

		if(!$aRow[0]['peihuoId']>0){
			echo json_encode(array('success'=>false,'msg'=>'没有配货单，不需要校验'));exit;
		}
		foreach ($aRow as &$value) {
			//查找该出库单下配货单的所有码单信息 不校验状态为finish的码单
			$sql="SELECT group_concat(madanId) as madanId
				from ph_peihuo2madan x
				left join madan_db y on x.madanId=y.id
				where x.phId = '{$value['peihuoId']}' and y.status<>'finish'";
			$_madanId = $this->_modelExample->findBySql($sql);
			$madan[]=$_madanId[0]['madanId'];
		}
		$madan = array_filter($madan);//去除空数据
		$_madanId=join(',',$madan);
		// $_madanId = $_madanId[0]['madanId'];
		// dump($_madanId);exit;
		if(!$_madanId){
			echo json_encode(array('success'=>false,'msg'=>'配货单没有对应的码单信息，无法校验'));exit;
		}

		//查找码单是否有遗漏
		$_sql="select id from madan_db where qrcode not in($qrcode) and id in ($_madanId)";
		$res = $this->_modelExample->findBySql($_sql);
		// dump($res);die;
		if(count($res)>0){
			$arr = array();
			foreach($res as $k=>&$v) {
			 $arr[] = $v['id'];
			}
			$miss_madanId=join(',',$arr);
			$_sql="select productId,rollNo from madan_db where  id in ($miss_madanId)";
			$miss_madans = $this->_modelExample->findBySql($_sql);
			foreach($miss_madans as $k=>&$vv) {
			 $miss_madan[] = '编号:'.$vv['productId'].','.$vv['rollNo'];
			}
			$miss_madandetail=join(',',$miss_madan);
			$_jiaoyan = 'no';
			$text = "失败";
			$_msg.= $miss_madandetail.'码单没有校验到，有遗漏';
			unset($res);
			unset($miss_madandetail);
			unset($miss_madanId);
			unset($miss_madanIds);
		}else{
			$_jiaoyan = 'yes';
			$text = "成功";
			$_msg .= '校验结束';
		}

		//查找校验是否有多出，就是不在配货单中，但是却校验了
		//2016年3月15日 15:22:52 瓯堡决定不考虑这种情况先
		// $_sql="select id,qrcode from madan_db where qrcode in($qrcode) and id not in ($_madanId) and status<>'finish'";
		// $res = $this->_modelExample->findBySql($_sql);
		// if(count($res)>0){
		// 	$_jiaoyan = 'no';
		// 	$text = "失败";
		// 	$_msg.= '<br>输入的条码中有不属于这个配货单中的数据，请确认';
		// }
		foreach ($aRow as &$value) {
			$arr =array(
				'id'=>$value['id'],
				'jiaoyan'=>$_jiaoyan.'',
				'jiaoyanDetial'=>$qrcode,
			);
			$this->_modelExample->update($arr);
		}

		// $arr =array(
		// 	'id'=>$id,
		// 	'jiaoyan'=>$_jiaoyan.'',
		// 	'jiaoyanDetial'=>$qrcode,
		// );

		// dump($arr);exit;
		// $this->_modelExample->update($arr);
		echo json_encode(array('success'=>true,'msg'=>$_msg,'status'=>$_jiaoyan,'text'=>$text));
	}

	/**
	 * 打印出库单
	 * 打印功能暂时未做，只是临时搭的
	 * Time：2015/09/16 18:00:40
	 * @author chen
	*/
	// function actionPrint(){

	// }

	function actionPrint(){
		$ids = $_GET['id'];
	    //TODO
		// 1.将ids分解成id数组
		// $idArr = explode(',',$ids);

        // 2.判断是否允许打印
        $sql="SELECT * from chuku_plan where orderId='{$ids}' and kind='现货仓库'";
		$_res = $this->_modelExample->findBySql($sql);
		foreach ($_res as &$v) {
			// 打印前验证- 出库单校验
			if($v['jiaoyan']!='yes'&&$this->_is_jiaoyan==true){
				echo "先校验成功才能打印出库单！";exit;
			}
		}

		// 3.过滤或拼接sql中使用的ids
		// $ids = count($idArr)>0?$ids:"''";

		// $sql="SELECT
		// 	x.payment,
		// 	x.ship_name,
		// 	x.ship_addr,
		// 	x.ship_mobile,
		// 	x.ship_tel,
		// 	x.memo,
		// 	c.compName,
		// 	p.proCode,
		// 	p.proName,
		// 	p.menfu,
		// 	p.kezhong,
		// 	p.color,
		// 	z.id as sid,
		// 	sum(d.cnt)as cnt,
		// 	d.peihuoId,
		// 	d.id as Did
		// 	from trade_order x
		// 	left join trade_order2product z on z.orderId=x.id
		// 	left join chuku_plan d on d.ord2proId=z.id
		// 	left join jichu_client c on c.member_id=x.clientId
		// 	left join jichu_product p on p.proCode=z.productId
		// 	where d.id IN({$ids}) group by p.proCode";
			//where d.id='{$_GET['id']}'"; GROUP_CONCAT(x.memo SEPARATOR  ' ') AS memo,

		$sql="SELECT
			x.id,
			x.orderCode,
			x.payment,
			x.ship_name,
			x.ship_addr,
			x.ship_mobile,
			x.ship_tel,
			x.memo,
			c.compName,
			p.proCode,
			p.proName,
			p.menfu,
			p.kezhong,
			p.color,
			sum(d.cnt)as cnt
			from trade_order x
			left join trade_order2product z on z.orderId=x.id
			left join chuku_plan d on d.ord2proId=z.id
			left join jichu_client c on c.member_id=x.clientId
			left join jichu_product p on p.proCode=z.productId
			where x.id='{$ids}' and z.kind='{$this->_type_order}' group by p.proCode";
		$rowset=$this->_modelExample->findBySql($sql);
		$_modeChuku = FLEA::getSingleton('Model_Cangku_Chuku');

		foreach ($rowset as & $vv) {
			//支付方式
			$v['payment'] = $this->getPayment($vv['payment']);
			//
			if($vv['ship_mobile']==''){
				$vv['ship_mobile']=$vv['ship_tel'];
			}
			//当注释为空时，将，替换掉不显示
			// $vv['memo']=str_replace(',','',$vv['memo']);
			$vv['cnt'] = round($vv['cnt'],2);
	        $sql="SELECT count(x.rollNo)as rollNo
	        	from madan_db x
	        	left join ph_peihuo2madan y on x.id=y.madanId
	        	left join ph_peihuo z on y.phId=z.id
	        	left join chuku_plan a on a.peihuoId=z.id
	        	where a.orderId ='{$ids}' group by a.productId";
	        $ret=$this->_modelExample->findBySql($sql);
	        foreach ($ret as $key => &$value) {
	        	$rowset[$key]['rollNo']=$value['rollNo'];
	        }
	        $vv['guige'] = $vv['menfu'].' '.$vv['kezhong'].' '.$vv['color'].' '.$vv['chengfen'];
		}
		// dump($rowset);die;
		$rowset[0]['people']=$_SESSION['REALNAME'];
		$rowset[0]['payment']=$v['payment'];
		$rowset[0]['time']=date('y-m-d h:i:s',time());
		$rowset[0]['chukuDate']=date('y-m-d',time());
		$heji=$this->getHeji($rowset,array('rollNo','baleNo','cnt'));
		$smarty = & $this->_getView();
		$smarty->assign('rowset', $rowset);
		$smarty->assign('heji',$heji);
		$smarty->display("Cangku/Print.tpl");
	}
	/**
	 * 样品标签打印打印
	 * Time：2016年3月25日10:09:11
	 * @author jiangxu
	 */

    function actionPrintYplab(){
		$ids = $_GET['id'];
		$sql= "select a.*,b.cnt,b.productId,c.compName
			from jichu_product a
			left join trade_order2product b on a.proCode = b.productId
			left join trade_order x on x.id = b.orderId
			left join jichu_client c on x.clientId = c.member_id
			where b.orderId='{$_GET['id']}'";
        $temp=$this->_subModel->findBySql($sql);
        foreach ($temp as $k => $v) {
        	$arr[$k]['productId']=$v['productId'];
        	$arr[$k]['jingmi']=$v['jingmi'];
        	$arr[$k]['weimi']=$v['weimi'];
        	$arr[$k]['shazhi']=$v['shazhi'];
        	$arr[$k]['zhengli']=$v['zhengli'];
        	$arr[$k]['menfu']=$v['menfu'];
        	$arr[$k]['kezhong']=$v['kezhong'];
        	$arr[$k]['chengfen']=$v['chengfen'];
        	$arr[$k]['cnt']=$v['cnt'];
        	$arr[$k]['dt']=date('Y-m-d h:i:s');
        	$arr[$k]['compName']=$v['compName'];
        }
        // dump($arr);exit;
        $smarty = & $this->_getView();
		$smarty->assign('row', $arr);
        $smarty->display('Cangku/PrintYplab.tpl');

    }
	/**
	 * 拣货单打印
	 * Time：2016年3月2日13:31:25
	 * @author jiangxu
	 */

	function actionPrintJh(){
		//根据后台传输的orderId
		$ids = $_GET['id'];
		$sql= "select a.*,d.orderCode
			from madan_db a
			left join ph_peihuo2madan y on y.madanId=a.id
			left join ph_peihuo x on x.id=y.phId
			left join trade_order2product c on x.id =c.peihuoId
			left join trade_order d on d.id=c.orderId
			where c.orderId='{$_GET['id']}'";
        $temp=$this->_modelJh->findBySql($sql);
        foreach ($temp as &$v) {
			$kuqu=$v['kuqu'];
			$arr[$kuqu][]=array(
				"millNo"=>$v['millNo'],
				"cnt"=>$v['cnt'],
				"rollNo"=>$v['rollNo'],
				"qrcode"=>$v['qrcode'],
				"memo"=>$v['memo'],
				"unit"=>$v['unit'],
	        	'orderCode'=>$v['orderCode'],
				);
	     }
	    foreach ($arr as  &$value) {
	     	$value[0]['cntJian'] =count($value);
	     	foreach ($value as &$v1) {
		     	$value[0]['cntSum'] +=$v1['cnt'];
	     	}
	     }
	     // dump($value);exit;
	    foreach ($arr as &$v2) {
	     	$arr[$kuqu][0]['zongM'] += $v2['0']['cntSum'];
	     	$arr[$kuqu][0]['zongJ'] += $v2['0']['cntJian'];

	     }
	     // dump($arr);exit;
		$time=date('Y-m-d H:i:s');
		$smarty = & $this->_getView();
		$smarty->assign('time', $time);
		$smarty->assign('arr', $arr);
		$smarty->assign('temp', $temp);
        $smarty->display('Cangku/PrintJhd.tpl');

	}


	/**
	 * 拣货单打印
	 * Time：2016年3月2日13:31:25
	 * @author jiangxu
	 */

	function actionPrintJhyp(){
		$sql= "SELECT y.*,x.orderCode
			from  trade_order x
			left join trade_order2product y on x.id=y.orderId
			where x.id='{$_GET['id']}'";
        $temp=$this->_modelJh->findBySql($sql);
        // dump($temp);exit;
        foreach ($temp as $k => $v) {
        	$arr[$k]['productId']=$v['productId'];
        	$arr[$k]['cnt']=$v['cnt'];
        	$arr[$k]['unit']=$v['unit'];
        	$arr[$k]['orderCode']=$v['orderCode'];
        }
        $cntJian=count($arr);
		$hj=$this->getHeji($temp,array('cnt'));
		$time=date('Y-m-d H:i:s');
		$smarty = & $this->_getView();
		$smarty->assign('time', $time);
		$smarty->assign('arr', $arr);
		$smarty->assign('hj', $hj);
		$smarty->assign('cntJian',$cntJian);
        $smarty->display('Cangku/PrintYpJhd.tpl');
	}



	/**
	 * 打印出库单后需要同步已发货到ec
	 * 打印出库单需要考虑全部发货和部分发货
	 * 打印出库单后需要同步打印次数到出库单表
	 * Time：2015/09/16 18:07:46
	 * @author li
	*/
	function actionUpdatePrint(){
		$id = $_GET['id'];
		$id!='' && $this->_modelExample->execute("update chuku_plan set print = print+1 where id in({$id})");

		// $_res = $this->_modelExample->find($id);

		//查找订单号
		// $order = $this->_modelExample->findBySql("select orderCode from trade_order where id='{$_res['orderId']}'");
		// dump($order);exit;
		// $this->apiChukuToEc($order[0]['orderCode'],1);
	}

	/**
	 * 打印出库单后需要同步已发货到ec
	 * Time：2015/09/16 18:07:46
	 * @author li
	*/
	/*function apiChukuToEc($orderCode,$status){
		$obj_api = FLEA::getSingleton('Api_Request');
        $r = $obj_api->callApi(array(
            'method'=>'apioubao.erp.response.change_ship_status',
            'params'=>array(
            	'order_id'=>$orderCode,
            	'status'=>$status,
        	)
        ));
        $ret = json_decode($r);
        // dump($ret);exit;
	}*/

	/**
	 * 待校验 已经确认 但没有校验
	 * Time：2015-10-26 19:52:16
	 * @author shen
	*/
	function actionJiaoyan(){
		$this->authCheck('3-2-6') and $this->authCheck('3-1-6');
		FLEA::loadClass('TMIS_Pager');
		$sql="SELECT x.*,c.orderCode,c.orderTime,c.status,c.ship_name,a.compName,
			z.proCode,z.proName,z.wuliaoKind,z.zuzhi,z.zhengli,z.menfu,z.kezhong
			from chuku_plan x
			left join trade_order2product y on x.ord2proId=y.id
			left join trade_order c on c.id=y.orderId
			left join jichu_product z on z.proCode=x.productId
			left join jichu_client a on a.member_id=c.clientId
			where jiaoyan='' and x.kind='现货仓库'";
		$sql.=" order by orderTime desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		foreach ($rowset as $k => &$v) {
			//显示订单状态
			$v['status'] = $this->getStatusOrder($v['status'] ,true);
		}
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			'orderTime' =>array('text'=>'订单时间','width'=>130),
			'status'=>array('text'=>'订单状态','width'=>70),
			'proCode'=>array('text'=>'花型六位号','width'=>100),
			'proName'=>array('text'=>'品名','width'=>70),
			'wuliaoKind'=>array('text'=>'物料大类','width'=>80),
			'zuzhi' =>array('text'=>'组织大类','width'=>80),
			'zhengli' =>array('text'=>'整理方式','width'=>80),
			'menfu' =>array('text'=>'门幅','width'=>70),
			'kezhong' =>array('text'=>'克重','width'=>70),
			'cnt' =>array('text'=>'数量','width'=>70),
			'cntCk' =>array('text'=>'已出库','width'=>70),
			'unit' =>array('text'=>'单位','width'=>50),
			'ship_name' =>array('text'=>'收货人','width'=>70),
			'compName'=>array('text'=>'客户','width'=>70),
			'orderCode'=>array('text'=>'订单编号(more)','width'=>130),
		);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'])));
		$smarty->display('TblList.tpl');

	}
}

?>