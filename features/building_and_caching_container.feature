Feature: Generating, building and caching a container


  Scenario: Not caching the container when in debug mode
    Given I am in debug mode
    And the cached container file does not exist
    When I generate the container
    Then I should receive an instance of that container
    But it should not be cached in a file

  Scenario: Generating and caching a container when not in debug mode
    Given I am not in debug mode
    And the cached container file does not exist
    When I generate the container
    Then I should receive an instance of that container
    And it should be cached in a file

  Scenario: Not generating a new container when a chached one exists
    Given I am not in debug mode
    And the cached container file already exists
    When I generate the container
    Then I should reiceve an instance of the existing container
