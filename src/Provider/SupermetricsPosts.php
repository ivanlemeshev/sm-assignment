<?php

namespace App\Provider;

use App\Entity\Post;
use App\Service\Supermetrics\Client;

/**
 * Class SupermetricsPosts
 *
 * @package App\Provider
 */
class SupermetricsPosts
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * SupermetricsPosts constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Returns array of all posts.
     *
     * @return Post[]
     */
    public function getPosts(): array
    {
        $token = $this->getToken();

        /** @var Post[] $posts */
        $posts = [];

        if ($token == '') {
            return $posts;
        }

        for ($page = 1; $page <= 10; $page++) {
            $rows = $this->client->getPosts($token, $page);

            /** @var array $row */
            foreach ($rows as $row) {
                try {
                    $posts[] = new Post($row);
                } catch (\Exception $e) {
                    // TODO handler exception
                }
            }
        }

        return $posts;
    }

    /**
     * Returns the token.
     *
     * @return string
     */
    private function getToken(): string
    {
        if (empty($_SESSION["token"]) || $_SESSION["token_expired_time"] < strtotime('now')) {
            $_SESSION["token_expired_time"] = strtotime('now + 50 minutes');
            $_SESSION["token"] = $this->client->register();
        }

        return strval($_SESSION["token"]);
    }
}
