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
 * Stockstatus default helper
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
        if (($product->isAvailable() || $this->isCustomStockStatusOnOutOfStock()) && $this->checkNumberProductsForCustomStatus($product)) {
            $customStockStatus = $product->getAttributeText('custom_stockstatus');
        }
        return $customStockStatus;
    }
    
    /**
     * check number of products for custom stock status (count_products field)
     * @param object $product
     * @return bool
     */
    public function checkNumberProductsForCustomStatus($product){
        $count = $product->getData('count_products');
        if(!in_array($product->getTypeID(), $this->getProductTypesForCount())) return true;
        $strockQty = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
        return $count <= $strockQty;
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
        if ($this->isShowStockLevel() && $product->getStockItem()->getQty() && in_array($product->getTypeID(), $this->getProductTypesForCount())) {
            $levelStockStatus = $this->__('%s in stock', (int)$product->getStockItem()->getQty());
            return $levelStockStatus . ' ' . $customStockStatus;
        }
        if(!strlen($customStockStatus)){
            return $defaultStockStatus;
        }else if ($this->isDefaultStockStatusHidden($product)){
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
        return Mage::getStoreConfig('web4pro_stockstatus/general/display_at_category');
    }

    /**
     * Is the custom status allowed at product view page
     * @return mixed
     */
    public function isCustomStockStatusOnProductViewPage()
    {
        return Mage::getStoreConfig('web4pro_stockstatus/general/display_at_product_page');
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
     * Is stock qty displayed
     * @return mixed
     */
    public function isShowStockLevel()
    {
        return Mage::getStoreConfig('web4pro_stockstatus/general/showstocklevel');
    }


    /**
     * Is show icon
     * @return mixed
     */
    public function isShowStockImage()
    {
        return Mage::getStoreConfig('web4pro_stockstatus/general/showimage');
    }

    /**
     * Is show icon only
     * @return mixed
     */
    public function isShowStockImageOnly()
    {
        return Mage::getStoreConfig('web4pro_stockstatus/general/showimageonly');
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


    /**
     * Generate image url
     * @param $optionId
     * @return string
     */
    public function getAttributeOptionImage($optionId)
    {
        $images = $this->getAttributeOptionImages();
        $image = array_key_exists($optionId, $images) ? $images[$optionId] : '';
        if ($image && (strpos($image, 'http') !== 0)) {
            $image = Mage::getDesign()->getSkinUrl($image);
        }
        return $image;
    }


    /**
     * @return mixed
     */
    public function getAttributeOptionImages()
    {
        $images = Mage::getResourceModel('eav/entity_attribute_option')->getAttributeOptionImages();
        return $images;
    }
    
    /**
     * get product types without count products field
     * 
     * @return array
     */
    public function getProductTypesForCount(){
        return array(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE);
    }
    
    /**
     * return custom stock status
     * 
     * @param unknown $product
     * @return string
     */
    public function getStockStatus($product){
        return $this->__('Availability:') . " " . $this->getNewStockStatus($product);
    }

    /**
     * return custom child stock status
     *
     * @param $product
     * @return string
     */
    public function getChildStockStatus($product){
        if (!$this->isShowStockImage()){
            return false;
        }
        $img = '';
        $custom_stockstatus = $product->getData('custom_stockstatus');
        if($custom_stockstatus && $this->isCustomStockStatusOnProductViewPage()){
            $src =  $this->getAttributeOptionImage($custom_stockstatus);
            $img = '<img style="display:inline;" align="top" height="20px" src="' . $src . '" />';
        }

        return $img . $this->__('Availability:') ." ".$this->getNewStockStatus($product);
    }
}