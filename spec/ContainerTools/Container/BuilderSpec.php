<?php

namespace spec\ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Container\Filesystem;
use ContainerTools\Container\Loader as ContainerLoader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ContainerTools\Configuration\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BuilderSpec extends ObjectBehavior
{
    private $containerFile = 'container.php';

    function let(
        Loader $loader,
        ContainerLoader $containerLoader,
        Filesystem $filesystem,
        ContainerBuilder $containerBuilder,
        Configuration $configuration
    ) {
        $configuration->getServicesFormat()->willReturn('xml');
        $configuration->getServicesFolders()->willReturn([]);
        $configuration->getContainerFilePath()->willReturn($this->containerFile);
        $configuration->getCompilerPasses()->willReturn([]);

        $containerLoader->loadFrom($this->containerFile)->willReturn($containerBuilder);

        $this->beConstructedWith($loader, $containerLoader, $filesystem);
    }

    function it_builds_and_caches_a_new_container_if_none_exists_yet_when_and_cache_is_not_fresh(
        Loader $loader,
        ContainerLoader $containerLoader,
        ContainerBuilder $containerBuilder,
        Filesystem $filesystem,
        Configuration $configuration
    ) {
        $filesystem->isCacheFresh()->willReturn(false);

        $loader->loadContainer($configuration)->willReturn($containerBuilder);

        $containerBuilder->compile()->shouldBeCalled();
        $filesystem->dump($containerBuilder)->shouldBeCalled();

        $this->build($configuration)->shouldReturn($containerBuilder);
    }

    function it_loads_an_existing_container_if_cache_is_fresh(
        ContainerBuilder $containerBuilder,
        ContainerLoader $containerLoader,
        Filesystem $filesystem,
        Configuration $configuration,
        Loader $loader
    ) {
        $filesystem->isCacheFresh()->willReturn(true);

        $filesystem->dump(Argument::any())->shouldNotBeCalled();
        $loader->loadContainer(Argument::any())->shouldNotBeCalled();

        $this->build($configuration)->shouldReturn($containerBuilder);
    }
}
