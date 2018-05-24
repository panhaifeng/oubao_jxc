<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Ruku');
class Controller_Cangku_Xianhuo_CaigouRk extends Controller_Cangku_Ruku{
	function __construct() {
		parent::__construct();
        $this->_cangkuName = __CANGKU_1;
        $this->_head = 'XHCG';
        $this->_kind = '采购入库';
        $this->_check='3-1-2';
        $this->_modelCaigou = &FLEA::getSingleton('Model_Caigou_Order');
        $this->_modelCaigou2pro = &FLEA::getSingleton('Model_Caigou_Order2Product');
        // $this->_rukushenhe = &FLEA::getSingleton('Model_Cangku_RukuShenhe');

        $this->fldMain = array(
            'rukuCode' => array('title' => '入库单号', "type" => "text", 'readonly' => true,'value' =>'自动生成' ),
            'rukuDate' => array('title' => '入库日期', 'type' => 'calendar', 'value' => date('Y-m-d')),
            'kuweiId' => array('title' => '仓库','type' => 'select', 'value' => '','name'=>'kuweiId','model'=>'Model_Jichu_Kuwei','condition'=>'ckName="'.$this->_cangkuName.'"'),
            'caigouCode' => array('title'=>'采购合同','type' => 'text', 'value' =>'','name'=>'caigouCode','readonly'=>true),
            'caigouId' => array('type' => 'hidden', 'value' =>''),
            'kind' => array('title'=>'入库类型','type' => 'text', 'value' => $this->_kind,'readonly'=>true),
            'madanExport'=>array('title'=>'码单导入','type'=>'file','name'=>'madanExport'),
            'memo'=>array('title'=>'备注','type'=>'textarea','name'=>'memo'),
            // 下面为隐藏字段
            'id' => array('type' => 'hidden', 'value' => '','name'=>'cgrkId'),
            'cangkuName' => array('type' => 'hidden', 'value' => $this->_cangkuName,'name'=>'cangkuName'),
            'isGuozhang' => array('type' => 'hidden', 'value' => '0','name'=>'isGuozhang'),
            'creater' => array('type' => 'hidden', 'value' => $_SESSION['REALNAME']),

        );
        // /从表表头信息
        // /type为控件类型,在自定义模板控件
        // /title为表头
        // /name为控件名
        // /bt开头的type都在webcontrolsExt中写的,如果有新的控件，也需要增加
        $this->headSon = array(
            '_edit' => array('type' => 'btBtnCopy', "title" => '操作', 'name' => '_edit[]'),
            'pihao'=>array('type'=>'BtText',"title"=>'批号','name'=>'pihao[]'),
            // 'productId' => array(
            //     'title' => '花型六位号',
            //     'type' => 'BtPopup',
            //     'name' => 'productId[]',
            //     'url'=>url('Jichu_Product','Popup'),
            //     'textFld'=>'proCode',
            //     'hiddenFld'=>'id',
            //     'inTable'=>true,
            //     'dialogWidth'=>900
            // ),
            'proCode'=>array('type'=>'BtText',"title"=>'花型六位号','name'=>'proCode[]','readonly'=>true),
            'productId'=>array('type'=>'BtHidden','name'=>'productId[]','readonly'=>true),
            'proName'=>array('type'=>'BtText',"title"=>'品名','name'=>'proName[]','readonly'=>true),
            'proXinxi'=>array('type'=>'BtText',"title"=>'产品信息','name'=>'proXinxi[]','readonly'=>true,'width'=>200),
            'cntCg' => array('type' => 'BtText', "title" => '采购数量', 'name' => 'cntCg[]','readonly'=>true),
            'cntYr' => array('type' => 'BtText', "title" => '已入库数量', 'name' => 'cntYr[]','readonly'=>true),
            'cnt' => array('type' => 'BtText', "title" => '入库数量', 'name' => 'cnt[]','readonly'=>true),
            'cntJian' => array('type' => 'BtText', "title" => '入库卷数', 'name' => 'cntJian[]','readonly'=>true),
            'Madan' => array('type' => 'btBtnMadan', "title" => '码单', 'name' => 'Madan[]'),
            // 'kuweiId' => array('title' => '库位','type' => 'BtSelect', 'value' => '','name'=>'kuweiId','model'=>'Model_Jichu_Kuwei','condition'=>'ckName="'.$this->_cangkuName.'"'),
            'unit' => array("title" => '单位', 'name' => 'unit[]','type'=>'BtSelect','value'=>'','options'=>array(
                    array('text'=>'M','value'=>'M'),
                    array('text'=>'Y','value'=>'Y')
                )),
            'memoView' => array('type' => 'BtText', "title" => '备注', 'name' => 'memoView[]'),
            // ***************如何处理hidden?
            'id' => array('type' => 'BtHidden', 'name' => 'id[]'),
            'cntM' => array('type' => 'BtHidden', 'name' => 'cntM[]'),
            'danjia' => array('type' => 'BtHidden', 'name' => 'danjia[]'),
            'cai2proId' => array('type' => 'BtHidden', 'name' => 'cai2proId[]'),
        );
        // 表单元素的验证规则定义
        $this->rules = array(
            'kuweiId' => 'required',
        );

        $this->sonTpl = array(
            'Cangku/sonTpl.tpl',
        );
	}

    function actionRight(){
        //权限判断
        $this->authCheck($this->_check);
        FLEA::loadClass('TMIS_Pager');
        $arr = TMIS_Pager::getParamArray(array(
            'danhao'=>'',
            'supplierId'=>'',
            'caiEmpId' => '',
            'isRkOver'=>'2'
        ));
        $sql="select x.*,a.compName,e.employName
            from caigou_order x
            left join jichu_supplier a on a.id=x.supplierId
            left join jichu_employ e on e.id=x.employId
            where 1 and x.shenhe='yes'";
        if($arr['supplierId']!='') $sql .=" and x.supplierId='{$arr['supplierId']}' ";
        if($arr['caiEmpId']!='') $sql .=" and x.employId='{$arr['caiEmpId']}' ";
        if($arr['danhao']!='') $sql .=" and x.orderCode like '%{$arr['danhao']}%' ";
        if($arr['isRkOver']==1) $sql .=" and x.id not in(select caigouId from caigou_order x
                                            left join caigou_order2product y on x.id=y.caigouId
                                            where rukuOver=0 group by caigouId)";
        if($arr['isRkOver']==0) $sql .=" and x.id in(select caigouId from caigou_order x
                                            left join caigou_order2product y on x.id=y.caigouId
                                            where rukuOver=0 group by caigouId)";
        $sql.=" order by orderCode desc";
        $pager = &new TMIS_Pager($sql);
        $rowset = $pager->findAll();
        // dump($rowset);die;
        if (count($rowset) > 0) foreach($rowset as &$v) {
            $v['orderDate']=$v['orderDate']=='0000-00-00'?'':$v['orderDate'];
            $v['_edit']="<a href='".$this->_url('Add',array(
                'caigouId'=>$v['id']
            ))."' title='登记入库'>登记入库</a>";
            //明细显示
            $str="select y.id as sid,y.jiaoqi,z.color,y.cnt,y.unit,y.danjia,y.memo,z.proCode,
                z.proName,z.chengfen,z.shazhi,z.menfu,CONCAT(z.jingmi,'*',z.weimi) as jwmi,y.rukuOver,
                r.id as rid
                from caigou_order2product y
                left join jichu_product z on z.proCode=y.productId
                left join cangku_ruku2product r on r.cai2proId=y.id
                where 1 and y.caigouId='{$v['id']}'  group by y.id";
            $str.=" order by proCode asc";
            $ret=$this->_modelExample->findBySql($str);
            // dump($str);die;
            foreach ($ret as &$r) {
                $str2="select sum(r.cntM) as rcnt
                    from  cangku_ruku2product r
                    left join cangku_ruku rk on rk.id=r.rukuId
                    where 1 and r.cai2proId='{$r['sid']}' and rk.cangkuName='{$this->_cangkuName}' ";
                $res=$this->_modelExample->findBySql($str2);
                $r['rcnt']=$res[0]['rcnt'];
                $r["select"] = "<input type='checkbox' name='ck[]' id='ck[]' value='{$k}' data-id='{$r['sid']}'>";
                $tempOver=$r['rukuOver']==0?1:0;
                $r['rukuOver']=$r['rukuOver']==0?'未完成':'完成';
                $r['rukuOver']="<a href='".$this->_url('SetOver',array(
                            'id'=>$r['sid'],
                            'rukuOver'=>$tempOver
                            ))."' title='{$r['rukuOver']}'>{$r['rukuOver']}</a>";
            }
            $v['Products']=$ret;
        }
         // 全选/取消全选
        $other_btn="<input type='checkbox' id='checkedAll' title='全选/取消全选'/>"."&nbsp;&nbsp";
        // 标记完成
        $other_btn.='<button type="button" class="btn btn-success btn-xs" id="finish" name="finish" data-href="'
                   .$this->_url('SetOverMulti')
                   .'" title="标记完成">完成</button>';

        $smarty = &$this->_getView();
        $arrFieldInfo = array(
            "_edit" => '操作',
            'orderCode'=>'单号',
            "orderDate" => "采购日期",
            "employName" => "采购人",
            'compName'=>'供应商',
            "memo" => '备注',
        );
        $arrField = array(
            "select"   =>array("text"=>$other_btn,"width"=>65),
            'rukuOver'=>'是否入库完成',
            'proCode'=>'花型六位号',
            'proName'=>'品名',
            'chengfen'=>'成分',
            'shazhi' =>'纱支',
            'jwmi' =>'经纬密',
            'menfu' =>'门幅',
            // 'color'=>'颜色',
            'cnt' =>'要货数',
            'unit' =>'单位',
            'danjia' =>'单价',
            'rcnt' =>'当前入库数量(M)',
            'memo' =>'备注',
        );
        $smarty->assign('title', '采购合同审核');
        $smarty->assign('arr_field_info', $arrFieldInfo);
        $smarty->assign('arr_field_info2', $arrField);
        $smarty->assign('sub_field', 'Products');
        $smarty->assign('add_display', 'none');
        $smarty->assign('arr_condition', $arr);
        $smarty->assign('arr_field_value', $rowset);
        $smarty->assign("page_info", $pager->getNavBar($this->_url($_GET['action']), $arr));
        $smarty->assign('sonTpl', "Cangku/Xianhuo/cgrk_son.tpl");
        $smarty->display('TblListMore.tpl');
    }

    function actionAdd(){
        //取得采购合同的数据
        $arr = $this->_modelCaigou2pro->findAll(array('caigouId' => $_GET['caigouId']),productId);
        // dump($arr);exit;

        foreach($arr as & $v) {
            $temp = array();
            $temp['productId']['value'] = $v['Product']['proCode'];
            $temp['proCode']['value'] = $v['Product']['proCode'];
            $temp['proName']['value'] = $v['Product']['proName'];
            $temp['proXinxi']['value'] = '成分:'.$v['Product']['chengfen'].',纱支:'.$v['Product']['shazhi'].',经纬密:'.$v['Product']['jingmi'].'*'.$v['Product']['weimi'].',门幅:'.$v['Product']['menfu'];
            $temp['unit']['value'] = $v['unit'];
            $temp['cntCg']['value'] = $v['cnt'];
            $temp['danjia']['value'] = $v['danjia'];
            $temp['cai2proId']['value']=$v['id'];

            //获取已入库数量
            $sql="select sum(cnt) as cnt from cangku_ruku2product where cai2proId='{$v['id']}' group by cai2proId";
            $retcnt=$this->_modelExample->findBySql($sql);
            $temp['cntYr']['value']=$retcnt[0]['cnt'];

            $rowsSon[] = $temp;

            $this->fldMain['caigouCode']['value']=$v['Caigou']['orderCode'];

        }


        $this->fldMain['caigouId']['value']=$_GET['caigouId'];
        // dump($rowsSon);exit;
        $smarty = &$this->_getView();
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);

        $_from = $_GET['fromAction']==''?'add':$_GET['fromAction'];
        $smarty->assign('fromAction', $_from);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $this->_beforeDisplayAdd($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }
    function actionEdit(){
        $arr = $this->_modelExample->find(array('id' => $_GET['id']));
        //采购信息
        $caigou = &FLEA::getSingleton('Model_Caigou_Order');
        $cai=$caigou->find(array('id'=>$arr['caigouId']));
        foreach ($cai['Products'] as & $c) {
            $retCai[$c['id']]=$c;
        }

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        $this->fldMain['caigouCode']['value']=$cai['orderCode'];
        //仓库信息
        if($arr['kuweiId']>0){
            $sql="select kuweiName from jichu_kuwei where id='{$arr['kuweiId']}'";
            $temp=$this->_subModel->findBySql($sql);
            $this->fldMain['kuweiId'] && $this->fldMain['kuweiId']['text']=$temp[0]['kuweiName'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $v['productId'] = $_temp[0]['proCode'];
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['proXinxi'] = '成分:'.$_temp[0]['chengfen'].',纱支:'.$_temp[0]['shazhi'].',经纬密:'.$_temp[0]['jingmi'].'*'.$_temp[0]['weimi'].',门幅:'.$_temp[0]['menfu'];

            $v['danjia'] = round($v['danjia'],6);

            //查找码单信息，并json_encode
            $sql="select y.* from madan_rc2madan x
                    left join madan_db y on x.madanId=y.id
                    where x.rukuId='{$v['id']}'";
            $retMadan = $this->_modelExample->findBySql($sql);
            $_temp=array();
            foreach($retMadan as & $m){
                //当码单被锁定或者已出库则不能修改码单
                if ($m['status'] !='active') {
                    $m['readonly']=true;
                }
                $_temp[$m['rollNo']-1]=$m;
            }
            $_temp['isCheck']=1;
            $v['Madan'] = json_encode($_temp);

            //获取已入库数量
            $sql="select sum(cnt) as cnt from cangku_ruku2product where cai2proId='{$v['cai2proId']}' group by cai2proId";
            $retcnt=$this->_modelExample->findBySql($sql);
            $v['cntYr']=$retcnt[0]['cnt'];
        }

        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $temp['productId']['text']=$v['proCode'];
            $temp['cntCg']['value']=$retCai[$v['cai2proId']]['cnt'];
            $rowsSon[] = $temp;
        }

        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('fromAction', $_GET['fromAction']);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $this->_beforeDisplayEdit($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }
     /**
     * ps ：标记采购合同完成(多条操作)
     * Time：2016-4-7 10:33:39
     * @author jiangxu
     * @param 参数类型
     * @return 返回值类型
    */
    function actionSetOverMulti(){
        // 构造数组
        $trades = $_POST['trades'];
        // 约定数据格式
        //   id = $trades['id']
        // 标记完成
        $RUKU_OVER = 1 ;
        $data = array();
        foreach ($trades as $k => $v) {
            $data[] = array(
                        'id'=> $v['id'],
                        'rukuOver'=> $RUKU_OVER,
                        );
        }

        $flag = $this->_modelCaigou2pro->saveRowset($data);
        if($flag){
            $return = array('flag'=>'success', 'msg'=>'标记成功', 'href' => $this->_url('Right',array()));
        }else{
            $return = array('flag'=>'fail', 'msg'=>'标记失败', 'href' => '');
        }

        echo json_encode($return);
    }

    /**
     * ps ：标记采购合同是否完成
     * Time：2015/09/17 15:31:41
     * @author jiang
    */
    function actionSetOver(){
        $arr=array(
                'id'=>$_GET['id'],
                'rukuOver'=>$_GET['rukuOver'],
        );
        $this->_modelCaigou2pro->save($arr);

        js_alert(null,'window.parent.showMsg("修改成功!")',$this->_url('Right',array()));
    }
    /**
     * 列子，调用父类的Add或Edit的时候，有些功能需要扩展，这里提供了接口实现
     * 获取信息后处理，就可以了
     * Time：2015/09/10 16:40:52
     * @author li
     * @param smarty object
    */
    // function _beforeDisplayEdit(& $smarty) {
    //     $rowsSon = & $smarty->_tpl_vars['rowsSon'];
    //     $areaMain = & $smarty->_tpl_vars['areaMain'];
    // }

    /*
    * ps：码单导入
    * time:2015-12-07 shen
    */

    function actionSaveMadanExport(){
        $temp=array();$arr=array();
        foreach ($_FILES as $k=> &$v){
            for($i=0;$i<count($v['name']);$i++){
                foreach ($v as $key=> &$value){
                    $temp[$key]=$value;
                }
                $arr[][$k]=$temp;
            }
        }
        if($arr['0']['madanExport']['name']==''){
            js_alert("没有选择文件禁止保存",'window.history.go(-1)');
        }
        foreach ($arr as &$v){
            $temp1='';
            foreach ($v as $kk=>&$vv){
                $temp1=$kk;
            }
            $dizhi['path']=$v[$kk];
            $filePath[$kk][]= $this->_importAttac($dizhi);
        }
        $filePath=$filePath['madanExport']['0']['filePath'];
        $arr = $this->_readExcel($filePath,0);
        $temp_juan=0;
        foreach ($arr as $key => &$v) {
            if ($key <1) continue;
            if(!$v[0]) continue;
            $sql="select * from jichu_kuqu where kuquName='{$v[4]}'";
            $res=$this->_modelExample->findBySql($sql);
            if($res){
                $kuquId=$res[0]['id'];
            }else{
                js_alert("请确认Excel中库区是否存在",'window.history.go(-1)');
            }
            $data[]=array(
                'productId'=>(string)$v[0],
                'rollNo'=>(string)$v[1],
                'cnt'=>(string)$v[2],
                'cntFormat'=>(string)$v[2],
                'qrcode'=>(string)$v[3],
                'kuqu'=>(string)$v[4],
                'pihao'=>(string)$v[5],
                'kuquId'=>$kuquId
                );
            $cnt+=$v[2];
        }
        $cntJian=count($data);
        ksort($data);

        //根据花型六位号对$data数组进行汇总
        foreach($data as $k=> &$v) {
            $res[$v["productId"]][] = $v;
        }
        $data=array_values($res);
        $_tempRes=array();
        foreach ($res as $key => & $r) {
            foreach ($r as & $s) {
                $_tempRes[$key][$s['rollNo']]=$s;
            }
            //求数组卷号最大值
            rsort($r);
            $temp_juan=$r[0]['rollNo'];
            for($i=1;$i<=$temp_juan;$i++){
                if(!is_array($_tempRes[$key][$i])){
                    $_tempRes[$key][$i]=null;
                }
            }
            ksort($_tempRes[$key]);
            $ttt[]=array_values($_tempRes[$key]);
        }
        // 向每行数据中加入 jsonData = "当前行的json格式数据"；
        foreach ($data as $key => &$rowJson) {
            foreach ($ttt as &$w) {
                $rowJson['jsonData'][] = json_encode($w);
            }
        }

       //处理$data数组 获取卷数和总数量
        foreach ($data as $key => &$value){
            $value['rollNo']=count($value)-1;
            $value['productId']=$value[0]['productId'];
            $value['pihao']=$value[0]['pihao'];
            $json_d['data']=$value['productId'];
            unset($value['productId']);
            foreach ($value as &$v2) {
                $value['cnt']+=$v2['cnt'];
            }
            $value['productId']=$json_d['data'];
        }
        $result=array_values($data);
        // dump($data);die;
        $jsonData[]=json_encode($result);
        echo json_encode(array('success'=>true,'data'=>$jsonData,'dataMadan'=>$data,'jian'=>$cntJian,'cnt'=>$cnt));exit;
    }

    function _importAttac($dizhi){
        //上传路径
        $path="upload/shengou/";
        $targetFile='';
        $tt = false;//是否上传文件成功
        //禁止上传的文件类型
        $upBitType = array(
                'application/x-msdownload',//exe,dll
                'application/octet-stream',//bat
                'application/javascript',//js
                'application/msword',//word
        );
        // dump($dizhi['path']['name']);die;
        //处理上传代码
        if($dizhi['path']['name']!=''){
            //附件大小不能超过10M
            $max_size=10;//M
            $max_size2=$max_size*1024*1024;
            if($dizhi['path']['size']>$max_size2){
                return array('success'=>false,'msg'=>"附件上传失败，请上传小于{$max_size}M的附件");
            }

            //限制类型
            if(in_array($dizhi['path']['type'],$upBitType)){
                $_msg = "该文件类型不允许上传";
                js_alert($_msg,'window.history.go(-1)');
                // return array('success'=>false,'msg'=>"该文件类型不允许上传");
            }

            //上传附件信息
            if ($dizhi['path']['name']!="") {
                $tempFile = $dizhi['path']['tmp_name'];
                //处理文件名
                $pinfo=pathinfo($dizhi['path']['name']);
                $ftype=$pinfo['extension'];
                // $fileName=md5(uniqid(rand(), true)).' '.$dizhi['path']['name'];
                // $fileName=$dizhi['path']['name'];
                $fileName=md5(uniqid(rand(), true));
                $targetFile=$path.$fileName;//目标路径
                $tt=move_uploaded_file($tempFile,iconv('UTF-8','gb2312',$targetFile));
                if($tt==false && $targetFile!='')$msg="上传失败，请重新上传附件";
            }
        }
        return array('filePath'=>$targetFile,'success'=>$tt,'msg'=>$msg);
    }


    /**
     * 读取某个excel文件的某个sheet数据
    */
    function _readExcel($filePath,$sheetIndex=0) {
        set_time_limit(0);
        include "Lib/PhpExcel/PHPExcel.php";

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize'=>'16MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $PHPExcel = new PHPExcel();
        //如果是2007,需要$PHPReader = new PHPExcel_Reader_Excel2007();
        $PHPReader = new PHPExcel_Reader_Excel5();
        if(!$PHPReader->canRead($filePath)){
            $PHPReader = new PHPExcel_Reader_Excel2007();
            if (!$PHPReader->canRead($filePath)) {
                js_alert('只能上传Excel文件！','window.history.go(-1)');
            }
        }
        $PHPExcel = $PHPReader->load($filePath);
        /**读取excel文件中的第一个工作表*/
        $currentSheet = $PHPExcel->getSheet($sheetIndex);
        /**取得共有多少列,若不使用此静态方法，获得的$col是文件列的最大的英文大写字母*/
        $allColumn = PHPExcel_Cell::columnIndexFromString($currentSheet->getHighestColumn());

        /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();
        //输出
        $ret = array();
        for($currow=1;$currow<=$allRow;$currow++){
          $_row=array();
          for($curcol=0;$curcol<$allColumn;$curcol++){
               $result=$currentSheet->getCellByColumnAndRow($curcol,$currow)->getValue();
               $_row[] = $result;
          }
          $ret[] = $_row;
        }
        return $ret;
    }

    function actionEditShenhe(){
        $this->authCheck('3-1-13-1');
        $arr = $this->_rukushenhe->find(array('id' => $_GET['id']));
        //采购信息
        $caigou = &FLEA::getSingleton('Model_Caigou_Order');
        $cai=$caigou->find(array('id'=>$arr['caigouId']));
        foreach ($cai['Products'] as & $c) {
            $retCai[$c['id']]=$c;
        }

        foreach ($this->fldMain as $k => &$v) {
            $v['value'] = $arr[$k]?$arr[$k]:$v['value'];
        }
        $this->fldMain['caigouCode']['value']=$cai['orderCode'];
        //仓库信息
        if($arr['kuweiId']>0){
            $sql="select kuweiName from jichu_kuwei where id='{$arr['kuweiId']}'";
            $temp=$this->_subModel->findBySql($sql);
            $this->fldMain['kuweiId'] && $this->fldMain['kuweiId']['text']=$temp[0]['kuweiName'];
        }

        // //加载库位信息的值
        $areaMain = array('title' => $this->_kind.'基本信息', 'fld' => $this->fldMain);

        // 入库明细处理
        $rowsSon = array();
        foreach($arr['Products'] as &$v) {
            $sql = "select * from jichu_product where proCode='{$v['productId']}'";
            $_temp = $this->_modelExample->findBySql($sql);
            $v['productId'] = $_temp[0]['proCode'];
            $v['proCode'] = $_temp[0]['proCode'];
            $v['proName'] = $_temp[0]['proName'];
            $v['proXinxi'] = '成分:'.$_temp[0]['chengfen'].',纱支:'.$_temp[0]['shazhi'].',经纬密:'.$_temp[0]['jingmi'].'*'.$_temp[0]['weimi'].',门幅:'.$_temp[0]['menfu'];

            $v['danjia'] = round($v['danjia'],6);

            //查找码单信息，并json_encode
            $retMadan=json_decode($v['MadanJson'],true);
            $_temp=array();
            foreach($retMadan as & $m){
                $_temp[$m['rollNo']-1]=$m;
            }
            $v['Madan'] = json_encode($_temp,true);

            //获取已入库数量
            $sql="select sum(cnt) as cnt from cangku_ruku2product where cai2proId='{$v['cai2proId']}' group by cai2proId";
            $retcnt=$this->_modelExample->findBySql($sql);
            $v['cntYr']=$retcnt[0]['cnt'];
        }

        foreach($arr['Products'] as &$v) {
            $temp = array();
            foreach($this->headSon as $kk => &$vv) {
                $temp[$kk] = array('value' => $v[$kk]);
            }
            $temp['productId']['text']=$v['proCode'];
            $temp['cntCg']['value']=$retCai[$v['cai2proId']]['cnt'];
            $rowsSon[] = $temp;
        }

        $smarty = &$this->_getView();
        $smarty->assign('areaMain', $areaMain);
        $smarty->assign('headSon', $this->headSon);
        $smarty->assign('rowsSon', $rowsSon);
        $smarty->assign('fromAction', $_GET['fromAction']);
        $smarty->assign('rules', $this->rules);
        $smarty->assign('sonTpl', $this->sonTpl);
        $this->_beforeDisplayEdit($smarty);
        $smarty->display('Main2Son/T1.tpl');
    }
}


?>