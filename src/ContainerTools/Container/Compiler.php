<?php
/**
 * Created by PhpStorm.
 * User: jacker
 * Date: 02/09/15
 * Time: 14:23
 */

namespace ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Configuration\Loader as ConfigLoader;

class Compiler
{
    /**
     * @var ContainerLoader
     */
    private $loader;

    /**
     * Compiler constructor.
     */
    public function __construct(ConfigLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param Configuration $configuration
     *
     * @return ContainerBuilder
     */
    public function compile(Configuration $configuration)
    {
        $container = $this->loader->loadContainer($configuration);

        foreach ($configuration->getCompilerPasses() as $compilerPass) {
            $container->addCompilerPass($compilerPass);
        }

        $container->compile();

        return $container;
    }
}