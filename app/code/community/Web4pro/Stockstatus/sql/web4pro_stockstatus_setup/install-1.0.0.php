<?php
/**
 * WEB4PRO - Creating profitable online stores
 * 
 * @author WEB4PRO <srepin@corp.web4pro.com.ua>
 * @category  WEB4PRO
 * @package   Web4pro_Stockstatus
 * @copyright Copyright (c) 2015 WEB4PRO (http://www.web4pro.net)
 * @license   http://www.web4pro.net/license.txt
 */
/**
 * Stockstatus module install script
 *
 * @category    Web4pro
 * @package     Web4pro_Stockstatus
 * @author      WEB4PRO <srepin@corp.web4pro.com.ua>
 */
$this->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->addAttribute('catalog_product', 'custom_stockstatus', array(
    'group'         				=> 'Custom Stock Status',
    'input'         				=> 'select',
    'type'          				=> 'text',
    'label'         				=> 'Custom Stock Status Message',
    'backend'       				=> '',
    'frontend'						=> '',
    'visible'       				=> true,
    'required'      				=> false,
    'user_defined' 					=> true,
    'searchable' 					=> false,
    'filterable' 					=> false,
    'comparable'    				=> false,
    'visible_on_front' 				=> false,
    'used_in_product_listing'       => true,
    'visible_in_advanced_search'  	=> false,
    'is_html_allowed_on_front' 		=> false,
    'global'        				=> Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'note'                          => 'Add messages by going to <b>Catalog->Attributes->Manage Attributes</b> and clicking on the <b>\'custom_stockstatus\'</b> attribute.'
));

$setup->addAttribute('catalog_product', 'hide_default_stockstatus', array(
    'group'         	=> 'Custom Stock Status',
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Hide Default Stock Status',
    'input'             => 'select',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'is_configurable'   => false,
    'used_in_product_listing'   => true
));


$this->endSetup();
