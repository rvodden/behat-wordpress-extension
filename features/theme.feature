Feature: Managing themes

  Background:
    Given I am logged in as an admin

  @db
  Scenario: Activate a plugin
    When I switch the theme to "twentysixteen"
    And I am on the dashboard
    Then I should see "Twenty Sixteen" in the "#wp-version a" element
