<?php

namespace spec\ContainerTools\Container;

use ContainerTools\Container\ContainerDumperFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemSpec extends ObjectBehavior
{
    private $path = '/path/file.php';

    function let(Filesystem $filesystem, ContainerDumperFactory $dumperFactory)
    {
        $this->beConstructedWith($filesystem, $dumperFactory, $this->path);
    }

    function it_returns_true_if_file_exists(Filesystem $filesystem)
    {
        $filesystem->exists('somefile.php')->willReturn(true);

        $this->exists('somefile.php')->shouldReturn(true);
    }

    function it_uses_container_dump_to_create_the_cache(Filesystem $filesystem, ContainerDumperFactory $dumperFactory, ContainerBuilder $containerBuilder, PhpDumper $dumper)
    {
        $dumper->dump()->willReturn('file contents');
        $dumperFactory->create($containerBuilder)->willReturn($dumper);

        $this->dump($containerBuilder);

        $filesystem->dumpFile($this->path, 'file contents')->shouldHaveBeenCalled();
    }
}
