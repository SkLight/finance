<?php

namespace App\Entity;

/**
 * Class UserCash
 * @package App\Service
 */
class UserCash
{
    /**
     * @return int
     */
    public function getId(): int
    {
        return 1;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return new User();
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return new Currency();
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return 100;
    }
}
