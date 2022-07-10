<?php

namespace Thewit\MultiplePayment\Model;

use Thewit\MultiplePayment\Model\ResourceModel\Payment as ResourcePayment;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Payment extends AbstractModel implements IdentityInterface
{
    const STATUS_FULL = 'full';
    const STATUS_PARTIAL = 'partial';
    const STATUS_MISSING = 'missing';

    const CACHE_TAG = 'thewit_multiplepayment_payment';

    protected $_cacheTag = 'thewit_multiplepayment_payment';

    protected $_eventPrefix = 'thewit_multiplepayment_payment';

    protected function _construct()
    {
        $this->_init(ResourcePayment::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
