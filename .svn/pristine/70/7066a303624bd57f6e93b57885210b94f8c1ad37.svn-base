<?php
FLEA::loadClass('TMIS_Controller');
class Controller_SMS_SMS extends TMIS_Controller {
	var $_modelExample;
	
	function __construct() {
		$this->_modelExample = FLEA::getSingleton('Model_SMS_Sender');
		$this->_modelShenhe= FLEA::getSingleton('Model_Shenhe_Shenhe2Node');
	}

	function actionTestSend(){
		$this->send();
	}

	/**
	 * 短信发送测试地址
	 * Time：2015/10/23 09:27:16
	 * @author li
	*/
	function send(){
		// $node = $this->_modelShenhe->_LastNode('销售合同');
		$node_all = $this->_modelShenhe->_config_shenhe();
		$node_order = $node_all['销售合同'];

		//不需要审核直接不需要发送短信
		if(!count($node_order)>0){
			return true;
		}

		//只对最后一个权限进行短信提醒
		$node = end($node_order);
		$next_key = count($node_order)-2;
		if($next_key>=0){
			$node_pre = $node_order[$next_key];
			$sql="select x.id,ifnull(y.id,'none') as shenhe from trade_order2product x
			left join shenhe_db y on y.tableId = x.orderId and y.nodeId='{$node['id']}'
			left join shenhe_db z on z.tableId = x.orderId and z.nodeId='{$node_pre['id']}'
			LEFT JOIN trade_order c on c.id=x.orderId 
			where  c.is_delivery ='Y' and x.shenhe='' and y.id is null and z.id is not null and x.is_sms=0 limit 0,1";
		}else{
			//查找是否存在需要审核的合同信息
			$sql="select x.id,ifnull(y.id,'none') as shenhe from trade_order2product x
			left join shenhe_db y on y.tableId = x.orderId and y.nodeId='{$node['id']}'
			where x.shenhe='' and y.id is null and x.is_sms=0 limit 0,1";
		}

		$order2Pro = $this->_modelExample->findBySql($sql);

		if(!count($order2Pro)>0){
			return false;
			exit;
		}
		//如果有需要短信提醒，没有暂停
		//超链接地址
		$_url = $this->getUrl();
		// echo $_url;exit;
		//短信内容
		if($_url!=''){
			$_content = "[溢代约克]您有合同需要审核， {$_url}";
		}else{
			$_content = "[溢代约克]您有合同需要审核,索要手机审核地址直接手机审核";
		}

		//echo $_content;exit;

		//查找需要发送短信的人员名单		
		$sql="select 
		y.userName,
		y.phone,
		y.id as userId
		from shenhe_user2node x
		left join acm_userdb y on x.userId = y.id 
		where x.nodeId = '{$node['id']}' and phone<>''";

		$user = $this->_modelExample->findBySql($sql);

		$logInfo = array('creater'=> 'admin', 'sendDate'=> time());
		foreach ($user as $key => & $v) {
			$v['tel'] = $v['phone'];

			$v['content'] = $_content;
			$_data['data'] = $v;
			$res=$this->_modelExample->sendForNormalSMS($_data,$logInfo);

			//更新已经发短信的订单信息
			$sql="update trade_order2product set is_sms=1 where shenhe='' and is_sms=0";
			$this->_modelExample->execute($sql);
		}
		return true;
	}

	/**
	 * 获取url
	 * Time：2015/10/27 19:19:08
	 * @author li
	 * @return string
	*/
	function getUrl(){
		//设置地址
		$_file = "phone.php";
		//获取根目录
		// dump($_SERVER);exit;
		$_dir = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
		
		$dir_name = explode('/', $_dir);
		$_dirname = end($dir_name);

		//获取ip
		//获取配置的域名，如果没有则系统自动获取ip如果还是获取不到ip则ip为空
		$domain_name = FLEA::getAppInf('domain_name');
		if(!$domain_name){
			FLEA::loadClass('TMIS_Common');
			$domain_name = TMIS_Common::get_onlineip();
		}
		//获取域名方法有待优化 暂时把地址写死 2016年3月22日 
		$domain_name = "http://www.acfabric.com";
		$domain_name!='' && $_url = $domain_name."/".$_dirname."/".$_file;
		return $_url.'';
	}
	/**
	 * 短信列表查询
	 * Time：2015/10/24 16:34:16
	 * @author zhangyan
	*/
	function actionReportLog(){
		FLEA::loadClass('TMIS_Pager'); 

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' => date("Y-m-d"),
		)); 

		$sql="SELECT * from sms_log 
			where 1";
        
        if($arr['dateFrom'] != '') {
			$sql .=" and ctime >= '{$arr['dateFrom']}' and ctime <= '{$arr['dateTo']} 23:59:59'";
		}
		$sql.=" order by  ctime desc";
		$pager = new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		foreach ($rowset as & $value) {
			$value['isSendOk'] = $value['isSendOk'] == 1 ? '成功' : '失败';
		}
		//查找已发成功的总数量
		$sql="select count(id) as cnt from sms_log where isSendOk=1";
		$ret=$this->_modelExample->findBySql($sql);

		$smarty = &$this->_getView(); 

		$arrFieldInfo = array(
			"userName" => "用户名",
			'tel'=>'电话',
			'content'=>'内容',
			'sendCnt'=>'条数',
			'isSendOk'=>'是否成功',
			'creater'=>'创建者',
			'ctime'=>'创建时间',
		); 
		$smarty->assign('title', '短信列表查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)).'&nbsp;已发数量:&nbsp;'.$ret[0]['cnt']);
		$smarty->display('TblList.tpl');
	}

}

?>