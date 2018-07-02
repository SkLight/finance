<?php declare(strict_types = 1);

namespace App\Entity;

/**
 * Class Transaction
 * @package App\Entity
 */
class Transaction
{
    /** @var int */
    private $journalType;

    /** @var int */
    private $operationId;

    /** @var Transfer[] */
    private $transfers;

    /**
     * @param int        $journalType
     * @param int        $operationId
     * @param Transfer[] $transfers
     */
    public function __construct(int $journalType, int $operationId, Transfer ...$transfers)
    {
        $this->journalType = $journalType;
        $this->operationId = $operationId;
        $this->transfers   = $transfers ?? [];
    }

    /**
     * @return int
     */
    public function getJournalType(): int
    {
        return $this->journalType;
    }

    /**
     * @return int
     */
    public function getOperationId(): int
    {
        return $this->operationId;
    }

    /**
     * @return Transfer[]
     */
    public function getTransfers(): array
    {
        return $this->transfers;
    }
}
