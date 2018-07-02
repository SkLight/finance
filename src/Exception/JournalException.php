<?php declare(strict_types = 1);

namespace App\Exception;

use App\Entity\Journal;

/**
 * Class JournalException
 * @package App\Exception
 */
class JournalException extends \Exception implements FinanceExceptionInterface
{
    public const INCORRECT_JOURNAL_TYPE = 300;
    public const TRANSACTION_EXISTS     = 301;

    /**
     * @param int $journalType
     *
     * @return JournalException
     */
    public static function incorrectJournalType(int $journalType): JournalException
    {
        return new self("Incorrect journal type: '{$journalType}'", self::INCORRECT_JOURNAL_TYPE);
    }

    public static function transactionExists(int $journalType, int $operationId)
    {
        return new self("Transaction exists: journalType '" . Journal::TYPES[$journalType] .
                "', operationId '{$operationId}'",self::TRANSACTION_EXISTS);
    }
}
