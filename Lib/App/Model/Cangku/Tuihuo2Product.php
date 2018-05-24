<?php
load_class('TMIS_Model_Cangku_Son');
class Model_Cangku_Tuihuo2Product extends TMIS_Model_Cangku_Son {
    var $tableName = 'cangku_chuku2product';
    var $primaryKey = 'id';

    //库存model标识符
    var $modelKucun = 'Model_Cangku_Kucun';
    //是否出库，如果是出库，数量新增时需要*-1
    var $isChuku = true;
    //子表记录中的主表映射名,用来取得主表记录
    var $mappingName = 'Ck';
    //日期字段,必须在主表中
    var $fldDate = 'chukuDate';
    //数量,金额字段,必须在子表中,且数量字段可以是多个
    var $fldCnt = array('cnt'=>'cnt','cntM'=>'cntM','cntJian'=>'cntJian');
    var $fldMoney = array('danjia'=>'danjia');
    //其他需要复制到库存表中的字段，主要是汇总字段(分主从表,字段名必须一致)，也可能是数量字段（不参与库存计算)
    var $fldCopyMain = array('kind','cangkuName','kuweiId');
    var $fldCopySon = array('productId','pihao','unit','isBaofei');

    var $belongsTo = array (
        array(
            'tableClass' => 'Model_Cangku_Chuku',
            'foreignKey' => 'chukuId',
            'mappingName' => 'Ck',
        ),

    );

    /**
     * 调用 create() 方法并且成功将数据存入数据库后引发 _afterCreateDb 事件
     * 新增之后希望自动更新申购明细中的caigou2proId，
     * 对应关系是：caigou_order2product的id和caigou_shengou2product的caigou2proId
     * @param array $row
     */
    function _afterCreateDb(& $row)
    {
        parent::_afterCreateDb($row);

        $this->_updateMDView($row);
    }
     /**
     * 调用 update() 方法并且成功将数据更新到数据库后引发 _afterUpdateDb 事件
     * 与新增相同的处理
     * @param array $row
     */
    function _afterUpdateDb(& $row)
    {
        parent::_afterUpdateDb($row);

        $this->_updateMDView($row);
    }

    //在保存之前将码单信息先保存
    function _updateMDView($row){
        $madan = &FLEA::getSingleton('Model_Cangku_Madan');
        $madan2ruku = &FLEA::getSingleton('Model_Cangku_Rc2Madan');
        //保存码单
        if(count($row['Madan'])>0) foreach ($row['Madan'] as & $v) {
            $_temp=$madan2ruku->find(array('chukuId'=>$row['id'],'madanId'=>$v['id']));
            $v['Madan'][0]['chukuId']=$row['id'];
            $v['Madan'][0]['id']=$_temp['id'];
            $id=$madan->save($v);
        }
        //删除码单  以保存的数据如果数量不存在  则说明需要删除
        foreach ($row['madan_clear'] as & $c) {
            $madan->removeByPkv($c);
        }
    }

    /**
     * 调用 remove() 或 removeByPkv() 方法后立即引发 _beforeRemoveDbByPkv 事件
     *
     * 调用 remove() 方法时，_beforeRemoveDbByPkv 事件出现在 _beforeRemove 事件之后。
     *
     * 如果要阻止 remove() 或 removeByPkv() 删除记录，
     * 该方法应该返回 false，否则返回 true。
     *
     * @param mixed $pkv
     *
     * @return boolean
     */
    //删除之前先删除码单信息
    function _beforeRemoveDbByPkv($pkv)
    {
        $madan2ruku = &FLEA::getSingleton('Model_Cangku_Rc2Madan');
        $madan = &FLEA::getSingleton('Model_Cangku_Madan');
        $ret=$madan2ruku->findAll(array('chukuId'=>$pkv));
        if(count($ret)>0) foreach ($ret as & $r) {
            $madan->removeByPkv($r['madanId']);
        }
        return true;
    }
}
?>