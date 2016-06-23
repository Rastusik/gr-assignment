<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\User;

use AppBundle\Model\Dto\Transaction;
use AppBundle\Model\Entity\AccountEntity;
use AppBundle\Model\Entity\TransactionEntity;
use AppBundle\Model\Entity\UserEntity;
use AppBundle\Model\Repository\AccountRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class AccountService
 * @author mfris
 * @package AppBundle\Service\User
 */
class Accounter
{

    /**
     * @var AccountRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * User constructor.
     * @param AccountRepository $repository
     * @param EntityManager $entityManager
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        AccountRepository $repository,
        EntityManager $entityManager,
        TokenStorage $tokenStorage
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public function findInvolvedAccounts()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->repository->findByInvolvedUser($user);
    }

    /**
     * @param string $search
     * @return array
     */
    public function searchInvolvedAccounts(string $search)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->repository->searchByInvolvedUser($user, $search);
    }

    /**
     * @param AccountEntity $account
     * @param UserEntity $disponent
     * @throws \RuntimeException
     */
    public function removeDisponentFromAccount(AccountEntity $account, UserEntity $disponent)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($account->getOwner() !== $user) {
            throw new \RuntimeException('You do not own the account.');
        }

        $account->removeDisponent($disponent);
        $this->entityManager->flush();
    }

    /**
     * @param AccountEntity $account
     * @param UserEntity $disponent
     * @throws \RuntimeException
     */
    public function addDisponentFromAccount(AccountEntity $account, UserEntity $disponent)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($account->getOwner() !== $user) {
            throw new \RuntimeException('You do not own the account.');
        }

        $account->addDisponent($disponent);
        $this->entityManager->flush();
    }

    /**
     * @param Transaction $transaction
     */
    public function executeTransaction(Transaction $transaction)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $transactionEntity =
            new TransactionEntity(
                $user,
                $transaction->getAccountFrom(),
                $transaction->getAccountTo(),
                $transaction->getAmount()
            );
        $transactionEntity->execute();

        $this->entityManager->persist($transactionEntity);
        $this->entityManager->flush();
    }

    /**
     * @param string $searchTerm
     * @param AccountEntity $account
     * @return array
     */
    public function searchExclude(string $searchTerm, AccountEntity $account) : array
    {
        return $this->repository->searchExclude('id', $searchTerm, $account->getId());
    }
}
