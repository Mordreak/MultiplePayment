<?php

namespace Thewit\MultiplePayment\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Thewit\MultiplePayment\Api\Data\PaymentInterface;

interface PaymentRepositoryInterface
{
    /**
     * @param int $id
     * @return Thewit\MultiplePayment\Api\Data\PaymentInterface
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param PaymentInterface $payment
     * @return PaymentInterface
     */
    public function save(PaymentInterface $payment);

    /**
     * @param PaymentInterface $payment
     * @return void
     */
    public function delete(PaymentInterface $payment);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return Thewit\MultiplePayment\Api\Data\PaymentSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
