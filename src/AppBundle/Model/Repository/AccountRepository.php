<?php
/**
 * @author  mfris
 */

namespace AppBundle\Model\Repository;

use AppBundle\Model\Entity\UserEntity;

/**
 * Class AccountRepository
 * @author mfris
 */
class AccountRepository extends Repository
{

    /**
     * @param UserEntity $user
     * @return array
     */
    public function findByInvolvedUser(UserEntity $user) : array
    {
        return $this->getInvolvedByUserQueryBuilder($user)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param UserEntity $user
     * @param string $search
     * @return array
     */
    public function searchByInvolvedUser(UserEntity $user, string $search) : array
    {
        return $this->getInvolvedByUserQueryBuilder($user)
                    ->andWhere('a.id LIKE :search')
                    ->setParameter('search', "%{$search}%")
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param string $attribute
     * @param $value
     * @param int $excludeValue
     * @return array
     */
    public function searchExclude(string $attribute, $value, int $excludeValue) : array
    {
        return $this->getSearchQueryBuilder($attribute, $value)
                    ->andWhere("e.{$attribute} != :excludeValue")
                    ->setParameter('excludeValue', $excludeValue)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param UserEntity $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getInvolvedByUserQueryBuilder(UserEntity $user)
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'o', 'd')
            ->join('a.owner', 'o')
            ->leftJoin('a.disponents', 'd')
            ->where('a.owner = :owner')
            ->orWhere('d.id = :disponent')
            ->orderBy('a.id')
            ->setParameter('owner', $user)
            ->setParameter('disponent', $user);
    }
}
