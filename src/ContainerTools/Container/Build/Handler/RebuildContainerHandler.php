<?php

namespace ContainerTools\Container\Build\Handler;

use ContainerTools\Container\Build\Handler;
use ContainerTools\Container\Build\Request;
use ContainerTools\Container\Compiler;
use ContainerTools\Container\Filesystem;

class RebuildContainerHandler extends Handler
{
    /**
     * @var Compiler
     */
    private $compiler;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Compiler $compiler
     * @param Filesystem $filesystem
     */
    public function __construct(Compiler $compiler, Filesystem $filesystem)
    {
        $this->compiler = $compiler;
        $this->filesystem = $filesystem;
    }

    public function process(Request $request)
    {
        $container = $this->compiler->compile($request->getConfiguration());

        $this->filesystem->dump($container, $request->getConfiguration()->getContainerFilePath());

        $request->setContainer($container);
    }
}
