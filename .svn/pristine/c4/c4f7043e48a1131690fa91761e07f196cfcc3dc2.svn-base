<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Supplier extends TMIS_Controller {
	var $_modelExample;
	var $title = "供应商资料";
	var $funcId = 26;
	var $_tplEdit='Jichu/SupplierEdit.tpl';
	function Controller_Jichu_Supplier() {
		//if(!//$this->authCheck()) die("禁止访问!");
		$this->_modelExample = & FLEA::getSingleton('Model_Jichu_Supplier');
        // $this->_modelTaitou = & FLEA::getSingleton('Model_Jichu_SupplierTaitou');

        $this->fldMain = array(
        	'id'=>array('title'=>'','type'=>'hidden','value'=>''),
        	'compCode'=>array('title'=>'供应商编号','type'=>'text','value'=>$this->_autoCode('','','jichu_supplier','compCode')),
        	'compName'=>array('title'=>'供应商名称','type'=>'text','value'=>''),
        	'gongxuId'=>array('title'=>'供应商类型','type'=>'hidden','value'=>'0'),
        	'people'=>array('title'=>'联系人','type'=>'text','value'=>''),
        	'isStop'=>array('title'=>'停止往来','type'=>'select','value'=>'','options'=>array(
        			array('text'=>'否','value'=>0),
        			array('text'=>'是','value'=>1)
        		)),
        	'address'=>array('title'=>'地址','type'=>'text','value'=>''),
        	'tel'=>array('title'=>'电话','type'=>'text','value'=>''),
        	'memo'=>array('title'=>'备注','type'=>'textarea','value'=>''),
        	'isSupplier'=>array('title'=>'','type'=>'hidden','value'=>'1'),
        );

        $this->rules = array(
			'compCode'=>'required repeat',
			'compName'=>'required'
		);
	}

	function actionRight() {
		$this->authCheck('15-4');
    	$title = '供应商档案';
		///////////////////////////////模板文件
		$tpl = 'TblList.tpl';

		// $hasZhizao = & FLEA::getAppInf('hasZhizao');
		
		$arr_field_info = array(
			'_edit'=>'操作',
			"compCode" =>array('text'=>"编码",'align'=>'left'),
			"compName" =>"名称",
			// "jghCode"=>'简称',
			'people'=>'联系人',
			"tel" =>"电话",
			"address" =>"地址",
			'memo'=>'备注'
		);
		
		///////////////////////////////模块定义
		$this->authCheck('15-4');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'key'=>''
		));
        $condition[] = array('isJiagong',0,'=');
		if($arr['key']!='') {
			$condition[] = array('compCode',"%{$arr['key']}%",'like','or');
			$condition[] = array('compName',"%{$arr['key']}%",'like');
		}
		
		$pager = new TMIS_Pager($this->_modelExample,$condition);
		$rowset =$pager->findAll();
		if(count($rowset)>0) foreach($rowset as & $v) {
			//$this->makeEditable($v,'memoCode');
			$v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
			if($v['isStop']==1)$v['_bgColor']="#5BC0DE";
		}
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arr_field_info);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr))."<font color='blue'>   蓝色表示停止往来</font>");
		$smarty->display($tpl);
    }

    function actionSave() {
        $this->authCheck('15-4');
		if(empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `jichu_supplier` where compCode='".$_POST['compCode']."' or compName='".$_POST['compName']."'";
			$rr = $this->_modelExample->findBySql($sql);
			//dump($rr);exit;
			if($rr[0]['cnt']>0) {
				js_alert('加工户名称或加工户代码重复!',null,$this->_url('add'));
			}
		} else {
		//修改时判断是否重复
			$str1="SELECT count(*) as cnt FROM `jichu_supplier` where id!=".$_POST['id']." and (compCode='".$_POST['compCode']."' or compName='".$_POST['compName']."')";
			$ret=$this->_modelExample->findBySql($str1);
			if($ret[0]['cnt']>0) {
				js_alert('加工户名称或加工户代码重复!',null,$this->_url('Edit',array('id'=>$_POST['id'])));
			}
		}
		
		// parent::actionSave();
		//首字母自动获取
		FLEA::loadClass('TMIS_Common');
		$letters=strtoupper(TMIS_Common::getPinyin($_POST['compName']));
		$_POST['letters']=$letters;
		// dump($_POST);exit;
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
	    $smarty->assign('title','供应商信息编辑');
	    $smarty->assign('aRow',$row);
	    $smarty->display('Main/A1.tpl');
	}

	function actionAdd($Arr){
		$smarty = & $this->_getView();
	    $smarty->assign('fldMain',$this->fldMain);
	    $smarty->assign('title','供应商信息编辑');
	    $smarty->assign('rules',$this->rules);
	    $smarty->display('Main/A1.tpl');
	}
    
     function actionPopup(){
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
          'key' => ''
        ));
        $str = "select * from jichu_supplier where 1 and isJiagong=0 and isStop=0";
        
        if ($arr['key']!='') $str .= " and (compName like '%$arr[key]%'
                            or zhujiCode like '%$arr[key]%')";
        $str .=" order by compName asc,compCode asc";
        $pager = new TMIS_Pager($str);
        $rowset =$pager->findAll($str);
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择产品');
        $arr_field_info = array(
        	 "compName" =>array('text'=>'供应商','width'=>200),
        	 'zhujiCode'=>'助记码',
             'compCode'=>'供应商编码',
             'people'=>'联系人',
        );
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('page_info',$pager->getNavBar($this->_url('Popup',$arr)));
        $smarty-> display('Popup/CommonNew.tpl');
    }
    /**
     * ps ：其他应付过账弹出窗口，增加类型一栏，区分供应商和加工户
     * Time：2015/10/22 15:15:14
     * @author liuxin
    */
    function actionPopupAll(){
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
          'key' => '',
          'supplierType' => '0'
        ));
        $str = "select * from jichu_supplier where 1 and isStop=0";
        
        if ($arr['key']!='') $str .= " and (compName like '%$arr[key]%'
                            or zhujiCode like '%$arr[key]%')";
		switch ($arr['supplierType']) {
			case '1':
				$str.=" and isJiagong=0";
				break;
			case '2':
				$str.=" and isJiagong=1";
			default:
				break;
		}
        $str .=" order by compName asc,compCode asc";
        $pager = new TMIS_Pager($str);
        $rowset =$pager->findAll($str);
        foreach ($rowset as &$v) {
        	$v['type']=$v['isJiagong']==0?'供应商':'加工户';
        }
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择产品');
        $arr_field_info = array(
        	 "compName" =>array('text'=>'供应商/加工户','width'=>200),
        	 'zhujiCode'=>'助记码',
             'compCode'=>'编码',
             'people'=>'联系人',
             'type'=>'类型',
        );
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('page_info',$pager->getNavBar($this->_url('Popup',$arr)));
        $smarty-> display('Popup/CommonNew.tpl');
    }
	function actionRemove() {
		//如果已使用该加工户，禁止删除
    	// if($_GET['id']>0){
	    //     $url=$this->_url($_GET['fromAction']==''?'right':$_GET['fromAction']);
	    //     //判断是否生产计划中中使用了该产品
	    //     $str="select count(*) as cnt from pisha_plan_son where supplierId='{$_GET['id']}'";
	    //     $temp=$this->_modelExample->findBySql($str);
	    //     if($temp[0]['cnt']>0){
	    //         js_alert('采购计划中已设置该供应商，禁止删除','',$url);
	    //     }

	    //     //判断是否入库界面已使用该加工户
	    //     $str="select count(*) as cnt from cangku_ruku where supplierId='{$_GET['id']}'";
	    //     $temp=$this->_modelExample->findBySql($str);
	    //     if($temp[0]['cnt']>0){
	    //         js_alert('仓库入库已使用该供应商，禁止删除','',$url);
	    //     }
     // 	}
		parent::actionRemove();
	}
}
?>