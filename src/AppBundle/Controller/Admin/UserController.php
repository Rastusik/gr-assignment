<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\Helper;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\UserEntityType;
use AppBundle\Model\Dto\Password;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Model\Entity\UserEntity;
use AppBundle\Service\Crud\User as UserService;
use Symfony\Component\HttpFoundation\Response;

/**
 * UserEntity controller.
 *
 * @Route(path="/admin/user", service="controller.admin.user")
 */
class UserController
{

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     * @param Helper $helper
     * @param UserService $userService
     */
    public function __construct(Helper $helper, UserService $userService)
    {
        $this->helper = $helper;
        $this->userService = $userService;
    }

    /**
     * Lists all UserEntity entities.
     *
     * @Route(path="/", name="admin_user_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction()
    {
        $users = $this->userService->findAll();

        return $this->helper->render('admin/user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Lists all UserEntity entities.
     *
     * @Route(path="/search", name="admin_user_search")
     * @Method("GET")
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $search = $request->get('search', '');
        $users = $this->userService->search($search);

        return $this->helper->render('admin/user/index.html.twig', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    /**
     * Finds and displays a UserEntity entity.
     *
     * @Route("/{id}", name="admin_user_show", requirements={
     *     "id": "\d+"
     * })
     * @Method("GET")
     * @param UserEntity $user
     * @return Response
     */
    public function showAction(UserEntity $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->helper->render('admin/user/show.html.twig', [
            'user' => $user,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * Creates a new UserEntity entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->editAction($request, new UserEntity());
    }

    /**
     * Displays a form to edit an existing UserEntity entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param UserEntity $user
     *
     * @return Response
     */
    public function editAction(Request $request, UserEntity $user)
    {
        $deleteForm = $user->getId() ? $this->createDeleteForm($user)->createView() : null;
        $editForm = $this->helper->createForm(UserEntityType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $user = $editForm->getData();
            $this->userService->save($user);

            return $this->helper->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
        }

        return $this->helper->render('admin/user/save.html.twig', [
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm,
        ]);
    }

    /**
     * Displays a form to edit an existing UserEntity entity.
     *
     * @Route("/{id}/password", name="admin_user_change_password")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param UserEntity $user
     *
     * @return Response
     */
    public function changePasswordAction(Request $request, UserEntity $user)
    {
        $changePasswordForm = $this->helper->createForm(ChangePasswordType::class, new Password());
        $changePasswordForm->handleRequest($request);

        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            $password = $changePasswordForm->getData();
            $this->userService->changePassword($user, $password);

            return $this->helper->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        return $this->helper->render('admin/user/change_password.html.twig', [
            'user' => $user,
            'change_password_form' => $changePasswordForm->createView(),
        ]);
    }

    /**
     * Deletes a UserEntity entity.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param UserEntity $user
     *
     * @return Response
     */
    public function deleteAction(Request $request, UserEntity $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->delete($user);
        }

        return $this->helper->redirectToRoute('admin_user_index');
    }

    /**
     * @param UserEntity $user
     * @return Form
     */
    private function createDeleteForm(UserEntity $user)
    {
        return $this->helper->createDeleteForm(
            $this->helper->generateUrl('admin_user_delete', ['id' => $user->getId()])
        );
    }
}
