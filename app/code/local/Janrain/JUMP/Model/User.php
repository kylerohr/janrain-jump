<?php

use janrain\plex\User as Plexer;
use janrain\jump\User as Jumper;
use janrain\plex\data\Transform;

class Janrain_JUMP_Model_User extends Mage_Core_Model_Abstract implements Plexer
{

    protected $customer;
    protected $jumper;

    protected function _construct()
    {
        $this->_init('janrain_jump/user');
    }

    /**
     * This is effectively a constructor-call, since Magento doesn't call _construct for
     * items loaded from the db.
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        if ($plexId = $this->getData('plex_id')) {
            $this->customer = Mage::getModel('customer/customer')->load($plexId);
        } else {
            $this->customer = Mage::getModel('customer/customer');
        }
    }

    public function setJumpUser(Jumper $j)
    {
        $this->jumper = $j;
    }

    public function getJumpUser()
    {
        return $this->jumper;
    }

    /**
     * Get the customer represented by this Plex User
     *
     * @return Mage_Customer_Model_Customer
     *   The native customer represented by this Plex User
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    public function setAttribute($path, $value)
    {
        $this->setData($path, $value);
    }
    public function getAttribute($path)
    {
        return $this->getData($path);
    }
    public function hasAttribute($path)
    {
        return array_key_exists($this->customer->getAttributes());
    }

    public function getAttributePaths()
    {
        return array_keys($this->customer->getAttributes());
    }

    /**
     * @inheritsDoc
     *
     * Sets data on the underlying customer.  Will attempt to set Plex User data first.
     */
    public function offsetSet($offset, $value)
    {
        $this->customer->setData($offset, $value);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->customer->getAttributes());
    }

    public function loginAs(Jumper $j)
    {
        #
    }



    public function registerAs(Jumper $j)
    {
        $this->setJumpId($j['uuid']);
        $jsonMap = Mage::getStoreConfig('jump/mapping/json');
        if ($jsonMap) {
            $tx = Transform::loadFromJson($jsonMap);
            $tx->map($j, $this);
        }
    }

    public function save()
    {
        $this->customer->save();
        $this->setPlexId($this->customer->getId());
        //$this->setJumpProfile($this->jumper->);
        parent::save();
    }

    public function getSessionItem($key, $default = null)
    {
        $session = Mage::getSingleton('customer/session');
        $out = $session->getData($key);
        if (!is_null($out)) {
            return $out;
        }
        return $default;
    }

    public function setSessionItem($key, $value)
    {
        if (!$this->getIsLoggedIn()) {
            throw new \Exception('');
        }
        $session = Mage::getSingleton('customer/session');
        $session->setData($key, $value);
    }

    public function getIsNew()
    {
        return $this->isNew();
    }

    public function getIsLoggedIn()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    public function getMappableFields()
    {
        return array_keys(Mage::getModel('customer/customer')->getAttributes());
    }
}
