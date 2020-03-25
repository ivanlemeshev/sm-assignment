<?php

use App\Aggregator\AverageNumberOfUserPostsByMonth;
use App\Aggregator\LongestPostByMonth;
use App\Aggregator\PostAverageLengthByMonth;
use App\Aggregator\TotalPostsByWeek;
use App\Handler\Statistics;
use App\Entity\Post;
use Codeception\Test\Unit;

class StatisticsTest extends Unit
{
    public function testShow()
    {
        $expected = [];
        $statistics = new Statistics();
        $this->assertEquals($expected, $statistics->getData());

        $posts = [
            new Post([
                'id' => '10',
                'from_name' => 'Author Name 2',
                "from_id" => "author_2",
                'message' => 'post_10',
                'type' => 'status',
                'created_time' => '2020-02-05T00:00:01+00:00',
            ]),
            new Post([
                'id' => '9',
                'from_name' => 'Author Name 1',
                "from_id" => "author_1",
                'message' => 'post_9',
                'type' => 'status',
                'created_time' => '2020-02-04T00:00:01+00:00',
            ]),
            new Post([
                'id' => '8',
                'from_name' => 'Author Name 1',
                "from_id" => "author_1",
                'message' => 'post_8',
                'type' => 'status',
                'created_time' => '2020-02-03T00:00:01+00:00',
            ]),
            new Post([
                'id' => '7',
                'from_name' => 'Author Name 2',
                "from_id" => "author_2",
                'message' => 'post_7',
                'type' => 'status',
                'created_time' => '2020-02-02T00:00:01+00:00',
            ]),
            new Post([
                'id' => '6',
                'from_name' => 'Author Name 1',
                "from_id" => "author_1",
                'message' => 'post_6',
                'type' => 'status',
                'created_time' => '2020-02-01T00:00:01+00:00',
            ]),
            new Post([
                'id' => '5',
                'from_name' => 'Author Name 1',
                "from_id" => "author_1",
                'message' => 'post_5',
                'type' => 'status',
                'created_time' => '2020-01-31T00:00:01+00:00',
            ]),
            new Post([
                'id' => '4',
                'from_name' => 'Author Name 1',
                "from_id" => "author_2",
                'message' => 'post_4',
                'type' => 'status',
                'created_time' => '2020-01-30T00:00:01+00:00',
            ]),
            new Post([
                'id' => '3',
                'from_name' => 'Author Name 2',
                "from_id" => "author_2",
                'message' => 'post_3',
                'type' => 'status',
                'created_time' => '2020-01-29T00:00:01+00:00',
            ]),
            new Post([
                'id' => '2',
                'from_name' => 'Author Name 1',
                "from_id" => "author_1",
                'message' => 'post_2',
                'type' => 'status',
                'created_time' => '2020-01-28T00:00:01+00:00',
            ]),
            new Post([
                'id' => '1',
                'from_name' => 'Author Name 1',
                "from_id" => "author_1",
                'message' => 'post_1',
                'type' => 'status',
                'created_time' => '2020-01-27T03:00:00+00:00',
            ]),
        ];

        $expected = [
            'post_average_length_by_month' => [
                '02.2020' => 6.2,
                '01.2020' => 6.0,
            ],
            'longest_post_by_month' => [
                '02.2020' => 7,
                '01.2020' => 6,
            ],
            'total_posts_by_week' => [
                '03.02.2020' => 3,
                '27.01.2020' => 7,
            ],
            'average_number_of_user_posts_by_month' => [
                '02.2020' => 2.5,
                '01.2020' => 2.5,
            ],
        ];

        $statistics = new Statistics();
        $statistics->add((new PostAverageLengthByMonth('post_average_length_by_month', $posts)));
        $statistics->add((new LongestPostByMonth('longest_post_by_month', $posts)));
        $statistics->add((new TotalPostsByWeek('total_posts_by_week', $posts)));
        $statistics->add((new AverageNumberOfUserPostsByMonth('average_number_of_user_posts_by_month', $posts)));

        $this->assertEquals($expected, $statistics->getData());
    }
}
