<?php declare(strict_types = 1);

namespace App\Entity;

use App\Exception\BalanceException;

/**
 * Class Balance
 * @package App\Entity
 */
class Balance
{
    public const TYPE_TOTAL = 1;

    /** @var int[][] */
    private $balances = [];

    /**
     * @return array
     */
    public function getBalances(): array
    {
        return $this->balances;
    }

    /**
     * @param Currency $currency
     * @param int      $type
     *
     * @return int
     */
    public function getBalanceByCurrency(Currency $currency, int $type = Balance::TYPE_TOTAL): int
    {
        return $this->balances[$currency->getId()][$type] ?? 0;
    }

    /**
     * @param int      $type
     * @param Currency $currency
     * @param int      $amount
     *
     * @return Balance
     * @throws BalanceException
     */
    public function addBalance(int $type, Currency $currency, int $amount): Balance
    {
        if (!array_key_exists($type, Journal::TYPES)) {
            throw BalanceException::incorrectBalanceType($type);
        }

        $currencyId = $currency->getId();

        $this->balances[$currencyId][$type]            = $amount;
        $this->balances[$currencyId][self::TYPE_TOTAL] = ($this->balances[$currencyId][self::TYPE_TOTAL] ?? 0) + $amount;

        return $this;
    }
}
