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
    $this->_assertFunFacts($numAccounts, $numYoyos, $numPhotos);

    // When
    $this->get(BASE_URL.'/galleries');
    // Then
    $this->_assertFunFacts($numAccounts, $numYoyos, $numPhotos);
  }

  function _assertFunFacts($numAccounts, $numYoyos, $numPhotos) {
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

  function testRegisterUserWithInternationalChars() {
    // Given
    $this->deleteRecord('users', array('username' => "'Nolsøe'"));
    $this->get(BASE_URL.'/register');
    // When
    $this->setField('username', 'Nolsøe');
    $this->setField('password', 'Password1');
    $this->setField('email', 'testUserN@somewhere.com');
    $this->clickSubmit('Register');
    // Then
    $this->assertText('Welcome, Nolsøe!');

    // When
    $this->get(BASE_URL.'/yoyos/Nolsøe');
    // Then
    $this->assertText('User "Nolsøe" has no yoyos in their collection');
  }

  function testResetPasswordValidations() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'lostPassword');
    $this->get(BASE_URL.'/passwordreset');
    // When
    $this->clickSubmit('Reset password');
    // Then
    $this->assertText('The username field is required');
    $this->assertNoText('Username not found'); // should short-circut validation callback

    // When
    $this->setField('username', 'bogusUsername');
    $this->clickSubmit('Reset password');
    // Then
    $this->assertText('Username not found');
  }

  function testResetPasswordEmailConfirmationMessage() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'lostPassword');
    $this->get(BASE_URL.'/passwordreset');
    // When
    $this->setField('username', 'testUser1');
    $this->clickSubmit('Reset password');
    // Then
    $this->assertText('An email with instructions on resetting your password has been sent to your registered email address.');
  }

  // TODO: unit test for email from/to/subject/message contents

  function testUserResetsPasswordWithTemporaryTokenFromEmail() {
  }

  function testViewGalleryLinksShouldHaveUrlEncodedLinks() {
    // Given
    $this->deleteRecord('users', array('username' => "'drumma/yoyo'"));
    $this->get(BASE_URL.'/register');
    // When
    $this->setField('username', 'drumma/yoyo');
    $this->setField('password', 'Password1');
    $this->setField('email', 'testUserDY@somewhere.com');
    $this->clickSubmit('Register');
    // Then
    $this->assertText('Welcome, drumma/yoyo!');

    // When
    $this->clickLink('collection');
    // Then
    $this->assertPattern('/\/yoyos\/drumma%2Fyoyo/');

    // When
    $this->clickLink('yoyocase.net');
    $this->clickLink('all galleries');
    // Then
    $this->assertPattern('/\/yoyos\/drumma%2Fyoyo/');
  }

  function testViewUserListForGalleries() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->insertRecord('yoyos', array('user_id' => $userid, 'model_name' => "'Freehand Zero'"));
    $this->insertRecord('photos', array('yoyo_id' => $yoyoid, 'url' => "'http://somewhere.com/photo.jpg'"));
    // When
    $this->get(BASE_URL.'/galleries');
    // Then
    $this->assertLink('testUser1');
    $this->assertText("testUser1 (1 yo's, 1 pics)");
  }
}

