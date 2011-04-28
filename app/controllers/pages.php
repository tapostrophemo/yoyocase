<?php

class Pages extends CI_Controller
{
  function index() {
    $this->load->model('Misc');
    $this->_view('home');
  }

  function privacy() { $this->_view('privacy'); }

  function tos() { $this->_view('tos'); }

  function credits() { $this->_view('credits'); }

  function tour() { $this->_view('tour'); }

  function help() { $this->_view('help'); }

  function fourYoFour() { $this->_view('404'); }

  function _view($page) {
    $this->load->view('pageTemplate', array('title' => 'online yoyo collection software', 'content' => $this->load->view("pages/$page", null, true)));
  }
}

