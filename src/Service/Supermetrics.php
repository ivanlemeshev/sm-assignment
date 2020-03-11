<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Psr\Log\LoggerInterface;

/**
 * Class Supermetrics
 *
 * @package App\Service
 */
class Supermetrics
{
    /**
     * The HTTP client
     *
     * @var Client $client
     */
    private Client $client;

    /**
     * @var LoggerInterface $logger.
     */
    private LoggerInterface $logger;

    /**
     * Supermetrics constructor
     *
     * @param string $baseUrl
     * @param LoggerInterface $logger
     * @param callable|null $handler
     */
    public function __construct(string $baseUrl, LoggerInterface $logger, callable $handler = null)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout'  => 1.0,
            'handler' => $handler,
        ]);
        $this->logger = $logger;
    }

    /**
     * Registers the client and returns its token
     *
     * @param string $clientId
     * @param string $email
     * @param string $name
     * @return string
     */
    public function register(string $clientId, string $email, string $name): string
    {
        try {
            $formParams = [
                'client_id' => $clientId,
                'email' => $email,
                'name' => $name,
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
     * Fetches posts
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
