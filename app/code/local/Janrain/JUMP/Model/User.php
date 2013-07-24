<?php

use janrain\plex\User as Plexer;
use janrain\jump\User as Jumper;
use janrain\plex\data\Transform;

class Janrain_JUMP_Model_User extends Mage_Core_Model_Abstract implements Plexer
{

    protected $customer;

    protected function _construct()
    {
        $this->_init('janrain_jump/user');
        $this->customer = Mage::getModel('customer/customer');
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
        $xfs = '[
            {"op":"AssignFromJump","j":"email","p":"email"},
            {"op":"AssignFromJump","j":"givenName","p":"firstname"},
            {"op":"AssignFromJump","j":"familyName","p":"lastname"}
            ]';
        $tx = Transform::loadFromJson($xfs);
        $tx->map($j, $this);
    }

    public function save()
    {
        $this->customer->save();
        $this->setPlexId($this->customer->getId());
        parent::save();
    }

    public function isLoggedIn()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    public function getMappableFields()
    {
        return array_keys(Mage::getModel('customer/customer')->getAttributes());
    }
}
