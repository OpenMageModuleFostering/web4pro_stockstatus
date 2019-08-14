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

Product.Config.prototype.getOptionLabel_base = Product.Config.prototype.getOptionLabel;
Product.Config.prototype.getOptionLabel = function(option, price) {
    var str = Product.Config.prototype.getOptionLabel_base.call(this, option, price);
    if(option.allowedProducts.size() == 1){
        var status = this.config.stockstatuses[option.allowedProducts].stockstatus;
        str+= ' (' + status + ')';
    }
    return str;
};

Product.Config.prototype.getIdOfSelectedProduct = function() {
    var status = '';
    var existingProducts = new Object();

    for (var i = this.settings.length - 1; i >= 0; i--) {
        var selected = this.settings[i].options[this.settings[i].selectedIndex];
        if (selected.config) {
            for (var iproducts = 0; iproducts < selected.config.products.length; iproducts++) {
                var usedAsKey = selected.config.products[iproducts] + "";
                if (existingProducts[usedAsKey] == undefined) {
                    existingProducts[usedAsKey] = 1;
                }
                else {
                    existingProducts[usedAsKey] = existingProducts[usedAsKey] + 1;
                }
            }
        }
    }

    for (var keyValue in existingProducts) {
        for (var keyValueInner in existingProducts) {
            if (Number(existingProducts[keyValueInner]) < Number(existingProducts[keyValue])) {
                delete existingProducts[keyValueInner];
            }
        }
    }

    var sizeOfExistingProducts = 0;
    var currentSimpleProductId = "";
    for (var keyValue in existingProducts) {
        currentSimpleProductId = keyValue;
        sizeOfExistingProducts = sizeOfExistingProducts + 1
    }

    if (sizeOfExistingProducts == 1) {
        return currentSimpleProductId;
    }

};

document.observe("dom:loaded", function() {
        $$('.product-options .input-box select').last().observe('change', function () {
            if(typeof spConfig.getIdOfSelectedProduct() != 'undefined') {
                var productId = spConfig.getIdOfSelectedProduct();
                var availabilityText = spConfig.config.availability;
                var status = availabilityText + ' ' + spConfig.config.stockstatuses[productId].stockstatus;
                $$('p.availability').invoke('update', status);
            }
        });
});


