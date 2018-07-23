<?php

declare(strict_types = 1);

namespace App\DTO;


/**
 * Class AuthorDTO
 * @package App\DTO
 */
/**
 * Class AuthorDTO
 * @package App\DTO
 */
class AuthorDTO extends BaseDTO
{
    /**
     * @var int
     */
    private $authorId;
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;


    /**
     * @param int $authorId
     * @return AuthorDTO
     */
    public function setAuthorId(int $authorId): AuthorDTO
    {
        $this->authorId = $authorId;

        return $this;
    }


    /**
     * @param string $firstName
     * @return AuthorDTO
     */
    public function setFirstName(string $firstName): AuthorDTO
    {
        $this->firstName = $firstName;

        return $this;
    }


    /**
     * @param string $lastName
     * @return AuthorDTO
     */
    public function setLastName(string $lastName): AuthorDTO
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return [
            'author_id' => $this->getAuthorId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'full_name' => $this->getFullName(),
        ];
    }

    /**
     * @return int
     */
    private function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    private function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    private function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    private function getFullName(): string
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }
}
