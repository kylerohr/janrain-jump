<?php
require_once Mage::getBaseDir('lib') . '/janrain/PsrAutoloader.php';

class Janrain_JUMP_Model_Customer extends Mage_Customer_Model_Customer {

	function __construct() {
		parent::__construct();
	}

	function getUuid() {
		$customer_id = $this->getId();

    // @TODO: Flesh out getUuid()
	}

	function save() {
		parent::save();

    // @TODO: Add save overrides
	}
}
