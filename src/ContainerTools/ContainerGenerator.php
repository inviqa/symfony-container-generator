<?php

namespace ContainerTools;

use ContainerTools\Configuration\DelegatingLoaderFactory;
use ContainerTools\Container\ContainerDumperFactory;
use ContainerTools\Container\Filesystem;
use ContainerTools\Container\Loader as ContainerLoader;
use ContainerTools\Configuration\Loader as ConfigurationLoader;
use Symfony\Component\Config\ConfigCache;
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
        $containerConfigCache = new ConfigCache($this->configuration->getContainerFilePath(), $this->configuration->getDebug());

        $builder = new Builder(
            new ConfigurationLoader(new SymfonyContainerBuilder(), new DelegatingLoaderFactory(), new SymfonyFilesystem()),
            new ContainerLoader(),
            new Filesystem(new SymfonyFilesystem(), new ContainerDumperFactory(), $containerConfigCache)
        );

        return $builder->build($this->configuration);
    }
}