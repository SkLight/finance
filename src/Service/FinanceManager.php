<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\Journal;
use App\Entity\Posting;
use App\Entity\Transaction;
use App\Entity\Transfer;
use App\Entity\UserCash;
use App\Exception\FinanceException;
use App\Exception\FinanceExceptionInterface;
use App\Exception\JournalException;
use App\Repository\JournalRepository;
use App\Repository\PostingRepository;
use Doctrine\DBAL\DBALException;
use Psr\Log\LoggerInterface;

/**
 * Class FinanceManager
 * @package App\Service
 */
class FinanceManager
{
    /** @var EntityManager */
    private $entityManager;

    /** @var LoggerInterface */
    private $logger;

    /** @var AccountManager */
    private $accountManager;

    /** @var JournalRepository */
    private $journalRepository;

    /** @var PostingRepository */
    private $postingRepository;

    /**
     * @param LoggerInterface   $logger
     * @param AccountManager    $accountManager
     * @param JournalRepository $journalRepository
     * @param PostingRepository $postingRepository
     */
    public function __construct(
        LoggerInterface $logger,
        AccountManager $accountManager,
        JournalRepository $journalRepository,
        PostingRepository $postingRepository
    ) {
        $this->logger            = $logger;
        $this->accountManager    = $accountManager;
        $this->journalRepository = $journalRepository;
        $this->postingRepository = $postingRepository;
    }

    /**
     * @param Transaction $transaction
     *
     * @return int Journal id
     * @throws FinanceException
     * @throws JournalException
     */
    private function doTransaction(Transaction $transaction): int
    {
        $journalType = $transaction->getJournalType();
        $operationId = $transaction->getOperationId();

        $this->logger->info('Begin transaction:', [
            'journalType' => Journal::TYPES[$journalType],
            'operationId' => $operationId,
        ]);

        try {
            try {
                $journal = $this->journalRepository->findJournal($journalType, $operationId);

                foreach ($transaction->getTransfers() as $transfer) {
                    $this->logger->info('Transfer:', [
                        'fromAccountId' => $transfer->getFromAccount()->getId(),
                        'toAccountId'   => $transfer->getToAccount()->getId(),
                        'amount'        => $transfer->getAmount(),
                    ]);

                    $posting = new Posting();
                    $posting->setJournal($journal)->setCurrency($transfer->getCurrency());

                    $debet = clone $posting;
                    $debet->setAccount($transfer->getFromAccount())->setAmount(-$transfer->getAmount());

                    $credit = clone $posting;
                    $credit->setAccount($transfer->getToAccount())->setAmount($transfer->getAmount());

                    $this->postingRepository->insert($debet);
                    $this->postingRepository->insert($credit);
                }

                $this->logger->info('Transaction done:', [
                    'journalId'   => $journal->getId(),
                    'journalType' => Journal::TYPES[$journalType],
                    'operationId' => $operationId,
                ]);
            } catch (JournalException $exception) {
                throw $exception;
            } catch (\Exception $exception) {
                $this->logger->error('Transaction fail:', [
                    'journalType' => Journal::TYPES[$journalType],
                    'operationId' => $operationId,
                ]);

                throw FinanceException::transactionFailed($exception);
            }
        } catch (DBALException $exception) {
            throw FinanceException::transactionFailed($exception);
        }

        return $journal->getId();
    }

    /**
     * @param UserCash $userCash
     *
     * @return int
     * @throws FinanceExceptionInterface
     */
    public function creditCashToUser(UserCash $userCash): int
    {
        $cashAccount = $this->accountManager->getCashAccount();
        $userAccount = $this->accountManager->getUserAccount($userCash->getUser());

        $transaction = new Transaction(Journal::TYPE_CREDIT_MONEY_TO_USER, $userCash->getId(),
            new Transfer($cashAccount, $userAccount, $userCash->getCurrency(), $userCash->getAmount())
        );

        return $this->doTransaction($transaction);
    }

    /**
     * @param UserCash $userCash
     *
     * @return int
     * @throws FinanceExceptionInterface
     */
    public function debitCashFromUser(UserCash $userCash): int
    {
        $cashAccount = $this->accountManager->getCashAccount();
        $userAccount = $this->accountManager->getUserAccount($userCash->getUser());

        $transaction = new Transaction(Journal::TYPE_DEBIT_MONEY_FROM_USER, $userCash->getId(),
            new Transfer($userAccount, $cashAccount, $userCash->getCurrency(), $userCash->getAmount())
        );

        return $this->doTransaction($transaction);
    }
}
