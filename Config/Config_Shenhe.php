<?php
    /*********************************************************************\
    *  Copyright (c) 1998-2013, TH. All Rights Reserved.
    *  Author :沈浩
    *  FName  :Config_Shenhe.php
    *  Time   :2014/05/13 18:31:40
    *  Remark :审核功能集成
    *  1,对应表中增加shenhe字段
    *  2,修改本文件中的$_node_table 和$_config_shenhe变量，配置好后即可动态设置哪些用户可以进行审核操作
    *  3，在控制器中加上$v['shenhe']=$this->getShenheHtml('其他收款',$v['id'])，界面就会显示对应的审核按钮，
    *  4, 在目标页面中判断当前用户是否可以使用该审核功能
    \*********************************************************************/
    /**
     * 审核名称对应的表名称
     * @var array
    */
    $_node_table = array(
        '申购单'=>'caigou_shengou',
        '采购合同'=>'caigou_order',
        '销售合同'=>'trade_order',
        '其他收款'=>'caiwu_ar_income',
    );
    /**
     * 审核配置文件
     * @var array
    */
    $_config_shenhe = array(
        '申购单'=>array(
            array(
                'text'=>'一级',//显示的文字
                'status'=>true,//true表示开启，false表示关闭
                'id'=>'1-1',//判断是否有改审核权限
            ),
            array(
                'text'=>'二级',//显示的文字
                'status'=>false,//true表示开启，false表示关闭
                'id'=>'1-2',//判断是否有改审核权限
            )
        ),
        '采购合同'=>array(
            array(
                'text'=>'一级',
                'status'=>true,
                'id'=>'2-1',
            ),
            array(
                'text'=>'二级',
                'status'=>true,
                'id'=>'2-2',
            ),
        ),
        '销售合同'=>array(
            array(
                'text'=>'一级',
                'status'=>true,
                'id'=>'3-1',
            ),
            array(
                'text'=>'二级',
                'status'=>true,
                'id'=>'3-2',
            ),
        ),
        '其他收款'=>array(
            array(
                'text'=>'审核',
                'status'=>true,
                'id'=>'4-1',
            )
        ),
    );
?>
