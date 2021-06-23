<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\GenerateCustomersService;

class CreateCustomersCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-customers';

    private $generateCustomersService;

    public function __construct(GenerateCustomersService $generateCustomersService)
    {
        $this->generateCustomersService = $generateCustomersService;

        parent::__construct();
    }

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    { 
        $output->writeln('Importing customers data, please wait...');
        $this->generateCustomersService->generateCustomers();
        $output->writeln('Customer data successfully imported');
        return Command::SUCCESS;
    }
}