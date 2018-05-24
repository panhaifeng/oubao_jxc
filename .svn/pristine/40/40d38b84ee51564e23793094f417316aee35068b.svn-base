<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Order.php
*  Time   :2014/05/13 18:31:40
*  Remark :订单相关api
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_Lib_Jichu extends Api_Response {
    
    
    /**
     * ps ：同步删除货品
     * Time :2015/10/12 09:36:20
     * @author 张艳
     * @param 参数类型
     * @return 返回值类型
    */
    function deleteProduct($params){
        if(!$params['data']) return array('success'=>false,'msg'=>'参数信息为空');

        __TRY();

        foreach ($params['data'] as $key => & $v) {
            $v = "'{$v}'";
        }
        $pros=join(',',$params['data']);
        //查找产品是否在申购、订单、仓库中存在
        $ord = &FLEA::getSingleton('Model_Trade_Order');
        $sql="select distinct productId from (
				select distinct productId from trade_order2product where productId in({$pros})
				union
				select distinct productId from caigou_shengou2product where productId in ({$pros})
				union
				select distinct productId from cangku_kucun where productId in ({$pros})
				) as a where 1";
        $ret=$ord->findBySql($sql);
        
        if($ret){
        	return array('success'=>false,'msg'=>"该商品有订单未处理",'data'=>$ret);
        }
        //删除产品
        $sql="delete from jichu_product where proCode in ({$pros})";
        $ret=$ord->findBySql($sql);
        //捕获异常，如果sql语句非法会抛出异常
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }
        return array('success'=>true,'msg'=>'删除成功','data'=>true);
    }  
    /**
      * ps ：同步删除会员
      * Time :2015/10/12 09:36:49
      * @author 张艳
      * @param 参数类型
      * @return 返回值类型
     */ 
    function deleteClient($params){
        if(!$params['member_id']) return array('success'=>false,'msg'=>'参数信息为空');
        $member_id=$params['member_id'];
       // $clients=join(',',$params['data']);
        __TRY();
        //查找客户是否已经下过订单
        $ord = &FLEA::getSingleton('Model_Trade_Order');
        $sql="select distinct clientId from trade_order where clientId = {$member_id}";
        $ret=$ord->findBySql($sql);
        if($ret){
        	return array('success'=>false,'msg'=>'会员已有订单','data'=>$ret);
        }
        //删除客户
        $sql="delete from jichu_client where member_id = {$member_id}";
        $ret=$ord->findBySql($sql);
        //捕获异常，如果sql语句非法会抛出异常
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }
        return array('success'=>true,'msg'=>'删除成功','data'=>true);
    }
    /**
      * ps ：同步银行账户名称和开户账号，
      * Time :2015/10/28 
      * @author 张艳
      * @param 参数类型
      * @return 返回值类型
     */ 
    function getBankName($params){
        $bank_model = &FLEA::getSingleton('Model_Jichu_Bank');
        __TRY();
        $result=$bank_model->findAll();
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }
        return array('success'=>true,'data'=>$result);
    }


    /**
      * ps ：查找是否有相同的公司名称
      * Time :2016年9月19日15:41:05
      * @author shen
      * @param 参数类型
      * @return 返回值类型
     */ 
    function getCompName($params){
        $client_model = &FLEA::getSingleton('Model_Jichu_client');
        __TRY();
        $sql="select * from jichu_client where compName = '{$params['data']}'";
        $result=$client_model->findBySql($sql);
        if($result){
            return array('success'=>false,'msg'=>'该公司名称已存在！');
        }else{
            return array('success'=>true,'data'=>$result);
        }
    }
}