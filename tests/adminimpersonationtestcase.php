<?php

class AdminImpersonationTestCase extends MY_WebTestCase
{
  function testAdminCanImpersonateOtherAccounts() {
    // Given
    $this->createAdminUser('testAdmin1', 'testAdmin1@somewhere.com', 'AdminPassword1');
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testAdmin1', 'AdminPassword1');
    // When
    $this->clickLink('site admin');
    $this->clickLink('user accounts');
    $this->clickLink('testUser1');
    $this->clickLink('impersonate');
    // Then
    $this->assertText('Impersonating: testUser1');

    // When
    $this->clickLink('account preferences');
    // Then
    $this->assertField('email', 'testUser1@somewhere.com');
  }

  function testAdminCannotImpersonateOrDeleteSelf() {
    // Given
    $this->createAdminUser('testAdmin1', 'testAdmin1@somewhere.com', 'AdminPassword1');
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testAdmin1', 'AdminPassword1');
    // When
    $this->clickLink('site admin');
    $this->clickLink('user accounts');
    $this->clickLink('testAdmin1');
    // Then
    $this->assertNoLink('impersonate');
    $this->assertNoLink('delete');
    // TODO: verify is safe from URL tampering to get to impersonate|delete paths
  }
}

