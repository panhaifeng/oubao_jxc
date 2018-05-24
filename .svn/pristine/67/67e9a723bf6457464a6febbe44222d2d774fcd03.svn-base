<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Ruku extends TMIS_Controller {

	/**
	 * 一对多模板的主信息集合
	 * @var array
	*/
	var $fldMain;

	/**
	 * 一对多模板的子信息集合
	 * @var array
	*/
	var $headSon;

	/**
	 * 表单元素的验证规则
	 * 进行有效性验证
	 * @var array
	*/
	var $rules;

	/**
	 * 仓库主表的model实例化
	 * @var object
	*/
	var $_modelExample;

	/**
	 * 仓库子表的model实例化
	 * @var object
	*/
	var $_subModel;

	/**
	 * 仓库名字
	 * 仓库大类：现货仓库/样品仓库/大货仓库
	 * @var string
	*/
	var $_cangkuName;

	/**
	 * 出入库类型
	 * 如：采购入库/加工入库/其他入库等
	 * @var string
	*/
	var $_kind;

	/**
	 * 出入库单号编码前缀
	 * @var string
	*/
	var $_head;

	/**
	 * 出入库单号编码前缀
	 * @var string|array 都支持
	*/
	var $sonTpl;

    /**
     * 查询界面为了区分采购入库是那个仓库入库的
     * @var string
    */
    var $_mold;

    /**
     * 添加登记界面的权限
     * @var array
    */

	/**
	 * 构造函数
	 * Time：2015/09/10 13:43:51
	 * @author li
	*/

     /**
     * 权限设置
     * @var string
    */
    var $_check;
	function __construct() {
		//model
		$this->_modelExample = &FLEA::getSingleton('Model_Cangku_Ruku');
		$this->_subModel = &FLEA::getSingleton('Model_Cangku_Ruku2Product');
        $this->_modelJh = &FLEA::getSingleton('Model_Cangku_Tuiku');
        // $this->_rukushenhe = &FLEA::getSingleton('Model_Cangku_RukuShenhe');

		//出库主信息
		$this->fldMain = array();

		//子信息
		$this->headSon = array();

		//表单元素的验证规则定义
		$this->rules = array();
	}


	function actionRight(){
		FLEA::loadClass('TMIS_Pager'); 

		//构造搜索区域的搜索类型
		$arr = TMIS_Pager::getParamArray(array(
			'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
			'dateTo' => date("Y-m-d"),
            'danhao'=>'',
			'hetongCode'=>'',
			'kuweiId_name'=>'',
			'kuweiName'=>$this->_cangkuName,
			'kind'=>'',
			'proCode'=>'',
            'jiagonghuId'=>'',
		)); 
		$sql="select x.*,y.id as rukuId,y.rukuDate,y.rukuCode,y.kind,k.kuweiName,c.orderCode,
				p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,p.menfu,p.kezhong,s.compName as jiagonghu
				from cangku_ruku2product x
				left join cangku_ruku y on x.rukuId=y.id
				left join jichu_kuwei k on k.id=y.kuweiId
				left join caigou_order c on c.id=y.caigouId
				left join jichu_product p on p.proCode=x.productId
                left join jichu_supplier s on s.id=y.jiagonghuId
				where 1 and cangkuName='{$this->_cangkuName}' ";

        if($arr['dateFrom'] != '') {
			$sql .=" and y.rukuDate >= '{$arr['dateFrom']}' and y.rukuDate <= '{$arr['dateTo']}'";
		}
        if($arr['danhao']!='') $sql .=" and y.rukuCode like '%{$arr['danhao']}%' ";
		if($arr['hetongCode']!='') $sql .=" and c.orderCode like '%{$arr['hetongCode']}%' ";
		if($arr['kuweiId_name']!='') $sql .=" and y.kuweiId = '{$arr['kuweiId_name']}' ";
		if($arr['kind']!='') $sql .=" and y.kind = '{$arr['kind']}' ";
		if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
        if($arr['jiagonghuId']!='') $sql .=" and y.jiagonghuId = '{$arr['jiagonghuId']}' ";
		$sql.="order by rukuDate desc,rukuCode desc";
        if ($_GET['export']==2) {
            $rowset = $this->_modelExample->findBySql($sql);
        } else {
    		$pager = &new TMIS_Pager($sql);
    		$rowset = $pager->findAll();            
        }
		// dump($rowset);die;
		foreach($rowset as & $v) {
			$v['rukuDate']=$v['rukuDate']=='0000-00-00'?'':$v['rukuDate'];

			//2015-10-29 by jiang 已过账禁止修改和删除
            $sql="select * from caiwu_yf_guozhang where ruku2ProId='{$v['id']}'";
            $ret=$this->_modelExample->findBySql($sql);
            if(count($ret)>0){
                $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='已过账禁止修改和删除'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已过账禁止修改和删除'></span>";
            }else{
				if($v['kind']=='采购入库'){ 
					$v['_edit']="<a href='".url('Cangku_'.$this->_mold.'_CaigouRk','Edit',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
	            }
				else if($v['kind']=='加工入库'){
	                $v['_edit']="<a href='".url('Cangku_'.$this->_mold.'_JiagongRk','Edit',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
	            }
	            else if($v['kind']=='采购退库'){
	                $v['_edit']="<a href='".url('Cangku_'.$this->_mold.'_CaigouTk','Edit',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-pencil' title='修改'></span></a>";
	                }
	            else{
					$v['_edit'] = $this->getEditHtml($v['rukuId']);
	            }

	            if($v['kind']=='采购退库'){
	                $v['_edit'].="&nbsp;<a href='".url('Cangku_'.$this->_mold.'_CaigouTk','Remove',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-trash text-danger' title='删除'></span></a>";
	            }
				else{
	                $v['_edit'].=' ' .$this->getRemoveHtml($v['rukuId']);
	            }
	        }

            
            //调货
            if($v['kind']=='调货' ){
            	$v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='调货数据不能修改'></span>";
				$v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash' ext:qtip='调货数据不能删除'></span>";
            }
            //退换入库
            if($v['kind']=='退换入库' ){
            	$v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='退换入库数据不能在此页面修改'></span>";
				$v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash' ext:qtip='退换入库数据不能在此页面删除'></span>";
            }

            //只有现货仓库需要查看码单和打印入库标签
            if($this->_cangkuName==__CANGKU_1){
            	$v['_edit'].="&nbsp;<a href='".$this->_url('MadanCheck',array(
                	'id'=>$v['id'],
                	'fromAction'=>$_GET['action'],
                	'width'=>'700',
    				'height'=>'400',
            	))."' class='thickbox'><span class='glyphicon glyphicon-zoom-in' title='查看码单'></span></a>";
                $v['_edit'].="&nbsp;<a href='".$this->_url('MadanPrint',array(
                    'id'=>$v['id']
                ))."'><span class='glyphicon glyphicon-comment' title='打印入库标签'></span></a>";

                  if($v['kind']=='采购退库'){
	                $v['_edit'].= "&nbsp;<a href='".$this->_url('PrintJh',array(
		                'id'=>$v['rukuId'],
		                'fromAction'=>$_GET['action'],
		                'fromController'=>$_GET['controller'],
		        ))."'><span class='glyphicon glyphicon-inbox' ext:qtip='拣货单打印'></span></a>";
	                $v['_edit'].= "&nbsp;<a href='".$this->_url('Print',array(
							'id'=>$v['rukuId'],
							'fromAction'=>$_GET['action'],
							'fromController'=>$_GET['controller'],
					))."'><span class='glyphicon glyphicon-print' ext:qtip='打印出库单'></span>{$_msg}</a>";
	            }
            }

            //采购退货
            if($v['kind']=='采购入库'){
                 $v['_edit'].="&nbsp;<a href='".url('Cangku_'.$this->_mold.'_CaigouTk','Add',array('id'=>$v['rukuId'],'fromAction'=>$_GET['action']))."'><span class='glyphicon glyphicon-log-out' title='采购退库登记'></span></a>";
            }
			$v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
			
		} 
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'_edit'); 
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'].' M';

        //导出去掉合计标签
        if($_GET['export']==1 || $_GET['export']==2){
            $hj['rukuCode'] = strip_tags($hj['_edit']);
        	$hj['cnt']=$hj['cntM'];
            unset($hj['_edit']);
        }
        
        $rowset[]=$hj;
        
		$smarty = &$this->_getView(); 
		// 左侧信息
		$arr_field_info = array(
			"_edit" => array('text'=>'操作','width'=>105),
			"rukuCode" => '单号',
			"rukuDate" => '入库日期',
			"kuweiName" => '库位',
			"kind" => '类型',
			"orderCode" => '采购合同',
            "jiagonghu" => '加工户',
			"proCode" => '花型六位号',
			"proName" => '品名',
			"chengfen" => '成分',
			"jwmi" => '经纬密',
			"menfu" => '门幅',
			"cnt" => '数量',
            "cntJian" => '卷数',
			"unit" => '单位'
		);

		$smarty->assign('title', '入库列表');
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		if(!isset($_GET['export'])) {
            //加入序号

            $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        }
		$smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
        $smarty->assign('fn_export1',$this->_url($_GET['action'],array('export'=>2)));
        if($_GET['export']==1){
            foreach ($rowset as $key => & $value) {
                foreach ($value as $key => & $v) {     
                	$v = strip_tags($v);
                }
                unset($value['_edit']);
            }
            unset($arr_field_info['_edit']);
            $this->_exportList(array('title'=>$title),$smarty);
        } elseif ($_GET['export']==2) {
            unset($arr_field_info['_edit']);
            $arr_field_info = array_merge(array('num'=>'序号') ,$arr_field_info);
            $i=1;
            foreach($rowset as & $v) {
                $v['num'] = $i++;
            }
            $smarty->assign('arr_field_info', $arr_field_info);
            $smarty->assign('arr_field_value', $rowset);
            $this->_exportList(array('title'=>$title),$smarty);
        }
		$smarty->display('TblList.tpl');
	}

	/**
	 * 仓库入库登记
	 * Time：2015/09/10 14:03:08
	 * @author li
	*/
	function actionAdd(){
        $this->authCheck($this->_check);
		$rowsSon = array_fill(0,5,array());
		// dump($rowsSon);exit;

		$smarty = &$this->_getView();
		$areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);
		$smarty->assign('areaMain', $areaMain);		
		$smarty->assign('headSon', $this->headSon);
		$smarty->assign('rowsSon', $rowsSon);

		//
		$_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
		$smarty->assign('fromAction', $_from);
		$smarty->assign('rules', $this->rules);
		$smarty->assign('sonTpl', $this->sonTpl);
		$this->_beforeDisplayAdd($smarty);
		$smarty->display('Main2Son/T1.tpl');
	}

	function actionEdit() {
		$arr = $this->_modelExample->find(array('id' => $_GET['id']));
        foreach ($cai['Products'] as & $c) {
            $retCai[$c['id']]=$c;
        }

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        //仓库信息
        if($arr['kuweiId']>0){
            $sql="select kuweiName from jichu_kuwei where id='{$arr['kuweiId']}'";
            $temp=$this->_subModel->findBySql($sql);
            $this->fldMain['kuweiId'] && $this->fldMain['kuweiId']['text']=$temp[0]['kuweiName'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $v['productId'] = $_temp[0]['proCode'];
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['chengfen'] = $_temp[0]['chengfen'];
            $v['shazhi'] = $_temp[0]['shazhi'];
            $v['menfu'] = $_temp[0]['menfu'];
            $v['jwmi'] = $_temp[0]['jingmi'].'*'.$_temp[0]['weimi'];

            $v['danjia'] = round($v['danjia'],6);

            //查找码单信息，并json_encode
            $sql="select y.* from madan_rc2madan x
                    left join madan_db y on x.madanId=y.id
                    where x.rukuId='{$v['id']}'";
            $retMadan = $this->_modelExample->findBySql($sql);
            $_temp=array();
            foreach($retMadan as & $m){
            	//当码单被锁定或者已出库则不能修改码单
            	if ($m['status'] !='active') {
                	$m['readonly']=true;
            	}
                $_temp[$m['rollNo']-1]=$m;
            }
            $_temp['isCheck']=1;
            $v['Madan'] = json_encode($_temp);

            //获取已入库数量
            $sql="select sum(cnt) as cnt from cangku_ruku2product where cai2proId='{$v['cai2proId']}' group by cai2proId";
            $retcnt=$this->_modelExample->findBySql($sql);
            $v['cntYr']=$retcnt[0]['cnt'];
        }

        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $temp['productId']['text']=$v['proCode'];
            $temp['cntCg']['value']=$retCai[$v['cai2proId']]['cnt'];
            $rowsSon[] = $temp;
        }

        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('fromAction', $_GET['fromAction']);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $this->_beforeDisplayEdit($smarty);
        $smarty->display('Main2Son/T1.tpl');
	}

	/**
	 * 数据集传递到修改编辑界面前的处理操作
	 * Time：2014/06/24 17:24:05
	 * @author li
	*/
	function _beforeDisplayEdit(& $smarty){
		return true;
	}

	/**
	 * 数据集传递到修改编辑界面前的处理操作
	 * Time：2014/06/24 17:24:05
	 * @author li
	*/
	function _beforeDisplayAdd(& $smarty){
		return true;
	}

	
	/**
	 * 
	 * Time：2015/09/10 13:59:22
	 * @author li
	 * @param POST 数据表单提交post
	*/
	function actionSave(){
		//有效性验证,没有明细信息禁止保存
		//开始保存
		$pros = array();
		foreach($_POST['productId'] as $key=> & $v) {
			if($v=='' || empty($_POST['cnt'][$key]) || $_POST['pihao'][$key]=='') continue; 
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
        // dump($pros);die;
		if(count($pros)==0) {
			js_alert('批号、产品、数量为必填信息!','window.history.go(-1)');
			exit;
		}
		/**
		 * @author chenran
		*/
		//采购入库时，入库卷数要是必填项
		foreach($pros as $key=> & $v){
			if($v['cntJian']==''){
		    	js_alert('入库卷数为必填项!','window.history.go(-1)');
		    }
		}
		$row = array();
		foreach($this->fldMain as $k=>&$vv) {
			$name = $vv['name']?$vv['name']:$k;
			$row[$k] = $_POST[$name];
		}
        if($row['rukuCode']=='自动生成') $row['rukuCode']=$this->_getNewCode($this->_head,'cangku_ruku','rukuCode');
		$row['Products'] = $pros;
		/*
		* 处理码单信息
		*/
		foreach($row['Products'] as & $v){
			$v['Madan'] = stripslashes($v['Madan']);
			// dump($v['Madan']);die;
			$madan = json_decode($v['Madan']);
			$_temp=array();
			foreach($madan as & $m){
				$m = (Array)$m;
				//数量不存在，说明该码单不需要保存
				if(empty($m['cntFormat'])){
					//如果id存在，则说明该码单需要在数据表中删除
					if($m['id']>0){
						$v['madan_clear'][]=$m['id'];
					}
					continue;
				}
				$m['rukuDate']=$row['rukuDate'];
				$m['productId']=$v['productId'];
				$m['millNo']=$v['pihao'];
				$m['unit']=$v['unit'];
				//将单位转换为米保存
				$m['unit']=='' && $m['unit']='M';//默认不能为空
				if($m['unit']=='Y') $m['cntM']=0.9144*$m['cnt'];
				else $m['cntM']=$m['cnt'];

				if($m) $_temp[]=$m;
			}
            $v['Madan'] = $_temp;
            //json保存在审核表中
			$v['MadanJSon'] = json_encode($v['Madan']);
		}
        // dump($row);die;
        //保存到入库审核表中 
        if(!$this->_modelExample->save($row)) {
		// if(!$this->_rukushenhe->save($row)) {
			js_alert('保存失败','window.history.go(-1)');
			exit;
		}
		//跳转
		js_alert(null,'window.parent.showMsg("保存成功!")',url($_POST['fromController'],'Right',array()));
	}

	/**
     * 显示码单入库信息
     * Time：2015/09/07 15:16:43
     * @author jiang
    */
    function actionViewMadan(){
        $smarty = & $this->_getView();
        $smarty->assign('title', "码单编辑");
        $smarty->display("Cangku/MadanEdit.tpl");
    }
    /**
     * 打印码单入库标签
     * Time：2016年4月8日10:55:00
     * @author jiangxu
    */
    function actionMadanPrint(){
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
        )); 
        $sql="SELECT x.*,a.chengfen,a.menfu,a.proName 
                from madan_db x
                left join madan_rc2madan y on y.madanId=x.id
                left join cangku_ruku2product z on y.rukuId=z.id
                left join jichu_product a on a.proCode=z.productId
                where y.rukuId='{$_GET['id']}' order by x.rollNo";
        $rowset=$this->_modelExample->findBySql($sql);
        // dump($rowset);exit;
        $smarty = & $this->_getView();
        $smarty->assign('row',$rowset);
        $smarty->display("Cangku/PrintRklab.tpl");
    }    
    /**
     * 打印采购退库运货单
     * Time：2016年4月11日10:55:00
     * @author jiangxu
    */

    	function actionPrint(){
    		// dump($_GET);
		$ids = $_GET['id'];
		$sql= "select a.*,s.compName,s.address,s.tel,s.people,p.menfu,p.kezhong
			from madan_db a
			left join madan_rc2madan b on b.madanId = a.id
			left join jichu_product p on p.proCode=a.productId
			left join cangku_ruku2product y on y.id = b.rukuId
			left join cangku_ruku x on x.id = y.rukuId
			left join caigou_order cg on cg.id = x.caigouId
			left join jichu_supplier s on s.id = cg.supplierId
			where x.id ='{$_GET['id']}' and a.status ='finish' ";

        $temp=$this->_modelJh->findBySql($sql);
        // dump($temp);exit;
        foreach ($temp as $k => $v) {
        	$rowset[$k]['productId']=$v['productId'];
        	$rowset[$k]['cnt']=$v['cnt'];
        	$rowset[$k]['unit']=$v['unit'];
        	$rowset[$k]['rollNo']=$v['rollNo'];
        	$rowset[$k]['millNo']=$v['millNo'];
        	$rowset[$k]['baleNo']=1;
        	$rowset[$k]['qrcode']=$v['qrcode'];
        	$rowset[$k]['compName']=$v['compName'];
        	$rowset[$k]['address']=$v['address'];
        	$rowset[$k]['tel']=$v['tel'];
        	$rowset[$k]['people']=$v['people'];
        	$rowset[$k]['menfu']=$v['menfu'];
        	$rowset[$k]['kezhong']=$v['kezhong'];
        	$rowset[$k]['color']=$v['color'];
        	$rowset[$k]['chengfen']=$v['chengfen'];
        	$rowset[$k]['guige']= $v['menfu'].' '.$v['kezhong'].' '.$v['color'].' '.$v['chengfen'];
        }

		// foreach ($rowset as & $vv) {
		// 	//支付方式
		// 	$v['payment'] = $this->getPayment($vv['payment']);
		// 	//
		// 	if($vv['ship_mobile']==''){
		// 		$vv['ship_mobile']=$vv['ship_tel'];
		// 	}
		// 	//当注释为空时，将，替换掉不显示
		// 	// $vv['memo']=str_replace(',','',$vv['memo']);
		// 	$vv['cnt'] = round($vv['cnt'],2);
	 //        $sql="SELECT count(x.rollNo)as rollNo 
	 //        	from madan_db x 
	 //        	left join ph_peihuo2madan y on x.id=y.madanId
	 //        	left join ph_peihuo z on y.phId=z.id
	 //        	left join chuku_plan a on a.peihuoId=z.id
	 //        	where a.orderId ='{$ids}' group by a.productId";
	 //        $ret=$this->_modelExample->findBySql($sql);
	 //        foreach ($ret as $key => &$value) {
	 //        	$rowset[$key]['rollNo']=$value['rollNo'];
	 //        }
	 //        $vv['guige'] = $vv['menfu'].' '.$vv['kezhong'].' '.$vv['color'].' '.$vv['chengfen'];
		// }
		// dump($rowset);die;
		$rowset[0]['person']=$_SESSION['REALNAME'];
		$rowset[0]['time']=date('y-m-d h:i:s',time());
		$rowset[0]['chukuDate']=date('y-m-d',time());
		// dump($rowset);exit;
		$heji=$this->getHeji($rowset,array('rollNo','baleNo','cnt'));
		$smarty = & $this->_getView();
		$smarty->assign('rowset', $rowset);
		$smarty->assign('heji',$heji);
		$smarty->display("Cangku/PrintSh.tpl");
	}
    /**
	 * 拣货单打印
	 * Time：2016年4月8日10:22:58
	 * @author jiangxu
	 */

	function actionPrintJh(){
		//根据后台传输的orderId
		$id=$_GET['id'];
		$sql= "select a.*
			from madan_db a
			left join madan_rc2madan b on b.madanId = a.id
			left join cangku_ruku2product y on y.id = b.rukuId
			left join cangku_ruku x on x.id = y.rukuId
			where x.id ='{$_GET['id']}' and x.kind ='采购退库' ";
        $temp=$this->_modelJh->findBySql($sql);
        foreach ($temp as $k => $v) {
        	$arr[$k]['millNo']=$v['millNo'];
        	$arr[$k]['cnt']=$v['cnt'];
        	$arr[$k]['rollNo']=$v['rollNo'];
        	$arr[$k]['qrcode']=$v['qrcode'];
        	$arr[$k]['kuqu']=$v['kuqu'];
        }
        $cntJian=count($arr);
		$hj=$this->getHeji($temp,array('cnt'));
		// dump($hj);die;
		$time=date('Y-m-d H:i:s');
		//$heji为总数量合计
		// $heji=$this->getHeji($arr,array('cnt'));
		$smarty = & $this->_getView();
		$smarty->assign('time', $time);
		$smarty->assign('arr', $arr);
		$smarty->assign('hj', $hj);
		$smarty->assign('cntJian',$cntJian);
        $smarty->display('Cangku/PrintTjhd.tpl');
    
	}
    /**
     * 查看码单
     * Time：2015-10-15 17:36:07
     * @author shen
    */
    function actionMadanCheck(){
        // dump($_GET);
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
        )); 
        $sql="SELECT y.* 
        	from madan_rc2madan x
            left join madan_db y on x.madanId=y.id
            where x.rukuId='{$_GET['id']}' order by y.millNo,y.rollNo";
        $rowset =$this->_modelExample->findBySql($sql); 
        // dump($rowset);die;
        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            // "id" => '编号',
			"productId" => array('text'=>'花型六位号','width'=>95),
            "millNo" => '批次号',
            "cntM" => '米数',
            "rollNo" => '卷号',
            "qrcode" => '条码',
            "kuqu" => '库位',
        );
        // dump($pager->getNavBar($this->_url($_GET['action'], $arr)));die;
        $smarty->assign('title', '查看码单');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        // $smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }

    /*
    * ps：码单导入
    * time:2015-12-07 shen
    */

    function actionSaveMadanExport(){
        $temp=array();$arr=array();
        foreach ($_FILES as $k=> &$v){
            for($i=0;$i<count($v['name']);$i++){
                foreach ($v as $key=> &$value){
                    $temp[$key]=$value;
                }
                $arr[][$k]=$temp;
            }
        }
        if($arr['0']['madanExport2']['name']==''){
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
        $filePath=$filePath['madanExport2']['0']['filePath'];
        $arr = $this->_readExcel($filePath,0); 
        $temp_juan=0;
        foreach ($arr as $key => &$v) {
            if ($key <1) continue;
            if(!$v[0]) continue;
            $sql="select id from jichu_kuqu where kuquName='$v[4]'";
            $kuquId=$this->_modelExample->findBySql($sql);
            $data[]=array(
                'productId'=>(string)$v[0],
                'rollNo'=>(string)$v[1],
                'cnt'=>(string)$v[2],
                'cntFormat'=>(string)$v[2],
                'qrcode'=>(string)$v[3],
                'kuqu'=>(string)$v[4],
                'pihao'=>(string)$v[5],
                'kuquId'=>$kuquId[0]['id'],
                );
            $cnt+=$v[2];
        }
        $cntJian=count($data);
        ksort($data);

        //根据花型六位号对$data数组进行汇总 
        foreach($data as $k=> &$v) {
            $res[$v["productId"]][] = $v; 
        }
        $data=array_values($res);
        $_tempRes=array();
        foreach ($res as $key => & $r) {
            foreach ($r as & $s) {
                $_tempRes[$key][$s['rollNo']]=$s;
            }
            //求数组卷号最大值
            rsort($r);
            $temp_juan=$r[0]['rollNo'];
            for($i=1;$i<=$temp_juan;$i++){
                if(!is_array($_tempRes[$key][$i])){
                    $_tempRes[$key][$i]=null;
                }
            }
            ksort($_tempRes[$key]);
            $ttt[]=array_values($_tempRes[$key]);
        }
        // 向每行数据中加入 jsonData = "当前行的json格式数据"；
        foreach ($data as $key => &$rowJson) {
            foreach ($ttt as &$w) {
                $rowJson['jsonData'][] = json_encode($w);
            }
        }

        //处理$data数组 获取卷数和总数量
        foreach ($data as $key => &$value){
            $value['rollNo']=count($value)-1;
            $value['productId']=$value[0]['productId'];
            $value['pihao']=$value[0]['pihao'];
            $json_d['data']=$value['productId'];
        	unset($value['productId']);
            foreach ($value as &$v2) {
                $value['cnt']+=$v2['cnt'];
            }
            $value['productId']=$json_d['data'];
        }
        $result=array_values($data);
        // dump($data);die;
        $jsonData[]=json_encode($result);
        echo json_encode(array('success'=>true,'data'=>$jsonData,'dataMadan'=>$data,'jian'=>$cntJian,'cnt'=>$cnt));exit;
    }

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
        // dump($dizhi['path']['name']);die;
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
    
    /*
    * 
    * ps:取得库区的ID
    */
    function actionGetkuquId(){
        $sql="select id from jichu_kuqu where kuquName='{$_POST['kuquName']}'";
        $res=$this->_modelExample->findBySql($sql);
        if(!$res){
          echo json_encode(array('success'=>false,'msg'=>'请查看库区是否存在！'));exit;
        }
        echo json_encode(array('success'=>true,'data'=>$res[0]['id']));exit;
    }
}

?>