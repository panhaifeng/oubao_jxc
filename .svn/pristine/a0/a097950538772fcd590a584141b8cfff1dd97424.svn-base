<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Caigou_Order extends TMIS_Controller {
	// /构造函数
	function __construct() {
		$this->_modelExample = &FLEA::getSingleton('Model_Caigou_Order');
		$this->_subModel = &FLEA::getSingleton('Model_Caigou_Order2Product');
        $this->_modelShenheDb = &FLEA::getSingleton('Model_Shenhe_ShenheDb');
		// 定义模板中的主表字段
		$this->fldMain = array(
			'orderCode' => array('title' => '单号', "type" => "text", 'readonly' => true,'value' => '自动生成'),

			// 'supplierId' => array(
			// 	'title' => '供应商',
			// 	'type' => 'popup',
			// 	'name' => 'supplierId',
			// 	'url'=>url('Jichu_Supplier','Popup'),
			// 	'textFld'=>'compName',
			// 	'hiddenFld'=>'id',
			// 	// 'inTable'=>true,
			// 	'dialogWidth'=>900
			// ),
			'compName'=>array('title'=>'供应商','type'=>'text','value'=>'','name'=>'compName','readonly'=>true),
			'supplierId'=>array('type'=>'hidden','value'=>'','name'=>'supplierId'),
			'employId'=>array('title'=>'采购人','type'=>'select','model' => 'Model_Jichu_Employ','isSearch'=>'true','condition'=>'isFire=0'),
			'orderDate' => array('title' => '采购日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
			'shengouId' =>array(
				'type' => 'btnCommon',
				"title" => '高级功能',
				'textFld'=>'选择申购单明细',
				'url'=>url('Caigou_Shengou','Popup')
				// 'dialogWidth'=>800
			),
			'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memoView'),
			// 下面为隐藏字段
			'id' => array('type' => 'hidden', 'value' => '','name'=>'cgId'),
			'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),
		);
		// /从表表头信息
		// /type为控件类型,在自定义模板控件
		// /title为表头
		// /name为控件名
		// /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
		$this->headSon = array(
			'_edit' => array('type' => 'BtBtnRemove', "title" => '+5行', 'name' => '_edit[]'),
			// 'productId' => array('type' => 'btproductpopup', "title" => '产品选择', 'name' => 'productId[]'),

			'jiaoqi'=>array('type'=>'BtCalendarInTbl',"title"=>'产品交期','name'=>'jiaoqi[]','value' => date('Y-m-d'),'inTable'=>true),
			//先隐藏弹框  因为先不考虑没有报价直接登记采购合同
			// 'productId' => array(
			// 	'title' => '花型六位号',
			// 	'type' => 'BtPopup',
			// 	'name' => 'productId[]',
			// 	'url'=>url('Jichu_Product','Popup'),
			// 	'textFld'=>'proCode',
			// 	'hiddenFld'=>'id',
			// 	'inTable'=>true,
			// 	'dialogWidth'=>900
			// ),
			'pihao' => array('type' => 'BtText', "title" => '批号', 'name' => 'pihao[]'),
			'proCode'=>array('type'=>'BtText',"title"=>'花型六位号','name'=>'proCode[]','readonly'=>true),
			'productId'=>array('type'=>'BtHidden',"title"=>'','name'=>'productId[]','readonly'=>true),
			'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
			'chengfen'=>array('type'=>'BtText',"title"=>'成分','name'=>'chengfen[]','readonly'=>true),
			'shazhi' => array('type' => 'BtText', "title" => '纱支', 'name' => 'shazhi[]','readonly'=>true),
			'jwmi' => array('type' => 'BtText', "title" => '经纬密', 'name' => 'jwmi[]','readonly'=>true),
			'menfu' => array('type' => 'BtText', "title" => '门幅', 'name' => 'menfu[]','readonly'=>true),
			// 'color'=>array('type'=>'BtText',"title"=>'颜色','name'=>'color[]','readonly'=>true),
			'cnt' => array('type' => 'BtText', "title" => '要货数', 'name' => 'cnt[]'),
			'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
        			array('text'=>'M','value'=>'M'),
        			array('text'=>'Y','value'=>'Y')
        		)),
			'danjia' => array('type' => 'BtText', "title" => '单价', 'name' => 'danjia[]'),
			'memo' => array('type' => 'BtText', "title" => '备注', 'name' => 'memo[]'),
			// ***************如何处理hidden?
			'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
			'shengou2proId' => array('type' => 'BtHidden', 'name' => 'shengou2proId[]'),
			'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
		);
		// 表单元素的验证规则定义
		$this->rules = array(
			'employId' => 'required',
		);
	}

	function actionRight(){
		//权限判断
		$this->authCheck('1-3-3');
		FLEA::loadClass('TMIS_Pager');

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' => date("Y-m-d"),
			'danhao'=>'',
			'supplierId'=>'',
			'caiEmpId' => '',
			'proCode' => '',
			'shengouCode'=>'',
		));
		$sql="SELECT x.*,y.id as sid,y.jiaoqi,z.color,y.cnt,y.unit,y.danjia,y.memo,z.proCode,z.proName,a.compName,
			e.employName,sg.shengouCode
			from caigou_order x
			left join caigou_order2product y on x.id=y.caigouId
			left join jichu_product z on z.proCode=y.productId
			left join jichu_supplier a on a.id=x.supplierId
			left join jichu_employ e on e.id=x.employId
			left join caigou_shengou2product sg2 on sg2.caigou2proId=y.id
			left join caigou_shengou sg on sg.id=sg2.shengouId
			where 1 ";

		if($arr['dateFrom'] != '') {
			$sql .=" and x.orderDate >= '{$arr['dateFrom']}' and x.orderDate <= '{$arr['dateTo']}'";
		}
		if($arr['supplierId']!='') $sql .=" and x.supplierId='{$arr['supplierId']}' ";
		if($arr['proCode']!='') $sql .=" and z.proCode like '%{$arr['proCode']}%' ";
		if($arr['caiEmpId']!='') $sql .=" and x.employId='{$arr['caiEmpId']}' ";
		if($arr['danhao']!='') $sql .=" and x.orderCode like '%{$arr['danhao']}%' ";
		if($arr['shengouCode']!='') $sql .=" and sg.shengouCode like '%{$arr['shengouCode']}%' ";
		$sql.=" order by orderCode desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		//dump($rowset);die;
		$_modeShenhe2Node = FLEA::getSingleton('Model_Shenhe_Shenhe2Node');
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['orderDate']=$v['orderDate']=='0000-00-00'?'':$v['orderDate'];
			$v['jiaoqi']=$v['jiaoqi']=='0000-00-00'?'':$v['jiaoqi'];

			//审核后的不能修改删除
			$statesh=$this->stateShenhe('采购合同',$v['id']);
			if($statesh=='未审核'){
				$v['_edit'] = $this->getEditHtml($v['id']) . ' ' . $this->getRemoveHtml($v['id']);
			}
			else {
				$v['_edit'].="<span class='glyphicon glyphicon-pencil' ext:qtip='已审核不能修改'></span>";
				$v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash' ext:qtip='已审核不能删除'></span>";
			}
			//拒绝后可以选择是否重新申购
			if($statesh=='拒绝'){
				$v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('AgainShenhe',array(
					id=>$v['id'],'shenheName'=>'采购合同'))."'><span class='glyphicon glyphicon-share-alt' title='重新申请'></span></a>";
				$v['state'] = "<a href='javascript:;' class='bg-danger' ext:qtip=\"{$_detial}\">{$statesh}</a>";
				// $statesh="<font class='text-danger'>拒绝</font>";
			}

			//只有审核过的才能打印
			if($v['shenhe']=='yes'){
				$v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('Print',array(id=>$v['id']))."' target='_blank'><span class='glyphicon glyphicon-print' title='打印'></span></a>";
			}
			else {
				$v['_edit'].="&nbsp;&nbsp;<a href='javascript:void(0)'><span class='glyphicon glyphicon-print' title='审核通过后才能打印'></span></a>";
			}
			$v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('ViewExport',array(id=>$v['id']))."' target='_blank'><span class='glyphicon glyphicon-export' title='导出'></span></a>";
			// $v['_edit'].="<a href='".$this->_url('ViewExport',array('id'=>$v['id']))."' target='_blank'>明细导出</a>". ' ';

			//显示审核信息
			$_detial=$this->showShenhe('采购合同',$v['id']);
			if($statesh=='拒绝'){
				$v['state'] = "<a href='javascript:;' class='bg-danger' ext:qtip=\"{$_detial}\">{$statesh}</a>";
			}elseif($statesh=='已通过'){
				$v['state'] = "<a href='javascript:;' class='bg-success' ext:qtip=\"{$_detial}\">{$statesh}</a>";
			}else{
				$v['state'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$statesh}</a>";
			}
			// $v['state'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$statesh}</a>";
		}
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			"_edit" => array('text'=>'操作','width'=>110),
			'orderCode'=>'单号',
			'state'=>'状态',
			"orderDate" => "采购日期",
			"employName" => "采购人",
			'compName'=>'供应商',
			'jiaoqi' => array('text'=>'交期','width'=>120),
			'proCode'=>'花型六位号',
			'shengouCode'=>'申购单号',
			'proName'=>'产品名称',
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
	 * @author jiang
	*/
	function actionEdit() {
		//dump($_GET);die;
		$arr = $this->_modelExample->find(array('id' => $_GET['id']));
		//dump($arr);exit;
		foreach ($this->fldMain as $k => &$v) {
			$v['value'] = $arr[$k]?$arr[$k]:$v['value'];
		}
		$this->fldMain['compName']['value']=$arr['Supplier']['compName'];

		foreach($arr['Products'] as &$v) {
			$v['jiaoqi']=$v['jiaoqi']=='0000-00-00'?'':$v['jiaoqi'];
			$_temp = $this->_subModel->find(array('id' => $v['id']));
			// dump($_temp);exit;
			$v['proCode'] = $_temp['Product']['proCode'];
			$v['proName'] = $_temp['Product']['proName'];
			$v['chengfen'] = $_temp['Product']['chengfen'];
			$v['shazhi'] = $_temp['Product']['shazhi'];
			$v['jwmi'] = $_temp['Product']['jingmi'].'*'.$_temp['Product']['weimi'];
			$v['menfu'] = $_temp['Product']['menfu'];
			//查找申购Id
			$sql="select group_concat(id) as shengou2proId from caigou_shengou2product where caigou2proId='{$v['id']}' group by caigou2proId";
			$shengou=$this->_modelExample->findBySql($sql);
			$v['shengou2proId']=$shengou[0]['shengou2proId'];
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
		$smarty->assign('sonTpl', 'Caigou/sonTpl.tpl');
		$smarty->display('Main2Son/T1.tpl');
	}

	function actionAdd() {
		$this->authCheck('1-3-2');
		while (count($rowsSon) < 5) {
			$rowsSon[]=array(
				// 'dengji'=>array('value'=>'一等品')
			);
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
		// dump($_POST);exit;
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
			//将单位转换为米保存
			if($row['unit']=='Y') $row['cntM']=0.9144*$row['cnt'];
			else $row['cntM']=$row['cnt'];
		}
		$row['Products'] = $pros;
		if($row['orderCode']=='自动生成') $row['orderCode']=$this->_getNewCode('CG','caigou_order','orderCode');
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
	 * ps ：采购合同打印
	 * Time：2015/08/25 14:14:04
	 * @author jiang
	 * @param 参数类型
	 * @return 返回值类型
	*/
	function actionPrint(){
		$sql="select x.*,y.id as sid,y.jiaoqi,z.color,y.cnt,y.unit,y.danjia,y.memo,z.proCode,z.proName,a.compName,
			employName,z.chengfen,z.jingmi,z.weimi,z.shazhi,z.menfu
			from caigou_order x
			left join caigou_order2product y on x.id=y.caigouId
			left join jichu_product z on z.proCode=y.productId
			left join jichu_supplier a on a.id=x.supplierId
			left join jichu_employ e on e.id=x.employId
			where 1 and x.id='{$_GET['id']}'";
		$row=$this->_modelExample->findBySql($sql);
		//打印时预留8行空行
		$c=count($row);
		$count=0;
		for ($i=$c; $i<(15+$c); $i++) {
				 $i++;
				 $row[$i][]='';
		}
		$smarty = &$this->_getView();
		$smarty->assign('row',$row);
		$smarty->display('Caigou/htPrint.tpl');
	}
	/**
	 * ps ：采购合同审核
	 * Time：2015/09/06 13:35:25
	 * @author jiang
	*/
	function actionListShenhe (){
		//权限判断
		$this->authCheck('1-3-4');
		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'danhao'=>'',
			'supplierId'=>'',
			'caiEmpId' => '',
			'isShenhe'=>'',
		));
		$sql="select x.*,a.compName,e.employName
			from caigou_order x
			left join jichu_supplier a on a.id=x.supplierId
			left join jichu_employ e on e.id=x.employId
			where 1";
		if($arr['supplierId']!='') $sql .=" and x.supplierId='{$arr['supplierId']}' ";
		if($arr['caiEmpId']!='') $sql .=" and x.employId='{$arr['caiEmpId']}' ";
		if($arr['danhao']!='') $sql .=" and x.orderCode like '%{$arr['danhao']}%' ";
		if($arr['isShenhe']!='all') $sql .=" and x.shenhe = '{$arr['isShenhe']}' ";
		if($_GET['shenhe']=='1') $sql.=" and x.shenhe<>'yes'";
		$sql.=" order by orderCode desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		//dump($rowset);die;
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['orderDate']=$v['orderDate']=='0000-00-00'?'':$v['orderDate'];
			$v['_edit']=$this->getShenheHtml('采购合同',$v['id']);
			//明细显示
			$str="select y.id as sid,if(y.jiaoqi='0000-00-00','',y.jiaoqi) as jiaoqi,z.color,y.cnt,y.unit,y.danjia,y.memo,z.proCode,z.proName,z.chengfen,z.shazhi,z.menfu,y.pihao,CONCAT(z.jingmi,'*',z.weimi) as jwmi
				from caigou_order2product y
				left join jichu_product z on z.proCode=y.productId
				where 1 and y.caigouId='{$v['id']}'";
			$str.=" order by proCode asc";
			$ret=$this->_modelExample->findBySql($str);
			$v['Products']=$ret;
		}
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			"_edit" => '操作',
			'orderCode'=>'单号',
			"orderDate" => "采购日期",
			"employName" => "采购人",
			'compName'=>'供应商',
			"memo" => '备注',
			"shTime" => '重新申请次数',
		);
		$arrField = array(
			'jiaoqi'=>'产品交期',
			'proCode'=>'花型六位号',
			'proName'=>'品名',
			'chengfen'=>'成分',
			'shazhi' =>'纱支',
			'jwmi' =>'经纬密',
			'menfu' =>'门幅',
			'pihao' =>'批号',
			// 'color'=>'颜色',
			'cnt' =>'要货数',
			'unit' =>'单位',
			'danjia' =>'单价',
			'memo' =>'备注',
		);
		$smarty->assign('title', '采购合同审核');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('arr_field_info2', $arrField);
		$smarty->assign('sub_field', 'Products');
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->display('TblListMore.tpl');
	}

	/**
	 * 在途数显示
	 * Time：2015/09/15 17:16:05
	 * @author li
	*/
	function actionZaitu(){
		// dump($_GET);exit;
		$_GET['type'] = mysql_escape_string(rawurldecode($_GET['type']));
		$_GET['proCode'] = mysql_escape_string(rawurldecode($_GET['proCode']));

		// dump($_GET);exit;
		$sql="select sum(x.cnt-y.cnt) as cnt,x.unit,x.jiaoqi,
				p.proCode,p.proName,p.chengfen,p.shazhi,p.menfu,
				p.jingmi,p.weimi
				from caigou_order2product x
				left join cangku_ruku2product y on x.id=y.cai2proId
				left join cangku_ruku r on r.id=y.rukuId
				left join jichu_product p on p.proCode=x.productId
				where 1 and p.proCode='{$_GET['proCode']}' and x.rukuOver=0 and r.kind='采购入库'
				group by x.productId,x.unit,x.jiaoqi having cnt>0";

		$rowset=$this->_modelExample->findBySql($sql);
		$smarty = &$this->_getView();
		$smarty->assign('row', $rowset);
		$smarty->display('Caigou/ZaituShow.tpl');
	}

	/**
	 * 导出
	 * Time：2015-09-16 20:44:09
	 * @author shen
	*/
	function actionViewExport(){
		FLEA::loadClass('TMIS_Common');
		$sql="SELECT x.*,y.id as sid,y.jiaoqi,z.color,y.cnt,y.unit,y.danjia,y.memo,z.proCode,z.proName,a.compName,
			employName,z.chengfen,z.jingmi,z.weimi,z.shazhi,z.menfu
			from caigou_order x
			left join caigou_order2product y on x.id=y.caigouId
			left join jichu_product z on z.proCode=y.productId
			left join jichu_supplier a on a.id=x.supplierId
			left join jichu_employ e on e.id=x.employId
			where 1 and x.id='{$_GET['id']}'";
		$row=$this->_modelExample->findBySql($sql);
		foreach ($row as &$v) {
			$v['jiaoqi']=$v['jiaoqi']=='0000-00-00'?'':$v['jiaoqi'];
		}
		// dump($row);exit;
		// dump(count($row));exit;
		$smarty=& $this->_getView();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=采购合同.xls");
		header("Content-Transfer-Encoding: binary");
		$smarty->assign('title','导出');
		$smarty->assign('aRow',$row);
		$smarty->display('Caigou/export.tpl');
	}
	/**
	* ps ：采购合同分析报表
	* Time：2015/10/22 15:13:23
	* @author jiang
	*/
	function actionReportOrder(){
		//权限判断
		$this->authCheck('14-7');
		FLEA::loadClass('TMIS_Pager');

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' => date("Y-m-d"),
			'danhao'=>'',
			'supplierId'=>'',
			'proCode' => '',
			'isRkOver'=>'2'
		));
		$sql="select o.*,x.cntM as caicnt,sum(y.cntM) as rucnt,x.unit,x.jiaoqi,
				x.danjia,x.memo,s.compName,p.proCode,p.proName,p.chengfen,
				p.shazhi,p.menfu,p.jingmi,p.weimi,p.colornum,p.colorsnum,p.pinming
				from caigou_order2product x
				left join caigou_order o on o.id=x.caigouId
				left join cangku_ruku2product y on x.id=y.cai2proId
				left join cangku_ruku r on r.id=y.rukuId
				left join jichu_product p on p.proCode=x.productId
				left join jichu_supplier s on s.id=o.supplierId
				where 1";

		if($arr['dateFrom'] != '') {
			$sql .=" and o.orderDate >= '{$arr['dateFrom']}' and o.orderDate <= '{$arr['dateTo']}'";
		}
		if($arr['supplierId']!='') $sql .=" and o.supplierId='{$arr['supplierId']}' ";
		if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
		if($arr['danhao']!='') $sql .=" and o.orderCode like '%{$arr['danhao']}%' ";
		if($arr['isRkOver']==1) $sql .=" and x.rukuOver=1";
        if($arr['isRkOver']==0) $sql .=" and x.rukuOver=0";
		$sql.=" group by x.id order by orderCode desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		//dump($rowset);die;
		$_modeShenhe2Node = FLEA::getSingleton('Model_Shenhe_Shenhe2Node');
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['orderDate']=$v['orderDate']=='0000-00-00'?'':$v['orderDate'];
			$v['jiaoqi']=$v['jiaoqi']=='0000-00-00'?'':$v['jiaoqi'];
			if($v['jiaoqi']!=''&&$v['jiaoqi']<date('Y-m-d')) $v['_bgColor']="pink";

			$v['wrucnt'] = $v['caicnt'] - $v['rucnt'];
		}
		$hj=$this->getHeji($rowset,array('caicnt','rucnt','wrucnt'),'orderCode');
		$rowset[]=$hj;
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			'orderCode'=>'单号',
			"orderDate" => "采购日期",
			'compName'=>'供应商',
			'jiaoqi' => array('text'=>'交期','width'=>120),
			'proCode'=>'花型六位号',
			'proName'=>'产品名称',
			"pinming" => "品名",
			"colornum" => "款号",
			"colorsnum" => "颜色号",
			// "color" => "颜色",
			'caicnt'=>'采购数量(M)',
			'rucnt'=>'已入库数量(M)',
			'wrucnt'=>'未入库数量(M)',
			// 'unit'=>'单位',
			'danjia'=>'单价',
			"memo" => '备注',
		);
		$smarty->assign('title', '采购合同分析报表');
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr))."<font color='red'>   红色表示日期超出交期</font>");
		$smarty->display('TblList.tpl');
	}
	/**
	 * ps ：采购合同待审核
	 * Time：2015-10-27 13:49:07
	 * @author shen
	*/
	function actionShenheWaiting(){
		$this->authCheck('1-3-4');
		FLEA::loadClass('TMIS_Pager');

		$sql="select x.*,a.compName,e.employName
			from caigou_order x
			left join jichu_supplier a on a.id=x.supplierId
			left join jichu_employ e on e.id=x.employId
			where 1 and x.shenhe<>'yes'";
		$sql.=" order by orderCode desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		if (count($rowset) > 0) foreach($rowset as &$v) {
			$v['orderDate']=$v['orderDate']=='0000-00-00'?'':$v['orderDate'];
			//明细显示
			$str="select y.id as sid,if(y.jiaoqi='0000-00-00','',y.jiaoqi) as jiaoqi,z.color,y.cnt,y.unit,y.danjia,y.memo,z.proCode,z.proName,z.chengfen,z.shazhi,z.menfu,CONCAT(z.jingmi,'*',z.weimi) as jwmi
				from caigou_order2product y
				left join jichu_product z on z.proCode=y.productId
				where 1 and y.caigouId='{$v['id']}'";
			$ret=$this->_modelExample->findBySql($str);
			$v['Products']=$ret;
		}
		$smarty = &$this->_getView();
		$arrFieldInfo = array(
			'orderCode'=>'单号',
			"orderDate" => "采购日期",
			"employName" => "采购人",
			'compName'=>'供应商',
			"memo" => '备注',
			"shTime" => '重新申请次数',
		);
		$arrField = array(
			'jiaoqi'=>'产品交期',
			'proCode'=>'花型六位号',
			'proName'=>'品名',
			'chengfen'=>'成分',
			'shazhi' =>'纱支',
			'jwmi' =>'经纬密',
			'menfu' =>'门幅',
			'cnt' =>'要货数',
			'unit' =>'单位',
			'danjia' =>'单价',
			'memo' =>'备注',
		);
		$smarty->assign('arr_field_info', $arrFieldInfo);
		$smarty->assign('arr_field_info2', $arrField);
		$smarty->assign('sub_field', 'Products');
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		$smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
		$smarty->display('TblListMore.tpl');
	}

	/**
	 * 即将交期
	 * Time：2016-2-26 12:50:41
	 * @author jiangxu
	*/
	function actionWillJiaoqi(){
		$this->authCheck('2-3');
		FLEA::loadClass('TMIS_Pager');
		$dateFrom=date('Y-m-d H:i:s');
		$dateTo = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+3,date('Y')));//获得三天的时间
		$sql="select
			x.*,
			y.id as sId,
			y.danjia,
			y.memo,
			y.cnt,
			y.cntM,
			y.unit,
			y.jiaoqi,
			y.rukuOver,
			c.compName,
			p.proCode,
			p.proName,
			p.menfu,
			p.wuliaoKind,
			p.zuzhi,
			f.employName
			from caigou_order x
			left join caigou_order2product y on x.id=y.caigouId
			left join jichu_employ f on f.id=x.employId
			left join jichu_supplier c on c.id=x.supplierId
			left join jichu_product p on p.proCode=y.productId
			where y.jiaoqi>='{$dateFrom}' and y.jiaoqi <= '{$dateTo}' ";
		$sql.=" order by y.jiaoqi desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		foreach($rowset as & $v) {
			$v['jiaoqi']=$v['jiaoqi']=='0000-00-00 00:00:00'?'':$v['jiaoqi'];

			//数量
			$v['cnt'] = round($v['cnt'],3).' '.$v['unit'];
		}

		$smarty = & $this->_getView();
		$arrField = array(
			'orderCode' =>array('text'=>'采购单号','width'=>130),
			'orderDate'=>array('text'=>'采购日期','width'=>100),
			'employName'=>array('text'=>'采购人','width'=>70),
			'supplierId'=>array('text'=>'供应商','width'=>70),
			'jiaoqi'=>array('text'=>'交期','width'=>100),
			'proCode'=>array('text'=>'花型六位号','width'=>100),
			'proName'=>array('text'=>'产品名称','width'=>70),
			'cnt' =>array('text'=>'数量','width'=>70),
			'unit' =>array('text'=>'单位','width'=>70),
			'danjia' =>array('text'=>'单价','width'=>70),
			'memo' =>array('text'=>'备注','width'=>80),

		);
		$smarty->assign('arr_field_info', $arrField);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_field_value', $rowset);
		$smarty->display('TblList.tpl');
	}
}

?>