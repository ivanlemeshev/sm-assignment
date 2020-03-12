<?php

use App\Service\Supermetrics\Client;
use App\Service\Supermetrics\Credentials;
use Codeception\Test\Unit;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Log\Test\TestLogger;

class SupermetricsClientTest extends Unit
{
    public function testRegister()
    {
        $token = 'smslt_ce7eb889f666_a187b28f041';

        $responseOk = [
            'meta' => [
                'request_id' => '0k_mfjEsa5cJQINTKoYkNn_yh5rGCvHN',
            ],
            'data' => [
                'client_id' => 'some_client_id',
                'email' => 'john@email.address',
                'sl_token' => $token,
            ]
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode($responseOk)),
            new Response(500),
        ]);

        $client = new Client('/', new TestLogger(), HandlerStack::create($mock));

        $credentials = new Credentials('some_client_id', 'john@email.address', 'John Doe');
        $actual = $client->register($credentials);
        $this->assertEquals($token, $actual);

        $actual = $client->register($credentials);
        $this->assertEquals('', $actual);

        $mock->reset();
    }

    public function testGetPosts()
    {
        $posts = [
            [
                'id' => 'post5e68609a29ea7_6eeaa679',
                'from_name' => 'Nydia Croff',
                "from_id" => "user_2",
                'message' => 'post_1',
                'type' => 'status',
                'created_time' => '2020-03-11T00:34:23+00:00',
            ],
        ];

        $responseOk = [
            'meta' => [
                'request_id' => '0k_mfjEsa5cJQINTKoYkNn_yh5rGCvHN',
            ],
            'data' => [
                'page' => 1,
                'posts' => $posts,
            ]
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode($responseOk)),
            new Response(500),
        ]);

        $client = new Client('/', new TestLogger(), HandlerStack::create($mock));

        $actual = $client->getPosts('token', 1);
        $this->assertEquals($posts, $actual);

        $actual = $client->getPosts('token', 1);
        $this->assertEquals([], $actual);

        $mock->reset();
    }
}
