<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Lidan extends TMIS_Controller {
	var $_modelExample;
	function Controller_Jichu_Lidan() {
		$this->_modelExample = & FLEA::getSingleton('Model_Jichu_Lidan');
		$this->fldMain = array(
			'id'=>array('title'=>'','type'=>'hidden','value'=>''),
        	'lidanCode'=>array('title'=>'理单员代码','type'=>'text','value'=>$this->getlidanCode()),
        	'lidanName'=>array('title'=>'理单员姓名','type'=>'text','value'=>''),
        	'sex'=>array('title'=>'性别','type'=>'radio','value'=>'','radios'=>array(
        			array('title'=>'男','value'=>0),
        			array('title'=>'女','value'=>1)
        	)),
        	'type'=>array('title'=>'类型','type'=>'select','value'=>'','options'=>array(
        			array('text'=>'正式','value'=>'正式'),
        			array('text'=>'试用','value'=>'试用'),
        			array('text'=>'临时','value'=>'临时'),
        			array('text'=>'离职','value'=>'离职'),
        		)),
        	'mobile'=>array('title'=>'手机','type'=>'text','value'=>''),
        	'address'=>array('title'=>'地址','type'=>'text','value'=>''),
        	'dateEnter'=>array('title'=>'入职日期','type'=>'calendar','value'=>''),
        	'dateLeave'=>array('title'=>'离职日期','type'=>'calendar','value'=>''),
        	'shenfenNo'=>array('title'=>'身份证号','type'=>'text','value'=>''),
        	'hetongCode'=>array('title'=>'用工合同号','type'=>'text','value'=>'')
        );

        $this->rules = array(
			'lidanCode'=>'required repeat',
			'lidanName'=>'required',
		);
	}
	function actionRight() {
		$this->authCheck('15-2');
		$title = '职工档案';
		///////////////////////////////模板文件
		$tpl = 'TblList.tpl';
		///////////////////////////////表头
		$arrField = array(
			"_edit"=>'操作',
			"lidanName" =>"姓名",
			"lidanCode" =>"理单员代码",
			'dateEnter'=>'入职日期',
			"sexName" =>"性别",
			"mobile" =>"手机",
			"address" =>"地址",
			"shenfenNo" =>"身份证号",
			"hetongCode" =>"用工合同号",
		);

		FLEA::loadClass('TMIS_Pager');
		$arr = TMIS_Pager::getParamArray(array(
			'key'=>'',
		));

		$sql="select * from jichu_lidan where 1";
		if($arr['key']!='') {
			$sql.=" and lidanCode like '%$arr[key]%' or lidanName like '%$arr[key]%'";

		}
		$pager = new TMIS_Pager($sql);

		$rowset =$pager->findAllBySql($sql);

		if(count($rowset)>0) foreach($rowset as & $v) {
			///////////////////////////////
			//$this->makeEditable($v,'memoCode');				
				if($v['type']=='离职') {
					$v['_bgColor'] = 'lightgreen';					
				}
				$v['sexName'] = $v['sex']==0 ?'男':'女';
				$v['_edit'] = $this->getEditHtml($v['id']) . '&nbsp;&nbsp;' . $this->getRemoveHtml($v['id']);
				if($v['dateEnter']=='0000-00-00') $v['dateEnter']="&nbsp;";

			}
		// dump($rowset);exit;
		$smarty = & $this->_getView();
		$smarty->assign('title', $title);
		$smarty->assign('arr_field_info',$arrField);
		$smarty->assign('arr_field_value',$rowset);
		$smarty->assign('arr_condition', $arr);
		$smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr))."<font color='red'>绿色表示已经离职</font>");
		$smarty->display($tpl);
	}

	function actionEdit(){
		$this->authCheck('15-2');
        $row = $this->_modelExample->find(array('id'=>$_GET['id']));
        $row['dateEnter']=$row['dateEnter']=='0000-00-00'?'':$row['dateEnter'];
        $row['dateLeave']=$row['dateLeave']=='0000-00-00'?'':$row['dateLeave'];

        $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
        // dump($row);dump($this->fldMain);exit;
        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('rules',$this->rules);
        $smarty->assign('title','理单员信息编辑');
        $smarty->assign('aRow',$row);
        $smarty->display('Main/A1.tpl');
    }

    function actionAdd($Arr){
    	$this->authCheck('15-2');
        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('title','理单员信息编辑');
        $smarty->assign('rules',$this->rules);
        $smarty->display('Main/A1.tpl');
    }


	function actionSave() {
		// dump($_POST);exit;
		if(empty($_POST['id'])) {
			$sql = "SELECT count(*) as cnt FROM `jichu_lidan` where lidanCode='".$_POST['lidanCode']."' or lidanName='".$_POST['lidanName']."'";
			$rr = $this->_modelExample->findBySql($sql);
			if($rr[0]['cnt']>0) {
				js_alert('理单员名称或代码重复!',null,$this->_url('add'));
			}
		} else {
		//修改时判断是否重复
			$str1="SELECT count(*) as cnt FROM `jichu_lidan` where id!=".$_POST['id']." and (lidanCode='".$_POST['lidanCode']."' or lidanName='".$_POST['lidanName']."')";
			$ret=$this->_modelExample->findBySql($str1);
			if($ret[0]['cnt']>0) {
				js_alert('理单员名称或代码重复!',null,$this->_url('Edit',array('id'=>$_POST['id'])));
			}
		}

		$_POST['fenlei']=$_POST['fenlei']==''?"":$_POST['fenlei'];
		$_POST['isFire']=0;
		if($_POST['type']=="试用") $_POST['paixu']=1;
		if($_POST['type']=="临时") $_POST['paixu']=2;
		if($_POST['type']=="离职") $_POST['isFire']=1;

		//首字母自动获取
		FLEA::loadClass('TMIS_Common');
		$letters=strtoupper(TMIS_Common::getPinyin($_POST['lidanName']));
		// $_POST['letters']=$letters;

		$arr=array(
			'id'=>$_POST['id'],
			'lidanCode'=>$_POST['lidanCode'],
			'lidanName'=>$_POST['lidanName'],
			'sex'=>$_POST['sex']+0,
			'depId'=>$_POST['depId'],
			'type'=>$_POST['type'],
			'mobile'=>$_POST['mobile'],
			'address'=>$_POST['address'],
			'dateEnter'=>$_POST['dateEnter'],
			'dateLeave'=>$_POST['dateLeave'],
			'shenfenNo'=>$_POST['shenfenNo'],
			'hetongCode'=>$_POST['hetongCode'],
			'isFire'=>$_POST['isFire'],
			'paixu'=>$_POST['paixu']
		);
		
		$this->_modelExample->save($arr);
		// if($_POST['Submit']=='保 存')
			js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction']));
		// else
			// js_alert(null,"window.parent.showMsg('保存成功!')",$this->_url('add'));
	}

	function actionRemove() {
		$this->authCheck('15-2');
		if($_GET['id']!="") {
			$sql="SELECT count(*) as cnt FROM `jichu_client` where lidanId =".$_GET['id'];
			//dump($sql);exit;
			$re=$this->_modelExample->findBySql($sql);
			//dump($re);exit;
			if($re[0]['cnt']>0) {
				js_alert('此理单员有业务订单，禁止删除！',null,$this->_url('right'));
			}
		}
		parent::actionRemove();
	}

	function getlidanCode(){
		$begin="001";
		$str="SELECT * FROM `jichu_lidan` order by (lidanCode) desc limit 0,1";
		//echo $str;exit;
		$re=mysql_fetch_assoc(mysql_query($str));
		if($re['lidanCode']!='')
		{
			$max=$re['lidanCode'];
			$next=$max+1001;
			return substr($next,1);
		}else{
			return $begin;
		}

	}
}
?>