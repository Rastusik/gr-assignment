<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Dto;

/**
 * Class Password
 * @author mfris
 * @package AppBundle\Model\Dto
 */
class Password
{

    /**
     * @var string
     */
    private $password = '';

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Password
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
