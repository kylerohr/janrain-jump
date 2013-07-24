<?php
require_once 'Mage/Customer/controllers/AccountController.php';

use janrain\Jump;
use janrain\plex\data\AssignFromJump;
use janrain\plex\data\Transform;
use janrain\plex\Platform;
use janrain\jump\User as Jumper;

class Janrain_JUMP_AccountController extends Mage_Customer_AccountController implements Platform
{
    public function registerCustomerFromJumper(Jumper $jumper)
    {
        $plexer = Mage::getModel('janrain_jump/user');
        $plexer->registerAs($jumper);

        #magento requires first name and last name
        if (empty($plexer['firstname'])) {
            $plexer['firstname'] = strstr(trim($jumper['displayName']), ' ', true);
        }
        if (empty($plexer['lastname'])) {
            $plexer['lastname'] = 'Janrain-Capture';
        }

        #finish
        try {
            $plexer->save();
        } catch (\Exception $e) {
            Mage::register('isSecureArea', true);
            $plexer->getCustomer()->delete();
            Mage::unregister('isSecureArea');
            throw $e;
        }
        return $plexer->getCustomer();
    }

    protected function loginCustomer($customer)
    {
        $session = $this->_getSession();
        $session->loginById($customer->getId());
        if ($session->getCustomer()->getIsJustConfirmed()) {
            $this->_wecomeCustomer($session->getCustomer(), true);
        }
        $this->_loginPostRedirect();
    }

    public function preDispatch()
    {
        parent::preDispatch();

        $req = $this->getRequest();
        $do = $req->getParam('do');
        $jump = \janrain\Jump::getInstance();

        if (empty($do)) {
            #no janrain actions called, "you didn't see anything"
            return;
        }

        switch ($do) {
        case 'login':
            $api = $jump->getFeature('Capture')->getApi();
            $jumper = $api->fetchUserByUuid($req->getParam('uuid'), $req->getParam('token'));
            $plexer = $this->fetchPlexerForJumper($jumper);
            if ($plexer) {
                $customer = $plexer->getCustomer();
            } else {
                #no mapping, try to register
                $customer = $this->registerCustomerFromJumper($jumper);
            }
            if ($customer) {
                $this->loginCustomer($customer);
            } else {
                #login and registration failed.
                throw new \Exception();
            }
            break;
        default:
            throw new \Exception();
        }
    }

    /**
     * Overriden to prepopulated customer creation fields.  Only modify data where marked.
     */
    public function createAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*');
            return;
        }
        $this->loadLayout();

        ##### START MODIFICATION AREA #####
        $session = Mage::getSingleton('customer/session');
        if ($jumper = $session->getJanrainJUMP()) {
            $formData = $this->getLayout()->getBlock('customer_form_register')->getFormData();

            if (isset($jumper->verifiedEmail)) {
                #use verified email if possible
                $formData->setEmail($jumper->verifiedEmail);
            } elseif (isset($jumper->email)) {
                #fallback to email
                $formData->setEmail($jumper->email);
            }
            if (isset($jumper->givenName)) {
                $formData->setFirstname($jumper->givenName);
            }
            if (isset($jumper->familyName)) {
                $formData->setLastname($jumper->familyName);
            }
        }
        ##### END MODIFICATION AREA #####

        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

##### janrain\plex\Platform implementation #####

    public function fetchPlexerForJumper(Jumper $j)
    {
        #load plexer
        $plexer = Mage::getModel('janrain_jump/user');
        if (!$plexer->load($j->getId(), 'jump_id')->getId()) {
            #no plexer for this jumper
            return null;
        }
        #we have a plexer, so get the customer info
        //var_dump($plexer->getCustomer());
        return $plexer;
    }

    public function getLocale()
    {
        return Mage::app()->getLocale()->getLocaleCode();
    }

    public function getConfig()
    {
        return Mage::helper('janrain_jump/data')->getConfig();
    }
}
