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
    public function __construct(SymfonyFilesystem $filesystem, ContainerDumperFactory $dumperFactory, $containerFilePath)
    {
        $this->containerFilePath = $containerFilePath;
        $this->filesystem = $filesystem;
        $this->dumperFactory = $dumperFactory;
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function dump(ContainerBuilder $containerBuilder)
    {
        $dumper = $this->dumperFactory->create($containerBuilder);
        $this->filesystem->dumpFile($this->containerFilePath, $dumper->dump());
    }

    public function exists($file)
    {
        return $this->filesystem->exists($file);
    }
}
