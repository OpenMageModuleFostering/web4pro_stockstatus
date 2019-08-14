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

$setup->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'count_products', 'apply_to', implode(',', Mage::helper('web4pro_stockstatus')->getProductTypesForCount()));

$this->endSetup();