<?php
// Set jumpUrl in Magento configuration
Mage::getModel('core/config')->saveConfig('jump/capture_settings/jumpUrl', 'janrain/jump');

// Add Janrain table
$installer = $this;
 
$installer->startSetup();


