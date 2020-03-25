<?php

namespace App\Aggregator;

use App\Entity\Post;

/**
 * Class PostAverageLengthByMonth
 *
 * @package App\Aggregator
 */
class PostAverageLengthByMonth implements AggregatorInterface
{
    /**
     * @var string
     */
    private string $fieldName;

    /**
     * @var Post[]
     */
    private array $posts = [];

    /**
     * @var int
     */
    private int $postsCount = 0;

    /**
     * PostAverageLengthByMonth constructor.
     *
     * @param string $fieldName
     * @param array $posts
     */
    public function __construct(string $fieldName, array $posts)
    {
        $this->fieldName = $fieldName;
        $this->posts = $posts;
        $this->postsCount = count($posts);
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return array
     */
    public function aggregate(): array
    {
        if ($this->postsCount == 0) {
            return [];
        }

        $totalLength = [];
        $postCounts = [];

        foreach ($this->posts as $post) {
            $month = $post->getCreatedTime()->format('m.Y');

            if (!isset($totalLength[$month])) {
                $totalLength[$month] = 0;
                $postCounts[$month] = 0;
            }

            $totalLength[$month] += mb_strlen($post->getMessage());
            $postCounts[$month]++;
        }

        $result = [];
        foreach ($totalLength as $month => $total) {
            $result[$month] = floor($total / $postCounts[$month] * 100) / 100;
        }

        return $result;
    }
}