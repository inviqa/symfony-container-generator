<?php

namespace ContainerTools;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

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
     * @var array CompilerPassInterface
     */
    private $compilerPasses = array();

    /**
     * @var string
     */
    private $environment;

    /**
     * @param string $containerFilePath
     * @param array $servicesFolders
     * @param boolean $debug
     * @param string $servicesFormat
     * @param string $environment
     */
    private function __construct($containerFilePath, array $servicesFolders, $debug, $servicesFormat, $environment = "prod")
    {
        $this->containerFilePath = $containerFilePath;
        $this->servicesFolders = $servicesFolders;
        $this->debug = $debug;
        $this->servicesFormat = $servicesFormat;
        $this->environment = $environment;
    }

    /**
     * @param $containerFilePath
     * @param array $configurationFolders
     * @param boolean $debug
     * @param string $servicesFormat
     * @param string $environment
     * @return Configuration
     */
    public static function fromParameters($containerFilePath, array $configurationFolders, $debug, $servicesFormat, $environment = "prod")
    {
        return new Configuration($containerFilePath, $configurationFolders, $debug, $servicesFormat, $environment);
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

    /**
     * Return the current environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * This method here is for backwards capability and should not be used
     *
     * @param bool $isTestEnv
     * @deprecated
     */
    public function setTestEnvironment($isTestEnv)
    {
        if($isTestEnv === true)
        {
            $this->environment = "test";
        }
    }

    /**
     * @return array CompilerPassInterface
     */
    public function getCompilerPasses()
    {
        return $this->compilerPasses;
    }

    /**
     * @param CompilerPassInterface $compilerPass
     */
    public function addCompilerPass(CompilerPassInterface $compilerPass)
    {
        $this->compilerPasses[] = $compilerPass;
    }
}
