<?php

namespace AppBundle\Controller;

use AppBundle\Model\Entity\UserEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @author mfris
 * @package AppBundle\Controller
 * @Route(path="/", service="controller.default")
 */
class DefaultController extends Controller
{

    /**
     * @var Helper
     */
    private $helper;

    /**
     * DefaultController constructor.
     * @param Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function indexAction()
    {
        /* @var $user UserEntity */
        $user = $this->helper->getUser();

        if (!$user) {
            return $this->helper->redirectToRoute('login');
        } elseif ($user->getRole()->getName() === 'ROLE_ADMIN') {
            return $this->helper->redirectToRoute('admin_user_index');
        }

        return $this->helper->redirectToRoute('user_account_index');
    }
}
