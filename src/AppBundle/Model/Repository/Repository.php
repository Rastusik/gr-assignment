<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Repository
 * @author mfris
 * @package AppBundle\Model\Repository
 */
abstract class Repository extends EntityRepository
{

    /**
     * @param string $attribute
     * @param mixed $value
     * @return array
     */
    public function search(string $attribute, $value)
    {
        return $this->getSearchQueryBuilder($attribute, $value)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getSearchQueryBuilder(string $attribute, $value)
    {
        return $this->createQueryBuilder('e')
                    ->where("e.{$attribute} LIKE :value")
                    ->setParameter('value', "%{$value}%");
    }
}
