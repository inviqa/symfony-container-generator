<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * ProjectServiceContainer.
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 */
class ProjectServiceContainer extends Container
{
    private $parameters;
    private $targetDirs = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();
        $this->scopes = array();
        $this->scopeChildren = array();
        $this->methodMap = array(
            'my_service2' => 'getMyService2Service',
            'my_servicxe1' => 'getMyServicxe1Service',
        );

        $this->aliases = array();
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        throw new LogicException('You cannot compile a dumped frozen container.');
    }

    /**
     * Gets the 'my_service2' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \MyService2 A MyService2 instance.
     */
    protected function getMyService2Service()
    {
        return $this->services['my_service2'] = new \MyService2();
    }

    /**
     * Gets the 'my_servicxe1' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \ModifiedService A ModifiedService instance.
     */
    protected function getMyServicxe1Service()
    {
        return $this->services['my_servicxe1'] = new \ModifiedService();
    }
}