<?php
/*********************************************************************\
*  Copyright (c) 2007-2015, TH. All Rights Reserved.
*  Author :li
*  FName  :CaigouRk.php
*  Time   :2015/09/10 13:57:24
*  Remark :采购入库：处理采购入库的逻辑代码
\*********************************************************************/
FLEA::loadClass('Controller_Cangku_Plan');
class Controller_Cangku_Xianhuo_Plan extends Controller_Cangku_Plan{

	function __construct() {
		parent::__construct();
        
        $this->_kind = __CANGKU_1;
        $this->_type_order = "现货";
        $this->_is_jiaoyan = true;
        $this->_mold='Xianhuo';
        $this->_check='3-1-6';
	}
    
}


?>