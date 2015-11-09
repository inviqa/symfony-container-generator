Feature: Generating, building and caching a container

  Scenario: Generating container and meta files in debug mode
    Given I am in debug mode
    And the cached container file does not exist
    When I generate the container
    Then I should receive an instance of that container
    And it should be cached in a file with a meta file

  Scenario: Regenerating container and meta files in debug mode when resource has changed
    Given I am in debug mode
    And the cached container and meta files have already been generated
    And I have modified one of the resources
    When I generate the container
    Then I should receive an instance of the regenerated container

  Scenario: Not regenerating container and meta files in debug mode if resource not changed
    Given I am in debug mode
    And the cached container and meta files have already been generated from preexisting
    When I generate the container
    Then I should receive an instance of the existing container

  Scenario: Generating and caching a container when not in debug mode
    Given I am not in debug mode
    And the cached container file does not exist
    When I generate the container
    Then I should receive an instance of that container
    And it should be cached in a file

  Scenario: Not generating a new container when a cached one exists
    Given I am not in debug mode
    And the cached container file already exists
    When I generate the container
    Then I should receive an instance of the existing container

