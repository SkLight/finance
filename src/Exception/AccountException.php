<?php declare(strict_types = 1);

namespace App\Exception;

use Doctrine\DBAL\DBALException;

/**
 * Class AccountException
 * @package App\Exception
 */
class AccountException extends \Exception implements FinanceExceptionInterface
{
    public const INCORRECT_ACCOUNT_TYPE = 200;
    public const DBAL_EXCEPTION         = 201;

    /**
     * @param int $accountType
     *
     * @return AccountException
     */
    public static function incorrectAccountType(int $accountType): AccountException
    {
        return new self("Incorrect account type: '{$accountType}'", self::INCORRECT_ACCOUNT_TYPE);
    }

    /**
     * @param DBALException $previous
     *
     * @return AccountException
     */
    public static function DBALException(DBALException $previous): AccountException
    {
        return new self($previous->getMessage(), self::DBAL_EXCEPTION, $previous);
    }
}
