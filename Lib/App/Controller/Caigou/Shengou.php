<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Caigou_Shengou extends TMIS_Controller {
	// /构造函数
	function __construct() {
		$this->_modelExample = &FLEA::getSingleton('Model_Caigou_Shengou');
		$this->_subModel = &FLEA::getSingleton('Model_Caigou_Shengou2Product');
        $this->_modelShenheDb = &FLEA::getSingleton('Model_Shenhe_ShenheDb');
		// 定义模板中的主表字段
		$this->fldMain = array(
			'shengouCode' => array('title' => '单号', "type" => "text", 'readonly' => true,'value' => '自动生成'),
			'depId'=>array('title'=>'申请部门','type'=>'select','model' => 'Model_Jichu_Department','isSearch'=>'true'),
			'employId'=>array('title'=>'申请人','type'=>'select','model' => 'Model_Jichu_Employ','isSearch'=>'true','condition'=>'isFire=0'),
			'shengouDate' => array('title' => '申请日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
			'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memoView'),
			// 下面为隐藏字段
			'id' => array('type' => 'hidden', 'value' => '','name'=>'sgId'),
			'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME'].''),
		);
		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		$this->headSon = array(
			'_edit' => array('type' => 'BtBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			// 'productId' => array('type' => 'btproductpopup', "title" => '产品选择', 'name' => 'productId[]'),
			'supplierId' => array(
				'title' => '供应商',
				'type' => 'BtPopup',
				'name' => 'supplierId[]',
				'url'=>url('Jichu_Supplier','Popup'),
				'textFld'=>'compName',
				'hiddenFld'=>'id',
				'inTable'=>true,
				'dialogWidth'=>900
			),
			'jiaoqi'=>array(
				'type'=>'BtCalendarInTbl',
				"title"=>'产品交期',
				'name'=>'jiaoqi[]',
				'value' => date('Y-m-d'),
				'inTable'=>true
			),
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
			'cnt' => array('type' => 'BtText', "title" => '要货数', 'name' => 'cnt[]'),
			'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
        			array('text'=>'M','value'=>'M',),
        			array('text'=>'Y','value'=>'Y')
        		)),
			'danjia' => array('type' => 'BtText', "title" => '单价', 'name' => 'danjia[]'),
			'memo' => array('type' => 'BtText', "title" => '备注', 'name' => 'memo[]'),
			// ***************如何处理hidden?
			'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
		);
		// 表单元素的验证规则定义
		$this->rules = array(
			'employId' => 'required',
		);
	}

	function actionRight(){
		//权限判断
		$this->authCheck('1-1-2');
		FLEA::loadClass('TMIS_Pager'); 

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' => date("Y-m-d"),
			'danhao'=>'',
			'supplierId'=>'',
			'proName' => '',
			'empId'=>'',
		)); 

		$sql="SELECT x.*,y.id as sid,y.jiaoqi,z.color,y.cnt,y.unit,y.danjia,y.memo,z.proCode,z.proName,a.compName,
			d.depName,employName  
			from caigou_shengou x 
			left join caigou_shengou2product y on x.id=y.shengouId
			left join jichu_product z on z.proCode=y.productId
			left join jichu_supplier a on a.id=y.supplierId
			left join jichu_department d on d.id=x.depId
			left join jichu_employ e on e.id=x.employId
			where 1";
        
        if($arr['dateFrom'] != '') {
			$sql .=" and x.shengouDate >= '{$arr['dateFrom']}' and x.shengouDate <= '{$arr['dateTo']}'";
		}
		if($arr['supplierId']!='') $sql .=" and y.supplierId='{$arr['supplierId']}' ";
		if($arr['empId']!='') $sql .=" and x.employId='{$arr['empId']}' ";
		if($arr['proName']!='') $sql .=" and z.proName like '%{$arr['proName']}%' ";
		if($arr['danhao']!='') $sql .=" and x.shengouCode like '%{$arr['danhao']}%' ";

		$sql.=" order by shengouCode desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		 //dump($rowset);exit;
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['shengouDate']=$v['shengouDate']=='0000-00-00'?'':$v['shengouDate'];

			//审核后的不能修改删除
			$statesh=$this->stateShenhe('申购单',$v['id']);
			if($statesh=='未审核'){
				$v['_edit'] = $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
			}
			else {
				$v['_edit'].="<span class='glyphicon glyphicon-pencil' ext:qtip='已审核不能修改'></span>";
				$v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已审核不能删除'></span>";
			}

			//显示审核信息
			$_detial=$this->showShenhe('申购单',$v['id']);
			
			//拒绝后可以选择是否重新申购
			if($statesh=='拒绝'){
				$v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('AgainShenhe',array(
					id=>$v['id'],'shenheName'=>'申购单'))."'><span class='glyphicon glyphicon-share-alt' title='重新申请'></span></a>";
				$statesh="<a href='javascript:;' ext:qtip=\"{$_detial}\" style='color:red;'>{$statesh}</a>";
			}

			$v['state'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$statesh}</a>";

			$v['cnt']=round($v['cnt'],3);
			$v['danjia']=round($v['danjia'],3);
			$v['jiaoqi']=$v['jiaoqi']=='0000-00-00'?'':$v['jiaoqi'];
		}
		$smarty = &$this->_getView(); 

		$arrFieldInfo = array(
			"_edit" => array('text'=>'操作','width'=>70),
			'shengouCode'=>'单号',
			'state'=>'状态',
			"shengouDate" => "申请日期",
			"depName" => "申请部门",
			"employName" => "申请人",
			'compName'=>'供应商',
			'jiaoqi' => array('text'=>'交期','width'=>120),
			'proCode'=>'花型六位号',
			'proName'=>'品名',
			// "color" => "颜色",
			'cnt'=>'数量',
			'unit'=>'单位',
			'danjia'=>'单价',
			"memo" => '备注', 
		); 
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	}

	/**
	 * ps ：修改
	 * Time：2015-08-21 14:07:34
	 * @author shen
	*/
	function actionEdit() {
		$arr = $this->_modelExample->find(array('id' => $_GET['id']));
		// dump($arr);die;
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
		}
		foreach($arr['Products'] as &$v) {
			$v['jiaoqi']=$v['jiaoqi']=='0000-00-00'?'':$v['jiaoqi'];
			$_temp = $this->_subModel->find(array('id' => $v['id']));
			$v['proCode'] = $_temp['Product']['proCode'];
			$v['proName'] = $_temp['Product']['proName'];
			$v['compName'] = $_temp['Supplier']['compName'];
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
			$temp['supplierId']['text'] = $v['compName'];
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
		$smarty->assign('sonTpl', 'Caigou/sonTpl.tpl');
		$smarty->display('Main2Son/T1.tpl');
	}

	function actionAdd($arr) {
		$this->authCheck('1-1-1');
		$_modeProduct = FLEA::getSingleton('Model_Jichu_Product');
		$_modeSupplier = FLEA::getSingleton('Model_Jichu_Supplier');
		//处理申购单导入信息
		if($arr){
			foreach ($this->fldMain as $k => &$v) {
				$v['value'] = $arr[$k]?$arr[$k]:$v['value']; 
			}
			foreach($arr['Products'] as &$v) {
				$v['jiaoqi']=$v['jiaoqi']=='0000-00-00'?'':$v['jiaoqi'];
				$_temp = $_modeProduct->find(array('proCode' => $v['productId']));
				$_sup=$_modeSupplier->find(array('compName'=>$v['compName']));
				$v['supplierId'] = $_sup['id'];
				$v['proCode'] = $_temp['proCode'];
				$v['proName'] = $_temp['proName'];
				$v['chengfen'] = $_temp['chengfen'];
				$v['shazhi'] = $_temp['shazhi'];
				$v['jwmi'] = $_temp['jingmi'].'*'.$_temp['weimi'];
				$v['menfu'] = $_temp['menfu'];
			} 
			foreach($arr['Products'] as &$v) {
				$temp = array();
				foreach($this->headSon as $kk => &$vv) {
					$temp[$kk] = array('value' => $v[$kk]);
				}
				$temp['productId']['text'] = $v['proCode'];
				$temp['supplierId']['text'] = $v['compName'];
				$rowsSon[] = $temp;
			} 
		}
		//补齐5行
		$cnt = count($rowsSon);
		for($i=5;$i>$cnt;$i--) {
			$rowsSon[] = array();
		}
		$smarty = &$this->_getView();
		$areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);
		$smarty->assign('areaMain', $areaMain);		
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);
		$smarty->assign('fromAction', $_GET['fromAction']==''?'add':$_GET['fromAction']);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', 'Caigou/sonTpl.tpl');
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
		$row['Products'] = $pros;

		//判断申购单节点是否需要审核，不需要审核的情况下，默认为已审核通过
		$_model2Node = &FLEA::getSingleton('Model_Shenhe_Shenhe2Node');
		$_isNeed = $_model2Node->_isNeedShenhe('申购单');
		if(!$_isNeed){
			//默认审核通过
			$row['shenhe']='yes';
		}
		if($row['shengouCode']=='自动生成') $row['shengouCode']=$this->_getNewCode('SG','caigou_shengou','shengouCode');
		// dump($row);exit;
		if(!$this->_modelExample->save($row)) {
			js_alert('保存失败','window.history.go(-1)');
			exit;
		}
		//跳转
		js_alert(null,'window.parent.showMsg("保存成功!")',url($_POST['fromController'],$_POST['fromAction'],array()));
		exit;
	}
	/**
	 * ps ：申购审核
	 * Time：2015/08/24 14:23:21
	 * @author jiang
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionListShenhe(){
		//权限判断
		$this->authCheck('1-1-3');
		FLEA::loadClass('TMIS_Pager'); 
		$arr = TMIS_Pager::getParamArray(array(
			'danhao'=>'',
			'empId'=>'',
			'isShenhe'=>'',
			// 'isHetong'=>'',
		));

		$sql="select x.*,d.depName,e.employName  
			from caigou_shengou x 
			left join jichu_department d on d.id=x.depId
			left join jichu_employ e on e.id=x.employId
			where 1";

		if($arr['empId']!='') $sql .=" and x.employId='{$arr['empId']}' ";
		if($arr['danhao']!='') $sql .=" and x.shengouCode like '%{$arr['danhao']}%' ";
		if($arr['isShenhe']!='all') $sql .=" and x.shenhe = '{$arr['isShenhe']}' ";
		// if($arr['isHetong']=='yes') $sql .="and exists(select s.shengouId from caigou_shengou2product s where s.									shengouId=x.id and s.caigou2proId>0)";
		// if($arr['isHetong']=='no') $sql .="and exists(select s.shengouId from caigou_shengou2product s where s.									shengouId=x.id and s.caigou2proId=0)";
		$sql.=" order by shengouCode desc";

		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		//dump($rowset);die;

		foreach($rowset as &$v) {
			// dump($v);exit;
			$v['shengouDate']=$v['shengouDate']=='0000-00-00'?'':$v['shengouDate'];
			//申购单审核，生成审核操作列
			$v['_edit']=$this->getShenheHtml('申购单',$v['id']);

			//审核总状态
			if($v['shenhe']=='yes'){
				$v['shenhe'] = '<span class="text-success">通过</span>';
			}elseif ($v['shenhe']=='no') {
				$v['shenhe'] = '<span class="text-danger">拒绝</span>';
			}

			//明细显示
			$str="select y.id as sid,if(y.jiaoqi='0000-00-00','',y.jiaoqi) as jiaoqi,y.cnt,y.unit,y.danjia,y.memo,z.proCode,z.proName,a.compName,z.chengfen,z.shazhi,z.menfu,CONCAT(z.jingmi,'*',z.weimi) as jwmi
				from caigou_shengou2product y
				left join jichu_product z on z.proCode=y.productId
				left join jichu_supplier a on a.id=y.supplierId
				where 1 and y.shengouId='{$v['id']}'";
			$ret=$this->_modelExample->findBySql($str);
			$v['Products']=$ret;
			$v['shTime']=$v['shTime']=='0'?'':$v['shTime'];

			//2017-2-20 by jeff,备注可点击进行修改
			$v['memo']=='' && $v['memo']='点击修改';
			$v['memo'] = "<a href='".$this->_url('editMemo',array(
				'id'=>$v['id']
			))."'>{$v['memo']}</a>";
		}

		$smarty = &$this->_getView(); 
		$arrFieldInfo = array(
			"_edit" => '操作',
			'shenhe'=>'审核状态',
			'shengouCode'=>'单号',
			"shengouDate" => "申请日期",
			"depName" => "申请部门",
			"employName" => "申请人",
			"memo" => '备注', 
			"shTime" => '重新申请次数', 
		); 
		$arrField = array(
			'compName'=>'供应商',
			'jiaoqi'=>'产品交期',
			'proCode'=>'花型六位号',
			'proName'=>'品名',
			'chengfen'=>'成分',
			'shazhi' =>'纱支',
			'jwmi' =>'经纬密',
			'menfu' =>'门幅',
			// 'color'=>'颜色',
			'cnt' =>'要货数',
			'unit' =>'单位',
			'danjia' =>'单价',
			'memo' =>'备注',
		);
		$smarty->assign('title', '申购单审核');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('arr_field_info2', $arrField);
		$smarty->assign('sub_field', 'Products');
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->display('TblListMore.tpl');
	}

	function actionEditMemo() {
		if(isset($_POST) && $_POST['id']>0) {
			$row = array('id'=>$_POST['id'],'memo'=>$_POST['memo']);
			$r = $this->_modelExample->save($row);
			if(!$r) {
				js_alert('保存失败,请联系管理员!',null,$this->_url('ListShenhe'));
				exit;
			}
			js_alert('修改成功!',null,$this->_url('ListShenhe'));
			exit;
		}
		$row = $this->_modelExample->find(array('id'=>$_GET['id']));
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<form name="FormSearch" id="FormSearch" method="post" action="" class="form-horizontal">';
		echo '修改备注<br/><textarea name="memo" id="memo" class="form-control" style="margin: 0px; width: 400px; height: 200px;">'.$row['memo'].'</textarea>';
		echo '<input type="hidden" name="id" value="'.$row['id'].'" />';
		echo '<br /><button type="submit" style="width:100px;height:40px;"> 保 存 </button>';
		echo '</form>';
	}
	
	/**
	 * ps ：申购弹框
	 * Time：2015/08/24 15:47:45
	 * @author jiang
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionPopup(){
		// dump($_GET);exit;
		FLEA::loadClass('TMIS_Pager');
   		$arr = TMIS_Pager::getParamArray(array(
        	'danhao' => '',
        	'supplierId'=>'',
        )); 
        $sid=explode(',', substr($_GET['sid'],0,strlen($_GET['sid'])-1));
        $retSid=array();
    	foreach ($sid as & $s) {
    		$retSid[$s]=$s;
    	}
    	// dump($retSid);exit;
    	$sql="select x.*,y.id as sid,y.jiaoqi,y.cnt,y.unit,y.danjia,y.memo,z.proName,a.compName,
			employName,y.supplierId,z.shazhi,z.menfu,z.jingmi,z.weimi,z.proCode,y.productId,y.cntM,z.chengfen  
			from caigou_shengou x 
			left join caigou_shengou2product y on x.id=y.shengouId
			left join jichu_product z on z.proCode=y.productId
			left join jichu_supplier a on a.id=y.supplierId
			left join jichu_employ e on e.id=x.employId
			where 1 and y.caigou2proId=0 
			";
		//判断是否已经审核结束
		$sql.=" and x.shenhe='yes'";
		//判断供应商是否停止往来,2015-10-15,by liuxin
		$sql.=" and a.isStop=0";
		if($arr['supplierId']!='') $sql .=" and y.supplierId='{$arr['supplierId']}' ";
		if($arr['proName']!='') $sql .=" and z.proName like '%{$arr['proName']}%' ";
		if($arr['danhao']!='') $sql .=" and x.shengouCode like '%{$arr['danhao']}%' ";
		$sql.=" order by proCode,shengouCode desc,supplierId desc";
		// dump($sql);exit;
    	$pager = &new TMIS_Pager($sql);
    	$rowset = $pager->findAll();
	    
	    foreach($rowset as $k=> & $v) {
	    	$che='';
	    	if($retSid[$v['sid']]) $che="checked='checked'";
	    	$v["select"] = "<input type='checkbox' name='ck[]' id='ck[]' value='{$k}' $che>";
	    	$v['jiaoqi']=$v['jiaoqi']=='0000-00-00'?'':$v['jiaoqi'];
	    }
	    // foreach($rowset as $k=> & $v) {
	    // 	$v["select"] = "<input type='checkbox' name='ck[]' id='ck[]' value='{$k}'>";
	    // }
	    $smarty = &$this->_getView();
	    $smarty->assign('title', '选择申购单号');
	    $other_btn="<input type='checkbox' id='checkedAll' title='全选/反选'/>"."&nbsp;&nbsp";
	    $other_btn.='<button type="button" class="btn btn-success btn-xs" id="choose" name="choose">确认</button>';
	    $arr_field_info = array(
	    	"select"   =>array("text"=>$other_btn,"width"=>65),
			"shengouDate" => "申请日期",
			'proCode'=>'花型六位号',
			'proName'=>'产品名称',
			'compName'=>'供应商',
			'jiaoqi' => array('text'=>'交期','width'=>90),
			'cnt'=>array('text'=>'数量','width'=>80),
			'unit'=>array('text'=>'单位','width'=>70),
			'danjia'=>array('text'=>'单价','width'=>80),
			'shengouCode'=>'单号',
			"employName" => "申请人",
			"memo" => '备注',
	    ); 

    
	    $smarty->assign('arr_field_info',$arr_field_info);
	    $smarty->assign('arr_field_value',$rowset);
	    $smarty->assign('add_display','none');
	    $smarty->assign('arr_condition',$arr);
	    $smarty->assign('page_info',$pager->getNavBar($this->_url('Popup',$arr)));
	    $smarty-> display('Popup/CommonNew.tpl');
	}

	/**
	 * ps ：显示明细
	 * Time：2015/08/24 16:08:02
	 * @author jiang
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionGetMingxi(){
		$arr = $this->_subModel->findAll(array('shengouId'=>$_GET['id']));
		if($arr) {
		echo json_encode(array('success'=>true,'Pro'=>$arr));
		exit;
		}
		echo json_encode(array('success'=>false,'msg'=>'未发现匹配产品!'));
	}


	/**
	 * 添加导入申购单的功能
	 * Time：2015/08/25 09:26:08
	 * @author li
	*/
	function actionImport(){
		$this->authCheck('1-1-4');
		$smarty = &$this->_getView();
		$smarty->display('Caigou/import.tpl');
	}
	/**
	 * 添加导入申购单的功能
	 * Time：2015-09-24 16:17:33
	 * @author shen
	*/
	function actionSaveShengou(){
		//处理上传文件
		$temp=array();$arr=array();
		foreach ($_FILES as $k=> &$v){
			for($i=0;$i<count($v['name']);$i++){
				foreach ($v as $key=> &$value){
					$temp[$key]=$value[$i];
				}
				$arr[][$k]=$temp;
			}
		}
		if($arr['0']['sgFile']['name']==''){
			js_alert("没有选择文件禁止保存",'window.history.go(-1)');
		}
		foreach ($arr as &$v){
			$temp1='';
			foreach ($v as $kk=>&$vv){
				$temp1=$kk;
			}
			$dizhi['path']=$v[$kk];
			$filePath[$kk][]= $this->_importAttac($dizhi);
		}
		$filePath=$filePath['sgFile']['0']['filePath'];
		$arr = $this->_readExcel($filePath,0); 


		$_modeDepartment = FLEA::getSingleton('Model_Jichu_Department');
		$_modeEmploy = FLEA::getSingleton('Model_Jichu_Employ');
		$_modeSupplier = FLEA::getSingleton('Model_Jichu_Supplier');
		$_modeProduct = FLEA::getSingleton('Model_Jichu_Product');

		$Department = $_modeDepartment->find(array(depName=>$arr[2][0]));
		$Employ = $_modeEmploy->find(array(employName=>$arr[2][1]));

		$row['depId']=$Department['id'];
		$row['employId']=$Employ['id'];
		$row['shengouDate']=$arr[2][2];
		foreach($arr as $k => &$v) {
			if ($k <2) continue;
			if($v[0]=='') continue;

			$Supplier = $_modeSupplier->find(array(compName=>$v[3]));
			$Employ = $_modeEmploy->find(array(employName=>$v[1]));
			$Department = $_modeDepartment->find(array(depName=>$v[0]));
			$Product = $_modeProduct->find(array(proCode=>$v[5]));

			//对申购单进行审核 限制导入
			if($Department['depName'] !=$v[0]){
				js_alert("申购单中有不存在的申请部门",'window.history.go(-1)');
			}
			if($Employ['employName'] !=$v[1]){
				js_alert("申购单中有不存在的员工",'window.history.go(-1)');
			}
			if($Supplier['compName'] !=$v[3]){
				js_alert("申购单中有不存在的供应商",'window.history.go(-1)');
			}
			if($Product['proCode'] !=$v[5]){
				js_alert("申购单中有不存在的商品编号",'window.history.go(-1)');
			}
			$bumen[]=$v[0];
			$people[]=$v[1];
			$date[]=$v[2];
			$row['Products'][]=array(
					"productId"=>$Product['proCode'],
					"compName"=>$v[3],
					"jiaoqi"=>$v[4],
					"cnt"=>$v[11],
					"unit" => $v[12],
					"danjia"=>$v[13],
				);
		}
		//对申购单中的申请部门，申请人，供应商进行唯一性验证
		if(count(array_unique($bumen))>1){
				js_alert("申购单中的申请部门不唯一",'window.history.go(-1)');
			}
		if(count(array_unique($people))>1){
				js_alert("申购单中的申请人不唯一",'window.history.go(-1)');
			}
		if(count(array_unique($date))>1){
				js_alert("申购单中的申请日期不唯一",'window.history.go(-1)');
			}
		$this->actionAdd($row);exit;
	}
	/**
	 * 处理申购单的上传
	 * Time：2015-09-24 14:23:01
	 * @author shen
	*/
	function _importAttac($dizhi){
		//上传路径
		$path="upload/shengou/";
		$targetFile='';
		$tt = false;//是否上传文件成功
		//禁止上传的文件类型
		$upBitType = array(
				'application/x-msdownload',//exe,dll
				'application/octet-stream',//bat
				'application/javascript',//js
				'application/msword',//word
		);
		//处理上传代码
		if($dizhi['path']['name']!=''){
			//附件大小不能超过10M
			$max_size=10;//M
			$max_size2=$max_size*1024*1024;
			if($dizhi['path']['size']>$max_size2){
				return array('success'=>false,'msg'=>"附件上传失败，请上传小于{$max_size}M的附件");
			}
				
			//限制类型
			if(in_array($dizhi['path']['type'],$upBitType)){
				$_msg = "该文件类型不允许上传";
      			js_alert($_msg,'window.history.go(-1)');
				// return array('success'=>false,'msg'=>"该文件类型不允许上传");
			}
				
			//上传附件信息
			if ($dizhi['path']['name']!="") {
				$tempFile = $dizhi['path']['tmp_name'];
				//处理文件名
				$pinfo=pathinfo($dizhi['path']['name']);
				$ftype=$pinfo['extension'];
				// $fileName=md5(uniqid(rand(), true)).' '.$dizhi['path']['name'];
				// $fileName=$dizhi['path']['name'];
				$fileName=md5(uniqid(rand(), true));
				$targetFile=$path.$fileName;//目标路径
				$tt=move_uploaded_file($tempFile,iconv('UTF-8','gb2312',$targetFile));
				if($tt==false && $targetFile!='')$msg="上传失败，请重新上传附件";
			}
		}		
		return array('filePath'=>$targetFile,'success'=>$tt,'msg'=>$msg);
	}
	/**
	 * 读取某个excel文件的某个sheet数据
 	*/
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
			js_alert('只能上传Excel文件！','window.history.go(-1)');
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

	/**
	 * ps ：选择部门时可选择的申请人随之改变
	 * Time：2015/09/24 13:25:47
	 * @author Liuxin
	*/
	function actionSelectedChanged(){
		if($_GET['depId'] != ''){
			$sql = "select id as employId,employName from jichu_employ where depId = '{$_GET['depId']}' and isFire=0";
		}
		else{
			$sql = "select id as employId,employName from jichu_employ where isFire=0";
		}
		$rowset=$this->_modelExample->findBySql($sql);
		echo json_encode($rowset);exit;
	}
	/**
	 * ps ：删除指定目录下的文件，不删除目录文件夹
	 * Time：2015-10-09 08:39:41
	 * @author shen
	*/
	function actionDelFile(){
		$dirName="./upload/shengou";
		$_remove = false;
		$_msg="没有需删除的缓存";

		if(file_exists($dirName) && $handle=opendir($dirName)){
	       	while(false!==($item = readdir($handle))){
               if($item!= "." && $item != ".."){
	                if(unlink($dirName.'/'.$item)){
						$_remove = true;
						$_msg="成功删除缓存文件";
              		}
            	}
        	}
      	}

      	js_alert($_msg,'window.history.go(-1)');
      	closedir($handle);
  	}
  	/**
	 * ps ：供应商报价走势报表
	 * Time：2015-10-22 25:04:41
	 * @author 张艳
	*/
	function actionReportSupplier(){
		$this->authCheck('14-2');
		FLEA::loadClass('TMIS_Pager'); 

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' => date("Y-m-d"),
			'supplierId'=>'',
			'proCode' => '',
		)); 

		$sql="SELECT x.*,y.id as sid,y.jiaoqi,z.color,z.pinming,z.colornum,z.colorsnum,sum(y.cnt) as cnt,y.unit,y.danjia,y.memo,z.proCode,z.proName,a.compName
			from caigou_shengou x 
			left join caigou_shengou2product y on x.id=y.shengouId
			left join jichu_product z on z.proCode=y.productId
			left join jichu_supplier a on a.id=y.supplierId
			where 1";
        
        if($arr['dateFrom'] != '') {
			$sql .=" and x.shengouDate >= '{$arr['dateFrom']}' and x.shengouDate <= '{$arr['dateTo']}'";
		}
		if($arr['supplierId']!='') $sql .=" and y.supplierId='{$arr['supplierId']}' ";
		if($arr['proCode']!='') $sql .=" and z.proCode like '%{$arr['proCode']}%' ";

		$sql.=" group by x.shengouDate,y.supplierId,z.proCode,y.danjia order by shengouDate desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['shengouDate']=$v['shengouDate']=='0000-00-00'?'':$v['shengouDate'];
			//显示审核信息
			$v['cnt']=round($v['cnt'],3).'/'.$v['unit'];
			$v['danjia']=round($v['danjia'],3);
			//以下代码可以直接写到sql语句中，使用group by进行过滤
			//如果时间，供应商，报价，花型六位号都相同，则过滤到该条信息
			// $keyTemp = $v['shengouDate'].$v['supplierId'].$v['proCode'].$v['danjia'];
			// 若已有$keyTemp的组合存在，则过滤
			// if(!isset($arrResult[$keyTemp])){
			// 	$arrResult[$keyTemp] = $v;
			// }
		}
		$smarty = &$this->_getView(); 

		$arrFieldInfo = array(
			"shengouDate" => "时间",
			'compName'=>'供应商',
			'proCode'=>'花型六位号',
			'proName'=>'品名',
			"pinming" => "品名",
			"colornum" => "款号",
			"colorsnum" => "颜色号",
			'danjia'=>'单价',
			'cnt'=>'数量/单位',
			"memo" => '备注', 
		); 
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	}
	/**
	 * ps ：申购单分类汇总
	 * Time：2015-10-22 18:45:57
	 * @author shen
	*/
	function actionReport(){
		$this->authCheck('14-1');
		FLEA::loadClass('TMIS_Pager'); 

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' => date("Y-m-d"),
			'supplierId'=>'',
			'proCode' => '',
			'isShenhe' => '',
		)); 

		$sql="SELECT sum(x.cntM)as cnt,x.unit,
			y.compName,z.shengouDate,a.proCode,a.proName,a.jingmi,
			a.weimi,a.shazhi,a.color,a.chengfen,a.wuliaoKind,a.pinming,
			a.colornum,a.colorsnum
			from caigou_shengou2product x 
			left join caigou_shengou z on x.shengouId=z.id
			left join jichu_supplier y on y.id=x.supplierId
			left join jichu_product a on a.proCode=x.productId
			where 1";
        if($arr['dateFrom'] != '') {
			$sql .=" and z.shengouDate >= '{$arr['dateFrom']}' and z.shengouDate <= '{$arr['dateTo']}'";
		}
		if($arr['supplierId']!='') $sql .=" and y.id='{$arr['supplierId']}' ";
		if($arr['proCode']!='') $sql .=" and a.proCode like '%{$arr['proCode']}%' ";
		if($arr['isShenhe']!='all') $sql .=" and z.shenhe = '{$arr['isShenhe']}' ";
		$sql.=" group by y.compName,a.proCode";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['shengouDate']=$v['shengouDate']=='0000-00-00'?'':$v['shengouDate'];
			$v['cnt']=round($v['cnt'],3);
			$v['danjia']=round($v['danjia'],3);
		}
		$rowset[]=$this->getHeji($rowset,array('cnt'),'compName');
		$smarty = &$this->_getView(); 
		$arrFieldInfo = array(
			'compName'=>'供应商',
			'proCode'=>'花型六位号',
			'proName'=>'品名',
			"pinming" => "品名",
			"colornum" => "款号",
			"colorsnum" => "颜色号",
			'jingmi'=>'经密',
			'weimi'=>'纬密',
			'shazhi'=>'纱支',
			'color'=>'颜色',
			'chengfen'=>'成分',
			'wuliaoKind'=>'整理方式',
			'cnt'=>'数量/M',
		); 
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	}
	/**
	 * ps ：申购单待审核
	 * Time：2015-10-27 08:41:13
	 * @author shen
	*/
	function actionShenheWaiting(){
		$this->authCheck('1-1-3');
		FLEA::loadClass('TMIS_Pager'); 
		$sql="SELECT x.*,d.depName,e.employName  
			from caigou_shengou x 
			left join jichu_department d on d.id=x.depId
			left join jichu_employ e on e.id=x.employId
			where x.shenhe=''";
		$sql.="order by shengouDate desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		foreach($rowset as &$v) {
			$v['shengouDate']=$v['shengouDate']=='0000-00-00'?'':$v['shengouDate'];
			//申购单审核，生成审核操作列
			
		}
		$smarty = &$this->_getView(); 
		$arrFieldInfo = array(
			'shengouCode'=>'单号',
			"shengouDate" => "申请日期",
			"depName" => "申请部门",
			"employName" => "申请人",
			"memo" => '备注', 
		); 
		$smarty->assign('title', '计划查询');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	}
}

?>