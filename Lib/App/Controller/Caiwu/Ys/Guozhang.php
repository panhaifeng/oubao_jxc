<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Caiwu_Ys_Guozhang extends TMIS_Controller {

    function __construct() {
        $this->_check='4-1-1';

        $this->_check2='4-1-2';
        //model
        $this->_modelExample = &FLEA::getSingleton('Model_Caiwu_Ar_Guozhang');

    }

    /**
     * ps ：库存
     * Time：2015/09/30 14:28:40
     * @author jiang
    */
    function actionListGuozhang(){
        $this->authCheck($this->_check);
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            // "dateFrom" => date('Y-m-01'),
            // "dateTo" => date('Y-m-d'),
            'orderCode'=>'',
            'danhao'=>'',
            'proCode'=>'',
            'clientId'=>'',
        ));
        $sql="select * from (
                select x.id,x.cntM,'M' as unit,x.danjia,sum(x.cntM*x.danjia) as money,y.id as chukuId,y.chukuDate,
                        y.chukuCode,y.kind,k.kuweiName,p.proCode,p.proName,p.chengfen,p.shazhi,
                        p.jingmi,p.weimi,p.menfu,p.kezhong,s.compName,o.orderCode,x.orderId,o.clientId,x.productId
                        from cangku_chuku2product x
                        left join cangku_chuku y on x.chukuId=y.id
                        left join jichu_kuwei k on k.id=y.kuweiId
                        left join jichu_product p on p.proCode=x.productId
                        left join trade_order o on o.id=x.orderId
                        left join jichu_client s on s.member_id=o.clientId
                        left join caiwu_ar_guozhang g on g.chuku2ProId=x.id
                        where 1 and y.isGuozhang=0 and g.id is null
                        group by x.id
                union

                select 0 as id,0 as cntM,null as unit,0 as danjia ,o.cost_tax as money,0 as chukuId,null
                        as chukuDate,null as chukuCode,'税金' as kind,
                        null as kuweiName,null as proCode,null as proName,null as chengfen,null as shazhi,
                        null as jingmi,null as weimi,null as menfu,
                        null as kezhong,c.compName,o.orderCode,x.orderId,o.clientId,null as productId
                        from cangku_chuku2product x
                        left join cangku_chuku y on x.chukuId=y.id
                        left join trade_order o on x.orderId=o.id
                        left join jichu_client c on c.member_id=o.clientId
                        left join caiwu_ar_guozhang g on g.orderId=o.id and g.kind='税金'
                        where 1 and y.isGuozhang=0 and g.id is null and o.cost_tax<>0
                        group by o.id
            ) as a where 1 ";
        if($arr['danhao']!='') $sql .=" and chukuCode='{$arr['danhao']}' ";
        if($arr['clientId']!='') $sql .=" and clientId='{$arr['clientId']}' ";
        if($arr['proCode']!='') $sql .=" and proCode like '%{$arr['proCode']}%'";
        if($arr['orderCode']!='') $sql .=" and orderCode like '%{$arr['orderCode']}%'";
        $sql.=" order by chukuDate desc,orderCode desc";
        // dump($sql);exit;
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as & $v) {
            $hj['_edit']='<b>合计</b>';
            $v['cntM']=round($v['cntM'],2);
            $hj['cntM'] += $v['cntM'];
            $hj['cntM'] = round($hj['cntM']);
            $v['_edit']="<input type='checkbox' id='chk[]' name='chk[]' value='chuku2ProId:{$v['id']},cnt:{$v['cntM']},unit:{$v['unit']},chukuId:{$v['chukuId']},chukuDate:{$v['chukuDate']},kind:{$v['kind']},kuweiName:{$v['kuweiName']},proCode:{$v['proCode']},orderId:{$v['orderId']},clientId:{$v['clientId']},productId:{$v['productId']}'/>";
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            $v['danjia']=round($v['danjia'],3);
            $v['money']=round($v['money'],2);
            $heji += $v['money'];
            $hj['money']="<div id='heji' name='heji'>$heji</div>";
            if($v['kind']=='运费过账' or $v['kind']=='订单优惠' or $v['kind']=='税金'){
                $v['danjia']='';
                $v['cntM']='';
            }else{
                 $v['danjia']="<input type='text' id='danjia[]' name='danjia[]' value='{$v['danjia']}'/>";
                 $v['cntM']=$v['cntM']."<input type='hidden' id='cntM[]' name='cntM[]' value='{$v['cntM']}'/>";
            }
            $v['zhekouMoney']="<input type='text' id='zhekouMoney[]' name='zhekouMoney[]' value='{$v['zhekouMoney']}'/>"."<input type='hidden' id='oldZhekou[]' name='oldZhekou[]' value='{$v['zhekouMoney']}'/>";
            $v['_money']="<input type='text' id='_money[]' readonly='true' name='_money[]' value='{$v['money']}'/>";
            $v['money']="<input type='text' id='money[]' readonly='true' name='money[]' value='{$v['money']}' />"."<input type='hidden' id='oldMoney[]' name='oldMoney[]' value='{$v['money']}'/>";
        }
        $rowset[] = $hj;
        $arrFieldInfo = array(
            "_edit"=>array('text'=>"<input type='checkbox' id='checkedAll' title='全选/反选'/>",'width'=>40),
            "orderCode" => array('text'=>'订单号','width'=>'130'),
            "chukuCode" => '出库单号',
            "chukuDate" => '出库日期',
            "kind" =>'类型',
            "compName" => '客户',
            "proCode" => '花型六位号',
            "cntM" => '数量',
            "danjia" => '单价',
            "_money" => '发生金额',
            "zhekouMoney" => '折扣金额',
            "money" => '入账金额',
            "proName" => '品名',
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
        $smarty->assign('sonTpl', 'Caiwu/Ys/Guozhang.tpl');
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }

    function actionRight(){
        $this->authCheck($this->_check2);
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            'orderCode'=>'',
            'key'=>'',
            "dateFrom" => date('Y-m-01'),
            "dateTo" => date('Y-m-d'),
            'proCode'=>'',
            'clientId'=>'',
            'fahuoKind'=>'',
        ));
        $sql="SELECT x.*,o.orderCode,c.compName,k.chukuCode,k.kuweiId,
                p.proCode,p.proName,p.shazhi,p.chengfen,p.menfu,p.jingmi,p.weimi,z.kuweiName
                from caiwu_ar_guozhang x
                left join trade_order o on o.id=x.orderId
                left join jichu_client c on c.member_id=x.clientId
                left join jichu_product p on p.proCode=x.productId
                left join cangku_chuku k on k.id=x.chukuId
                left join jichu_kuwei z on z.id=k.kuweiId
                left join jichu_employ q on c.traderId=q.id
                left join jichu_lidan r on r.id=c.lidanId
                where 1 and x.kind!='其他过账'";
        $sql.=" and x.guozhangDate >= '{$arr['dateFrom']}' and x.guozhangDate <= '{$arr['dateTo']}'";
        if($arr['clientId']!='') $sql .=" and x.clientId='{$arr['clientId']}' ";
        if($arr['key']!='') $sql .=" and c.compName like '%{$arr['key']}%' or c.compCode like '%{$arr['key']}%' or q.employName like '%{$arr['key']}%' or r.lidanName like '%{$arr['key']}%' ";
        if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%'";
        if($arr['orderCode']!='') $sql .=" and o.orderCode like '%{$arr['orderCode']}%'";
        if($arr['fahuoKind']!='') $sql .=" and x.kind = '{$arr['fahuoKind']}'";
        // dump($sql);die;
        $sql.=" order by guozhangDate desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as & $v) {
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            $v['cntM']=round($v['cntM'],3);
            $v['danjia']=round($v['danjia'],3)==0?'':round($v['danjia'],2);
           // $v['money']=round($v['money'],2)==0?'':round($v['money'],2);
           // $v['_money']=round($v['_money'],2)==0?'':round($v['_money'],2);
            $v['zhekouMoney']=round($v['zhekouMoney'],2)==0?'':round($v['zhekouMoney'],2);
            $v['_edit']=$this->getRemoveHtml($v['id']);
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];
            //2016年3月29日09:54:22 运费过账保留1位小数 by qianyi
            if($v['kind']=='运费过账'){
                $v['money']=number_format($v['money'],1)==0?'0':number_format($v['money'],1);
                $v['_money']=number_format($v['_money'],1)==0?'0':number_format($v['_money'],1);
            }else{
                $v['money']=round($v['money'],2)==0?'':round($v['money'],2);
                $v['_money']=round($v['_money'],2)==0?'':round($v['_money'],2);
            }
        }
        $rowset[] = $this->getHeji($rowset, array('money','cntM'), '_edit');
        $arrFieldInfo = array(
            "_edit"=>array('text'=>'操作','width'=>40),
            "orderCode" => array('text'=>'订单号','width'=>120),
            "guozhangDate"=>'过账日期',
            "chukuCode" => '出库单号',
            "chukuDate" => '出库日期',
            "kind" =>'类型',
            "compName" => array('text'=>'客户','width'=>140),
            // "proCode" => '花型六位号',
            "proName" => '品名',
            "cnt" => '数量',
            "danjia" => '单价',
            "_money" => '发生金额',
            "zhekouMoney" => '折扣金额',
            "money" => '入账金额',
            // "chengfen" => '成分',
            // "jwmi" => '经纬密',
            // "menfu" => array('text'=>'门幅','width'=>'50'),
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
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
            if($_GET['export']==1){
                $this->_exportList(array('title'=>$title),$smarty);
            }
        $smarty->display('TblList.tpl');
    }

    function actionSave(){
        dump($_GET);die;
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
            $arr['danjia']=$danjia[$key]+0;
            $arr['money']=$money[$key];
            $arr['_money']=$_money[$key];
            $arr['zhekouMoney']=$zhekouMoney[$key];
            // dump($arr);exit;
            $id=$this->_modelExample->save($arr);
        }
        echo json_encode('true');exit;
    }

  /**
   * ps ：应收款报表
   * Time：2015/10/14 08:41:59
   * @author jiang
  */
    function actionReport(){
        $this->authCheck('4-1-10');
        $tpl = $_GET['print']?'Print.tpl':'TblList.tpl';
        FLEA::loadclass('TMIS_Pager');
        $arr=TMIS_Pager::getParamArray(array(
            'dateFrom'=>date('Y-m-01'),
            'dateTo'=>date('Y-m-d'),
            'clientId'=>'',
        ));
        //得到期初发生
        //应付款表中查找,日期为期初日期
        //按照加工商汇总
        $sql="select sum(money*huilv) as fsMoney,clientId from caiwu_ar_guozhang where guozhangDate < '{$arr['dateFrom']}'";
        if($arr['clientId']!=''){
            $sql.=" and clientId='{$arr['clientId']}'";
        }
        $sql.=" group by clientId order by clientId";
        $rowset = $this->_modelExample->findBySql($sql);
        foreach($rowset as & $v){
            //期初金额
            $row[$v['clientId']]['initMoney']=round(($v['fsMoney']+0),3);//期初余额
            $row[$v['clientId']]['initIn']=round(($v['fsMoney']+0),3);
        }
        //得到起始日期前的收款金额
        //从付款表中查找
        //按照加工商汇总
        $sqlIncome = "SELECT sum(money*huilv) as shouKuanMoney,clientId FROM `caiwu_ar_income` where  shouhuiDate < '{$arr['dateFrom']}'";
        if($arr['clientId']!=''){
            $sqlIncome.=" and clientId='{$arr['clientId']}'";
        }
        $sqlIncome.=" group by clientId order by clientId";
        $rowset = $this->_modelExample->findBySql($sqlIncome);
        foreach($rowset as & $v){
            //期初金额
            $row[$v['clientId']]['initMoney']=round(($row[$v['clientId']]['initMoney']-$v['shouKuanMoney']+0),3);//期初余额=期初发生-期初已付款
            $row[$v['clientId']]['initOut']=round($v['shouKuanMoney'],3);
        }

        //得到本期的已收款
        //付款表中查找
        //按照客户汇总
        $str="SELECT sum(money*huilv) as moneySk,clientId from caiwu_ar_income where 1 ";
        if($arr['dateFrom']!=''){
            $str.=" and shouhuiDate>='{$arr['dateFrom']}' and shouhuiDate<='{$arr['dateTo']}'";
        }
        if($arr['clientId']!=''){
            $str.=" and clientId='{$arr['clientId']}'";
        }
        $str.=" group by clientId order by clientId";
        //echo $str;exit;
        $fukuan=$this->_modelExample->findBySql($str);
        foreach($fukuan as & $v1){
            $row[$v1['clientId']]['moneySk']=round(($v1['moneySk']+0),2);
        }

        //得到本期发生
        //应付款表中查找
        //按照客户汇总
        $sql="select sum(money*huilv) as fsMoney,clientId from caiwu_ar_guozhang where 1";
        if($arr['dateFrom']!=''){
            $sql.=" and guozhangDate>='{$arr['dateFrom']}' and guozhangDate<='{$arr['dateTo']}'";
        }
        if($arr['clientId']!=''){
            $sql.=" and clientId='{$arr['clientId']}'";
        }
        $sql.=" group by clientId order by clientId";
        $rowset = $this->_modelExample->findBySql($sql);
        foreach($rowset as & $v2){
            $row[$v2['clientId']]['fsMoney']=round(($v2['fsMoney']+0),2);
        }

        //本期开票
        $str1="SELECT sum(money*huilv) as faPiaoMoney,clientId FROM `caiwu_ar_fapiao` where 1";
        if($arr['dateFrom']!=''){
            $str1.=" and fapiaoDate>='{$arr['dateFrom']}' and fapiaoDate<='{$arr['dateTo']}'";
        }
        if($arr['clientId']!=''){
            $str1.=" and clientId='{$arr['clientId']}'";
        }
        $str1.=" group by clientId order by clientId";
        $fukuan=$this->_modelExample->findBySql($str1);
        foreach ($fukuan as $v2){
            $row[$v2['clientId']]['faPiaoMoney']=round(($v2['faPiaoMoney']+0),2);
        }

        //已开票总金额
        $str2="SELECT sum(money*huilv) as faPiaoMoneyAll,clientId FROM `caiwu_ar_fapiao` where 1";
        // if($arr['dateFrom']!=''){
        //     $str2.=" and fapiaoDate>='{$arr['dateFrom']}' and fapiaoDate<='{$arr['dateTo']}'";
        // }
        if($arr['clientId']!=''){
            $str2.=" and clientId='{$arr['clientId']}'";
        }
        $str2.=" group by clientId order by clientId";
        $kaipiaoAll=$this->_modelExample->findBySql($str2);
        foreach ($kaipiaoAll as $v2){
            $row[$v2['clientId']]['faPiaoMoneyAll']=round(($v2['faPiaoMoneyAll']+0),2);
        }
        //未开票订单
        $sql="select x.orderCode,x.clientId,sum(x.money) as weikaipiaoMoney from trade_order x where  is_tax_over=0";
        // if($arr['dateFrom']!=''){
        //     $sql.=" and orderTime>='{$arr['dateFrom']} 00:00:00' and orderTime<='{$arr['dateTo']} 23:59:59'";
        // }
        if($arr['clientId']!=''){
            $sql.=" and x.clientId='{$arr['clientId']}'";
        }
        $sql.=" group by clientId order by clientId";
        $rowset=$this->_modelExample->findBySql($sql);
        // dump($rowset);die;
        foreach ($rowset as & $v) {
            $row[$v['clientId']]['weikaipiaoMoney']=round(($v['weikaipiaoMoney']+0),2);
        }
        //开票总额度 按应收
        $str3="select sum(money*huilv) as kpMoney,clientId from caiwu_ar_guozhang where 1";
        // if($arr['dateFrom']!=''){
        //     $str3.=" and guozhangDate>='{$arr['dateFrom']}' and guozhangDate<='{$arr['dateTo']}'";
        // }
        if($arr['clientId']!=''){
            $str3.=" and clientId='{$arr['clientId']}'";
        }
        $str3.=" group by clientId order by clientId";
        $rowset = $this->_modelExample->findBySql($str3);
        foreach($rowset as & $v){
            //期初金额
            $row[$v['clientId']]['kplimit']=round(($v['kpMoney']+0),2);//期初余额
            // $row[$v['clientId']]['initIn']=round(($v['kpMoney']+0),3);
        }

        $mClient=& FLEA::getSingleton('Model_Jichu_Client');
        if(count($row)>0){
            foreach($row as $key => & $v){
                $c=$mClient->find(array('member_id'=>$key));
                $v['clientId']=$key;
                $v['compName']=$c['compName'];

                $v['weishouMoney']=round(($v['initMoney']+$v['fsMoney']-$v['moneySk']),2);

                $v['kplimitAll']=round(($v['kplimit']-$v['faPiaoMoneyAll']),2);
            }
        }

        $heji=$this->getHeji($row,array('initMoney','moneySk','faPiaoMoney','weishouMoney','fsMoney','faPiaoMoneyAll','kplimit','kplimitAll'),'compName');
        foreach($row as $key=>& $v){
            $v['moneySk']="<a href='".url('Caiwu_Ys_Income','right',array(
                        'clientId'=>$v['clientId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='收款明细'>".$v['moneySk']."</a>";
            $v['faPiaoMoney']="<a href='".url('Caiwu_Ys_Fapiao','right',array(
                        'clientId'=>$v['clientId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='开票明细'>".$v['faPiaoMoney']."</a>";
             $v['weikaipiaoMoney']="<a href='".url('Caiwu_Ys_Guozhang','WeikaipiaoD',array(
                        'clientId'=>$v['clientId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'is_tax'=>'true',
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='未开票明细'>".$v['weikaipiaoMoney']."</a>";
            $v['fsMoney']="<a href='".url('Caiwu_Ys_Guozhang','RightAll',array(
                        'clientId'=>$v['clientId'],
                        'dateFrom'=>$arr['dateFrom'],
                        'dateTo'=>$arr['dateTo'],
                        'width'=>'700',
                        'no_edit'=>1,
                        'TB_iframe'=>1,))."' class='thickbox' title='应收明细'>".$v['fsMoney']."</a>";

            //查看对账单
            $v['duizhang']="<a href='".$this->_url('Duizhang',array(
                    'dateFrom'=>$arr['dateFrom'],
                    'dateTo'=>$arr['dateTo'],
                    'clientId'=>$v['clientId'],
                    'no_edit'=>1,
            ))."' target='_blank'>明细</a>";
            //添加导出功能 2016年3月31日17:33:59 by 杜壮
            $v['_edit'] = "<a href='".$this->_url( 'Duizhang' , array(
                    'export'   => 1,
                    'clientId' => $v['clientId'],
                    'dateFrom' => $arr['dateFrom'],
                    'dateTo'   => $arr['dateTo'],
            ) )."' >导出</a>";
        }

        $arrFiled=array(
            "orderNum" =>array('text'=>"序号",'width'=>'40'),
            'compName'=>array('text'=>"客户",'width'=>'200'),
            "initMoney" =>"期初余额",
            "fsMoney" =>"本期发生",
            "moneySk" =>"本期收款",
            "weishouMoney" =>"本期未收款",
            "faPiaoMoney" =>"本期开票",
            "faPiaoMoneyAll" =>"已开票总金额",
            "kplimit" =>"开票总额度",
            // "weikaipiaoMoney"=>"未开票订单",
            "kplimitAll" =>"剩余开票额度",
            'duizhang'=>'对账单',
            '_edit' =>array( 'text'=> '导出' , 'width'=>'50' ),
        );



        if($_GET['print']){
            unset($arrFiled['duizhang']);
        }
        // dump($_GET);die;
        //去除导出功能的A标签 2016年3月29日08:50:00 by jiangxu
        if($_GET['export']==1){
            foreach ($row as & $value) {
                foreach ($value as & $k) {
                    $k=strip_tags($k);
                }
            }
        }

        //导出去掉合计标签
        if($_GET['export']==1){
            $heji['compName'] = strip_tags($heji['compName']);
        }

        //给数组增加排列序号 2016年10月9日13:53:59 by shen
        $i = 1;
        foreach ($row as &$value) {
            $value['orderNum'] = $i;
            $i++;
        }

        $row[]=$heji;
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
        $smarty->assign('title','应收款报表');
        $smarty->assign('page_info',"<font color='green'>金额已折合人民币</font>");
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
            if($_GET['export']==1){
                $this->_exportList(array('title'=>$title),$smarty);
            }
        $smarty->display($tpl);
    }

    function actionRightAll(){
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'),
            "dateTo" => date('Y-m-d'),
            'proCode'=>'',
            'clientId'=>'',
            'orderCode'=>'',
        ));
        $sql="select x.*,o.orderCode,c.compName,k.chukuCode,
                p.proCode,p.proName,p.shazhi,p.chengfen,p.menfu,p.jingmi,p.weimi,z.kuweiName
                from caiwu_ar_guozhang x
                left join trade_order o on o.id=x.orderId
                left join jichu_client c on c.member_id=x.clientId
                left join jichu_product p on p.proCode=x.productId
                left join cangku_chuku k on k.id=x.chukuId
                left join jichu_kuwei z on z.id=k.kuweiId
                where 1";
        $sql.=" and x.guozhangDate >= '{$arr['dateFrom']}' and x.guozhangDate <= '{$arr['dateTo']}'";
        if($arr['clientId']!='') $sql .=" and x.clientId='{$arr['clientId']}' ";
        if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%'";
        if($arr['orderCode']!='') $sql .=" and o.orderCode like '%{$arr['orderCode']}%'";
        $sql.=" order by guozhangDate,orderCode";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        foreach ($rowset as & $v) {
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];
            $v['cnt']=round($v['cnt'],3);
            $v['danjia']=round($v['danjia'],3);
            $v['money']=round($v['money'],2);
            $v['_edit']=$this->getRemoveHtml($v['id']);
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];
        }
        $rowset[] = $this->getHeji($rowset, array('money','cnt'), $_GET['no_edit']==1?'guozhangDate':'_edit');
        $arrFieldInfo = array(
            "_edit"=>array('text'=>'操作','width'=>70),
            "guozhangDate"=>'过账日期',
            "chukuCode" => '出库单号',
            "chukuDate" => '出库日期',
            "orderCode" => '订单号',
            "kind" =>'类型',
            "compName" => '客户',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "cnt" => '数量',
            "danjia" => '单价',
            "money" => '金额',
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

    /**
     * ps ：对账单明细
     * Time：2015/10/14 08:41:39
     * @author jiang
    */
    function actionDuizhang(){
        $arr=$_GET;
        $tpl = $arr['print']?'PrintNewdz.tpl':'PrintDuizhang.tpl';

        if(empty($arr['clientId'])){
            echo "缺少客户信息";exit;
        }

        //应收款 本期发生金额
        $sql="select sum(money*huilv) as fsMoney,clientId from caiwu_ar_guozhang where 1";
        if($arr['dateFrom']!=''){
            $sql.=" and guozhangDate>='{$arr['dateFrom']}' and guozhangDate<='{$arr['dateTo']}'";
        }
        if($arr['clientId']!=''){
            $sql.=" and clientId='{$arr['clientId']}'";
        }
        $sql.=" group by clientId order by clientId";
        $rowset_fs = $this->_modelExample->findBySql($sql);
        $fs_money= round($rowset_fs[0]['fsMoney'],2);

        //应收款 本期收款
        $str="SELECT sum(money*huilv) as moneySk,clientId from caiwu_ar_income where 1 ";
        if($arr['dateFrom']!=''){
            $str.=" and shouhuiDate>='{$arr['dateFrom']}' and shouhuiDate<='{$arr['dateTo']}'";
        }
        if($arr['clientId']!=''){
            $str.=" and clientId='{$arr['clientId']}'";
        }
        $str.=" group by clientId order by clientId";
        $rowset_fk=$this->_modelExample->findBySql($str);
        $fk_money= round($rowset_fk[0]['moneySk'],2);

        //应开票
        //开票额度 按应收
        $sqlkpall="select sum(money*huilv) as kpMoney,clientId from caiwu_ar_guozhang where 1 ";
        if($arr['clientId']!=''){
            $sqlkpall.=" and clientId='{$arr['clientId']}'";
        }
        $rowset_kpall = $this->_modelExample->findBySql($sqlkpall);
        $money_kpall = $rowset_kpall[0]['kpMoney'];

        //所有开票金额
        $sql="SELECT sum(money*huilv) as faPiaoMoneyAll,clientId FROM `caiwu_ar_fapiao` where 1 and clientId='{$arr['clientId']}'";
        $kaipiaoAll=$this->_modelExample->findBySql($sql);
        $kp_jieyu=round($money_kpall-$kaipiaoAll[0]['faPiaoMoneyAll'],2);

        //到上期为止的开票额度-到上期为止的开票金额=上期结余
        //额度
        $kplimit_before="select sum(money*huilv) as kpMoney,clientId from caiwu_ar_guozhang where 1 ";
        if($arr['clientId']!=''){
            $kplimit_before.=" and clientId='{$arr['clientId']}'";
        }
        if($arr['dateFrom']!=''){
            $kplimit_before.=" and guozhangDate<'{$arr['dateFrom']}'";
        }
        $rowset_kplb = $this->_modelExample->findBySql($kplimit_before);
        $money_kplb = $rowset_kplb[0]['kpMoney'];

        $sqlkp="SELECT sum(money*huilv) as faPiaoMoneyAll,clientId FROM `caiwu_ar_fapiao` where 1 ";
        if($arr['clientId']!=''){
            $sqlkp.=" and clientId='{$arr['clientId']}'";
        }
        if($arr['dateFrom']!=''){
            $sqlkp.=" and fapiaoDate<'{$arr['dateFrom']}'";
        }
        // dump($sqlkp);die;
        $kpBefore=$this->_modelExample->findBySql($sqlkp);
        $money_kpBefore = $kpBefore[0]['faPiaoMoneyAll'];
        $money_jieyu = round($money_kplb-$money_kpBefore,2);

        //
        $sqlkpC="select sum(money)as fpmoneyCurr from caiwu_ar_fapiao  where 1";
        if($arr['clientId']!=''){
            $sqlkpC.=" and clientId='{$arr['clientId']}'";
        }
        if($arr['dateFrom']!=''){
            $sqlkpC.=" and fapiaoDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sqlkpC.=" and fapiaoDate <= '{$arr['dateTo']}'";
        }
        $sqlkpC.=" order by fapiaoDate";
        $kpCurr = $this->_modelExample->findBySql($sqlkpC);
        $money_kpC = round($kpCurr[0]['fpmoneyCurr'],2);



        //查找对账单客户
        $mClient=& FLEA::getSingleton('Model_Jichu_Client');
        $jgh=$mClient->find(array('member_id'=>$arr['clientId']));
        // 对账单设计格式
        //查找期初欠款的情况
        //期初发生
        $sql="select sum(money) as money from caiwu_ar_guozhang where guozhangDate < '{$arr['dateFrom']}' and clientId='{$arr['clientId']}'";
        $res1=mysql_fetch_assoc(mysql_query($sql));
        //期初付款
        $sql="select sum(money) as money from caiwu_ar_income where shouhuiDate < '{$arr['dateFrom']}' and clientId='{$arr['clientId']}'";
        $res2=mysql_fetch_assoc(mysql_query($sql));
        $row['money']=round($res1['money']-$res2['money'],2);
        $qichu=round($res1['money']-$res2['money'],2);
        // $row['kind']="<b>期初余额</b>";
        $row['orderCode']="<b>期初</b>";

        //本期应付款对账信息
        //查找应付款信息
        $sql="select x.*,c.orderCode,c.id as cid,c.discount,c.youhui,c.payment,c.memo as tradeMemo,a.shazhi,a.proCode,a.proName,a.chengfen,a.menfu,a.jingmi,a.weimi
            from caiwu_ar_guozhang x
            left join jichu_product a on x.productId=a.proCode
            left join cangku_chuku2product b on b.chukuId=x.chukuId
            left join trade_order c on c.id=x.orderId
            where 1";

        if($arr['clientId']!=''){
            $sql.=" and x.clientId='{$arr['clientId']}'";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.guozhangDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.guozhangDate <= '{$arr['dateTo']}'";
        }
        $sql.=" group by x.id order by c.orderCode,guozhangDate";
        // dump($sql);exit;

        $rows = $this->_modelExample->findBySql($sql);
        foreach ($rows as $key =>& $value) {
            $value['payment']=$this->getPayment($value['payment']);
            // $value['money'] = $value['money'] + $value['discount'] - $value['youhui'];
            $sql="select * from trade_order2product where id ='{$value['ord2proId']}'";
            $rr = $this->_modelExample->findBySql($sql);
            $value['productKind'] = $rr[0]['kind'];
            //运费过账和订单减免显示订单编号
            if($value['orderCode']==''){
                $aa="select orderCode from trade_order where id ='{$value['orderId']}'";
                $rs = $this->_modelExample->findBySql($aa);
                $value['orderCode'] = $rs[0]['orderCode'];
            }
        }
        //查找已收款信息
        $sql="select x.money*x.huilv as shouhuimoney,x.shouhuiDate as guozhangDate,x.memo,x.type from caiwu_ar_income x where 1 ";
        if($arr['clientId']!=''){
            $sql.=" and x.clientId='{$arr['clientId']}'";
        }
        if($arr['dateFrom']!=''){
            $sql.=" and x.shouhuiDate >= '{$arr['dateFrom']}'";
        }
        if($arr['dateTo']!=''){
            $sql.=" and x.shouhuiDate <= '{$arr['dateTo']}'";
        }
        $sql.=" order by shouhuiDate";
        $rows2 = $this->_modelExample->findBySql($sql);
        foreach ($rows2 as $key => &$v2) {
            $v2['payment']=$v2['type'];
            $v2['orderCode']='实收';
        }
        //合并应收款与已收款明细信息
        //if(count($rows2) > 0){
        $rows=array_merge($rows,$rows2);
        //按照日期排序
        $rows=array_column_sort($rows,'guozhangDate',SORT_ASC);
    //  }
        // dump($sql);exit;
        //处理数据
        foreach($rows as  & $v){
            $v['shoukuanMoney']=$v['shouhuimoney']==0?'':round($v['shouhuimoney'],3);
            $v['money']=$v['money']==0?'':round($v['money'],2);
            $v['moneyRmb']=$v['money']==0?'':round($v['money']*$v['huilv'],2);

            if(!empty($v['cnt']))$v['cnt']=round($v['cnt'],2);
            $v['danjia']=round($v['danjia'],3);
            $v['danjia']=$v['danjia']==0?'':$v['danjia'];
            $v['cnt']=$v['cnt']==0?'':$v['cnt'];
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];
            //处理运费过账 其他过账显示
            if($v['orderCode']==''){
                // $v['orderCode']=$v['kind'];
            }
            $v['ProductInfo']=$v['proCode'].' '.$v['productKind'];
            //.' '.$v['chengfen'].' '.$v['menfu']
        }
        $i=0;
        foreach ($rows as & $v){
            $v['date']=$v['guozhangDate'];
            $ret[$i]=$v;
                $i++;
        }
        //获得时间内开票金额
        $sql="select x.fapiaoDate,x.money,x.memo from caiwu_ar_fapiao x
                    where 1";
        if($arr['clientId']!=''){
            $sql.=" and x.clientId='{$arr['clientId']}'";
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

        // dump($ret);exit;   'orderCode' => SORT_ASC,
        //$ret=array_sortby_multifields($ret, array('orderCode' => SORT_ASC,'date' => SORT_ASC));
        $ret=array_column_sort($ret,'date');
        $ret=array_merge(array($row),$ret);

        //增加一列序号 统计数据 2016年10月9日13:50:17 by shen
        foreach ($ret as $kk => &$value) {
            $value['orderNum'] = $kk+1;
        }
        $heji = $this->getHeji($ret, array('cnt','money','fapiaoMoney','shoukuanMoney'), 'date');
        $heji['Ymoney']=round(($heji['money']-$heji['shoukuanMoney']),2);
        $heji['money']=round($heji['money'],2);
        $heji['cnt']=round($heji['cnt'],2);
        $heji['cnt']=$heji['cnt'].'M';
        $ret[]=$heji;

        // 应收款
        $jy_money=round(($qichu+$fs_money-$fk_money),2);
        $sk['orderCode']='<b>应收款</b>';
        $sk['date']='上期结余';
        $sk['ProductInfo']=$qichu;
        $sk['cnt']='本期发生';
        $sk['danjia']=$fs_money;
        $sk['money']='本期收款';
        $sk['shoukuanMoney']=$fk_money;
        $sk['payment']='本期结余';
        $sk['kind']=$jy_money;
        $ret_else[]=$sk;
        //应开票
        $kp['orderCode']='<b>应开票</b>';
        $kp['date']='上期结余';
        $kp['ProductInfo']=$money_jieyu;
        $kp['cnt']='本期应开票';
        $kp['danjia']=$fs_money;
        $kp['money']='本期开票';
        $kp['shoukuanMoney']=$money_kpC;
        $kp['payment']='本期结余';
        $kp['kind']=$kp_jieyu;
        $ret_else[]=$kp;

        $arr_field_info=array(
            'orderNum'=>'序号',
            'orderCode'=>'订单号',
            'date'=>'日期',
            // 'chukuDate'=>'发生日期',
            // "proCode" => '花型六位号',
            // "proName" => '品名',
            // "chengfen" => '成分',
            // "menfu" => '门幅',
            'ProductInfo'=>'产品信息',
            'cnt'=>'数量',
            'danjia'=>'单价',
            'money'=>'应收款',
            'shoukuanMoney'=>'实收',
            'fapiaoMoney'=>'开票',
            // 'Ymoney'=>'余额',
            'payment'=>'付款方式',
            'kind'=>array('text'=>'类型','width'=>'90px'),
            'tradeMemo'=>array('text'=>'订单备注','width'=>'100px'),
            'memo'=>array('text'=>'备注','width'=>'100px'),
        );
        // dump($ret_else);exit;
        if($_GET['print']){
            unset($arr_field_info['memo']);
            unset($arr_field_info['tradeMemo']);
        }
        $smarty=& $this->_getView();
        $smarty->assign('title',"{$jgh['compName']}对账单");
        $smarty->assign('arr_main_value',array('账期'=>$arr['dateFrom'] . ' 至 ' . $arr['dateTo']));
        $smarty->assign('arr_condition',$arr);
        if($_GET['no_edit']!=1){
            $smarty->assign('sonTpl',"Caiwu/Ys/sonTpl.tpl");
        }
        $smarty->assign('print_href',$this->_url($_GET['action'],array(
            'print'=>1,
            'clientId'=>$arr['clientId'],
            'dateFrom'=>$arr['dateFrom'],
            'dateTo'=>$arr['dateTo'],
        )));
        $smarty->assign('arr_field_info',$arr_field_info);
        $smarty->assign('arr_field_value',$ret);
        $smarty->assign('arr_field_value2',$ret_else);
        //去除HTML标签 添加导出为xml文件功能 2016年3月31日17:33:59 by 杜壮
        if($_GET['export'] == '1' ) {
            foreach ($ret as $key => & $value) {
                foreach ($value as $key => & $v) {
                $v = strip_tags($v);
                }
            }
            unset($arr_field_info['_edit']);
            $smarty->assign('arr_field_info' , $arr_field_info );
            $smarty->assign('arr_field_value' , $ret );
            header( "Content-type: application/vnd.ms-excel" );
            header( "Content-Disposition: attachment; filename=test.xls" );
            $smarty->display('Export2Excel.tpl');
        }
        // else $smarty->display('PrintOld.tpl');
        echo $smarty->display($tpl);
    }

    function actionWeikaipiaoD(){
        // dump($_GET);exit;
       //权限判断
        $this->authCheck('2-3');
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'orderCode'=>'',
            'clientId'=>'',
            'proCode' => '',
            'is_tax'=>'',
        ));
        $sql="SELECT * from trade_order x
            where x.is_tax='true' and x.is_tax_over='0'";
        // if($arr['clientId']!='') $sql .=" and x.clientId='{$arr['clientId']}' ";
        // if($arr['is_tax']!='') $sql .=" and x.is_tax='{$arr['is_tax']}' ";
        // if($arr['proCode']!='') $sql .=" and p.proCode like '%{$arr['proCode']}%' ";
        // if($arr['orderCode']!='') $sql .=" and x.orderCode like '%{$arr['orderCode']}%' ";
        // if($arr['order_kind']!='') $sql .=" and y.kind = '{$arr['order_kind']}' ";
        // if($arr['employId']!='') $sql .=" and x.traderId = '{$arr['employId']}'  ";

        $sql.=" order by x.orderTime desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($rowset);exit;

        if (count($rowset) > 0) foreach($rowset as &$v) {
            $v['orderTime']=$v['orderTime']=='0000-00-00 00:00:00'?'':$v['orderTime'];
            $v['cost_freight']=round($v['cost_freight'],3);
            $v['cnt']=round($v['cnt'],2);

            //审核后的不能修改删除
            $statesh=$this->stateShenhe('销售合同',$v['sId']);
            $over=$v['is_setover']==0?1:0;

            if($statesh=='未审核'){
                if($v['kind']=='大货'){
                    $v['_edit']=$this->getEditHtml($v['id']).' '.$this->getRemoveHtml($v['id']);

                }else{
                    $v['_edit'].="<span class='glyphicon glyphicon-pencil' ext:qtip='电商下单，禁止操作'></span>";
                    $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='电商下单，禁止操作'></span>";
                }
            }else {
                $v['_edit'].="<span class='glyphicon glyphicon-pencil' ext:qtip='已审核不能修改'></span>";
                $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='已审核不能删除'></span>";
            }

            if($v['kind']=='大货'){
                if($v['is_setover']!='1'){
                    $v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('SetOver',array(
                        id=>$v['id'],
                        'over'=>$over,
                        'fromAction'=>$_GET['action']
                    ))."'><span class='glyphicon glyphicon-ok' ext:qtip='完成'></span></a>";
                }else{
                    $v['_edit']="<span class='glyphicon glyphicon-pencil' ext:qtip='合同完成，禁止操作'></span>";
                    $v['_edit'].="&nbsp;<span class='glyphicon glyphicon-trash ' ext:qtip='合同完成，禁止操作'></span>";
                    $v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('SetOver',array(
                        id=>$v['id'],
                        'over'=>$over,
                        'fromAction'=>$_GET['action']
                    ))."'><span class='glyphicon glyphicon-share-alt' ext:qtip='取消完成'></span></a>";
                }
            }

            // if($statesh=='拒绝'){
            //  $statesh="<p class='bg-danger'>{$statesh}</p>";
            // }elseif($statesh=='已通过'){
            //  $statesh="<p class='bg-success'>{$statesh}</p>";
            // }

            //显示审核信息
            $_detial=$this->showShenhe('销售合同',$v['sId']);
            if($statesh=='拒绝'){
                $v['state'] = "<a href='javascript:;' class='bg-danger' ext:qtip=\"{$_detial}\">{$statesh}</a>";
            }elseif($statesh=='已通过'){
                $v['state'] = "<a href='javascript:;' class='bg-success' ext:qtip=\"{$_detial}\">{$statesh}</a>";
            }else{
                $v['state'] = "<a href='javascript:;' ext:qtip=\"{$_detial}\">{$statesh}</a>";
            }

            $v['_edit'].="&nbsp;&nbsp;<a href='".$this->_url('Print',array(id=>$v['id']))."' target='_blank'><span class='glyphicon glyphicon-print' title='打印'></span></a>";
            $v['is_tax']=$v['is_tax']=='true'?'是':'否';

            //支付方式
            $v['payment']=$this->getPayment($v['payment']);

            //是否发货
            if($v['is_delivery']=='N'){
                $v['is_delivery']="<p class='bg-danger' title='{$v['isdelivery_desc']}'>{$v['is_delivery']}</p>";
            }

            $v['status'] = $this->getStatusOrder($v['status'] ,true);
            //显示币种
            $this->getBizhong($v['currency']);
            //税率
            // $v['tax'] = round(($v['cost_tax']/($v['money']-$v['cost_freight']-$v['cost_tax'])*100),2).' %';
            $v['tax'] = round($v['cost_tax'],2);
            //数量
            $v['cnt'] = round($v['cnt'],2);
            //整单折扣
            $v['pmt_money'] = round($v['pmt_money'],2);
        }
        $smarty = &$this->_getView();
        $arrFieldInfo = array(
            "_edit" => '操作',
            'orderCode'=>array('width'=>'130','text'=>'订单号'),
            'orderTime'=>array('width'=>'130','text'=>'下单时间'),
            'state'=>array('width'=>'70','text'=>'审核状态'),
            'status'=>array('width'=>'70','text'=>'订单状态'),
            "compName" => "客户",
            "employName"=>"业务员",
            'payment'=>'支付方式',
            'is_delivery'=>array('width'=>'70','text'=>'是否发货'),
            'shipping'=>'配送方式',
            'ship_name'=>array('width'=>'70','text'=>'收货人'),
            'ship_addr'=>'收货地址',
            'pmt_order'=>array('width'=>'70','text'=>'订单优惠'),
            'cost_freight'=>array('width'=>'70','text'=>'配送费用'),
            'currency'=>array('width'=>'70','text'=>'支付货币'),
            'money' =>array('width'=>'70','text'=>'总金额'),
            // 'is_tax'=>array('width'=>'70','text'=>'是否开票'),
            'proCode'=>array('width'=>'70','text'=>'花型六位号'),
            'proName'=>array('width'=>'70','text'=>'产品名称'),
            'kind'=>array('width'=>'70','text'=>'类型'),
            // "color" => "颜色",
            'cnt'=>array('width'=>'70','text'=>'数量'),
            'unit'=>array('width'=>'70','text'=>'单位'),
            'danjia'=>array('width'=>'70','text'=>'单价'),
            // 'tax'=>array('width'=>'70','text'=>'税率'),
            //订单税率改为订单税金，by张艳 2015-11-10 根据蒋会蒋会提示
            'tax' =>array('width'=>'70','text'=>'税金'),
            "memo" => '备注',
        );
        $smarty->assign('title', '计划查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr))."&nbsp;<span class='text-danger'>是否发货：红色表示不用发货</span>");
        $smarty->display('TblList.tpl');
    }
}
