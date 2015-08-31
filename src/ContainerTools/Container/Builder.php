<?php

namespace ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Configuration\Loader as ConfigLoader;
use ContainerTools\Container\Loader as ContainerLoader;
use Symfony\Component\DependencyInjection\Container;

class Builder
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var Loader
     */
    private $containerLoader;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param ConfigLoader $loader
     * @param Loader $containerLoader
     * @param Filesystem $filesystem
     * @param Configuration $configuration
     */
    public function __construct(
        ConfigLoader $loader,
        ContainerLoader $containerLoader,
        Filesystem $filesystem,
        Configuration $configuration
    ) {
        $this->loader = $loader;
        $this->containerLoader = $containerLoader;
        $this->filesystem = $filesystem;
        $this->configuration = $configuration;
    }

    /**
     * @return Container
     */
    public function build()
    {
        $containerFilePath = $this->configuration->getContainerFilePath();
        $containerHasBeenBuilt = $this->filesystem->exists($containerFilePath);
        $isDebug = $this->configuration->getDebug();

        if ($isDebug) {
            $container = $this->compile();
        } else if ($containerHasBeenBuilt) {
            $container = $this->containerLoader->loadFrom($containerFilePath);
        } else {
            $container = $this->compile();
            $this->filesystem->dump($container);
        }

        return $container;
    }

    /**
     * @return ContainerBuilder
     */
    private function compile()
    {
        $container = $this->loader->loadContainer($this->configuration);

        foreach ($this->configuration->getCompilerPasses() as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }

        $container->compile();

        return $container;
    }
}
