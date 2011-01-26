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
    $this->assertPattern('<div id="slideshow">');
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
    $this->assertText("$numAccounts users");
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
    $this->assertNoRecord('users', 'perishable_token', "'TODO:'");

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

  function testResetPasswordSendsEmailAndShowsConfirmationMessage() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'lostPassword');
    $this->get(BASE_URL.'/passwordreset');
    // When
    $this->setField('username', 'testUser1');
    $this->clickSubmit('Reset password');
    // Then
    $this->assertText('An email with instructions on resetting your password has been sent to your registered email address.');
    // NB: requires email config to be set to test mode
    $this->assertText('Hi testUser1');
    $this->assertPattern('/http:\/\/dev\.yoyocase\.net\/passreset\/.+/');
  }

  function testResetPasswordFormValidations() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'lostPassword');
    $this->get(BASE_URL.'/passwordreset');
    $this->setField('username', 'testUser1');
    $this->clickSubmit('Reset password');
    // When
    $this->get(BASE_URL.$this->getResetTokenPath());
    $this->clickSubmit('Reset password');
    // Then
    $this->assertText('The password field is required');
    $this->assertText('The confirm password field is required');

    // When
    $this->setField('password', 'Password1');
    $this->setField('passconf', 'xPassword1');
    $this->clickSubmit('Reset password');
    // Then
    $this->assertText('The confirm password field does not match the password field');
  }

  function testShouldUpdateWithNewPassword() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'lostPassword');
    $this->get(BASE_URL.'/passwordreset');
    $this->setField('username', 'testUser1');
    $this->clickSubmit('Reset password');
    // When
    $this->get(BASE_URL.$this->getResetTokenPath());
    $this->setField('password', 'Password1');
    $this->setField('passconf', 'Password1');
    $this->clickSubmit('Reset password');
    // Then
    $this->assertText('Your password has been updated');

    // When
    $this->clickLink('logout');
    $this->logInAs('testUser1', 'Password1');
    // Then
    $this->assertLink('logout');
  }

  function testUserResetsPasswordWithTemporaryTokenFromEmail() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'lostPassword');
    $this->updateRecord('users', 'perishable_token', "'newToken'", 'id', $userid);
    // When
    $this->get(BASE_URL.'/passreset/newToken');
    // Then
    $this->assertText('Please enter a new password');
    $this->assertNoRecord('users', 'perishable_token', "'newToken'"); // each token should be usable only once
  }

  function testInvalidPasswordResetToken() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'lostPassword');
    $this->updateRecord('users', 'perishable_token', "'token1'", 'id', $userid);
    // When
    $this->get(BASE_URL.'/passreset/token2');
    // Then
    $this->assertText("That password reset request is invalid or has expired");
  }
}

