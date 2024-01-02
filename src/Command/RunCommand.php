<?php

namespace App\Command;

use App\Business\SMTPBusiness;
use App\SMTP\Session;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(name: 'app:run', description: 'Run SMTP Server')]
class RunCommand extends Command
{


    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly SMTPBusiness          $smtpBusiness
    )
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $socket = new SocketServer('127.0.0.1:2525');
        $domainName = $this->parameterBag->get('domain_name');

        $socket->on('connection', function (ConnectionInterface $connection) use ($domainName) {
            $session = new Session($connection);
            $connection->write("220 $domainName SMTP server ready\r\n");
            $connection->on('data', function ($data) use ($session) {
                $response = $this->smtpBusiness->handleMessage($data, $session);
                if (null !== $response) {
                    $session->getConnection()->write("$response\r\n");
                }
            });
        });

        return Command::SUCCESS;
    }
}