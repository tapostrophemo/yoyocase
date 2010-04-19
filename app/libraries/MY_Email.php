<?php

/**
 * class MY_Email: overrides default CI_Email 'send' behavior to allow for unit testing; provides
 * useful accessor methods to be used in unit testing
 */
class MY_Email extends CI_Email
{
  var $_in_test_mode = false;
  var $prod_test = false;
  var $_test_write_dir = '';
  var $_test_file_prefix = '';

  function send($testRetval = true) {
    if ($this->_in_test_mode) {
      return $testRetval;
    }

    return parent::send();
  }

  function inTestMode() { return $this->_in_test_mode; }

  function isProdTest() { return $this->prod_test; }

  function getTo() { return $this->_headers['To']; }

  function getFrom() { return $this->_headers['From']; }

  function getReplyTo() { return $this->_headers['Reply-To']; }

  function getSubject() { return $this->_headers['Subject']; }

  function getBody() { return $this->_body; }
}

