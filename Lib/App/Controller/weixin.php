<?php
class Controller_weixin extends FLEA_Controller_Action {
 public $APPID="wx074ec52cfcf796cc";      
 public $APPSECRET="5b9e137c96b49b874b2b7b49cf616deb";  

    //用户首次配置开发环境
    function Controller_weixin() {

    }
    public function actionIndex()
    {
        //dump(312312);die;
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
        $echostr   = $_GET['echostr'];      
    $token     = 'skye';
    $tmpArr    = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr    = implode( $tmpArr );
    $tmpStr    = sha1( $tmpStr );
    if( $tmpStr == $signature && $echostr)
    {
        echo $echostr;
    }else{
       // $this->reposeMsg();
        //$this->Createmenu();
    }
    }

   

  public function actionCreatemenu(){
    //dump(231);die;
    $data='{  
     "button":[  
     {      
              "type":"view",  
               "name":"精选课程",  
              "url":"https://w.url.cn/s/ASOsHnk"  
     },       
       
      {  
          "name":"优研优选",  
           "sub_button":[  
            {      
               "type":"click",  
              "name":"院校&导师",  
           "key":"SCHOOCL_TEACHER"  
           },  
           {  
              "type":"view",  
              "name":"快速登录",  
              "url":"http://www.uyanuxuan.com/index.php"  
            },  
            {  
              "type":"view",  
              "name":"导师计划",  
               "url":"http://www.uyanuxuan.com/index.php/Home/About/xsjh.html"  
            }]    },  
         
       {  
          "name":"我的",  
          "sub_button":[  
            {      
               "type":"click",  
              "name":"联系我们",  
              "key":"CONTACTUS"  
           },  
           {  
              "type":"view",  
              "name":"正版软件",  
              "url":"http://www.xmypage.com/model2_37685.html"  
           },  
           {  
              "type":"view",  
              "name":"四六级冲刺",  
               "url":"https://h5.youyinian.cn/"  
            }]  
        }        
      ]  
 }';      
     $access_token=$this->GetToken();  
     $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;    
    $result=$this->postcurl($url,$data);  
     var_dump($result);             

  }
   function GetToken(){
       $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->APPID."&secret=".$this->APPSECRET;        
      $date=$this->postcurl($url);  
        $access_token=$date['access_token'];  
       return $access_token;   

   }

         //请求接口方法  
function postcurl($url,$data = null){         
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $url);  
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);   
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
   if (!empty($data)){  
      curl_setopt($ch, CURLOPT_POST, TRUE);  
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
   }  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
$output = curl_exec($ch);  
curl_close($ch);  
return  $output=json_decode($output,true);            
} 


}