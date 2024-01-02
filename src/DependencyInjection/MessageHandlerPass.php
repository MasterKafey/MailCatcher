<?php

namespace App\DependencyInjection;

use App\Business\SMTPBusiness;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MessageHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $smtpBusiness = $container->findDefinition(SMTPBusiness::class);
        $messageHandlers = $container->findTaggedServiceIds('app.smtp.message_handler');

        foreach ($messageHandlers as $messageHandler => $id) {
            $smtpBusiness->addMethodCall('addMessageHandler', [new Reference($messageHandler)]);
        }
    }
}