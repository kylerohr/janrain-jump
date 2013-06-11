<?php
require_once Mage::getBaseDir('lib') . '/janrain/PsrAutoloader.php';

class Janrain_JUMP_Model_User extends Mage_Core_Model_Abstract {

  function __construct() {
    $this->_init('janrain_jump/user');
  }
}
