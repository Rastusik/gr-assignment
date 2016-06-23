<?php
/**
 * @author  mfris
 */

namespace AppBundle\Model\Repository;

use AppBundle\Model\Entity\AccountEntity;
use AppBundle\Model\Entity\UserEntity;

/**
 * Class TransactionRepository
 * @author mfris
 */
class TransactionRepository extends Repository
{

    /**
     * @param AccountEntity $account
     * @param UserEntity|null $disponent
     * @return array
     */
    public function getByAccount(AccountEntity $account, UserEntity $disponent = null) : array
    {
        $builder = $this->createQueryBuilder('t')
            ->select('t', 'd', 'at')
            ->join('t.disponent', 'd')
            ->join('t.accountTo', 'at')
            ->where('(t.accountFrom = :account1 OR t.accountTo = :account2)')
            ->orderBy('t.id', 'DESC')
            ->setParameter('account1', $account)
            ->setParameter('account2', $account);

        if ($disponent) {
            $builder->andWhere('d = :disponent')
                    ->setParameter('disponent', $disponent);
        }

        return $builder->getQuery()
                       ->getResult();
    }
}
