<?php

namespace App\Entity;

use DateTime;

/**
 * Class Post
 *
 * @package App\Entity
 */
class Post
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $fromName;

    /**
     * @var string
     */
    private string $fromId;

    /**
     * @var string
     */
    private string $message;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var DateTime
     */
    private DateTime $createdTime;

    /**
     * Post constructor.
     *
     * @param array $data
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        if (!empty($data['id']) && is_string($data['id'])) {
            $this->id = $data['id'];
        }

        if (!empty($data['from_name']) && is_string($data['from_name'])) {
            $this->fromName = $data['from_name'];
        }

        if (!empty($data['from_id']) && is_string($data['from_id'])) {
            $this->fromId = $data['from_id'];
        }

        if (!empty($data['message']) && is_string($data['message'])) {
            $this->message = $data['message'];
        }

        if (!empty($data['type']) && is_string($data['type'])) {
            $this->message = $data['type'];
        }

        if (!empty($data['created_time']) && is_string($data['created_time'])) {
            $this->createdTime = new DateTime($data['created_time']);
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * @return string
     */
    public function getFromId(): string
    {
        return $this->fromId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime(): DateTime
    {
        return $this->createdTime;
    }
}
