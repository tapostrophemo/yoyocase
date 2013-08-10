<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="description" content="yoyocase.net is a website where you can show off and manage your yo-yo collection"/>
<meta name="keywords" content="yo-yo,yoyo,collecting,collection,website,site,software"/>
<title><?php if (isset($title)) echo "$title - "; ?>yoyocase.net</title>
<link rel="stylesheet" type="text/css" href="/res/main.css"/>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
</head>
<body>

<div id="container">

<div id="header">
<?php if ($this->session->userdata('logged_in')): ?>
 <h1><a href="/account">yoyocase.net</a></h1>
<?php else: ?>
 <h1><a href="/">yoyocase.net</a></h1>
<?php endif; ?>
<?php if ($this->session->userdata('impersonating')): ?>
<div id="impersonating">Impersonating: <?=$this->session->userdata('username')?></div>
<?php endif; ?>
</div>

<div id="nav">
 <ul class="rounded">
 <?php if ($this->session->userdata('logged_in')): ?>
  <li><a href="/yoyos" class="lsf-icon collection">collection</a></li>
  <li><a href="/preferences" class="lsf-icon preferences">preferences</a></li>
  <?php if ($this->session->userdata('is_admin')): echo '<li><a href="/admin" class="lsf-icon admin">site admin</a></li>'; endif; ?>
  <li><a href="/galleries" class="lsf-icon galleries">galleries</a></li>
  <li><a href="/logout" class="lsf-icon logout">logout</a></li>
 <? else: ?>
  <li><a href="/register" class="lsf-icon register">register</a></li>
  <li><a href="/galleries" class="lsf-icon galleries">galleries</a></li>
  <li><a href="/login" class="lsf-icon login">login</a></li>
 <? endif; ?>
 </ul>
</div>

<div id="content">

<?php $this->load->view('site/newUserLink'); ?>

<?php if ($this->session->flashdata('err')): ?>
 <div class="err miniRound"><?=$this->session->flashdata('err')?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('msg')): ?>
 <div class="msg miniRound"><?=$this->session->flashdata('msg')?></div>
<?php endif; ?>

 <?= $content ?>
</div><!-- /#content -->

<div id="footer">
 Copyright &copy; 2009-<?=date("Y")?>, <a href="http://www.eastofcleveland.com" target="_blank">Dan Parks</a>. All Rights Reserved.<br/>
 User submissions copyright the submitter. Some other content derived from Creative Commons-licensed material.<br/>
 <br/>
 <a href="/credits">credits</a> |
 <a href="/tos">terms of service</a> |
 <a href="/privacy">privacy</a> |
 <a href="http://twitter.com/yoyocase" target="_blank">follow us on Twitter</a>
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

