<?php
namespace Thewit\MultiplePayment\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PaymentSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return Thewit\MultiplePayment\Api\Data\PaymentInterface[]
     */
    public function getItems();

    /**
     * @param Thewit\MultiplePayment\Api\Data\PaymentInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
