<?php

class UsersTestCase extends MY_WebTestCase
{
  function testMainUserPage() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    // When
    $this->logInAs('testUser1', 'Password1');
    // Then
    $this->assertLink('your gallery');
    $this->assertLink('all galleries');

    // When
    $this->clickLink('collection');
    // Then
    $this->assertLink('view gallery');
  }

  function testAccountSettings() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    // When
    $this->clickLink('preferences');
    // Then
    $this->assertText('Username');
    $this->assertText('testUser1');
    $this->assertText('Email');
    $this->assertField('email', 'testUser1@somewhere.com');
    $this->assertField('password', '');
    $this->assertField('passconf', '');

    // When
    $this->setField('password', 'asdf');
    $this->setField('passconf', 'asdf');
    $this->setField('email', 'testUser1@elsewhere.com');
    $this->clickSubmit('Update');
    // Then
    $this->assertText('Preferences updated.');
    // When
    $this->clickLink('preferences');
    // Then
    $this->assertField('email', 'testUser1@elsewhere.com');

    // When
    $this->clickLink('logout');
    $this->logInAs('testUser1', 'asdf');
    // Then
    $this->assertText('Welcome back!');

    // When
    $this->clickLink('preferences');
    $this->setField('password', 'asdfx');
    $this->setField('passconf', 'asdf');
    $this->clickSubmit('Update');
    // Then
    $this->assertText('The confirm password field does not match the password field.');

    // When
    $this->createUser('testUserX', 'testUserX@somewhere.com', 'Password1');
    $this->clickLink('preferences');
    $this->setField('email', 'testUserX@somewhere.com');
    $this->clickSubmit('Update');
    // Then
    $this->assertText('Email has already been taken.');
    $this->assertField('email', 'testUser1@elsewhere.com');
  }

  function testPhotoIntegrationSettings() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->updateRecord('users', 'flickr_userid', "'1234@4321'", 'username', "'testUser1'");
    $this->updateRecord('users', 'photobucket_username', "'ralph'", 'username', "'testUser1'");
    $this->logInAs('testUser1', 'Password1');
    // When
    $this->clickLink('preferences');
    // Then
    $this->assertText('flickr user id: 1234@4321');
    $this->assertText('Photobucket username: ralph');
  }
}

