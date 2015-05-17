<?php

namespace ContainerTools;

use ContainerTools\Container\Dumper;
use ContainerTools\Container\Loader as ContainerLoader;
use ContainerTools\Configuration\Loader as ConfigurationLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use ContainerTools\Container\Builder;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;

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
     * @param string $containerFilePath
     * @param array $configurationFolders
     * @param bool $debug
     */
    public function __construct($containerFilePath, array $configurationFolders, $debug = true)
    {
        $this->configuration = Configuration::fromParameters($containerFilePath, $configurationFolders, $debug);
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container ?: $this->buildContainer();
    }

    /**
     * @return Container
     */
    private function buildContainer()
    {
        $builder = new Builder(
            new ConfigurationLoader(),
            new SymfonyContainerBuilder(),
            new Dumper($this->configuration->getContainerFilePath()),
            new ContainerLoader(),
            new Filesystem()
        );

        return $builder->build($this->configuration);
    }
} 