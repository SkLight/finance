<?php declare(strict_types = 1);

namespace App\Exception;

/**
 * Class FinanceException
 * @package App\Exception
 */
class FinanceException extends \Exception implements FinanceExceptionInterface
{
    public const TRANSACTION_FAILED = 100;

    /**
     * @param \Exception $previous
     *
     * @return FinanceException
     */
    public static function transactionFailed(\Exception $previous): FinanceException
    {
        return new self("Transaction fail: '{$previous->getMessage()}'", self::TRANSACTION_FAILED, $previous);
    }
}
