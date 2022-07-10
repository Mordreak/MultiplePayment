<?php

namespace Thewit\MultiplePayment\Model;

class MultiplePayment extends \Magento\Payment\Model\Method\AbstractMethod
{
    const METHOD_CODE = 'multiplepayment';

    protected $_code = 'multiplepayment';

    protected $_isOffline = true;

    protected $_canAuthorize = true;

    /**
     * @var string
     */
    protected $_formBlockType = \Magento\OfflinePayments\Block\Form\Checkmo::class;

    /**
     * @var string
     */
    protected $_infoBlockType = \Magento\OfflinePayments\Block\Info\Checkmo::class;

    /**
     * Get config payment action, do nothing if status is pending
     *
     * @return string|null
     */
    public function getConfigPaymentAction()
    {
        return $this->getConfigData('order_status') == 'pending' ? null : parent::getConfigPaymentAction();
    }

    /**
     * @return string
     */
    public function getMethods()
    {
        $path = 'payment/' . $this->getCode() . '/methods';
        return explode("\n",
            $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->getStore()));
    }
}
