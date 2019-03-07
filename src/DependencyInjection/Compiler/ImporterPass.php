<?php

namespace App\DependencyInjection\Compiler;

use App\Import\SaveGameImportProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ImporterPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        $services = $this->findAndSortTaggedServices('importer.saved-game', $container);

        $processorService = $container->findDefinition(SaveGameImportProcessor::class);
        $processorService->setArgument('$importers', $services);
    }
}
