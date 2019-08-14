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
 * Attachments default helper
 *
 * @category    Web4pro
 * @package     Web4pro_Stockstatus
 * @author      WEB4PRO <srepin@corp.web4pro.com.ua>
 */

class Web4pro_Stockstatus_Block_Catalog_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{
    /**
     * Composes configuration for js
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $config = parent::getJsonConfig();
        $config = Mage::helper('core')->jsonDecode($config);
        $stockstatuses = array();
        $helper = Mage::helper('web4pro_stockstatus');
        $_product = $this->getProduct();
        $ids = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($_product->getId());
        $_subproducts = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('entity_id', $ids)
            ->addAttributeToSelect('*');
        foreach ($_subproducts as $product){
            $stockstatuses[$product->getId()]["stockstatus"] = $helper->getNewStockStatus($product);
            $config['stockstatuses'] = $stockstatuses;
            $config['availability'] = $helper->__('Availability:');
        }
        return Mage::helper('core')->jsonEncode($config);
    }
}