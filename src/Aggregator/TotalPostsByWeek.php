<?php declare(strict_types = 1);

namespace App\Aggregator;

use App\Entity\Post;

/**
 * Class TotalPostsByWeek
 *
 * @package App\Aggregator
 */
class TotalPostsByWeek implements AggregatorInterface
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
     * TotalPostsByWeek constructor.
     *
     * @param string $fieldName
     * @param Post[] $posts
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

        /** @var array<string, int> $totalPosts **/
        $totalPosts = [];

        foreach ($this->posts as $post) {
            $dateTime = clone $post->getCreatedTime();
            $weekStartDate = $dateTime->modify('Monday this week');

            $week = $weekStartDate->format('d.m.Y');
            if (!$week) {
                // skip posts with incorrect dates
                continue;
            }

            if (!isset($totalPosts[$week])) {
                $totalPosts[$week] = 0;
            }

            $totalPosts[$week]++;
        }

        uksort($totalPosts, function (string $a, string $b) {
            $tm1 = strtotime($a);
            $tm2 = strtotime($b);
            return ($tm1 < $tm2) ? 1 : (($tm1 > $tm2) ? -1 : 0);
        });

        return $totalPosts;
    }
}
