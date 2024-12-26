<?php

/** generated from /home/tac/g/survos/survos/vendor/survos/maker-bundle/templates/skeleton/bundle/src/Bundle.tpl.php */

namespace Survos\TheGuardianBundle;

use Survos\TheGuardianBundle\Command\TheGuardianListCommand;
use Survos\TheGuardianBundle\Controller\TheGuardianController;
use Survos\TheGuardianBundle\Service\TheGuardianService;
use Survos\TheGuardianBundle\Twig\TwigExtension;
use Survos\SimpleDatatables\SurvosSimpleDatatablesBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SurvosTheGuardianBundle extends AbstractBundle
{

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // get all bundles https://symfony.com/doc/current/bundles/prepend_extension.html
        $bundles = $builder->getParameter('kernel.bundles');
        $hasSimpleDatatables = in_array(SurvosSimpleDatatablesBundle::class, array_values($bundles));

        $serviceId = 'survos_the-guardian.the-guardian_service';
        $container->services()->alias(TheGuardianService::class, $serviceId);
        $builder->autowire($serviceId, TheGuardianService::class)
            ->setArgument('$apiKey', $config['api_key'])
            ->setArgument('$cacheTimeout', $config['cache_timeout'])
            ->setAutoconfigured(true)
            ->setAutowired(true)
            ->setPublic(true);

        $builder->autowire(TheGuardianController::class)
            ->setArgument('$simpleDatatablesInstalled', $hasSimpleDatatables)
            ->addTag('container.service_subscriber')
            ->addTag('controller.service_arguments')
        ;

        foreach ([TheGuardianListCommand::class] as $commandName) {
            $builder->autowire($commandName)
                ->setAutoconfigured(true)
                ->addTag('console.command')
            ;
        }

//        // twig classes, for the-guardian_url ?
//        $builder
//            ->autowire('survos.the-guardian_twig', TwigExtension::class)
//            ->setAutoconfigured(true)
//            ->addTag('twig.extension');
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $rootNode = $definition->rootNode();
        $rootNode
            ->children()
                ->scalarNode('api_key')->defaultNull()->end()
                ->integerNode('cache_timeout')->defaultValue(3600)->end()
//                ->scalarNode('region')->defaultValue(null)->end()
//                ->scalarNode('readonly_password')->defaultValue(null)->end()
//                ->scalarNode('password')->defaultValue(null)->end()
            ->end();

    }

}
