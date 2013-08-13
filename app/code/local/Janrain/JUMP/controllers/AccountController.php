<?php
require_once 'Mage/Customer/controllers/AccountController.php';

use janrain\Jump;
use janrain\jump\data\AssignFromJump;
use janrain\jump\data\Transform;
use janrain\plex\Platform;
use janrain\jump\User as Jumper;
use janrain\plex\User as Plexer;

class Janrain_JUMP_AccountController extends Mage_Customer_AccountController implements Platform
{
    protected $jump;

    protected function _construct()
    {
        $this->jump = Jump::getInstance();
    }

    public function preDispatch()
    {
        parent::preDispatch();

        $req = $this->getRequest();
        $do = $req->getParam('do');

        if (empty($do)) {
            #no janrain actions called, "you didn't see anything"
            return;
        }

        switch ($do) {
        case 'login':
            $api = $this->jump->getFeature('CaptureApi');
            #call api to load janrain user
            $jumper = $api->fetchUserByUuid($req->getParam('uuid'), $req->getParam('token'));
            if (!$jumper) {
                throw new \InvalidArgumentException('Capture User not found!');
            }
            $plexer = $this->fetchPlexUser($jumper);
            if ($plexer) {
                $customer = $plexer->getCustomer();
            } else {
                #no mapping, try to register
                $plexer = $this->registerJumpUser($jumper);
                $customer = $plexer->getCustomer();
            }
            if ($customer) {
                $this->loginPlexUser($plexer);
            } else {
                #login and registration failed.
                throw new \Exception('login/registration failed!');
            }
            break;
        default:
            throw new \Exception("unmatched do! \"$do\"");
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
    public function registerJumpUser(Jumper $j) {
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
        return $plexer;
    }

    public function loginPlexUser(Plexer $p)
    {
        $json = Mage::getStoreConfig('jump/mapping/json');
        if ($json) {
            $trans = Transform::loadFromJson($json);
            $trans->map($p->getJumpUser(), $p);
        }
        $customer = $p->getCustomer();
        $session = $this->_getSession();
        $session->loginById($customer->getId());
        if ($session->getCustomer()->getIsJustConfirmed()) {
            $this->_wecomeCustomer($session->getCustomer(), true);
        }
        $captureSession = (object) array(
            'token' => $this->getRequest()->getParam('token'),
            'expires' => time() + 50 * 60);
        $p->setSessionItem('janrain_jump.session', $captureSession);
        $p->setAttribute('profile', json_encode($p->getJumpUser()->getProfileData()));
        $p->save();
        $this->_loginPostRedirect();
    }

    public function fetchPlexUser(Jumper $j)
    {
        static $cachedPlexer;

        if (is_null($cachedPlexer)) {
            #load plexer
            $plexer = Mage::getModel('janrain_jump/user');
            if (!$plexer->load($j->getAttribute('/uuid'), 'jump_id')->getId()) {
                #no plexer for this jumper
                return null;
            }
            #we have a plexer, so get the customer info
            //var_dump($plexer->getCustomer());
            $cachedPlexer = $plexer;
            $plexer->setJumpUser($j);
        }
        return $cachedPlexer;
    }

    public function getLocale()
    {
        return Mage::app()->getLocale()->getLocaleCode();
    }

    public function getConfig()
    {
        return Mage::helper('janrain_jump/config');
    }
}
