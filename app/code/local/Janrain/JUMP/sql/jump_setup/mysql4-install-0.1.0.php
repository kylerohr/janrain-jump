<?php
// Set jumpUrl in Magento configuration
Mage::getModel('core/config')->saveConfig('jump/capture_settings/jumpUrl', 'janrain/jump');

// Add Janrain table
$installer = $this;
 
$installer->startSetup();

$table = new Varien_Db_Ddl_Table();
$table->setName($this->getTable('janrain_jump'));
$table->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, 
                  array('unsigned' => true, 'primary' => true));

$table->addColumn('uuid', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255);
$table->addIndex('uuid', 'uuid');

$this->getConnection()->createTable($table);

$installer->endSetup();
