<?php
require Mage::getBaseDir('lib') . '/janrain/PsrAutoloader.php';

class Janrain_JUMP_Model_Observer
{
	public function __construct()
	{
		Mage::log('Observer Code executed!');
	}

	public function cms_page_render()
	{
		//echo '<pre>';print_r(func_get_args());
		//die('boom!');
	}
}
