<?php

namespace ContainerTools;

use ContainerTools\Configuration\DelegatingLoaderFactory;
use ContainerTools\Container\Builder;
use ContainerTools\Container\ContainerDumperFactory;
use ContainerTools\Container\Filesystem;
use Pimple\Container as PimpleContainer;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use ContainerTools\Container\Loader as ContainerLoader;
use ContainerTools\Configuration\Loader as ConfigurationLoader;

class ServiceDefinitions
{
    /**
     * @param Configuration $configuration
     *
     * @return PimpleContainer
     */
    public static function create(Configuration $configuration)
    {
        $container = new PimpleContainer();
        $container['symfony.filesystem'] = function ($c) {
            return new SymfonyFilesystem();
        };

        $container['configuration_loader'] = function ($c) {
            return new ConfigurationLoader(new SymfonyContainerBuilder(), new DelegatingLoaderFactory(), $c['symfony.filesystem']);
        };

        $container['container_builder'] = function ($c) use ($configuration) {
            return new Builder(
                $c['configuration_loader'],
                new ContainerLoader(),
                new Filesystem($c['symfony.filesystem'], new ContainerDumperFactory(), $configuration->getContainerFilePath()),
                $configuration
            );
        };

        return $container;
    }
}