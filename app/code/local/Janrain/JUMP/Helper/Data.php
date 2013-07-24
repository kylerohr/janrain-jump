<?php

class Janrain_JUMP_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getConfig()
    {
        $arr = array();
        $mageConf = Mage::getStoreConfig('jump');
        foreach ($mageConf as $k => &$v) {
            $arr += $v;
        }
        $arr['jumpUrl'] = Mage::getBaseUrl() . '/customer/account/';
        return new \ArrayObject($arr);
    }

    public function registerJumper()
    {
        #
    }

    public function loginJumper()
    {
        #
    }
}
