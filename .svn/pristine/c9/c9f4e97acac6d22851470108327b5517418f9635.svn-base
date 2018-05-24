<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Trade_Peihuo extends TMIS_Controller {
	// /构造函数
	function __construct() {
		$this->_modelExample = &FLEA::getSingleton('Model_Peihuo_Peihuo');
		$this->_subModel = &FLEA::getSingleton('Model_Peihuo_Peihuo2Madan');
	}

	function actionRight(){
		//权限判断
		$this->authCheck('2-1');
		FLEA::loadClass('TMIS_Pager'); 
		$arr = TMIS_Pager::getParamArray(array(
            'dateFrom' =>date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30,date("Y"))),
            'dateTo' => date("Y-m-d"),
			'danhao'=>'',
			'orderCode'=>'',
			'clientId'=>'',
            'proCode' => '',
			'status_active' => '',
		)); 
		$sql="select x.*,o.orderCode,op.kind as okind,c.compName,
				p.proCode,p.proName,p.chengfen,p.jingmi,p.weimi,p.menfu 
				from ph_peihuo x
				left join trade_order2product op on op.peihuoId=x.id
				left join trade_order o on o.id=op.orderId
				left join jichu_client c on c.member_id=x.clientId
				left join jichu_product p on p.proCode=x.productId
				where 1";
        if($arr['dateFrom'] != '') {
            $sql .=" and x.peihuoDate >= '{$arr['dateFrom']}' and x.peihuoDate <= '{$arr['dateTo']}'";
        }
		if($arr['danhao']!='') $sql .=" and x.peihuoCode like '%{$arr['danhao']}%' ";
		if($arr['clientId']!='') $sql .=" and x.clientId='{$arr['clientId']}' ";
		if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
        if($arr['orderCode']!='') $sql .=" and o.orderCode like '%{$arr['orderCode']}%' ";
		if($arr['status_active']!='') $sql .=" and x.status_active = '{$arr['status_active']}' ";
        // dump($sql);exit;
		$sql.=" order by x.peihuoDate desc";
		$pager = &new TMIS_Pager($sql);
		$rowset = $pager->findAll();
		// dump($rowset);exit;

		if (count($rowset) > 0) foreach($rowset as &$v) {
			//查看码单
            if($v['okind']!='样品'){
                 $v['_edit']="&nbsp;<a href='".$this->_url('MadanCheck',array(
                    'id'=>$v['id'],
                    'fromAction'=>$_GET['action'],
                    'width'=>'700',
                    'height'=>'400',
                ))."' class='thickbox'><span class='glyphicon glyphicon-zoom-in' title='查看码单'></span></a>";
            }
            //大货的删除配货单
            if($v['isDahou']=='1'){
                $v['_edit'].="&nbsp;<a href='".$this->_url('Remove',array(
                    'id'=>$v['id'],
                ))."'><span class='glyphicon glyphicon-trash text-danger' title='删除配货单'></span></a>";
            }
		    $v['jingmi']=$v['weimi']?$v['jingmi'].'*'.$v['weimi']:$v['jingmi'];
		    $v['status']=$this->getStatusOrder($v['status'] ,true);
            $v['cntM']=round($v['cntM'],2);
		}
		$smarty = &$this->_getView(); 
		$arrFieldInfo = array(
			"_edit" => array('text'=>'操作','width'=>50),
			'peihuoCode'=>array('text'=>'配货编号','width'=>120),
			'peihuoDate'=>'配货日期',
			'orderCode'=>array('text'=>'订单号','width'=>120),
			'okind'=>'类型',
			'compName'=>'客户',
			'status_active'=>'下单状态',
			"status"=>'配货单状态',
			"proCode" => '花型六位号',
            "proName" => '品名',
            'cntM'=>'数量(M)',
            "chengfen" => '成分',
            "jingmi" => '经纬密',
            "menfu" => '门幅',
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
	* ps ：查看码单
	* Time：2015/10/25 15:31:11
	* @author jiang
	*/
	function actionMadanCheck(){
        // dump($_GET);exit;
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
        )); 
        $sql="select y.* from ph_peihuo2madan x
                left join madan_db y on x.madanId=y.id
                where x.phId='{$_GET['id']}' order by rollNo";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll(); 
        $smarty = &$this->_getView(); 
        // 左侧信息
        $arr_field_info = array(
            // "id" => '编号',
            "productId" => '花型六位号',
            "millNo" => '批次号',
            "cntM" => '米数',
            "rollNo" => '卷号',
            "qrcode" => '条码',
        );
        $smarty->assign('title', '查看码单');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('page_info', $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }		
    /**
    * ps ：大货可以删除配货单
    * Time：2015-11-05 15:23:07
    * @author shen
    */	
    function actionRemove(){
        // dump($_GET);exit;
        $sql="SELECT x.*,z.ruku2proId,z.status from  madan_db x
            left join ph_peihuo2madan y on y.madanId=x.id
            left join ph_peihuo z on z.id=y.phId
            where z.id='{$_GET['id']}'";
        $rowset=$this->_modelExample->findbysql($sql);
        // dump($rowset);
        //只有状态为active的可以删除
        if($rowset[0]['status']!='active'){
            js_alert("已锁定，禁止删除",null,$this->_url('right'));
            exit;
        }
        $sql="update cangku_ruku2product set dahuo2proId='0' where id='{$rowset['0']['ruku2proId']}'";
        $this->_modelExample->execute($sql);

        $sql="update cangku_chuku2product set dahuo2proId='0' where dbId='{$rowset['0']['ruku2proId']}'";
        $this->_modelExample->execute($sql);
        
        $_peihuoIds = join(',',array_col_values($rowset,'id'));
        $sql="update madan_db set status='active' where id in({$_peihuoIds})";
        $this->_modelExample->execute($sql);
        $this->_modelExample->removeByPkv($_GET['id']);
        js_alert(null,"window.parent.showMsg('成功删除')",$this->_url('right'));
        exit;
    }
}

?>