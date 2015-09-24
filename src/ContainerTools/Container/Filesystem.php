<?php

namespace ContainerTools\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class Filesystem
{
    /**
     * @var string
     */
    private $containerFilePath;

    /**
     * @var SymfonyFilesystem
     */
    private $filesystem;

    /**
     * @var ContainerDumperFactory
     */
    private $dumperFactory;

    /**
     * @param SymfonyFilesystem $filesystem
     * @param ContainerDumperFactory $dumperFactory
     * @param string $containerFilePath
     */
    public function __construct(SymfonyFilesystem $filesystem, ContainerDumperFactory $dumperFactory)
    {
        $this->filesystem = $filesystem;
        $this->dumperFactory = $dumperFactory;
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function dump(ContainerBuilder $containerBuilder, $containerFilePath)
    {
        $dumper = $this->dumperFactory->create($containerBuilder);
        $this->filesystem->dumpFile($containerFilePath, $dumper->dump());
    }

    public function exists($file)
    {
        return $this->filesystem->exists($file);
    }
}
