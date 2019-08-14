<?php
/**
 * WEB4PRO - Creating profitable online stores
 *
 * @author WEB4PRO <spoprigin@corp.web4pro.com.ua>
 * @category  WEB4PRO
 * @package   Web4pro_Stockstatus
 * @copyright Copyright (c) 2015 WEB4PRO (http://www.web4pro.net)
 * @license   http://www.web4pro.net/license.txt
 */
/**
 * Stockstatus module install script add image attribute to option
 *
 * @category    Web4pro
 * @package     Web4pro_Stockstatus
 * @author      WEB4PRO <spoprigin@corp.web4pro.com.ua>
 */
$this->startSetup();
$setup = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');

$setup->addAttribute('catalog_product', 'count_products', array(
    'group'         				=> 'Custom Stock Status',
    'input'         				=> 'text',
    'type'          				=> 'int',
    'label'         				=> 'Number of products',
    'backend'       				=> '',
    'frontend'					=> '',
    'default'                       		=> '',
    'visible'       				=> true,
    'required'      				=> false,
    'user_defined' 				=> true,
    'searchable' 				=> false,
    'class'                         		=> 'validate-zero-or-greater', 
    'filterable' 				=> false,
    'comparable'    				=> false,
    'visible_on_front' 				=> false,
    'used_in_product_listing'       		=> true,
    'visible_in_advanced_search'  		=> false,
    'is_html_allowed_on_front' 			=> false,
    'global'        				=> Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'note'                          		=> 'Minimum number of products in the stock for custom status appearance'
));

$setup->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'hide_default_stockstatus', 'note', 'Hide default stock status if custom status exists');

$setup->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'count_products', 'apply_to', implode(',', Mage::helper('web4pro_stockstatus')->getProductTypesForCount()));

$this->endSetup();
