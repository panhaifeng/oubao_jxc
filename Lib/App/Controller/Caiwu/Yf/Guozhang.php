<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Caiwu_Yf_Guozhang extends TMIS_Controller {

    function __construct() {
        //model
        $this->_modelExample = &FLEA::getSingleton('Model_Caiwu_Yf_Guozhang');

    }

    /**
     * ps ：库存
     * Time：2015/09/30 14:28:40
     * @author jiang
    */
    function actionListGuozhangCg(){
        $this->authCheck('4-2-1');
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'), 
            "dateTo" => date('Y-m-d'),
            'danhao'=>'',
            'hetongCode'=>'',
            'proCode'=>'',
        )); 
        $sql="select x.*,y.cnt,y.unit,o.orderCode,o.supplierId,y.danjia,y.id as ruku2ProId,
                p.proCode,p.proName,p.chengfen,p.jingmi,p.weimi,p.menfu,y.productId,z.compName,k.kuweiName
                from cangku_ruku x
                left join cangku_ruku2product y on x.id=y.rukuId
                left join caigou_order o on o.id=x.caigouId
                left join jichu_product p on p.proCode=y.productId
                left join caiwu_yf_guozhang g on g.ruku2proId=y.id
                left join jichu_supplier z on o.supplierId=z.id
                left join jichu_kuwei k on k.id=x.kuweiId
                where x.kind like '%采购%' and g.id is null";
        $sql.=" and x.rukuDate<='{$arr['dateTo']}' and x.rukuDate>='{$arr['dateFrom']}'";
        if($arr['danhao']!='') $sql .=" and x.rukuCode='{$arr['danhao']}' ";
        if($arr['hetongCode']!='') $sql .=" and o.orderCode like '%{$arr['hetongCode']}%'";
        if($arr['proCode']!='') $sql .=" and proCode like '%{$arr['proCode']}%'";
        $sql.=" order by x.rukuDate desc";
        // dump($sql);exit;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($rowset);exit;
        foreach ($rowset as & $v) {
            $hj['_edit']='<b>合计</b>';
            $hj['cnt']+=$v['cnt'];
            $v['_edit']="<input type='checkbox' id='chk[]' name='chk[]' value='ruku2ProId:{$v['ruku2ProId']},cnt:{$v['cnt']},unit:{$v['unit']},rukuId:{$v['id']},rukuDate:{$v['rukuDate']},kind:{$v['kind']},productId:{$v['productId']},supplierId:{$v['supplierId']}'/>";
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            // $v['cnt']=round($v['cnt'],3);
            $v['danjia']=round($v['danjia'],3);
            $v['money']=round($v['danjia']*$v['cnt'],2);
            $heji += $v['money'];
            $hj['money']="<div id='heji' name='heji'>$heji</div>";
            $v['cnt']=$v['cnt']."<input type='hidden' id='cnt[]' name='cnt[]' value='{$v['cnt']}'/>";
            $v['danjia']="<input type='text' id='danjia[]' name='danjia[]' value='{$v['danjia']}'/>";
            $v['zhekouMoney']="<input type='text' id='zhekouMoney[]' name='zhekouMoney[]' value='{$v['zhekouMoney']}'/>";
            $v['_money']="<input type='text' id='_money[]' name='_money[]' value='{$v['money']}'/>";
            $v['money']="<input type='text' id='money[]' name='money[]' value='{$v['money']}' />"."<input type='hidden' id='oldMoney[]' name='oldMoney[]' value='{$v['money']}'/>";
        }
        $rowset[] = $hj;
        $arrFieldInfo = array(
            "_edit"=>array('text'=>"<input type='checkbox' id='checkedAll' title='全选/反选'/>",'width'=>40),
            "rukuCode" => '入库单号',
            "rukuDate" => '入库日期',
            "kind" =>'类型',
            "orderCode" => '采购合同',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "compName" => '供应商',
            "cnt" => '数量',
            "danjia" => '单价',
            "_money" => '发生金额',
            "zhekouMoney" => '折扣金额',
            "money" => '入账金额',
            "chengfen" => '成分',
            "jwmi" => '经纬密',
            "menfu" => array('text'=>'门幅','width'=>'50'),
            "kuweiName" => '库位',
            "unit" => '单位',
            );

        $smarty = &$this->_getView();
        $smarty->assign('title', '收发存报表'); 
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $other_url="<button type='button' class='btn btn-info btn-sm' id='save2' name='save2'>保存</button>";
        $smarty->assign('other_url', $other_url);
        $smarty->assign('sonTpl', 'Caiwu/Yf/Guozhang.tpl');
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }

    function actionRight(){
        $this->authCheck('4-2-3');
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'), 
            "dateTo" => date('Y-m-d'),
            'hetongCode'=>'',
            'supplierIds'=>'',
            'proCode'=>'',
            'rukuCode'=>'',
        )); 
        $sql="SELECT x.*,o.orderCode,k.rukuCode,k.kuweiId,s.compName,
                p.proCode,p.proName,p.shazhi,p.chengfen,p.menfu,p.jingmi,p.weimi,z.kuweiName
                from caiwu_yf_guozhang x
                left join jichu_product p on p.proCode=x.productId
                left join cangku_ruku k on k.id=x.rukuId
                left join caigou_order o on o.id=k.caigouId
                left join jichu_kuwei z on z.id=k.kuweiId
                left join jichu_supplier s on s.id=x.supplierId
                where 1 and x.kind!='其他过账'";
        $sql.=" and x.guozhangDate >= '{$arr['dateFrom']}' and x.guozhangDate <= '{$arr['dateTo']}'";
        if($arr['supplierIds']!='')$sql.=" and x.supplierId='{$arr['supplierIds']}'";
        // if($arr['jiagonghuId']!='') $sql .=" and x.supplierId='{$arr['jiagonghuId']}' ";
        if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%'";
        if($arr['hetongCode']!='') $sql .=" and o.orderCode like '%{$arr['hetongCode']}%'";
        if($arr['rukuCode']!='') $sql .=" and k.rukuCode like '%{$arr['rukuCode']}%'";
        $sql.=" order by guozhangDate desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as & $v) {
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            $v['cnt']=round($v['cnt'],3);
            $v['danjia']=round($v['danjia'],3);
            $v['money']=round($v['money'],2);
            $v['_edit']=$this->getRemoveHtml($v['id']);
            $v['rukuDate']=$v['rukuDate']=='0000-00-00'?'':$v['rukuDate'];
        }
        $rowset[] = $this->getHeji($rowset, array('money','cnt'), '_edit');
        $arrFieldInfo = array(
            "_edit"=>array('text'=>'操作','width'=>40),
            "guozhangDate"=>'过账日期',
            "rukuCode" => '入库单号',
            "rukuDate" => '入库日期',
            "orderCode" => '采购合同',
            "compName" => '加工供应商',
            "kind" =>'类型',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "cnt" => '数量',
            "danjia" => '单价',
            "_money" => '发生金额',
            "zhekouMoney" => '折扣金额',
            "money" => '入账金额',
            "chengfen" => '成分',
            "jwmi" => '经纬密',
            "menfu" => array('text'=>'门幅','width'=>'50'),
            "kuweiName" => '库位',
            "unit" => '单位',
            );

        $smarty = &$this->_getView();
        $smarty->assign('title', '收发存报表'); 
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }
  
    function actionSave(){
        $ids=$_GET['ids'];
        $danjia=$_GET['danjia'];
        $money=$_GET['money'];
        $_money=$_GET['_money'];
        $zhekouMoney=$_GET['zhekouMoney'];
        foreach ($ids as $key=>&$v){
            $arr=array();
            $temp=explode(',',$v);
            foreach ($temp as &$t){
                $tempt=explode(':',$t);
                $arr[$tempt[0]]=$tempt[1].'';
            }
            $arr['guozhangDate']=date('Y-m-d');
            $arr['creater']=$_SESSION['REALNAME'];
            $arr['danjia']=$danjia[$key];
            $arr['money']=$money[$key];
            $arr['_money']=$_money[$key];
            $arr['zhekouMoney']=$zhekouMoney[$key];
            $id=$this->_modelExample->save($arr);
        }
        echo json_encode('true');exit;
    }

  /**
   * ps ：应付款报表
   * Time：2015/10/14 08:41:59
   * @author jiang
  */
    function actionReport(){
        $this->authCheck('4-2-11');
        $tpl = $_GET['print']?'Print.tpl':'TblList.tpl';
        FLEA::loadclass('TMIS_Pager');
        $arr=TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-01'),
            'dateTo'=>date('Y-m-d'),
            'supplierIds'=>'',
        ));
        //得到期初发生
        //应付款表中查找,日期为期初日期
        //按照加工商汇总
        $sql="select sum(money) as fsMoney,supplierId from caiwu_yf_guozhang where guozhangDate < '{$arr['dateFrom']}'";
        if($arr['supplierIds']!=''){
            $sql.=" and supplierId='{$arr['supplierIds']}'";
        }
        $sql.=" group by supplierId order by supplierId";
        $rowset = $this->_modelExample->findBySql($sql);
        
        foreach($rowset as & $v){
            //期初金额
            $row[$v['supplierId']]['initMoney']=round(($v['fsMoney']+0),3);//期初余额
            $row[$v['supplierId']]['initIn']=round(($v['fsMoney']+0),3);
        }
        //得到起始日期前的收款金额
        //从付款表中查找
        //按照加工商汇总
        $sqlIncome = "SELECT sum(x.money) as FukuMoney,supplierId FROM `caiwu_yf_fukuan` x 
        
        where  x.fukuanDate < '{$arr['dateFrom']}'";
        if($arr['supplierIds']!=''){
            $sqlIncome.=" and x.supplierId='{$arr['supplierIds']}'";
        }
        $sqlIncome.=" group by x.supplierId order by x.supplierId";
        $rowset = $this->_modelExample->findBySql($sqlIncome);
        
        foreach($rowset as & $v){
            //期初金额
            $row[$v['supplierId']]['initMoney']=round(($row[$v['supplierId']]['initMoney']-$v['FukuMoney']+0),3);//期初余额=期初发生-期初已付款
            $row[$v['supplierId']]['initOut']=round($v['FukuMoney'],3);
        }
        
        //得到本期的已付款
        //付款表中查找
        //按照加工户汇总
        $str="SELECT sum(x.money) as moneyfukuan,x.supplierId from caiwu_yf_fukuan x                
                where 1 ";
        if($arr['dateFrom']!=''){
            $str.=" and x.fukuanDate>='{$arr['dateFrom']}' and x.fukuanDate<='{$arr['dateTo']}'";
        }
        if($arr['supplierIds']!=''){
            $str.=" and supplierId='{$arr['supplierIds']}'";
        }
        $str.=" group by x.supplierId order by x.supplierId";
        //echo $str;exit;
        $fukuan=$this->_modelExample->findBySql($str);
        
        foreach($fukuan as & $v1){
            $row[$v1['supplierId']]['fukuanMoney']=round(($v1['moneyfukuan']+0),3);
        }
        //dump($str);exit;
        //得到本期发生
        //应付款表中查找
        //按照加工户汇总
        $sql="select sum(money) as fsMoney,supplierId from caiwu_yf_guozhang where 1";
        if($arr['dateFrom']!=''){
            $sql.=" and guozhangDate>='{$arr['dateFrom']}' and guozhangDate<='{$arr['dateTo']}'";
        }
        if($arr['supplierIds']!=''){
            $sql.=" and supplierId='{$arr['supplierIds']}'";
        }
        $sql.=" group by supplierId order by supplierId";
        $rowset = $this->_modelExample->findBySql($sql);
        
        foreach($rowset as & $v2){
            $row[$v2['supplierId']]['fsMoney']=round(($v2['fsMoney']+0),3);
            //$row[$v2['supplierId']]['isY']=$v2['isY'];
        }

        //得到本期发票
        $str1="SELECT sum(x.money) as faPiaoMoney,x.supplierId FROM `caiwu_yf_fapiao` x                 
                where 1";
        if($arr['dateFrom']!=''){
            $str1.=" and x.fapiaoDate>='{$arr['dateFrom']}' and x.fapiaoDate<='{$arr['dateTo']}'";
        }
        if($arr['supplierIds']!=''){
            $str1.=" and x.supplierId='{$arr['supplierIds']}'";
        }
        $str1.=" group by x.supplierId order by x.supplierId";
        $fukuan=$this->_modelExample->findBySql($str1);
        //dump($row);exit;
        foreach ($fukuan as $v2){
            $row[$v2['supplierId']]['faPiaoMoney']=round(($v2['faPiaoMoney']+0),3);
        }
        
        $m=& FLEA::getSingleton('Model_Jichu_Jiagonghu');
        if(count($row)>0){
            foreach($row as $key => & $v){
                $c=$m->find(array('id'=>$key));
                $v['supplierId']=$key;
                $v['compName']=$c['compName'];
                
                $v['weifuMoney']=round(($v['initMoney']+$v['fsMoney']-$v['fukuanMoney']),3);
            }
        }
        
        $heji=$this->getHeji($row,array('initMoney','fukuanMoney','faPiaoMoney','weifuMoney','fsMoney'),'compName');
        foreach($row as $key=>& $v){
            $v['fukuanMoney']="<a href='".url('Caiwu_Yf_Fukuan','right',array(
                        'supplierId'=>$v['supplierId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='付款明细'>".$v['fukuanMoney']."</a>";
            $v['faPiaoMoney']="<a href='".url('Caiwu_Yf_Fapiao','right',array(
                        'supplierId'=>$v['supplierId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'800',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='收票明细'>".$v['faPiaoMoney']."</a>";
            $v['fsMoney']="<a href='".url('Caiwu_Yf_Guozhang','RightAll',array(
                        'supplierId'=>$v['supplierId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='应付明细'>".$v['fsMoney']."</a>";

            //查看对账单
            
            $v['duizhang']="<a href='".$this->_url('Duizhang',array(
                    'dateFrom'=>$arr['dateFrom'],
                    'dateTo'=>$arr['dateTo'],
                    'supplierId'=>$v['supplierId'],
                    //'isY'=>$v['isY'],
                    'no_edit'=>1,
            ))."' target='_blank'>明细</a>";

            $v['_edit']="<a href='".$this->_url('Duizhang',array(
                    'export'=>1,
                    'supplierId'=>$v['supplierId'],
                    'dateFrom'=>$arr['dateFrom'],
                    'dateTo'=>$arr['dateTo'],
            ))."' >导出</a>";
            
            // $v['duizhang'].="   <a href='".$this->_url('Duizhang2',array(
            //         'dateFrom'=>$arr['dateFrom'],
            //         'dateTo'=>$arr['dateTo'],
            //         'supplierId'=>$v['supplierId'],
            //         //'isY'=>$v['isY'],
            //         'no_edit'=>1,
            // ))."' target='_blank'>汇总</a>";
                    
            
        }
        $row[]=$heji;

        $arrFiled=array(
            'compName'=>"对象名称",
            "initMoney" =>"期初余额",
            "fsMoney" =>"本期发生",
            "fukuanMoney" =>"本期付款",
            "weifuMoney" =>"本期结余",
            "faPiaoMoney" =>"本期收票",
            'duizhang'=>'对账单',
            '_edit'=>array('text'=>'导出','width'=>'50'),
            // 'hexiao'=>'核销',
        );
        //dump($row);exit;
        if($_GET['print']){
            unset($arrFiled['duizhang']);
        }
        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info',$arrFiled);
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('add_display','none');
        $smarty->assign('arr_field_value',$row);
        $smarty->assign('heji',$heji);
        $smarty->assign('print_href',$this->_url($_GET['action'],array(
            'print'=>1
        )));
        $smarty->assign('page_info',"<font color='green'>金额已折合人民币</font>");
        $smarty->assign('title','应付款报表');
        if($_GET['print']) {
            //设置账期显示
            $smarty->assign('arr_main_value',array(
                '账期'=>$arr['dateFrom'] . ' 至 ' . $arr['dateTo']
            ));
        }
        $smarty->display($tpl);
}

    function actionRightAll(){
        $title = '应付款查询';
        $tpl = 'TblList.tpl';
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
                'dateFrom'=>date('Y-m-d',mktime(0,0,0,date('m')-1,date('d'),date('Y'))),
                'dateTo'=>date('Y-m-d'),
                'supplierId'=>'',
                'no_edit'=>'',
        ));
        $sql="SELECT x.*,y.proName,y.color,z.compName 
            from caiwu_yf_guozhang x
            left join jichu_product y on x.productId=y.id
            left join jichu_supplier z on z.id=x.supplierId
            where 1";
        if($arr['orderId']>0){
            $arr['dateFrom']='';
            $arr['dateTo']='';
            $sql.=" and x.orderId='{$arr['orderId']}'";
        }
        if($arr['dateFrom']!='')$sql.=" and x.guozhangDate >='{$arr['dateFrom']}' and x.guozhangDate<='{$arr['dateTo']}'";
        if($arr['supplierId']!='')$sql.=" and x.supplierId='{$arr['supplierId']}'";
        if($arr['compName']!='')$sql.=" and z.compName like '%{$arr['compName']}%'";
        if($arr['orderCode']!='')$sql.=" and y.orderCode like '%{$arr['orderCode']}%'";
        if($arr['product']!='')$sql.=" and a.product like '%{$arr['product']}%'";
        $sql.=" order by x.id asc";
        $pager =& new TMIS_Pager($sql);
        $rowset =$pager->findAll();
        //dump($rowset);exit;
        $rowset[] = $this->getHeji($rowset, array('cnt','money'), $_GET['no_edit']==1?'compName':'_edit');
        foreach ($rowset as &$v) {
            $v['rukuDate']=$v['rukuDate']=='0000-00-00'?'':$v['rukuDate'];
        }
        // dump($rowset);exit;
        $arrField = array(
                "_edit"=>array('text'=>'操作','width'=>'80'),
                "compName"=>'应付对象',
                "guozhangDate"=>'过账日期',
                "rukuDate"=>'发生日期',
                "cnt"=>array('text'=>'数量','width'=>70),
                "_money"=>array('text'=>'发生金额','width'=>90),
                "zhekouMoney"=>array('text'=>'折扣金额','width'=>90),
                "money"=>array('text'=>'应付金额','width'=>90),
                // "huilv"=>array('text'=>'汇率','width'=>70),
                "memo"=>"备注",
                "creater"=>"制单人",
        );
        $smarty = & $this->_getView();
        $smarty->assign('title', $title);
        $smarty->assign('arr_field_info',$arrField);
        $smarty->assign('arr_field_value',$rowset);
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('add_display', 'none');
        $smarty->assign('page_info',$pager->getNavBar($this->_url($_GET['action'],$arr)));
        $smarty->display($tpl);
    }

    /**
     * ps ：对账单明细
     * Time：2015/10/14 08:41:39
     * @author jiang
    */
    function actionDuizhang(){
        $arr=$_GET;
        if(empty($arr['supplierId'])){
            echo "缺少供应加工商信息";exit;
        }
        //查找对账单加工户
        $mClient=& FLEA::getSingleton('Model_Jichu_Jiagonghu');
        $jgh=$mClient->find($arr['supplierId']);
        // 对账单设计格式
        //查找期初欠款的情况
        //期初发生
        $sql="SELECT sum(money) as money from caiwu_yf_guozhang where guozhangDate < '{$arr['dateFrom']}' and supplierId='{$arr['supplierId']}'";
        $res1=mysql_fetch_assoc(mysql_query($sql));
        //期初付款
        $sql="SELECT sum(money) as money from caiwu_yf_fukuan where fukuanDate < '{$arr['dateFrom']}' and supplierId='{$arr['supplierId']}'";
        $res2=mysql_fetch_assoc(mysql_query($sql));
        $row['money']=$res1['money']-$res2['money'];
        $row['kind']="<b>期初余额</b>";

        //本期应付款对账信息
        //查找应付款信息
        $sql="SELECT x.*,y.proCode,y.proName 
            from caiwu_yf_guozhang x  
            left join jichu_product y on x.productId=y.proCode           
            where 1";
        if($arr['supplierId']!=''){
            $sql.=" and x.supplierId='{$arr['supplierId']}'";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.guozhangDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.guozhangDate <= '{$arr['dateTo']}'";
        }
        $sql.=" order by rukuDate";
        $rows = $this->_modelExample->findBySql($sql);
        // dump($sql);exit;
        //处理数据
        foreach($rows as  & $v){
            $v['money']=$v['money']==0?'':round($v['money'],2);
            $v['zhekouMoney']=$v['zhekouMoney']==0?'':round($v['zhekouMoney'],2);
            $v['cnt']=$v['cnt']==0?'':round($v['cnt'],2);
            $v['danjia']=$v['danjia']==0?'':round($v['danjia'],2);
            $v['rukuDate']=$v['rukuDate']=='0000-00-00'?'':$v['rukuDate'];
            $v['date']=$v['guozhangDate'];
            $v['ProductInfo']=$v['proCode'].' '.$v['proName'];
            $ret[]=$v;
        }
        
        //获得时间内开票金额
        $sql="SELECT x.fapiaoDate,x.money,x.memo from caiwu_yf_fapiao x
                    where 1";
        if($arr['supplierId']!=''){
            $sql.=" and x.supplierId='{$arr['supplierId']}'";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.fapiaoDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.fapiaoDate <= '{$arr['dateTo']}'";
        }
        $sql.=" order by fapiaoDate";
        $fapiao = $this->_modelExample->findBySql($sql);
        foreach ($fapiao as & $v){
            if($v['money']>0){
                $i++;
                $ret[$i]['date']=$v['fapiaoDate'];
                $ret[$i]['fapiaoMoney']=$v['money'];
                $ret[$i]['memo']=$v['memo'];
            }
        }
        
        //获得时间内付款金额
        $sql="SELECT x.fukuanDate,x.money,x.memo from caiwu_yf_fukuan x
                    where 1";
        if($arr['supplierId']!=''){
            $sql.=" and x.supplierId='{$arr['supplierId']}'";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.fukuanDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.fukuanDate <= '{$arr['dateTo']}'";
        }
        $sql.=" order by fukuanDate";
        $fukuan = $this->_modelExample->findBySql($sql);
        //dump($sql);exit;
        foreach ($fukuan as & $v){
            if($v['money']>0){
                $i++;
                $ret[$i]['date']=$v['fukuanDate'];
                $ret[$i]['fukuanMoney']=$v['money'];
                $ret[$i]['memo']=$v['memo'];
            }
        }
        $ret=array_column_sort($ret,'date');
        $ret2=array_merge(array($row),$ret);
        $heji = $this->getHeji($ret2, array('cnt','money','fapiaoMoney','fukuanMoney'), 'date');
        // $heji['Ymoney']=round(($heji['money']-$heji['fukuanMoney']),2);
        $ret2[]=$heji;
        $arr_field_info=array(
                    'date'=>'日期',   
                    'rukuDate'=>'发生日期',
                    // 'kind'=>'发生工序',
                    'cnt'=>'总数量',
                    // 'proName'=>'品名',
                    // 'proCode'=>'花型六位号',
                    'ProductInfo'=>'产品信息',
                    'money'=>'应付款',
                    'fapiaoMoney'=>'收票',
                    'fukuanMoney'=>'实际付款',
                    // 'Ymoney'=>'余额',
                    'kind'=>'类型',
        ); 
        $smarty=& $this->_getView();
        $smarty->assign('title',"{$jgh['compName']}对账单");
        $smarty->assign('arr_main_value',array('账期'=>$arr['dateFrom'] . ' 至 ' . $arr['dateTo']));
        $smarty->assign('arr_condition',$arr);
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$ret2);
        if ($_GET['export']=='1') {
            //去除html标签
            foreach ($ret2 as $key => & $value) {
                    foreach ($value as $k => & $v) {
                        $v=strip_tags($v);
                    }
            }
            //2016年3月31日17:35:07 对账单导出 by qianyi
            unset($arr_field_info['_edit']);
            $smarty->assign('arr_field_info',$arr_field_info);
            $smarty->assign('arr_field_value',$ret2);
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=test.xls");
            $smarty->display('Export2Excel.tpl');
        }else  $smarty->display('PrintOld.tpl');
    }





    /**
     * ps ：加工入库过账
     * Time：2015/10/22 13:24:12
     * @author liuxin
    */
    function actionListGuozhangJg(){
        $this->authCheck('4-2-2');
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            'danhao'=>'',
            "dateFrom" => date('Y-m-01'), 
            "dateTo" => date('Y-m-d'),
            'proCode'=>'',
            'jiagonghuId'=>'',
        )); 
        $sql = "SELECT x.id,x.cnt,0 as danjia,0 as money,x.unit,y.id as rukuId,y.rukuDate,
                        y.rukuCode,y.kind,k.kuweiName,o.id as supplierId,o.compName,
                        p.proCode,p.proName,p.chengfen,p.shazhi,
                        p.jingmi,p.weimi,p.menfu,p.kezhong,x.productId
                        from cangku_ruku2product x
                        left join cangku_ruku y on x.rukuId=y.id
                        left join jichu_kuwei k on k.id=y.kuweiId
                        left join jichu_supplier o on y.jiagonghuId=o.id
                        left join jichu_product p on p.proCode=x.productId
                        left join caiwu_yf_guozhang g on g.ruku2ProId=x.id
                        where 1 and y.isGuozhang=0 and y.kind='加工入库' and g.id is null";
        $sql.=" and y.rukuDate >= '{$arr['dateFrom']}' and y.rukuDate <= '{$arr['dateTo']}'";
        if($arr['danhao']!='') $sql .=" and y.rukuCode='{$arr['danhao']}' ";
        if($arr['jiagonghuId']!='') $sql .=" and o.id='{$arr['jiagonghuId']}' ";
        if($arr['proCode']!='') $sql .=" and proCode like '%{$arr['proCode']}%'";
        $sql.=" group by x.id order by y.rukuDate desc,y.rukuCode desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();

        foreach ($rowset as & $v) {
            $hj['_edit']='<b>合计</b>';
            $hj['cnt']+=$v['cnt'];
            $v['_edit']="<input type='checkbox' id='chk[]' name='chk[]' value='ruku2ProId:{$v['id']},cnt:{$v['cnt']},unit:{$v['unit']},rukuId:{$v['rukuId']},rukuDate:{$v['rukuDate']},kind:{$v['kind']},kuweiName:{$v['kuweiName']},proCode:{$v['proCode']},supplierId:{$v['supplierId']},productId:{$v['productId']}'/>";
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            $v['cnt']=round($v['cnt'],3);
            $v['danjia']=round($v['danjia'],3);
            $v['money']=round($v['money'],2);
            $heji += $v['money'];
            $hj['money']="<div id='heji' name='heji'>$heji</div>";
            $v['cnt']=$v['cnt']."<input type='hidden' id='cnt[]' name='cnt[]' value='{$v['cnt']}'/>";
            $v['danjia']="<input type='text' id='danjia[]' name='danjia[]' value='{$v['danjia']}'/>";
            $v['zhekouMoney']="<input type='text' id='zhekouMoney[]' name='zhekouMoney[]' value='{$v['zhekouMoney']}'/>";
            $v['_money']="<input type='text' id='_money[]' name='_money[]' value='{$v['money']}'/>";
            $v['money']="<input type='text' id='money[]' name='money[]' value='{$v['money']}' />"."<input type='hidden' id='oldMoney[]' name='oldMoney[]' value='{$v['money']}'/>";
        }
        $rowset[] = $hj;
        $arrFieldInfo = array(
            "_edit"=>array('text'=>"<input type='checkbox' id='checkedAll' title='全选/反选'/>",'width'=>40),
            "rukuCode" => '入库单号',
            "rukuDate" => '入库日期',
            "kind" =>'入库类型',
            "compName" => '加工户',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "cnt" => '数量',
            "danjia" => '单价',
            "_money" => '发生金额',
            "zhekouMoney" => '折扣金额',
            "money" => '入账金额',
            "chengfen" => '成分',
            "jwmi" => '经纬密',
            "menfu" => array('text'=>'门幅','width'=>'50'),
            "kuweiName" => '库位',
            "unit" => '单位',
            );

        $smarty = &$this->_getView();
        $smarty->assign('title', '收发存报表'); 
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $other_url="<button type='button' class='btn btn-info btn-sm' id='save2' name='save2'>保存</button>";
        $smarty->assign('other_url', $other_url);
        $smarty->assign('sonTpl', 'Caiwu/Yf/Guozhang.tpl');
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }
}

?>