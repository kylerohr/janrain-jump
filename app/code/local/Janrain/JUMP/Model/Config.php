<?php
class Janrain_JUMP_Model_Config extends Mage_Core_Model_Abstract {

	function __construct() {
		parent::__construct();
	}

	function getConfig() {
		$out = array();
		$conf = Mage::getStoreConfig('jump');
		foreach ($conf as $k => &$v) {
			$out = array_merge($out, $v);
		}
		return $conf;
	}

	function getTestConfig() {
		// Get config
		$config = Mage::getStoreConfig('jump/capture_settings/capture.clientId');
		return $config;
	}

	function setTestConfig($value) {
		// Set Config
		Mage::getModel('core/config')->saveConfig('jump/capture_settings/capture.clientId', $value);
	}
}
