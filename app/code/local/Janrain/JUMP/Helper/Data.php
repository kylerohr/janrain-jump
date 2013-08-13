<?php
use janrain\plex\Config;

class Janrain_JUMP_Helper_Data extends Mage_Core_Helper_Abstract implements Config
{
    protected $data;

    public function __construct()
    {
        $this->data = array();
        $storeConf = Mage::getStoreConfig('jump');
        foreach ($storeConf as $k => &$v) {
            $this->data += $v;
        }
        $this->data['jumpUrl'] = Mage::getBaseUrl() . '/customer/account/';
    }

    public function setItem($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getItem($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function toJson()
    {
        return json_encode($this->data);
    }
}
