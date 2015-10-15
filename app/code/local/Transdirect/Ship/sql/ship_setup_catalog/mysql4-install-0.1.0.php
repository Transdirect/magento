<?php
require_once('app/Mage.php');
$installer = $this;
// $installer->startSetup();
$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');

// $setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$installer->startSetup();

$this->addAttribute('catalog_product', 'item_height', array(
        'backend'       => '',
        'source'        => '',
        'entity_model'  => 'catalog/product',
        'label'         => 'Item Height',
        //'group'           => 'ItemSize',
        'group'         => 'For Shipping Item Information',
        'input'         => 'text',
        'type'          => 'text',
        'is_html_allowed_on_front' => true,
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'user_defined'  => false,
        'default'       => '',
        'visible_on_front' => true
    ));

$this->addAttribute('catalog_product', 'item_width', array(
        'backend'       => '',
        'source'        => '',
        'entity_model'  => 'catalog/product',
        'label'         => 'Item Width',
        //'group'           => 'ItemSize',
        'group'         => 'For Shipping Item Information',
        'input'         => 'text',
        'type'          => 'text',
        'is_html_allowed_on_front' => true,
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'user_defined'  => false,
        'default'       => '',
        'visible_on_front' => true
    ));

$this->addAttribute('catalog_product', 'item_dim', array(
        'backend'       => '',
        'source'        => '',
        'entity_model'  => 'catalog/product',
        'label'         => 'Item Length',
        //'group'           => 'ItemSize',
        'group'         => 'For Shipping Item Information',
        'input'         => 'text',
        'type'          => 'text',
        'is_html_allowed_on_front' => true,
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'user_defined'  => false,
        'default'       => '',
        'visible_on_front' => true
    ));

$this->addAttribute('catalog_product', 'item_weight', array(
        'backend'       => '',
        'source'        => '',
        'entity_model'  => 'catalog/product',
        'label'         => 'Item Weight',
        //'group'           => 'ItemSize',
        'group'         => 'For Shipping Item Information',
        'input'         => 'text',
        'type'          => 'text',
        'is_html_allowed_on_front' => true,
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'user_defined'  => false,
        'default'       => '',
        'visible_on_front' => true
    ));

$installer->endSetup();