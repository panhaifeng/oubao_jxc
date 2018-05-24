<?php
/*********************************************************************\
*  Copyright (c) 1998-2013, TH. All Rights Reserved.
*  Author :Jeff
*  FName  :Response.php
*  Time   :2014/05/13 18:31:40
*  Remark :api接口的响应类
\*********************************************************************/
class Api_Response {
    // var $starTime;
    var $_json;//从json文件载入后的对象
    var $_arr_method=array();//所有的方法名列表
    var $_method;//当前的方法
    var $_params;//当前参数
    var $_success;//是否成功
    var $_msg;//失败的错误信息，或者成功后的结果json
    var $_configFile = 'ConfigResponse.json';
    function __construct() {
        //判断超时代码，暂时不考虑
        // $this->starTime = $_SERVER['REQUEST_TIME']?$_SERVER['REQUEST_TIME']:time();
        //根据配置文件获得所有的方法列表,用作映射,可考虑引入缓存机制 todo
        $api_json = file_get_contents(APP_DIR."/Api/".$this->_configFile);

        //去掉api_json的注释,todo

        // echo $api_json;
        $arr = json_decode($api_json,true);
        if(!$arr) {
            echo "api配置文件decode失败，请检查config.json语法!";
            exit;
        }
        $this->_json = $arr;

        foreach($arr['data'] as $k=>&$v) {
            if($k=='//') continue;
            foreach($v as $kk=>&$vv) {
                if($kk=='//') continue;
                //如果是数组，说明是详细配置
                if(is_array($vv)) {
                    $name = isset($vv['name'])?$vv['name']:'';
                    $desc = isset($vv['desc'])?$vv['desc']:'';
                    $this->_arr_method[$kk] = array(
                        'name'=>$name,
                        'desc'=>$desc,
                        'params'=>$vv['params']
                    );

                    if(isset($vv['funcName'])) {
                        $temp = explode('@',$vv['funcName']);
                        $className = $temp[0];
                        $funcName = $temp[1];
                        $this->_arr_method[$kk]['className']=$className;
                        $this->_arr_method[$kk]['funcName']=$funcName;
                    }                    
                    continue;
                }
                //如果不是数组，说明是简单的接口说明
                echo "{$kk}接口的声明存在错误!";exit;                
            }
        }
        // consoleLog($api_json);
        // consoleLog(json_decode($api_json,true));

        // $this->_arr_method = array(
        //     'order.create'=>array('className'=>'Api_Order','funcName'=>'create')
        // );
    }
    /**
     * @desc ：调用api对应的方法，
     * @author jeff 2015/09/21 15:55:03
     * @param 参数类型
     * @return json
    */
    function response() {        
        $method = isset($_POST['method'])?$_POST['method']:'';      
        if(!$method) {
            $this->error('$_POST["method"] not found',false);
            return false;
        }
        //开始正式访问api方法，
        $params = isset($_POST['params'])?$_POST['params']:'';
        $isDebug = isset($_POST['isDebug']) ? $_POST['isDebug'] : false;
        if(!$params) {
            $this->error('$_POST["params"] can not be empty',false);
            return false;
        }
        if(!isset($params['token'])) {
            $this->error('$_POST["params"]["token"] not found',false);
            return false;
        }
        if($params['token']!=md5($this->_json['token'])) {
            $this->error('token not match',false);
            return false;
        }
        if(!$this->_checkParams($params)) {
            $this->error('params is not avlidate',false);
            return false;
        }
        if(!isset($this->_arr_method[$method])) {
            $this->error('api method is not announced',false);
            return false;
        }

        //调试模式下输出传入参数
        if($isDebug) {
            echo "<b>post params:</b><br />";
            dump($_POST);
        }
        $class = FLEA::getSingleton($this->_arr_method[$method]['className']);
        if(!$class) {
            $this->error("{$this->_arr_method[$method]['className']} not found",false);
            return false;
        }        

        $this->_method = $method;
        $this->_params = $params;

        if(!isset($this->_arr_method[$method])) {
            $this->error("{$method} is not defined!",false);
            return false;
        }

        $funcName = $this->_arr_method[$method]['funcName'];
        if(!method_exists($class, $funcName)) {
            $this->error("{$this->_arr_method[$method]['className']}@{$funcName} not exists!",false);
            return false;
        }
        // $this->error($this->_arr_method[$method]['className']);
       
        $ret = $class->$funcName($params);

        //调试模式下输出返回结果
        if($isDebug) {            
            echo "<b>api return:</b><br />";
            dump($ret);
            exit;
        }
        if(!$ret) {
            $this->error('the value returned can not be null',false);
            return false;
        }
        if(!$ret['success']) {
            $this->_success = false;
            $this->_msg = $ret['msg'];
            $this->error($ret['msg'],true);//抛出错误信息，并写日志
            return false;
        }
        $this->_success = true;
        $this->_msg = json_encode($ret['data']);
        $this->success($ret['data']);//输出response，并写日志
        return true;
    }

    //抛出错误信息
    /**
     * @desc ：判出错误信息
     * @author jeff 2015/09/21 15:55:03
     * @param msg:错误提示
     * @param bWritelog:是否记录日志,系统级别的错误都不需要写日志
     * @return 返回值类型
    */
    function error($msg,$bWritelog=true) {
        if($bWritelog) {
            $this->writeLog();
        }
        //先写日志，然后返回
        // $msg = urlencode($msg); 
        // $msg = json_decode(json_encode($msg));         
        $ret = array(
            'rsp'   =>  false,
            // 'res'   =>  $code,
            'data'  =>  array('msg'=>$msg),
        ); 
        echo json_encode($ret);
        exit;
    }

    //成功返回
    function success($data,$bWritelog=true) {
        if($bWritelog) {
            $this->writeLog();
        }
        //先写日志，然后返回
        $ret = array(
            'rsp'   =>  true,
            // 'res'   =>  $code,
            'data'  =>  $data
        );
        echo json_encode($ret);
        exit;
    }

    /**
     * @desc ：检查参数是否正确,这个方法一般需要在子类中进行重写，因为参数的合法性可能包含业务逻辑
     * @author jeff 2015/09/21 15:55:03
     * @param 参数类型
     * @return 返回值类型
    */
    function _checkParams($params) {
        return true;
    }

    /**
     * @desc ：写日志
     * @author jeff 2015/09/21 15:55:03
     * @param 参数类型
     * @return 返回值类型
    */
    function writeLog() {
        $row = array(
            'type'=>0,//0表示响应
            'success'=>$this->_success?true:false,
            'apiName'=>$this->_method,
            'url'=>$_SERVER['REQUEST_URI'],
            'params'=>json_encode($this->_params),
            'msg'=>$this->_msg,            
            'calltime'=>date('Y-m-d H:i:s'),
            'retrytime'=>'',
        );
        //在控制台中输出变量，需要和chromephp配合 ，具体安装和使用请参考170/phpwind中的帖子
        // consoleLog("保存日志");
        $model = & FLEA::getSingleton('Model_Api_Log');
        // consoleLog($row);
        $model->save($row);
        return true;        
    }

    /**
     * @desc ：得到签名，暂时不考虑
     * @author jeff 2015/09/21 15:55:03
     * @param 参数类型
     * @return 返回值类型
    */
    private function genSign($params, $token) {
        if (!$token) {
            return false;
        }
        return strtoupper(md5(strtoupper(md5($this->assemble($params))) . $token));
    }

    //设置accesstoken,暂时先不考虑
    protected function set_accesstoken($member_id) {
        if (empty($member_id)) {
            return falase;
        }
        kernel::single("base_session")->start();
        $member_ident = kernel::single("base_session")->sess_id();
        $sess_id = md5($member_ident . 'api' . $member_id);
        kernel::single("base_session")->set_sess_id($sess_id);
        return $sess_id;
    }

    /**
     * @time 2015-07-14
     * @author Mark 
     * @param type $accesstoken
     * @param type $member_id 现在不要member_id 2015/7/28 20:40
     * @return boolean
     */
    protected function check_accesstoken($accesstoken) {
        if (empty($accesstoken)) {
            return false;
        }
        $_GET['sess_id'] = $accesstoken;
        kernel::single("base_session")->start();
        $userObject = kernel::single('b2c_user_object');
        $member_id = $userObject->get_member_id();
        return $member_id;
    }
}