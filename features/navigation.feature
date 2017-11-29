Feature: Navigation around types of WordPress front-end screen.

  @db @javascript
  Scenario: Access author archive
    Given there are users:
      | user_login | user_pass | user_email       | role   |
      | test       | test      | test@example.com | author |
    And there are posts:
      | post_title      | post_content              | post_status | post_author |
      | Admin article   | The content of my article | publish     | admin       |
      | Testing article | The content of my article | publish     | test        |
    When I am viewing posts published by admin
    Then I should see "Admin article"
    And I should not see "Testing article"
