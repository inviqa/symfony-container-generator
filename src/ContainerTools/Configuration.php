<?php

namespace ContainerTools;

class Configuration
{
    private $containerFilePath;
    private $servicesFolders;
    private $debug;

    private function __construct($containerFilePath, $servicesFolders, $debug)
    {
        $this->containerFilePath = $containerFilePath;
        $this->servicesFolders = $servicesFolders;
        $this->debug = $debug;
    }

    public static function fromParameters($containerFilePath, array $configurationFolders, $debug)
    {
        return new Configuration($containerFilePath, $configurationFolders, $debug);
    }

    /**
     * @return string
     */
    public function getContainerFilePath()
    {
        return $this->containerFilePath;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @return ARRAY
     */
    public function getServicesFolders()
    {
        return $this->servicesFolders;
    }
}