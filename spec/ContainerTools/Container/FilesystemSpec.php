<?php

namespace spec\ContainerTools\Container;

use ContainerTools\Container\ContainerDumperFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemSpec extends ObjectBehavior
{
    function let(Filesystem $filesystem, ContainerDumperFactory $dumperFactory, ConfigCache $configCache)
    {
        $this->beConstructedWith($filesystem, $dumperFactory, $configCache);
    }

    function it_returns_true_if_file_exists(Filesystem $filesystem)
    {
        $filesystem->exists('somefile.php')->willReturn(true);

        $this->exists('somefile.php')->shouldReturn(true);
    }

    function it_uses_config_cache_to_create_the_cache(Filesystem $filesystem, ContainerDumperFactory $dumperFactory, ContainerBuilder $containerBuilder, PhpDumper $dumper, ConfigCache $configCache)
    {
        $dumper->dump()->willReturn('file contents');
        $dumperFactory->create($containerBuilder)->willReturn($dumper);
        $containerBuilder->getResources()->willReturn([]);

        $this->dump($containerBuilder);

        $configCache->write('file contents', [])->shouldHaveBeenCalled();
    }
}
