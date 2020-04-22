<?php

namespace Blog\Post\Api\Data;

interface ContactSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Post list.
     * @return Blog\Post\Api\Data\ContactInterface[]
     */
    public function getItems();

    /**
     * Set name list.
     * @param Blog\Post\Api\Data\ContactInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
