<?php

namespace App\Aggregator;

use App\Entity\Post;

/**
 * Class AverageNumberOfUserPostsByMonth
 *
 * @package App\Aggregator
 */
class AverageNumberOfUserPostsByMonth implements AggregatorInterface
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
     * AverageNumberOfUserPostsByMonth constructor.
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

        $monthPosts = [];
        foreach ($this->posts as $post) {
            $month = $post->getCreatedTime()->format('m.Y');
            $user = $post->getFromId();

            if (!isset($monthPosts[$month])) {
                $monthPosts[$month] = [];
            }

            if (!isset($monthPosts[$month][$user])) {
                $monthPosts[$month][$user] = 0;
            }

            $monthPosts[$month][$user]++;
        }

        $result = [];
        foreach ($monthPosts as $month => $users) {
            $totalUsers = 0;
            $totalPosts = 0;

            foreach ($users as $user => $count) {
                $totalUsers++;
                $totalPosts += $count;
            }

            $result[$month] = floor($totalPosts / $totalUsers * 100) / 100;
        }

        return $result;
    }
}