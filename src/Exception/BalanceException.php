<?php declare(strict_types=1);

namespace App\Exception;

use Doctrine\DBAL\DBALException;

/**
 * Class BalanceException
 * @package App\Exception
 */
class BalanceException extends \Exception implements FinanceExceptionInterface
{
    public const INCORRECT_BALANCE_TYPE = 400;
    public const DBAL_EXCEPTION         = 401;

    /**
     * @param int $balanceType
     *
     * @return BalanceException
     */
    public static function incorrectBalanceType(int $balanceType): BalanceException
    {
        return new self("Incorrect balance type: '{$balanceType}'", self::INCORRECT_BALANCE_TYPE);
    }

    /**
     * @param DBALException $previous
     *
     * @return BalanceException
     */
    public static function DBALException(DBALException $previous): BalanceException
    {
        return new self($previous->getMessage(), self::DBAL_EXCEPTION, $previous);
    }

}
