<?php

namespace AppBundle\Controller\User;

use AppBundle\Controller\Helper;
use AppBundle\Model\Entity\UserEntity;
use AppBundle\Service\User\Transactioner;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Model\Entity\AccountEntity;
use AppBundle\Service\User\Accounter;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\Crud\User as UserService;

/**
 * AccountEntity controller.
 *
 * @Route(path="/user/account", service="controller.user.account")
 */
class AccountController
{

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var Accounter
     */
    private $accounter;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Transactioner
     */
    private $transactioner;

    /**
     * UserController constructor.
     * @param Helper $helper
     * @param Accounter $accounter
     * @param UserService $userService
     * @param Transactioner $transactioner
     */
    public function __construct(
        Helper $helper,
        Accounter $accounter,
        UserService $userService,
        Transactioner $transactioner
    ) {
        $this->helper = $helper;
        $this->accounter = $accounter;
        $this->userService = $userService;
        $this->transactioner = $transactioner;
    }

    /**
     * Lists all AccountEntity entities.
     *
     * @Route("/", name="user_account_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction()
    {
        $accounts = $this->accounter->findInvolvedAccounts();

        return $this->helper->render('user/account/index.html.twig', array(
            'accounts' => $accounts,
        ));
    }

    /**
     * Finds and displays a AccountEntity entity.
     *
     * @Route("/{id}", name="user_account_show", requirements={
     *     "id": "\d+"
     * })
     * @Method("GET")
     * @param AccountEntity $account
     * @return Response
     */
    public function showAction(AccountEntity $account)
    {
        $transactions = $this->transactioner->getTransactionsByAccount($account);

        return $this->helper->render('user/account/show.html.twig', [
            'account' => $account,
            'transactions' => $transactions,
        ]);
    }

    /**
     *
     * @Route(path="/search", name="user_account_search")
     * @Method("GET")
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $search = $request->get('search', '');
        $accounts = $this->accounter->searchInvolvedAccounts($search);

        return $this->helper->render('user/account/index.html.twig', [
            'accounts' => $accounts,
            'search' => $search,
        ]);
    }

    /**
     *
     * @Route(
     *     path="/{id}/remove-disponent/{disponentId}",
     *     name="user_account_remove_disponent",
     *     requirements={
     *          "id": "\d+",
     *          "disponentId": "\d+"
     *     })
     * @ParamConverter("disponent", class="AppBundle:UserEntity", options={"id" = "disponentId"})
     * @Method("GET")
     * @param AccountEntity $account
     * @param UserEntity $disponent
     *
     * @return Response
     */
    public function removeDisponentAction(AccountEntity $account, UserEntity $disponent)
    {
        $this->accounter->removeDisponentFromAccount($account, $disponent);

        return $this->helper->redirectToRoute('user_account_show', ['id' => $account->getId()]);
    }

    /**
     *
     * @Route(
     *     path="/{id}/add-disponent/{disponentId}",
     *     name="user_account_add_disponent",
     *     requirements={
     *          "id": "\d+",
     *          "disponentId": "\d+"
     *     })
     * @ParamConverter("disponent", class="AppBundle:UserEntity", options={"id" = "disponentId"})
     * @Method("GET")
     * @param AccountEntity $account
     * @param UserEntity $disponent
     *
     * @return Response
     */
    public function addDisponentAction(AccountEntity $account, UserEntity $disponent)
    {
        $this->accounter->addDisponentFromAccount($account, $disponent);

        return $this->helper->redirectToRoute('user_account_show', ['id' => $account->getId()]);
    }

    /**
     *
     * @Route(path="/{id}/search-disponent", name="user_account_show_disponent_search")
     * @Method("GET")
     * @param Request $request
     * @param AccountEntity $account
     *
     * @return Response
     */
    public function searchDisponentAction(Request $request, AccountEntity $account)
    {
        $search = $request->get('search', '');
        $disponents = $this->userService->search($search);

        return $this->helper->render('user/account/show.html.twig', [
            'account' => $account,
            'search' => $search,
            'foundDisponents' => $disponents,
        ]);
    }
}
