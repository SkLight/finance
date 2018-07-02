<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Account;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class AccountRepository
 * @package App\Repository
 */
class AccountRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var EntityRepository */
    private $repository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $entityManager->getRepository(Account::class);
    }

    /**
     * @param int $accountType
     * @param int $memberId
     *
     * @return Account
     * @throws DBALException
     */
    public function getAccount(int $accountType, int $memberId): Account
    {
        $connection = $this->entityManager->getConnection();

        $rawAccount = $connection->fetchAssoc('SELECT * FROM account WHERE type = :type AND account_member_id = :memberId', [
            'type'     => $accountType,
            'memberId' => $memberId,
        ]);

        if (!$rawAccount) {
            $connection->exec('INSERT INTO account (type, account_member_id, created_at, updated_at) ' .
                "VALUES ({$accountType}, {$memberId}, now(), now())");

            $rawAccount = $connection->fetchAssoc('SELECT * FROM account WHERE type = :type AND account_member_id = :memberId', [
                'type'     => $accountType,
                'memberId' => $memberId,
            ]);
        }

        $account = new Account($accountType, $memberId);
        $account->setId((int)$rawAccount['id']);

        return $account;
    }
}
