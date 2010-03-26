<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>yoyocase.net</title>
<link rel="stylesheet" type="text/css" href="/res/main.css"/>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
</head>
<body>
<div id="container">

<div id="header">
<?php if (isset($this->session) && $this->session->userdata('logged_in')): ?>
 <h1><a href="/account">yoyocase.net</a></h1>
<?php else: ?>
 <h1><a href="/">yoyocase.net</a></h1>
<?php endif; ?>
</div>

<div id="nav">
 <ul class="rounded">
 <?php if (isset($this->session) && $this->session->userdata('logged_in')): ?>
  <li><a href="/yoyos">collection</a></li>
  <li><a href="/preferences">preferences</a></li>
  <?php if ($this->session->userdata('is_admin')): echo '<li><a href="/admin">site admin</a></li>'; endif; ?>
  <li><a href="/logout">logout</a></li>
 <? else: ?>
  <li><a href="/register">register</a></li>
  <li><a href="/galleries">galleries</a></li>
  <li><a href="/login">login</a></li>
 <? endif; ?>
 </ul>
</div>

<div id="content">
<?php if ($this->session->flashdata('err')): ?>
 <div class="err"><?=$this->session->flashdata('err')?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('msg')): ?>
 <div class="msg"><?=$this->session->flashdata('msg')?></div>
<?php endif; ?>

 <?= $content ?>
</div><!-- /#content -->

<div id="footer">
 Copyright &copy; 2009, 2010, Dan Parks. All Rights Reserved.<br/>
 User submissions copyright the submitter. Other content derived from Creative Commons-licensed material.<br/>
 <br/>
 <a href="/credits">Credits</a> |
 <a href="/privacy">Privacy</a> |
 <a href="http://twitter.com/yoyocase" target="_blank">Follow us on Twitter</a>
</div>

</div><!-- /#container -->

<script type="text/javascript">
window.onload = function () {
  if (document.forms && document.forms[0] && document.forms[0].elements[0]) {
    if (document.forms[0].elements[0].type == "text" || document.forms[0].elements[0].type == "password") {
      document.forms[0].elements[0].focus();
    }
  }
};
</script>

</body>
</html>

