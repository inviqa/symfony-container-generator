<?php

namespace ContainerTools\Configuration;

use ContainerTools\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

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
     * @param ContainerBuilder $containerBuilder
     */
    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    /**
     * @param Configuration $configuration
     *
     * @return $this
     */
    public function load(Configuration $configuration)
    {
        $this->serviceConfigs = $configuration->getServicesFolders();
        $this->servicesFormat = $configuration->getServicesFormat();

        return $this;
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function into(ContainerBuilder $containerBuilder)
    {
        $loader = new DelegatingLoader(new LoaderResolver(array(
            new XmlFileLoader($containerBuilder, new FileLocator($this->serviceConfigs)),
            new YamlFileLoader($containerBuilder, new FileLocator($this->serviceConfigs)),
        )));

        $loader->load('services.' . $this->servicesFormat);
    }

    /**
     * @param Configuration $configuration
     * @return ContainerBuilder
     */
    public function loadContainer(Configuration $configuration)
    {
        $this->load($configuration)->into($this->containerBuilder);

        return $this->containerBuilder;
    }
} 