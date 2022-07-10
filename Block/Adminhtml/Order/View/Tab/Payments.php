<?php

namespace Thewit\MultiplePayment\Block\Adminhtml\Order\View\Tab;

use Thewit\MultiplePayment\Helper\Data as PaymentHelper;
use Thewit\MultiplePayment\Model\ResourceModel\Payment\CollectionFactory as PaymentCollectionFactory;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface as PriceCurrencyHelper;
use Magento\Framework\Registry;

class Payments extends Template implements TabInterface
{
    protected $_template = 'order/view/tab/payments.phtml';
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    protected $_paymentCollectionFactory;

    protected $_paymentHelper;

    protected $_priceCurrencyHelper;

    /**
     * Collection factory
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PaymentCollectionFactory $paymentCollectionFactory
     * @param PaymentHelper $paymentHelper
     * @param PriceCurrencyHelper $priceCurrencyHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PaymentCollectionFactory $paymentCollectionFactory,
        PaymentHelper $paymentHelper,
        PriceCurrencyHelper $priceCurrencyHelper,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_paymentCollectionFactory = $paymentCollectionFactory;
        $this->_paymentHelper = $paymentHelper;
        $this->_priceCurrencyHelper = $priceCurrencyHelper;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Payments');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Order Payments');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    public function getPayments()
    {
        $paymentCollection = $this->_paymentCollectionFactory->create();
        $paymentCollection->addFieldToFilter('order_id', $this->getOrder()->getId());

        return $paymentCollection;
    }

    public function getTotalPaid()
    {
        return $this->formatPrice($this->_paymentHelper->getTotalPaid($this->getOrder()));
    }

    public function getTotalDue()
    {
        return $this->formatPrice($this->_paymentHelper->getTotalDue($this->getOrder()));
    }

    public function formatPrice($amount)
    {
        return $this->_priceCurrencyHelper->convertAndFormat(
            $amount,
            false,
            PriceCurrencyHelper::DEFAULT_PRECISION,
            null,
            $this->getOrder()->getOrderCurrency()
        );
    }

    public function getStatus()
    {
        return $this->_paymentHelper->getPaymentStatus($this->getOrder());
    }
}
