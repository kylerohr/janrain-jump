<?php
class Janrain_JUMP_Model_Config extends Mage_Core_Model_Abstract {

	function __construct() {
		parent::__construct();
	}

	function getConfig() {
		return array('capture.clientId' => '6ktpqgv775wk7grhs5gc6k26z22khx6t',
					 'capture.captureServer' => 'https://byron.dev.janraincapture.com',
					 'capture.loadJsUrl' => 'd16s8pqtk4uodx.cloudfront.net/byron-janrain/load.js',
					 'capture.appId' => '6jreb2yub54ekd3f3a4vymx8wh',
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
