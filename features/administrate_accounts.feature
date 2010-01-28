@admin
Feature: Administer accounts
  In order to administer the sites
  Administrators will need to be able to view, lock, unlock, and delete user accounts

  Background:
    Given a user exists with username: "testAdmin1", password: "Password1", is_admin: true

  Scenario: an administrator can view his/her own account
    Given I am logged in with username: "testAdmin1", password: "Password1"
    And user: 1 was created at: "2010-01-02 15:06:07"
    When I follow "site admin"
    Then I should see "Site Administration"
    When I follow "user accounts"
    Then I should see the following accounts:
      | Username   | Registered on       | Last login |
      | testAdmin1 | 2010-01-02 15:06:07 | today      |

  Scenario: an administrator can view other accounts
    Given I am logged in with username: "testAdmin1", password: "Password1"
    And a user exists with username: "testUser2", password: "Password2", email: "testUser2@example.com"
    And user: 2 last logged in at: "2010-01-02 15:06:07"
    When I follow "site admin"
    And I follow "user accounts"
    Then I should see the following accounts:
      | Username   | Registered on | Last login          |
      | testAdmin1 | today         | today               |
      | testUser2  | today         | 2010-01-02 15:06:07 |

