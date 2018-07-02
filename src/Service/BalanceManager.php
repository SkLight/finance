<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;
use App\Entity\Account;
use App\Entity\Balance;
use App\Exception\AccountException;
use App\Exception\BalanceException;
use App\Repository\CurrencyRepository;
use App\Repository\PostingRepository;
use Doctrine\DBAL\DBALException;

/**
 * Class BalanceManager
 * @package App\Service
 */
class BalanceManager
{
    /** @var PostingRepository */
    private $postingRepository;

    /** @var CurrencyRepository */
    private $currencyRepository;

    /** @var AccountManager */
    private $accountManager;

    /**
     * @param PostingRepository  $postingRepository
     * @param CurrencyRepository $currencyRepository
     * @param AccountManager     $accountManager
     */
    public function __construct(
        PostingRepository $postingRepository,
        CurrencyRepository $currencyRepository,
        AccountManager $accountManager
    ) {
        $this->postingRepository  = $postingRepository;
        $this->currencyRepository = $currencyRepository;
        $this->accountManager     = $accountManager;
    }

    /**
     * @param Account $account
     *
     * @return Balance
     * @throws BalanceException
     */
    private function getBalance(Account $account): Balance
    {
        try {
            $accountBalances = $this->postingRepository->findBalancesForAccount($account);
        } catch (DBALException $exception) {
            throw BalanceException::DBALException($exception);
        }

        $balance = new Balance();

        foreach ($accountBalances as $accountBalance) {
            $currency = $this->currencyRepository->findById((int)$accountBalance['currency_id']);

            if (null !== $currency) {
                $balance->addBalance((int)$accountBalance['type'], $currency, (int)$accountBalance['amount']);
            }
        }

        return $balance;
    }

    /**
     * @return Balance
     * @throws AccountException
     * @throws BalanceException
     */
    public function getCashBalance(): Balance
    {
        return $this->getBalance($this->accountManager->getCashAccount());
    }

    /**
     * @param User $user
     *
     * @return Balance
     * @throws AccountException
     * @throws BalanceException
     */
    public function getUserBalance(User $user): Balance
    {
        return $this->getBalance($this->accountManager->getUserAccount($user));
    }
}
