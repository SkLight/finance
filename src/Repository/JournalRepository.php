<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Journal;
use App\Exception\JournalException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class JournalRepository
 * @package App\Repository
 */
class JournalRepository
{
    /** @var EntityRepository */
    private $repository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $entityManager->getRepository(Journal::class);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->repository->count([]);
    }

    /**
     * @param int $id
     *
     * @return Journal|null
     */
    public function findById(int $id): ?Journal
    {
        return $this->repository->find($id);
    }

    /**
     * @param int $journalType
     * @param int $operationId
     *
     * @return Journal
     * @throws DBALException
     * @throws JournalException
     */
    public function findJournal(int $journalType, int $operationId): Journal
    {
        $connection = $this->entityManager->getConnection();

        $rawJournal = $connection->fetchAssoc('SELECT * FROM journal WHERE type = :type AND operation_id = :operationId', [
            'type'        => $journalType,
            'operationId' => $operationId,
        ]);

        if ($rawJournal) {
            throw JournalException::transactionExists($journalType, $operationId);
        }

        $connection->exec('INSERT INTO journal (type, operation_id, created_at) ' .
            "VALUES ({$journalType}, {$operationId}, now())");

        $rawJournal = $connection->fetchAssoc('SELECT * FROM journal WHERE type = :type AND operation_id = :operationId', [
            'type'        => $journalType,
            'operationId' => $operationId,
        ]);

        $journal = new Journal($journalType, $operationId);
        $journal->setId((int)$rawJournal['id']);

        return $journal;
    }
}
