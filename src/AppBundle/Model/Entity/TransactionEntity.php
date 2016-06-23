<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use AppBundle\Model\Exception;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Class TransactionEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="AppBundle\Model\Repository\TransactionRepository")
 */
class TransactionEntity extends Entity
{

    use Traits\IntegerIdTrait;

    /**
     * @var UserEntity
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\Entity\UserEntity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="disponent_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $disponent;

    /**
     * @var AccountEntity
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\Entity\AccountEntity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_from_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $accountFrom;

    /**
     * @var AccountEntity
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\Entity\AccountEntity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_to_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $accountTo;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $amount = 0;

    /**
     * AccountEntity constructor.
     *
     * @param UserEntity $disponent,
     * @param AccountEntity $accountFrom,
     * @param AccountEntity $accountTo,
     * @param int $amount
     */
    public function __construct(
        UserEntity $disponent,
        AccountEntity $accountFrom,
        AccountEntity $accountTo,
        int $amount
    ) {
        $this->setAccountFrom($accountFrom);
        $this->setDisponent($disponent);
        $this->setAccountTo($accountTo);
        $this->createdAt = new DateTime();
        $this->setAmount($amount);
    }

    /**
     * @return UserEntity
     */
    public function getDisponent()
    {
        return $this->disponent;
    }

    /**
     * @param UserEntity $disponent
     * @return TransactionEntity
     *
     * @throws Exception
     */
    private function setDisponent(UserEntity $disponent) : TransactionEntity
    {
        if (!$this->accountFrom->hasDisponent($disponent)) {
            throw new Exception('Not a disponent od the source account - ' . $disponent->getId());
        }

        $this->disponent = $disponent;

        return $this;
    }

    /**
     * @return AccountEntity
     */
    public function getAccountFrom()
    {
        return $this->accountFrom;
    }

    /**
     * @param AccountEntity $accountFrom
     *
     * @return TransactionEntity
     */
    private function setAccountFrom($accountFrom) : TransactionEntity
    {
        $this->accountFrom = $accountFrom;

        return $this;
    }

    /**
     * @return AccountEntity
     */
    public function getAccountTo()
    {
        return $this->accountTo;
    }

    /**
     * @param AccountEntity $accountTo
     * @return TransactionEntity
     *
     * @throws Exception
     */
    public function setAccountTo($accountTo) : TransactionEntity
    {
        if ($this->accountFrom === $accountTo) {
            throw new Exception('Same source and destination accounts.');
        }

        $this->accountTo = $accountTo;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt() : DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return TransactionEntity
     *
     * @throws Exception
     */
    public function setAmount(int $amount) : TransactionEntity
    {
        if ($amount <= 0) {
            throw new Exception('Invalid amount - ' . $amount);
        }

        $this->amount = $amount;

        return $this;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $this->accountFrom->makeWithdraval($this);
        $this->accountTo->makeDeposit($this);
    }
}
