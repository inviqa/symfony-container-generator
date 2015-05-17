<?php

namespace ContainerTools\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper as ContainerDumper;

class Dumper
{
    /**
     * @var string
     */
    private $containerFilePath;

    /**
     * @param string $containerFilePath
     */
    public function __construct($containerFilePath)
    {
        $this->containerFilePath = $containerFilePath;
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function dump(ContainerBuilder $containerBuilder)
    {
        $dumper = new ContainerDumper($containerBuilder);

        file_put_contents($this->containerFilePath, $dumper->dump());
    }
}
