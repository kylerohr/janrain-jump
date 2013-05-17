<?php
spl_autoload_register(
	function ($className) {
		if ('janrain' == strstr($className, '\\', true)) {
			require_once dirname(__DIR__) . '/' . str_replace('\\', '/', $className) . '.php';
		}
	}, true, true);
