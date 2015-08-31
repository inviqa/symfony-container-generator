<?php

namespace ContainerTools;

use Symfony\Component\DependencyInjection\Container;
use Pimple\Container as PimpleContainer;

class ContainerGenerator
{
    /**
     * @var PimpleContainer
     */
    private $pimple;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->pimple = ServiceDefinitions::create($configuration);
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->pimple['container_builder']->build();
    }
}
