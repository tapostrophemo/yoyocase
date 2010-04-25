<?php

class CronTestCase extends MY_WebTestCase
{
  function testShouldExpirePasswordResetTokens() {
    // Given..When
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'lostPassword');
    // Then
    $this->assertNoRecord('user_pw_reset', 'user_id', $userid);

    // When
    $this->get(BASE_URL.'/cron/expirePasswordResetTokens');
    // Then
    $this->assertText('expirePasswordResetTokens completed');
    $this->assertNoRecord('user_pw_reset', 'user_id', $userid);

    // When
    $this->get(BASE_URL.'/passwordreset');
    $this->setField('username', 'testUser1');
    $this->clickSubmit('Reset password');
    $tokenPath = $this->getResetTokenPath();
    $this->get(BASE_URL.'/cron/expirePasswordResetTokens');
    // Then
    $this->assertRecord('user_pw_reset', 'user_id', $userid);

    // When
    $this->get(BASE_URL.'/cron/expirePasswordResetTokens');
    $this->get(BASE_URL.$tokenPath);
    // Then
    $this->assertNoRecord('user_pw_reset', 'user_id', $userid);
    $this->assertText('That password reset request is invalid or has expired');
  }
}

