<?php
//Set default options. jumpUrl in Magento configuration
$mageConfig = Mage::getModel('core/config');
$mageConfig->saveConfig('jump/capture_settings/jumpUrl', 'customer/account');

// Add Janrain table
$installer = $this;

$installer->startSetup();

$connection = $installer->getConnection();
$connection->dropTable('janrain_jump');
$table = $connection->newTable($installer->getTable('janrain_jump'));
#add the fake id, so we can use default model loading/saving semantics
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
$table->addColumn(
    'profile',
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
