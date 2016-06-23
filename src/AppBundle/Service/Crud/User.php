<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Crud;

use AppBundle\Model\Dto\Password;
use AppBundle\Model\Entity\UserEntity;
use AppBundle\Model\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class User
 * @author mfris
 * @package AppBundle\Service\Crud
 */
class User extends Crud
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * User constructor.
     * @param UserRepository $repository
     * @param EntityManager $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UserRepository $repository,
        EntityManager $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct($repository, $entityManager);
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->doSearch('name', $searchTerm);
    }

    /**
     * @param UserEntity $user
     */
    public function save(UserEntity $user)
    {
        $this->doSave($user);
    }

    /**
     * @param UserEntity $user
     */
    public function delete(UserEntity $user)
    {
        $this->doDelete($user);
    }

    /**
     * @param UserEntity $user
     * @param Password $password
     */
    public function changePassword(UserEntity $user, Password $password)
    {
        $hashedPassword = $this->passwordEncoder->encodePassword($user, $password->getPassword());
        $user->setPassword($hashedPassword);

        $this->doSave($user);
    }
}
