<?php

namespace ContainerTools\Configuration;

use ContainerTools\Configuration;
use Symfony\Component\Config\Loader\DelegatingLoader;
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
     * @var string
     */
    private $environment;

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
    ) {
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
        $this->environment = $configuration->getEnvironment();
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

        $this->attemptToLoad($loader, $path, $servicesFile);

        // Prod services are in services.yml and therefore should not be mockable with env specific files
        if($this->environment !== "prod")
        {
            $servicesEnvFile = 'services_' . $this->environment . '.' . $this->servicesFormat;
            $this->attemptToLoad($loader, $path, $servicesEnvFile);
        }
    }

    /**
     * Load and parse the file if it exists
     *
     * @param DelegatingLoader $loader
     * @param string $path
     * @param string $fileName
     */
    private function attemptToLoad(DelegatingLoader $loader, $path, $fileName)
    {
        if ($this->filesystem->exists($path . '/' . $fileName)) {
            $loader->load($fileName);
        }
    }
}
