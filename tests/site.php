<?php

class SiteTestCase extends WebTestCase
{
  function testHomepage() {
    $this->assertTrue($this->get(BASE_URL));
    $this->assertLink('register');
    $this->assertLink('login');
    $this->assertNoLink('collection');
    $this->assertNoLink('preferences');
    $this->assertNoLink('site admin');
    $this->assertNoLink('logout');
  }
}

