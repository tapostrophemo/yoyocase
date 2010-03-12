<?php

class SiteTestCase extends MY_WebTestCase
{
  function testHomepage() {
    // Given..When
    $this->assertTrue($this->get(BASE_URL));
    // Then
    $this->assertLink('register');
    $this->assertLink('galleries');
    $this->assertLink('login');
    $this->assertNoLink('collection');
    $this->assertNoLink('preferences');
    $this->assertNoLink('site admin');
    $this->assertNoLink('logout');
  }

  function testSiteFunFacts() {
    // Given
    $numAccounts = $this->countRecords('users');
    $numYoyos = $this->countRecords('yoyos');
    $numPhotos = $this->countRecords('photos');
    // When
    $this->get(BASE_URL.'/register');
    // Then
    $this->assertText("$numAccounts user accounts");
    $this->assertText("$numYoyos yoyos");
    $this->assertText("$numPhotos photos");
  }

  function testRegisterNewUser() {
    // Given
    $this->deleteRecord('users', array('username' => "'testUser1'", 'email' => "'testUser1@somewhere.com'"));
    $this->get(BASE_URL.'/register');
    // When
    $this->setField('username', 'testUser1');
    $this->setField('password', 'Password1');
    $this->setField('email', 'testUser1@somewhere.com');
    $this->clickSubmit('Register');
    // Then
    $this->assertText('Welcome, testUser1!');
    $this->assertNoLink('register');
    $this->assertNoLink('site admin');
    $this->assertNoLink('login');
    $this->assertLink('collection');
    $this->assertLink('preferences');
    $this->assertLink('logout');

    $this->clickLink('logout');

    // Given
    $this->clickLink('register');
    // When
    $this->setField('username', 'testUser1');
    $this->setField('password', 'Password1');
    $this->setField('email', 'testUser1@somewhere.com');
    $this->clickSubmit('Register');
    // Then
    $this->assertText('Username has already been taken');
  }

  function testViewUserListForGalleries() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    // When
    $this->get(BASE_URL.'/galleries');
    // Then
    $this->assertLink('testUser1');
  }
}

