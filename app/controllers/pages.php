<?php

class Pages extends Controller
{
  function index() { $this->_view('home'); }

  function privacy() { $this->_view('privacy'); }

  function tos() { $this->_view('tos'); }

  function credits() { $this->_view('credits'); }

  function tour() { $this->_view('tour'); }

  function _view($page) {
    $this->load->view('pageTemplate', array('title' => 'online yoyo collection software', 'content' => $this->load->view("pages/$page", null, true)));
  }
}

