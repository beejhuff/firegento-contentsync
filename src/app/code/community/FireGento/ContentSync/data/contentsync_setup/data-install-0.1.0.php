<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

// set default directories
$installer->setConfigData(
    'contentsync/storage_file/directory',
    Mage::getBaseDir('app') . DS . 'content' . DS
);

$installer->endSetup();
