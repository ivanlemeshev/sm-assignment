<?php

namespace App\Aggregator;

use App\Entity\Post;

/**
 * Interface AggregatorInterface
 *
 * @package App\Aggregator
 */
interface AggregatorInterface
{
    /**
     * @return string
     */
    public function getFieldName(): string;

    /**
     * @return array
     */
    public function aggregate(): array;
}
