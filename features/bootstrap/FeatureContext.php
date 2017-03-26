<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use ContainerTools\Configuration;
use Symfony\Component\DependencyInjection\Container;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Container
     */
    private $generatedServices;

    /**
     * @var string
     */
    private $cachedContainerFile = 'container.cache.php';
    private $cachedContainerMetaFile = 'container.cache.php.meta';

    /**
     * @var Exception
     */
    private $generatorException;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @beforeScenario
     */
    public function clearCachedContainer()
    {
        if (file_exists($this->cachedContainerFile)) {
            unlink($this->cachedContainerFile);
        }

        if (file_exists($this->cachedContainerMetaFile)) {
            unlink($this->cachedContainerMetaFile);
        }

        if (file_exists('features/etc/services_test.xml')) {
            unlink('features/etc/services_test.xml');
        }

        copy('features/dummy/services.xml', 'features/etc/services.xml');
    }

    /**
     * @Given I am in debug mode
     */
    public function iAmInDebugMode()
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/etc/'], true, 'xml');
    }

    /**
     * @When I generate the container
     */
    public function iGenerateTheContainer()
    {
        $serviceFolders = implode(',', $this->configuration->getServicesFolders());
        $isDebug = $this->configuration->getDebug() ? 'true' : 'false';
        $env = $this->configuration->getEnvironment();
        exec('php features/bootstrap/generate.php ' . $serviceFolders . ' '. $isDebug. ' '. $env);

        $this->generatedServices = unserialize(file_get_contents('serialized.container'));
    }

    /**
     * @Then I should receive an instance of that container
     */
    public function iShouldReceiveAnInstanceOfThatContainer()
    {
        $this->assertHasService('my_service1');
        $this->assertNotHasService('preexisting');
    }

    /**
     * @Then I should receive an instance of the regenerated container
     */
    public function iShouldReceiveAnInstanceOfTheRegeneratedServices()
    {
        $this->assertHasService('my_modified_service1');
    }

    /**
     * @Then it should not be cached in a file
     */
    public function itShouldNotBeCachedInAFile()
    {
        if (file_exists($this->cachedContainerFile)) {
            throw new RuntimeException(sprintf('Cache file %s should not have been created.', $this->cachedContainerFile));
        }
    }

    /**
     * @Given the cached container file does not exist
     */
    public function theCachedContainerFileDoesNotExist()
    {
        if (file_exists($this->cachedContainerFile)) {
            throw new RuntimeException('Cached container file should not exist prior to scenario');
        }

        if (file_exists($this->cachedContainerMetaFile)) {
            throw new RuntimeException('Cached container meta file should not exist prior to scenario');
        }
    }

    /**
     * @Given I am not in debug mode
     */
    public function iAmNotInDebugMode()
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/etc/'], false, 'xml');
    }

    /**
     * @Then it should be cached in a file
     */
    public function itShouldBeCachedInAFile()
    {
        if (!file_exists($this->cachedContainerFile)) {
            throw new RuntimeException(sprintf('Cache file %s should have been created.', $this->cachedContainerFile));
        }
    }

    /**
     * @Then it should be cached in a file with a meta file
     */
    public function itShouldBeCachedInAFileWithAMetaFile()
    {
        $this->itShouldBeCachedInAFile();

        if (!file_exists($this->cachedContainerMetaFile)) {
            throw new RuntimeException(sprintf('Cache meta file %s should have been created.', $this->cachedContainerMetaFile));
        }
    }

    /**
     * @Given the cached container file already exists
     */
    public function theCachedContainerFileAlreadyExists()
    {
        copy('features/dummy/preexisting.container.cache.php', 'container.cache.php');

        if (!file_exists($this->cachedContainerFile)) {
            throw new RuntimeException(sprintf('Expected cached container file %s to exist.', $this->cachedContainerFile));
        }
    }

    /**
     * @Given the cached container and meta files have already been generated
     */
    public function theCachedContainerAndMetaFilesHaveAlreadyBeenGenerated()
    {
        $this->iGenerateTheContainer();
    }

    /**
     * @Given the cached container and meta files have already been generated from preexisting
     */
    public function theCachedContainerAndMetaFilesHaveAlreadyBeenGeneratedFromPreexisting()
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/preexisting/'], true, 'xml');

        $this->iGenerateTheContainer();
    }

    /**
     * @Then I should receive an instance of the existing container
     */
    public function iShouldReceiveAnInstanceOfTheExistingContainer()
    {

        $this->assertHasService('preexisting');
    }

    /**
     * @Given I have :env environment services
     */
    public function iHaveEnvironmentServices($env)
    {
        copy("features/dummy/services_{$env}.xml", "features/etc/services_{$env}.xml");
    }

    /**
     * @Given the environment is set to :env
     */
    public function theEnvironmentIsSetTo($env)
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/etc/'], true, 'xml', $env);
    }

    /**
     * @Then the :env services should be available
     */
    public function theTestServicesShouldBeAvailable($env)
    {
        $this->assertHasService("{$env}_service");
    }

    /**
     * @Given I have do not have test services
     */
    public function iHaveDoNotHaveTestServices()
    {
        expect(file_exists('features/etc/services_test.xml'))->toBe(false);
    }

    /**
     * @Then an exception should be thrown
     */
    public function anExceptionShouldBeThrown()
    {
        expect($this->generatorException)->toBeAnInstanceOf(Exception::class);
    }

    /**
     * @Given the test environment is not set
     */
    public function theTestEnvironmentIsNotSet()
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/etc/'], true, 'xml');

        $this->configuration->setTestEnvironment(false);
    }

    /**
     * @Then the test services should not be available
     */
    public function theTestServicesShouldNotBeAvailable()
    {
        $this->assertNotHasService('test_service');
    }

    /**
     * @Given I have configured different services in both :folder1 and :folder2
     */
    public function iHaveConfiguredServicesInBothAnd($folder1, $folder2)
    {
        $folder1 = 'features/'.$folder1;
        $folder2 = 'features/'.$folder2;
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, [$folder1, $folder2], true, 'xml');
    }

    /**
     * @Then it should contain services from both files
     */
    public function itShouldContainServicesFromBothFiles()
    {
        $this->assertHasService('my_service1');
        $this->assertHasService('my_service2');
    }

    /**
     * @Given I have modified one of the resources
     */
    public function iModifyOneOfTheResources()
    {
        sleep(1); // otherwise symfony CacheConfig won't pick up on changed timestamp
        copy('features/dummy/modified_services.xml', 'features/etc/services.xml');
    }

    /**
     * @param $service
     */
    private function assertHasService($service)
    {
        if (!in_array($service, $this->generatedServices)) {
            throw new RuntimeException(sprintf('Expected container to contain %s service', $service));
        }
    }

    /**
     * @param $service
     */
    private function assertNotHasService($service)
    {
        if (in_array($service, $this->generatedServices)) {
            throw new RuntimeException(sprintf('Expected container to not contain %s service', $service));
        }
    }
}
