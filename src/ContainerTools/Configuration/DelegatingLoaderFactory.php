<?php

namespace ContainerTools\Configuration;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DelegatingLoaderFactory
{

    public function create(ContainerBuilder $containerBuilder, $path)
    {
        $fileLocator = new FileLocator($path);

        return new DelegatingLoader(new LoaderResolver(array(
            new XmlFileLoader($containerBuilder, $fileLocator),
            new YamlFileLoader($containerBuilder, $fileLocator),
        )));
    }
}