<?php

namespace ContainerTools;

use ContainerTools\Configuration\DelegatingLoaderFactory;
use ContainerTools\Container\Build\BuildChainHandler;
use ContainerTools\Container\Build\BuildHandler;
use ContainerTools\Container\Build\ContainerAlreadyBuiltHandler;
use ContainerTools\Container\Build\DebugModeHandler;
use ContainerTools\Container\Build\RebuildContainerHandler;
use ContainerTools\Container\Compiler;
use ContainerTools\Container\ContainerDumperFactory;
use ContainerTools\Container\Filesystem;
use ContainerTools\Container\Loader as ContainerLoader;
use ContainerTools\Configuration\Loader as ConfigurationLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use ContainerTools\Container\Builder;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class ContainerGenerator
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container = $this->container ?: $this->buildContainer();
    }


    /**
     * @return Container
     */
    private function buildContainer()
    {
        $configurationLoader = new ConfigurationLoader(new SymfonyContainerBuilder(), new DelegatingLoaderFactory(), new SymfonyFilesystem());
        $compiler = new Compiler($configurationLoader);
        $filesystem = new Filesystem(new SymfonyFilesystem(), new ContainerDumperFactory(), $this->configuration->getContainerFilePath());

        $builder = new Builder(
            new BuildChainHandler(
                new DebugModeHandler($compiler),
                new ContainerAlreadyBuiltHandler(new ContainerLoader(), $filesystem),
                new RebuildContainerHandler($compiler, $filesystem)
            )
        );

        return $builder->build($this->configuration);
    }
}