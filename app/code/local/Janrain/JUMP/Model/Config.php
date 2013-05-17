<?php
class Janrain_JUMP_Model_Config extends Varien_Object {
	function __construct() {
		parent::__construct();
	}

	function getConfig() {
		return array('clientId' => '',
					 'captureName' => '',
					 'engageName' => '',
					 'captureAppId' => '');
	}
}