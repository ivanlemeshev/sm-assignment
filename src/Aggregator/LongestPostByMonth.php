<?php declare(strict_types = 1);

namespace App\Aggregator;

use App\Entity\Post;

/**
 * Class LongestPostByMonth
 *
 * @package App\Aggregator
 */
class LongestPostByMonth implements AggregatorInterface
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
     * LongestPostByMonth constructor.
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

        $longestPosts = [];
        foreach ($this->posts as $post) {
            $month = $post->getCreatedTime()->format('m.Y');
            $length = mb_strlen($post->getMessage());

            if (!isset($longestPosts[$month])) {
                $longestPosts[$month] = 0;
            }

            if ($length > $longestPosts[$month]) {
                $longestPosts[$month] = $length;
            }
        }

        $result = [];
        foreach ($longestPosts as $month => $length) {
            $result[$month] = $length;
        }

        return $result;
    }
}
