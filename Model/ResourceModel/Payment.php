<?php

namespace Thewit\MultiplePayment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Thewit\MultiplePayment\Helper\Data as PaymentHelper;

class Payment extends AbstractDb
{
    protected $_paymentHelper;

    public function __construct(
        Context $context,
        PaymentHelper $paymentHelper
    ) {
        $this->_paymentHelper = $paymentHelper;
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('multiplepayment_payment', 'id');
    }

    protected function _afterDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_paymentHelper->updateOrderPaidInformation($object);
        return parent::_afterDelete($object);
    }
}
