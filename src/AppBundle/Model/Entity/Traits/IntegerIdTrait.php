<?php

/**
 * @author  mfris
 */

namespace AppBundle\Model\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of IdEntity
 *
 * @author mfris
 */
trait IntegerIdTrait
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = 0;

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->id;
    }
}
