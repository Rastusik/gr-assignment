<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Crud;

use AppBundle\Model\Entity\AccountEntity;
use AppBundle\Model\Repository\AccountRepository;
use AppBundle\Model\Repository\Repository;
use Doctrine\ORM\EntityManager;

/**
 * Class Account
 * @author mfris
 * @package AppBundle\Service\Crud
 */
class Account extends Crud
{

    /**
     * User constructor.
     * @param AccountRepository $repository
     * @param EntityManager $entityManager
     */
    public function __construct(
        AccountRepository $repository,
        EntityManager $entityManager
    ) {
        parent::__construct($repository, $entityManager);
    }

    /**
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->doSearch('id', $searchTerm);
    }

    /**
     * @param AccountEntity $account
     */
    public function save(AccountEntity $account)
    {
        $this->doSave($account);
    }

    /**
     * @param AccountEntity $account
     */
    public function delete(AccountEntity $account)
    {
        $this->doDelete($account);
    }
}
