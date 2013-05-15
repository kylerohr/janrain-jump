<?php
namespace janrain;
class PsrLoader {

	public function loadClass($className) {
				
	}

	private static $instance;
	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
