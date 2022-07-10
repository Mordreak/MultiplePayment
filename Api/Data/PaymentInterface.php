<?php

namespace Thewit\MultiplePayment\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PaymentInterface extends ExtensibleDataInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @param string $name
     * @return void
     */
    public function setMethod($name);

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param int $orderId
     * @return void
     */
    public function setOrderId($orderId);

    /**
     * @return string
     */
    public function getDate();

    /**
     * @param string $date
     * @return void
     */
    public function setDate($date);

    /**
     * @return string
     */
    public function getAmount();

    /**
     * @param mixed $amount
     * @return void
     */
    public function setAmount($amount);
}
