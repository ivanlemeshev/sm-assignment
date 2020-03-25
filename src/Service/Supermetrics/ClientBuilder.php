<?php declare(strict_types = 1);

namespace App\Service\Supermetrics;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Client
 *
 * @package App\Service\Supermetrics
 */
class ClientBuilder
{
    /**
     * @var string
     */
    private string $baseUrl;

    /**
     * @var Credentials
     */
    private Credentials $credentials;

    /**
     * @var callable|null $handler
     */
    private $handler = null;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * ClientBuilder constructor.
     *
     * @param string $baseUrl
     * @param Credentials $credentials
     */
    public function __construct(string $baseUrl, Credentials $credentials)
    {
        $this->baseUrl = $baseUrl;
        $this->credentials = $credentials;
        $this->logger = new NullLogger;
    }

    /**
     * @param LoggerInterface $logger
     * @return ClientBuilder
     */
    public function setLogger(LoggerInterface $logger): ClientBuilder
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param callable|null $handler
     * @return ClientBuilder
     */
    public function setHandler(?callable $handler): ClientBuilder
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return Credentials
     */
    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return callable|null
     */
    public function getHandler(): ?callable
    {
        return $this->handler;
    }

    /**
     * @return Client
     */
    public function build(): Client
    {
        return new Client($this);
    }
}
