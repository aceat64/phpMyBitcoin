<?php 
/* SVN FILE: $Id$ */
/* App schema generated on: 2010-08-31 12:08:10 : 1283276950*/
class AppSchema extends CakeSchema {
	var $name = 'App';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $cake_sessions = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'data' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'expires' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $logs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'description' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'ip' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'model_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'action' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'change' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'created' => array('column' => 'created', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $nodes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 64),
		'hostname' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'port' => array('type' => 'integer', 'null' => false, 'default' => '8332', 'length' => 5),
		'uri' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 64),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 64),
		'version' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'balance' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '8,2'),
		'blocks' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'connections' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'generate' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'hashespersec' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'pending_blocks' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'generated_blocks' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'last_update' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
}
?>