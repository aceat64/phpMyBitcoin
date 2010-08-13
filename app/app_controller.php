<?php

class AppController extends Controller {
	var $components = array('Auth','Session');

	function beforeFilter() {
		Security::setHash('sha256'); // Bitcoin uses sha256 so why would we use something different :)
	}
}
