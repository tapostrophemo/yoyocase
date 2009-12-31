Feature: Security during user operations on yoyo collections
  In order to preserve peace and happiness amongst users of the site
  Registered users will not be allowed to alter yoyos belonging to other users

  Background:
    Given a user exists with username: "testUser1", password: "Password1"
    And a user exists with username: "testUser2", password: "Password2", email: "testUser2@somewhere.com"
    And a yoyo exists with manufacturer: "Duncan", model_name: "FHZ", user_id: 2

  Scenario: Cannot view edit screen for non-owned yoyo
    Given I am logged in with username: "testUser1", password: "Password1"
    When I go to Edit yoyo 1
    Then I should see "You cannot edit another user's yoyos"

# TODO: finish this validation feature
#  Scenario: Cannot save updates for non-owned yoyo
#    Given I am logged in with username: "testUser1", password: "Password1"
#    When I post to Save yoyo 1
#    Then I should see "You cannot update another user's yoyos"

