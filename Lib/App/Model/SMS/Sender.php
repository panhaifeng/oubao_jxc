<?php
load_class('TMIS_TableDataGateway');
class Model_SMS_Sender extends TMIS_TableDataGateway {
  // 短消息发送配置表
  var $tableName   = 'sms_sender';
  var $primaryKey  = 'id';
  // 发送通道参数
  var $_arrSMS = array();
  // 发送配置信息
  var $_arrSetSender = array();
  // 短信单价，后期将其移动到Config中或DB中
  var $_price = 0;

  function Model_SMS_Sender() {
    // 通过配置文件来确定，发送短信的服务平台，和将配套使用的接口
    // 从配置文件取值 
    require "Config/sms_config.php";
    $arrSMS = $sms_config['SMS'];
    $this->_arrSMS = $arrSMS['param'];
    $this->_modelLog = & FLEA::getSingleton('Model_SMS_Log');
    $this->_modelSet = & FLEA::getSingleton('Model_SMS_Set');
    $this->_price = $sms_config['price'];
    parent:: __construct();
  }

  function _beforeSendMessage(&$arr){
    // 验证能否使用发送短信的功能
    if(!$this->_canSend($arr)){
      return false;
    }
    return true;
  }

  /**
   * 发送验证
   */
  function _canSend($arr){
    $cnt = count($arr['arrTo']);
    if($cnt<1){
      $this->_errorMsg = '号码有误';
      return false;
    }
    // 余额不足时
    $p = $this->_price * $cnt;
    $money = $this->getAccountMoney();
    if($money<$p){
      $this->_errorMsg = '余额不足';
      return false;
    }
    // 此项服务尚未开通
    $kind = $arr['kind'];
    $kindInfo = $this->getServiceInfo();
    if(!$kindInfo[$kind]['value']){
      $this->_errorMsg = '此项服务尚未开通';
      return false;
    }
    return true;
  }


  /**
   * 创建日志
   */
    function _createLog($arr){
        $res = $this->_modelLog->createLog($arr);
        if(!$res){
            $this->_errorMsg = $this->_modelLog->_errorMsg;
            return false;
        }
        return true;
    }
    /**
   * 统计发送成功短信的数量
   */
    function _createSet($arr){
        $res = $this->_modelSet->createSet($arr);
        if(!$res){
            $this->_errorMsg = $this->_modelSet->_errorMsg;
            return false;
        }
        return true;
    }

  /**
   * 发送普通短信
   * @param $sendInfo['tels'] 包含手机号码的字符串，并列号码使用;分隔的
   * @param $sendInfo['content'] 要发送的内容
   * @param $logInfo 短信日志用信息
   * @param $kind 服务类别
   */
  function sendForNormalSMS($sendInfo, $logInfo, $kind = 0){
    // dump($sendInfo);
    // 获取sendInfo 信息
    $_data = $sendInfo['data'];
    // dump($_data);
    $sendDate = $sendInfo['sendDate'];        // 发送时间
    $creater  = $_SESSION['REALNAME']?$_SESSION['REALNAME']:'系统自动';// 发送人
    $userName=$_data['userName'];
    $userId=$_data['userId'];
    $content  = $_data['content'];         // 发送内容
    // 处理发送信息
    $tels  = $_data['tel'];// 获取要发送的电话号码集
    $arrTel =  $this->_getArrayTel($_data['tel']);// 处理电话列表
    // 验证发送内容
    if(!count($arrTel)){
        $msg[] = '没有有效的收信人';
    }else{
        if(count($arrTel)>40)$msg[] = '有效的收信人不能超过40个';
    }
    if(!$content){
        $msg[] = '请输入有效的内容作为发送信息';
    }
    //若$msg中存在信息，代表有验证不通过，返回
    if(isset($msg[0])){
      $this->_errorMsg = join(",\n", $msg);
      return false;
    }
    // 发送信息
    $data = array('content'=> $content, 'tels'=> $arrTel , 'kind'=>$kind);
  
    /*// 发送前处理(包括验证短信账户内余额，以及发送内容是否安全)
    $res = $this->_beforeSendMessage($data);
    if(!$res){
      return false;
    }*/
    // 逐一发送
    $res = $this->sendSMS($data, $msgList);
    //将错误消息提交给类变量
    $msg = array();
    foreach ($arrTel as $k => $v) {
        $_tel = $v;
        // 创建log
        $log = array('isSendOk'=> $msgList[$_tel]['isSendOk']
                   ,'returnMsg'=> $msgList[$_tel]['returnMsg']
                   ,'sendDate'=> $sendDate?$sendDate:time()
                   ,'sendKind'=> $kind
                   ,'userId'=>$userId
                   ,'userName'=>$userName
                   ,'aboutId'=>$logInfo['aboutId']?$logInfo['aboutId']:0
                   ,'tel'=>$_tel
                   ,'tels'=>$tels
                   ,'content'=>$content
                   ,'sendCnt'=>ceil(($this->CountStr($content)+7)/67)
                   ,'creater'=>$creater
                   ,'ctime'=>date('Y-m-d H:i:s',time()));
        if(!$log['isSendOk'])$msg[] = $_tel.' '.$log['returnMsg'];
        $logRes=$this->_createLog($log); 
        if ($logRes && $log['isSendOk']) {
            $resSet=$this->_modelSet->find(array('item'=>'ISSUCCESS'));
            $logItem=array('item'=>'ISSUCCESS' 
                ,'itemName'=>$msgList[$_tel]['returnMsg']
                ,'value'=>$log['sendCnt']
            );
            if (count($resSet)) {
                $logItem['id']=$resSet['id'];
                $logItem['value']=$resSet['value']+$log['sendCnt'];
            }
            $this->_createSet($logItem); 
        }
    }
    $this->_errorMsg = join(",\n", $msg);
    return isset($msg[0]);
  }

  /**
   * 运单生成后的短信通知
   */
  function sendAfterCreat($tels, $logInfo,$kind = 1){
  }


  /**
   * 为短信模板替换 需要的值
   * @param $dataSrc 将填入的值
   * @param $map 将被替换的 关键字
   * @param $targetTxt 模板信息
   * @return string 返回已做替换的信息内容
   */
  function replaceForSmsModel($dataSrc, $map= array(), $targetTxt){
     foreach ($map as $k => $v) {
        $search[] = $v;
        $replace[] = $dataSrc[$k];
     }
     // dump($search);dump($replace);dump($targetTxt);exit;
     $targetTxt = str_replace($search, $replace, $targetTxt);
     return $targetTxt;
  }


  /**
   * 发送短信,
   * {功能描述: 1. 发送短信，2.返回发送结果。
   * 短消息日志需要包含前面的内容，且需要更多关联信息，所以应当在调用本方法后，由调用者自己生成完整的记录}
   * @param $data 应当包括如下参数{'content'发送内容,'arrTo'手机号码,'kind'确定服务是否开通}
   * @param $sendOnceOne 是否每条号码发送一次
   * @return @param &$msgList 存储发送后的返回消息，若$sendOnceOne==1 针对每条号码,若$sendOnceOne=0 针对所有号码,
   * @return boolean {true:发送成功 , false:发送失败,}
   */
  function sendSMS($data, &$msgList = array(), $sendOnceOne = true){
    // 获得短信发送内容值
    $content = $data['content'];
    $telsTo = $data['tels'];
    // 获得短信发送配置参数
    $param = $this->_arrSMS;
    // 获得短信发送URL
    $arr = array('ip'=>$param['ip'], 
                  'port'=>$param['port'], 
                  'userName'=>$param['userName'], 
                  'passwd'=>$param['passwd'], 
                  'to'=>'',
                  'content'=>$content);
    // 若要求一条号码提交一次
    if($sendOnceOne){
      // 遍历执行
      foreach ($telsTo as $k => $v) {
          $arr['to'] = $v;
          $sendInfoTemp = $this->_sendSms($arr);
          if(!$sendInfoTemp['isSendOk'])$res = false;
          $msgList[$v] = $sendInfoTemp;
      }
    }else{
      $arr['to'] = join(' ', $telsTo);
      $sendInfoTemp = $this->_sendSms($arr);
      if(!$sendInfoTemp['isSendOk'])$res = false;
      $msgList[] = $sendInfoTemp;
    }
    // 返回发送结果
    return $res;
  }

  /**
   * 发送单条信息
   */
  function _sendSms($arr){
    // 将发送内容按照短信格式编码
    $arr['content']= urlencode(iconv('utf-8','gbk',$arr['content']));
    // 将发送电话按照 RFC 1738 对 URL 进行编码 
    $arr['to'] = rawurlencode($arr['to']);
    $url = $this->_getUrl($arr);
    // dump($url);exit;
    // 发送返回值
    $ret = $this->_curl($url);
    $ret = (string)$ret;
    // $ret = 100;
    //  返回错误说明
    $errorMap = array(
        '0'=>'发送成功',
        '-2'=>'发送参数不正确',
        '-3'=>'用户载入延迟',
        '-6'=>'密码错误',
        '-7'=>'用户不存在',
        '-11'=>'发送号码数大于最大发送数量',
        '-12'=>'余额不足',
        '-99'=>'内部处理错误'
    );
    // 不确定返回的参数是
    // dump($ret);
    $isSuccess = ('0' == $ret) ? 1 : 0;
    $returnMsg = '发送成功';
    $isSuccess==0 && $returnMsg = isset($errorMap[$ret])?$errorMap[$ret]:'通道未知错误';
    return array('isSendOk'=> $isSuccess,
                 'returnMsg'=> $returnMsg);
  }

  /**
   * curl模拟post形式访问
   * Time：2015/10/27 16:32:19
   * @author li
  */
  function _curl($url = '' ,$post_data = array() ,$method = 'GET'){
      $ch = curl_init();
      // $_method = $method == 'GET' ? 0 : 1 ;
      strtoupper($method) == 'POST' && curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_URL,$url);
      //设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上, 0为直接输出屏幕，非0则不输出
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      count($post_data)>0 && curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
      //curl_excc会输出内容，而$result只是状态标记
      $result = curl_exec($ch);
      $errorCode = curl_errno($ch);
      //释放curl句柄
      curl_close($ch);
      if(0 !== $errorCode) {
          return -99;
      }
      return $result;
  }
  /**
   * 获得账户充值记录
   */
  function getAccountMoneyLog($arr= array()){
    $sql = "SELECT * 
            FROM sms_sender
            WHERE groupId = 1
            ";
    // 创建时间
    if($arr['dateFrom']){
      $sql .=" AND time >= ".strtotime($arr['dateFrom']);
    }
    if($arr['dateEnd']){
      $sql .=" AND time >= ".strtotime($arr['dateEnd']);
    }
    $rows = $this->findBySql($sql);
    // 处理获得总金额
    return $rows;
  }
  function renderMoneyLog($rows){
    // $columnAccountMoney = array('time'=>'时间', 'money'=>'金额', 'memo'=>'备注',);
    foreach ($rows as $k => $v) {
      $temp = array();
      $temp = $v;
      $temp['money'] = $v['value']?floatval($v['value']):0;
      $temp['memo'] = $v['itemName']?$v['itemName']:'';
      $temp['time'] = $v['time']?date('Y-m-d', $v['time']):'long long ago';
      $data[] = $temp;
    }
    // dump($data);
    return  $data;
  }

  // 获取短信模板
  function getSMSTxtModel($arr){    
    $sql = "SELECT * 
            FROM sms_sender
            WHERE groupId = 2
            ";
    // 创建时间
    if($arr['dateFrom']){
      $sql .=" AND time >= ".strtotime($arr['dateFrom']);
    }
    if($arr['dateEnd']){
      $sql .=" AND time >= ".strtotime($arr['dateEnd']);
    }
    $rows = $this->findBySql($sql);
    return $rows;
  }

  /**
   * 处理获得的短信模板信息
   */
  function renderForSMSTxtModel($rows){
    foreach ($rows as $k => $v) {
      $data[$v['index']] = $v;
    }
    // dump($data);
    return  $data;
  }

  // 获取短信服务开通信息
  function getServiceInfo($arr=array()){    
    $sql = "SELECT * 
            FROM sms_sender
            WHERE groupId = 3
            ";
    $rows = $this->findBySql($sql);
    // 处理获得总金额
    return $rows;
  }

  /**
   * 处理获得的短信模板信息
   */
  function renderForServiceInfo($rows){
    foreach ($rows as $k => $v) {
      $data[$v['index']] = $v;
    }
    return $data;
  }

  // 获取账户余额
  function getAccountMoney($arr){
    $sql = "SELECT * 
            FROM sms_sender
            WHERE groupId = 1
            ";
    // 创建时间
    if($arr['dateFrom']){
      $sql .=" AND time >= ".strtotime($arr['dateFrom']);
    }
    if($arr['dateEnd']){
      $sql .=" AND time >= ".strtotime($arr['dateEnd']);
    }
    $rows = $this->findBySql($sql);
    // 处理获得总金额
    $cnt = 0;
    foreach ($rows as $k => $v) {
      $cnt += floatval($v['value']);
    }
    return $cnt;
  }

  function getNumForSetMoney($arr = array()){
    $sql = "SELECT COUNT(*) AS cnt 
            FROM sms_sender
            WHERE groupId = 1
            ";
    // 创建时间
    if($arr['dateFrom']){
      $sql .=" AND time >= ".strtotime($arr['dateFrom']);
    }
    if($arr['dateEnd']){
      $sql .=" AND time >= ".strtotime($arr['dateEnd']);
    }
    $rows = $this->findBySql($sql);
    // 获得纪录的总数
    $num = $rows[0]['cnt']||0;
    return $num;
  }

  /**
   * groupId = 1,充值金额记录参数设置
   */
  function addSendAccountMoney($data){
    $index = $this->getNumForSetMoney()||0;
    $row = array('groupId'=>1, 
                 'groupName'=>'账户金额', 
                 'index' => $index, 
                 'item'=>'money',
                 'itemName'=> $data['memo']?$data['memo']:'',
                 'value'=> $data['money']?$data['money']:0,
                 'isActive'=> 1,
                 'time'=>$data['time']?$data['time']:time(),
                );
    $res = $this->save($row);
    if(!$res){
      $this->_errorMsg = '充值出错，联系相关人员';
      return false;
    }
    return true;
  }

  /**
   * groupId = 3, 服务开放参数设置
   */
  function setServiceValue($id, $isOpen = 0){
    $row = array('id'=>$id, 'value'=> $isOpen?1:0);
    $res = $this->save($row);
    if(!$res){
      $this->_errorMsg ='开通服务失败';
      return false;
    }
    return true;
  }

  /**
   * groupId = 2, 短消息模板参数设置
   */
  function setSMSModelValue($row){
    $res = $this->save($row);
    if(!$res){
      $this->_errorMsg ='更改短消息模板失败';
      return false;
    }
    return true;
  }


  /**
   * 从字符串中提取 号码数组
   * 从形似如下的结构中提取手机号码
   *  .ex：
   *  input：" 1395XXX001, 1395XXX002,1231;
   *           1395XXX003;
   *           <1395XXX001>,asdad 1395XXX004, 139sd5XXX005;" 
   *  output: array('1395XXX001', '1395XXX002', '1395XXX003', '1395XXX001', '1395XXX004')
   *获取有效电话号码
   */
    function _getArrayTel($tels){
        $arrTel = array();
        $arr = explode(';', $tels);
        $ruleMp = "/((13[0-9])|147|(15[0-35-9])|180|182|(18[5-9]))[0-9]{8}/";
        // 电话号
        $ruleTp = "/((\(?\d{3,4}\))?|(\d{3,4}-)?)\d{7,8}/";  
        foreach ($arr as $k=> $v) {
            $rule = "/<.*>/";
            $ret = preg_match($rule, $v, $targetArr);
            //取出在<>中的电话号码  //取出在手动输入的电话号码
            $v = $ret?$targetArr[0]:$v;   
            $cntTel = preg_match_all($ruleMp, $v, $resultArr);
            $result = $resultArr[0];
            // 将检测到的手机号码加入电话列表
            if($cntTel){
                foreach ($result as $k => $v) {
                    if($v) $arrTel[] =  $v;
                }
            }
        }
        return $arrTel;
    }

   /**
    *    匹配手机号码
    *   规则：
    *       手机号码基本格式：
    *       前面三位为：
    *       移动：134-139 147 150-152 157-159 182 187 188
    *       联通：130-132 155-156 185 186
    *       电信：133 153 180 189
    *       后面八位为：
    *       0-9位的数字
    */
  function pregPN($test, $isFind = true){
      $rule  = $isFind ? "/((13[0-9])|147|(15[0-35-9])|180|182|(18[5-9]))[0-9]{8}/"
                       : "/^((13[0-9])|147|(15[0-35-9])|180|182|(18[5-9]))[0-9]{8}$/A";
      $cntTel = preg_match_all($rule,$test,$resultArr);
      // dump($resultArr);exit;
      $result = $resultArr[0];
      // 将检测到的手机号码加入电话列表
      $arrTel = array();
      if($cntTel){
          foreach ($result as $k => $v) {
              if($v) $arrTel[] =  $v;
          }
      }
      return $arrTel;
  }

  /**
   * 获得URL
   * @param $arr 
   *             
   */
  function _getUrl($arr){
        $ip = $arr['ip'];
        $port = $arr['port'];
        $userName = $arr['userName'];
        $passwd = $arr['passwd'];
        $to = $arr['to'];
        $content = $arr['content'];
        /* 序号  参数名称    含义
            1   username（必须）    用户名
            2   password（必须）    密码
            3   to（必须）  发送目的号码，多号码以空格格开。一次最多100个号码
            4   text(内容不能为空)    内容，以URLEncoder.encode(content, "GB2312")编码
            5   subid(可为空)  用户自定义扩展（代理商2的子账号，用于是否特服号后追加此子账号）
            6   msgtype（必须，默认为1）    短信类型 1：普通短信
                     4：长短信
                     5：彩信
            返回错误说明
        */
        $url = "http://{$ip}:{$port}/cgi-bin/sendsms?username={$userName}&password={$passwd}&to={$to}&text={$content}&msgtype=1";
        return $url;
  }

  /**
   * 获得字符串的单元字符数
   */
  function CountStr($str, $charset = 'utf-8'){
    $count = 0;
    // TEST CASE //$count=18: $str="w 怎么 67 9☆，　【（~ 12";
    preg_match_all("/./us", $str, $match);
    // 返回单元个数
    $count = count($match[0]);
    return $count;
  }
}
?>