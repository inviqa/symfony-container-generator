<?php
namespace ContainerTools\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerDumperFactory
{
    public function create(ContainerBuilder $containerBuilder)
    {
        return new PhpDumper($containerBuilder);
    }
}
