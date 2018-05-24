<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :BackPlan.php
*  Time   :2015/10/08 13:30:33
*  Remark :退换货申请
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_BackPlan extends TMIS_Controller {

    /**
     * 订单类型
     * @var string
    */
    var $_type_order;

    /**
     * 仓库名字
     * 仓库大类：现货仓库/样品仓库/大货仓库
     * @var string
    */
    var $_cangkuName;

    function __construct() {

        $this->_check='3-1-10';
        //model
        $this->_modelExample = FLEA::getSingleton('Model_Cangku_BackPlan');
    }

    /**
     * 退换货任务清单
     * Time：2015/10/08 10:34:56
     * @author li
    */
    function actionBackPlan(){
        $this->authCheck($this->_check);
        FLEA::loadClass('TMIS_Pager');
        $where = TMIS_Pager::getParamArray(array(
            'orderCode'=>'',
            'clientId'=>'',
            'proName' => '',
            'back_ststus' => '',
        ));

        //修改sql语句，查询退换货已发出和已接受的数量，2015-10-19，by liuxin
        $sql="select 
            x.* ,
            z.cntM,
            p.proCode,
            p.proName,
            p.chengfen,
            p.shazhi,
            p.jingmi,
            p.weimi,
            p.menfu,
            p.color,
            p.kezhong,
            p.zuzhi,
            p.zhengli,
            p.wuliaoKind,
            c.compName,
            e.isChuRuku
            from cangku_back_plan x
            inner join trade_order y on x.orderId = y.id
            inner join jichu_product p on p.proCode = x.productId
            inner join trade_order2product z on x.ord2proId=z.id
            left join jichu_client c on c.member_id=y.clientId
            left join cangku_chuku2product d on d.backId=x.id
            left join cangku_chuku e on e.id=d.chukuId
            where 1";
        //订单类型
        $this->_type_order!='' && $sql.=" and z.kind='{$this->_type_order}'";

        if($where['orderCode']!=''){
            $sql.=" and x.orderCode like '%{$where['orderCode']}%'";
        }
        if($where['clientId']!=''){
            $sql.=" and y.clientId = '{$where['clientId']}'";
        }
        if($where['proName']!=''){
            $sql.=" and x.name like '%{$where['proName']}%'";
        }
        if($where['back_ststus']!='all'){
            $sql.=" and x.status = '{$where['back_ststus']}'";
        }
        $sql.=" group by x.id order by x.add_time asc";

        $pager = new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($rowset);exit;
        foreach ($rowset as $key => &$v) {
            $v['cntM'] = round($v['cntM'],3);
            $v['num'] = round($v['num'],3);

            //操作
            //退库入库
            $_rk_edit = "<a href='".$this->_url('RukuAdd',array(
                'planId'=>$v['id']
                ))."' title='退换入库'><span class='glyphicon glyphicon-log-in'></span></a>";

            //退换出库
            if($v['type']==1){
                $_ck_edit="<span class='glyphicon glyphicon-log-out' title='退货不需要发货'></span>";
            }else{
                $_ck_edit = "<a href='".$this->_url('ChukuAdd',array(
                    'planId'=>$v['id']
                ))."' title='退换出库'><span class='glyphicon glyphicon-log-out'></span></a>";
            }

            if($v['status']!=''){
                $_rk_edit="<span class='glyphicon glyphicon-log-in' title='已完成或拒绝不能入库'></span>";
                $_ck_edit="<span class='glyphicon glyphicon-log-out' title='已完成或拒绝不需要发货'></span>";
            }

            //是否完成
            $_isOver_edit = "<a href='".$this->_url('IsOver',array(
                'planId'=>$v['id'],
                'status'=>'完成',
            ))."' title='完成' onclick='return confirm(\"您确认要完成吗?\")'><span class='glyphicon glyphicon-ok'></span></a>";

            //拒绝
            $_jujue_edit = "<a href='".$this->_url('IsOver',array(
                'planId'=>$v['id'],
                'status'=>'拒绝',
            ))."' title='拒绝' onclick='return confirm(\"您确认要拒绝吗?\")'><span class='glyphicon glyphicon-remove'></span></a>";

            //状态是完成或者拒绝的单子，应不可以进行完成或拒绝操作，2015-10-16，by liuxin
            if($v['status']!=''){
                $_isOver_edit="<span class='glyphicon glyphicon-ok' title='已完成或拒绝不能完成'></span>";
                $_jujue_edit="<span class='glyphicon glyphicon-remove' title='已完成或拒绝不能拒绝'></span>";
            }

            $v['_edit']=$_rk_edit."&nbsp;&nbsp;".$_ck_edit."&nbsp;&nbsp;".$_isOver_edit."&nbsp;&nbsp;".$_jujue_edit;

            //退换货
            $v['type'] = $v['type']==1 ? '退' : '换';
            //已接受 
            $sql="SELECT sum(x.cntM)as cnt,y.isChuRuku 
                from cangku_chuku2product x 
                left join cangku_chuku y on y.id=x.chukuId
                where backId={$v['id']} and y.isChuRuku=1";
            $ret=$this->_modelExample->findBySql($sql);
            //已发出
            $str="SELECT sum(x.cntM)as cnt,y.isChuRuku 
                from cangku_chuku2product x 
                left join cangku_chuku y on y.id=x.chukuId
                where backId={$v['id']} and y.isChuRuku=0";
            $ret2=$this->_modelExample->findBySql($str);
            // dump($ret);exit;
            $v['num_rk']=round($ret2[0]['cnt']*-1,2);
            $v['num_ck']=$ret[0]['cnt'];
        }
        // dump($rowset);exit;
        $smarty = & $this->_getView(); 
        $arrFieldInfo = array(
            "_edit" => '操作',
            'add_time'=>array('width'=>'130','text'=>'申请时间'),
            'return_id'=>array('width'=>'90','text'=>'流水号'),
            'status'=>array('width'=>'50','text'=>'状态'),
            'orderCode'=>array('width'=>'130','text'=>'订单号'),
            "cntM" => array('width'=>'80','text'=>'下单数量(M)'),
            "num" => array('width'=>'80','text'=>'退/换数量(M)'),
            "type" => array('width'=>'50','text'=>'退/换'),
            "title" => array('width'=>'100','text'=>'原因'),
            "num_rk" => array('width'=>'80','text'=>'已接收(M)'),
            "num_ck" => array('width'=>'80','text'=>'已发出(M)'),
            
            "compName" => array('width'=>'80','text'=>'客户'),
            "proCode" => array('width'=>'80','text'=>'编号'),
            "proName" => array('width'=>'80','text'=>'商品名称'),
            "menfu" => array('width'=>'80','text'=>'门幅'),
            "kezhong" => array('width'=>'80','text'=>'克重'),
            "zuzhi" => array('width'=>'80','text'=>'组织'),
            "zhengli" => array('width'=>'80','text'=>'整理方式'),
            "wuliaoKind" => array('width'=>'80','text'=>'物料'),
        ); 
        $smarty->assign('title', '退换货任务查询');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $where);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $where)));
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$where));
            if($_GET['export']==1){
                foreach ($rowset as $key => & $value) {
                    foreach ($value as $key => & $v) {     
                        $v = strip_tags($v);
                    }
                    unset($value['_edit']);
                }
                unset($arr_field_info['_edit']);
                $this->_exportList(array('title'=>$title),$smarty);
            }
        $smarty->display('TblList.tpl');
    }


    /**
     * 是否完成
     * Time：2015/10/08 14:36:26
     * @author li
    */
    function actionIsOver(){
        $id = (int)$_GET['planId'];
        $_status = $_GET['status'];

        $this->_modelExample->clearLinks();
        $info = $this->_modelExample->find($id);
        // dump($res);exit;

        $_data = array(
            'id'=>$id,
            'status'=>$_status,
        );
        $this->_modelExample->update($_data);

        //同步
        

        //查找该流水号的所有退换货记录
        $sql="select count(*) as cnt,status,orderCode from cangku_back_plan where return_id='{$info['return_id']}' group by status";
        $res = $this->_modelExample->findBySql($sql);
        $_status_cnt = array();
        foreach ($res as $key => & $v) {
            $_status_cnt[$v['status']] = $v['cnt'];
        }

        //相同流水号状态全部设置并且存在完成的，同步完成状态
        //如果全部标记为拒绝，则同步状态为拒绝

        if($_status_cnt['完成']>0 && $_status_cnt['']==0){
            $_status=4;
        }elseif($_status_cnt['完成']==0 && $_status_cnt['']==0){
            $_status=5;
        }
        //存在需要同步的状态
        if($_status>0){
            //退货状态
            $ship_status = 3;//部分完成
            if($_status_cnt['完成'] > 0 && ($_status_cnt['拒绝']+$_status_cnt[''] == 0)){
                $ship_status = 4;//全部完成
            }
            $obj_api = FLEA::getSingleton('Api_Request');
            $r = $obj_api->callApi(array(
                'method'=>'apioubao.erp.response.do_finish_redelivery',
                'params'=>array(
                    'return_id'=>$info['return_id'],
                    'status'=>$_status,
                    'ship_status'=>$ship_status,
                    'order_id'=>$res[0]['orderCode'],

                )
            ));
            $ret = json_decode($r,true);
            if($ret['data']['success']==false){
                js_alert(null,'window.parent.showMsg("操作完成，同步状态到EC失败")',$this->_url('BackPlan'));
            }

            //同步进销存订单状态
            $sql="update trade_order set ship_status = '{$ship_status}' where orderCode='{$res[0]['orderCode']}'";
            $this->_modelExample->execute($sql);
        }        
        
        js_alert(null,'window.parent.showMsg("操作完成")',$this->_url('BackPlan'));
    }

    
}
?>