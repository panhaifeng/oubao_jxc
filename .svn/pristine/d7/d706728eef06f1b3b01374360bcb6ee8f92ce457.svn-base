<?php
/**
 *
 * 快递鸟电子面单接口
 *
 * @技术QQ: 4009633321
 * @技术QQ群: 200121393
 * @see: http://www.kdniao.com/MiandanAPI.aspx
 * @copyright: 深圳市快金数据技术服务有限公司
 * 
 * ID和Key请到官网申请：http://www.kdniao.com/ServiceApply.aspx
 */

FLEA::loadClass('Api_Response');
// defined('EBusinessID') or define('EBusinessID', '1257774');
// //电商加密私钥，快递鸟提供，注意保管，不要泄漏
// defined('AppKey') or define('AppKey', '0540fdd9-b314-4d96-b2ef-07b98171c88d');
// //请求url，接口正式地址：http://api.kdniao.cc/api/eorderservice
// defined('ReqURL') or define('ReqURL', 'http://testapi.kdniao.cc:8081/api/eorderservice');

class Api_Lib_Express extends Api_Response {
    private $EBusinessID;
    // private $EBusinessID = '1257774';
    private $AppKey;
    // private $AppKey = '0540fdd9-b314-4d96-b2ef-07b98171c88d';
    private $ReqURL;
    // private $ReqURL = 'http://api.kdniao.cc:80/api/EOrderService';
    private $Sender;

    function __construct(){
        //加载配置文件
        $this->kdbird_config = require_once('Config/kdbird_config.php');
        // dump($_login_config);
        $this->EBusinessID = $this->kdbird_config['EBusinessID'];
        $this->AppKey = $this->kdbird_config['AppKey'];
        $this->ReqURL = $this->kdbird_config['ReqURL'];
        $this->Sender = $this->kdbird_config['Sender'];
    }

    function _get_config(){
        return $this->kdbird_config;
    }

    //调用获取物流轨迹
    //-------------------------------------------------------------
    function payOrder($eorder = array()){
        if($eorder){
            $eorder['Sender'] = $this->Sender;
        }
        // dump($eorder);die;
        //调用电子面单
        $jsonParam = json_encode($eorder);
        // echo "电子面单接口提交内容：<br/>".$jsonParam;
        $jsonResult = $this->subOrder($jsonParam);
        // echo "<br/><br/>电子面单提交结果:<br/>".$jsonResult;
        //解析电子面单返回结果
        $result = json_decode($jsonResult, true);
        // echo "<br/><br/>返回码:".$result["ResultCode"];
        if($result["ResultCode"] == "100") {
        // echo "<br/>是否成功:".$result["Success"];
        }else{
            echo "<br/>电子面单下单失败,失败原因：".$result['Reason'];exit();
        }
        return $result;
    }

    
//-------------------------------------------------------------


    /**
     * Json方式 查询订单物流轨迹
     */
    function subOrder($requestData){
        // var_dump($requestData);die;
        //$requestData= "{\"OrderCode\":\"\",\"ShipperCode\":\"".$shipperCode."\",\"LogisticCode\":\"".$logisticCode."\"}";
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result=$this->sendPost($this->ReqURL, $datas);   
        //根据公司业务处理返回的信息......
        return $result;
    }


 
    /**
     *  post提交数据 
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据 
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();   
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);      
        }   
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;

        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }

        fclose($fd); 
        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容   
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

}