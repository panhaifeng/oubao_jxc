<?php
load_class('TMIS_TableDataGateway');
class Model_SMS_Log extends TMIS_TableDataGateway {
  var $tableName   = 'sms_log';
  var $primaryKey  = 'id';

  /**
   * 发送短消息日志生成
   * @param $data 短消息数据来源
   * @return bool {true,创建成功 | false,创建失败}
   * @author zhuli
   */
  function createLog($data){
    $res = $this->save($data);
    if(!$res){
      $this->_errorMsg = '创建短消息日志失败';
      return false; 
    }
    return true;
  }

  /**
    * 获取已发短信条数
    */ 
  function getCntForSMSLog($arr = array()){
     $sql = "SELECT sum(sendCnt) AS cnt
             FROM sms_log
             WHERE 1
            ";
     if(isset($arr['isSendOk']))$sql.=" AND isSendOk = {$arr['isSendOk']}";
     if($arr['sendKind'])$sql.=" AND sendKind = {$arr['sendKind']}";
     $row = $this->findBySql($sql);
     return $row[0]['cnt']?$row[0]['cnt']:0;
  }
}
?>