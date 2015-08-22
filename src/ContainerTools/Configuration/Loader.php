<?php

namespace ContainerTools\Configuration;

use ContainerTools\Configuration;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Loader
{
    /**
     * @var array
     */
    private $serviceConfigs;

    /**
     * @var string
     */
    private $servicesFormat;

    /**
     * @var ContainerBuilder
     */
    private $containerBuilder;

    /**
     * @var bool
     */
    private $isTestEnvironment;

    /**
     * @var DelegatingLoaderFactory
     */
    private $delegatingLoaderFactory;

    /**
     * @param ContainerBuilder $containerBuilder
     * @param DelegatingLoaderFactory $delegatingLoaderFactory
     */
    public function __construct(ContainerBuilder $containerBuilder, DelegatingLoaderFactory $delegatingLoaderFactory)
    {
        $this->containerBuilder = $containerBuilder;
        $this->delegatingLoaderFactory = $delegatingLoaderFactory;
    }

    /**
     * @param Configuration $configuration
     */
    public function configure(Configuration $configuration)
    {
        $this->serviceConfigs = $configuration->getServicesFolders();
        $this->servicesFormat = $configuration->getServicesFormat();
        $this->isTestEnvironment = $configuration->isTestEnvironment();
    }

    /**
     * @param Configuration $configuration
     *
     * @return ContainerBuilder
     */
    public function loadContainer(Configuration $configuration)
    {
        $this->configure($configuration);

        array_walk($this->serviceConfigs, array($this, 'process'));

        return $this->containerBuilder;
    }

    /**
     * @param $path
     *
     * @throws FileLoaderLoadException
     */
    private function process($path)
    {
        $loader = $this->delegatingLoaderFactory->create($this->containerBuilder, $path);

        $loader->load('services.' . $this->servicesFormat);

        if ($this->isTestEnvironment) {
            $loader->load('services_test.' . $this->servicesFormat);
        }
    }
}
