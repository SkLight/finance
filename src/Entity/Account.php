<?php declare(strict_types = 1);

namespace App\Entity;

use App\Exception\AccountException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Account
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="account", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_index", columns={"type", "account_member_id"})
 * })
 * @UniqueEntity(fields={"type", "accountMemberId"})
 * @ORM\HasLifecycleCallbacks()
 */
class Account
{
    use TimestampableTrait;

    public const TYPE_CASHBOOK = 100;
    public const TYPE_USER     = 200;

    public const TYPES = [
        self::TYPE_CASHBOOK => 'Cashbook',
        self::TYPE_USER     => 'User',
    ];

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private $type;

    /**
     * cachbook.id, user.id, ...
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $accountMemberId;

    /**
     * @param int $type
     * @param int $accountMemberId
     */
    public function __construct(int $type, int $accountMemberId)
    {
        $this->type            = $type;
        $this->accountMemberId = $accountMemberId;
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
     * @return Account
     */
    public function setId(int $id): Account
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
     * @return Account
     * @throws AccountException
     */
    public function setType(int $type): Account
    {
        if (!array_key_exists($type,self::TYPES)) {
            throw AccountException::incorrectAccountType($type);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccountMemberId(): int
    {
        return $this->accountMemberId;
    }

    /**
     * @param int $accountMemberId
     *
     * @return Account
     */
    public function setAccountMemberId(int $accountMemberId): Account
    {
        $this->accountMemberId = $accountMemberId;

        return $this;
    }
}
