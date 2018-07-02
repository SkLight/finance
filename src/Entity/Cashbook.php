<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CashBook
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="cashbook")
 */
class Cashbook
{
    public const CASH_ID = 1;

    public const CASHBOOK_IDS = [
        self::CASH_ID => 'Cash',
    ];

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $description;

    /**
     * @param int    $id
     * @param string $description
     */
    public function __construct(int $id, string $description)
    {
        $this->id          = $id;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Cashbook
     */
    public function setId(int $id): Cashbook
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Cashbook
     */
    public function setDescription(string $description): Cashbook
    {
        $this->description = $description;

        return $this;
    }
}
