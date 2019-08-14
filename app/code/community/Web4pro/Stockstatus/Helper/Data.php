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
class Web4pro_Stockstatus_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Get default stock status of product
     * @param $product
     * @return string
     */
    public function getDefaultStockStatus($product)
    {
        if($product->isAvailable()){
            $result = $this->__('In stock');
        }
        else{
            $result = $this->__('Out of stock');
        }
        return $result;
    }


    /**
     * Get custom stock status of product
     * @param $product
     * @return string
     */
    public function getCustomStockStatus($product)
    {
        $customStockStatus = '';
        if ($product->isAvailable() || $this->isCustomStockStatusOnOutOfStock()){
            $customStockStatus = $product->getAttributeText('custom_stockstatus');
        }
        return $customStockStatus;
    }


    /**
     * Get new stock status based on default and custom
     * @param object $product
     * @return string
     */
    public function getNewStockStatus($product)
    {
        $defaultStockStatus = $this->getDefaultStockStatus($product);
        $customStockStatus = $this->getCustomStockStatus($product);
        if ($this->isShowStockLevel() && $product->getStockItem()->getQty()) {
            $levelStockStatus = $this->__('%s in stock', (int)$product->getStockItem()->getQty());
            return $levelStockStatus . ' ' . $customStockStatus;
        }
        if ($this->isDefaultStockStatusHidden($product)){
            return $customStockStatus;
        } else {
            return $defaultStockStatus . ' ' . $customStockStatus;
        }
    }


    /**
     * Check whether to hide a ddefault status
     * @param object $product
     * @return mixed
     */
    public function isDefaultStockStatusHidden($product)
    {
        return $product->getData('hide_default_stockstatus');
    }

    /**
     * Is the custom status allowed at page of category
     * @return mixed
     */
    public function isCustomStockStatusOnProductListPage()
    {
        return Mage::getStoreConfig('web4pro_stockstatus/general/display_at_categoty');
    }

    /**
     * Is the custom status allowed at page of shoping cart
     * @return mixed
     */
    public function isCustomStockStatusInShoppingCart()
    {
        return Mage::getStoreConfig('web4pro_stockstatus/general/display_in_cart');
    }

    /**
     * Is the custom status allowed for Out of stock products
     * @return mixed
     */
    public function isCustomStockStatusOnOutOfStock()
    {
        return Mage::getStoreConfig('web4pro_stockstatus/general/outofstock');
    }

    /**
     * is stock qty displayed
     * @return mixed
     */
    public function isShowStockLevel()
    {
        return Mage::getStoreConfig('web4pro_stockstatus/general/showstocklevel');
    }

    /**
     * Is the custom status allowed at page of product view
     * @param $product
     * @return string
     */
    public function showStockStatus($product)
    {
        $result = '';
        if ($this->isCustomStockStatusOnProductListPage()){
            $result = $this->getNewStockStatus($product);
        }
        return $result;
    }
}