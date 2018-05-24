<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Order.php
*  Time   :2014/05/13 18:31:40
*  Remark :订单相关api
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_Lib_Caiwu extends Api_Response {


    /**
     * ps ：同步收款记录
     * Time：2015/10/13 17:37:14
     * @author jiang
    */
    function createYsGuozhang($params = array()){
        if(!$params) return array('success'=>false,'msg'=>'参数信息为空');
        $ret=$params;
        __TRY();
        //查找订单Id
        $modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $modelIncome = FLEA::getSingleton('Model_Caiwu_Ar_Income');

        //判断是否已经同步过此支付单号的收款 2016年12月9日 09:08:04  by shen
        if($ret['payment_id']){
            $_already = $modelIncome->find(array('payment_id'=>$ret['payment_id']));
            if($_already){
              return array('success'=>false,'msg'=>'已经同步过此支付单号的收款');
            }
        }

        if($ret['order_id']){
            $row = $modelOrder->find(array('orderCode'=>$ret['order_id']));
        }

        if(!$ret['member_id']){
            return array('success'=>false,'msg'=>'客户必须存在');
        }

        //查找银行账户
        if($ret['bank_name']!=''){
            $sql="select id from jichu_bank where itemName = '{$ret['bank_name']}'";
            $_bank = $modelOrder->findBySql($sql);
            $_bankId = $_bank[0]['id'];
        }

        $arr=array(
            'type'=>($ret['paymethod']=='alipay') ? '支付宝' : $ret['paymethod'],
            'bankId'=>$_bankId+0,
            'orderId'=>$row['id'].'',
            'clientId'=>$ret['member_id'].'',
            'shouhuiDate'=>$ret['mtime'].'',
            'money'=>$ret['money']+0,
            'payment_id'=>$ret['payment_id'].'',
            'bizhong'=>'CNY',
            'memo'=>$ret['memo'].'',
        );

        //财务应收款
        $modelIncome->save($arr);

        //同步数据到订单中
        if(isset($ret['pay_status']) && $row['id']>0){
            $model_order = FLEA::getSingleton('Model_Trade_Order');
            //更新条件
            $_condition = " id = '{$row['id']}'";
            $model_order->updateField($_condition ,'pay_status',$ret['pay_status']+0);
        }
        //捕获异常，如果sql语句非法会抛出异常
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }
        return array('success'=>true,'msg'=>'同步成功','data'=>true);
    }

    /**
     * 退款api
     * Time：2015/10/14 19:37:00
     * @author li
    */
    function moneyback($params = array()){
        $data = $params;
        if(!$data){
            return array('success'=>false,'msg'=>'参数信息为空');
        }

        //订单号必须存在
        if(!$data['order_id']){
            return array('success'=>false,'msg'=>'订单号必须存在');
        }

        //客户必须存在
        if(!$data['member_id']){
            return array('success'=>false,'msg'=>'客户必须存在');
        }

        __TRY();
        //查找订单Id
        $modelOrder = FLEA::getSingleton('Model_Trade_Order');
        $modelIncome = FLEA::getSingleton('Model_Caiwu_Ar_Income');

        //查找订单
        if($data['order_id']){
            $row = $modelOrder->find(array('orderCode'=>$data['order_id']));
        }

        //查找是否已经发货
        $sql="select count(*) as cnt from cangku_chuku2product where orderId = '{$row['id']}'";
        $_chuku = $modelOrder->findBySql($sql);
        if($_chuku['cnt']>0){
            return array('success'=>false,'msg'=>'已出库，请先退货再退款');
        }

        //查找是否存在出库单
        $sql="select count(*) as cnt from chuku_plan where orderId = '{$row['id']}'";
        $_plan = $modelOrder->findBySql($sql);
        if($_plan['cnt']>0){
            return array('success'=>false,'msg'=>'已有出库单，请先取消发货再退款');
        }

        if($data['paymethod']!='预存款支付'){
            //查找银行账户
            if($data['bank_name']!=''){
                $sql="select id from jichu_bank where itemName = '{$data['bank_name']}'";
                $_bank = $modelOrder->findBySql($sql);
                $_bankId = $_bank[0]['id'];
            }
            //同步收款记录
            $arr=array(
                'type'=>$data['paymethod'].'',
                'orderId'=>$row['id'],
                'clientId'=>$data['member_id'],
                'shouhuiDate'=>$data['mtime'].'',
                'money'=>$data['money']+0,
                'bizhong'=>'CNY',
                'memo'=>$data['memo'].'',
                'bankId'=>$_bankId+0,
            );

            //财务应收款
            $modelIncome->save($arr);
        }
        //同步数据到订单中
        if(isset($data['pay_status']) && $row['id']){
            $model_order = FLEA::getSingleton('Model_Trade_Order');
            //更新条件
            $_condition = " id = '{$row['id']}'";
            $model_order->updateField($_condition ,'pay_status',$data['pay_status']+0);
        }

        //捕获异常，如果sql语句非法会抛出异常
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }
        return array('success'=>true,'msg'=>'同步成功','data'=>true);
    }

    /**
     * 开票同步api
     * Time：2015-12-23 16:03:21
     * @author shen
    */
    function kaipiaolimit($params = array()){
        $data = $params;
        //客户必须存在
        if(!$data['member_id']){
            return array('success'=>false,'msg'=>'客户必须存在');
        }
        $modelFapiao = FLEA::getSingleton('Model_Caiwu_Ar_Fapiao');
        $modelGuozhang = FLEA::getSingleton('Model_Caiwu_Ar_Guozhang');

        //所有开票金额
        $sql="SELECT sum(money*huilv) as faPiaoMoneyAll,clientId FROM `caiwu_ar_fapiao` where 1 and clientId='{$data['member_id']}'";
        $kaipiaoAll=$modelFapiao->findBySql($sql);

        //开票额度 按应收
        $str="select sum(money*huilv) as kpMoney,clientId from caiwu_ar_guozhang where 1 and clientId='{$data['member_id']}'";
        $rowset = $modelGuozhang->findBySql($str);
        //提取开票所有明细通过api接口同步商城
        $kpsql="select * from caiwu_ar_fapiao where 1 and clientId='{$data['member_id']}'";
        $fpmingxi=$modelFapiao->findBySql($kpsql);

        $kp_limit=$rowset[0]['kpMoney']-$kaipiaoAll[0]['faPiaoMoneyAll'];
        $ret=array(
            "kaipiao"=>$kaipiaoAll[0]['faPiaoMoneyAll'],
            "kaipiaoover"=>$rowset[0]['kpMoney'],
            "kaipiaolimit"=>$kp_limit,
            "mingxi"=>$fpmingxi
            );
        return array('success'=>true,'data'=>$ret);

    }
}