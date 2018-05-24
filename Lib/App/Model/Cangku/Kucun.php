<?php
FLEA::loadClass('TMIS_TableDataGateway');
class Model_Cangku_Kucun extends TMIS_TableDataGateway {
    var $tableName = "cangku_kucun";
    var $primaryKey = "id";

    /**
     * 查找库存
     * Time：2015/10/14 20:30:09
     * @author li
    */
    function getKucun($_kuwei = '',$proCode){
        //查找库存,无论库位为样品还是现货库存显示都为现货
        if($_kuwei=='现货'){
            //所有现货码单信息
            $sqlRkmadan="SELECT DISTINCT m.madanId
                from cangku_ruku x
                inner join cangku_ruku2product y on x.id=y.rukuId
                inner join madan_rc2madan m on m.rukuId=y.id
                where 1 and x.cangkuName='现货仓库' and y.productId='{$proCode}' ";

            $sqlRkmadan.=" UNION SELECT DISTINCT m.madanId
                from cangku_chuku x
                inner join cangku_chuku2product y on x.id=y.chukuId
                inner join madan_rc2madan m on m.chukuId=y.id
                where 1 and x.cangkuName='现货仓库' and y.productId='{$proCode}'";
            // //码单中所有可用的码单
            $sql="SELECT sum(if(unit='Y',cnt*0.9144,cnt)) as cnt from madan_db where status='active' and productId='{$proCode}' and id in ({$sqlRkmadan})";
            // echo $sql;exit;

            //不锁定码单，显示实际库存
            // $sql="select sum(cntM) as cnt from cangku_kucun where productId='{$proCode}' and cangkuName='现货仓库'";

            // dump($sql);exit; where 
            $res = $this->findBySql($sql);

            return $res[0]['cnt']+0;

        }
        elseif($_kuwei=='样品'){

            //计算时间:样品库存动态变化
            // $_buyCnt = substr(time(),-4);
            // $_buyCnt = (int)$_buyCnt;
            // $ypKucun = 99999;
            //欧宝将样品库存还原成99999
          $xhRuku="SELECT DISTINCT m.madanId
                from cangku_ruku x
                inner join cangku_ruku2product y on x.id=y.rukuId
                inner join madan_rc2madan m on m.rukuId=y.id
                where 1 and x.cangkuName='现货仓库' and y.productId='{$proCode}' ";

            $xhRuku.=" UNION SELECT DISTINCT m.madanId
                from cangku_chuku x
                inner join cangku_chuku2product y on x.id=y.chukuId
                inner join madan_rc2madan m on m.chukuId=y.id
                where 1 and x.cangkuName='现货仓库' and y.productId='{$proCode}'";
            // //码单中所有可用的码单
            $sql="SELECT sum(if(unit='Y',cnt*0.9144,cnt)) as cnt from madan_db where status='active' and productId='{$proCode}' and id in ({$xhRuku})";
            $rowset = $this->findBySql($sql);

            // //码单中所有可用的码单
            $sql2="SELECT sum(if(unit='Y',cnt*0.9144,cnt)) as cnt from cangku_kucun where 1 and productId='{$proCode}' and cangkuName='样品仓库'";
            $rowset2 = $this->findBySql($sql2);
            $ypKucun=$rowset[0]['cnt']+$rowset2[0]['cnt'];
            return $ypKucun;
        }

        return 0;
    }


     /**
     * 查找库存
     * Time：2016年7月27日13:32:47
     * @author shen
    */
    function getproductsKucun($proCode){
        $str="SELECT DISTINCT m.madanId
                from cangku_ruku x
                inner join cangku_ruku2product y on x.id=y.rukuId
                inner join madan_rc2madan m on m.rukuId=y.id
                where 1  and y.productId in({$proCode['product_id']}) and x.cangkuName='现货仓库'" ;

        $str.=" UNION SELECT DISTINCT m.madanId
            from cangku_chuku x
            inner join cangku_chuku2product y on x.id=y.chukuId
            inner join madan_rc2madan m on m.chukuId=y.id
            where 1  and y.productId in({$proCode['product_id']})";
        // //码单中所有可用的码单
        $sql="SELECT sum(if(unit='Y',cnt*0.9144,cnt)) as cnt,productId from madan_db where status='active' and productId in({$proCode['product_id']}) and id in ({$str})  group by productId";
        // echo $sql;exit;

        //不锁定码单，显示实际库存
        // $sql="select sum(cntM) as cnt from cangku_kucun where productId='{$proCode}' and cangkuName='现货仓库'";

        $res = $this->findBySql($sql);

        return $res;
    }
}
?>