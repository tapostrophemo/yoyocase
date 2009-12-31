Feature: User account/preferences management
  In order to participate as a user on the site
  Registered users will need to be able to modify their account preferences

  Background:
    Given a user exists with username: "testUser1", password: "Password1", email: "testUser1@example.com"
    And I am on the home page

  Scenario: Preferences link in navbar exists
    Given I am logged in with username: "testUser1", password: "Password1"
    When I follow "preferences"
    Then I should see "Edit preferences"

  Scenario: Update email
    Given I am logged in with username: "testUser1", password: "Password1"
    And I follow "preferences"
    And I fill in "Email" with "newAddress_testUser1@example.com"
    When I press "Update"
    Then user should exist with username: "testUser1", email: "newAddress_testUser1@example.com"
    And I should see "Preferences updated successfully"

  Scenario: Attempt to use an existing email address fails
    Given a user exists with username: "testUser2", password: "Password2", email: "foo@bar.com"
    And I am logged in with username: "testUser1", password: "Password1"
    When I follow "preferences"
    And I fill in "Email" with "foo@bar.com"
    And I press "Update"
    Then user should not exist with username: "testUser1", email: "foo@bar.com"
    And I should see "Email has already been taken"

  Scenario: User begins flickr account verification
    Given I am logged in with username: "testUser1", password: "Password1"
    And I follow "preferences"
    When I press "Verify flickr account"
    Then I should be redirected to the flickr auth page

# TODO: figure out out to fake this out; until then, no automated test, manual test only
#  Scenario: flickr completes account verification
#    Given I am logged in with username: "testUser1", password: "Password1"
#    And a user should not exist with username: "testUser1", flickr_userid: "foobarbaz"
#    When flickr redirects to my flickr callback path
#    Then a user should exist with username: "testUser1", flickr_userid: "foobarbaz"

