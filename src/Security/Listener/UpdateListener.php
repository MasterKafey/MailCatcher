<?php

namespace App\Security\Listener;

use App\Business\DatabaseBusiness;
use App\Business\EnvironmentBusiness;
use App\Business\UpdateBusiness;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: 'kernel.request')]
class UpdateListener
{
    public function __construct(
        private readonly UpdateBusiness  $updateBusiness,
        private readonly RouterInterface $router,
        private readonly EnvironmentBusiness $environmentBusiness,
    )
    {

    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->updateBusiness->needUpdate() || $this->environmentBusiness->isCurrentEnvironment(EnvironmentBusiness::DEV_ENVIRONMENT)) {
            return;
        }

        $currentRoute = $event->getRequest()->attributes->get('_route');
        $updateCode = $this->updateBusiness->getUpdateCode();

        if (null === $updateCode && $currentRoute !== 'app_update_missing_update_code') {
            $event->setResponse(new RedirectResponse($this->router->generate('app_update_missing_update_code')));
        } else if (null !== $updateCode && $currentRoute !== 'app_update_database') {
            $event->setResponse(new RedirectResponse($this->router->generate('app_update_database')));
        }
    }
}