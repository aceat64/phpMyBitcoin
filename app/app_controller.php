<?php

class AppController extends Controller {
	var $components = array('Auth','Session');

	function beforeFilter() {
		Security::setHash('sha256'); // Bitcoin uses sha256 so why would we use something different :)

		if (sizeof($this->uses) && $this->{$this->modelClass}->Behaviors->attached('Logable') ) {
			$this->{$this->modelClass}->setUserData($this->Auth->user());
			$this->{$this->modelClass}->setUserIp($_SERVER['REMOTE_ADDR']);
		}
	}
}
