<?php

namespace ContainerTools\Container;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class Filesystem
{
    /**
     * @var SymfonyFilesystem
     */
    private $filesystem;

    /**
     * @var ContainerDumperFactory
     */
    private $dumperFactory;
    /**
     * @var ConfigCache
     */
    private $containerConfigCache;

    /**
     * @param SymfonyFilesystem $filesystem
     * @param ContainerDumperFactory $dumperFactory
     * @param ConfigCache $containerConfigCache
     */
    public function __construct(SymfonyFilesystem $filesystem, ContainerDumperFactory $dumperFactory, ConfigCache $containerConfigCache)
    {
        $this->filesystem = $filesystem;
        $this->dumperFactory = $dumperFactory;
        $this->containerConfigCache = $containerConfigCache;
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function dump(ContainerBuilder $containerBuilder)
    {
        $dumper = $this->dumperFactory->create($containerBuilder);

        $this->containerConfigCache->write(
            $dumper->dump(),
            $containerBuilder->getResources()
        );
    }

    public function exists($file)
    {
        return $this->filesystem->exists($file);
    }

    public function isCacheFresh()
    {
        return $this->containerConfigCache->isFresh();
    }
}
