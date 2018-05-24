<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Report');
class Controller_Cangku_Dahuo_Report extends Controller_Cangku_Report{
    /**
     * 添加登记界面的权限
     * @var array
    */
    var $_check;
    function __construct() {
        parent::__construct();
        $this->_cangkuName = __CANGKU_3;
        $this->_mold = 'Dahuo';
        $this->_check='3-3-12';
    }

}


?>