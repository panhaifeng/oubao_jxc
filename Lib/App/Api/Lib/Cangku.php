<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Order.php
*  Time   :2014/05/13 18:31:40
*  Remark :仓库相关api,小李已经做了，可以去掉，暂时先留着
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_Lib_Cangku extends Api_Response {

    /**
     * @desc ：根据货品id获得可选择配货的码单数据,必须没锁定，
     * @author jeff 2015/09/11 14:31:18
     * @param 参数类型
     * @return 返回值类型
    */
    function getMadan($params) {
        if(!isset($params['product_id']) || $params['product_id']=='') {
            return array('success'=>false,'msg'=>'必须传入product_id');
        }
        $product_id = $params['product_id'];

        $ret = array(
            array('rollCode'=>'1#','barCode'=>'12345','cntM'=>100,'lot No'=>'A','rollId'=>1),
            array('rollCode'=>'2#','barCode'=>'22222','cntM'=>99.2,'lot No'=>'A','rollId'=>2),
            array('rollCode'=>'3#','barCode'=>'33333','cntM'=>98,'lot No'=>'A','rollId'=>3),
            array('rollCode'=>'4#','barCode'=>'444443','cntM'=>120,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'6#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'7#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'8#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'9#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'10#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'11#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'12#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'13#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'14#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
            array('rollCode'=>'15#','barCode'=>'444443','cntM'=>111,'lot No'=>'A','rollId'=>4),
        );
        return array('success'=>true,'data'=>$ret);
    }

    /**
     * 获取库存的api
     * Time：2015/09/14 14:11:04
     * @author li
     * @param kuweiName selected 现货/样品
     * @param ptoduct_id string 产品档案中的proCode，唯一
     * @return array cnt int 库存数量
    */
    function getKucun($params = array()){
        $_param_kuweiName = array('现货','样品');
        if(!in_array($params['kuweiName'], $_param_kuweiName)){
            return array('success'=>false,'msg'=>'参数中的kuweiName不能为空，且为选择项：现货/样品');
        }

        if(!$params['product_id']){
            return array('success'=>false,'msg'=>'参数中的product_id不能为空');
        }

        //模拟数据，主要测试是否连接成功
        // $cnt = $params['kuweiName'] == '现货' ? 1550 : 235;
        //开始查找库存信息
        $_model = FLEA::getSingleton('Model_Cangku_Kucun');
        $cnt = $_model->getKucun($params['kuweiName'],$params['product_id']);

        return array('success'=>true,'data'=>array('cnt'=>round($cnt,2)));
    }

     /**
     * 获取库存的api
     * Time：2016年7月27日13:30:35
     * @author shen
     * @param ptoduct_id string 产品档案中的proCode，唯一
     * @return array cnt int 库存数量
    */
    function getproductsKucun($params){
        if(!$params['product_id']){
            return array('success'=>false,'msg'=>'参数中的product_id不能为空');
        }
        //开始查找库存信息
        $_model = FLEA::getSingleton('Model_Cangku_Kucun');
        $cnt = $_model->getproductsKucun($params);

        return array('success'=>true,'data'=>$cnt);
    }


    /**
     * 在途数
     * Time：2015/09/15 16:47:04
     * @author li
     * @param kuweiName selected 现货/样品
     * @param ptoduct_id string 产品档案中的proCode，唯一
     * @return string 本地在途数的url地址
    */
    function getZaitu($params = array()){
        $_param_kuweiName = array('现货','样品');
        if(!in_array($params['kuweiName'], $_param_kuweiName)){
            return array('success'=>false,'msg'=>'参数中的kuweiName不能为空，且为选择项：现货/样品');
        }

        if(!$params['product_id']){
            return array('success'=>false,'msg'=>'参数中的product_id不能为空');
        }
        __TRY();

        $_model = FLEA::getSingleton('Model_Caigou_Order');

        // dump($_GET);exit;
        $sql="select 
            sum(x.cntM-ifnull(y.cntM,0)) as cnt,
            'M' as unit,
            x.jiaoqi,
            p.proCode,
            p.proName,
            p.chengfen,
            p.shazhi,
            p.menfu,
            p.jingmi,
            p.weimi
            from caigou_order2product x
            left join (
                select sum(cntM) as cntM,cai2proId from cangku_ruku2product group by cai2proId
            ) y on x.id=y.cai2proId
            left join jichu_product p on p.proCode=x.productId
            left join caigou_order j on j.id=x.caigouId
            where 1 and x.productId='{$params['product_id']}' and x.rukuOver=0 and j.shenhe='yes'
            group by x.productId,x.unit,x.jiaoqi
            having cnt>0";

        $rowset=$_model->findBySql($sql);

        foreach ($rowset as $key => & $v) {
            if($v['jiaoqi']=='0000-00-00'){
                $v['jiaoqi']='暂无';
            }
        }

        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }

        return array('success'=>true,'data'=>$rowset);
    }

    /**
     * 保存退换货记录
     * Time：2015/09/30 11:19:10
     * @author li
    */
    function backtask($params = array()){
        // 测试数据
        /*$params=Array(
            'data' => Array                (
                'order_id' => '150916152527738',
                'title' => '颜色不对',
                'type' => 1,
                'add_time' => '2015-10-14 18:22:51',
                'member_id' => 2,
                'return_id' => '20151014183104',
                'product_data' => Array
                    (
                        Array
                            (
                                'bn' => '120001-YP',
                                'name' => 'CAROLA(现货|样品：样品、格型：条布)',
                                'num' => 1,
                                'price' => 44,
                                'productId' => '120001',
                            )

                    ),

                'content' => 'dfhkjsfhkaldsfh',
                'status' => 5,
            )
        );*/

        __TRY();
        $_model = FLEA::getSingleton('Model_Cangku_BackPlan');
        $_controller = FLEA::getSingleton('TMIS_Controller');
        //处理数据，保留有效数据
        $_data =  $params['data'];

        //查找订单是否存在
        $sql="select id from trade_order where orderCode='{$_data['order_id']}'";
        $order = $_model->findBySql($sql);
        $order = $order[0];

        if(!$order['id']>0){
            return array('success'=>false,'msg'=>'没有对应的订单，请确认');exit;
        }

        //实际有效数据集
        $data_arr = array();
        //获取最新编号
        $newCode = $_controller->_getNewCode('BACK','cangku_back_plan','planCode');
        foreach ($_data['product_data'] as $key => & $v) {
            if(empty($v['bn']) || empty($v['num']))continue;
            //查找订单明细id
            $sql="select id from trade_order2product 
                where orderId='{$order['id']}' and bn='{$v['bn']}'";
            $ord2pro = $_model->findBySql($sql);
            $ord2pro = $ord2pro[0];

             //查找该return_id是否已经存在
            $sql="select id from cangku_back_plan where return_id = '{$_data['return_id']}' and ord2proId='{$ord2pro['id']}'";
            $plan = $_model->findBySql($sql);
            $_id = $plan[0]['id'];
            
            $temp = array(
                'orderId'=>$order['id'],
                'ord2proId'=>$ord2pro['id'],
                'orderCode'=>$_data['order_id'],
                'bn'=>$v['bn'],
                'productId'=>$v['productId'],
                'title'=>$_data['title'],
                'type'=>$_data['type'],
                'add_time'=>$_data['add_time'],
                'return_id'=>$_data['return_id'],
                'num'=>$v['num'],
                'price'=>$v['price'],
                'content'=>$_data['content'],
                'name'=>$v['name'],
                'status'=>$_data['status']==5?'拒绝':'',
            );

            if($_id>0){
                $temp['id'] = $_id;
            }else{
                $temp['planCode'] = $newCode;
            }

            $data_arr[] = $temp;
        }
        if(!count($data_arr)>0){
            return array('success'=>false,'msg'=>'没有有效数据，请确认数据');
        }
        
        // dump($data_arr);exit;
        $_model->saveRowset($data_arr);

        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }

        return array('success'=>true,'msg'=>'完成');
    }
    /**
     * @desc ：根据码单id获得码单数据
     * @author shen 2016年4月8日 13:32:54
     * @param 参数类型
     * @return 返回值类型
    */
    function getMadanDetail($params) {
        
        $madan_ids = $params['madan_ids'];
        if(!isset($madan_ids) || $madan_ids=='') {
            return array('success'=>false,'msg'=>'必须传入madan_ids');
        }
        $_ma = FLEA::getSingleton('Model_Cangku_Madan');
        $sql="SELECT x.*,y.jingmi,y.weimi,y.menfu,y.zhengli,y.chengfen 
                from madan_db x
                left join jichu_product y on x.productId=y.proCode
                where x.id in ({$madan_ids})";
        $ret = $_ma->findBySql($sql);

        
        return array('success'=>true,'data'=>$ret);
    }

    /**
     * 所有商品的在途数
     * Time：2016年8月5日11:00:42
     * @author shen
     * @param ptoduct_id string 所有
     * @return string 本地在途数的url地址
    */
    function getproductsZaitu($params = array()){
        __TRY();

        $_model = FLEA::getSingleton('Model_Caigou_Order');

        // dump($_GET);exit;
        $sql="select 
            sum(x.cntM-ifnull(y.cntM,0)) as cnt,
            'M' as unit,
            x.jiaoqi,
            p.proCode,
            p.proName,
            p.chengfen,
            p.shazhi,
            p.menfu,
            p.jingmi,
            p.weimi
            from caigou_order2product x
            left join (
                select sum(cntM) as cntM,cai2proId from cangku_ruku2product group by cai2proId
            ) y on x.id=y.cai2proId
            left join jichu_product p on p.proCode=x.productId
            left join caigou_order j on j.id=x.caigouId
            where 1 and x.rukuOver=0 and j.shenhe='yes'
            group by x.productId,x.unit,x.jiaoqi
            having cnt>0";

        $rowset=$_model->findBySql($sql);

        foreach ($rowset as $key => & $v) {
            if($v['jiaoqi']=='0000-00-00'){
                $v['jiaoqi']='暂无';
            }
        }

        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }

        return array('success'=>true,'data'=>$rowset);
    }
}