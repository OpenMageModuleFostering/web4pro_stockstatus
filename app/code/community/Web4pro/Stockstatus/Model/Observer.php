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

class Web4pro_Stockstatus_Model_Observer
{
    /**
     * web4pro stockstatus helper
     *
     */
    private $_helper = '';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author WEB4PRO <srepin@corp.web4pro.com.ua>
     */
    public function __construct()
    {
        $this->_helper = Mage::helper('web4pro_stockstatus');
    }

    /**
     * Update product sotck statuses
     * @access public
     * @param  Varien_Event_Observer $observer
     * @author WEB4PRO <srepin@corp.web4pro.com.ua>
     */
    public function updateStockStatus($observer)
    {
        $block = $observer->getBlock();
        $stockstatuses = $this->_getStockStatuses($block);
        $isStockStatus = count($stockstatuses);
        if (empty($isStockStatus)){
            return;
        }
        $transport = $observer->getEvent()->getTransport();
        $block = new Web4pro_Stockstatus_Block_Stockstatus();
        if ($stockstatuses['isProductView'] == 1){
            $stockstatus = $stockstatuses['stockstatus'];
            $block->replaceStockStatus($transport['html'], $stockstatus);
        } else {
            $block->replaceStockStatuses($transport['html'], $stockstatuses);
        }
        $block->toHtml();
    }

    /**
     * Getting statuses depending on block
     * @param  object $block
     * @access private
     * @author WEB4PRO <srepin@corp.web4pro.com.ua>
     */
    private function _getStockStatuses($block)
    {
        $stockstatuses = array();
        switch(true) {
            case $block instanceof Mage_Catalog_Block_Product_View:
                $product = $block->getProduct();
                $stockstatuses['stockstatus'] = $this->_helper->__('Availability: ') . $this->_helper->getNewStockStatus($product);
                $stockstatuses['isProductView'] = 1;
                break;

            case $block instanceof Mage_Catalog_Block_Product_List:
                $collection = $block->getLoadedProductCollection();
                Mage::getSingleton('cataloginventory/stock')->addItemsToProducts($collection);
                if ($this->_helper->isCustomStockStatusOnProductListPage()){
                    foreach ($block->getLoadedProductCollection() as $item){
                        $stockstatuses[] = $this->_helper->getNewStockStatus($item);
                    }
                }
                break;

            case $block instanceof Mage_Checkout_Block_Cart:
                if ($this->_helper->isCustomStockStatusInShoppingCart()){
                    foreach ($block->getItems() as $item){
                        $stockstatuses[] = $this->_helper->getNewStockStatus($item->getProduct());
                    }
                }
                break;
        }
        return $stockstatuses;
    }

    /**
     * Set stock status attribute
     * @param  Varien_Event_Observer $observer
     * @access public
     * @author WEB4PRO <srepin@corp.web4pro.com.ua>
     */
    public function setStockStatusAttribute($observer)
    {
        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();
        $quoteItem->setCustomStockstatus($product->getCustomStockstatus());
        $quoteItem->setHideDefaultStockstatus($product->getHideDefaultStockstatus());
    }
}