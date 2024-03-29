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

class Web4pro_Stockstatus_Block_Stockstatus extends Mage_Core_Block_Text
{


    /**
     * Replace stock statuses at product list and cart pages
     * @param $transport
     * @param $stockstatuses
     */
    public function replaceStockStatuses($transport, $stockstatuses)
    {
        $script = '
            <script type="text/javascript">
            //<![CDATA[
                document.observe("dom:loaded", function() {
                    if($$("h2.product-name").first()){
                        var stockstatuses = '. Mage::helper('core')->jsonEncode($stockstatuses) . ';' .
                            'stockstatuses.each(function(name, index){
                                $$("h2.product-name")[index].insert({after:name})
                            })
                    }
                });
            //]]>
            </script>
        ';
        $text = $transport . $script;
        $this->setData('text', $text);
    }

    /**
     * Replace sotck status at product view page
     * @param $transport
     * @param $stockstatus
     * @param $product
     */
    public function replaceStockStatus($transport, $stockstatus, $product)
    {
        $helper = $this->helper('web4pro_stockstatus');
        $stockImage = '';

        if($helper->isCustomStockStatusOnProductViewPage()){
            $observer = Mage::getModel('web4pro_stockstatus/observer');
            $stockImage = $observer->getStockStatusImage($product);
        }

        $script = '<script type="text/javascript">
        //<![CDATA[
            document.observe("dom:loaded", function() {
                $$("p.availability").first().update(\''. $stockImage . $stockstatus .'\');
                Product.Config.standartStockStatus = \'' . $stockImage . $stockstatus .'\';
            });
        //]]>
        </script>
        ';
        $text = $transport . $script;
        $this->setData('text', $text);
    }
}