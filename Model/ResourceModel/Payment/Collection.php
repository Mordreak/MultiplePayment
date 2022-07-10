<?php

namespace Thewit\MultiplePayment\Model\ResourceModel\Payment;

use Thewit\MultiplePayment\Model\Payment;
use Thewit\MultiplePayment\Model\ResourceModel\Payment as ResourcePayment;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'thewit_mulitplepayment_payment_collection';
    protected $_eventObject = 'payment_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Payment::class, ResourcePayment::class);
    }
}
