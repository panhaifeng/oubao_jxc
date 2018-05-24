<?php
/*********************************************************************\
*  Copyright (c) 2007-2013, TH. All Rights Reserved.
*  Author :li
*  FName  :Ruku.php
*  Time   :2015/09/10 13:40:27
*  Remark :仓库入库父类：实现仓库的基础通用功能
\*********************************************************************/
FLEA::loadClass('TMIS_Controller');
class Controller_Cangku_Report extends TMIS_Controller {

    /**
     * 仓库主表的model实例化
     * @var object
    */
    var $_modelExample;

    /**
     * 仓库子表的model实例化
     * @var object
    */
    var $_subModel;

    /**
     * 仓库名字
     * 仓库大类：现货仓库/样品仓库/大货仓库
     * @var string
    */
    var $_cangkuName;

    /**
     * 出入库类型
     * 如：采购入库/加工入库/其他入库等
     * @var string
    */
    var $_kind;

    /**
     * 出入库单号编码前缀
     * @var string
    */
    var $_head;

    /**
     * 出入库单号编码前缀
     * @var string|array 都支持
    */
    var $sonTpl;

    /**
     * 查询界面为了区分采购入库是那个仓库入库的
     * @var string
    */
    var $_mold;
     /**
     * 权限设置
     * @var string
    */
    var $_check;
    function __construct() {
        //model
        $this->_modelExample = &FLEA::getSingleton('Model_Cangku_Ruku');
        $this->_subModel = &FLEA::getSingleton('Model_Cangku_Ruku2Product');

    }

    /**
     * ps ：库存
     * Time：2015/09/30 14:28:40
     * @author jiang
    */
    function actionReportKucun(){
        $this->authCheck($this->_check);
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            "dateFrom" => date('Y-m-01'),
            "dateTo" => date('Y-m-d'),
            'kuweiId_name'=>'',
            'kuweiName'=>$this->_cangkuName,
            'proCode'=>'',
        ));
        // dump($this->_subModel->getSfcSql());exit;
        if($arr['kuweiId_name']>0){
            $strCon.=" and kuweiId='{$arr['kuweiId_name']}'";
        }
        if($arr['proCode']!=''){
            $strCon.=" and productId like '%{$arr['proCode']}%'";
        }
        $strCon.=" and cangkuName='{$this->_cangkuName}' and isBaofei = 1";
        $strGroup="cangkuName,kuweiId,productId";
        $sqlUnion="select {$strGroup},sum(cnt) as cntInit,sum(cntM) as cntMInit,sum(cntJian) as cntJianInit,0 as cntRuku,0 as cntMRuku,0 as cntJianRuku,0 as cntChuku,0 as cntMChuku,0 as cntJianChuku,sum(money) as moneyInit,0 as moneyRuku,0 as moneyChuku
        from `cangku_kucun` where dateFasheng<'{$arr['dateFrom']}'
         {$strCon} group by {$strGroup}
        union
        select {$strGroup},
        0 as cntInit,0 as cntMInit,0 as cntJianInit,sum(cnt) as cntRuku,sum(cntM) as cntMRuku,sum(cntJian) as cntJianRuku,0 as cntChuku,0 as cntMChuku,0 as cntJianChuku,0 as moneyInit,sum(money) as moneyRuku,0 as moneyChuku
        from `cangku_kucun` where
        dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
        and rukuId>0  {$strCon} group by {$strGroup}
        union
        select {$strGroup},
        0 as cntInit,0 as cntMInit,0 as cntJianInit,0 as cntRuku,0 as cntMRuku,0 as cntJianRuku,sum(cnt*-1) as cntChuku,sum(cntM*-1) as cntMChuku,sum(cntJian*-1) as cntJianChuku,0 as moneyInit,0 as moneyRuku,sum(money*-1) as moneyChuku
        from `cangku_kucun` where
        dateFasheng>='{$arr['dateFrom']}' and dateFasheng<='{$arr['dateTo']}'
        and chukuId>0  {$strCon} group by {$strGroup}";
        $sql="select
        {$strGroup},sum(cntInit) as cntInit,sum(cntMInit) as cntMInit,sum(cntJianInit) as cntJianInit,sum(cntRuku) as cntRuku,sum(cntMRuku) as cntMRuku,sum(cntJianRuku) as cntJianRuku,sum(cntChuku) as cntChuku,sum(cntMChuku) as cntMChuku,sum(cntJianChuku) as cntJianChuku,sum(moneyInit) as moneyInit,sum(moneyRuku) as moneyRuku,sum(moneyChuku) as moneyChuku
        from ({$sqlUnion}) as x
        group by {$strGroup}
        having  sum(cntInit)<>0 or sum(cntMInit)<>0 or sum(cntJianInit)<>0 or sum(cntRuku)<>0 or sum(cntMRuku)<>0 or sum(cntJianRuku)<>0 or sum(cntChuku)<>0 or sum(cntMChuku)<>0 or sum(cntJianChuku)<>0 or sum(moneyInit)<>0 or sum(moneyRuku)<>0 or sum(moneyChuku)<>0
        order by kuweiId,productId";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        //如果进行库存导出功能，则重新查询一个数据集再塞进导出功能的值中就可以实现分页功能下的所有数据导
        // by jiangxu 2016年5月25日 14:25:08
        if($_GET['export']==1) $rowsets = $this->_modelExample->findBySql($sql);
        // if($_GET['export']==1) $rowsets = $pager->findAll();
        foreach ($rowset as & $v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $temp = $this->_modelExample->findBySql($sql);
            $v['proCode'] = $temp[0]['proCode'];
            $v['proName'] = $temp[0]['proName'];
            $v['chengfen'] = $temp[0]['chengfen'];
            $v['jwmi'] = $temp[0]['jingmi'].'*'.$temp[0]['weimi'];
            $v['menfu'] = $temp[0]['menfu'];

            $sql = "select * from jichu_kuwei where id='{$v['kuweiId']}'";
            $temp = $this->_modelExample->findBySql($sql);
            $v['kuwei'] = $temp[0]['kuweiName'];

            $v['cntInit']=round($v['cntMInit'],2);
            $v['cntRuku']=round($v['cntMRuku'],2);
            $v['cntChuku']=round( $v['cntMChuku'],2);
            $v['cntKucun'] = round($v['cntInit'] + $v['cntRuku'] - $v['cntChuku'], 2);

            $v['cntJianInit']=round($v['cntJianInit'],2);
            $v['cntJianRuku']=round($v['cntJianRuku'],2);
            $v['cntJianChuku']=round( $v['cntJianChuku'],2);
            $v['cntJianKucun'] = round($v['cntJianInit'] + $v['cntJianRuku'] - $v['cntJianChuku'], 2);
        }
        foreach ($rowsets as & $v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $temp = $this->_modelExample->findBySql($sql);
            $v['proCode'] = $temp[0]['proCode'];
            $v['proName'] = $temp[0]['proName'];
            $v['chengfen'] = $temp[0]['chengfen'];
            $v['jwmi'] = $temp[0]['jingmi'].'*'.$temp[0]['weimi'];
            $v['menfu'] = $temp[0]['menfu'];

            $sql = "select * from jichu_kuwei where id='{$v['kuweiId']}'";
            $temp = $this->_modelExample->findBySql($sql);
            $v['kuwei'] = $temp[0]['kuweiName'];

            $v['cntInit']=round($v['cntMInit'],2);
            $v['cntRuku']=round($v['cntMRuku'],2);
            $v['cntChuku']=round( $v['cntMChuku'],2);
            $v['cntKucun'] = round($v['cntInit'] + $v['cntRuku'] - $v['cntChuku'], 2);

            $v['cntJianInit']=round($v['cntJianInit'],2);
            $v['cntJianRuku']=round($v['cntJianRuku'],2);
            $v['cntJianChuku']=round( $v['cntJianChuku'],2);
            $v['cntJianKucun'] = round($v['cntJianInit'] + $v['cntJianRuku'] - $v['cntJianChuku'], 2);
        }



        $hj=$this->getHeji($rowset,array('cntInit','cntRuku','cntChuku','cntKucun','cntJianInit','cntJianRuku','cntJianChuku','cntJianKucun'),'kuwei');
        $hj2=$this->getHeji($rowsets,array('cntInit','cntRuku','cntChuku','cntKucun','cntJianInit','cntJianRuku','cntJianChuku','cntJianKucun'),'kuwei');
        //出入库数量形成可弹出明细的链接
        foreach($rowset as & $v) {
            // $cName = str_replace('chuku', 'ruku', strtolower($_GET['controller']));
            $v['cntRuku'] = "<a href='".$this->_url('PopupRk',array(
                'dateFrom'=>$arr['dateFrom'],
                'dateTo'=>$arr['dateTo'],
                'kuweiId'=>$v['kuweiId'],
                'productId'=>$v['productId'],
            ))."' target='_blank'>{$v['cntRuku']}</a>";

            $v['cntChuku'] = "<a href='".$this->_url('PopupCk',array(
                'dateFrom'=>$arr['dateFrom'],
                'dateTo'=>$arr['dateTo'],
                'kuweiId'=>$v['kuweiId'],
                'productId'=>$v['productId'],
            ))."' target='_blank'>{$v['cntChuku']}</a>";
        }

        //增加一个后处理事件,方便进行扩展 2016-12-28 by jeff
        $this->afterGetRowset($rowset);
        $this->afterGetRowset($rowsets);

        $rowset[]=$hj;
        $rowsets[]=$hj2;

        $arrFieldInfo = array(
            'kuwei' => '库位',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "chengfen" => '成分',
            "jwmi" => '经纬密',
            "menfu" => '门幅',
            'cntInit' => '期初',
            'cntRuku' => '本期入库',
            'cntChuku' => '本期出库',
            'cntKucun' => '余存',
            'cntJianInit' => '期初卷数',
            'cntJianRuku' => '入库卷数',
            'cntJianChuku' => '出库卷数',
            'cntJianKucun' => '余存卷数',
            );
        $this->afterGetFields($arrFieldInfo);
        //去除导出功能的A标签 2016年3月31日10:13:06 by duzhuang and qianyi
        if($_GET['export']==1){
            foreach ($rowsets as & $value) {
                foreach ($value as & $k) {
                    $k=strip_tags($k);
                }
            }
        }
        $other_url="<button type='button' class='btn btn-info btn-sm' id='kucun'>导出码单剩余库存</button>";
        $smarty = &$this->_getView();
        $smarty->assign('title', '收发存报表');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->assign('other_url', $other_url);
        $smarty->assign('sonTpl', "Cangku/Xianhuo/Report.tpl");
        //添加导出功能 2016年3月30日16:44:00 by duzhuang and qianyi
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
        if($_GET['export']==1){
            $smarty->assign('arr_field_value', $rowsets);
            $this->_exportList(array('title'=>$title),$smarty);
        }
        $smarty->assign('arr_field_value', $rowset);
        $smarty->display('TblList.tpl');

    }

    //取得报表数据后的一个后处理事件，在子类中进行定义,方便子类进行扩展,2016-12-28 by jeff
    function afterGetRowset(&$rows) {}

    //字段处理的后处理事件
    function afterGetFields(&$f) {}

    //导出库存
    function actionckReport(){
        $sql = "select x.*
        from madan_db x where x.status<>'finish'";
        $rowset = $this->_modelExample->findBySql($sql);
        $this->afterGetRowset($rowset,$flag=1);
        $smarty = &$this->_getView();
        // dump($rowset);exit;
        // 左侧信息
        $arr_field_info = array(
            "productId" => '花型六位号',
            "millNo" => '批次号',
            "cnt" => '数量',
            "cntM" => '米数',
            "unit" => '单位',
            "rollNo" => '卷号',
            "baleNo" => '包号',
            "qrcode" => '条码',
            "danjia" => '单价',
            "moneyKucun" => '金额',
            "kuqu" => '库区',
            "rukuDate" => '入库日期',
        );
        $smarty->assign('title', '入库列表');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        // $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)));
        $this->_exportList(array('title'=>$title),$smarty);
        $smarty->display('TblList.tpl');

    }
    /**
     * ps ：显示入库
     * Time：2015/09/30 15:55:10
     * @author jiang
    */
    function actionPopupRk(){
        FLEA::loadClass("TMIS_Pager");
        $sql="select x.*,y.id as rukuId,y.rukuDate,y.rukuCode,y.kind,k.kuweiName,c.orderCode,
                p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,p.menfu,p.kezhong,s.compName as jiagonghu
                from cangku_ruku2product x
                left join cangku_ruku y on x.rukuId=y.id
                left join jichu_kuwei k on k.id=y.kuweiId
                left join caigou_order c on c.id=y.caigouId
                left join jichu_product p on p.proCode=x.productId
                left join jichu_supplier s on s.id=y.jiagonghuId
                where 1 and cangkuName='{$this->_cangkuName}'";
        $sql.=" and x.productId='{$_GET['productId']}' and kuweiId='{$_GET['kuweiId']}'
                and y.rukuDate<='{$_GET['dateTo']}' and rukuDate>='{$_GET['dateFrom']}'";
        $sql.="order by rukuDate desc,rukuCode desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($this->_mold);die;
        foreach($rowset as & $v) {
            $v['rukuDate']=$v['rukuDate']=='0000-00-00'?'':$v['rukuDate'];
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];

        }
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'rukuCode');
        $hj['cnt']=$hj['cntM'].' M';
        $rowset[]=$hj;
        $smarty = &$this->_getView();
        // 左侧信息
        $arr_field_info = array(
            "rukuCode" => '单号',
            "rukuDate" => '入库日期',
            "kuweiName" => '库位',
            "kind" => '类型',
            "orderCode" => '采购合同',
            "jiagonghu" => '加工户',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "chengfen" => '成分',
            "jwmi" => '经纬密',
            "menfu" => '门幅',
            "cnt" => '数量',
            "cntJian" => '卷数',
            "unit" => '单位'
        );

        $smarty->assign('title', '入库列表');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        // $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }

   /**
    * ps ：显示出库
    * Time：2015/10/08 09:04:36
    * @author jiang
   */
    function actionPopupCk(){
        FLEA::loadClass("TMIS_Pager");
        $sql="select x.*,y.id as chukuId,y.chukuDate,y.chukuCode,y.kind,k.kuweiName,c.orderCode,
                p.proCode,p.proName,p.chengfen,p.shazhi,p.jingmi,p.weimi,p.menfu,p.kezhong,s.compName as jiagonghu
                from cangku_chuku2product x
                left join cangku_chuku y on x.chukuId=y.id
                left join jichu_kuwei k on k.id=y.kuweiId
                left join trade_order c on c.id=x.orderId
                left join jichu_product p on p.proCode=x.productId
                left join jichu_supplier s on s.id=y.jiagonghuId
                where 1 and cangkuName='{$this->_cangkuName}'";
        $sql.=" and x.productId='{$_GET['productId']}' and kuweiId='{$_GET['kuweiId']}'
                and y.chukuDate<='{$_GET['dateTo']}' and chukuDate>='{$_GET['dateFrom']}'";
        $sql.="order by chukuDate desc,chukuCode desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($this->_mold);die;
        foreach($rowset as & $v) {
            $v['chukuDate']=$v['chukuDate']=='0000-00-00'?'':$v['chukuDate'];
            $v['jwmi']=$v['jingmi'].'*'.$v['weimi'];

        }
        $hj=$this->getHeji($rowset,array('cntM','cntJian'),'chukuCode');
        $hj['cntM']=round($hj['cntM'],2);
        $hj['cnt']=$hj['cntM'].' M';
        $rowset[]=$hj;
        $smarty = &$this->_getView();
        // 左侧信息
        $arr_field_info = array(
            "chukuCode" => '单号',
            "chukuDate" => '出库库日期',
            "kuweiName" => '库位',
            "kind" => '类型',
            "orderCode" => '订单编号',
            "jiagonghu" => '加工户',
            "proCode" => '花型六位号',
            "proName" => '品名',
            "chengfen" => '成分',
            "jwmi" => '经纬密',
            "menfu" => '门幅',
            "cnt" => '数量',
            "cntJian" => '卷数',
            "unit" => '单位'
        );

        $smarty->assign('title', '出库列表');
        $smarty->assign('arr_field_info', $arr_field_info);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        // $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->display('TblList.tpl');
    }

    function actionKuquKucun(){
        $this->authCheck($this->_check);
        FLEA::loadClass("TMIS_Pager");
        $arr = &TMIS_Pager::getParamArray(array(
            'kuquName'=>'',
        ));

        $sql="SELECT
            sum(y.cnt)as cntAll,sum(y.cntM)as cntMAll,count(y.rollNo)as rollNo,
            y.kuqu,w.kuquName
            from madan_rc2madan x
            left join madan_db y on x.madanId=y.id
            left join jichu_kuqu w on w.id=y.kuquId
            where y.status<>'finish' ";
        if($arr['kuquName']!=''){
            $sql.=" and y.kuqu='{$arr['kuquName']}'";
        }
        $sql.="group by w.kuquName";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();

        foreach ($rowset as & $v) {

        }
        $hj=$this->getHeji($rowset,array('cntAll','cntMAll','rollNo'),'kuquName');

        $rowset[]=$hj;
        $arrFieldInfo = array(
            'kuquName' => '库区',
            'cntAll' => '数量',
            'cntMAll' => '米数',
            'rollNo' => '卷数',
            // 'cntJianInit' => '期初卷数',
            // 'cntJianRuku' => '入库卷数',
            // 'cntJianChuku' => '出库卷数',
            // 'cntJianKucun' => '余存卷数',
            );
        $smarty = &$this->_getView();
        $smarty->assign('title', '库存报表');
        // $smarty->assign('pk', $this->_modelDefault->primaryKey);
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action'], $arr)));
        $smarty->assign('arr_field_value', $rowset);
        //添加导出功能 2016年3月30日16:44:00 by duzhuang and qianyi
        $smarty->assign('fn_export',$this->_url($_GET['action'],array('export'=>1)+$arr));
            if($_GET['export']==1){
                $this->_exportList(array('title'=>$title),$smarty);
            }
        $smarty->display('TblList.tpl');
    }
}

?>