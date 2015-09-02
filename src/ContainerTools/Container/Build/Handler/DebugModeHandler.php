<?php

namespace ContainerTools\Container\Build\Handler;

use Behat\Testwork\ServiceContainer\ContainerLoader;
use ContainerTools\Container\Build\Handler;
use ContainerTools\Container\Build\Request;
use ContainerTools\Container\Compiler;

class DebugModeHandler extends Handler
{
    /**
     * @var ContainerLoader
     */
    private $compiler;

    /**
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    public function process(Request $request)
    {

        if ($request->getConfiguration()->getDebug()) {
            $request->setContainer($this->compiler->compile($request->getConfiguration()));
        } else {
            $this->next->process($request);
        }
    }
}