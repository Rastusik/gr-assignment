<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use AppBundle\Model\Exception;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AccountEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="AppBundle\Model\Repository\AccountRepository")
 */
class AccountEntity extends Entity
{

    use Traits\IntegerIdTrait;

    /**
     * @var UserEntity
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\Entity\UserEntity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $owner;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Model\Entity\UserEntity")
     * @ORM\JoinTable(name="account_disponent__account__user",
     *   joinColumns={
     *     @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   }
     * )
     */
    private $disponents;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $balance = 0;

    /**
     * AccountEntity constructor.
     */
    public function __construct()
    {
        $this->disponents = new ArrayCollection();
    }

    /**
     * @return UserEntity
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param UserEntity $owner
     *
     * @return AccountEntity
     */
    public function setOwner(UserEntity $owner) : AccountEntity
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisponents()
    {
        return $this->disponents;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $disponents
     *
     * @return AccountEntity
     */
    public function setDisponents(Collection $disponents) : AccountEntity
    {
        $this->disponents = $disponents;

        return $this;
    }

    /**
     * Add category
     *
     * @param UserEntity $disponent
     *
     * @return AccountEntity
     */
    public function addDisponent(UserEntity $disponent) : AccountEntity
    {
        $this->disponents[] = $disponent;

        return $this;
    }

    /**
     * Remove category
     *
     * @param UserEntity $disponent
     *
     * @return AccountEntity
     */
    public function removeDisponent(UserEntity $disponent) : AccountEntity
    {
        $this->disponents->removeElement($disponent);

        return $this;
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     *
     * @return AccountEntity
     */
    public function setBalance(int $balance) : AccountEntity
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @param UserEntity $disponent
     * @return bool
     */
    public function hasDisponent(UserEntity $disponent)
    {
        return $this->disponents->contains($disponent);
    }

    /**
     * @param TransactionEntity $transaction
     * @return bool
     *
     * @throws Exception
     */
    public function makeWithdraval(TransactionEntity $transaction)
    {
        $amount = $transaction->getAmount();

        if ($this->balance < $amount) {
            throw new Exception('Unable to make transaction - not enough resources.');
        }

        $this->balance -= $amount;

        return true;
    }

    /**
     * @param TransactionEntity $transaction
     * @return bool
     *
     * @throws Exception
     */
    public function makeDeposit(TransactionEntity $transaction)
    {
        $amount = $transaction->getAmount();

        if ($amount < 0) {
            throw new Exception('Unable to make deposit - negative amount.');
        }

        $this->balance += $amount;

        return true;
    }
}
