<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class Reviews
{
    /**
     * @Assert\NotBlank(message="Текст не заполнен")
     */
    private string $message;

    /**
     * @Assert\NotBlank(message="ИД пользователя пустое")
     */
    private string $userId;

    /**
     * @Assert\NotBlank(message="Имя пользователя пустое")
     */
    private string $username;

    /**
     * @Assert\NotBlank
     */
    private string $create_at;

    public function __construct()
    {
        $this->setCreateAt(time());
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('message', new NotBlank());
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getCreateAt(): string
    {
        return $this->create_at;
    }

    /**
     * @param string $create_at
     */
    public function setCreateAt(int $create_at): void
    {
        $this->create_at = $create_at;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}
