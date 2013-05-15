<?php
namespace janrain\jump;

interface ConfigInterface {
	public function get($optName);
	public function set($optName, $value);
}