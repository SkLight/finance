<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Posting;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class PostingRepository
 * @package App\Repository
 */
class PostingRepository
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
        $this->repository    = $entityManager->getRepository(Posting::class);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->repository->count([]);
    }

    /**
     * @param Posting $posting
     *
     * @throws DBALException
     */
    public function insert(Posting $posting): void
    {
        $this->entityManager
            ->getConnection()
            ->executeQuery('INSERT INTO posting (account_id, journal_id, currency_id, amount, created_at) VALUES (:accountId, :journalId, :currencyId, :amount, now())', [
                'accountId'  => $posting->getAccount()->getId(),
                'journalId'  => $posting->getJournal()->getId(),
                'currencyId' => $posting->getCurrency()->getId(),
                'amount'     => $posting->getAmount()
            ]);
    }

    /**
     * @return int
     */
    public function getTotalAmount(): int
    {
        try {
            return (int)$this->repository
                ->createQueryBuilder('p')
                ->select('sum(p.amount)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return -1;
        }
    }

    /**
     * @param Account $account
     *
     * @return array[]
     */
    public function findBalancesForAccount(Account $account): array
    {
        $connection = $this->entityManager->getConnection();

        $sql = 'SELECT p.currency_id, j.type, sum(p.amount) amount FROM posting p
                INNER JOIN journal j ON p.journal_id = j.id
                WHERE p.account_id = :id
                GROUP BY p.currency_id, j.type
               ';

        $stmt = $connection->prepare($sql);
        $stmt->execute(['id' => $account->getId()]);

        return $stmt->fetchAll();
    }
}
