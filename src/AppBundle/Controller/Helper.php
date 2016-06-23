<?php
/**
 * @author     mfris
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

/**
 *
 * @author  mfris
 * @package AppBundle\Controller
 */
class Helper
{

    /**
     * symfony request stack
     *
     * @var RequestStack
     */
    private $requestStack;

    /**
     * symfony http kernel
     *
     * @var HttpKernel
     */
    private $httpKernel;

    /**
     * symfony router
     *
     * @var Router
     */
    private $router;

    /**
     * symfony session
     *
     * @var Session
     */
    private $session;

    /**
     * symfony twig engine
     *
     * @var TwigEngine
     */
    private $templating;

    /**
     * symfony auth checker
     *
     * @var AuthorizationChecker
     */
    private $authChecker;

    /**
     * symfony user token storage
     *
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * symfony csrf token manager
     *
     * @var CsrfTokenManager
     */
    private $csrfTokenManager;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * Context constructor.
     * @param RequestStack $requestStack
     * @param HttpKernel $httpKernel
     * @param Router $router
     * @param Session $session
     * @param TwigEngine $templating
     * @param AuthorizationChecker $authChecker
     * @param TokenStorageInterface $tokenStorage
     * @param CsrfTokenManager $csrfTokenManager
     * @param FormFactory $formFactory
     */
    public function __construct(
        RequestStack $requestStack,
        HttpKernel $httpKernel,
        Router $router,
        Session $session,
        TwigEngine $templating,
        AuthorizationChecker $authChecker,
        TokenStorageInterface $tokenStorage,
        CsrfTokenManager $csrfTokenManager,
        FormFactory $formFactory
    ) {
        $this->requestStack = $requestStack;
        $this->httpKernel = $httpKernel;
        $this->router = $router;
        $this->session = $session;
        $this->templating = $templating;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->formFactory = $formFactory;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string      $route         The name of the route
     * @param mixed       $parameters    An array of parameters
     * @param bool|string $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    /**
     * Forwards the request to another controller.
     *
     * @param string $controller The controller name (a string like BlogBundle:Post:index)
     * @param array  $path       An array of path parameters
     * @param array  $query      An array of query parameters
     *
     * @return Response A Response instance
     */
    public function forward($controller, array $path = array(), array $query = array())
    {
        $path['_controller'] = $controller;
        $subRequest = $this->requestStack->getCurrentRequest()->duplicate($query, null, $path);

        return $this->httpKernel>handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url    The URL to redirect to
     * @param int    $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param string $route      The name of the route
     * @param array  $parameters An array of parameters
     * @param int    $status     The status code to use for the Response
     *
     * @return RedirectResponse
     */
    public function redirectToRoute($route, array $parameters = array(), $status = 302)
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type    The type
     * @param string $message The message
     *
     * @throws \LogicException
     */
    public function addFlash($type, $message)
    {
        $this->session->getFlashBag()->add($type, $message);
    }

    /**
     * Returns flash bag object with flash messages
     *
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
     */
    public function getFlashBag()
    {
        return $this->session->getFlashBag();
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @param mixed $attributes The attributes
     * @param mixed $object     The object
     *
     * @return bool
     *
     * @throws \LogicException
     */
    public function isGranted($attributes, $object = null)
    {
        return $this->authChecker->isGranted($attributes, $object);
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    public function renderView($view, array $parameters = array())
    {
        return $this->templating->render($view, $parameters);
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->templating->renderResponse($view, $parameters, $response);
    }

    /**
     * Streams a view.
     *
     * @param string           $view       The view name
     * @param array            $parameters An array of parameters to pass to the view
     * @param StreamedResponse $response   A response instance
     *
     * @return StreamedResponse A StreamedResponse instance
     */
    public function stream($view, array $parameters = array(), StreamedResponse $response = null)
    {
        $templating = $this->templating;

        $callback = function () use ($templating, $view, $parameters) {
            $templating->stream($view, $parameters);
        };

        if (null === $response) {
            return new StreamedResponse($callback);
        }

        $response->setCallback($callback);

        return $response;
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    public function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }

    /**
     * Checks the validity of a CSRF token.
     *
     * @param string $id    The id used when generating the token
     * @param string $token The actual token sent with the request that should be validated
     *
     * @return bool
     */
    public function isCsrfTokenValid($id, $token) : bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string $type    The fully qualified class name of the form type
     * @param mixed  $data    The initial data for the form
     * @param array  $options Options for the form
     *
     * @return Form
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     *
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return FormBuilder
     */
    public function createFormBuilder($data = null, array $options = array()) : FormBuilder
    {
        return $this->formFactory->createBuilder(FormType::class, $data, $options);
    }

    /**
     * @param string $url
     * @return Form
     */
    public function createDeleteForm(string $url) : Form
    {
        return $this->createFormBuilder()
            ->setAction($url)
            ->setMethod('DELETE')
            ->getForm();
    }
}
