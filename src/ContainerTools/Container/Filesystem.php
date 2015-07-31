<?php

namespace ContainerTools\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper as ContainerDumper;
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
     * @param SymfonyFilesystem $filesystem
     * @param string $containerFilePath
     */
    public function __construct(SymfonyFilesystem $filesystem, $containerFilePath)
    {
        $this->containerFilePath = $containerFilePath;
        $this->filesystem = $filesystem;
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function dump(ContainerBuilder $containerBuilder)
    {
        $dumper = new ContainerDumper($containerBuilder);

        $this->filesystem->dumpFile($this->containerFilePath, $dumper->dump());
    }

    public function exists($file)
    {
        return $this->filesystem->exists($file);
    }
}
