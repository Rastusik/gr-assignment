<?php
/**
 * @author  mfris
 */

namespace AppBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Serializable;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Model\Repository\UserRepository")
 */
class UserEntity extends Entity implements UserInterface, Serializable
{
    use Traits\IntegerIdTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=75, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $password = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $phone = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address = '';

    /**
     * @var RoleEntity
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\Entity\RoleEntity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $role;

    /**
     * UserEntity constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return UserEntity
     */
    public function setUsername(string $username) : UserEntity
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return UserEntity
     */
    public function setName(string $name) : UserEntity
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone() : string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return UserEntity
     */
    public function setPhone(string $phone) : UserEntity
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return UserEntity
     */
    public function setAddress(string $address) : UserEntity
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return RoleEntity
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param RoleEntity $role
     *
     * @return UserEntity
     */
    public function setRole(RoleEntity $role) : UserEntity
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return UserEntity
     */
    public function setPassword(string $password) : UserEntity
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return [$this->role->getName()];
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }
}
