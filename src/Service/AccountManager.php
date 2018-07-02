<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\Account;
use App\Entity\Cashbook;
use App\Entity\User;
use App\Exception\AccountException;
use App\Repository\AccountRepository;
use Doctrine\DBAL\DBALException;

/**
 * Class AccountManager
 * @package App\Service
 */
class AccountManager
{
    /** @var AccountRepository */
    private $accountRepository;

    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param int $accountType
     * @param int $accountMemberId
     *
     * @return Account
     * @throws AccountException
     */
    private function getAccount(int $accountType, int $accountMemberId): Account
    {
        try {
            return $this->accountRepository->getAccount($accountType, $accountMemberId);
        } catch (DBALException $exception) {
            throw AccountException::DBALException($exception);
        }
    }

    /**
     * @return Account
     * @throws AccountException
     */
    public function getCashAccount(): Account
    {
        return $this->getAccount(Account::TYPE_CASHBOOK, Cashbook::CASH_ID);
    }

    /**
     * @param User $user
     *
     * @return Account
     * @throws AccountException
     */
    public function getUserAccount(User $user): Account
    {
        return $this->getAccount(Account::TYPE_USER, $user->getId());
    }
}
