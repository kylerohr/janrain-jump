<?php
/*
 * Resource model for Jump Users
 */
class Janrain_JUMP_Model_Resource_User_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('janrain_jump/user');
    }
}
