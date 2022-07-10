<?php

namespace Thewit\MultiplePayment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\OrderRepository;
use Thewit\MultiplePayment\Model\Payment;
use Thewit\MultiplePayment\Model\PaymentFactory;
use Thewit\MultiplePayment\Model\PaymentRepository;
use Thewit\MultiplePayment\Model\ResourceModel\Payment\CollectionFactory as PaymentCollectionFactory;
use Thewit\MultiplePayment\Model\ResourceModel\PaymentFactory as ResourcePaymentFactory;

class Data extends AbstractHelper
{
    protected $paymentRepository;
    protected $paymentFactory;
    protected $paymentCollectionFactory;
    protected $orderRepository;
    protected $resourcePaymentFactory;

    protected $totalsPaid = [];

    public function __construct(
        Context $context,
        PaymentFactory $paymentFactory,
        PaymentRepository $paymentRepository,
        PaymentCollectionFactory $paymentCollectionFactory,
        OrderRepository $orderRepository,
        ResourcePaymentFactory $resourcePaymentFactory
    ) {
        parent::__construct($context);
        $this->paymentFactory = $paymentFactory;
        $this->paymentRepository = $paymentRepository;
        $this->paymentCollectionFactory = $paymentCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->resourcePaymentFactory = $resourcePaymentFactory;
    }

    public function getStoreConfig()
    {
        return true;
    }

    public function addPayment($method, $amount, $comments, $order)
    {
        $payment = $this->paymentFactory->create();
        $date = new \DateTime();

        $payment->setOrderId($order->getId());
        $payment->setDate($date->format('Y-m-d'));
        $payment->setMethod($method);
        $payment->setAmount($amount);
        $payment->setComments($comments);

        $this->resourcePaymentFactory->create()->save($payment);

        $this->updateOrderPaidInformation($order);

        return $payment;
    }

    public function createPayment($order)
    {
        $paymentMethod = $order->getPayment()->getMethod();

        return $this->addPayment($paymentMethod, $order->getGrandTotal(), '', $order);
    }

    public function getTotalPaid($order)
    {
        if (!isset($this->totalsPaid[$order->getId()])) {
            $payments = $this->paymentCollectionFactory->create();
            $payments->addFieldToFilter('order_id', $order->getId());

            $totalPaid = 0;
            foreach ($payments as $payment) {
                $totalPaid += $payment->getAmount();
            }
            $this->totalsPaid[$order->getId()] = $totalPaid;
        }

        return $this->totalsPaid[$order->getId()];
    }

    public function getTotalDue($order)
    {
        return $order->getGrandTotal() - $this->getTotalPaid($order);
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @param false $force
     */
    public function updateOrderPaidInformation($order, $force = false)
    {
        if ($force) {
            $this->totalsPaid = [];
        }

        $totalPaid = $this->getTotalPaid($order);
        $totalDue = $this->getTotalDue($order);

        if ($totalPaid != $order->getTotalPaid()) {
            $order->setBaseTotalPaid($totalPaid)
                ->setTotalPaid($totalPaid)
                ->setBaseTotalDue($totalDue)
                ->setTotalDue($totalDue);

            $order->getResource()->save($order);
        }

        if ($order->getPayment() && $totalPaid != $order->getPayment()->getAmountPaid()) {
            $order->getPayment()->setBaseAmountPaid($totalPaid)->setAmountPaid($totalPaid)->save();
        }
    }

    public function getPaymentStatus($order)
    {
        $orderTotal = $order->getGrandTotal();
        $missing = $this->getTotalDue($order);
        if ($missing == 0) {
            return Payment::STATUS_FULL;
        }
        if ($missing == $orderTotal) {
            return Payment::STATUS_MISSING;
        }
        return Payment::STATUS_PARTIAL;
    }
}
