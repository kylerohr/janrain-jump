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

	function getTestConfig() {
		// Get config
		$config = Mage::getStoreConfig('jump/capture_settings/capture_app_id');
		return $config;
	}

	function setTestConfig($value) {
		// Set Config
		Mage::getModel('core/config')->saveConfig('jump/capture_settings/capture_app_id', $value);
	}
}
