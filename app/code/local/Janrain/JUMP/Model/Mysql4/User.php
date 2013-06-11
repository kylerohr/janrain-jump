<?php
require_once Mage::getBaseDir('lib') . '/janrain/PsrAutoloader.php';

class Janrain_JUMP_Model_Mysql4_User extends Mage_Core_Model_Mysql4_Abstract {

  protected function _construct() {
    $this->_init('jump/janrain_jump', 'customer_id');
  }
}
