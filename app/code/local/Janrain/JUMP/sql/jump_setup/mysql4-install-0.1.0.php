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
$connection = $installer->getConnection();
$connection->dropTable('janrain_jump');
$table = $connection->newTable($installer->getTable('janrain_jump'));
#add the fake, so we can use
$table->addColumn(
    'magento_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'primary' => true, 'nullable' => false, 'auto_increment' => true));
$table->addColumn(
    'plex_id',
    Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array('unsigned' => true, 'nullable' => false));
$table->addColumn(
    'jump_id',
    Varien_Db_Ddl_Table::TYPE_CHAR, 40, array('nullable' => false));
$table->addColumn(
    'engage_ids',
    Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => true));

#add unique indexes
$table->addIndex('plex_id', 'plex_id', array('unique'));

#lookups done by capture use jump_id
$table->addIndex('jump_id', 'jump_id', array('unique'));

#lookups done by engage use a substring of engageid
$table->addIndex('engage_ids', array(array('name' => 'engage_ids', 'size' => Varien_Db_Ddl_Table::DEFAULT_TEXT_SIZE)), array('unique'));

#if the customer is deleted, so should all of their social mappings
$table->addForeignKey('customer_id', 'plex_id', 'customer_entity', 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE);

$this->getConnection()->createTable($table);

$installer->endSetup();
