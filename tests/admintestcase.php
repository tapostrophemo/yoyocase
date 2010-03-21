<?php

class AdminTestCase extends MY_WebTestCase
{
/*  Scenario: an administrator can view other accounts
    Given I am logged in with username: "testAdmin1", password: "Password1"
    And a user exists with username: "testUser2", password: "Password2", email: "testUser2@example.com"
    And user: 2 last logged in at: "2010-01-02 15:06:07"
    When I follow "site admin"
    And I follow "user accounts"
    Then I should see the following accounts:
      | Username   | Registered on | Last login          |
      | testAdmin1 | today         | today               |
      | testUser2  | today         | 2010-01-02 15:06:07 |
*/

  function testAdminCanViewOtherAccounts() {
    // Given
    $this->createAdminUser('testAdmin1', 'testAdmin1@somewhere.com', 'AdminPassword1');
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testAdmin1', 'AdminPassword1');
    // When
    $this->clickLink('site admin');
    $this->clickLink('user accounts');
    // Then
    $this->assertText('Username Registered on Last login Email');
    // TODO: verify registered_on/last_login timestamps
    $this->assertText('testAdmin1@somewhere.com');
    $this->assertText('testUser1@somewhere.com');

    // When
    $yoyoid = $this->insertRecord('yoyos', array('user_id' => $userid, 'model_name' => "'Freehand Zero'"));
    $this->clickLink('(refresh)');
    // Then
    $this->assertText("testUser1 (1 yo's, 0 pics)");

    // When
    $this->insertRecord('photos', array('yoyo_id' => $yoyoid, 'url' => "'http://somewhere.com/photo.jpg'"));
    $this->clickLink('(refresh)');
    // Then
    $this->assertText("testUser1 (1 yo's, 1 pics)");
  }
}

