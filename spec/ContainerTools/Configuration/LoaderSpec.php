<?php

namespace spec\ContainerTools\Configuration;

use ContainerTools\Configuration;
use ContainerTools\Configuration\DelegatingLoaderFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LoaderSpec extends ObjectBehavior
{
    function let(ContainerBuilder $containerBuilder, DelegatingLoaderFactory $delegatingLoaderFactory, Configuration $configuration)
    {
        $this->beConstructedWith($containerBuilder, $delegatingLoaderFactory);

        $configuration->getServicesFormat()->willReturn('xml');
        $configuration->isTestEnvironment()->willReturn(false);
    }

    function it_loads_services_into_a_container(
        Configuration $configuration,
        ContainerBuilder $containerBuilder,
        DelegatingLoaderFactory $delegatingLoaderFactory,
        DelegatingLoader $delegatingLoader
    ) {
        $configuration->getServicesFolders()->willReturn(['etc1/']);

        $delegatingLoaderFactory->create($containerBuilder, 'etc1/')->willReturn($delegatingLoader);

        $this->loadContainer($configuration)->shouldReturnAnInstanceOf(Container::class);

        $delegatingLoader->load('services.xml')->shouldHaveBeenCalled();

    }

    function it_loads_services_into_a_container_from_multiple_paths(
        Configuration $configuration,
        ContainerBuilder $containerBuilder,
        DelegatingLoaderFactory $delegatingLoaderFactory,
        DelegatingLoader $delegatingLoader1,
        DelegatingLoader $delegatingLoader2
    ){
        $configuration->getServicesFolders()->willReturn(['etc1/', 'etc2/']);

        $delegatingLoaderFactory->create($containerBuilder, 'etc1/')->willReturn($delegatingLoader1);
        $delegatingLoaderFactory->create($containerBuilder, 'etc2/')->willReturn($delegatingLoader2);

        $this->loadContainer($configuration)->shouldReturnAnInstanceOf(Container::class);

        $delegatingLoader1->load('services.xml')->shouldHaveBeenCalled();
        $delegatingLoader2->load('services.xml')->shouldHaveBeenCalled();
    }

    function it_loads_services_into_a_container_including_test_services(
        Configuration $configuration,
        ContainerBuilder $containerBuilder,
        DelegatingLoaderFactory $delegatingLoaderFactory,
        DelegatingLoader $delegatingLoader1,
        DelegatingLoader $delegatingLoader2
    ) {
        $configuration->getServicesFolders()->willReturn(['etc1/', 'etc2/']);
        $configuration->isTestEnvironment()->willReturn(true);

        $delegatingLoaderFactory->create($containerBuilder, 'etc1/')->willReturn($delegatingLoader1);
        $delegatingLoaderFactory->create($containerBuilder, 'etc2/')->willReturn($delegatingLoader2);

        $this->loadContainer($configuration)->shouldReturnAnInstanceOf(Container::class);

        $delegatingLoader1->load('services.xml')->shouldHaveBeenCalled();
        $delegatingLoader2->load('services.xml')->shouldHaveBeenCalled();

        $delegatingLoader1->load('services_test.xml')->shouldHaveBeenCalled();
        $delegatingLoader2->load('services_test.xml')->shouldHaveBeenCalled();
    }
}
