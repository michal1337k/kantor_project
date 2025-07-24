<?php

// src/Command/UpdateCurrencyRatesCommand.php
namespace App\Command;

use App\Service\ExchangeRateFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-currency-rates',
    description: 'Aktualizuje kursy walut z API NBP.',
)]
final class UpdateCurrencyRatesCommand extends Command
{
    public function __construct(private ExchangeRateFetcher $fetcher)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->fetcher->updateRates();
        $output->writeln('Zaktualizowano kursy walut.');
        return Command::SUCCESS;
    }
}

?>