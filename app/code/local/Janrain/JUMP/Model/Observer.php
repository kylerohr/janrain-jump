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
        //$session = Mage::getSingleton('customer/session');
        //$loggedIn = $session->isLoggedIn();
        $jump = \janrain\Jump::getInstance();
        $jump->init($config);

        #setup routes
        $magentoConfig = Mage::getConfig();
        $adminRoute = new Mage_Core_Model_Config_Element('<janrain_jump><use>admin</use><args><module>Janrain_JUMP</module><frontName>jump</frontName></args></janrain_jump>');
        $magentoConfig->getNode('admin/routers')->appendChild($adminRoute);
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
