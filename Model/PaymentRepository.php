<?php

namespace Thewit\MultiplePayment\Model;

use Thewit\MultiplePayment\Api\PaymentRepositoryInterface;
use Thewit\MultiplePayment\Api\Data\PaymentInterface;
use Thewit\MultiplePayment\Api\Data\PaymentSearchResultInterfaceFactory;
use Thewit\MultiplePayment\Model\ResourceModel\Payment\CollectionFactory as PaymentCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * @var PaymentFactory
     */
    private $paymentFactory;

    /**
     * @var PaymentCollectionFactory
     */
    private $paymentCollectionFactory;

    /**
     * @var PaymentSearchResultInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        PaymentFactory $PaymentFactory,
        PaymentCollectionFactory $PaymentCollectionFactory,
        PaymentSearchResultInterfaceFactory $PaymentSearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->paymentFactory = $PaymentFactory;
        $this->paymentCollectionFactory = $PaymentCollectionFactory;
        $this->searchResultFactory = $PaymentSearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function getById($id)
    {
        $payment = $this->paymentFactory->create();
        $payment->getResource()->load($payment, $id);
        if (! $payment>getId()) {
            throw new NoSuchEntityException(__('Unable to find payment with Id "%1"', $id));
        }
        return $payment;
    }

    public function save(PaymentInterface $payment)
    {
        $payment->getResource()->save($payment);
        return $payment;
    }

    public function delete(PaymentInterface $payment)
    {
        $payment->getResource()->delete($payment);
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }
}
