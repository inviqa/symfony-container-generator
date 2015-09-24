<?php
namespace ContainerTools\Container\Build\Handler;

use ContainerTools\Container\Build\Handler;
use ContainerTools\Container\Build\Request;
use ContainerTools\Container\Filesystem;
use ContainerTools\Container\Loader as ContainerLoader;

class ContainerAlreadyBuiltHandler extends Handler
{
    /**
     * @var ContainerLoader
     */
    private $containerLoader;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * ContainerAlreadyBuiltHandler constructor.
     */
    public function __construct(ContainerLoader $containerLoader, Filesystem $filesystem)
    {
        $this->containerLoader = $containerLoader;
        $this->filesystem = $filesystem;
    }

    public function process(Request $request)
    {
        $containerFilePath = $request->getConfiguration()->getContainerFilePath();

        if ($this->filesystem->exists($containerFilePath)) {
            $request->setContainer($this->containerLoader->loadFrom($containerFilePath));
        } else {
            $this->next->process($request);
        }
    }
}
