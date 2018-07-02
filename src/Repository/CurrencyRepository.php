<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class CurrencyRepository
 * @package App\Repository
 */
class CurrencyRepository
{
    /** @var EntityRepository */
    private $repository;

    /**
     * CurrencyRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Currency::class);
    }

    /**
     * @param int $id
     *
     * @return Currency|null
     */
    public function findById(int $id): ?Currency
    {
        return $this->repository->find($id);
    }

    /**
     * @param $currencyCode
     *
     * @return Currency|null
     */
    public function findOneByCode($currencyCode): ?Currency
    {
        return $this->repository->findOneBy(['isoCode' => $currencyCode]);
    }
}
