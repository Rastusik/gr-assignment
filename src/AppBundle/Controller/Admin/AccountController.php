<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\Helper;
use AppBundle\Service\User\Transactioner;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Model\Entity\AccountEntity;
use AppBundle\Service\Crud\Account as AccountService;
use AppBundle\Form\AccountEntityType;
use Symfony\Component\HttpFoundation\Response;

/**
 * AccountEntity controller.
 *
 * @Route(path="/admin/account", service="controller.admin.account")
 */
class AccountController
{

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * @var Transactioner
     */
    private $transactioner;

    /**
     * UserController constructor.
     * @param Helper $helper
     * @param AccountService $accountService
     * @param Transactioner $transactioner
     */
    public function __construct(Helper $helper, AccountService $accountService, Transactioner $transactioner)
    {
        $this->helper = $helper;
        $this->accountService = $accountService;
        $this->transactioner = $transactioner;
    }

    /**
     * Lists all AccountEntity entities.
     *
     * @Route("/", name="admin_account_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $accounts = $this->accountService->findAll();

        return $this->helper->render('admin/account/index.html.twig', array(
            'accounts' => $accounts,
        ));
    }

    /**
     * Finds and displays a AccountEntity entity.
     *
     * @Route("/{id}", name="admin_account_show", requirements={
     *     "id": "\d+"
     * })
     * @Method("GET")
     * @param AccountEntity $account
     * @return Response
     */
    public function showAction(AccountEntity $account)
    {
        $deleteForm = $this->createDeleteForm($account);
        $transactions = $this->transactioner->getTransactionsByAccount($account);

        return $this->helper->render('admin/account/show.html.twig', [
            'account' => $account,
            'delete_form' => $deleteForm->createView(),
            'transactions' => $transactions,
        ]);
    }

    /**
     *
     * @Route(path="/search", name="admin_account_search")
     * @Method("GET")
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $search = $request->get('search', '');
        $accounts = $this->accountService->search($search);

        return $this->helper->render('admin/account/index.html.twig', [
            'accounts' => $accounts,
            'search' => $search,
        ]);
    }

    /**
     * Creates a new UserEntity entity.
     *
     * @Route("/new", name="admin_account_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->editAction($request, new AccountEntity());
    }

    /**
     * Displays a form to edit an existing UserEntity entity.
     *
     * @Route("/{id}/edit", name="admin_account_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param AccountEntity $account
     *
     * @return Response
     */
    public function editAction(Request $request, AccountEntity $account)
    {
        $deleteForm = $account->getId() ? $this->createDeleteForm($account)->createView() : null;
        $editForm = $this->helper->createForm(AccountEntityType::class, $account);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $account = $editForm->getData();
            $this->accountService->save($account);

            return $this->helper->redirectToRoute('admin_account_show', ['id' => $account->getId()]);
        }

        return $this->helper->render('admin/account/save.html.twig', [
            'account' => $account,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm,
        ]);
    }

    /**
     * Deletes a AccountEntity entity.
     *
     * @Route("/{id}", name="admin_account_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param AccountEntity $account
     *
     * @return Response
     */
    public function deleteAction(Request $request, AccountEntity $account)
    {
        $form = $this->createDeleteForm($account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountService->delete($account);
        }

        return $this->helper->redirectToRoute('admin_account_index');
    }

    /**
     * @param AccountEntity $account
     * @return Form
     */
    private function createDeleteForm(AccountEntity $account)
    {
        return $this->helper->createDeleteForm(
            $this->helper->generateUrl('admin_account_delete', ['id' => $account->getId()])
        );
    }
}
