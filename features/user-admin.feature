Feature: Managing users

  Scenario: I can add a new user
    Given I am logged in as an admin
    And I am on the dashboard
    And I go to menu item "Users"
    When I click on the "Add New" link in the header
    Then I should be on the "Add New User" page

  @javascript @db
  Scenario: I can log in as a user which has been added
    Given there are users:
      | user_login | user_pass | user_email       | role          |
      | test       | test      | test@example.com | author        |
    And I am logged in as a test
    When I go to the dashboard
    Then the toolbar should show I am authenticated as test
