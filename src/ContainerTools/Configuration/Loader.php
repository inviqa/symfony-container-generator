<?php

namespace ContainerTools\Configuration;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Loader
{
    private $serviceConfigs;

    /**
     * @param array $serviceConfigs
     *
     * @return $this
     */
    public function load(array $serviceConfigs)
    {
        $this->serviceConfigs = $serviceConfigs;

        return $this;
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    public function into(ContainerBuilder $containerBuilder)
    {
        $loader = new YamlFileLoader($containerBuilder, new FileLocator($this->serviceConfigs));

        $loader->load('services.yml');
    }
} 