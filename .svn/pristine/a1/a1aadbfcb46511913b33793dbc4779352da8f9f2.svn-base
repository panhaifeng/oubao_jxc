<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Bank extends TMIS_Controller {
	var $_modelExample;
	var $funcId = 35;
	function Controller_Jichu_Bank() {
		$this->_modelExample = & FLEA::getSingleton('Model_Jichu_Bank');

		$this->fldMain = array(
        	'id'=>array('title'=>'','type'=>'hidden','value'=>''),
        	'itemName'=>array('title'=>'账户名称','type'=>'text','value'=>''),
        	'address'=>array('title'=>'地址','type'=>'text','value'=>''),
        	'manger'=>array('title'=>'负责人','type'=>'text','value'=>''),
        	'tel'=>array('title'=>'电话','type'=>'text','value'=>''),
        	'contacter'=>array('title'=>'联系人','type'=>'text','value'=>''),
        	'phone'=>array('title'=>'电话','type'=>'text','value'=>''),
        	'tel'=>array('title'=>'营业厅电话','type'=>'text','value'=>''),
        	'acountCode'=>array('title'=>'开户账号','type'=>'text','value'=>''),
        	'xingzhi'=>array('title'=>'性质','type'=>'select','value'=>'','options'=>array(
        			array('text'=>'基本户','value'=>'基本户'),
        			array('text'=>'一般户','value'=>'一般户'),
        			array('text'=>'税务专用','value'=>'税务专用')
        		)),
        	'memo'=>array('title'=>'备注','type'=>'textarea','value'=>''),
        );

        $this->rules = array(
			'itemName'=>'required repeat'
		);
	}

	function actionRight() {
		$this->authCheck('15-8');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
		$sql="select * from jichu_bank where 1";
		if($arr['key']!='') $sql .=" and itemName like '%{$arr['key']}%'";
		$pager = new TMIS_Pager($sql);
		$rowset =$pager->findAll();
		// dump($rowset);exit;
		if(count($rowset)>0) foreach($rowset as & $v) {
			$v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
			}
		//dump($rowset);
		$smarty = & $this->_getView();
		$smarty->assign('title', '银行帐户');
		#对操作栏进行赋值
		
		$arr_field_info = array(
			'_edit'=>'操作',
			"itemName" =>"账户名称",
			"address"=>"地址",
			"manger"=>"负责人",
			"tel"=>"电话",
			"contacter"=>"联系人",
			"phone"=>"营业厅电话",
			"acountCode"=>"开户账号",
			"xingzhi"=>"性质"
		);

		// $smarty->assign('arr_edit_info',$arr_edit_info);
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition',$arr);
		$smarty->assign('page_info',$pager->getNavBar($this->_url('right')));
		// $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('grid')));
		$smarty-> display('TblList.tpl');
	}

	function actionSave() {
		$id = $this->_modelExample->save($_POST);
		if($_POST['Submit']=='保 存')
			js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction']));
		else
			js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('add'));
	}

	function actionEdit(){
		$row = $this->_modelExample->find(array('id'=>$_GET['id']));
	    $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
	    // dump($row);dump($this->fldMain);exit;
	    $smarty = & $this->_getView();
	    $smarty->assign('fldMain',$this->fldMain);
	    $smarty->assign('rules',$this->rules);
	    $smarty->assign('title','银行账户编辑');
	    $smarty->assign('aRow',$row);
	    $smarty->display('Main/A1.tpl');
	}

	function actionAdd($Arr){
		$smarty = & $this->_getView();
	    $smarty->assign('fldMain',$this->fldMain);
	    $smarty->assign('title','银行账户编辑');
	    $smarty->assign('rules',$this->rules);
	    $smarty->display('Main/A1.tpl');
	}

	function actionRemove() {
		$from = $_GET['fromAction']?$_GET['fromAction']:$_GET['from'];
		//构造sql语句判断账户是否在使用中，如果是，则不允许删除。2015-11-02，by liu
		$sql = "select * from caiwu_ar_income where bankId = '{$_GET['id']}'";
		$rowset = $this->_modelExample->findBySql($sql);
		if(count($rowset)!=0){
			js_alert('银行账户在收款登记中被使用，无法删除。',null,$this->_url($from));
			exit;
		}
		if ($this->_modelExample->removeByPkv($_GET['id'])) {
			if($from=='') redirect($this->_url("right"));
			else js_alert(null,"window.parent.showMsg('成功删除')",$this->_url($from));
		}
		else js_alert('出错，不允许删除!',null,$this->_url($from));

	}
}
?>