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

class Web4pro_Stockstatus_Block_Catalog_Product_View_Type_Bundle_Option extends Mage_Bundle_Block_Catalog_Product_View_Type_Bundle_Option
{
    /**
     * Returns the formatted string for the quantity chosen for the given selection
     *
     * @param Mage_Catalog_Model_Proudct $_selection
     * @param bool                       $includeContainer
     * @return string
     */
    public function getSelectionQtyTitlePrice($_selection, $includeContainer = true)
    {
        $price = $this->getProduct()->getPriceModel()->getSelectionPreFinalPrice($this->getProduct(), $_selection);
        $_selectionProduct = Mage::getModel('catalog/product')->load($_selection->getId());
        $stockStatus = Mage::helper('web4pro_stockstatus')->getNewStockStatus($_selectionProduct);
        if($stockStatus)
        {
            $stockStatus = '(' . $stockStatus . ') &nbsp; ';
        }
        
        return $_selection->getSelectionQty()*1 . ' x ' . $_selection->getName() . ' &nbsp; ' . $stockStatus .
            ($includeContainer ? '<span class="price-notice">':'') . '+' .
            $this->formatPriceString($price, $includeContainer) . ($includeContainer ? '</span>':'');
    }


    /**
     * Get title price for selection product
     *
     * @param Mage_Catalog_Model_Product $_selection
     * @param bool $includeContainer
     * @return string
     */
    public function getSelectionTitlePrice($_selection, $includeContainer = true)
    {
        $price = $this->getProduct()->getPriceModel()->getSelectionPreFinalPrice($this->getProduct(), $_selection, 1);
        $_selectionProduct = Mage::getModel('catalog/product')->load($_selection->getId());
        $stockStatus = Mage::helper('web4pro_stockstatus')->getNewStockStatus($_selectionProduct);
        if($stockStatus)
        {
            $stockStatus = '(' . $stockStatus . ') &nbsp; ';
        }
        return $_selection->getName() . ' &nbsp; ' . $stockStatus . ($includeContainer ? '<span class="price-notice">':'') . '+' .
            $this->formatPriceString($price, $includeContainer) . ($includeContainer ? '</span>':'');
    }
}