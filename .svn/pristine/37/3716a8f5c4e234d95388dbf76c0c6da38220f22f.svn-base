<?php
load_class('TMIS_TableDataGateway');
class Model_SMS_Set extends TMIS_TableDataGateway {
    var $tableName   = 'sys_set';
    var $primaryKey  = 'id';

    /**
    * 统计成功发送的短信数
    * @param $data 短消息数据来源
    * @return bool {true,创建成功 | false,创建失败}
    * @author zhangyan
    */
    function createSet($data){
        $res = $this->save($data);
        if(!$res){
            $this->_errorMsg = '统计数据失败';
            return false; 
        }
        return true;
    }
}
?>