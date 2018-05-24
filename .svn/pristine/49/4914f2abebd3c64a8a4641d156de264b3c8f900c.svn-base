<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :Peihuo.php
*  Time   :2015/09/10 19:54:52
*  Remark :配货单API，提供配货单码单数据
\*********************************************************************/
FLEA::loadClass('Api_Response');
class Api_Lib_Peihuo extends Api_Response {

    //重写_checkParams,参数是否合法可能包含业务逻辑
    function _checkParams($params) {
        return true;
    }


    /**
     * 获取所有的可以配货的码单数据
     * 已锁定的不获取
     * status=active可以获取
     * status=lock锁定
     * Time：2015/09/10 19:56:31
     * @author li
     * @param params Array
     * @param params.productId 产品id ：必须
     * @param params.millNo 批次信息 ：不是必须
     * @return array 码单的数据集
    */
    function getDataActive($params = array()){
        //参数
        $productId = $params['productId'];
        $millNo = $params['millNo'];

        if(!$productId>0) {
            return array('success'=>false,'msg'=>'参数中的productId必须大于0');
        }

        __TRY();

        //码单model
        $_model = FLEA::getSingleton('Model_Cangku_Madan');
        $_model->clearLinks();

        //筛选条件
        // $_condition[]=array('status','active','=');//状态为active
        // $_condition[]=array('productId',$productId,'=');//productId为参数中的productId
        // $millNo!='' && $_condition[]=array('millNo',$millNo,'=');//millNo为参数中的millNo

        //注意，这里提供的数据只需要提供M数就可以了，ec中需要统一显示米数 *
        /*$sql="select
            x.id,
            x.productId,
            x.millNo,
            x.cntM as cnt,
            'M' as unit,
            x.cnt as cntUnit,
            x.rollNo,
            x.baleNo,
            x.qrcode
            from madan_db x
            inner join madan_rc2madan y on x.id=y.madanId
            inner join cangku_ruku2product z on z.id=y.rukuId
            where x.status='active' and x.productId='{$productId}' order by x.millNo,x.rollNo";*/
        $sql="select id,productId,millNo,if(unit='Y',cnt,cntM) as cnt,if(unit='Y',cnt*0.9144,cntM) as cntMi,if(unit='Y','Y','M') as unit,cnt as cntUnit,rollNo,baleNo,qrcode from madan_db where status='active' and productId='{$productId}' order by millNo,rollNo";
        $rowset = $_model->findBySql($sql);
        // dump($rowset);exit;
        foreach ($rowset as $key => & $v) {
            $v['cnt'] = round($v['cnt'],3);
        }

        // $rowset = $this->getData();
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }

        return array('success'=>true,'data'=>$rowset);
    }

    /**
     * ps ：自动配货
     * Time：2015/10/26 15:35:20
     * @author liuxin
     * @param array productId,cntM
     * @return array 码单数据集
    */
    function autoPeihuo($params = array()){
        // $import_data=print_r($params,1);
        // file_put_contents('data/test.txt',$import_data,FILE_APPEND);
        //参数
        $productId = $params['productId'];
        $cntM = $params['cntM'];
        if(!$productId>0) {
            return array('success'=>false,'msg'=>'参数中的productId必须大于0');
        }

        __TRY();
        $_modelExample = FLEA::getSingleton('Model_Cangku_Madan');
        $_modelExample->clearLinks();
        $i = $cntM;
        /*$sql = "select m.id,m.productId,m.cntM,false as bool,r.rukuDate,m.millNo
                    from madan_db m
                    inner join madan_rc2madan y on m.id=y.madanId
                    inner join cangku_ruku2product c on c.id=y.rukuId
                    inner join cangku_ruku r on c.rukuId = r.id
                    where m.productId = '{$productId}' and m.status = 'active'
                    order by r.rukudate,m.millNo,m.cntM desc";*/
        $sql="select id,productId,millNo,cntM,'M' as unit,rollNo,baleNo,false as bool
             from madan_db
             where status='active' and productId='{$productId}'
             order by rukuDate,millNo,cntM desc";
        $rowset = $_modelExample->findBySql($sql);
       /* $str = "select count(*) as num,sum(m.cntM) as cnt
                    from madan_db m
                    inner join madan_rc2madan y on m.id=y.madanId
                    inner join cangku_ruku2product c on c.id=y.rukuId
                    inner join cangku_ruku r on c.rukuId = r.id
                    where m.productId = '{$productId}' and m.status = 'active'
                    order by r.rukudate,m.millNo,m.cntM desc";*/
        $str="select count(*) as num,sum(cntM) as cnt
             from madan_db
             where status='active' and productId='{$productId}'
             order by rukuDate,millNo,cntM desc";
        $rows = $_modelExample->findBySql($str);

        //算法优化，优先选择同一批次的码单进行配货，by liu
       /* $str2 = "select m.millNo,sum(m.cntM) as cnt
                    from madan_db m
                    inner join madan_rc2madan y on m.id=y.madanId
                    inner join cangku_ruku2product c on c.id=y.rukuId
                    inner join cangku_ruku r on c.rukuId = r.id
                    where m.productId = '{$productId}' and m.status = 'active'
                    group by m.millNo
                    order by r.rukudate,m.millNo,m.cntM desc";*/
        $str2="select millNo,sum(cntM) as cnt
             from madan_db
             where status='active' and productId='{$productId}'
             group by millNo
             order by rukuDate,millNo,cntM desc";
        $ret = $_modelExample->findBySql($str2);
        foreach ($rowset as $k1 => &$v1) {
            $ds[$v1['millNo']][] = $v1;
        }
        $tis->countData = $rows[0]['num'];
        //是否已找到方案，找到时标记为‘1’
        $flag = 0;

        //客户想要配货的M数大于该产品所有码单M数总和，则返回该产品所有码单
        if($cntM >= $rows[0]['cnt']){
            foreach ($ds as &$vv1) {
                foreach ($vv1 as &$vv2) {
                    $vv2['bool'] = true;
                }
            }
            $flag = 1;
        }

        //同一批号下所有码单正好可以满足时，返回该批号
        if (!$flag) {
            foreach ($ret as &$v5) {
                if ($v5['cnt'] == $cntM) {
                    foreach ($ds[$v5['millNo']] as &$v4) {
                        $v4['bool'] = true;
                    }
                    $flag = 1;
                    break;
                }
            }
        }

        //一个批号可以满足条件时，返回该批号
        if(!$flag){
            foreach ($ret as &$v5) {
                if ($v5['cnt'] > $cntM) {
                    if ($this->traceback($ds[$v5['millNo']],$i)) {
                        $flag = 1;
                        break;
                    }
                }
            }
        }

        //一个批号无法满足时
        if (!$flag) {
            $this->staSum = 0;
            //无法精确满足时，选择最接近的一种方案
            while(!$this->traceback($rowset,$i)){
                $i = $this->staSum;
            }
            unset($ds);
            foreach ($rowset as &$value) {
                $ds[$value['millNo']][] = $value;
            }
        }
        //优化结束

        $data = array();
        foreach ($ds as $k => &$v) {
            foreach ($v as &$v3) {
                if($v3['bool']){
                    $data[]=$v3;
                }
            }
        }
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }
        //释放全局变量
        unset($this->staSum);
        unset($this->countData);
        return array('success'=>true,'data'=>$data);
    }

    /**
     * ps ：选择自动配货的组合
     * 子集合算法
     * Time：2015/10/26 17:33:09
     * @author liuxin
     * @param array 所有符合条件的码单
     * @param array 所有符合条件的码单集的数量
     * @param array 配货总M数
     * @param c 最终要得到的数据结果
     * @return bool
    */
    function traceback(&$data,$c)
    {
        $p = 0;$sum = 0;$n = count($data);
        while ($p>=0) {
            if(!$data[$p]['bool']){
                $data[$p]['bool'] = true ;
                $sum += $data[$p]['cntM'] ;
                //从所有批号中取数据时，记录最相近的一个方案
                if($this->countData = $n){
                    if(abs($sum - $c) < abs($this->staSum - $c)||$this->staSum == 0){
                        $this->staSum = $sum;
                    }
                }
                if(abs($c - $sum) < 1) return true ;
                else if( $c < $sum){
                    $data[$p]['bool'] = false ;
                    $sum -=$data[$p]['cntM'] ;
                }
                $p++ ;
            }
            if($p>=$n){
                while($data[$p-1]['bool']){
                    $p-- ;
                    $data[$p]['bool'] = false ;
                    $sum -= $data[$p]['cntM'];
                    if($p<1) return false ;
                }
                while( !$data[$p-1]['bool']){
                    $p-- ;
                    if($p<1) return false ;
                }
                $sum -= $data[$p-1]['cntM'] ;
                $data[$p-1]['bool'] = false ;
            }
        }
        return false;
    }
    /**
     * 锁定或取消锁定状态：
     *保存订单和取消订单的时候走这边
     *这两种情况进销存中都还没有生成订单  所以只要改变下码单的状态就可以了
     * Time：2015/09/11 10:28:29
     * @author li
     * @param array
     * @return int 影响行数
    */
    function lock($params = array()){
        //参数中status只有两个选项
        $_param_status = array('active','lock');
        if(!in_array($params['status'], $_param_status)){
            return array('success'=>false,'msg'=>'参数中的status不能为空，且为选择项：active/lock');
        }

        if(!$params['madanIds']){
            return array('success'=>false,'msg'=>'参数中的madanIds不能为空,且为1,2,3形式');
        }

        __TRY();

        // model
        $_model = FLEA::getSingleton('Model_Cangku_Madan');
        $_model_peihuo = FLEA::getSingleton('Model_Peihuo_Peihuo');
        //byzhangyan添加，原因：不知道为什么svn上面显示我删除了这段代码，可是我并没有改，现在加上
        //2015-11-4 by jiang 码单为finish的不允许保存订单
        //如果是取消订单  则finish不需要改变状态
        if($params['status']=='lock'){
            $sqlma="select productId,rollNo from madan_db
                where status in ('finish','lock') and id in({$params['madanIds']})";
            $tempma = $_model->findBySql($sqlma);
            if ($tempma) {
                foreach ($tempma as $key => $value) {
                    $lock[$value['productId']][] = $value['rollNo'];
                }
                foreach ($lock as $key => $val) {
                    $lock[$key] = implode(',', $lock[$key]);
                }
                foreach ($lock as $key => $value) {
                    $result[] = "货品".$key."中卷号".$value."的商品已经被锁定！";
                }
                $reply = implode('<br>',$result);
                $reply = "卷号异常，以下花型可能已经被购买：(请重新配货)<br>".$reply;
                return array('success'=>false,'msg'=>$reply);exit;
            }
        }else{
            //查找码单是否已经被下订单并配货
            $sqlc="select count(x.id) as cnt
              from ph_peihuo x
              inner join ph_peihuo2madan y on y.phId=x.id
              where x.status!='dead' and y.madanId in ({$params['madanIds']})";
              // $sql="SELECT count(id) as cnt from madan_db where id in ($_madan_all_str) and status = 'lock'";

            $temp = $_model_peihuo->findBySql($sqlc);
            if($temp[0]['cnt']>0){
                return array('success'=>false,'msg'=>'订单中的有些码单已经被其他会员购买并支付，不能取消订单');exit;
            }

            $sqlma="select id from madan_db
                    where status <> 'finish' and id in({$params['madanIds']})";
            $tempma = $_model->findBySql($sqlma);
            $_affected_rows=mysql_affected_rows();
            if(!$tempma){
                return array('success'=>true,'data'=>array('affected_rows'=>$_affected_rows));exit;
            }else{
                $tempma=array_col_values($tempma,'id');
                $params['madanIds']=join(',',$tempma);
            }
        }

        //更新条件
        $_condition = " id in ({$params['madanIds']})";
         //2015-10-27 by jiang 如果选择了大货的配货单  码单的状态应该是不需要改变的
        //只改变配货单的状态
        if(!$params['peihuoCodes']){
            //更新影响行数
            $_model->updateField($_condition ,'status' ,$params['status']);

            //获取影响行数
            $_affected_rows = $_model->getDBO()->_affectedRows();
        }else{
            $peihuoCodes='';
            $_pcode=explode(',',$params['peihuoCodes']);
            foreach ($_pcode as $key => & $v) {
                $peihuoCodes[] = "'{$v}'";
            }
            $peihuoCode=join(',',$peihuoCodes);
            //判断是锁定码单还是取消锁定
            if($params['status']=='lock'){
                $sql="update ph_peihuo set status='dead' where peihuoCode in({$peihuoCode})";
            }else{
                $sql="update ph_peihuo set status='active' where peihuoCode in({$peihuoCode})";
            }
            $_model->execute($sql);
            //获取影响行数
            $_affected_rows=mysql_affected_rows();
        }

        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }

        return array('success'=>true,'data'=>array('affected_rows'=>$_affected_rows));
    }
    //下单结算时检查码单状态
    function checkMadan($params = array()){
        if(!$params['madanIds']){
            return array('success'=>false,'msg'=>'参数中的madanIds不能为空,且为1,2,3形式');
        }
        __TRY();
        // model
        $_model = FLEA::getSingleton('Model_Cangku_Madan');
        $sqlma="select productId,rollNo from madan_db
            where status in ('finish','lock') and id in({$params['madanIds']})";
        $tempma = $_model->findBySql($sqlma);
        if ($tempma) {
            foreach ($tempma as $key => $value) {
                $lock[$value['productId']][] = $value['rollNo'];
            }
            foreach ($lock as $key => $val) {
                $lock[$key] = implode(',', $lock[$key]);
            }
            foreach ($lock as $key => $value) {
                $result[] = "货品".$key."中卷号".$value."的商品已经被锁定！";
            }
            $reply = implode('<br>',$result);
            $reply = "卷号异常，以下花型可能已经被购买：(请重新配货)<br>".$reply;
            return array('success'=>false,'msg'=>$reply);exit;
        }
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }
        return array('success'=>true);
    }
    /**
     * 获取码单的状态
     * Time：2015/09/11 08:59:30
     * @author li
     * @param madanIds 1,2,3,4
     * @return array()
    */
    function status($params = array()){
        if(!$params['madanIds']){
            return array('success'=>false,'msg'=>'参数中的madanIds不能为空,且为1,2,3形式');
        }

        __TRY();

        // model
        $_model = FLEA::getSingleton('Model_Cangku_Madan');

        $sql="SELECT id as madanId,millNo,rollNo,qrcode,status,cntM,'M' as unit from madan_db
            where id in ({$params['madanIds']}) order by millNo,rollNo";

        $status_arr = $_model->findBySql($sql);

        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }

        return array('success'=>true,'data'=>array('status_arr'=>$status_arr));
    }

    /**
    * ps ：显示该客户的所有未下单并且为active的配货单
    * Time：2015/10/26 08:54:12
    * @author jiang
    */
    function getPeihuo($params = array()){
        //参数
        $productId = $params['productId'];
        $clientId = $params['clientId'];
        if(!$productId>0||!clientId>0) {
            return array('success'=>false,'msg'=>'参数中的productId和clientId必须大于0');
        }

        __TRY();

        //码单model
        $_model = FLEA::getSingleton('Model_Cangku_Madan');
        $_model->clearLinks();

        //注意，这里提供的数据只需要提供M数就可以了，ec中需要统一显示米数 *
        $sql="select
            z.cntM,
            count(x.id) as cntJuan,
            x.productId,
            group_concat(x.id) as madan_ids,
            z.peihuoCode
            from madan_db x
            inner join ph_peihuo2madan y on x.id=y.madanId
            inner join ph_peihuo z on z.id=y.phId
            where z.status_active='未下单' and z.status='active' and x.productId='{$productId}' and clientId='{$clientId}'
            group by z.id order by x.millNo,x.rollNo ";
        $rowset = $_model->findBySql($sql);
        foreach ($rowset as $key => & $v) {
            $v['cnt'] = round($v['cnt'],3);
        }
        // $rowset = $this->getData();
        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }

        return array('success'=>true,'data'=>$rowset);
    }

    /**
     * 检测配货单状态 是否可以下单支付
     * Time 2016年7月28日12:18:45
     * @author shen
     * @param params Array
     * @param params madan id
     * @return array()
    */
    function getDataState($params = array()){
        //参数
        $madanIds = $params['madanIds'];

        if(!$madanIds>0) {
            return array('success'=>false,'msg'=>'参数中的madanIds不能为空,且为1,2,3形式');
        }

        __TRY();

        $_model_peihuo = FLEA::getSingleton('Model_Peihuo_Peihuo');

        $sqlc="select count(x.id) as cnt
              from ph_peihuo x
              inner join ph_peihuo2madan y on y.phId=x.id
              where x.status!='dead' and y.madanId in ({$madanIds})";

        $temp = $_model_peihuo->findBySql($sqlc);
        if($temp[0]['cnt']>0){
            return array('success'=>false,'msg'=>'订单中的有些码单已经被其他会员购买并支付,请重新下单！');exit;
        }

        $ex = __CATCH();
        if (__IS_EXCEPTION($ex)) {
            return array('success'=>false,'msg'=>$ex->getMessage());
        }

        return array('success'=>true);
    }

}