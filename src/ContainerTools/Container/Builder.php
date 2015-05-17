<?php

namespace ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Configuration\Loader as ConfigLoader;
use ContainerTools\Container\Loader as ContainerLoader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;

class Builder
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var SymfonyContainerBuilder
     */
    private $containerBuilder;

    /**
     * @var Dumper
     */
    private $dumper;

    /**
     * @var Loader
     */
    private $containerLoader;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param ConfigLoader $loader
     * @param SymfonyContainerBuilder $containerBuilder
     * @param Dumper $dumper
     * @param Loader $containerLoader
     * @param Filesystem $filesystem
     */
    public function __construct(
        ConfigLoader $loader,
        SymfonyContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem
    ) {
        $this->loader = $loader;
        $this->containerBuilder = $containerBuilder;
        $this->dumper = $dumper;
        $this->containerLoader = $containerLoader;
        $this->filesystem = $filesystem;
    }

    /**
     * @param Configuration $configuration
     *
     * @return Container
     */
    public function build(Configuration $configuration)
    {
        $containerHasBeenBuilt = $this->hasContainerBeenBuilt($configuration->getContainerFilePath());
        $isDebug = $configuration->getDebug();

        if ($isDebug) {
            $container = $this->compile($configuration->getServicesFolders());
        } else {
            if ($containerHasBeenBuilt) {
                $container = $this->containerLoader->requireOnce($configuration->getContainerFilePath());
            } else {
                $container = $this->compile($configuration->getServicesFolders());
                $this->dumper->dump($container);
            }
        }

        return $container;
    }

    /**
     * @param $cachedContainer
     *
     * @return boolean
     */
    private function hasContainerBeenBuilt($cachedContainer)
    {
        return $this->filesystem->exists($cachedContainer);
    }

    /**
     * @param array $configurationFolders
     *
     * @return ContainerBuilder
     */
    private function compile(array $configurationFolders)
    {
        $container = $this->buildContainer($configurationFolders);
        $container->compile();

        return $container;
    }

    /**
     * @return Container
     */
    private function buildContainer(array $configurationFolders)
    {
        $this->loader
            ->load($configurationFolders)
            ->into($this->containerBuilder);

        return $this->containerBuilder;
    }
}

