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
	if(option._f != undefined && !option._f.isSwatch){
	    if(option.allowedProducts.size() == 1){
	    	var status = this.config.stockstatuses[option.allowedProducts].stockstatus;
	        str+= ' (' + status + ')';
	    }
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
if(Product.ConfigurableSwatches != undefined){
	Product.ConfigurableSwatches.prototype.getProductByOption = function(option, options){
		try{
			var arr = [];
			var productCode = '';
			if(option != null){
				productCode = option.attr.code;
				arr.push(option.allowedProducts);
			}
			for (var i in options) {
				if(options[i].code == productCode) continue;
				arr.push(options[i]._e.selectedOption.allowedProducts);
		    }
			arr = this._u.intersectAll(arr);
			if(arr.length > 1) return false;
			return arr[0];
		} catch(e){
			return false;
		}

	};
	
	Product.ConfigurableSwatches.prototype.checkStockStatus = function() {
	    var inStock = true;
	    var checkOptions = arguments.length ? arguments[0] : this._E.activeConfigurableOptions;
	    var arr = {};
	    // Set out of stock if any selected item is not enabled
	    checkOptions.each( function(selectedOpt) {
	    	arr[selectedOpt.id] = selectedOpt.attr;
	        if (!selectedOpt._f.enabled) {
	            inStock = false;
	        }
	    });
	    var option = (this._F.currentAction == 'over-swatch' || this._F.currentAction == 'out-swatch') ? this._E.optionOver : null; 
	    var productId = this.getProductByOption(option, arr);
	    var stockStatus = (checkOptions.size() > 1) && productId ? getFormatStockStatus(Translator.translate(spConfig.config.stockstatuses[productId].stockstatus)): Product.Config.standartStockStatus;
	    this.setStockStatus( inStock, stockStatus);
	};
	
	Product.ConfigurableSwatches.prototype.setStockStatus = function(inStock, stockStatus) {
	    if (inStock) {
	        this._E.availability.each(function(el) {
	            var el = $(el);
	            el.addClassName('in-stock').removeClassName('out-of-stock');
	            el.textContent = stockStatus;
	        });
	
	        this._E.cartBtn.btn.each(function(el, index) {
	            var el = $(el);
	            el.disabled = false;
	            el.removeClassName('out-of-stock');
	            el.writeAttribute('onclick', this._E.cartBtn.onclick);
	            el.title = '' + Translator.translate(this._E.cartBtn.txt[index]);
	            el.select('span span').invoke('update', Translator.translate(this._E.cartBtn.txt[index]));
	        }.bind(this));
	    } else {
	        this._E.availability.each(function(el) {
	            var el = $(el);
	            el.addClassName('out-of-stock').removeClassName('in-stock');
	            el.textContent = Translator.translate('Out of stock');
	        });
	        this._E.cartBtn.btn.each(function(el) {
	            var el = $(el);
	            el.addClassName('out-of-stock');
	            el.disabled = true;
	            el.removeAttribute('onclick');
	            el.observe('click', function(event) {
	                Event.stop(event);
	                return false;
	            });
	            el.writeAttribute('title', Translator.translate('Out of Stock'));
	            el.select('span span').invoke('update', Translator.translate('Out of Stock'));
	        });
	    }
	};
}

function getFormatStockStatus(status){
    var availabilityText = spConfig.config.availability;
    return status != '' ? availabilityText + ' ' + status : '';
}

document.observe("dom:loaded", function() {
    $$('.product-options .input-box select').last().observe('change', function () {
    	if(typeof spConfig.getIdOfSelectedProduct() != 'undefined') {
            var productId = spConfig.getIdOfSelectedProduct();
            var status = getFormatStockStatus(spConfig.config.stockstatuses[productId].stockstatus);
            $$('p.availability').invoke('update', status);
        }
    });
});