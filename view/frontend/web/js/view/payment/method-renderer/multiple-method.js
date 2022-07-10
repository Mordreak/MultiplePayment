/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @api */
define([
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'ko',
    'Magento_Checkout/js/model/quote'
], function (Component, jquery, ko, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Thewit_MultiplePayment/payment/multiple'
        },

        isPlaceOrderActionAllowed: function()
        {
            if (typeof jquery('input[name=multiplepayment_method]:checked') == 'undefined' ||
                typeof jquery('input[name=multiplepayment_method]:checked').val() == 'undefined') {
                return false;
            }
            return true;
        },

        enablePlaceOrder: function(data, event)
        {
            jquery('button.action.primary.checkout').removeClass('disabled').attr('disabled', false);
            return true;
        },

        /**
         * Get payment method data
         */
        getData: function () {
            return {
                'method': this.item.method,
                'po_number': null,
                'additional_data': {'multiple_method': jquery('input[name=multiplepayment_method]:checked').val()},
            };
        },

        getMethods: function () {
            return window.checkoutConfig.payment.multiplepayment.methods;
        }
    });
});
