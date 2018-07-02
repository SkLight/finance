<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Posting
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="posting")
 * @ORM\HasLifecycleCallbacks()
 */
class Posting
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @var Journal
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Journal", inversedBy="postings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $journal;

    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

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
     * @return Posting
     */
    public function setId(int $id): Posting
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return Posting
     */
    public function setAccount(Account $account): Posting
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Journal
     */
    public function getJournal(): Journal
    {
        return $this->journal;
    }

    /**
     * @param Journal $journal
     *
     * @return Posting
     */
    public function setJournal(Journal $journal): Posting
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     *
     * @return Posting
     */
    public function setCurrency(Currency $currency): Posting
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        // bigint: Type that maps a database BIGINT to a PHP string. Doctrine 2.6.1
        /** @noinspection UnnecessaryCastingInspection */
        return (int)$this->amount;
    }

    /**
     * @param int $amount
     *
     * @return Posting
     */
    public function setAmount(int $amount): Posting
    {
        $this->amount = $amount;

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
     * @return Posting
     */
    public function setCreatedAt(\DateTime $createdAt): Posting
    {
        $this->createdAt = $createdAt;

        return $this;
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
