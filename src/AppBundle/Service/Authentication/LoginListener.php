<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Authentication;

use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class LoginListener
 * @author mfris
 * @package AppBundle\Service\Authentication
 */
class LoginListener
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var TokenStorage
     */
    private $token;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Router $router
     * @param TokenStorage $token
     * @param EventDispatcherInterface $dispatcher
     * @param Logger $logger
     */
    public function __construct(
        Router $router,
        TokenStorage $token,
        EventDispatcherInterface $dispatcher,
        Logger $logger
    ) {
        $this->router       = $router;
        $this->token        = $token;
        $this->dispatcher   = $dispatcher;
        $this->logger       = $logger;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $roles = $this->token->getToken()->getRoles();

        $rolesTab = array_map(function (Role $role) {
            return $role->getRole();
        }, $roles);

        $this->logger->info(var_export($rolesTab, true));

        $route = $this->router->generate('user_account_index');

        if (in_array('ROLE_ADMIN', $rolesTab)) {
            $route = $this->router->generate('admin_user_index');
        }

        $event->getResponse()->headers->set('Location', $route);
    }
}
