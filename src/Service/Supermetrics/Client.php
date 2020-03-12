<?php

namespace App\Service\Supermetrics;

use App\Service\Supermetrics\Credentials;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Psr\Log\LoggerInterface;

/**
 * Class Client
 *
 * @package App\Service\Supermetrics
 */
class Client
{
    /**
     * @var Guzzle
     */
    private Guzzle $client;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Supermetrics constructor.
     *
     * @param string $baseUrl
     * @param LoggerInterface $logger
     * @param callable|null $handler
     */
    public function __construct(string $baseUrl, LoggerInterface $logger, callable $handler = null)
    {
        $this->client = new Guzzle([
            'base_uri' => $baseUrl,
            'timeout'  => 1.0,
            'handler' => $handler,
        ]);
        $this->logger = $logger;
    }

    /**
     * Registers the client and returns token.
     *
     * @param Credentials $credentials
     * @return string
     */
    public function register(Credentials $credentials): string
    {
        try {
            $formParams = [
                'client_id' => $credentials->getClientId(),
                'email' => $credentials->getEmail(),
                'name' => $credentials->getName(),
            ];

            $response = $this->client->post('/assignment/register', [
                'form_params' => $formParams,
            ]);

            if ($response->getStatusCode() != 200) {
                $this->logger->error('Error on registering client', [
                    'form_params' => $formParams,
                    'response' => $response,
                ]);

                return '';
            }

            /** @var array $data */
            $data = json_decode($response->getBody()->getContents(), true);
            if (isset($data['data']['sl_token']) && is_string($data['data']['sl_token'])) {
                return $data['data']['sl_token'];
            }
        } catch (RequestException $e) {
            $context = ['request' => Psr7\str($e->getRequest())];
            $response = $e->getResponse();
            if ($response !== null) {
                $context['response'] = Psr7\str($response);
            }

            $this->logger->error('Exception on registering client', $context);
        }

        return '';
    }

    /**
     * Fetches posts.
     *
     * @param string $token
     * @param int $page
     * @return array
     */
    public function getPosts(string $token, int $page = 1): array
    {
        try {
            $query = [
                'sl_token' => $token,
                'page' => $page,
            ];

            $response = $this->client->get('/assignment/posts', [
                'query' => $query,
            ]);


            if ($response->getStatusCode() != 200) {
                $this->logger->error('Error on getting posts', [
                    'query' => $query,
                    'response' => $response,
                ]);

                return [];
            }

            /** @var array $data */
            $data = json_decode($response->getBody()->getContents(), true);
            if (isset($data['data']['posts']) && is_array($data['data']['posts'])) {
                return $data['data']['posts'];
            }
        } catch (RequestException $e) {
            $context = ['request' => Psr7\str($e->getRequest())];
            $response = $e->getResponse();
            if ($response !== null) {
                $context['response'] = Psr7\str($response);
            }

            $this->logger->error('Exception on getting posts', $context);
        }

        return [];
    }
}
