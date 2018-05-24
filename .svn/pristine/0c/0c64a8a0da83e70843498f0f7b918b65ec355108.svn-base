<?php
FLEA::loadClass('TMIS_Controller');
class Controller_Peihuo_Peihuo extends TMIS_Controller {
    var $_modelExample;
    function __construct() {
        $this->_modelExample = & FLEA::getSingleton('Model_Peihuo_Peihuo');

    }

    /**
     * 测试配货单配货
     * Time：2015/09/17 11:30:01
     * @author li
    */
    function actionTest(){
        $params = '{"orderCode":"150922201185947","Order":{"orderCode":"150922201185947","money":"310.000","money_order":"310.000","pmt_money":"0","pay_status":"0","ship_status":"0","is_delivery":"Y","orderTime":"2015-09-22 20:11:41","payment":"deposit","clientId":"3","currency":"CNY","shipping":"\u5feb\u9012","ship_name":"\u54c8\u54c8\u54c8","ship_addr":"\u6c5f\u82cf\u5e38\u5dde\u5e02\u6b66\u8fdb\u533a\u79d1\u6559\u57ce","ship_zip":"","ship_tel":"","ship_mobile":"15295168096","ship_area":"mainland:\u6c5f\u82cf\/\u5e38\u5dde\u5e02\/\u6b66\u8fdb\u533a:1663","is_tax":"false","tax_type":"false","cost_tax":"0.000","memo":"","cur_rate":"1.0000","status":"active","cost_freight":"10.000"},"Pro":[{"productId":"071001","bn":"071001-XH","danjia":"100.000","danjia_p":"100.000","cnt":"3","unit":"M","kind":"\u73b0\u8d27","spec_info":"a:1:{s:12:\"product_attr\";a:3:{i:1;a:2:{s:5:\"label\";s:6:\"\u989c\u8272\";s:5:\"value\";s:6:\"\u9ed1\u8272\";}i:5;a:2:{s:5:\"label\";s:13:\"\u73b0\u8d27|\u6837\u54c1\";s:5:\"value\";s:6:\"\u73b0\u8d27\";}i:6;a:2:{s:5:\"label\";s:6:\"\u683c\u578b\";s:5:\"value\";s:6:\"\u6761\u5e03\";}}}","madan":"149","item_type":"product","money":"300.000"}],"token":"aa"}';
        $_params = json_decode($params,true);
        dump($_params);exit;
        $Products = $_params['Pro'];
        foreach ($Products as $key => & $v) {
            $v['madan'] = trim($v['madan']);
            $_madan = explode(',',$v['madan']);
            $_madan_all[]=$v['madan'];
            $madan = array();
            foreach ($_madan as & $m) {
                if(!$m)continue;
                $madan[]=array('madanId'=>$m);
            }
            $_peihuo = array(
                'status_active'=>'已下单',
                'peihuoDate'=>date('Y-m-d'),
                'cntM'=>32,
                'productId'=>'130082',
                'Peihuo'=>$madan
            );

            //配货单编号
            if($_peihuo['id']==0){
                $_peihuo['peihuoCode'] = $this->_modelExample->getNewCode();
            }
            
            // $_id = $this->_modelExample->save($_peihuo);
        }
        $_madan_all = array_filter($_madan_all);
        dump($_madan_all);
        $_madan_all_str = join(',',$_madan_all);
        dump($_madan_all_str);
    }

    /**
     * ps ：配货单显示
     * Time：2015/09/17 19:15:43
     * @author jiang
    */
    function actionView(){
        $madan=& FLEA::getSingleton('Model_Cangku_Madan');
        $row=$this->_modelExample->find(array('id'=>$_GET['peihuoId']));
        
        $sql="SELECT * from madan_db x 
            left join ph_peihuo2madan y on x.id=y.madanId
            where y.phId in({$_GET['peihuoId']})";
        $rowset = $this->_modelExample->findBysql($sql);
        // dump($rowset);die;
        foreach ($rowset as $k=> & $v) {
            $ma=$madan->find(array('id'=>$v['madanId']));
            $ma['cnt']=round($ma['cnt']);
            $ma['cntM']=round($ma['cntM'],2);
            $ma['Product']['menfu']=round($ma['Product']['menfu']);
            $ma['Product']['kezhong']=round($ma['Product']['kezhong']);
            $ma['hang']=$k;
            $arr[$ma['millNo']][]=$ma;
        }
        $zJianshu = $this->getHeji($ma,array('cnt'));
        $smarty = & $this->_getView();
        $smarty->assign('row', $arr);
        $smarty->display('Peihuo/Peihuo.tpl');
    }

    /**
     * ps ：配货单显示
     * Time：2016年3月17日 10:44:04
     * @author shen
    */
    function actionView2(){
        $sql="SELECT y.*,
            z.menfu,
            z.kezhong,
            z.shazhi,
            z.jingmi,
            z.weimi,
            z.zhengli,
            z.chengfen,
            z.proCode,
            z.Content
            from ph_peihuo2madan x
            left join madan_db y on y.id=x.madanId
            left join jichu_product z on z.proCode=y.productId
            where x.phId in ({$_GET['peihuoId']})";
        $rowset =$this->_modelExample->findBysql($sql);
        // dump($rowset);die;
        foreach ($rowset as $key => &$v) {
        // dump($v["cnt"]);exit;
            $compare = $v['productId'].','.$v['millNo'];
            $row[$compare][]=array(
                "cnt"=>$v['cnt'],
                "cntM"=>$v['cntM'],
                "rollNo"=>$v['rollNo'],
                "kuqu"=>$v['kuqu'],
                "millNo"=>$v['millNo'],
                "Product"=>array(
                    "proCode"=>$v['proCode'],
                    "shazhi"=>$v['shazhi'],
                    "jingmi"=>$v['jingmi'],
                    "weimi"=>$v['weimi'],
                    "chengfen"=>$v['chengfen'],
                    "zhengli"=>$v['zhengli'],
                    "kezhong"=>$v['kezhong'],
                    "Content"=>$v['Content'],
                ),
            );
        }
        $zJianshu = count($rowset);
        $zShuliang = $this->getHeji($rowset,array('cntM'));
        // dump($zShuliang);exit;
        $smarty = & $this->_getView();
        $smarty->assign('row', $row);
        $smarty->assign('zJianshu', $zJianshu);
        $smarty->assign('zShuliang', $zShuliang);
        $smarty->display('Peihuo/Peihuo.tpl');
    }
}
?>