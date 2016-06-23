<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Crud;

use AppBundle\Model\Entity\Entity;
use AppBundle\Model\Repository\Repository;
use Doctrine\ORM\EntityManager;

/**
 * Class Crud
 * @author mfris
 * @package AppBundle\Service\Crud
 */
abstract class Crud
{

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Crud constructor.
     * @param Repository $repository
     * @param EntityManager $entityManager
     */
    public function __construct(Repository $repository, EntityManager $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return null|Entity
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function findAll() : array
    {
        return $this->repository->findAll();
    }

    /**
     * @param mixed $searchTerm
     * @return array
     */
    abstract public function search($searchTerm);

    /**
     * @param Entity $entity
     *
     * @return void
     * @throws
     */
    protected function doSave(Entity $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
    }

    /**
     * @param Entity $entity
     */
    protected function doDelete(Entity $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush($entity);
    }

    /**
     * @param string $attribute
     * @param mixed $value
     *
     * @return array
     */
    protected function doSearch(string $attribute, $value) : array
    {
        return $this->repository->search($attribute, $value);
    }
}
