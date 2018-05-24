<?php
class Controller_Crontab{
	function __construct() {
        $this->_modelExample = FLEA::getSingleton('Model_CrontabLog');
	}

    //执行保存log
    function actionLogs(){
        require "crontab_list.php";
        // dump($crontab_list);exit;

        foreach ($crontab_list as $key => & $v) {
            if(!$v['address'])continue;
            $this->add($v);
        }

        $this->DoCrontab();
    }

    //开始添加
    function add($params = array()){
        //查找是否已经保存在表中
        $row = $this->_modelExample->find(array('address'=>$params['address'],'result'=>'未执行'));
        $_id = $row['id'];
        
        //如果之前保存的信息未执行，则需要等待执行后再更新下次执行时间
        if($row['result'] != '未执行'){
            $cur_time = time();
            $params['time'] = (int)$params['time'];
            $params['time']<=0 && $params['time']+=1;

            $arr = array(
                'id'=>$_id.'',
                'result'=>'未执行',
                'desc'=>$params['desc'],
                'address'=>$params['address'],
                'isActive'=>$params['isActive'],
                'time'=>$params['time'],
                'runtime'=>$cur_time + ($params['time']*60),
                'createtime'=>$cur_time,
            );
            // dump($arr);exit;
            $this->_modelExample->save($arr);
        }
        
    }

    //执行计划
    function DoCrontab(){
        $condition['result'] = '未执行';
        $condition['isActive'] = '1';
        $condition[] = array('runtime',time(),'<=');
        
        $rows = $this->_modelExample->findAll($condition);
        
        //开始执行
        foreach ($rows as $key => & $v) {
            list($controller ,$action) = explode('@',$v['address']);
            $class = FLEA::getSingleton($controller);

            if(!method_exists($class, $action)) {
                echo $action."在类".$controller."中不存在";
                continue;
            }
            $class->$action();

            //更新结果
            $arr = array(
                'id'=>$v['id'],
                'result'=>'已执行',
            );
            $this->_modelExample->update($arr);
        }
        
    }
}
?>