<?php
require Mage::getBaseDir('lib') . '/janrain/PsrAutoloader.php';

class Janrain_JUMP_Model_Observer
{
    public function __construct()
    {
        Mage::log('Observer Code executed!');
    }

    public function cms_page_render()
    {
        //echo '<pre>';print_r(func_get_args());
        //die('boom!');
    }

    public function controller_front_init_before()
    {
        $config = Mage::helper('janrain_jump/data')->getConfig();
        $config['features'] = array('Core', 'Capture');
        $jump = \janrain\Jump::getInstance();
        $jump->init($config);
        $session = Mage::getSingleton('customer/session');
        if ($capture = $jump->getFeature('Capture')) {
            #refresh or destroy the session as needed
            $this->updateSession($jump);
            #tell capture about it!
            $capture->setConfigItem('capture.session', $session->getData('janrain_jump.session'));
        }

        #setup admin route
        $magentoConfig = Mage::getConfig();
        $adminRoute = new Mage_Core_Model_Config_Element('<janrain_jump><use>admin</use><args><module>Janrain_JUMP</module><frontName>jump</frontName></args></janrain_jump>');
        $magentoConfig->getNode('admin/routers')->appendChild($adminRoute);
    }

    protected function updateSession($jump)
    {
        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {
            #not logged into local, set the session to null so capture knows to void the token
            $session->setData('janrain_jump.session', null);
            return;
        }
        #user is logged in...  check for expired token
        $jumpSession = $session->getData('janrain_jump.session');
        $time = time();
        if (empty($jumpSession) || $time >= $jumpSession->expires) {
            #session expired, get new token
            $model = Mage::getModel('janrain_jump/user');
            $model->load($session->getCustomer()->getId(), 'plex_id');
            if ($model->getJumpId()) {
                #plexer found, rock it
                $jumpSession = $jump->getFeature('Capture')->getApi()->getToken($model->getJumpId());
            } else {
                #user logged in with a non-matching plexer? eep null out the capture session and force a new login
                $jumpSession = null;
            }
            $session->setData('janrain_jump.session', $jumpSession);
        }
    }

    public function controller_action_layout_generate_xml_before($observed)
    {
        $layout = $observed->getLayout();
        //echo '<pre style="text-align:left">';
        //print_r($layout);
        $update = $layout->getUpdate();
        //print_r($update);
        //$update = $observed->getLayout()->getUpdate();
        $updateInstruction = '<reference name="head"><block type="janrain_jump/header" name="header" before="-"/></reference>';
        $updateInstruction .= '<reference name="content"><block type="janrain_jump/widget" name="widget" before="-"/></reference>';
        $update->addUpdate($updateInstruction);

    }
}
