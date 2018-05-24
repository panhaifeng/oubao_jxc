<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Chuku extends TMIS_Controller {

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
	 * 如：采购出库/加工出库/其他出库等
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
	 * 构造函数
	 * Time：2015/09/10 13:43:51
	 * @author li
	*/
	function __construct() {
		//model
		$this->_modelExample = &FLEA::getSingleton('Model_Cangku_Chuku');
		$this->_subModel = &FLEA::getSingleton('Model_Cangku_Chuku2Product');

		//出库主信息
		$this->fldMain = array();

		//子信息
		$this->headSon = array();

		//表单元素的验证规则定义
		$this->rules = array();
	}


	function actionRight(){
		//权限判断
		FLEA::loadClass('TMIS_Pager'); 
		$arr = TMIS_Pager::getParamArray(array(
			'proCode'=>'',
			'clientId'=>'',
			'kuweiId_name'=>'',
			'kuweiName'=>$this->_cangkuName,
		)); 

		$condition=array();
		$condition[]=array('Ck.cangkuName',$this->_cangkuName,'=');
		$condition[]=array('Ck.kind',$this->_kind,'=');

		if($arr['proCode']!=''){
			$condition[]=array('productId',"%{$arr['productId']}%",'like');
		}
		if($arr['kuweiId_name']!=''){
			$condition[]=array('Ck.kuweiId',"{$arr['kuweiId_name']}",'=');
		}
		if($arr['clientId']!=''){
			$condition[]=array('Ck.clientId',"{$arr['clientId']}",'=');
		}

		//查找计划
		$pager = &new TMIS_Pager($this->_subModel,$condition,'chukuCode desc');
		$rowset = $pager->findAll();

		// dump($rowset);exit;
		foreach($rowset as & $v) {
			$v['_edit'] = $this->getEditHtml($v['Ck']['id']);
			//删除操作
			$v['_edit'] .= ' ' .$this->getRemoveHtml($v['Ck']['id']);

			//查找对应的产品明细信息
			$sql="select proCode,proName,zhengli,menfu,kezhong,chengfen,wuliaoKind,zuzhi 
				from jichu_product where proCode='{$v['productId']}'";
			$res = $this->_modelExample->findBySql($sql);
			// dump($sql);exit;
			$v+=$res[0];

			//查找库位
			$sql="select * from jichu_kuwei where id='{$v['Ck']['kuweiId']}'";
			$res = $this->_modelExample->findBySql($sql);
			$v['cangkuName'] = $res[0]['kuweiName'];

			//查找订单与客户信息
			$sql="select x.orderCode ,if(c.compName='',c.compCode,compName) as compName
			from trade_order x 
			left join jichu_client c on c.member_id = x.clientId
			where x.id = '{$v['orderId']}'";
			$res = $this->_modelExample->findBySql($sql);
			$v['orderCode'] = $res[0]['orderCode'];
			$v['compName'] = $res[0]['compName'];
		} 
		$rowset[]=$this->getHeji($rowset,array('cnt','cntJian'),'_edit');

		$smarty = &$this->_getView(); 
		// 左侧信息
		$arr_field_info = array(
			"_edit" => '操作',
			"Ck.chukuCode" => '出库单号',
			"Ck.chukuDate" => '出库日期',
			"orderCode" => '订单号',
			"compName" => '客户',
			"proCode" => '花型六位号',
			"proName" => '品名',
			"chengfen" => '成分',
			"menfu" => '门幅',
			"kezhong" => '克重',
			"wuliaoKind" => '物料大类',
			"zhengli" => '整理方式',
			"zuzhi" => '组织大类',
			"cangkuName" => '库位',
			"cnt" => '数量',
			"unit" => '单位',
		);

		$smarty->assign('title', '入库列表');
		$smarty->assign('arr_field_info', $arr_field_info);
		$smarty->assign('add_display', 'none');
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('arr_field_value', $rowset);
		// $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
		$smarty->display('TblList.tpl');
	}

	/**
	 * 仓库入库登记
	 * Time：2015/09/10 14:03:08
	 * @author li
	*/
	function actionAdd(){
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
			$v['proCode'] = $_temp[0]['proCode'];
			$v['proName'] = $_temp[0]['proName'];
			$v['guige'] = $_temp[0]['guige'];

			$v['danjia'] = round($v['danjia'],6);
		}

		foreach($arr['Products'] as &$v) {
			$temp = array();
			foreach($this->headSon as $kk => &$vv) {
				$temp[$kk] = array('value' => $v[$kk]);
			}
			$rowsSon[] = $temp;
		}

		//补齐5行
		$cnt = count($rowsSon);
		for($i=5;$i>$cnt;$i--) {
			$rowsSon[] = array();
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
			if($v=='' || empty($_POST['cnt'][$key])) continue;
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

		if(!$pros){
            js_alert('保存失败，没有有效明细数据','window.history.go(-1)');
            exit;
        }
		$row['Products'] = $pros;
		// dump($row);exit;
		if(!$this->_modelExample->save($row)) {
			js_alert('保存失败','window.history.go(-1)');
			exit;
		}

		//跳转
		js_alert(null,'window.parent.showMsg("保存成功!")',url($_POST['fromController'],$_POST['fromAction']));
	}

	/**
	 * 发货状态同步订单发货状态
	 * Time：2015/09/22 14:55:04
	 * @author li
	*/
	function ship_status_toec($pros_order){
		// dump($data);exit;
		foreach ($pros_order as $key => & $v) {
            //查找订单信息
            $sql="select 
            x.orderCode,
            sum(y.cntM) as cntM
            from trade_order x
            left join trade_order2product y on y.orderId=x.id
            where x.id = '{$v}' ";
            $temp = $this->_modelExample->findBySql($sql);

            //查找仓库出库数据
            $sql="select sum(cntM) as cntM from cangku_chuku2product where orderId='{$v}'";
            $temp1 = $this->_modelExample->findBySql($sql);

            //查找物流编号
            $sql="select y.logi_no,y.corp_name
                from cangku_chuku2product x 
                left join cangku_chuku y on x.chukuId=y.id
                 where x.orderId='{$v}'";
            $temp2 = $this->_modelExample->findBySql($sql);

            $_status[]=array(
                'order_id'=>$temp[0]['orderCode'],
                'orderId'=>$v,
                'status'=>((int)$temp[0]['cntM']>(int)$temp1[0]['cntM']) ? 2 : 1,

                'logi_no'=>$temp2[0]['logi_no'],
                'corp_name'=>$temp2[0]['corp_name'],
            );
        }

        if(!count($_status)>0){
        	return false;
        }
        // dump($_status);exit;

        $modelOrder = FLEA::getSingleton('Model_Trade_Order');

        //更新订单的发货状态
        foreach ($_status as $key => & $v) {
            $_tmp = array(
                'id'=>$v['orderId'],
                'ship_status'=>$v['status'],
            );
            $modelOrder->update($_tmp);
            unset($v['orderId']);
        }

        //同步到ec

		$obj_api = FLEA::getSingleton('Api_Request');        
        $result = $obj_api->callApi(array(
            'method'=>'apioubao.erp.response.change_ship_status',
            'params'=>array('data'=>$_status)
        ));
        $r = json_decode($result,true);
        if(!$r) {
	        echo "<b>解析失败,返回：</b>：<br />";
	        echo $result;
	        exit;
	    }
	    if($r['rsp']===false) {
	        //输出$r['data']['msg']的错误信息
	        echo "<b>获取库存信息失败，相关信息如下</b>：<br />";
	        echo $r['data']['msg'];
	        exit;
	    } 
    	return true;
	}
	

    /**
     * 同步发货信息到ec中
     * Time：2015/09/19 21:41:02
     * @author li
     * @param 参数类型
     * @return 返回值类型
    */
    function actionApiToEcChuku(){
    	set_time_limit(0);
    	//可以传递多条信息1,2,3
    	$id = explode(',',$_GET['id']);
    	$_condition['in()']=$id;
    	$_condition[]=array('orderId','0','>');
    	$_condition[]=array('ord2proId','0','>');

    	$_res = $this->_subModel->findAll($_condition);

    	//订单明细Model
    	$_order2_model = FLEA::getSingleton('Model_Trade_Order2Product');

    	//获取ec物流信息
    	$_modelPlan = FLEA::getSingleton('Model_Cangku_Plan');
    	$_wuliu = $_modelPlan->getWuliuFromEc();

    	//配送方式
    	$_dlytype_info = array();
        foreach ($_wuliu['data']['dlytype'] as $key => & $v) {
            $_dlytype_info[$v['dt_name']]=$v['dt_id'];
        }

        //物流公司
        $_dlycorp_info = array();
        foreach ($_wuliu['data']['dlycorp'] as $key => & $v) {
            $_dlycorp_info[$v['name']]=$v['corp_id'];
        }

    	// dump($_dlytype_info);
    	// dump($_dlycorp_info);exit;
    	// dump($_res);
    	//处理需要发货的记录
    	$_arr = array();
    	foreach ($_res as $key => & $v) {
    		$_order2Pro = $_order2_model->find($v['ord2proId']);
    		// dump($v['Ck']);exit;
    		$temp = array();
    		$temp['order_id'] = $_order2Pro['Order']['orderCode'];
    		$temp['delivery'] = $_dlytype_info[$v['Ck']['shipping']];
    		$temp['logi_id'] = $_dlycorp_info[$v['Ck']['corp_name']];
    		$temp['logi_no'] = $v['Ck']['logi_no'];
    		$temp['money'] = $v['Ck']['money'];
    		$temp['ship_addr'] = $v['Ck']['ship_addr'];
    		$temp['ship_area'] = $_order2Pro['Order']['ship_area'];
    		$temp['ship_mobile'] = $v['Ck']['ship_mobile'];
    		$temp['ship_tel'] = $v['Ck']['ship_tel'];
    		$temp['ship_zip'] = $v['Ck']['ship_zip'];
    		//查找订单号和订单明细货品好
    		$temp['send'][]=array('bn'=>$_order2Pro['bn'],'cnt'=>1);
    		
    		$_arr[]=$temp;
    	}

    	$arr['send_data'] = $_arr;
    	// dump($arr);exit;

    	$obj_api = FLEA::getSingleton('Api_Request');        
        $result = $obj_api->callApi(array(
            'method'=>'apioubao.erp.response.do_delivery',
            'params'=>$arr
        ));
        $r = json_decode($result,true);
        if(!$r) {
	        echo "<b>解析失败,返回：</b>：<br />";
	        echo $result;
	        exit;
	    }
	    if($r['rsp']===false) {
	        //输出$r['data']['msg']的错误信息
	        echo "<b>获取库存信息失败，相关信息如下</b>：<br />";
	        echo $r['data']['msg'];
	        exit;
	    } 
    	return true;
    }
    /**
     * ps ：码单显示
     * Time：2015/09/28 16:28:51
     * @author jiang
    */
    function actionViewMadan(){
        $ma=&FLEA::getSingleton('Model_Cangku_Madan');
        $_modelPro=&FLEA::getSingleton('Model_Jichu_Product');
        $product = $_modelPro->find(array('proCode'=>$_GET['productId']));
        if($_GET['chukuId']) $stat=" and (y.status='active' or x.chukuId='{$_GET['chukuId']}')";
        else $stat=" and y.status='active'";
        $sql="SELECT y.* from madan_db y
                   left join madan_rc2madan x on x.madanId=y.id 
                   where y.productId='{$_GET['productId']}' and y.millNo='{$_GET['pihao']}'
                    {$stat}
                   group by y.id order by y.rollNo";
        $row = $ma->findBySql($sql);
        $ret=array();
        foreach ($row as & $v) {
            $v['cntM']=round($v['cntM'],3);
            $v['cnt']=round($v['cnt'],3);
            if($v['unit']=='Y'){
                $v['cntMi']=$v['cnt']*0.9144;
            }else{
                $v['cntMi']=$v['cntM'];
            }
            $ret['Peihuo'][$v['millNo']][]=$v;
            // $product=$v['Product'];
        }
        $smarty = &$this->_getView(); 
        $smarty->assign('Peihuo', $ret);
        $smarty->assign('Product', $product);
        $smarty->display('Cangku/Xianhuo/PeihuoSelect.tpl');
    }

    /**
     * ps ：查找批号
     * Time：2015/10/08 17:00:23
     * @author jiang
    */
    function actionAutocomplete(){
        $map = $_POST['title'];
        $sql = "select distinct pihao from cangku_ruku2product
        where pihao like '%$map%'";
        $lists = $this->_modelExample->findBySql($sql);
//      dump($sql);exit;
        if(!$lists){
            $lists[0]['id'] = "-1";
            $lists[0]['depName'] = "查到0条匹配项";
        }
        $data = json_encode($lists);
        echo $data;
    
    } 

    /**
     * ps ：已生成出库单没有出库完成
     * Time：2015-10-26 20:06:31
     * @author shen
    */
    function actionChukuWaiting(){
        $this->authCheck('3-2-6') and $this->authCheck('3-1-6');
        FLEA::loadClass('TMIS_Pager'); 
        // $sql="SELECT (x.cntM-sum(y.cntM)) as cnt,x.cntM,
        //     sum(y.cntM)as cnt1,y.unit,
        //     a.proCode,a.proName,a.wuliaoKind,a.zuzhi,a.zhengli,a.menfu,a.kezhong,
        //     b.compName,
        //     c.orderTime,c.status,c.ship_name,c.orderCode
        //     from chuku_plan x
        //     left join cangku_chuku2product y on x.id=y.planId
        //     left join trade_order2product z on z.id=x.ord2proId
        //     left join trade_order c on c.id=z.orderId
        //     left join jichu_product a on a.proCode=x.productId
        //     left join jichu_client b on b.member_id=c.clientId
        //     where 1 group by x.id having cnt>0";

        $sql="SELECT sum(cntM) as cnt,planId,unit,orderTime,
                    proCode,proName,wuliaoKind,zuzhi,zhengli,menfu,kezhong,
                    compName,orderTime,status,ship_name,orderCode from(
                SELECT sum(x.cntM) as cntM,x.id as planId,
                    y.unit,z.orderTime,
                    a.proCode,a.proName,a.wuliaoKind,a.zuzhi,a.zhengli,a.menfu,a.kezhong,
                    b.compName,z.status,z.ship_name,z.orderCode
                    from chuku_plan x
                    left join trade_order2product y on x.ord2proId=y.id
                    left join trade_order z on z.id=y.orderId
                    left join jichu_product a on a.proCode=x.productId
                    left join jichu_client b on b.member_id=z.clientId
                    where 1 group by x.id
                union 
                SELECT sum(cntM*-1) as cntM,x.planId,
                    null as unit,null as orderTime,
                    null as proCode,null as proName,null as wuliaoKind,null as zuzhi,null as zhengli,null as menfu,null as kezhong,
                    null as compName,null as status,null as ship_name,null as orderCode
                    from cangku_chuku2product x
                    where 1 group by x.planId
            ) as a 
        where 1 and planId>0 group by planId having cnt>0";
        // dump($sql);exit;
        $sql.=" order by orderTime desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($rowset);exit;
        foreach ($rowset as $k => &$v) {
            //显示订单状态
            $v['status'] = $this->getStatusOrder($v['status'] ,true);
        }
        $smarty = & $this->_getView();
        $arrFieldInfo = array(
            'orderTime' =>array('text'=>'订单时间','width'=>130),
            'status'=>array('text'=>'订单状态','width'=>70),
            'proCode'=>array('text'=>'花型六位号','width'=>70),
            'proName'=>array('text'=>'品名','width'=>70),
            'wuliaoKind'=>array('text'=>'物料大类','width'=>80),
            'zuzhi' =>array('text'=>'组织大类','width'=>80),
            'zhengli' =>array('text'=>'整理方式','width'=>80),
            'menfu' =>array('text'=>'门幅','width'=>70),
            'kezhong' =>array('text'=>'克重','width'=>70),
            'cnt' =>array('text'=>'数量','width'=>70),
            // 'cntM' =>array('text'=>'已出库','width'=>70),
            'unit' =>array('text'=>'单位','width'=>50),
            'ship_name' =>array('text'=>'收货人','width'=>70),
            'compName'=>array('text'=>'客户','width'=>70),
            'orderCode'=>array('text'=>'订单编号(more)','width'=>130),
        ); 
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
        $smarty->display('TblList.tpl');
    }
}

?>