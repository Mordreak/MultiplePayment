<?php

namespace Thewit\MultiplePayment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Webapi\Rest\Request;
use Thewit\MultiplePayment\Helper\Data as PaymentHelper;

class OrderObserver implements ObserverInterface
{
    protected $paymentHelper;

    protected $request;

    public function __construct(
        PaymentHelper $paymentHelper,
        Request $request
    ) {
        $this->request = $request;
        $this->paymentHelper = $paymentHelper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getData('order');
        $params = null;
        try {
            $params = $this->request->getBodyParams();
        } catch (\Exception $e) {
        }

        if ($params && isset($params['paymentMethod']) &&
            isset($params['paymentMethod']['method']) &&
            $params['paymentMethod']['method'] == 'multiplepayment' &&
            isset($params['paymentMethod']['additional_data']) &&
            $params['paymentMethod']['additional_data']) {
            if ($order->getPayment()) {
                $order->getPayment()->setAdditionalData(json_encode($params['paymentMethod']['additional_data']))->save();
            }

            if (!$this->paymentHelper->getTotalPaid($order)) {
                $multipleMethod = $params['paymentMethod']['additional_data']['multiple_method'];
                $this->paymentHelper->addPayment($multipleMethod, $order->getGrandTotal(), '', $order);
            }
        }

        if ($order->getPayment()) {
            $paymentMethod = $order->getPayment()->getmethod();
            if ($paymentMethod == 'multiplepayment') {
                $this->paymentHelper->updateOrderPaidInformation($order);
            }
        }
    }
}
