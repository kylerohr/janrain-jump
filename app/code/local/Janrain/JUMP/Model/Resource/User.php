<?php

class Janrain_JUMP_Model_Resource_User extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('janrain_jump/user', 'magento_id');
    }
}
