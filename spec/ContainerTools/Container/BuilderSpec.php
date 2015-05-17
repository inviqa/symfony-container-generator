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
    private $configFolders = [];

    private $containerFile = 'container.php';

    function let(
        Loader $loader,
        ContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem
    ) {
        $loader->load($this->configFolders)->willReturn($loader);

        $this->beConstructedWith($loader, $containerBuilder, $dumper, $containerLoader, $filesystem);
    }

    function it_does_not_cache_the_container_when_in_debug_mode(
        Loader $loader,
        ContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem
    ) {
        $debug = true;

        $filesystem->exists(Argument::any())->willReturn();

        $loader->into($containerBuilder)->shouldBeCalled();
        $containerBuilder->compile()->shouldBeCalled();

        $dumper->dump($containerBuilder)->shouldNotBeCalled();
        $containerLoader->requireOnce(Argument::any())->shouldNotBeCalled();

        $configuration = Configuration::fromParameters($this->containerFile, $this->configFolders, $debug);

        $this->build($configuration);
    }


    function it_builds_and_caches_a_new_container_if_none_exists_yet_when_not_in_debug_mode(
        Loader $loader,
        ContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem
    ) {
        $debug = false;
        $filesystem->exists(Argument::any())->willReturn(false);

        $loader->into($containerBuilder)->shouldBeCalled();
        $containerBuilder->compile()->shouldBeCalled();
        $dumper->dump($containerBuilder)->shouldBeCalled();

        $containerLoader->requireOnce(Argument::any())->shouldNotBeCalled();

        $configuration = Configuration::fromParameters($this->containerFile, $this->configFolders, $debug);

        $this->build($configuration);
    }



    function it_loads_an_existing_container_if_it_exists_when_not_in_debug_mode(
        Loader $loader,
        ContainerBuilder $containerBuilder,
        Dumper $dumper,
        ContainerLoader $containerLoader,
        Filesystem $filesystem
    ) {
        $debug = false;
        $filesystem->exists(Argument::any())->willReturn(true);

        $loader->into($containerBuilder)->shouldNotBeCalled();
        $containerBuilder->compile()->shouldNotBeCalled();
        $dumper->dump($containerBuilder)->shouldNotBeCalled();

        $containerLoader->requireOnce($this->containerFile)->shouldBeCalled();

        $configuration = Configuration::fromParameters($this->containerFile, $this->configFolders, $debug);

        $this->build($configuration);
    }
}
