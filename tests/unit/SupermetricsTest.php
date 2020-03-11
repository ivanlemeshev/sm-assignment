<?php

use App\Service\Supermetrics;
use Codeception\Test\Unit;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Log\Test\TestLogger;

class SupermetricsClientTest extends Unit
{
    public function testRegisterSuccess()
    {
        $expectedToken = 'smslt_ce7eb889f666_a187b28f041';

        $response = [
            'meta' => [
                'request_id' => '0k_mfjEsa5cJQINTKoYkNn_yh5rGCvHN',
            ],
            'data' => [
                'client_id' => 'some_client_id',
                'email' => 'john@email.address',
                'sl_token' => $expectedToken,
            ]
        ];

        $mock = new MockHandler([new Response(200, [], json_encode($response))]);
        $client = new Supermetrics('/', new TestLogger(), HandlerStack::create($mock));
        $actualToken = $client->register('some_client_id', 'john@email.address', 'John Doe');
        $this->assertEquals($expectedToken, $actualToken);
        $mock->reset();
    }

    public function testRegisterError()
    {
        $mock = new MockHandler([new Response(500)]);
        $client = new Supermetrics('/', new TestLogger(), HandlerStack::create($mock));
        $actualToken = $client->register('some_client_id', 'john@email.address', 'John Doe');
        $this->assertEquals('', $actualToken);
        $mock->reset();
    }

    public function testGetPostsSuccess()
    {
        $expectedPosts = [
            'id' => 'post5e68609a29ea7_6eeaa679',
            'from_name' => 'Nydia Croff',
            "from_id" => "user_2",
            'message' => 'post_1',
            'type' => 'status',
            'created_time' => '2020-03-11T00:34:23+00:00',
        ];

        $response = [
            'meta' => [
                'request_id' => '0k_mfjEsa5cJQINTKoYkNn_yh5rGCvHN',
            ],
            'data' => [
                'page' => 1,
                'posts' => [
                    'id' => 'post5e68609a29ea7_6eeaa679',
                    'from_name' => 'Nydia Croff',
                    "from_id" => "user_2",
                    'message' => 'post_1',
                    'type' => 'status',
                    'created_time' => '2020-03-11T00:34:23+00:00',
                ],
            ]
        ];

        $mock = new MockHandler([new Response(200, [], json_encode($response))]);
        $client = new Supermetrics('/', new TestLogger(), HandlerStack::create($mock));
        $actualPosts = $client->getPosts('token', 1);
        $this->assertEquals($expectedPosts, $actualPosts);
        $mock->reset();
    }

    public function testGetPostsError()
    {
        $mock = new MockHandler([new Response(500)]);
        $client = new Supermetrics('/', new TestLogger(), HandlerStack::create($mock));
        $actualPosts = $client->getPosts('token', 1);
        $this->assertEquals([], $actualPosts);
        $mock->reset();
    }
}
