<?php
require_once Mage::getBaseDir('lib') . '/janrain/PsrAutoloader.php';

class Janrain_JUMP_Model_Mysql4_User_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

  protected function _construct() {
    $this->_init('jump/janrain_jump');
  }
}
