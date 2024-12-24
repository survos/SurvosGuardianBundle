<?php

namespace Survos\TheGuardianBundle\Command;

use Survos\TheGuardianBundle\Service\TheGuardianService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\Table;
use Zenstruck\Console\Attribute\Argument;
use Zenstruck\Console\Attribute\Option;
use Zenstruck\Console\InvokableServiceCommand;
use Zenstruck\Console\IO;
use Zenstruck\Console\RunsCommands;
use Zenstruck\Console\RunsProcesses;

#[AsCommand('guardian:list', 'list the-guardian sources and articles (various endpoints)')]
final class TheGuardianListCommand extends InvokableServiceCommand
{
    use RunsCommands;
    use RunsProcesses;

    public function __construct(
        private readonly TheGuardianService $theGuardianService,
    )
    {
        parent::__construct();
    }

    public function __invoke(
        IO                                                                                          $io,
        #[Argument(description: 'endpoint (source, search)')] string        $endpoint='',
        #[Option(description: 'filter by top')] bool $top = false,
        #[Option(description: 'search string')] ?string $q=null,
        #[Option(description: '2-letter language code')] string $locale='en',

    ): int
    {
        if ($q) {
            $query = $this->theGuardianService->contentApi()
                ->setQuery($q);
            $response = $this->theGuardianService->fetch($query);

            $table = new Table($io);
            $table->setHeaderTitle($q);
            $headers = ['Title', 'Url'];
            $table->setHeaders($headers);
            foreach ($response->results as $rowData) {
                $row = [
                    $rowData->webTitle,
                    $rowData->webUrl,
                ];
                $table->addRow($row);
            }
            $table->render();
        }
        return self::SUCCESS;

    }




}
