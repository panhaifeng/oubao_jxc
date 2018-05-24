<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Jichu_Client extends TMIS_Controller {
    var $_modelExample;
    var $_modelTaitou;
    var $title = "客户档案";
    var $funcId = 8;
    var $_tplEdit = "Jichu/ClientEdit.tpl";

    function Controller_Jichu_Client() {
        $this->_modelExample = & FLEA::getSingleton('Model_Jichu_Client');
        $this->_modelEmploy = & FLEA::getSingleton('Model_Jichu_Employ');

        $this->fldMain = array(
            'id'=>array('title'=>'','type'=>'hidden','value'=>''),
            'compCode'=>array('title'=>'用户名','type'=>'text','value'=>$this->_autoCode('','','jichu_client','compCode'),'disabled'=>true),
            'compName'=>array('title'=>'客户名称','type'=>'text','value'=>'','disabled'=>true),
            // 'sex'=>array('title'=>'姓别','type'=>'select','value'=>'','options'=>array(
            //         array('text'=>'女','value'=>0),
            //         array('text'=>'男','value'=>1))),
            // 'isGuanwang'=>array('title'=>'官网用户','type'=>'select','value'=>'','options'=>array(
            //      array('text'=>'是','value'=>0),
            //      array('text'=>'否','value'=>1))),
            'traderId'=>array('title'=>'业务员','type'=>'select','model' => 'Model_Jichu_Employ','isSearch'=>'true','condition'=>'isFire=0'),
            'lidanId'=>array('title'=>'理单员','type'=>'select','model' => 'Model_Jichu_Lidan','isSearch'=>'true','condition'=>'isFire=0'),
            // 'edu'=>array('title'=>'信用额度','type'=>'text','value'=>''),
            // 'address'=>array('title'=>'地址','type'=>'text','value'=>''),
            // 'quyuCode'=>array('title'=>'区域四级编码组合','type'=>'text','value'=>''),
            // 'compDate'=>array('title'=>'注册时间','type' => 'calendar', 'value' => date('Y-m-d')),
            // 'diqu'=>array('title'=>'地区','type'=>'text','value'=>''),
            // 'dianhua'=>array('title'=>'固定电话','type'=>'text','value'=>''),
            // 'mobile'=>array('title'=>'手机','type'=>'text','value'=>''),
            // 'email'=>array('title'=>'Email','type'=>'text','value'=>''),
            // 'memo'=>array('title'=>'备注','type'=>'textarea','value'=>''),\
            //添加会员类型，add by liu，2016-12-06
            'user_type'=>array('title'=>'用户类型','type'=>'select','value'=>'','options'=>array(
                    array('text'=>'内销','value'=>1),
                    array('text'=>'外销','value'=>2))),
        );

        $this->rules = array(
            'compCode'=>'required repeat',
            // 'compName'=>'required',
            'codeAtOrder'=>'required',
            // 'traderId'=>'required'
        );
    }

    /**
     * @desc ：测试ec的接口
     * @author jeff 2015/09/11 13:26:32
     * @param 参数类型
     * @return 返回值类型
    */
    function actionTestApi() {
        $obj_api = FLEA::getSingleton('Api_Request');
        $r = $obj_api->callApi(array(
            'method'=>'apioubao.erp.response.get_members',
            'params'=>array()
        ));
        $ret = json_decode($r);
        // dump($ret);exit;
    }
    /**
     * ps ：进销存同步ec里面的会员信息
     * Time：2015/09/11 13:44:09
     * @author jiang
    */
    function actionTongbu(){
        // sleep(10);
        //先查找客户档案的最新修改时间  将时间戳传递过去
        $sql="select dt from jichu_client where 1 order by dt desc limit 1";
        $ret=$this->_modelExample->findBySql($sql);

        $last_modify = strtotime($ret[0]['dt'])+0;
        
        //发出请求
        set_time_limit(0);

        $obj_api = FLEA::getSingleton('Api_Request');
        $r = $obj_api->callApi(array(
            'method'=>'apioubao.erp.response.get_members',
            'params'=>array(
                'last_modify'=>$last_modify
            )
        ));

        $ret = json_decode($r,true);
        
        // dump($ret);exit;
        //判断是否连接成功
        $_data=$ret;
        if($_data['rsp']=='fail'){
            js_alert(
                '连接失败！',
                "window.parent.location.href=window.parent.location.href;",
                $this->_url('right')
            );
            exit;
        } 

        //开始将ec中的数据保存到进销存中
        foreach ($_data['data'] as & $v) {
            $_arr=array(
                'member_id'=>$v['member_id'],
                'compCode'=>trim($v['uname']),
                'compName'=>$v['name'].'',
                'sex'=>$v['sex']+0,
                'edu'=>$v['advance'].'',
                'compDate'=>date('Y-m-d',$v['regtime']).'',
                'diqu'=>$v['area'].'',
                'dianhua'=>$v['tel'].'',
                'mobile'=>$v['mobile'].'',
                'email'=>$v['email'].'',
                'memo'=>$v['remark'].'',
                'com_type'=>$v['com_type'].'',
                'edu'=>$v['advance'].'',
                //添加会员类型，add by liu，2016-12-06
                'user_type'=>$v['user_type'],
            );

            $row=$this->_modelExample->find(array('member_id'=>$v['member_id']));
            $row['id']>0 && $_arr['id'] = $row['id'];

            $arr[] = $_arr;
        }

        // dump($v);dump($arr);exit;
        $this->_modelExample->saveRowset($arr);

        //删除ec中已经删除的数据     暂时不考虑
        // $mid='';
        // foreach ($_data['data']['member_id'] as $k=> & $vv) {
        //  $mid.=$k==0?$vv['member_id']:','.$vv['member_id'];
        // }
        // $sqlmid="delete from jichu_client where member_id not in ({$mid})";
        // $this->_modelExample->execute($sqlmid);
        
        js_alert(
            null,
            "window.parent.showMsg('同步成功');window.location.href=window.location.href;",
            $this->_url('right')
        );
        exit;
    }

    function actionEdit(){
        $row = $this->_modelExample->find(array('id'=>$_GET['id']));
        $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
        //处理提示信息，是否已经上传营业执照
        if($row['zhizaoPic']!=''){
            $this->fldMain['pic']['addonEnd']="已上传";
            $this->fldMain['pic']['addonEnd'] .= "<a href='".$row['zhizaoPic']."' target='_blank'>营业执照</a>";
        }
        // dump($this->fldMain);exit;
        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('rules',$this->rules);
        $smarty->assign('title','客户信息编辑');
        $smarty->assign('aRow',$row);
        $smarty->assign('form',array('upload'=>true));
        $smarty->display('Main/A1.tpl');
    }

    function actionAdd($Arr){
        //需要设置默认业务员
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        $canSeeAllOrder = $mUser->canSeeAllOrder($_SESSION['USERID']);
        if(!$canSeeAllOrder) {//如果不能看所有订单，得到当前用户的关联业务员
            $user = $mUser->find(array('id'=>$_SESSION['USERID']));
            $traderId = $user['traders'][0]['id'];
        }

        $row['traderId']=$traderId;

        // $this->fldMain = $this->getValueFromRow($this->fldMain,$row);
        $smarty = & $this->_getView();
        $smarty->assign('fldMain',$this->fldMain);
        $smarty->assign('title','客户信息编辑');
        $smarty->assign('rules',$this->rules);
        if($_GET['fromAction']!=''){
            $smarty->assign('other_button',"<input class='btn btn-default' type='button' name='back' value='返 回' onclick=\"window.location.href='".$this->_url($_GET['fromAction'])."'\">");
        }
        $smarty->assign('form',array('upload'=>true));
        $smarty->display('Main/A1.tpl');
    }

    function actionRight() {
        ///////////////////////////////模块定义
        $this->authCheck('15-3');
        ////////////////////////////////标题
        $title = '客户档案编辑';
        ///////////////////////////////模板文件
        $tpl = 'TblList.tpl';
        ///////////////////////////////表头
        $arr_field_info = array(
            '_edit'=>array('text'=>'操作','width'=>50),
            'id'=>'用户编号',
            'compCode'=>'用户名',
            'compName'=>'客户名称',
            'com_type'=>'客户类型',
            'sex'=>'性别',
            // 'isGuanwang'=>'官网用户',
            'Trader.employName'=>'业务员',
            'Lidan.lidanName'=>'理单员',
            // 'edu'=>'预存款',
            // 'address'=>'地址',
            // 'quyuCode'=>'区域四级编码组合',
            'compDate'=>'注册时间',
            //2015-9-21 注释 by jeff,以下两个对于进销存没什么意义,不显示即可，
            // 'diqu'=>'地区',
            // 'dianhua'=>'固定电话',
            'mobile'=>'手机',
            'email'=>'Email',
            'memo'=>'备注',
            //添加会员类型，add by liu，2016-12-06
            'user_type'=>'会员类型',
        );

        

        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'key'=>'',
            'traderId'=>'',
            'lidanId'=>'',
            //'lzId'=>''
        ));

        $condition=array();
        if($arr['key']!='') {
            $condition[] = array('compCode',"%{$arr['key']}%",'like','or');
            $condition[] = array('compName',"%{$arr['key']}%",'like');
        }

        //业务员只能看自己的客户
        /*$mUser = & FLEA::getSingleton('Model_Acm_User');
        $canSeeAllOrder = $mUser->canSeeAllOrder($_SESSION['USERID']);
        if(!$canSeeAllOrder) {//如果不能看所有订单，得到当前用户的关联业务员
            $traderId = $mUser->getTraderIdByUser($_SESSION['USERID'],true);
            if($traderId)$condition['in()'] = array('traderId'=>$traderId);
        }*/

        if($arr['traderId']!=0) {
         $condition[] = array('traderId',"{$arr['traderId']}",'=');
        }
        if($arr['lidanId']!=0) {
         $condition[] = array('lidanId',"{$arr['lidanId']}",'=');
        }
        //dump($condition);
        $pager = new TMIS_Pager($this->_modelExample,$condition,'id desc');
        $rowset =$pager->findAll();
        // dump($rowset);die;

        if(count($rowset)>0) foreach($rowset as & $v) {
            $v['compDate']=$v['compDate']=='0000-00-00'?'':$v['compDate'];
            $v['_edit'] = $this->getEditHtml($v['id']) ;
            //判断客户是否下过订单
            $sql="SELECT count(*) as cnt FROM `trade_order` where clientId=".$v['member_id'];
            // dump($sql);exit;
            $re=$this->_modelExample->findBySql($sql);
            if($re[0]['cnt']==0) {
              $v['_edit'] .= '&nbsp;&nbsp;' .$this->getRemoveHtml($v['member_id']);
            } else {
                //$v['_edit'] .= '&nbsp;&nbsp;' .$re[0]['cnt'] . '单';
                // $v['_edit'] .= $v['member_id'];
            }
            
            $v['sex']=$v['sex']==0?'女':'男';
            $v['isGuanwang']=$v['isGuanwang']==0?'否':'是';
            //添加会员类型，add by liu，2016-12-06
            if($v['user_type'] == 1){
                $v['user_type'] = '内销';
            }
            elseif ($v['user_type'] == 2) {
                $v['user_type'] = '外销';
            }
            else{
                $v['user_type'] = '';
            }
        }
        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('add_display', 'none');

        //2016-12-5 by jeff 增加导出功能
        if($_GET['export']==1){
            $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)).$note);
        }
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
        if($_GET['export']==1){
            $this->_exportList(array('title'=>$title),$smarty);
        }

        $other_url="<a href='".$this->_url('Tongbu',array(
            'id'=>$v['id'],
            ))."' onclick='layer.msg(\"玩命同步中，不要操作，以免中断\",100)'>同步商城会员</a>";
        $smarty->assign('other_url', $other_url);
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->display($tpl);
    }

    function actionSave() {
        // dump($_POST);exit;
        $this->authCheck('15-3');
        //判断是否重复,
        //新增时判断公司名和代码是否重复
        if(empty($_POST['id'])) {
            $sql = "SELECT count(*) as cnt FROM `jichu_client` where 1 and compCode={$_POST['compCode']}";
            $rr = $this->_modelExample->findBySql($sql);
            // dump($rr);exit;
            if($rr[0]['cnt']>0) {
                js_alert('客户名称或客户代码重复!',null,$this->_url('add'));
            }
        } else {
        //修改时判断是否重复
            $str1="SELECT count(*) as cnt FROM `jichu_client` where id!=".$_POST['id']." and (compCode='".$_POST['compCode']."')";
            $ret=$this->_modelExample->findBySql($str1);
            if($ret[0]['cnt']>0) {
                js_alert('客户名称或客户代码重复!',null,$this->_url('Edit',array('id'=>$_POST['id'])));
            }
        }

        //首字母自动获取
        FLEA::loadClass('TMIS_Common');
        $letters=strtoupper(TMIS_Common::getPinyin($_POST['compName']));
        $_POST['letters']=$letters;

        $id = $this->_modelExample->save($_POST);
        //$dbo= FLEA::getDBO(false);dump($dbo->log);exit;
        // dump($_POST['Submit']);exit;
        js_alert('',null,$this->_url($_POST['fromAction']==''?'add':$_POST['fromAction'],array('fromAction'=>$_POST['fromAction'])));

    }

    function actionRemove() {
        if($_GET['id']!="") {
             $sql="SELECT count(*) as cnt FROM `trade_order` where clientId=".$_GET['id'];
             $re=$this->_modelExample->findBySql($sql);
             if($re[0]['cnt']>0) {
                 js_alert('此客户有订单存在，不能够删除',null,$this->_url('right'));
             }
        }
        $row = $this->_modelExample->find(array('member_id'=>$_GET['id']));
        $_GET['id'] = $row['id'];
        parent::actionRemove();
    }

    //在模式对话框中显示待选择的客户，返回某个客户的json对象。
    function actionPopup() {
        FLEA::loadClass('TMIS_Pager');
//      TMIS_Pager::clearCondition();
        $arr = TMIS_Pager::getParamArray(array(
            //'traderId' => '',
            'key' => '',
            'showModel'=>''
        ));
        $condition=array();
        if($arr['key']!='') {
            $condition[] = array('compCode',"%{$arr['key']}%",'like','or');
            $condition[] = array('compName',"%{$arr['key']}%",'like');
        }
    
        if($arr['traderId']!=0) {
         $condition[] = array('traderId',"{$arr['traderId']}",'=');
        }
        if($arr['lidanId']!=0) {
         $condition[] = array('lidanId',"{$arr['lidanId']}",'=');
        }
        //dump($condition);
        $pager = new TMIS_Pager($this->_modelExample,$condition,'id desc');
        $rowset =$pager->findAll();
        
        if(count($rowset)>0) foreach($rowset as & $v){
            $str="select * from jichu_employ where id='{$v['traderId']}'";
            $re=mysql_fetch_assoc(mysql_query($str));
            $v['traderName']=$re['employName'];
            if($re['isFire']==1){
                $v['fire']='是';
            }
            //$temp = $mTrader->find($v[traderId]);
            //$v[traderName] = $temp[employName];
            //$v[_edit] = "<a href='#' onclick=\"retOptionValue($v[id],'$v[compName]')\">选择</a>";
        }
        if($_GET['kind']==0){
            $arr_field_info = array(
                "compCode" =>"编码",
                "compName" =>"名称",
                'traderName'=>'本厂联系人',
                "people" =>"联系人",
                "address" =>"地址",
                "tel" =>"电话",
                "mobile" =>"手机",
                //"carCode" =>"车牌号",
                "memo" =>"备注",
                "fire" =>"是否离职",

            );
        }else{
            $arr_field_info = array(
                "compCode" =>"编码",
                "compName" =>"名称",
            );
        }
        // $arr['kind']=$_GET['kind'];
        // dump($rowset); exit;
        $smarty = & $this->_getView();
        $smarty->assign('title', '选择客户');
        $pk = $this->_modelExample->primaryKey;
        $smarty->assign('pk', $pk);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_field_info',$arr_field_info);
        //2017-2-23 by jeff，php数组变成js后,chrome会自动对数组按键值排序，所以这里要预先排序下
        ksort($rowset);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('add_url',$this->_url('add',array('fromAction'=>$_GET['action'])));
        $smarty->assign('s',$arr);
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('clean',true);
        // $smarty->assign('arr_js_css',$this->makeArrayJsCss(array('thickbox')));// nowork
        $smarty-> display('Popup/CommonNew.tpl');
    }

    function actionGetJsonByKey() {
        $sql = "select * from jichu_client where (
            compName like '%{$_GET['code']}%' or zhujiCode like '%{$_GET['code']}%' or compCode like '%{$_GET['code']}%'
        )";
        $arr = $this->_modelExample->findBySql($sql);
        echo json_encode($arr);exit;
    }
    //根据传入的id获得具体信息,订单录入时根据客户定位业务员时用到
    function actionGetJsonById() {
        $sql = "select * from jichu_client where id='{$_GET['id']}'";
        $arr = $this->_modelExample->findBySql($sql);
        echo json_encode($arr[0]);exit;
    }
    //根据业务员查找客户
    function actionGetJsonByTraderId() {
        $sql = "select * from jichu_client where 1";
        if($_GET['traderId']!='')$sql.=" and traderId='{$_GET['traderId']}'";
        $mUser = & FLEA::getSingleton('Model_Acm_User');
        $canSeeAllOrder = $mUser->canSeeAllOrder($_SESSION['USERID']);
        if(!$canSeeAllOrder) {
            //如果不能看所有订单，得到当前用户的关联业务员
            $traderId = $mUser->getTraderIdByUser($_SESSION['USERID']);
            if($traderId)$sql .= " and traderId in ({$traderId})";
        }
        // $sql.=" order by convert(trim(compName) USING gbk)";
        // $arr = $this->_modelExample->findBySql($sql);
        $sql.=" order by ";
        $kg = & FLEA::getAppInf('khqcxs');
        if($kg)$sql.=" letters";
        else $sql.=" compCode";

        $arr = $this->_modelExample->findBySql($sql);

        //生成下拉框
        $ret=$this->_modelExample->options($arr);
        echo json_encode($ret);exit;
    }

    //开票抬头设置
    function actionSetTaitou(){
        $rows=$this->_modelTaitou->findAll(array('clientId'=>$_GET['clientId']));
        //dump($rows);exit;
        $smarty = & $this->_getView();
        $smarty->assign('title', '开票抬头设置');
        $smarty->assign("aRow", $rows);
        $smarty->display('Jichu/ClientTaitou.tpl');
    }

    //保存抬头设置
    function actionSaveTaitou(){
        //dump($_POST);exit;
        $rows=array(            
            'taitou'=>$_POST['taitou'],
            'clientId'=>$_POST['clientId'],
            'memo'=>$_POST['memo']
        );
        if($rows) $this->_modelTaitou->save($rows);
        // js_alert(null,'window.parent.parent.showMsg("设置成功");window.parent.location.href=window.parent.location.href');
        js_alert('保存成功！','',$this->_url('SetTaitou',array('clientId'=>$_POST['clientId'])));
    }

    //删除抬头设置
    function actionDelTaitouAjax(){
        if($_GET['id']!='') {
            if($this->_modelTaitou->removeByPkv($_GET['id'])) {
                echo json_encode(array('success'=>true));
                exit;
            }
        }
    }
    //新增会员提醒
    function actionNewClient(){
        $this->authCheck('15-3');
        FLEA::loadClass('TMIS_Pager');
        $today = date('w')>0 ? date('w')-1 : 6;//星期几：0（星期7）~ 6（星期六）
        $dateFrom = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$today,date('Y')));
        $dateTo = date('Y-m-d H:i:s');
        $sql="SELECT * from jichu_client where compDate>='{$dateFrom}' and compDate<= '{$dateTo}'";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as $key =>& $v) {
            $v['sex']=$v['sex']==0?'女':'男';
        }
        $smarty = & $this->_getView();
        $arrField = array(
            'compCode'=>'公司编码',
            'compName'=>'客户名称',
            'com_type'=>'客户类型',
            'sex'=>'性别',
            // 'edu'=>'预存款',
            'compDate'=>'注册时间',
            'mobile'=>'手机',
            'email'=>'Email',
            'memo'=>'备注',
        ); 
        $smarty->assign('arr_field_info', $arrField);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'])));
        $smarty->display('TblList.tpl');
    }
}
?>