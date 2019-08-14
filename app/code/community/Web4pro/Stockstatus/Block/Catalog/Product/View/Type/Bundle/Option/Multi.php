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
class Web4pro_Stockstatus_Block_Catalog_Product_View_Type_Bundle_Option_Multi extends Web4pro_Stockstatus_Block_Catalog_Product_View_Type_Bundle_Option
{
    /**
     * Set template
     *
     * @return void
     */
    public function _construct()
    {
        $this->setTemplate('bundle/catalog/product/view/type/bundle/option/multi.phtml');
    }
}