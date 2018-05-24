<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Report');
class Controller_Cangku_Xianhuo_Report extends Controller_Cangku_Report{
    /**
     * 添加登记界面的权限
     * @var array
    */
    var $_check;
    function __construct() {
        parent::__construct();
        $this->_cangkuName = __CANGKU_1;
        $this->_mold = 'Xianhuo';
        $this->_check='3-1-12';
    }

    /**
     * @desc ：报表数据的后处理事件
     * @author jeff #TM_TH_DTTM
     * @param 参数类型
     * @return 返回值类型
    */
    function afterGetRowset(&$rows,$flag) {
        foreach($rows as & $v) {
            // dump($v);exit;
            //从caigou_order2product表中获取加权平均值，这个可能对数据库效率有影响，后期可能会改为数据库缓存，或者redis|memcached缓存
            $sql = "select sum(cntM*danjia)/sum(cntM) as danjia
            from caigou_order2product
            where productId='{$v['productId']}'";
            $temp = $this->_modelExample->findBySql($sql);
            $v['danjia']=round($temp[0]['danjia'],2);
            if ($flag) {
                $v['moneyKucun']=round($v['danjia']*$v['cnt'],2);
            }else{
                $v['moneyKucun']=round($v['danjia']*$v['cntKucun'],2);
            }
        }
        // dump($rows[0]);exit;
    }

    /**
     * @desc ：字段的后处理时间，用来增加扩展字段
     * @author jeff #TM_TH_DTTM
     * @param 参数类型
     * @return 返回值类型
    */
    function afterGetFields(&$f) {
        $newFields = array();
        //在数量后面增加单价，金额字段
        foreach($f as $k=>& $v) {
            $newFields[$k] = $v;
            if($k!='cntKucun') {
                continue;
            }
            $newFields['danjia']='单价';
            $newFields['moneyKucun']='金额';
        }
        $f = $newFields;
    }
}


?>