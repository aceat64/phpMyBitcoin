<?php
class User extends AppModel {
	var $name = 'User';
	var $displayField = 'username';
	var $actsAs = array('Logable' => array(
		'userModel' => 'User',
		'userKey' => 'user_id',
		'change' => 'list', // options are 'list' or 'full'
		'description_ids' => TRUE // options are TRUE or FALSE
	));
	var $hasMany = array('Log');
	var $validate = array(
		'username' => array(
			'maxlength' => array(
				'rule' => array('maxlength',64),
				'message' => 'Username can not be longer then 64 characters',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'minlength' => array(
				'rule' => array('minlength',4),
				'message' => 'Username must be at least 4 characters',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Username can not be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Password can not be empty',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}
?>
