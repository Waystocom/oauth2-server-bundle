<?php

namespace OAuth2ServerBundle\Command;

use Exception;
use OAuth2ServerBundle\Exception\ScopeNotFoundException;
use OAuth2ServerBundle\Manager\ClientManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateClientCommand
 */
class CreateClientCommand extends Command
{
    /**
     * @var ClientManager
     */
    protected ClientManager $clientManager;

    /**
     * CreateClientCommand constructor.
     *
     * @param ClientManager $clientManager
     * @param string|null   $name
     */
    public function __construct(ClientManager $clientManager, ?string $name = null)
    {
        parent::__construct($name);
        $this->clientManager = $clientManager;
    }

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('OAuth2:CreateClient')
            ->setDescription('Create a OAuth2 client')
            ->addArgument('identifier', InputArgument::REQUIRED, 'The client identifier')
            ->addArgument(
                'redirect_uri',
                InputArgument::REQUIRED,
                'The client redirect uris (comma separated)'
            )
            ->addArgument(
                'grant_types',
                InputArgument::OPTIONAL,
                'Grant types to restrict the client to (comma separated)'
            )
            ->addArgument(
                'scopes',
                InputArgument::OPTIONAL,
                'Scopes to restrict the client to (comma separated)'
            );
    }

    /**
     * execute
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $client = $this->clientManager->createClient(
                $input->getArgument('identifier'),
                explode(',', $input->getArgument('redirect_uri')),
                explode(',', $input->getArgument('grant_types')),
                explode(',', $input->getArgument('scopes'))
            );
        } catch (ScopeNotFoundException $exception) {
                $output->writeln('<fg=red>Scope not found, please create it first</fg=red>');

                return 1;
        } catch (Exception $exception) {
            $output->writeln(
                '<fg=red>Unable to create client ' . $input->getArgument('identifier') . '</fg=red>'
            );
            $output->writeln('<fg=red>' . $exception->getMessage() . '</fg=red>');

            return 1;
        }

        $output->writeln(
            '<fg=green>Client '
            . $input->getArgument('identifier')
            . ' created with secret '
            . $client->getClientSecret()
            . '</fg=green>'
        );

        return 0;
    }
}
