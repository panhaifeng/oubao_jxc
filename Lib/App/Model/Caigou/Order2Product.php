<?php
load_class('TMIS_TableDataGateway');
class Model_Caigou_Order2Product extends TMIS_TableDataGateway {
	var $tableName = 'caigou_order2product';
	var $primaryKey = 'id';
	var $belongsTo = array (
		array(
			'tableClass' => 'Model_Jichu_Product',
			'foreignKey' => 'productId',
			'mappingName' => 'Product',
            'p_primaryKey' => 'proCode',
		),
		array(
			'tableClass' => 'Model_Caigou_Order',
			'foreignKey' => 'caigouId',
			'mappingName' => 'Caigou'
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
    	$this->_updateSGView($row);
    }

    /**
     * 调用 update() 方法并且成功将数据更新到数据库后引发 _afterUpdateDb 事件
     * 与新增相同的处理
     * @param array $row
     */
    function _afterUpdateDb(& $row)
    {
    	$this->_updateSGView($row);
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
    function _beforeRemoveDbByPkv($pkv)
    {
        if($pkv){
        	$sqlsg="update caigou_shengou2product set caigou2proId='0' where caigou2proId='{$pkv}'";
    		$this->findBySql($sqlsg);
        }
        return true;
    }

    //在保存之前将采购明细Id保存到申购明细中
    function _updateSGView($row){
        if($row['shengou2proId']){
    		$sqlsg="update caigou_shengou2product set caigou2proId='{$row['id']}' where id in({$row['shengou2proId']})";
    		$this->findBySql($sqlsg);
        }
    }
}
?>