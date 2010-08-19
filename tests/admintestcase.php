<?php

class AdminTestCase extends MY_WebTestCase
{
  function testAdminCanViewOtherAccounts() {
    // Given
    $this->createAdminUser('testAdmin1', 'testAdmin1@somewhere.com', 'AdminPassword1');
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testAdmin1', 'AdminPassword1');
    // When
    $this->clickLink('site admin');
    $this->clickLink('user accounts');
    // Then
    $this->assertText('Username Registered on Last login');
    $this->assertNoText('Email');
    $this->assertLink('testAdmin1');
    $this->assertLink('testUser1');
    // TODO: verify registered_on/last_login timestamps
    $this->assertNoText('testAdmin1@somewhere.com');
    $this->assertNoText('testUser1@somewhere.com');

    // When
    $this->get("/admin/userDetail/$userid");
    // Then
    $this->assertText('testUser1@somewhere.com');

    // When
    $this->back();
    $yoyoid = $this->createYoyo($userid, 'Freehand Zero');
    $this->clickLink('(refresh)');
    // Then
    $this->assertText("testUser1 (1y / 0p)");

    // When
    $this->createPhoto($yoyoid, 'http://somewhere.com/photo.jpg');
    $this->clickLink('(refresh)');
    // Then
    $this->assertText("testUser1 (1y / 1p)");
  }
}

