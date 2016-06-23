<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RoleEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 * @ORM\Table(name="role")
 * @ORM\Entity()
 */
class RoleEntity extends Entity
{

    use Traits\IntegerIdTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $name;

    /**
     * RoleEntity constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
     * @return RoleEntity
     */
    public function setName(string $name) : RoleEntity
    {
        $this->name = $name;

        return $this;
    }
}
