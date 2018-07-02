<?php declare(strict_types = 1);

namespace App\Entity;

/**
 * Class Transfer
 * @package App\Entity
 */
class Transfer
{
    /** @var Account */
    private $fromAccount;

    /** @var Account */
    private $toAccount;

    /** @var Currency */
    private $currency;

    /** @var int */
    private $amount;

    /**
     * @param Account  $fromAccount
     * @param Account  $toAccount
     * @param Currency $currency
     * @param int      $amount
     */
    public function __construct(Account $fromAccount, Account $toAccount, Currency $currency, int $amount)
    {
        $this->fromAccount = $fromAccount;
        $this->toAccount   = $toAccount;
        $this->currency    = $currency;
        $this->amount      = $amount;
    }

    /**
     * @return Account
     */
    public function getFromAccount(): Account
    {
        return $this->fromAccount;
    }

    /**
     * @return Account
     */
    public function getToAccount(): Account
    {
        return $this->toAccount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}
