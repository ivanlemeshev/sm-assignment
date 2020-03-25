<?php

namespace App\Handler;

use App\Aggregator\AggregatorInterface;

/**
 * Class Statistics
 *
 * @package App\Handler
 */
class Statistics
{
    /**
     * @var array
     */
    private array $data = [];

    /**
     * @param AggregatorInterface $aggregator
     */
    public function add(AggregatorInterface $aggregator)
    {
        $this->data[$aggregator->getFieldName()] = $aggregator->aggregate();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
