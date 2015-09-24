<?php

namespace ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Configuration\Loader as ConfigLoader;
use ContainerTools\Container\Loader as ContainerLoader;
use Symfony\Component\DependencyInjection\Container;

class Builder
{
    /**
     * @var ConfigLoader
     */
    private $loader;

    /**
     * @var ContainerLoader
     */
    private $containerLoader;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param ConfigLoader $loader
     * @param ContainerLoader $containerLoader
     * @param Filesystem $filesystem
     */
    public function __construct(
        ConfigLoader $loader,
        ContainerLoader $containerLoader,
        Filesystem $filesystem
    ) {
        $this->loader = $loader;
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
        $containerFilePath = $configuration->getContainerFilePath();
        $containerHasBeenBuilt = $this->filesystem->exists($containerFilePath);
        $isDebug = $configuration->getDebug();

        if ($isDebug) {
            $container = $this->compile($configuration);
        } else if ($containerHasBeenBuilt) {
            $container = $this->containerLoader->loadFrom($containerFilePath);
        } else {
            $container = $this->compile($configuration);
            $this->filesystem->dump($container);
        }

        return $container;
    }

    /**
     * @param Configuration $configuration
     *
     * @return Container
     */
    private function compile(Configuration $configuration)
    {
        $container = $this->loader->loadContainer($configuration);

        foreach ($configuration->getCompilerPasses() as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }

        $container->compile();

        return $container;
    }
}
