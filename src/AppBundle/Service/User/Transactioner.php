<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\User;

use AppBundle\Model\Entity\AccountEntity;
use AppBundle\Model\Repository\TransactionRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Transactioner
 * @author mfris
 * @package AppBundle\Service\User
 */
class Transactioner
{

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * Transactioner constructor.
     * @param TransactionRepository $repository
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TransactionRepository $repository, TokenStorage $tokenStorage)
    {
        $this->repository = $repository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param AccountEntity $account
     * @return array
     */
    public function getTransactionsByAccount(AccountEntity $account) : array
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user->getRole()->getName() === 'ROLE_ADMIN' || $account->getOwner() === $user) {
            $user = null;
        }

        return $this->repository->getByAccount($account, $user);
    }
}
