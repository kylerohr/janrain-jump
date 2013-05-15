<?php
namespace janrain\jump;

use \ArrayObject as ArrayObject;

class CaptureConfig extends ArrayObject implements CaptureConfigInterface {

	public function __construct(Array $data) {
		parent::__construct();
		$this['capture.clientId'] = $data['clientId'];
		$this['capture.name'] = $data['captureName'];
		$this['engage.name'] = $data['engageName'];
		$this['capture.id'] = $data['captureAppId'];
	}
	public function get($optionName) {
		if ($this->offsetExists($optionName)) {
			return $this[$optionName];
		}
		return null;
	}

	public function set($optionName, $value) {}
}