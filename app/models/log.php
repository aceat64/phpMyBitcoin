<?php
class Log extends AppModel {
	var $name = 'Log';
	var $displayField = 'title';
	var $order = 'created DESC';
	var $belongsTo = array('User');
}
?>
