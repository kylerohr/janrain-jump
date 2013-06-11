<?php
// Set jumpUrl in Magento configuration
Mage::getModel('core/config')->saveConfig('jump/capture_settings/jumpUrl', 'janrain/jump');

// Add Janrain table
$installer = $this;

$installer->startSetup();

/**
 * from Byron: @todo declare primary key to be [uuid, customer_id] to enforce/enable CaptureExpress mapping and login
 * from Byron: @todo declare foreign key on jump.customer_id -> customer.customer_id so we can maintain referential integrity and update cascades.
 */

$table = new Varien_Db_Ddl_Table();
$table->setName($this->getTable('janrain_jump'));
$table->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10,
                  array('unsigned' => true, 'primary' => true));

$table->addColumn('uuid', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255);
$table->addIndex('uuid', 'uuid');

$this->getConnection()->createTable($table);

$installer->endSetup();
