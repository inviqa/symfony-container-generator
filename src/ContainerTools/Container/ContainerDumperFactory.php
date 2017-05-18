<?php
namespace ContainerTools\Container;

use Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerDumperFactory
{
    public function create(ContainerBuilder $containerBuilder)
    {
        $dumper = new PhpDumper($containerBuilder);
        if (class_exists('\Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper')) {
            $proxyDumper = new ProxyDumper();
            $dumper->setProxyDumper($proxyDumper);
        }
        return $dumper;
    }
}
