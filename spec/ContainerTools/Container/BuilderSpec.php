<?php

namespace spec\ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Container\Dumper;
use ContainerTools\Container\Loader as ContainerLoader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ContainerTools\Configuration\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;

class BuilderSpec extends ObjectBehavior
{
    private $containerFile = 'container.php';

    function let(
        Loader $loader,
        ContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem,
        Configuration $configuration
    ) {
        $configuration->getServicesFormat()->willReturn('xml');
        $configuration->getServicesFolders()->willReturn([]);
        $configuration->getContainerFilePath()->willReturn($this->containerFile);

        $loader->load($configuration)->willReturn($loader);

        $this->beConstructedWith($loader, $containerBuilder, $dumper, $containerLoader, $filesystem);
    }

    function it_does_not_cache_the_container_when_in_debug_mode(
        Loader $loader,
        ContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem,
        Configuration $configuration
    ) {
        $configuration->getDebug()->willReturn(true);
        $filesystem->exists(Argument::any())->willReturn();

        $loader->into($containerBuilder)->shouldBeCalled();
        $containerBuilder->compile()->shouldBeCalled();

        $dumper->dump($containerBuilder)->shouldNotBeCalled();
        $containerLoader->requireOnce(Argument::any())->shouldNotBeCalled();

        $this->build($configuration);
    }


    function it_builds_and_caches_a_new_container_if_none_exists_yet_when_not_in_debug_mode(
        Loader $loader,
        ContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem,
        Configuration $configuration
    ) {
        $configuration->getDebug()->willReturn(false);
        $filesystem->exists(Argument::any())->willReturn(false);

        $loader->into($containerBuilder)->shouldBeCalled();
        $containerBuilder->compile()->shouldBeCalled();
        $dumper->dump($containerBuilder)->shouldBeCalled();

        $containerLoader->requireOnce(Argument::any())->shouldNotBeCalled();

        $this->build($configuration);
    }

    function it_loads_an_existing_container_if_it_exists_when_not_in_debug_mode(
        Loader $loader,
        ContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem,
        Configuration $configuration
    ) {
        $configuration->getDebug()->willReturn(false);
        $filesystem->exists(Argument::any())->willReturn(true);

        $loader->into($containerBuilder)->shouldNotBeCalled();
        $containerBuilder->compile()->shouldNotBeCalled();
        $dumper->dump($containerBuilder)->shouldNotBeCalled();

        $containerLoader->requireOnce($this->containerFile)->shouldBeCalled();

        $this->build($configuration);
    }
}
