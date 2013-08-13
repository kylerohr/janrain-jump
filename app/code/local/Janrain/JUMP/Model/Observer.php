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
        $config = Mage::helper('janrain_jump');
        $config->setItem('features', array('Core', 'CaptureApi', 'Capture'));
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
        //$magentoConfig = Mage::getConfig();
        //$adminRoute = new Mage_Core_Model_Config_Element('<janrain_jump><use>admin</use><args><module>Janrain_JUMP</module><frontName>jump</frontName></args></janrain_jump>');
        //$magentoConfig->getNode('admin/routers')->appendChild($adminRoute);
        #setup admin ACL for admin config
        $magentoConfig = Mage::getConfig();
        $aclNode = new Mage_Core_Model_Config_Element('<jump translate="title" module="janrain_jump">Janrain</jump>');
        $confAclNode = $magentoConfig->getNode('adminhtml/acl/resources/admin/children/system/children/config/children');
        $confAclNode->appendChild($aclNode);
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
        $route = Mage::app()->getFrontController()->getRequest()->getRouteName();
        if ('adminhtml' == $route) {
            return;
        }
        $layout = $observed->getLayout();
        $update = $layout->getUpdate();
        $updateInstruction = '<reference name="head"><block type="janrain_jump/header" name="header" before="-"/></reference>';
        $updateInstruction .= '<reference name="content"><block type="janrain_jump/widget" name="widget" before="-"/></reference>';
        $update->addUpdate($updateInstruction);

    }


    public function adminhtml_init_system_config($observed)
    {
        /*$conf = Mage::getConfig();
        $conf = $conf->getNode('adminhtml/acl/resources/admin/children/system/children/config/children');
                print_r($conf);
        exit();*/

        //var_dump($observed);
        $adminConfig = $observed->getConfig();
        #setup config section
        $jumpTab = new Mage_Core_Model_Config_Element('<janrain translate="label" module="janrain_jump"><label>Janrain</label><sort_order>250</sort_order></janrain>');
        $adminConfig->getNode('tabs')->appendChild($jumpTab);
        /*$jump = \janrain\Jump::getInstance();
        $api = $jump->getFeature('CaptureApi');
        var_dump($api('entity',
            array(
                'type_name' => 'user',
                'attribute_name' => '/statuses',
                'uuid' => 'e2c9174b-0904-4757-a15a-f3d8d149983a'
                )));
        $schema = $api('entityType', array('type_name' => 'user'));
        $atts = array();
        echo '<pre>';
        foreach ($schema['schema']['attr_defs'] as $attr) {
            if ($attr['type'] == 'plural') {
                $atts[] = $attr['name'] . '*';
            } else {
                $atts[] = $attr['name'];
            }
        }
        echo '<pre>', print_r($atts, true);
        exit();*/
        //echo '<pre>',print_r($adminConfig->getNode('tabs'), true);
        //exit();
        //$adminConfig->getNode('tabs')->appendChild($jumpTab);
        //echo '<pre>', print_r($adminConfig->getNode('tabs'), true);
        //var_dump($magentoConfig);
        //exit();
        //$magentoConfig->getNode('system')->appendChild($optionsSecion);
    }

    public function customer_save_commit_after($observed)
    {
        $jump = \janrain\Jump::getInstance();
        if (!$jump->getFeature('CaptureApi')) {
            return;
        }
        $model = $observed->data_object;
        $plexer = Mage::getModel('janrain_jump/user');
        $plexer->load($model->getId(), 'plex_id');
        $api = \janrain\Jump::getInstance()->getFeature('CaptureApi');
        if ($api) {
            $jumper = $api->fetchUserByUuid($plexer->getJumpId());
            $json = Mage::getStoreConfig('jump/mapping/json');
            if ($json) {
                $trans = \janrain\jump\data\Transform::loadFromJson($json);
                $trans->map($jumper, $plexer);
                $plexer->save();
            }
        }
    }
}
