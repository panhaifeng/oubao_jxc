<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Main extends TMIS_Controller {

	function Controller_Main() {
		$this->_modelExample = &FLEA::getSingleton('Model_OaMessage');
		$this->_modelAcmOa = &FLEA::getSingleton('Model_Acm_User2message');
        $this->_modelruku = &FLEA::getSingleton('Model_Cangku_Ruku');

	}
	
	function actionIndex() {
		if (!$_SESSION['REALNAME']) {
			redirect(url("Login"));	exit;
		}

		//判断浏览器类型：如果浏览器类型不对，给予提示
		FLEA::loadClass('TMIS_Common');
		TMIS_Common::doBrowser();
		
		$smarty = & $this->_getView();
		$smarty->display('Main.tpl');
	}

	function actionGetMenu() {
		$f = &FLEA::getAppInf('menu');
		include $f;
		$m = &FLEA::getSingleton('Model_Acm_Func');
		$ret = array();
		foreach($_sysMenu as &$v) {
			$a = $m->changeVisible($v, array('userName' => $_SESSION['USERNAME']));
			if(!$a) continue;
			$ret[] = $a;
		}

		//处理图标问题
		foreach($ret as & $v) {
			$this->setIconTree($v);
		}
		echo json_encode($ret);
	}

	/**
	 * 处理icon，优先使用spanIcon
	 * Time：2015/07/22 14:52:55
	 * @author li
	 * @param array
	 * @return array
	*/
	function setIconTree(&$node){
		//处理图标问题
		if($node['iconSpan']!=''){
			$node['text']="<span class='glyphicon-tree glyphicon {$node['iconSpan']}'></span> ".$node['text'];
			$node['iconCls']='x-tree-icon-hide';
			unset($node['iconSpan']);
		}

		foreach ($node['children'] as & $v) {
			$this->setIconTree($v);
		}
	}

	function actionWelcome() {
		$smarty = & $this->_getView();
		//订单提醒
		$weekarray=array("日","一","二","三","四","五","六");
		$today = date('w')>0 ? date('w')-1 : 6;//星期几：0（星期7）~ 6（星期六）
		$dateFrom = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$today,date('Y')));
		$dateTo = date('Y-m-d');

		$sql="SELECT x.* from trade_order2product x
			left join trade_order y on x.orderId=y.id
			where orderTime>='{$dateFrom}' and orderTime <= '{$dateTo} 23:59:59' and status='active'";
		$order=$this->_modelExample->findBySql($sql);

		$order=count($order);
		$today=$weekarray[date("w")];

		$smarty->assign('order',$order);
		$smarty->assign('dateTo',$dateTo);
		$smarty->assign('today',$today);

		//所有没有出库的销售和同提醒
		$sql="SELECT y.* from trade_order x
			left join trade_order2product y on y.orderId=x.id
			left join cangku_chuku2product c on c.ord2proId=y.id
			where c.id is  null and x.is_delivery='Y' and x.status='active'";
		$fahuo=$this->_modelExample->findBySql($sql);
		$fahuo=count($fahuo);
		$smarty->assign('fahuo',$fahuo);

		//待确认发货任务 合同审核后没有确认发货单
		$sql="SELECT * from trade_order x 
			left join trade_order2product y on x.id=y.orderId
			left join chuku_plan z on z.ord2proId=y.id
			where y.shenhe='yes' and z.id is null and x.is_delivery='Y' and x.status='active'";
		$fahuoWaiting=$this->_modelExample->findBySql($sql);
		$fahuoWaiting=count($fahuoWaiting);
		$smarty->assign('fahuoWaiting',$fahuoWaiting);
		
		//产品即将交期
		$dateFrom=date('Y-m-d H:i:s');
		$dateTo = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+3,date('Y')));
		$sql="SELECT x.* from caigou_order2product x
			left join caigou_order y on x.caigouId=y.id
			where x.jiaoqi>='{$dateFrom}' and x.jiaoqi <= '{$dateTo} 23:59:59' ";
		$jiaoqi=$this->_modelExample->findBySql($sql);
		$jiaoqi=count($jiaoqi);

		$smarty->assign('jiaoqi',$jiaoqi);
		$smarty->assign('dateTo',$dateTo);
		
		//待校验任务
		$sql="SELECT * from chuku_plan where jiaoyan='' and kind='现货仓库'";
		$jiaoyan=$this->_modelExample->findBySql($sql);
		$jiaoyan=count($jiaoyan);
		$smarty->assign('jiaoyan',$jiaoyan);

		//未出库完成 已生成出库单没有出库完成
		$sql="SELECT sum(cntM)as cnt,planId from(
				SELECT sum(cntM) as cntM,x.id as planId 
					from chuku_plan x
					where 1 group by x.id
				union 
				SELECT sum(cntM*-1) as cntM,x.planId 
					from cangku_chuku2product x
					where 1 group by x.planId
			) as a where 1 and planId>0  group by planId having cnt>0";
		$chuku=$this->_modelExample->findBySql($sql);
		$chuku=count($chuku);
		$smarty->assign('chuku',$chuku);


		//申购单待审核
		$sql="SELECT * from caigou_shengou where shenhe=''";
		$shengou=$this->_modelExample->findBySql($sql);
		$shengou=count($shengou);
		$smarty->assign('shengou',$shengou);

		//采购合同待审核
		$sql="SELECT * from caigou_order where shenhe<>'yes'";
		$caigou=$this->_modelExample->findBySql($sql);
		$caigou=count($caigou);
		$smarty->assign('caigou',$caigou);

		//销售合同待审核
		$sql="SELECT * from trade_order x 
			left join trade_order2product y on y.orderId=x.id
			where y.shenhe=' ' and x.status='active' and is_delivery='Y'";
		$xiaoshou=$this->_modelExample->findBySql($sql);
		$xiaoshou=count($xiaoshou);
		$smarty->assign('xiaoshou',$xiaoshou);

		//新增会员,权限判断
		$today = date('w')>0 ? date('w')-1 : 6;//星期几：0（星期7）~ 6（星期六）
		$dateFrom = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$today,date('Y')));
		$dateTo=date('Y-m-d H:i:s');
		$sql="SELECT * from jichu_client where compDate>='{$dateFrom}' and compDate <= '{$dateTo}'";
		$huiyuan=$this->_modelExample->findBySql($sql);
		$huiyuan=count($huiyuan);
		$smarty->assign('huiyuan',$huiyuan);

		//订单数 和总米数
		$dateFrom = date('Y-01-01');
		$dateTo = date('Y-12-31');
		$sql="SELECT sum(cntM) as cntM,count(*) as num,m from(
				select 
						month(orderTime)as m,
						sum(y.cntM)as cntM,
						x.orderTime
				from trade_order x
				left join trade_order2product y on x.id=y.orderId
				where x.orderTime>='{$dateFrom}' and x.orderTime <= '{$dateTo} 23:59:59'
				group by x.id
			) as a where 1 group by month(orderTime)";
		$rowset=$this->_modelExample->findBySql($sql);
		
		$temp_row=array();
		foreach ($rowset as & $r) {
			$temp_row[$r['m']]=$r;
		}
		$arr=array();
		for ($i=1; $i <13 ; $i++) {
			$arr[$i]['cntM']=$temp_row[$i]['cntM'].'';
			$arr[$i]['num']=$temp_row[$i]['num'].'';
		}

		$smarty->assign('arr',json_encode($arr));
		
		$smarty->assign('time',date('Y-m-d H:i:s',time()));
		$smarty->display('Welcome.tpl');
	}

	function actionTzViewDetails() {
		//dump($_GET);exit;
		$row=$this->_modelExample->findAll(array('id'=>$_GET['id']));
		if($_SESSION['USERID']!='') {
			if($row[0]['kindName']!='订单变动通知') {
				$sql="SELECT count(*) as cnt,kind,id FROM `acm_user2message` where messageId='{$_GET['id']}' and userId='{$_SESSION['USERID']}'";
				$rr=mysql_fetch_assoc(mysql_query($sql));
				
				if($rr['cnt']==0) {
					$arr=array(
						'userId'=>$_SESSION['USERID'],
						'messageId'=>$_GET['id'],
						'kind'=>0,
					);
				}else if($rr['kind']==1){
					$arr=array(
						'id'=>$rr['id'],
						'kind'=>0,
					);
				}
				
				if($arr && $_SESSION['USERID']!='')$this->_modelAcmOa->save($arr);
			}
		}
		$smarty = & $this->_getView();
		$smarty->assign('title','查看通知');
		$smarty->assign("row", $row[0]);
		$smarty->display('OaViewDetails.tpl');
	}

	//处理弹出窗口后下次不在弹出消息的问题
	function actionTzViewDetailsByAjax(){
		// dump(1);exit;
		if($_SESSION['USERID']=='')exit;
		$userId=$_SESSION['USERID'];
		$sql="SELECT x.* FROM `oa_message` x 
		left join oa_message_class y on y.className=x.kindName
		where y.isWindow=0
		and not exists(select * from acm_user2message z where z.messageId=x.id and z.userId='{$userId}')";
		$rr=$this->_modelExample->findBySql($sql);
		foreach($rr as & $v){
			// if($v['kindName']=='行政通知') {
					$arr[]=array(
						'userId'=>$_SESSION['USERID'],
						'messageId'=>$v['id'],
						'kind'=>1,
					);
			// }
		}
		if($arr)$this->_modelAcmOa->saveRowset($arr);
		echo json_encode(array('success'=>true));exit;
	}

	function changToHtml($val) {//将特殊字元转成 HTML 格式
		$val=htmlspecialchars($val);
		$val= str_replace("\011", ' &nbsp;&nbsp;&nbsp;', str_replace('  ', ' &nbsp;', $val));
		$val= ereg_replace("((\015\012)|(\015)|(\012))", '<br />', $val);
		return $val;
	}
	function cSubstr($str,$start,$len) {//截取中文字符串
		$temp = "<span title='".$str."'>".mb_substr($str,$start,$len,'utf-8')."</span>";
		return $temp;
	}

	//
	function actionGetTongzhiByAjax() {
		$userId=$_SESSION['USERID'];
		$sql="SELECT x.*,count(*) as cnt FROM `oa_message`  x
		left join oa_message_class y on y.className=x.kindName
		where y.isWindow=0
		and not exists(select * from acm_user2message z where z.messageId=x.id and z.userId='{$userId}')";
		$rr=$this->_modelExample->findBySql($sql);
		//dump($rr);exit;
		//if($rr[0]['cnt']>0){
		echo json_encode($rr[0]);
		exit;
	//}

	}

	//
	function actionGetMailByAjax() {
		$userId=$_SESSION['USERID'];
		$sql="SELECT count(*) as cnt FROM mail_db where accepterId='{$userId}' and timeRead='0000-00-00 00:00:00'";
		//dump($sql);exit;
		$rr=$this->_modelExample->findBySql($sql);
		echo json_encode($rr[0]);
		exit;
	}

	//根据id取得通知内容，返回为json
	function actionGetContentByAjax() {
		$row=$this->_modelExample->findAll(array('id'=>$_GET['id']));
		if($_SESSION['USERID']!='') {
			if($row[0]['kindName']=='行政通知') {
				$sql="SELECT count(*) as cnt FROM `acm_user2message` where messageId='{$_GET['id']}' and userId='{$_SESSION['USERID']}'";
				$rr=mysql_fetch_assoc(mysql_query($sql));
				if($rr['cnt']==0) {
					$arr=array(
						'userId'=>$_SESSION['USERID'],
						'messageId'=>$_GET['id'],
					);
					if($arr && $arr['userId']!='')
						$this->_modelAcmOa->save($arr);
				//$dbo=FLEA::getDBO(false);dump($dbo->log);exit;
				}
			}
		}
		$row=$this->_modelExample->find(array('id'=>$_GET['id']));
		echo json_encode($row);exit;
	}
	//获取新订单通知
	// function actionGetNewTrade(){
	// 	$userId=$_SESSION['USERID'];
	// 	$sql="SELECT x.*,count(*) as cnt FROM oa_message  x
	// 		where 1 and kindName = '订单'
	// 		and not exists(select z.* from acm_user2message z where z.messageId=x.id and z.userId='{$userId}')";
	// 	$rr=$this->_modelExample->findBySql($sql);
	// 	// dump($sql);exit;
	// 	echo json_encode($rr['0']);
	// 	exit;
	// }

	//2016年3月28日12:22:09 获取新的退库通知by jiangxu
	// function actionGetNewTkmsg(){
	// 	$userId=$_SESSION['USERID'];
	// 	$sql="select * from acm_roledb where roleName like '%仓库%' or roleName = '财务科'";
	// 	$row=$this->_modelExample->findBySql($sql);
	// 	foreach ($row as &$v) {
	// 		$str="select * from acm_user2role where roleId = '{$v['id']}'";
	// 		$arr=$this->_modelExample->findBySql($str);
	// 		foreach ($arr as &$vv) {
	// 			$sql = "select * from acm_userdb where id = '{$vv['userId']}'";
	// 			$rowset=$this->_modelExample->findBySql($sql);
	// 			foreach ($rowset as &$value) {
	// 				// dump($value['id']);
	// 				if($userId==$value['id']){
	// 				$sql="SELECT x.*,count(*) as cnt FROM oa_message  x
	// 				where 1 and kindName ='采购退库'
	// 				and not exists(select z.* from acm_user2message z where z.messageId=x.id and z.userId='{$userId}')";
	// 				$rr=$this->_modelExample->findBySql($sql);
	// 				}
	// 			}	
	// 		}
	// 	}
	// 			    echo json_encode($rr['0']);
	// 			    exit;
	// }
		
	//处理弹出窗口后下次不在弹出消息的问题
	function actionTzNext(){
		if($_SESSION['USERID']=='')exit;
		$userId=$_SESSION['USERID'];
		$sql="SELECT x.* FROM `oa_message` x 
		where 1
		and not exists(select * from acm_user2message z where z.messageId=x.id and z.userId='{$userId}')";
		$rr=$this->_modelExample->findBySql($sql);
		foreach($rr as & $v){
			$arr[]=array(
				'userId'=>$_SESSION['USERID'],
				'messageId'=>$v['id'],
				'kind'=>1,
			);
		}
		if($arr)$this->_modelAcmOa->saveRowset($arr);
		echo json_encode(array('success'=>true));exit;
	}
}
?>