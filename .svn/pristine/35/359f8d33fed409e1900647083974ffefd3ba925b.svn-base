<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Shenhe extends TMIS_Controller {
	var $_modelExample;
	var $fldMain; 
	// /构造函数
	function __construct() {
		$this->_modelExample = &FLEA::getSingleton('Model_Shenhe_ShenheDb'); 
		$this->fldMain = array(
			'status'=>array('title' => '状态', "type" => "select", 'value' => '','options'=>array(
					array('text'=>'通过','value'=>'yes'),
					array('text'=>'未通过','value'=>'no'),
				)),
			'id' => array('type' => 'hidden', 'value' => ''), 
		);
		$this->rules = array(
	      'ckName'=>'required',
	      'kwName'=>'required'
	    );
	}

	function actionRight() {
		$this->authCheck('15-5');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'key' => '',
		));
		$str = "select * from jichu_kuwei where 1";
		if ($arr['key'] != '') $str .= " and kuweiName like '%{$arr['key']}%'";
		$str.=" order by id desc";
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll(); 
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['_edit'] = $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
		}
		$smarty = &$this->_getView();
		$smarty->assign('title', '仓库');
		$arr_field_info = array(
			"_edit" => '操作',
			// 'kuweiName'=>array('text'=>'仓库','width'=>180),
		);
		foreach($this->fldMain as $k=>& $v) {
			if($v['type'] == 'hidden') continue;
			$arr_field_info[$k] = $v['title'];
		}
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('arr_field_value', $rowset); 
		// $smarty->assign('arr_js_css', $this->makeArrayJsCss(array('thickbox')));
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	} 

	function actionEdit() {
		//先判读是否有权限
		if(!$this->authShenhe($_GET['nodeId'])){
			js_alert('没有该审核节点的权限，无法操作','window.history.go(-1)');
			exit;
		}
		$smarty = &$this->_getView();
		$smarty->assign('fldMain', $this->fldMain);
		$smarty->assign('rules',$this->rules);
		$smarty->assign('title', '仓库信息');
		$smarty->display('Main/A1.tpl');
	}

	function actionSave() {
		
		$_POST['kuweiName']=$_POST['ckName'].$_POST['kwName'];
		if (!$_POST['kuweiName']) {
			js_alert('请输入库位名!', 'window.history.go(-1)');
			exit;
		}


		// 产品编码不重复
		$sql = "select count(*) cnt from jichu_kuwei where kuweiName='{$_POST['kuweiName']}' and id<>'{$_POST['id']}'";
		$_rows = $this->_modelExample->findBySql($sql);
		if ($_rows[0]['cnt'] > 0) {
			js_alert('库位重复!',  'window.history.go(-1)');
			exit;
		}

		
		//首字母自动获取
		FLEA::loadClass('TMIS_Common');
		$letters=strtoupper(TMIS_Common::getPinyin($_POST['kuweiName']));
		$_POST['letters']=$letters;

		$this->_modelExample->save($_POST);
		js_alert(null, 'window.parent.showMsg("保存成功")', $this->_url($_POST['fromAction']));
		exit;
	}

	/**
	 * 弹出窗口选择
	 * Time：2014/08/06 10:39:09
	 * @author li
	*/
	function actionPopup() {
		FLEA::loadClass('TMIS_Pager');
		TMIS_Pager::clearCondition();
		$arr = TMIS_Pager::getParamArray(array(
			'key' => '',
		));
		//查找数据sql
		$str = "select * from jichu_kuwei where 1";
		if ($arr['key'] != '') $str .= " and kuweiName like '%{$arr['key']}%'";
		//按照加工户首字母排序
		$str.=" order by letters asc,id desc";
		//分页查找数据
		$pager = &new TMIS_Pager($str);
		$rowset = $pager->findAll();
	
		$smarty = &$this->_getView();
		$smarty->assign('title', '仓库');
		$arr_field_info = array(
			'kuweiName'=>array('text'=>'仓库名称'),

		);
		
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('page_info', $pager->getNavBar($this->_url('Popup', $arr)));
		$smarty->display('Popup/CommonNew.tpl');
	} 
}

?>