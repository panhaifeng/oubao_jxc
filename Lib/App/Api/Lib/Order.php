<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Order.php
*  Time   :2014/05/13 18:31:40
*  Remark :订单相关api
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_Lib_Order extends Api_Response {

	/**
	 * 保存订单的接口
	 * Time：2015/09/14 20:15:28
	 * @author li
	 * @param Order：订单主要信息，Pro订单明细信息，Peihuo配货单信息
	 * @return 返回值类型
	   --------------- 需要接口和说明 $params['Order'] ---------------
	   orderCode：订单号，
	   money：订单需要支付的总金额
	   money_order：该单商品的价值金额：该金额不一定等于应付款
	   pmt_goods：商品优惠金额
	   pmt_order：订单优惠金额
	   pay_status：付款状态
	   ship_status：发货状态
	   is_delivery：是否需要发货
	   orderTime：下单时间
	   payment：支付方式
	   clientId：会员用户名：会员主键
	   traderId：业务员：根据客户查找业务员
	   currency：订单支付货币
	   shipping：配送方式
	   ship_name：收货人
	   ship_addr：收货地址
	   ship_zip：收货人邮编
	   ship_tel：收货电话
	   ship_email：收货人email
	   ship_mobile：收货人手机
	   ship_area：收货地区
	   is_tax：是否要开发票[true/false]
	   tax_type：发票类型['false','personal','company']
	   tax_content：发票内容
	   cost_tax：订单税率
	   tax_company：发票抬头
	   memo：备注
	   cur_rate：汇率
	   status：订单状态['active','dead','finish']
	   cost_freight：配送费用

	   ------------------- Pro ----------------
	   productId:货号，注意，应该是处理过的产品编号，不是最原始的货号
	   danjia：明细商品单价,可能有了折扣
	   danjia_p：该商品当时出售原价
	   cnt：明细商品购买数量
	   unit：单位
	   kind：销售类型：现货/样品
	   spec_info：其他描述
	   bn：货号全称，和ec订单明细中的货号保持一致
	   madan：码单信息【1,2,3,4,5,6传递码单id就可以了】
	   item_type：明细商品类型：商品，赠品等['product','pkg','gift','adjunct']
	*/
	function create($params) {
		$orderCode = $params['orderCode'];
		if(!$orderCode) {
			return array('success'=>false,'msg'=>'订单号不合法');
		}
		//以下是业务逻辑代码,注意，如果不使用try,程序会中断，导致api无反应，这会严重影响调试效率
		__TRY();

		//实例化订单的model
		$_model = FLEA::getSingleton('Model_Trade_Order');
		$_modelSon = FLEA::getSingleton('Model_Trade_Order2Product');
		$_model_peihuo = FLEA::getSingleton('Model_Peihuo_Peihuo');
		$_modelClient = FLEA::getSingleton('Model_Jichu_Client');
		$_modelOa = FLEA::getSingleton('Model_OaMessage');

		//先查找是否已经存在该订单，如果存在，找到订单id，更新订单信息
		$order_id = $_model->find(array('orderCode'=>$orderCode),null,'id');
		//订单主表id
		$_order = $params['Order'];
		// $_order['id'] = $order_id;
		//发票问题:如果需要开票，但是开票类型有问题，需要提示错误，需要还原出现这种情况的场景，无法还原，但是数据表中出现了这个问题：限制无法保存在正式环境之前，正式安装后这段代码可以注释掉

		/*if($_order['is_tax'] == 'true' && $_order['tax_type']=='false'){
		  $_order['tax_type'] = 'company';

		  $_time = strtotime('2015-11-05');
		  if(time()<$_time){
			return array('success'=>false,'msg'=>'该订单是要开票的，但是开票类型为不开票，这个问题需要反馈给程序员，请留意出现问题的场景');exit;
		  }
		}*/
		//限制结束

		$_order['Products'] = $params['Pro'];
		if(!count($_order['Products'])>0){
			return array('success'=>false,'msg'=>'没有有效订单产品信息，请确认后再尝试');exit;
		}
		if($order_id['id']==0){
		  	//查找是否传过来的码单已经有锁定状态的，如果存在不能保存
			//查找码单是否已经被锁定，如果已经锁定，不能下单成功，需要提示信息
			$arr_madan_order = array_col_values($_order['Products'],'madan');
			foreach ($arr_madan_order as $key => & $v) {
			  	$v = trim($v);
			}
			$arr_madan_order = array_filter($arr_madan_order);
			$_madan_all_str = join(',',$arr_madan_order);

			if($_madan_all_str!=''){
			  //2015-11-4 by jiang 码单为finish的一定不可以购买
			  $sqlma="select count(id) as cnt from madan_db
					where status='finish' and id in({$_madan_all_str})";
			  $tempma = $_model_peihuo->findBySql($sqlma);
			  if($tempma[0]['cnt']>0){
				 return array('success'=>false,'msg'=>'码单已锁定,请重新选择');exit;
			  }

			  //查找码单是否已经被下订单并配货
			  $sql="select count(x.id) as cnt
			  from ph_peihuo x
			  inner join ph_peihuo2madan y on y.phId=x.id
			  where x.status!='dead' and y.madanId in ({$_madan_all_str})";
			  // $sql="SELECT count(id) as cnt from madan_db where id in ($_madan_all_str) and status = 'lock'";
			  $temp = $_model_peihuo->findBySql($sql);
			  if($temp[0]['cnt']>0){
				return array('success'=>false,'msg'=>'配货单选择的码单已经被其他会员购买并支付，请重新选择配货单');exit;
			  }
			}

			//处理地区，现在的地区里面有很多不要的数据，在进销存中处理其实不合理，暂时放在这里
			list($mainland ,$area ,$num) = explode(':',$_order['ship_area']);
			$_order['area'] = $area;

			//处理订单时间：默认不能为空
			$_order['orderTime']=='' && $_order['orderTime']=date('Y-m-d H:i:s');

			//根据客户查找业务员
			$_retClient=$_modelClient->find(array('member_id'=>$_order['clientId']));
			$_order['traderId']=$_retClient['traderId']+0;

			$_peihuo = array();//处理配货单，配货单需要保存在配货单数据表中
			// $_madan_all = array();
			foreach ($_order['Products'] as $key => & $v) {
				//查找是否已经存在
				$_temp = $_modelSon->find(array(
					'productId'=>$v['productId'],
					'orderId'=>$_order['id']
				  ), null, 'id,peihuoId');
				$v['id'] = $_temp['id'];
				// $v['unit']=='' && $v['unit']='M';//默认不能为空
				if (!trim($v['unit'])) {
					$v['unit'] = 'M';
				}
				$v['cntM'] = $v['unit'] == "M" ? $v['cnt'] : $v['cnt']*0.9144;

				//
				$v['madan'] = trim($v['madan']);
				$_madan = explode(',',$v['madan']);
				// $_madan_all[]=$v['madan'];
				$madan = array();
				foreach ($_madan as & $m) {
					if(!$m)continue;
					$madan[]=array('madanId'=>$m);
				}

				if(count($madan)>0){
					$_peihuo = array(
						'id'=>$_temp['peihuoId']+0,
						'status_active'=>'已下单',
						'peihuoDate'=>$_order['orderTime'],
						'cntM'=>$v['cntM'],
						'productId'=>$v['productId'],
						'clientId'=>$_order['clientId'],
						'Peihuo'=>$madan
					);

					//配货单编号
					if($_peihuo['id']==0){
						$_peihuo['peihuoCode'] = $_model_peihuo->getNewCode();
					}

					$_id = $_model_peihuo->save($_peihuo);
					//配货id信息放在订单明细中
					$v['peihuoId'] = $_peihuo['id']>0 ? $_peihuo['id'] : $_id;
				}
			}
		  	$_order['id'] = '';
		  	//开始保存订单信息
			$_model->save($_order);

			//保存订单信息到通知表中
			$arr_tongzhi=array(
			  'kindName' => '订单',
			  'title' => $_order['orderCode'],
			  'orderId' => $_order['orderCode'],
			  );
			$_modelOa->save($arr_tongzhi);

			//锁定码单状态
			// $_madan_all = array_filter($_madan_all);
			// $_madan_all_str = join(',',$_madan_all);
			$_madan_all_str!='' && $_model->execute("update madan_db set status='lock' where id in ({$_madan_all_str})");

			//捕获异常，如果sql语句非法会抛出异常
			$ex = __CATCH();
			if (__IS_EXCEPTION($ex)) {
				return array('success'=>false,'msg'=>$ex->getMessage());
			}
		}
		return array('success'=>true,'data'=>'已同步完成');
	}

	function messageCreate($params) {
		$orderCode = $params['orderCode'];
		//实例化订单的model
		$mdlMessage = FLEA::getSingleton('Model_Trade_OrderMessage');
		$message = array(
			'orderId'=>$orderCode,
			'clientId'=>$params['message']['clientId'],
			'title'=>$params['message']['title'],
			'content'=>$params['message']['content'],
		);
		$mdlMessage->save($message);
		return array('success'=>true,'data'=>'已同步完成');
	}
	//是否允许删除订单
	function removeable($params) {
		$orderId = $params['orderId'];
		if(!$orderId) {
			return array('success'=>false,'msg'=>'参数中的orderId必须>0');
		}
		//以下是具体的业务逻辑代码

		return array('success'=>true,'data'=>true);
	}

	/**
	 * @desc ：根据orderId得打订单信息
	 * @author jeff 2015/09/14 19:07:14
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function getOrderById($params) {
		$orderId = $params['orderId'];
		if(!$orderId) {
			return array('success'=>false,'msg'=>'参数中的orderId不能为空');
		}
		__TRY();
		$model = FLEA::getSingleton('Model_Jichu_Client');
		$row = $model->find(array('orderCode'=>$orderId));
		if(!$row) {
			return array('success'=>false,'msg'=>'未发现相匹配的订单');
		}

		//捕获异常，如果sql语句非法会抛出异常
		$ex = __CATCH();
		if (__IS_EXCEPTION($ex)) {
			return array('success'=>false,'msg'=>$ex->getMessage());
		}
		return array('success'=>true,'data'=>$row);
	}
	/**
	 * ps ：根据ec后台内容传值给进销存
	 * Time：2016年3月31日12:51:41
	 * @author jiangxu
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function settag($params=array()){
		$model = FLEA::getSingleton('Model_Trade_Order');
		// dump($params['tag_name']);die;
		foreach ($params['orderCode'] as & $v) {
		  $row[] = $model->find(array('orderCode'=>$v));
		  foreach ($row as &$vv){
			$arr= array(
			  'id'=>$vv['id'],
			  'tag_name' =>$params['tag_name'],
			);
			$model->update($arr);
		  }
		}
		if(!$row) {
		   return array('success'=>false,'msg'=>'未发现相匹配的订单');
		}
		   return array('success'=>true,'data'=>true);
	}

	/**
	 * 取消发货功能
	 * Time：2015/10/08 18:41:50
	 * @author li
	*/
	function isdelivery($params = array()){
	  $orderCode = $params['orderCode'];
	  $isdelivery_desc = $params['isdelivery_desc'];
	  $_success = true;
	  if(!$orderCode) {
		  return array('success'=>false,'msg'=>'参数中的orderCode不能为空');
	  }

	  __TRY();

	  $model = FLEA::getSingleton('Model_Trade_Order');

	  $row = $model->find(array('orderCode'=>$orderCode));
	  if(!$row) {
		return array('success'=>false,'msg'=>'未发现相匹配的订单');
	  }

	  //查找当前该订单的状态
	  //如果订单已经发货货打印发货单，需要客服通过电话通知仓库取消出库单
	  $sql="select sum(print) as print from chuku_plan where orderId = '{$row['id']}'";
	  $res = $model->findBySql($sql);
	  $isPrint = $res[0]['print']>0;//是否打印过

	  //是否已经发货
	  $sql="select count(*) as cnt from cangku_chuku2product where orderId = '{$row['id']}'";
	  $res = $model->findBySql($sql);
	  $isFahuo = $res[0]['cnt']>0;//是否打印过

	  //处理信息
	  if($isPrint){
		$_msg = "发货单已打印，不能取消，请紧急电话通知仓库人员停止发货！仓库人员删除出货单后才能取消发货";
		$_success = false;
	  }
	  if($isFahuo){
		$_msg = "已发货，不能取消发货";
		$_success = false;
	  }

	  if($_success==false){
		$_msg=='' && $_msg='不能取消发货，请联系仓库人员了解进度';
		return array('success'=>false,'msg'=>$_msg);
	  }

	  //开始
	  $arr = array(
		'id'=>$row['id'],
		'isdelivery_desc'=>$isdelivery_desc,
		'is_delivery'=>'N',
	  );

	  $model->update($arr);

	  //2015-10-28 by jiang 如果选择了大货的配货单  码单的状态应该是不需要改变的
	  //只改变配货单的状态
	  $_dapeihuos = join(',',array_col_values($row['Products'],'peihuoCodes'));

	  //取消发货需要将现货的配货单变为dead
	  $_peihuoIds = join(',',array_col_values($row['Products'],'peihuoId'));
	  $sql="update ph_peihuo set status='dead' where id in({$_peihuoIds})";
	  $model->execute($sql);
	  $_dapeihuos=trim($_dapeihuos);//去空格
	  $_peihuoIds=trim($_peihuoIds);//去空格
	  $_dapeihuos=trim($_dapeihuos,',');//去逗号
	  $_peihuoIds=trim($_peihuoIds,',');//去逗号
	  //增加判断 先不改动代码 2016年3月31日 10:38:57 by shen
	  $_peihuos = str_replace(',','',$_dapeihuos);
	  $_peihuos=trim($_peihuos);//去空格

	  if($_peihuos==''){
		//取消发货需要解除锁定码单信息，提供其他人可以继续买
		if($_peihuoIds!=''){
			$_modelMadan = FLEA::getSingleton('Model_Cangku_Madan');
			//查找配货单对应的所有码单信息
			$sql="select madanId from ph_peihuo2madan where phId in ({$_peihuoIds})";
			$temp = $_modelMadan->findBySql($sql);

			$madanIds = join(',',array_col_values($temp,'madanId'));
			if($madanIds!=''){
			  //更新条件
			  $_condition = " id in ({$madanIds}) and status='lock'";
			  //更新影响行数
			  $_modelMadan->updateField($_condition ,'status' ,'active');
			}
		}
	  }else{
		//将大货的配货单改为active
		$_pcode=explode(',',$_dapeihuos);
		foreach ($_pcode as $key => & $v) {
			$peihuoCodes[] = "'{$v}'";
		}
		$peihuoCode=join(',',$peihuoCodes);
		if($peihuoCode!=''){
		  $sql="update ph_peihuo set status='active' where peihuoCode in({$peihuoCode})";
		  $model->execute($sql);
		  //获取影响行数
		  $_affected_rows=mysql_affected_rows();
		}

	  }

	  //捕获异常，如果sql语句非法会抛出异常
	  $ex = __CATCH();
	  if (__IS_EXCEPTION($ex)) {
		  return array('success'=>false,'msg'=>$ex->getMessage());
	  }

	  return array('success'=>true,'msg'=>'已取消发货成功');
	}

	/**
	 * 改变状态：
	 *1、订单完成
	 * Time：2015/10/09 18:02:06
	 * @author li
	*/
	function statuschange($params = array()){
	  $orderCode = $params['orderCode'];
	  $status = $params['status'];
	  $order_status = array('active','dead','finish');
	  if(!$orderCode) {
		return array('success'=>false,'msg'=>'参数中的orderCode不能为空');
	  }

	  if(!in_array($status,$order_status)) {
		return array('success'=>false,'msg'=>'参数中的status不能为空，且必须为active/dead/finish');
	  }

	  __TRY();

	  $model = FLEA::getSingleton('Model_Trade_Order');

	  $row = $model->find(array('orderCode'=>$orderCode));
	  //by 张艳 当没有支付的时候没有生成订单，所以不能这么写，该处要改动
	  /*if(!$row) {
		return array('success'=>false,'msg'=>'未发现相匹配的订单');
	  }*/

	  //有效性验证
	  //查找是否打印发货单，是否校验，是否发货完成
	  $sql="select sum(print) as print from chuku_plan where orderId='{$row['id']}'";
	  $res = $model->findBySql($sql);
	  $isPrint = $res[0]['print']>0;//是否打印过


	  //是否已经发货
	  $sql="select count(*) as cnt,group_concat(planId) as planId from cangku_chuku2product where orderId = '{$row['id']}'";
	  $res = $model->findBySql($sql);
	  $isFahuo = $res[0]['cnt']>0;
	  $planId = $res[0]['planId'];

	  //查找是否有已经校验但是还没有发货的出库单
	  $planId == '' && $planId = "''";
	  $sql="select id from chuku_plan where orderId = '{$row['id']}' and jiaoyan='yes' and id not in($planId)";
	  $res = $model->findBySql($sql);
	  $is_wfh = count($res)>0;

	  //完成状态：如果已打印发货单，还没有发货，需要提示通知仓库禁止发货
	  if($status == 'finish'){
		$_msg = '';
		if($is_wfh){
		  $_msg = "存在已校验但是还没有发货的出库单，如果不需要发货，请紧急联系仓库人员停止发货";
		}

		if($_msg!=''){
		  return array('success'=>false,'msg'=>$_msg);
		}

		if($row['is_delivery'] != 'N'){
		  //2015-10-28 by jiang 订单完成时 配货单也要标记完成
		  $_peihuoIds = join(',',array_col_values($row['Products'],'peihuoId'));
		  $sql="update ph_peihuo set status='finish' where id in({$_peihuoIds})";
		  $model->execute($sql);
		}
	  }
	  //作废状态：如果打印发货单未发货，需要提醒不能作废
	  if($status == 'dead'){
		$_msg = '';
		if($isPrint){
		  $_msg = "已打印发货单，请先联系仓库人员删除发货单才能作废";
		}
		if($isFahuo){
		  $_msg = "已发货，不能作废";
		}

		if($_msg!=''){
		  return array('success'=>false,'msg'=>$_msg);
		}

		//取消订单成功后需要对码单解锁
		//获取要解除锁定的码单信息
		$sql="select group_concat(z.madanId) as madanId
		  from trade_order2product x
		  left join ph_peihuo y on x.peihuoId=y.id
		  left join ph_peihuo2madan z on z.phId=y.id
		  where x.orderId='{$row['id']}'";

		$_modelMadan = FLEA::getSingleton('Model_Cangku_Madan');
		$temp = $_modelMadan->findBySql($sql);
		$madanIds = $temp[0]['madanId'];
		// dump($madanIds);exit;
		//更新条件
		$_condition = " id in ({$madanIds})";
		//更新影响行数
		$_modelMadan->updateField($_condition ,'status' ,'active');

		//2015-10-27 by jiang 如果有大货的配货单 则取消配货单
		$peihuoCodes='';
		foreach($row['Products'] as & $r){
			if($r['peihuoCodes']){
				$_pcode=explode(',',$r['peihuoCodes']);
				foreach ($_pcode as & $p) {
					$peihuoCodes[]="'{$p}'";
				}
			}
		}
		if($peihuoCodes){
			$_peihuo=join(',',$peihuoCodes);
			$sql="update ph_peihuo set status='active' where peihuoCode in($_peihuo)";
			$_modelMadan->findBySql($sql);
		}
	  }
	  //修改订单状态
	  $arr = array(
		'id'=>$row['id'],
		'status'=>$status
	  );

	  $model->update($arr);

	  //捕获异常，如果sql语句非法会抛出异常
	  $ex = __CATCH();
	  if (__IS_EXCEPTION($ex)) {
		  return array('success'=>false,'msg'=>$ex->getMessage());
	  }

	  return array('success'=>true,'msg'=>'已状态同步成功');
	}
}