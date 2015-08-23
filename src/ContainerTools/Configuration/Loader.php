<?php

namespace ContainerTools\Configuration;

use ContainerTools\Configuration;
use Symfony\Component\Filesystem\Filesystem;
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
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param ContainerBuilder $containerBuilder
     * @param DelegatingLoaderFactory $delegatingLoaderFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        ContainerBuilder $containerBuilder,
        DelegatingLoaderFactory $delegatingLoaderFactory,
        Filesystem $filesystem
    )
    {
        $this->containerBuilder = $containerBuilder;
        $this->delegatingLoaderFactory = $delegatingLoaderFactory;
        $this->filesystem = $filesystem;
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

        $servicesFile = 'services.' . $this->servicesFormat;
        $servicesTestFile = 'services_test.' . $this->servicesFormat;

        if ($this->filesystem->exists($path . '/' . $servicesFile)) {
            $loader->load($servicesFile);
        }

        if ($this->isTestEnvironment && $this->filesystem->exists($path . '/' . $servicesTestFile)) {
            $loader->load($servicesTestFile);
        }
    }
}
