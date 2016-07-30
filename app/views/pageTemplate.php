<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="description" content="yoyocase.net is a website where you can show off and manage your yo-yo collection"/>
<meta name="keywords" content="yo-yo,yoyo,collecting,collection,website,site,software"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title><?php if (isset($title)) echo "$title - "; ?>yoyocase.net</title>
<link rel="stylesheet" type="text/css" href="/res/normalize-4.1.1.min.css"/>
<link rel="stylesheet" type="text/css" href="/res/skeleton-framework-1.0.8.min.css"/>
<link rel="stylesheet" type="text/css" href="/res/new.css"/>
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
</head>
<body>

<div class="container">
  <div class="row">
    <div class="four columns">&nbsp;</div>
    <div class="seven columns offset-by-one">

      <header class="content">
        <?php if ($this->session->userdata('logged_in')): ?>
          <h1><a href="/account">yoyocase.net</a></h1>
        <?php else: ?>
          <h1><a href="/">yoyocase.net</a></h1>
        <?php endif; ?>

        <?php if ($this->session->userdata('impersonating')): ?>
          <div class="impersonating">Impersonating: <?=$this->session->userdata('username')?></div>
        <?php endif; ?>
      </header>

    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="four columns">

      <nav class="content">
        <ul>
          <?php if ($this->session->userdata('logged_in')): ?>
            <li><a href="/yoyos" class="lsf-icon collection">collection</a></li>
            <li><a href="/preferences" class="lsf-icon preferences">preferences</a></li>
            <?php if ($this->session->userdata('is_admin')): echo '<li><a href="/admin" class="lsf-icon admin">site admin</a></li>'; endif; ?>
            <li><a href="/galleries" class="lsf-icon galleries">galleries</a></li>
            <li><a href="/logout" class="lsf-icon logout">logout</a></li>
          <?php else: ?>
            <li><a href="/register" class="lsf-icon register">register</a></li>
            <li><a href="/galleries" class="lsf-icon galleries">galleries</a></li>
            <li><a href="/login" class="lsf-icon login">login</a></li>
          <?php endif; ?>
        </ul>
      </nav>

      <footer class="content">
        Copyright &copy; 2009-<?=date("Y")?>, <a href="http://www.eastofcleveland.com" target="_blank">Dan Parks</a>. All Rights Reserved.<br/>
        User submissions copyright the submitter. Some other content derived from Creative Commons-licensed material.<br/>
        <br/>
        <ul>
          <li><a href="/credits">credits</a></li>
          <li><a href="/tos">terms of service</a></li>
          <li><a href="/privacy">privacy</a></li>
          <li><a href="http://twitter.com/yoyocase" class="lsf-icon twitter" target="_blank"></a></li>
        </ul>
      </footer>

    </div>
    <div class="seven columns offset-by-one">

      <div class="content">
        <?php $this->load->view('site/newUserLink'); ?>

        <?php if ($this->session->flashdata('err')): ?>
          <div><?=$this->session->flashdata('err')?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('msg')): ?>
          <div><?=$this->session->flashdata('msg')?></div>
        <?php endif; ?>

        <?= $content ?>
      </div>

    </div>
  </div>
</div>

</body>
</html>
