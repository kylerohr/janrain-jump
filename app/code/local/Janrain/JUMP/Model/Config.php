<?php
class Janrain_JUMP_Model_Config extends Mage_Core_Model_Abstract {
	function __construct() {
		parent::__construct();
	}

	function getConfig() {
		return array('clientId' => '',
					 'captureName' => '',
					 'engageName' => '',
					 'captureAppId' => '',
					 'tokenUrl' => '');
	}
}