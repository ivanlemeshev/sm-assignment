<?php

namespace App\Provider;

use App\Entity\Post;
use App\Service\Supermetrics\Client;
use App\Service\Supermetrics\Credentials;

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
    private Client $supermetrics;

    /**
     * @var Credentials
     */
    private Credentials $credentials;

    /**
     * SupermetricsPosts constructor.
     *
     * @param Client $supermetrics
     * @param Credentials $credentials
     */
    public function __construct(Client $supermetrics, Credentials $credentials)
    {
        $this->supermetrics = $supermetrics;
        $this->credentials = $credentials;
    }

    /**
     * Returns array of all posts.
     *
     * @return Post[]
     */
    public function getPosts(): array
    {
        $token = $this->getToken();
        $posts = [];

        if ($token == '') {
            return $posts;
        }

        try {
            for ($page = 1; $page <= 10; $page++) {
                $rows = $this->supermetrics->getPosts($token, $page);
                foreach ($rows as $row) {
                    $posts[] = new Post($row);
                }
            }
        } catch (\Exception $e) {
            // TODO handler exception
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
            $_SESSION["token"] = $this->supermetrics->register($this->credentials);
        }

        return $_SESSION["token"];
    }
}
