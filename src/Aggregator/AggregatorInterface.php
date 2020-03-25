<?php declare(strict_types = 1);

namespace App\Aggregator;

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
