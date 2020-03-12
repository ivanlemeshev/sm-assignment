<?php

namespace App\Handler;

use App\Entity\Post;

/**
 * Class Statistics
 *
 * @package App\Handler
 */
class Statistics
{
    /**
     * @var Post[]
     */
    private array $posts = [];

    /**
     * @var int
     */
    private int $postsCount = 0;

    /**
     * PostStatistics constructor.
     *
     * @param array $posts
     */
    public function __construct(array $posts)
    {
        $this->posts = $posts;
        $this->postsCount = count($posts);
    }

    /**
     * @return array
     */
    public function show(): array
    {
        return [
            'post_average_length_by_month' => $this->getPostAverageLengthByMonth(),
            'longest_post_by_month' => $this->getLongestPostByMonth(),
            'total_posts_by_week' => $this->getTotalPostsByWeek(),
            'average_number_of_user_posts_by_month' => $this->getAverageNumberOfUserPostsByMonth(),
        ];
    }

    /**
     * @return array
     */
    private function getPostAverageLengthByMonth(): array
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

    /**
     * @return array
     */
    private function getLongestPostByMonth(): array
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

    /**
     * @return array
     */
    private function getTotalPostsByWeek(): array
    {
        if ($this->postsCount == 0) {
            return [];
        }

        $totalPosts = [];
        foreach ($this->posts as $post) {
            $dateTime = clone $post->getCreatedTime();
            $weekStartDate = $dateTime->modify('Monday this week');

            $key = $weekStartDate->format('d.m.Y');
            if (!isset($totalPosts[$key])) {
                $totalPosts[$key] = 0;
            }

            $totalPosts[$key]++;
        }

        uksort($totalPosts, function ($a, $b) {
            $tm1 = strtotime($a);
            $tm2 = strtotime($b);
            return ($tm1 < $tm2) ? 1 : (($tm1 > $tm2) ? -1 : 0);
        });

        return $totalPosts;
    }

    /**
     * @return array
     */
    private function getAverageNumberOfUserPostsByMonth(): array
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
