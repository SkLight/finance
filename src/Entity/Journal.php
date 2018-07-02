<?php declare(strict_types = 1);

namespace App\Entity;

use App\Exception\JournalException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Journal
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="journal", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_index", columns={"type", "operation_id"})
 * })
 * @UniqueEntity(fields={"type", "operationId"})
 * @ORM\HasLifecycleCallbacks()
 */
class Journal
{
    public const TYPE_CREDIT_MONEY_TO_USER  = 10;
    public const TYPE_DEBIT_MONEY_FROM_USER = 11;

    public const TYPES = [
        self::TYPE_CREDIT_MONEY_TO_USER  => 'Credit money to user',
        self::TYPE_DEBIT_MONEY_FROM_USER => 'Debit money from user',
    ];

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     */
    private $operationId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Posting[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Posting", mappedBy="journal")
     */
    private $postings;

    /**
     * @param int $type
     * @param int $operationId
     */
    public function __construct(int $type, int $operationId)
    {
        $this->type        = $type;
        $this->operationId = $operationId;
        $this->postings    = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        // bigint: Type that maps a database BIGINT to a PHP string. Doctrine 2.6.1
        /** @noinspection UnnecessaryCastingInspection */
        return (int)$this->id;
    }

    /**
     * @param int $id
     *
     * @return Journal
     */
    public function setId(int $id): Journal
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return Journal
     * @throws JournalException
     */
    public function setType(int $type): Journal
    {
        if (!array_key_exists($type,self::TYPES)) {
            throw JournalException::incorrectJournalType($type);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getOperationId(): int
    {
        // bigint: Type that maps a database BIGINT to a PHP string. Doctrine 2.6.1
        /** @noinspection UnnecessaryCastingInspection */
        return (int)$this->operationId;
    }

    /**
     * @param int $operationId
     *
     * @return Journal
     */
    public function setOperationId(int $operationId): Journal
    {
        $this->operationId = $operationId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Journal
     */
    public function setCreatedAt(\DateTime $createdAt): Journal
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Posting[]
     */
    public function getPostings(): array
    {
        return $this->postings->toArray();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime();
        }
    }
}
