<?php
/*
 * 实施人员用的后台配置程序，
 * 可进行动态密码卡的设置，
 * 可进行功能权限的定义。
 * 可查看db_change
 */
FLEA::loadClass('TMIS_Controller');
class Controller_Tool extends TMIS_Controller {
    var $m;
    function Controller_Tool() {
	$this->m= & FLEA::getSingleton('Model_Jichu_Client');
	$this->_guozhang = & FLEA::getSingleton('Model_Caiwu_Ar_Guozhang');
    //echo 1;exit;
    }
    function actionIndex() {
		if($_SESSION['SN']==1||$_GET['_debug']==1){
			$smarty = & $this->_getView();
			$smarty->display('Tool/Index.tpl');
		}else{
			js_alert('没有通过动态密码卡验证，禁止操作',null,url('Login','Index'));
		}
	}

    //利用ajax获得工具栏的操作目录
    function actionGetToolMenu() {
		$menu = array(
			// array('text'=>'开关管理','leaf'=>true,'src'=>'?controller=Tool&action=Kaiguan'),
			array('text'=>'动态密码卡管理','expanded'=> false,'leaf'=>true,'src'=>'?controller=Tool&action=dongtai'),
			array('text'=>'设置弹窗信息','expanded'=> false,'leaf'=>true,'src'=>'?controller=Tool&action=setTanchuang'),
			array('text'=>'测试数据自动生成工具','expanded'=> false,'leaf'=>true,'src'=>'?controller=Tool&action=TestDataInsert'),
			array('text'=>'短信日志列表','expanded'=> false,'leaf'=>true,'src'=>'?controller=SMS_SMS&action=ReportLog'),
		);
		echo json_encode($menu);
    }

    /**
    	* @author li
    	* @return null
    	*/
    function actionBuilding(){
    	FLEA::loadClass('TMIS_Common');
    	$m = FLEA::getSingleton('Model_Jichu_Client');
    	///客户的首字母自动填充
    	$sql="select id,compName from yixiang_client where 1";
    	$res=$m->findBySql($sql);
    	foreach($res as & $v){
    		$letters=strtoupper(TMIS_Common::getPinyin($v['compName']));
    		$sql="update yixiang_client set letters='{$letters}' where id='{$v['id']}'";
    		$m->execute($sql);
    	}

    	///员工档案的首字母
    	$sql="select id,compName from jichu_jiagonghu where 1";
    	$res=$m->findBySql($sql);
    	foreach($res as & $v){
    		$letters=strtoupper(TMIS_Common::getPinyin($v['compName']));
    		$sql="update jichu_jiagonghu set letters='{$letters}' where id='{$v['id']}'";
    		$m->execute($sql);
    	}
    	///加工户的首字母
    	$sql="select id,employName from jichu_employ where 1";
    	$res=$m->findBySql($sql);
    	foreach($res as & $v){
    		$letters=strtoupper(TMIS_Common::getPinyin($v['employName']));
    		$sql="update jichu_employ set letters='{$letters}' where id='{$v['id']}'";
    		$m->execute($sql);
    	}
    	echo '补丁完成';exit;
    }

    //开关设置
    function actionKaiguan() {
    	if(count($_POST)>0) {
    		$ret = array();
    		$m = FLEA::getSingleton('Model_Acm_SetParamters');
    		foreach($_POST as $k=>&$v) {
    			if($k=='Submit') continue;
    			//找到相关的记录，取得相对应的id
    			$sql = "select id from sys_set where item='{$k}'";
    			$_rows = $this->m->findBySql($sql);
    			$ret[] = array(
    				'id'=>$_rows[0]['id'],
    				'item'=>$k,
    				'value'=>$v
    			);
    		}
    		$m->saveRowset($ret);
    		js_alert(null,"window.parent.showMsg('保存成功')",$this->_url('kaiguan'));
    	}
    	FLEA::loadClass('TMIS_Common');
    	$row = TMIS_Common::getSysSet();
    	// dump($row);
    	$smarty = & $this->_getView();  
    	$smarty->assign('aRow',$row);  	
    	$smarty->display('Tool/Kaiguan.tpl');
    }

    //管理动态密码卡
    function actionDongtai() {
		$sql = "select * from acm_sninfo";
		$rowset = $this->m->findBySql($sql);
		$rowset[] = array();
		$smarty = & $this->_getView();
		$smarty->assign('rowset',$rowset);
		$smarty->display('Tool/Dongtai.tpl');
    }
    function actionSaveDongtai() {
		$m = & FLEA::getSingleton('Model_Acm_Sninfo');
		if($m->save($_POST)) {
			js_alert(null,'window.parent.showMsg("保存成功")',$this->_url('dongtai'));
		}
    }

	

	//导出菜单目录
    function actionExport() {
		echo("<a href='".$this->_url('View')."'>导出</a>");
    }
    function actionView() {
		include('Config/menu.php');
		$smarty = & $this->_getView();
		$smarty -> assign('row',$_sysMenu);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=test.xls");
		header("Content-Transfer-Encoding: binary");
		$smarty -> display('Tool/MenuView.tpl');
    }

	//设置弹窗内容，如果这里设置了，登录成功后，会弹出一个对话框，强制用户观看。
	function actionSetTanchuang() {
		//$this->authCheck('8');
		$sql = "select * from sys_pop";
		$row = mysql_fetch_assoc(mysql_query($sql));
		$tpl = 'Tool/PopEdit.tpl';
		$smarty = & $this->_getView();
		$smarty->assign('aRow',$row);
		$smarty->display($tpl);
	}
	function actionSavePop() {
		$m = & FLEA::getSingleton('Model_Sys_Pop');
		$id = $m->save($_POST);
		js_alert('保存成功,提交的信息将会在用户登录的第一时间弹出显示，客户必须关闭弹窗才可继续操作！','',$this->_url('SetTanchuang'));
	}
	//利用ajax取得弹窗的内容
	function actionGetPopByAjax() {
		$d = date('Y-m-d');
		$sql = "select * from sys_pop where dateFrom<='{$d}' and dateTo>='{$d}'";
		//dump($sql);exit;
		$row = mysql_fetch_assoc(mysql_query($sql));
		if(!$row) {
			$arr = array(
				'success'=>false
			);
		} else {
			$arr = array(
				'success'=>true,
				'data'=>$row
			);
		}
		echo json_encode($arr);
	}


	/****************************读取excel文件********************************************/
	function actionReadExcel() {
		$filePath='a.xls';
		$arr = $this->_readExcel($filePath);
		//以下为数据处理过程
		//$ret = array();
		foreach($arr as $k=> & $v) {
			if($k==0) continue;
			$row = array(
				'proCode'=>$v[1].'',
				'proName'=>$v[2].'',
				'unit'=>'只',
				'priceRetail'=>$v[4].'',
				'barCode'=>$v[6]
			);
			//dump($row);exit;
			$sql = "insert into jxc_jianzhong.jichu_product(
				proCode,
				proName,
				unit,
				priceRetail,
				barCode
			) values(
				'{$row['proCode']}',
				'{$row['proName']}',
				'{$row['unit']}',
				'{$row['priceRetail']}',
				'{$row['barCode']}'
			)";
			mysql_query($sql) or die(mysql_error());
		}
		// dump($ret[0]);exit;
		
		//dump($arr[1]);dump($ret);exit;
		// $m = & FLEA::getSingleton('Model_Jichu_Client');
		// $m->createRowset($ret);
		echo "成功!";
	}
	//读取某个excel文件的某个sheet数据，
	function _readExcel($filePath,$sheetIndex=0) {
		set_time_limit(0);
		include "Lib/PhpExcel/PHPExcel.php";

		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
		$cacheSettings = array('memoryCacheSize'=>'16MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$PHPExcel = new PHPExcel();
		//如果是2007,需要$PHPReader = new PHPExcel_Reader_Excel2007();
		$PHPReader = new PHPExcel_Reader_Excel5();
		if(!$PHPReader->canRead($filePath)){
			echo 'no Excel';
			return ;
		}
		$PHPExcel = $PHPReader->load($filePath);
		/**读取excel文件中的第一个工作表*/
		$currentSheet = $PHPExcel->getSheet($sheetIndex);
		/**取得共有多少列,若不使用此静态方法，获得的$col是文件列的最大的英文大写字母*/
		$allColumn = PHPExcel_Cell::columnIndexFromString($currentSheet->getHighestColumn());

		/**取得一共有多少行*/
		$allRow = $currentSheet->getHighestRow();
		//输出
		$ret = array();
		for($currow=1;$currow<=$allRow;$currow++){
		  $_row=array();
		  for($curcol=0;$curcol<$allColumn;$curcol++){
			   $result=$currentSheet->getCellByColumnAndRow($curcol,$currow)->getValue();
			   $_row[] = $result;
		  }
		  $ret[] = $_row;
		}
		return $ret;
	}

	//测试数据自动生成工具
	function actionTestDataInsert(){
		$clientIndex = 100;

		$actionIndex = 5000;

		for ($i=0; $i <$clientIndex ; $i++) { 
			$sql="insert into jichu_client (compName,compCode,traderId) values ('客户".$i."',00{$i},1)";
			mysql_query($sql);
		}

		for ($i=0; $i <$actionIndex ; $i++) { 
			$sql="insert into reschedule (clientId,scheduleDate,traderId,styleId) values ('1',now(),1,10)";
			mysql_query($sql);
		}
	}


	function actionTestRemoveData(){
		
		$sql="SELECT id,orderId,kind from caiwu_ar_guozhang where kind='运费过账' ";
		$rr = $this->_guozhang->findBySql($sql);
		foreach ($rr as  &$value) {
			$_rr[] = $value['id'];
		}
		$_res[] = join(',',$_rr);
		// dump($rr);die;

		$arr="SELECT min(id),kind,orderId from caiwu_ar_guozhang where kind='运费过账' group by orderId   ";
		$res = $this->_guozhang->findBySql($arr);
		foreach ($res as  &$v) {
			$_arr[] = $v['min(id)'];
		}
		$_row[] = join(',',$_arr);
		// dump($res);die;

		$str="DELETE from caiwu_ar_guozhang 
			where  id in ({$_res['0']}	) 
			and  id not in ({$_row['0']}) ";
		// dump($str);die;
		$this->_guozhang->execute($str);
	}
}
?>