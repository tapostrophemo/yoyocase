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

<nav class="drawer main-column-only">
  <?php $this->load->view('site/_navContent'); ?>
</nav>

<input type="checkbox" id="drawer-trigger" class="drawer-trigger main-column-only"/>
<label for="drawer-trigger" class="main-column-only"></label>

<div class="container">
  <div class="row">
    <div class="four columns">&nbsp;</div>
    <div class="seven columns offset-by-one">

      <header class="content">
        <?php $homeUrl = $this->session->userdata('logged_in') ? "/account" : "/"; ?>
        <h1 class="u-text-center"><a href="<?php echo $homeUrl; ?>"><img src="/res/logo.jpg" alt="yoyocase.net"/></a></h1>

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

      <nav class="content sidebar-only">
        <?php $this->load->view('site/_navContent'); ?>
      </nav>

      <footer class="content sidebar-only">
        <?php $this->load->view('site/_footerContent'); ?>
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

      <footer class="content main-column-only">
        <?php $this->load->view('site/_footerContent'); ?>
      </footer>
    </div>
  </div>
</div>

</body>
</html>
