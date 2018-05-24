<?php
load_class('FLEA_Db_TableDataGateway');
class Model_CrontabLog extends FLEA_Db_TableDataGateway {
	var $primaryKey = 'id';
	var $tableName = 'crontab_log';

}
?>