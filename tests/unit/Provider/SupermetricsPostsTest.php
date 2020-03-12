<?php

use App\Entity\Post;
use App\Service\Supermetrics\Client;
use App\Service\Supermetrics\Credentials;
use App\Provider\SupermetricsPosts;
use Codeception\Test\Unit;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Log\Test\TestLogger;

class SupermetricsPostsTest extends Unit
{
    public function testGetPosts()
    {
        $registerResponse = [
            'meta' => [
                'request_id' => '0k_mfjEsa5cJQINTKoYkNn_yh5rGCvHN',
            ],
            'data' => [
                'client_id' => 'some_client_id',
                'email' => 'john@email.address',
                'sl_token' => 'smslt_ce7eb889f666_a187b28f041',
            ]
        ];

        $postsPageResponse = [
            'meta' => [
                'request_id' => '0k_mfjEsa5cJQINTKoYkNn_yh5rGCvHN',
            ],
            'data' => [
                'posts' => [
                    [
                        'id' => 'post5e68609a29ea7_6eeaa679',
                        'from_name' => 'Nydia Croff',
                        "from_id" => "user_2",
                        'message' => 'post_1',
                        'type' => 'status',
                        'created_time' => '2020-03-11T00:34:23+00:00',
                    ],
                ],
            ]
        ];

        $posts = [];
        $queue = [new Response(200, [], json_encode($registerResponse))];

        for ($page = 1; $page <= 10; $page++) {
            $queue[] = new Response(200, [], json_encode($postsPageResponse));
            $posts[] = new Post([
                'id' => 'post5e68609a29ea7_6eeaa679',
                'from_name' => 'Nydia Croff',
                "from_id" => "user_2",
                'message' => 'post_1',
                'type' => 'status',
                'created_time' => '2020-03-11T00:34:23+00:00',
            ]);
        }

        $mock = new MockHandler($queue);

        $client = new Client('/', new TestLogger(), HandlerStack::create($mock));
        $credentials = new Credentials('client_id', 'john@email.address', 'John Doe');

        $provider = new SupermetricsPosts($client, $credentials);

        $actual = $provider->getPosts();
        $this->assertEquals($posts, $actual);

        $mock->reset();
    }
}
