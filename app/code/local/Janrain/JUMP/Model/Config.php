<?php
require_once Mage::getBaseDir('lib') . '/janrain/PsrAutoloader.php';

class Janrain_JUMP_Model_Config extends Mage_Core_Model_Abstract {

	function __construct() {
		parent::__construct();
	}

	function getConfigKeys() {
		return array(
			'capture.clientId',
			'capture.captureServer',
			'capture.appId',
			'jumpUrl',
			);
	}

	function getConfig() {
		$keys = $this->getConfigKeys();

		$config = array();

		foreach ($keys as $key) {
			$setting = Mage::getStoreConfig('jump/capture_settings/' . $key);

			$config[$key] = $setting ? $setting : '';
		}

		return $config;
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
