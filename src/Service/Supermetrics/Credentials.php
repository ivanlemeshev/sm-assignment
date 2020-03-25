<?php declare(strict_types = 1);

namespace App\Service\Supermetrics;

/**
 * Class Credentials
 *
 * @package App\Service\Supermetris
 */
class Credentials
{
    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $name;

    /**
     * Credentials constructor.
     *
     * @param string $clientId
     * @param string $email
     * @param string $name
     */
    public function __construct(string $clientId, string $email, string $name)
    {
        $this->clientId = $clientId;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
