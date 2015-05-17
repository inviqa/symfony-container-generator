<?php

namespace ContainerTools;

class Configuration
{
    /**
     * @var string
     */
    private $containerFilePath;

    /**
     * @var array
     */
    private $servicesFolders;

    /**
     * @var boolean
     */
    private $debug;

    /**
     * @var string
     */
    private $servicesFormat;

    /**
     * @param string $containerFilePath
     * @param array $servicesFolders
     * @param boolean $debug
     * @param string $servicesFormat
     */
    private function __construct($containerFilePath, array $servicesFolders, $debug, $servicesFormat)
    {
        $this->containerFilePath = $containerFilePath;
        $this->servicesFolders = $servicesFolders;
        $this->debug = $debug;
        $this->servicesFormat = $servicesFormat;
    }

    /**
     * @param $containerFilePath
     * @param array $configurationFolders
     * @param $debug
     * @param $servicesFormat
     * @return Configuration
     */
    public static function fromParameters($containerFilePath, array $configurationFolders, $debug, $servicesFormat)
    {
        return new Configuration($containerFilePath, $configurationFolders, $debug, $servicesFormat);
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

    /**
     * @return string
     */
    public function getServicesFormat()
    {
        return $this->servicesFormat;
    }
}