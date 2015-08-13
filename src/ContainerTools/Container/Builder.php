<?php

namespace ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Configuration\Loader as ConfigLoader;
use ContainerTools\Container\Loader as ContainerLoader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
     * @param Loader $containerLoader
     * @param Filesystem $filesystem
     */
    public function __construct(
        ConfigLoader $loader,
        SymfonyContainerBuilder $containerBuilder,
        ContainerLoader $containerLoader,
        Filesystem $filesystem
    ) {
        $this->loader = $loader;
        $this->containerBuilder = $containerBuilder;
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
            $container = $this->compile($configuration);
        } else {
            if ($containerHasBeenBuilt) {
                $container = $this->containerLoader->requireOnce($configuration->getContainerFilePath());
            } else {
                $container = $this->compile($configuration);
                $this->filesystem->dump($container);
            }
        }

        return $container;
    }

    /**
     * @param string $cachedContainer
     *
     * @return boolean
     */
    private function hasContainerBeenBuilt($cachedContainer)
    {
        return $this->filesystem->exists($cachedContainer);
    }

    /**
     * @param Configuration $configuration
     *
     * @return ContainerBuilder
     */
    private function compile(Configuration $configuration)
    {
        $container = $this->buildContainer($configuration);

        foreach ($configuration->getCompilerPasses() as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }

        $container->compile();

        return $container;
    }

    /**
     * @param Configuration $configuration
     * @return ContainerBuilder
     */
    private function buildContainer(Configuration $configuration)
    {
        $this->loader
            ->load($configuration)
            ->into($this->containerBuilder);

        return $this->containerBuilder;
    }
}
