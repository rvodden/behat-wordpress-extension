@db
Feature: Plugins

  Scenario: Activate a plugin
    When I activate the "hello" plugin
    And I am on the dashboard
    Then I should see a "#dolly" element

  Scenario: Deactivate a plugin
    Given the "hello" plugin is active
    When I deactivate the "hello" plugin
    And I am on the dashboard
    Then I should not see a "#dolly" element
