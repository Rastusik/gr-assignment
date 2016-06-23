<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Dto;

use AppBundle\Model\Entity\AccountEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Transaction
 * @author mfris
 * @package AppBundle\Model\Dto
 */
class Transaction
{

    /**
     * @var AccountEntity
     */
    private $accountFrom;

    /**
     * @var AccountEntity
     */
    private $accountTo;

    /**
     * @var int
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "The smallest transfer amount is {{ limit }}.",
     * )
     */
    private $amount = 0;

    /**
     * Transaction constructor.
     * @param AccountEntity $accountFrom
     */
    public function __construct(AccountEntity $accountFrom)
    {
        $this->accountFrom = $accountFrom;
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
     */
    public function setAccountFrom($accountFrom)
    {
        $this->accountFrom = $accountFrom;
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
     */
    public function setAccountTo($accountTo)
    {
        $this->accountTo = $accountTo;
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
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}
