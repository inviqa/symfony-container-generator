<?php

namespace ContainerTools\Container\Build;

use Behat\Testwork\ServiceContainer\ContainerLoader;
use ContainerTools\Container\Compiler;

class DebugModeHandler extends Handler
{
    /**
     * @var ContainerLoader
     */
    private $compiler;

    /**
     * DebugModeHandler constructor.
     * @param ContainerLoader $containerLoader
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